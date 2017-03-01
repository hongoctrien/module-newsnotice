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

if ($nv_Request->isset_request('submit', 'post')) {
    $emailist = $nv_Request->get_textarea('emaillist', '', 'br', 1);
    
    if (empty($emailist)) {
        die('NO_' . $lang_module['error_required_emaillist']);
    }
    
    $array_emailist = explode('<br />', $emailist);
    foreach ($array_emailist as $index => $email) {
        if (! empty($email)) {
            if ($check = nv_check_valid_email($email) != '') {
                die('NO_' . sprintf($lang_module['error_vaild_email'], $email));
            }
        } else {
            unset($array_emailist[$index]);
        }
    }
    
    if (! empty($array_emailist)) {
        $i = 0;
        set_time_limit(0);
        foreach ($array_emailist as $email) {
            try {
                $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_emaillist(email, time_reg, time_active, status) VALUES(:email, ' . NV_CURRENTTIME . ', ' . NV_CURRENTTIME . ', 1)');
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    $i ++;
                }
            } catch (Exception $e) {
                // Nếu đã có rồi thì bỏ qua
            }
        }
        die('OK_' . sprintf($lang_module['addemail_success'], $i));
    }
    
    die('NO_' . $lang_module['error_empty_emaillist']);
}

$xtpl = new XTemplate($op . ".tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=mailcontent');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents, false);
include NV_ROOTDIR . '/includes/footer.php';