<?php

/**
 * @Project NUKEVIET 4.x
* @Author mynukeviet (contact@mynukeviet.com)
* @Copyright (C) 2014 mynukeviet. All rights reserved
* @License GNU/GPL version 2 or any later version
* @Createdate 2-10-2010 18:49
*/
if (! defined('NV_IS_FILE_ADMIN'))
    die('Stop!!!');

$page_title = $lang_module['config'];

$xtpl = new XTemplate("config.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=config");

$error = '';

if ($nv_Request->isset_request('save', 'post')) {
    $array_config['active'] = $nv_Request->get_bool('active', 'post', 0);
    $array_config['title_email'] = $nv_Request->get_string('title_email', 'post', '');
    $array_config['numperpage'] = $nv_Request->get_int('numperpage', 'post', 20);
    
    if (! is_integer($array_config['numperpage'])) {
        $error = $lang_module['error_numperpage_type'];
    }
    
    if (empty($error)) {
        foreach ($array_config as $config_name => $config_value) {
            $query = "REPLACE INTO " . NV_PREFIXLANG . "_" . $module_data . "_config VALUES (" . $db->quote($config_name) . "," . $db->quote($config_value) . ")";
            $db->query($query);
        }
        
        $nv_Cache->delMod($module_name);
        
        Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=config");
        die();
    }
}

$nv_config = GetConfigValue();
$xtpl->assign('CHECKED', $nv_config['active'] ? 'checked="checked"' : '');
$xtpl->assign('DATA', $nv_config);
$xtpl->assign('EROR', $error);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';