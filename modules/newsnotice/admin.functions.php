<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2014 mynukeviet. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */
if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE') or ! defined('NV_IS_MODADMIN'))
    die('Stop!!!');

$allow_func = array(
    'main',
    'maillist',
    'config',
    'change_status',
    'mailcontent'
);

define('NV_IS_FILE_ADMIN', true);
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

function nv_delete_email($id)
{
    global $db, $module_data;

    $count = $db->exec("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_emaillist WHERE id = " . $id);
    
    return $count;
}