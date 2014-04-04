<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @Createdate Wed, 19 Mar 2014 13:32:54 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['maillist'];

$xtpl = new XTemplate( "maillist.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );

$nv_config_module = GetConfigValue();

$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = $nv_config_module['numperpage'];

// Search
$where = $url = '';
if( $nv_Request->isset_request( 'search', 'get' ) )
{
	$email = $nv_Request->get_string( 'email', 'get', '' );
	$status = $nv_Request->get_string( 'status', 'get', '' );
	
	if( ! empty( $email ) )
	{
		$where .= " AND `email` like '%" . $email . "%'";
		$url .= "&email=" . $email;	
	}
	
	if( $status != '' )
	{
		$where .= " AND `status` = " . $status . "";
		$url .= "status=" . $status;	
	}
}

$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=maillist" . $url;

// Get num row
$sql = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $module_data . "_emaillist` WHERE 1=1 " . $where;
$result = $db->sql_query( $sql );
list( $all_page ) = $db->sql_fetchrow( $result );

// Select all email
$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "_emaillist` WHERE 1=1 " . $where . " ORDER BY `id` DESC LIMIT " . $page . ", " . $per_page;
$result = $db->sql_query( $sql );

$array_status = array( $lang_module['noactive'], $lang_module['actived'] );

$i = 1;
while( $row = $db->sql_fetchrow( $result ) )
{
	$xtpl->assign( 'CLASS', $i % 2 == 0 ? 'class=\"second\"' : '' );
	foreach( $array_status as $key => $val )
	{
		$xtpl->assign( 'STATUS', array(
			'key' => $key,
			'val' => $val,
			'selected' => ( $key == $row['status'] ) ? " selected=\"selected\"" : "",
		) );

		$xtpl->parse( 'main.maillist.status' );
	}
	
	$row['stt'] = $i;
	$row['time_reg'] = ! $row['time_reg'] ? 'N/A' : nv_date( 'd/m/Y H:i', $row['time_reg'] );
	$row['time_active'] = ! $row['time_active'] ? 'N/A' : nv_date( 'd/m/Y H:i', $row['time_active'] );
	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'main.maillist' );
	
	$i++;
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