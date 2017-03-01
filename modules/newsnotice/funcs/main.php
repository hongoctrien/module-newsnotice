<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2014 mynukeviet. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */
if (! defined('NV_IS_MOD_NEWSNOTICE'))
    die('Stop!!!');

$notice = '';
$page_title = $module_info['custom_title'];

if ($nv_Request->isset_request('status, email', 'get')) {
    $status = $nv_Request->get_string('status', 'get', '');
    $email = $nv_Request->get_string('email', 'get', '');
    
    if ($status == 'success') {
        $result = $db->query("SELECT email FROM " . NV_PREFIXLANG . "_" . $module_data . "_emaillist WHERE email = " . $db->quote($email));
        if ($result->rowCount() > 0) {
            $notice = $lang_module['error_existed_email'];
        } else {
            $key = md5($email . NV_CURRENTTIME);
            $status = 1;
            $time_active = NV_CURRENTTIME;
            if ($array_config['active_required']) {
                $time_active = $status = 0;
            }
            $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_emaillist( email, time_reg, time_active, check_key, status ) VALUES( " . $db->quote($email) . ", " . NV_CURRENTTIME . ", " . $time_active . ", " . $db->quote($key) . ", " . $status . " )";
            if ($db->query($sql)) {
                if ($status) {
                    $notice = $lang_module['notice_success'];
                    
                    // Gửi thư phản hồi sau khi đăng ký thành công
                    if($array_config['active_thank']){
                        nv_sendmail_thank($email);
                    }
                } else {
                    $notice = sprintf($lang_module['notice_success_mail_active'], $email);
                    $url = $global_config['site_url'] . "/index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=main&status=done&email=" . $email . "&key=" . $key;
                    $mail_content = sprintf($lang_module['email_content'], $email, $global_config['site_name'], $global_config['site_url'], $url);
                    nv_sendmail(array(
                        $global_config['site_name'],
                        $global_config['site_email']
                    ), $email, $lang_module['title_email'], $mail_content);
                }
            } else {
                $notice = $lang_module['error_success_mail_active'];
            }
        }
    } elseif ($status == 'done') {
        $key = $nv_Request->get_string('key', 'get', '');
        $sql = "SELECT id, status FROM " . NV_PREFIXLANG . "_" . $module_data . "_emaillist WHERE email = " . $db->quote($email) . " AND check_key = " . $db->quote($key);
        $result = $db->query($sql);
        $row = $result->fetch();
        $count = $result->rowCount();
        
        if ($count) {
            if (! $row['status']) {
                $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_emaillist SET time_active = " . NV_CURRENTTIME . ", status = 1 WHERE email = " . $db->quote($email);
                $db->query($sql);
                $notice = $lang_module['notice_success_done_actived'];
                // Gửi thư phản hồi sau khi đăng ký thành công
                if($array_config['active_thank']){
                    nv_sendmail_thank($email);
                }
            } else {
                $notice = $lang_module['notice_actived'];
            }
        } else {
            $notice = $lang_module['notice_success_done_active'];
        }
    } elseif ($status == 'cancel') {
        $key = $nv_Request->get_string('key', 'get', '');
        $sql = "SELECT id, status FROM " . NV_PREFIXLANG . "_" . $module_data . "_emaillist WHERE email = " . $db->quote($email) . " AND check_key = " . $db->quote($key);
        $result = $db->query($sql);
        $row = $result->fetch();
        $count = $result->rowCount();
        
        if ($count) {
            if ($row['status']) {
                $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_emaillist SET time_active = 0, status = 0 WHERE email = " . $db->quote($email);
                $db->query($sql);
                $notice = $lang_module['notice_cancel'];
            } else {
                $notice = $lang_module['notice_deactived'];
            }
        } else {
            $notice = $lang_module['notice_success_done_cancel'];
        }
    } else {
        header('Location: ' . NV_BASE_SITEURL);
    }
} else {
    header('Location: ' . NV_BASE_SITEURL);
}

$xtpl = new XTemplate("main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

$xtpl->assign('NOTICE', $notice);
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';