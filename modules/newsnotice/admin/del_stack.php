<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 15:23
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$id = $nv_Request->get_int( 'id', 'post', 0 );

if( empty( $id ) ) die( 'NO_' . $id );

$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id` = " . $id;
$db->sql_query( $sql );

if( ! $db->sql_affectedrows() )
{
	die( 'NO_' . $id );
}

include ( NV_ROOTDIR . "/includes/header.php" );
echo 'OK_' . $id;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>