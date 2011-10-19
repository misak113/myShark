<?php

use Kate\Http\UserAgentParser,
    Kate\Http\Cookies;

/**
 * Model který zajišťuje práci s uživateli
 */
class UserModel extends \Kate\Main\Model 
{
    const HASH_KEY = 'key_for_hash_generation';
    const COOKIE_UID = 'user_uid_hash';
    
    const FRONTEND_USER_GROUP_ID = 1;
    const ROBOT_USER_GROUP_ID = 2;
    const ADMIN_USER_GROUP_ID = 2;
    
    private static $defaultUserGroups = array(
        self::FRONTEND_USER_GROUP_ID => array('text' => 'Frontend uživatel', 'parent' => null),
        self::ROBOT_USER_GROUP_ID => array('text' => 'Robot', 'parent' => null),
        self::ADMIN_USER_GROUP_ID => array('text' => 'Administrátor', 'parent' => 1),
    );
    
    private static $permissionsGeneral = array(
        array('type' => 'web', 'operation' => 'display', 'text' => 'Zobrazení webových stránek'),
    );
    
    private $user, $userAgent, $ip, $request, $response, $userInstance = null, $permissions = array();
    
    protected function __construct() {
        parent::__construct();
        $robots = UserAgentParser::getRobots();
        $this->request = \Nette\Environment::getHttpRequest();
        $this->response = \Nette\Environment::getHttpResponse();
        $this->userAgent = $this->request->getHeader('user-agent', reset($robots));
        $this->ip = $this->request->getRemoteAddress();
        
        $this->cache()->alterPermissions();
        $this->cache()->alterUserGroups();
    }
    
    /**
     * Pokud aktuální uživatel neexistuje tak jej vytvoří a naloaduje jej
     */
    public function logUser() {
        
        
        
        if (UserAgentParser::isRobot($this->userAgent)) {
            // @todo Zaloguje příchod robota
        } else {
            $hashCode = $this->request->getCookie(self::COOKIE_UID);
            
            if (!$hashCode) {
                $this->createUserNotExists();
            } else {
                $args = array();
                $args['lastAccessDate'] = new Nette\Database\SqlLiteral('NOW()');
                $args['ip'] = $this->ip;
                $args['userAgent'] = $this->userAgent;
                $args['countLoads'] = new Nette\Database\SqlLiteral('countLoads + 1');
                $rowsAffected = $this->db->table('user')->where('hashCode', $hashCode)->update($args);
                if ($rowsAffected === 0) {
                    $this->createUserNotExists();
                    return;
                }
            }
            
            $this->user = $this->db->table('user')->where('hashCode', $hashCode)->limit(1)->fetch();
        }
    }
    
    private function createUserInDatabase() {
        $hashCode = sha1(self::HASH_KEY . $this->ip . $this->userAgent);
        $args = array();
        $args['id_userGroup'] = $this->getDefaultUserGroupId();
        $args['lastAccessDate'] = new Nette\Database\SqlLiteral('NOW()');
        $args['hashCode'] = $hashCode;
        $args['ip'] = $this->ip;
        $args['userAgent'] = $this->userAgent;
        $args['noCookie'] = true;
        $args['countLoads'] = 0;
        $this->user = $this->db->table('user')->insert($args);
    }
    
    private function createUserNotExists() {
        $hashCode = sha1(self::HASH_KEY . $this->ip . $this->userAgent);
        $this->user = $this->db->table('user')->where('hashCode', $hashCode)->where('noCookie', true)->limit(1)->fetch();
        if ($this->user['id_user'] === null) {
            $this->createUserInDatabase();
            return;
        } else {
            if (Cookies::get()->isEnabled()) {
                $hashCode = sha1(rand() . self::HASH_KEY . time());

                $this->response->setCookie(self::COOKIE_UID, $hashCode, '+10 years', '/');

                $args = array();
                $args['lastAccessDate'] = new Nette\Database\SqlLiteral('NOW()');
                $args['hashCode'] = $hashCode;
                $args['noCookie'] = false;
                $args['ip'] = $this->ip;
                $args['userAgent'] = $this->userAgent;
                $args['countLoads'] = new Nette\Database\SqlLiteral('countLoads + 1');
                $this->db->table('user')->where('id_user', $this->user['id_user'])->update($args);
            } else {
                $args = array();
                $args['lastAccessDate'] = new Nette\Database\SqlLiteral('NOW()');
                $args['ip'] = $this->ip;
                $args['userAgent'] = $this->userAgent;
                $args['countLoads'] = new Nette\Database\SqlLiteral('countLoads + 1');
                $this->db->table('user')->where('id_user', $this->user['id_user'])->update($args);
            }
        }
    }

    /**
     * Vrátí id výchozí skupiny pro uživatele který přistoupí
     */
    public function getDefaultUserGroupId() {
        return self::FRONTEND_USER_GROUP_ID;
    }
    
    /**
     * Vrátí aktuálního uživatele
     * @return array uživatel
     */
    public function getUser() {
        if ($this->userInstance === null) {
            $this->userInstance = $this->cache()->loadUser($this->user['id_user']);
        }
        return $this->userInstance;
    }
    
