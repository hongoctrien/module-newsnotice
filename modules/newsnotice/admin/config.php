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
$error = '';

if ($nv_Request->isset_request('save', 'post')) {
    $array_config['active'] = $nv_Request->get_bool('active', 'post', 0);
    $array_config['title_email'] = $nv_Request->get_string('title_email', 'post', '');
    $array_config['numperpage'] = $nv_Request->get_int('numperpage', 'post', 20);
    $array_config['active_required'] = $nv_Request->get_int('active_required', 'post', 0);
    $array_config['active_thank'] = $nv_Request->get_int('active_thank', 'post', 0);

    if (empty($error)) {
        $sth = $db->prepare("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_config SET config_value = :config_value WHERE config_name = :config_name");
        foreach ($array_config as $config_name => $config_value) {
            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }

        $nv_Cache->delMod($module_name);

        Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=config");
        die();
    }
}

$array_config['ck_active'] = $array_config['active'] ? 'checked="checked"' : '';
$array_config['ck_active_required'] = $array_config['active_required'] ? 'checked="checked"' : '';
$array_config['ck_active_thank'] = $array_config['active_thank'] ? 'checked="checked"' : '';

$xtpl = new XTemplate("config.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('ACTION', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=config");
$xtpl->assign('DATA', $array_config);
$xtpl->assign('EROR', $error);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';