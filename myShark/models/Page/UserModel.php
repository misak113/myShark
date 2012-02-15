<?php

use Kate\Http\UserAgentParser,
    Kate\Http\Cookies;

/**
 * Model který zajišťuje práci s uživateli
 */
class UserModel extends \Kate\Main\Model implements Nette\Security\IAuthenticator, \Nette\Security\IAuthorizator
{
    const HASH_KEY = 'key_for_hash_generation';
    const COOKIE_UID = 'user_uid_hash';
	const LOGIN_EXPIRATION = '+ 30 minutes';
    
    const FRONTEND_USER_GROUP_ID = 1;
    const ROBOT_USER_GROUP_ID = 2;
    const ADMIN_USER_GROUP_ID = 3;
    
    private static $defaultUserGroups = array(
        self::FRONTEND_USER_GROUP_ID => array('text' => 'Frontend uživatel', 'parent' => null),
        self::ROBOT_USER_GROUP_ID => array('text' => 'Robot', 'parent' => null),
        self::ADMIN_USER_GROUP_ID => array('text' => 'Administrátor', 'parent' => self::FRONTEND_USER_GROUP_ID),
    );
    
    private static $permissionsGeneral = array(
        array('type' => 'web', 'operation' => 'display', 'text' => 'Zobrazení webových stránek'),
		array('type' => 'web', 'operation' => 'animate', 'text' => 'Zobrazení animovaných webových stránek'),
    );
    
    private $userFetch, $userAgent, $ip, $request, $response, 
			$user = null, $permissions = array();
    
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
				return;
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
            $this->userFetch = $this->db->table('user')->where('hashCode', $hashCode)->limit(1)->fetch();
			
			// @todo pokud je prihlasen tak nastavi jako user z AdminModel
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
        $this->db->table('user')->insert($args);
		$this->userFetch = $this->db->table('user')->where('hashCode', $hashCode)->where('noCookie', true)->limit(1)->fetch();
    }
    
    private function createUserNotExists() {
        $hashCode = sha1(self::HASH_KEY . $this->ip . $this->userAgent);
        $this->userFetch = $this->db->table('user')->where('hashCode', $hashCode)->where('noCookie', true)->limit(1)->fetch();
		if ($this->userFetch['id_user'] === null) {
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
                $this->db->table('user')->where('id_user', $this->userFetch['id_user'])->update($args);
            } else {
                $args = array();
                $args['lastAccessDate'] = new Nette\Database\SqlLiteral('NOW()');
                $args['ip'] = $this->ip;
                $args['userAgent'] = $this->userAgent;
                $args['countLoads'] = new Nette\Database\SqlLiteral('countLoads + 1');
                $this->db->table('user')->where('id_user', $this->userFetch['id_user'])->update($args);
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
        if ($this->user === null) {
            $this->user = $this->container->user;
			$this->user->setAuthorizator($this);
			$this->user->setExpiration(self::LOGIN_EXPIRATION, true);
			if (!$this->user->isLoggedIn()) {
				$this->user->setAuthenticator($this);
				$this->user->login();
			}
			
        }
        return $this->user;
    }
	
	
	public function authenticate(array $credentials) {
		$userData = $this->cache()->loadUserData();
		$identity = new Nette\Security\Identity($userData['id_user'], self::FRONTEND_USER_GROUP_ID, $userData);
		return $identity;
	}
    
    /**
     * Vrátí uživatele podle ID_user
     * @param int $idUser id uživatele
     * @return array uživatel pole 
     */
    public function loadUserData($idUser = false) {
        if ($idUser === $this->userFetch['id_user'] || ($idUser === false && $this->userFetch['id_user'])) {
            $user = array();
            foreach ($this->userFetch as $attr => $val) {
                $user[$attr] = $val;
            }
            $user['userGroup'] = $this->loadUserGroup($this->userFetch['id_userGroup']);
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
	    FROM permission
	    LEFT JOIN phrase AS permission_phrase ON (permission_phrase.id_phrase = permission.id_phrase) 
	    LEFT JOIN usergrouphaspermission ON (usergrouphaspermission.id_permission = permission.id_permission)
            LEFT JOIN usergroup ON (usergroup.id_userGroup = usergrouphaspermission.id_userGroup) ';
        if ($idUserGroup) {
            $sql .= 'WHERE usergroup.id_userGroup = ? ';
            $args[] = $idUserGroup;
        }
	$sql.= 'GROUP BY type, operation ';
        $q = $this->db->queryArgs($sql, $args);
        $res = $q->fetchAll();
        if (!$res) {
            return array();
        }
        $perms = array();
        foreach ($res as $row) {
			if (!$row->offsetGet('id_permission')) continue;
            $perms[$row->offsetGet('id_permission')] = array(
                'type' => $row->offsetGet('type'),
                'operation' => $row->offsetGet('operation'),
                'text' => $row->offsetGet('text'),
                'link' => $row->offsetGet('link'),
            );
        }
        return $perms;
    }

    public function isAllowed($idUserGroup, $type, $operation) {
        $userGroup = $this->cache()->loadUserGroup($idUserGroup);
		$perms = $userGroup['permissions'];
        foreach ($perms as $perm) {
            if ($perm['type'] == $type && $perm['operation'] == $operation) {
                return true;
            }
        }
        return false;
    }
    
	
	public function adminUserLogged($idUser) {
		$userFetch = $this->db->table('user')
				->where('id_user', $idUser)
				->limit(1)->fetch();
		if (!$userFetch) {
			throw new \Nette\Security\AuthenticationException();
		}
		
		$args = array();
		$args['countLoads'] = 0;
		$this->db->table('user')->where('id_user', $this->userFetch['id_user'])->update($args);
		
		$hashCode = sha1($this->userFetch['hashCode'] . rand());
		$args = array();
		$args['hashCode'] = $hashCode;
		$args['lastAccessDate'] = new Nette\Database\SqlLiteral('NOW()');
		$args['ip'] = $this->ip;
		$args['userAgent'] = $this->userAgent;
		$args['countLoads'] = new Nette\Database\SqlLiteral('countLoads + '.$this->userFetch['countLoads']);
		$this->db->table('user')->where('id_user', $idUser)->update($args);
		$this->response->setCookie(self::COOKIE_UID, $hashCode, '+10 years', '/');
		
		$userFetch = $this->db->table('user')
				->where('id_user', $idUser)
				->limit(1)->fetch();
		
		
		$this->userFetch = $userFetch;
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
        $this->db->beginTransaction();
        foreach ($perms as $perm) {
            foreach ($nowPerms as $nowPerm) {
                if ($nowPerm['operation'] == $perm['operation'] && $nowPerm['type'] == $perm['type']) {
                    continue 2;
                }
            }
            $idPhrase = ControlModel::get()->insertPhrase(PageModel::get()->getDefaultLanguage(), $perm['text']);
            $data = array(
                'id_phrase' => $idPhrase,
                'type' => $perm['type'],
                'operation' => $perm['operation'],
            );
            $this->db->table('permission')->insert($data);
        }
        $this->db->commit();
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
