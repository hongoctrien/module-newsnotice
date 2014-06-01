<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 18:49
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'post', 0 );
if( empty( $id ) ) die( "NO_" . $id );

$new_status = $nv_Request->get_bool( 'new_status', 'post' );
$new_status = ( int )$new_status;
$time_active = $new_status ? NV_CURRENTTIME : 0;

$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_emaillist` SET `time_active` = " . $time_active . ", `status`=" . $new_status . " WHERE `id`=" . $id;
if( ! $db->sql_query( $sql ) )
{
	die( 'NO_' . $id );
}

nv_del_moduleCache( $module_name );

include ( NV_ROOTDIR . "/includes/header.php" );
echo 'OK_' . $id;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>