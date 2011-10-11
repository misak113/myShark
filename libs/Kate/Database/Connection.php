<?php

namespace Kate\Database;

use Kate\External\SqlParser,
        Kate\Main\Loader;

class Connection extends \Nette\Database\Connection {
    
    /**
     * Obohatí sql o jazykové mutace pokud je třeba
     * @param string $sql sql
     * @param array $args argumenty
     * @return \Nette\Database\Statement statement
     */
    public function queryArgs($sql, $args) {
        
        // Pro Nette diagnostics
        if (strpos($sql, 'SELECT') !== 0) {
            return parent::queryArgs($sql, $args);
        }
        
        $parser = new SqlParser();
        $parsed = $parser->parse($sql);
        $changed = false;
        if (key_exists('FROM', $parsed)) {
            foreach ($parsed['FROM'] as $join) {
                if ($join['table'] === 'phrase' || $join['table'] === '`phrase`') {
                    $changed = true;
                    
                    
                    if (key_exists('WHERE', $parsed)) {
                        $oldExp = $parsed['WHERE'];
                        $parsed['WHERE'] = array();
                        $parsed['WHERE'][] = array(
                            'expr_type' => 'expression',
                            'base_expr' => '',
                            'sub_tree' => $oldExp,
                        );
                        $parsed['WHERE'][] = array(
                            'base_expr' => 'AND',
                            'expr_type' => 'operator',
                            'sub_tree' => 0,
                        );
                    } else {
                        $parsed['WHERE'] = array();
                    }
                    
                    
                    
                    $subExpr = array();
                    
                    
                    $subExpr[] = array(
                        'base_expr' => $join['alias'].'.id_language',
                        'expr_type' => 'colref',
                        'sub_tree' => 0,
                    );
                    $subExpr[] = array(
                        'base_expr' => '=',
                        'expr_type' => 'operator',
                        'sub_tree' => 0,
                    );
                    $subExpr[] = array(
                        'base_expr' => Loader::getPageModel()->getLanguage(),
                        'expr_type' => 'const',
                        'sub_tree' => 0,
                    );
                    
                    $subExpr[] = array(
                        'base_expr' => 'OR',
                        'expr_type' => 'operator',
                        'sub_tree' => 0,
                    );
                    
                    $subExpr[] = array(
                        'base_expr' => $join['alias'].'.id_language',
                        'expr_type' => 'colref',
                        'sub_tree' => 0,
                    );
                    $subExpr[] = array(
                        'base_expr' => 'IS',
                        'expr_type' => 'operator',
                        'sub_tree' => 0,
                    );
                    $subExpr[] = array(
                        'base_expr' => 'NULL',
                        'expr_type' => 'const',
                        'sub_tree' => 0,
                    );
                    
                    
                    $parsed['WHERE'][] = array(
                        'base_expr' => '',
                        'expr_type' => 'expression',
                        'sub_tree' => $subExpr,
                    );
                }
            }
        }
        if ($changed) {
            $sql = $parser->build($parsed);
        }
        return parent::queryArgs($sql, $args);
    }
    
}
?>
