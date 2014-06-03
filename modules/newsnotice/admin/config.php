<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @Createdate Wed, 19 Mar 2014 13:32:54 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['config'];

$xtpl = new XTemplate( "config.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=config" );

$error = '';

if( $nv_Request->isset_request( 'save', 'post') )
{
	$array_config['active'] = $nv_Request->get_bool( 'active', 'post', 0 );
	$array_config['title_email'] = $nv_Request->get_string( 'title_email', 'post', '' );
	$array_config['numperpage'] = $nv_Request->get_int( 'numperpage', 'post', 20 );
	$array_config['nummail'] = $nv_Request->get_int( 'nummail', 'post', 20 );
	
	if( empty( $error) )
	{
	    foreach ( $array_config as $config_name => $config_value )
	    {
			$query = "REPLACE INTO `" . NV_PREFIXLANG . "_" . $module_data . "_config` VALUES (" . $db->dbescape( $config_name ) . "," . $db->dbescape( $config_value ) . ")";
			$db->sql_query( $query );
	    }
	
	    nv_del_moduleCache( $module_name );
	
	    Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=config" );
	    die();
	}
}

$nv_config = GetConfigValue();
$xtpl->assign( 'CHECKED', $nv_config['active'] ? 'checked="checked"' : '' );
$xtpl->assign( 'DATA', $nv_config );
$xtpl->assign( 'EROR', $error );

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>