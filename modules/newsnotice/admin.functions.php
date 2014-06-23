<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @Createdate Wed, 19 Mar 2014 13:32:54 GMT
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$submenu['messages'] = $lang_module['messages'];
$submenu['maillist'] = $lang_module['maillist'];
$submenu['config'] = $lang_module['config'];

$allow_func = array( 'main', 'maillist', 'messages', 'messages_send', 'messages_sending', 'config', 'change_status', 'delete', 'del_stack' );

define( 'NV_IS_FILE_ADMIN', true );

function GetConfigValue()
{
    global $module_data;
    
    $sql = "SELECT `config_name`, `config_value` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_config`";
    $list = nv_db_cache( $sql );
    
    $array = array();
    foreach ( $list as $values )
    {
        $array[$values['config_name']] = $values['config_value'];
    }
    
    return $array;
}

?>