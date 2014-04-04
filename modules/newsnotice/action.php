<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @Createdate Wed, 19 Mar 2014 13:32:54 GMT
 */

if ( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_emaillist`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config`";

$result = $db->sql_query( "SELECT `id`, `run_file` FROM `" . NV_CRONJOBS_GLOBALTABLE . "` ORDER BY `id` DESC" );
$is_auto = array();
while ( list( $id, $run_file ) = $db->sql_fetchrow( $result ) )
{
    $run_file = ( ! empty( $run_file ) ) ? array_map( "trim", explode( "_", $run_file ) ) : array( "", 0 );
    if ( $run_file[1] == $module_data )
    {
        $is_auto[] = $id;
    }
}
if ( $is_auto )
{
    $db->sql_query( "DELETE FROM `" . NV_CRONJOBS_GLOBALTABLE . "` WHERE `id` IN (" . implode(",", $is_auto ) .")");
}

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "` (
	`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
	`listid` varchar(30) NOT NULL,
	`time_stacked` int(11) NOT NULL DEFAULT '0',
	`time_sended` int(11) DEFAULT '0',
	`listsended` mediumtext,
	`totalsended` int(4) DEFAULT '0',
	`status` tinyint(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`))ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_emaillist` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`email` varchar(200) NOT NULL,
	`time_reg` int(11) NOT NULL DEFAULT '0',
	`time_active` int(11) NOT NULL,
	`key` varchar(50) NOT NULL DEFAULT '0',
	`status` tinyint(4) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	UNIQUE KEY `email` (`email`)) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` (
	`config_name` varchar(30) NOT NULL,
	`config_value` varchar(255) NOT NULL,
	UNIQUE KEY `config_name` (`config_name`))ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` VALUES ('active', '1')";
$sql_create_module[] = "INSERT INTO `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` VALUES ('title_email', '')"; // Tieu de email
$sql_create_module[] = "INSERT INTO `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` VALUES ('numperpage', '20')";

$result = $db->sql_query( "SHOW COLUMNS FROM `" . NV_CRONJOBS_GLOBALTABLE . "`" );
$list_field = array();
$list_value_send_stack = array();
$list_value_send_mail = array();

while ( $row = $db->sql_fetch_assoc( $result ) )
{
	if ( preg_match( "/^([a-zA-Z0-9]{2})\_cron_name$/", $row['Field'] ) )
	{
		$list_field[] = $row['Field'];
		$list_value_send_stack[] = "'News notice to stack'";
		$list_value_send_mail[] = "'News notice to mail'";
	}
}

$list_field = ", `" . implode( "`, `", $list_field ) . "`";
$list_value_send_stack = ", " . implode( ", ", $list_value_send_stack );
$list_value_send_mail = ", " . implode( ", ", $list_value_send_mail );
$interval = 120;
$interval_sendmail = $interval + 10; 

$sql_create_module[] = "INSERT INTO `" . NV_CRONJOBS_GLOBALTABLE . "` ( `id`, `start_time`, `interval`, `run_file`, `run_func`, `params`, `del`, `is_sys`, `act`, `last_time`, `last_result` " . $list_field . " ) VALUES ( NULL, " . NV_CURRENTTIME . ", " . $interval . ", 'nv_newsnotice_auto_sendmail.php', 'cron_newsnotice_send_mail', 'newsnotice, " . $lang . "_news_rows', 0, 0, 1, " . NV_CURRENTTIME . ", 1 " . $list_value_send_stack . " )";
$sql_create_module[] = "INSERT INTO `" . NV_CRONJOBS_GLOBALTABLE . "` ( `id`, `start_time`, `interval`, `run_file`, `run_func`, `params`, `del`, `is_sys`, `act`, `last_time`, `last_result` " . $list_field . " ) VALUES ( NULL, " . NV_CURRENTTIME . ", " . $interval_sendmail . ", 'nv_newsnotice_auto_sendmail.php', 'cron_newsnotice_start_send_mail', 'newsnotice, " . $lang . "_news_rows', 0, 0, 1, " . NV_CURRENTTIME . ", 1 " . $list_value_send_mail . " )";

?>