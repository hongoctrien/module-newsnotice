<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @Createdate Wed, 19 Mar 2014 13:32:54 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['messages'];
$array_data = array( 'title' => '', 'content' => '', 'option' => 'schedule', 'object' => 'maillist' );
$error = '';

$xtpl = new XTemplate( "messages_send.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=messages_sending" );

if( ! empty( $array_data['content'] ) ) $array_data['content'] = nv_htmlspecialchars( $array_data['content'] );

if( defined( 'NV_EDITOR' ) ) require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );

if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$array_data['content'] = nv_aleditor( "content", '100%', '200px', $array_data['content'] );
}
else
{
	$array_data['content'] = "<textarea style=\"width:100%;height:200px\" name=\"content\">" . $array_data['content'] . "</textarea>";
}

$xtpl->assign( 'CONTENT', $array_data['content'] );
$xtpl->assign( 'DATA', $array_data );

if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

$option = array( 
	'schedule' => $lang_module['message_option_schedule'],
	'now' => $lang_module['message_option_now'] );

foreach( $option as $key => $value )
{
	$xtpl->assign( 'OPTION', array( 'value' => $key, 'title' => $value, 'checked' => $array_data['option'] == $key ? 'checked="checked"' : '' ) );
	$xtpl->parse( 'main.option' );
}

$object = array( 
	'maillist' => $lang_module['message_object_user_notice'],
	'system' => $lang_module['message_object_user_system'] );

foreach( $object as $key => $value )
{
	$xtpl->assign( 'OBJECT', array( 'value' => $key, 'title' => $value, 'checked' => $array_data['object'] == $key ? 'checked="checked"' : '' ) );
	$xtpl->parse( 'main.object' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo 1;
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>