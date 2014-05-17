<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @Createdate Wed, 19 Mar 2014 13:32:54 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['main'];

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$nv_config_module = GetConfigValue();
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;
$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = $nv_config_module['numperpage'];

// Get num row
$sql = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "`";
$result = $db->sql_query( $sql );
list( $all_page ) = $db->sql_fetchrow( $result );

$global_array_cat_news = array();
$sql = "SELECT catid, alias FROM `" . NV_PREFIXLANG . "_news_cat` ORDER BY `order` ASC";
$result_cat = $db->sql_query( $sql );
while( list( $catid_i, $alias_i ) = $db->sql_fetchrow( $result_cat ) )
{
	$global_array_cat_news[$catid_i] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=news&amp;" . NV_OP_VARIABLE . "=" . $alias_i;
}

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` ORDER BY `id` DESC LIMIT " . $page . ", " . $per_page;
$result = $db->sql_query( $sql );

while( $row = $db->sql_fetchrow( $result ) )
{
	$listid = explode( ",", $row['listid'] );
	$listsended = explode( ",", $row['listsended'] );

	foreach( $listid as $id )
	{
		$list_sql = "SELECT `id`, `title`, `alias`, `catid` FROM `" . NV_PREFIXLANG . "_news_rows` WHERE `status` = 1 AND `id` = " . $id;
		$list_result = $db->sql_query( $list_sql );
		$post = $db->sql_fetchrow( $list_result );
		
		$link = NV_MY_DOMAIN . $global_array_cat_news[$post['catid']] . "/" . $post['alias'] . "-" . $post['id'];
		$xtpl->assign( 'LISTPOST', array( 'title' => $post['title'], 'link' => $link ) );
		$xtpl->parse( 'main.loop.listpost' );
	}
	
	$row['countsended'] = count( $listsended );
	$row['status'] = $row['status'] ? $lang_module['status_completed'] .' ('. $row['countsended'] . ')' : $lang_module['status_stack'];
	$row['time_send'] = ! $row['time_send'] ? 'N/A' : nv_date( 'd/m/Y H:i', $row['time_send'] );
	$xtpl->assign( 'DATA', $row );
	$xtpl->parse( 'main.loop' );
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );
if ( ! empty( $generate_page ) )
{
    $xtpl->assign( 'GENERATE_PAGE', $generate_page );
    $xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>