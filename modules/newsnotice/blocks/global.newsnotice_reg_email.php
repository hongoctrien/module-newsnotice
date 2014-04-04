<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES., JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10/08/2011 08:00
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if ( ! nv_function_exists( 'nv_function_reg_email' ) )
{
	function nv_function_reg_email( $block_config )
	{		
		global $global_config, $module_info, $lang_module;
		$module = $block_config['module'];		

		if ( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module . "/global.newsnotice_reg_email.tpl" ) )
		{
			$block_theme = $module_info['template'];
		}
		else
		{
			$block_theme = "default";
		}
		
		$lang_temp = $lang_module;
		if ( file_exists( NV_ROOTDIR . "/modules/" . $module . "/language/" . $global_config['site_lang'] . ".php" ) )
		{
			require_once NV_ROOTDIR . "/modules/" . $module . "/language/" . $global_config['site_lang'] . ".php";
		}
		$lang_module = $lang_module + $lang_temp;
		unset( $lang_temp );
		
		$xtpl = new XTemplate( 'global.newsnotice_reg_email.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/modules/' . $module );
		$xtpl->assign( 'LANG', $lang_module );
		$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
		$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
		$xtpl->assign( 'ACTION', NV_BASE_SITEURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module );
		
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}
}

if( defined( 'NV_SYSTEM' ) ) 
{
	global $site_mods, $module_name;
	$module = $block_config['module'];

	$content = 	nv_function_reg_email( $block_config );
}
?>