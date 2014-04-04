<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @Createdate Wed, 19 Mar 2014 13:32:54 GMT
 */

if ( ! defined( 'NV_IS_MOD_NEWSNOTICE' ) ) die( 'Stop!!!' );

$page_title = $module_info['custom_title'];

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

if( $nv_Request->isset_request( 'status, email', 'get') )
{
	$status = $nv_Request->get_string( 'status', 'get', '' );
	$email = $nv_Request->get_string( 'email', 'get', '' );
	
	if( $status == 'success' )
	{
		$key = md5( $email . NV_CURRENTTIME );
		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_emaillist`( `email`, `time_reg`, `time_active`, `key`, `status` ) VALUES( " . $db->dbescape_string( $email ) . ", " . NV_CURRENTTIME . ", 0, " . $db->dbescape_string( $key ) . ", 0 )";
		
		if( $db->sql_query( $sql ) )
		{
			$notice = sprintf( $lang_module['notice_success_mail_active'], $email );
			$url = $global_config['site_url'] . "/index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=main&status=done&email=" . $email . "&key=" . $key;
			$mail_content = sprintf( $lang_module['email_content'], $email, $global_config['site_name'], $global_config['site_url'], $url );
			nv_sendmail( array( $global_config['site_name'], $global_config['site_email'] ), $email, $lang_module['title_email'], $mail_content );
		}
		else 
		{
			$notice = $lang_module['error_success_mail_active'];
		}
	}
	elseif ( $status == 'done' ) 
	{
		$key = $nv_Request->get_string( 'key', 'get', '' );
		$sql = "SELECT `id`, `status` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_emaillist` WHERE `email` = " . $db->dbescape_string( $email ) . " AND `key` = " . $db->dbescape_string( $key );
		$result = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $result );
		$count = $db->sql_numrows( $result );

		if( $count )
		{
			if( ! $row['status'] )
			{
				$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_emaillist` SET `time_active` = " . NV_CURRENTTIME . ", `status` = 1 WHERE `email` = " . $db->dbescape_string( $email );
				$db->sql_query( $sql );
				$notice = $lang_module['notice_success_done_actived'];
			}
			else
			{
				$notice = $lang_module['notice_actived'];
			}
		}
		else 
		{
			$notice = $lang_module['notice_success_done_active'];
		}
	}
	elseif ( $status == 'cancel' )
	{
		$key = $nv_Request->get_string( 'key', 'get', '' );
		$sql = "SELECT `id`, `status` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_emaillist` WHERE `email` = " . $db->dbescape_string( $email ) . " AND `key` = " . $db->dbescape_string( $key );
		$result = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $result );
		$count = $db->sql_numrows( $result );

		if( $count )
		{
			if( $row['status'] )
			{
				$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_emaillist` SET `time_active` = 0, `status` = 0 WHERE `email` = " . $db->dbescape_string( $email );
				$db->sql_query( $sql );
				$notice = $lang_module['notice_cancel'];
			}
			else
			{
				$notice = $lang_module['notice_deactived'];
			}
		}
		else 
		{
			$notice = $lang_module['notice_success_done_cancel'];
		}
	} 
	else
	{
		header( 'Location: ' .  NV_BASE_SITEURL );
	}
}
else 
{
	header( 'Location: ' .  NV_BASE_SITEURL );
}

$xtpl->assign( 'NOTICE', $notice );
$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>