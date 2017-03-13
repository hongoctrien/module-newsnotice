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

$page_title = $lang_module['main'];

if ($nv_Request->isset_request('delete', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    
    if (empty($id))
        die('NO_' . $id);
    
    $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id = " . $id;
    if (! $db->query($sql)) {
        die('NO_' . $id);
    }
    
    include NV_ROOTDIR . '/includes/header.php';
    echo 'OK_' . $id;
    include NV_ROOTDIR . '/includes/footer.php';
}

$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;
$page = $nv_Request->get_int('page', 'get', 1);
$per_page = $array_config['numperpage'];

// Get num row
$sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "";
$result = $db->query($sql);
$all_page = $result->fetchColumn();

$global_array_cat_news = array();
$sql = "SELECT catid, alias FROM " . NV_PREFIXLANG . "_news_cat ORDER BY weight ASC";
$result_cat = $db->query($sql);
while (list ($catid_i, $alias_i) = $result_cat->fetch(3)) {
    $global_array_cat_news[$catid_i] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=news&amp;" . NV_OP_VARIABLE . "=" . $alias_i;
}

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " ORDER BY id DESC LIMIT " . $per_page . " OFFSET " . ($page - 1) * $per_page;
$result = $db->query($sql);

$xtpl = new XTemplate("main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

while ($row = $result->fetch()) {
    $listid = explode(",", $row['listid']);
    $listid = array_diff($listid, array(''));
    $listsended = explode(",", $row['listsended']);
    $listsended = array_diff($listsended, array(''));
    
    $i = 0;
    foreach ($listid as $id) {
        $post = $db->query("SELECT id, title, alias, catid FROM " . NV_PREFIXLANG . "_news_rows WHERE status = 1 AND id = " . $id)->fetch();
        if ($post) {
            $i ++;
            $link = NV_MY_DOMAIN . $global_array_cat_news[$post['catid']] . "/" . $post['alias'] . "-" . $post['id'];
            $xtpl->assign('LISTPOST', array(
                'title' => $post['title'],
                'link' => $link
            ));
            $xtpl->parse('main.loop.listpost');
        }
    }
    
    if ($i > 0) {
        $row['countsended'] = count($listsended);
        $row['status'] = $row['status'] ? $lang_module['status_completed'] : $lang_module['status_stack'];
        $row['time_sended'] = ! $row['time_sended'] ? 'N/A' : nv_date('d/m/Y H:i', $row['time_sended']);
        $xtpl->assign('DATA', $row);
        $xtpl->parse('main.loop');
    }
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