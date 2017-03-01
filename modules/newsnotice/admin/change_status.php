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

$id = $nv_Request->get_int('id', 'post', 0);
if (empty($id))
    die("NO_" . $id);

$sql = "SELECT email, status FROM " . NV_PREFIXLANG . "_" . $module_data . "_emaillist WHERE id=" . $id;
$result = $db->query($sql);
$email = $result->fetchColumn();
$numrows = $result->rowCount();
if ($numrows != 1)
    die('NO_' . $id);

$new_status = $nv_Request->get_bool('new_status', 'post');
$new_status = (int) $new_status;
$time_active = $new_status ? NV_CURRENTTIME : 0;

$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_emaillist SET time_active = " . $time_active . ", status=" . $new_status . " WHERE id=" . $id;
if ($db->query($sql) and ! empty($email)) {
    
    // Gửi thư phản hồi sau khi đăng ký thành công
    if ($new_status and $array_config['active_thank']) {
        nv_sendmail_thank($email);
    }
    
    $action = $new_status ? 'active' : 'deactive';
    $contents = sprintf($lang_module['sendmail_content_status_' . $action], $email, $global_config['site_name'], $global_config['site_url']);
    nv_sendmail(array(
        $global_config['site_name'],
        $global_config['site_url']
    ), $email, $lang_module['sendmail_title_status'], $contents);
}

$nv_Cache->delMod($module_name);

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $id;
include NV_ROOTDIR . '/includes/footer.php';