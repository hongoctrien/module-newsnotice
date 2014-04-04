<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-1-2010 15:23
 */

if( ! defined( 'NV_IS_MOD_NEWSNOTICE' ) ) die( 'Stop!!!' );

$email = $nv_Request->get_string( 'email', 'get', '' );

if( empty( $email ) ) die();
	
$sql = "SELECT `email` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_emaillist` WHERE `email` = " . $db->dbescape_string( $email );
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );

include ( NV_ROOTDIR . "/includes/header.php" );
echo $num;
include ( NV_ROOTDIR . "/includes/footer.php" );

?>