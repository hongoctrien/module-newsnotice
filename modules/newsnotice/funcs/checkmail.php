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

$email = $nv_Request->get_string('email', 'get', '');

if (empty($email))
    die();

$sql = "SELECT email FROM " . NV_PREFIXLANG . "_" . $module_data . "_emaillist WHERE email = " . $db->quote($email);
$result = $db->query($sql);
$num = $result->rowCount();

include NV_ROOTDIR . '/includes/header.php';
echo $num;
include NV_ROOTDIR . '/includes/footer.php';