<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2014 mynukeviet. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */
if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$array_config = array();
$sql = "SELECT config_name, config_value FROM " . NV_PREFIXLANG . "_" . $module_data . "_config";
$list = $nv_Cache->db($sql, '', $module_name);

foreach ($list as $values) {
    $array_config[$values['config_name']] = $values['config_value'];
}

function nv_sendmail_thank($tomail)
{
    global $global_config, $module_file, $lang_module;
    
    $xtpl_mail = new XTemplate('sendmail_thank_' . NV_LANG_DATA . '.tpl', NV_ROOTDIR . '/themes/default/modules/' . $module_file);
    $xtpl_mail->assign('LANG', $lang_module);
    $xtpl_mail->assign('SITE_NAME', $global_config['site_name']);
    $xtpl_mail->assign('SITE_DESCRIPTION', $global_config['site_description']);
    
    $subject = '[' . $global_config['site_name'] . '] ' . $lang_module['thank_mail'];
    
    $xtpl_mail->parse('main');
    $message = $xtpl_mail->text('main');
    
    @nv_sendmail($global_config['site_email'], $tomail, $subject, $message);
}