    /**
     * Vrátí uživatele podle ID_user
     * @param int $idUser id uživatele
     * @return array uživatel pole 
     */
    public function loadUser($idUser) {
        if ($idUser === $this->user['id_user']) {
            $user = array();
            foreach ($this->user as $attr => $val) {
                $user[$attr] = $val;
            }
            $user['userGroup'] = $this->loadUserGroup($this->user['id_userGroup']);
            return $user;
        } else {
            // @todo načte požadovaného uživatele
            return false;
        }
    }

    /**
     * Načte uživatelskou skupinu podle id
     * @param int $idUserGroup id skupiny
     * @return array uživatelská skupina 
     */
    public function loadUserGroup($idUserGroup) {
        $sql = 'SELECT id_userGroup, text, link, id_userGroup_parent
                FROM usergroup
                LEFT JOIN phrase AS usergroup_phrase ON (usergroup_phrase.id_phrase = usergroup.id_phrase)
                WHERE usergroup.id_userGroup = ?
                LIMIT 1';
        $args = array($idUserGroup);
        $q = $this->db->queryArgs($sql, $args);
        $row = $q->fetch();
        if (!$row) {
            return false;
        }
        $userGroup = array(
            'id_userGroup' => $row->offsetGet('id_userGroup'),
            'text' => $row->offsetGet('text'),
            'link' => $row->offsetGet('link'),
            'parent' => $this->loadUserGroup($row->offsetGet('id_userGroup_parent')),
        );
        $userGroup['permissions'] = $this->getPermissions($idUserGroup) + ($userGroup['parent']?$userGroup['parent']['permissions']:array());
        return $userGroup;
    }
    
    public function getPermissions($idUserGroup = false) {
        if (!isset($this->permissions[$idUserGroup])) {
            $this->permissions[$idUserGroup] = $this->cache->loadPermissions($idUserGroup);
        }
        return $this->permissions[$idUserGroup];
    }


    /**
     * Načte prává dané skupiny
     * @param int $idUserGroup id skupiny
     */
    public function loadPermissions($idUserGroup = false) {
        $args = array();
        $sql = 'SELECT permission.id_permission, type, operation, text, link
            FROM usergrouphaspermission
            RIGHT JOIN permission ON (permission.id_permission = usergrouphaspermission.id_permission)
            LEFT JOIN phrase AS permission_phrase ON (permission_phrase.id_phrase = permission.id_phrase) ';
        if ($idUserGroup) {
            $sql .= 'WHERE id_userGroup = ?';
            $args[] = $idUserGroup;
        }
        $q = $this->db->queryArgs($sql, $args);
        $res = $q->fetchAll();
        if (!$res) {
            return array();
        }
        $perms = array();
        foreach ($res as $row) {
            $perms[$row->offsetGet('id_permission')] = array(
                'type' => $row->offsetGet('type'),
                'operation' => $row->offsetGet('operation'),
                'text' => $row->offsetGet('text'),
                'link' => $row->offsetGet('link'),
            );
        }
        return $perms;
    }

    public function isAllowed($type, $operation) {
        $user = $this->getUser();
        $perms = $user['userGroup']['permissions'];
        foreach ($perms as $perm) {
            if ($perm['type'] == $type && $perm['operation'] == $operation) {
                return true;
            }
        }
        return false;
    }
    
    
    
    public function alterPermissions() {
        $modules = PageModel::get()->getModules();
        $perms = self::$permissionsGeneral;
        
        foreach ($modules as $module) {
            $moduleModel = $module['label'].'ModuleModel';
            $modulePerms = $moduleModel::get()->getPermissions();
            foreach ($modulePerms as $perm) {
                $perm['type'] = 'Module' . $module['label'] . '_' . $perm['type'];
                $perms[] = $perm;
            }
        }
        $nowPerms = $this->loadPermissions();
        foreach ($perms as $perm) {
            foreach ($nowPerms as $nowPerm) {
                if ($nowPerm['operation'] == $perm['operation'] && $nowPerm['type'] == $perm['type']) {
                    continue 2;
                }
            }
            $this->db->beginTransaction();
            $idPhrase = ControlModel::get()->insertPhrase(PageModel::get()->getDefaultLanguage(), $perm['text']);
            $data = array(
                'id_phrase' => $idPhrase,
                'type' => $perm['type'],
                'operation' => $perm['operation'],
            );
            $this->db->table('permission')->insert($data);
            $this->db->commit();
        }
        return true;
    }
    
    public function alterUserGroups() {
        foreach (self::$defaultUserGroups as $idUserGroup => $userGroup) {
            try {
                $this->db->beginTransaction();
                $idPhrase = ControlModel::get()->insertPhrase(PageModel::get()->getDefaultLanguage(), $userGroup['text']);
                $data = array(
                    'id_phrase' => $idPhrase,
                    'id_userGroup' => $idUserGroup,
                    'id_userGroup_parent' => $userGroup['parent'],
                );
                $this->db->table('usergroup')->insert($data);
                $this->db->commit();
            } catch (PDOException $e) {
                // Již existuje
                $this->db->rollBack();
            }
        }
        return true;
    }

}

?>
