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
    die('NO_' . $id);

$sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_emaillist WHERE id = " . $id;
if (! $db->query($sql)) {
    die('NO_' . $id);
}

include NV_ROOTDIR . '/includes/header.php';
echo 'OK_' . $id;
include NV_ROOTDIR . '/includes/footer.php';