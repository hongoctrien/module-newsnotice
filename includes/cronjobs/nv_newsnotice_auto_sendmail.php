<?php

/**
 * @Project NUKEVIET 3.4
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 02-07-2011 09:02
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! defined( 'NV_IS_CRON' ) ) die( 'Stop!!!' );

function cron_newsnotice_send_mail( $module, $tablenews )
{
	global $db, $global_config, $db_config;

	$check = true;
	$tablenews = $db_config['prefix'] . "_" . $tablenews;
	
	list( $time ) = $db->sql_fetchrow( $db->sql_query( "SELECT `time_stacked` FROM `" . NV_PREFIXLANG . "_" . $module . "` ORDER BY `id` DESC LIMIT 0,1 " ) );
	
	if( $time )
	{
		$time_stacked = $time;
	} 
	else 
	{
		$time_stacked = NV_CURRENTTIME - ( 1440 * 60 ); // Neu chua gui lan nao thi mac dinh lay cac bai viet dc gui trc do 1 ngay
	}
	
    $sql = "SELECT `config_name`, `config_value` FROM `" . NV_PREFIXLANG . "_" . $module . "_config`";
    $list = nv_db_cache( $sql, $module );
    
    $nv_module_config = array();
    foreach ( $list as $values )
    {
        $nv_module_config[$values['config_name']] = $values['config_value'];
    }

	if( ! empty( $tablenews ) and $nv_module_config['active'] )
	{	
		$sql = "SELECT SQL_CALC_FOUND_ROWS `id` FROM `" . $tablenews . "` WHERE `status`= 1 AND `publtime` > " . $time_stacked . " AND `publtime` <= " . NV_CURRENTTIME . " ORDER BY `publtime` DESC";
		$result = $db->sql_query( $sql );
		$numrows = $db->sql_numrows( $result );

		if( $numrows > 0 ) // Neu trong khoang thoi gian $minspace co bai viet moi thi ....
		{
			$listid = array();
			while( $row = $db->sql_fetchrow( $result ) )
			{
				$listid[] = $row['id'];
			}
			
			$lid = implode( ",", $listid );
			
			$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module . "`( `id`, `listid`, `time_stacked`, `totalsended`, `status` ) VALUES ( NULL, " . $db->dbescape( $lid ) . ", " . NV_CURRENTTIME . ", 0, 0)";
			$id_insert = $db->sql_query_insert_id( $sql );
			
			if( $id_insert == 0 )
			{
				$check = false;
			}
			else
			{
				$check = cron_newsnotice_start_send_mail( $module, $tablenews );
			}
		}
	}
	else
	{
		$check = false;
	}

	return $check;
}

function cron_newsnotice_start_send_mail( $module, $tablenews )
{
	global $global_config, $db_config, $db;
	
    $sql = "SELECT `config_name`, `config_value` FROM `" . NV_PREFIXLANG . "_" . $module . "_config`";
    $list = nv_db_cache( $sql, $module );
    
    $nv_module_config = array();
    foreach ( $list as $values )
    {
        $nv_module_config[$values['config_name']] = $values['config_value'];
    }
	
	$sql_email = "SELECT `id`, `email` FROM `" . NV_PREFIXLANG . "_" . $module . "_emaillist` WHERE `status` = 1";
	$result_email = $db->sql_query( $sql_email );
	$nummail = $db->sql_numrows( $result_email );
	
	if( $nummail > 0 )
	{
		$sql_send = "SELECT `id`, `listid` FROM `" . NV_PREFIXLANG . "_" . $module . "` WHERE `status` = 0";
		$result_send = $db->sql_query( $sql_send );
		
		include ( NV_ROOTDIR . "/modules/" . $module . "/language/" . NV_LANG_INTERFACE . ".php" );
				
		while( list( $id, $listid ) = $db->sql_fetchrow( $result_send ) )
		{
			if( ! empty( $listid ) )
			{
				$listidmail = array();
				$count = 0;
				while( $mail = $db->sql_fetchrow( $result_email ) )
				{
					if( $count == 20 )
					{
						$count = 0;
						sleep(2); //Nghi 2 giay roi gui tiep
					}
					else 
					{
						$result_key = $db->sql_query("SELECT `key` FROM `" . NV_PREFIXLANG . "_" . $module . "_emaillist` WHERE `email` = " . $db->dbescape_string( $mail['email'] ) );
						list( $key ) = $db->sql_fetchrow( $result_key );
				
						$cancellink = $global_config['site_url'] . "/index.php?" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=main&status=cancel&email=" . $mail['email'] . "&key=" . $key;
						
						$content = "<div style='line-height: 25px'>";
						$content .= sprintf( $lang_module['sendmail_content_new_post'], $mail['email'], $global_config['site_name'], $global_config['site_url'] );
						$content .= '<ul>';
						$content .= GetNews( $listid, $tablenews );
						$content .= '</ul>';
						$content .= '------------------------------<br />';
						$content .= "<em>" . sprintf( $lang_module['sendmail_content_note'], $global_config['site_name'], $global_config['site_url'], $cancellink ) . "</em>";
						$content .= '</div>';
						
						if( nv_sendmail( array( $global_config['site_name'], $global_config['site_email'] ), $mail['email'], $nv_module_config['title_email'], $content ) )
						{
							$listidmail[] = $mail['id'];
						}
						$count++;
					}
				}
				
				$update = "UPDATE `" . NV_PREFIXLANG . "_" . $module . "` SET `listsended` = " . $db->dbescape_string( implode( ",", $listidmail ) ) . ", `time_sended` = " . NV_CURRENTTIME . ", `status` = 1 WHERE `id` = " . $id;
				$db->sql_query( $update );
			}
		}
	}
	return true;
}

function GetNews( $listid, $tablenews )
{
	global $db;
	
	$newsmodule = explode( "_", $tablenews );
	$newsmodule = array_map( "trim", $newsmodule );
	
	$listid = explode( ",", $listid );
	
	$global_array_cat_news = array();
	$sql = "SELECT catid, alias FROM `" . NV_PREFIXLANG . "_" . $newsmodule[2] . "_cat` ORDER BY `order` ASC";
	$result_cat = $db->sql_query( $sql );
	while( list( $catid, $alias ) = $db->sql_fetchrow( $result_cat ) )
	{
		$global_array_cat_news[$catid] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=news&amp;" . NV_OP_VARIABLE . "=" . $alias;
	}
	
	$fulllink = '';
	foreach( $listid as $id )
	{
		$sql = "SELECT `id`, `title`, `alias`, `catid` FROM `" . $tablenews . "` WHERE `id` = " . $id;
		$result = $db->sql_query( $sql );
		$row = $db->sql_fetchrow( $result );
		
		$link = NV_MY_DOMAIN . $global_array_cat_news[$row['catid']] . "/" . $row['alias'] . "-" . $row['id'];	
		$fulllink .= "<li><a target=\"_blank\" href='" . $link . "'>" . $row['title'] . "</a></li>";	
	}
	return $fulllink;
}

?>