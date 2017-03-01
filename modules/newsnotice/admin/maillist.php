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

$page_title = $lang_module['maillist'];
$page = $nv_Request->get_int('page', 'get', 0);
$per_page = $array_config['numperpage'];

if ($nv_Request->isset_request('delete', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    
    if (empty($id))
        die('NO_' . $id);
    
    if (! nv_delete_email($id)) {
        die('NO_' . $id);
    }
    
    include NV_ROOTDIR . '/includes/header.php';
    echo 'OK_' . $id;
    include NV_ROOTDIR . '/includes/footer.php';
} elseif ($nv_Request->isset_request('delete_list', 'post')) {
    $listall = $nv_Request->get_title('listall', 'post', '');
    $array_id = explode(',', $listall);
    
    if (! empty($array_id)) {
        foreach ($array_id as $id) {
            nv_delete_email($id);
        }
        $nv_Cache->delMod($module_name);
        die('OK');
    }
    die('NO');
}

// Search
$where = $url = '';
if ($nv_Request->isset_request('search', 'get')) {
    $email = $nv_Request->get_string('email', 'get', '');
    $status = $nv_Request->get_string('status', 'get', '');
    
    if (! empty($email)) {
        $where .= " AND email like '%" . $email . "%'";
        $url .= "&email=" . $email;
    }
    
    if ($status != '') {
        $where .= " AND status = " . $status . "";
        $url .= "status=" . $status;
    }
}

$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=maillist" . $url;

// Get num row
$sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_emaillist WHERE 1=1 " . $where;
$result = $db->query($sql);
$all_page = $result->fetchColumn();

// Select all email
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_emaillist WHERE 1=1 " . $where . " ORDER BY id DESC LIMIT " . $page . ", " . $per_page;
$result = $db->query($sql);

$array_status = array(
    $lang_module['noactive'],
    $lang_module['actived']
);

$xtpl = new XTemplate("maillist.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('BASE_URL', $base_url);

$i = 1;
while ($row = $result->fetch()) {
    foreach ($array_status as $key => $val) {
        $xtpl->assign('STATUS', array(
            'key' => $key,
            'val' => $val,
            'selected' => ($key == $row['status']) ? " selected=\"selected\"" : ""
        ));
        
        $xtpl->parse('main.maillist.status');
    }
    
    $row['stt'] = $i;
    $row['time_reg'] = ! $row['time_reg'] ? 'N/A' : nv_date('H:i d/m/Y', $row['time_reg']);
    $row['time_active'] = ! $row['time_active'] ? 'N/A' : nv_date('H:i d/m/Y', $row['time_active']);
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.maillist');
    
    $i ++;
}

$array_action = array(
    'delete_list_id' => $lang_global['delete']
);
foreach ($array_action as $key => $value) {
    $xtpl->assign('ACTION', array(
        'key' => $key,
        'value' => $value
    ));
    $xtpl->parse('main.action_top');
    $xtpl->parse('main.action_bottom');
}

$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);
if (! empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';