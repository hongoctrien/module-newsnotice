<?php

/**
 * @Project NUKEVIET 4.x
* @Author mynukeviet (contact@mynukeviet.com)
* @Copyright (C) 2014 mynukeviet. All rights reserved
* @License GNU/GPL version 2 or any later version
* @Createdate 2-10-2010 18:49
*/
if (! defined('NV_SYSTEM'))
    die('Stop!!!');

define('NV_IS_MOD_NEWSNOTICE', true);

$array_config = array();
$sql = "SELECT config_name, config_value FROM " . NV_PREFIXLANG . "_" . $module_data . "_config";
$list = $nv_Cache->db($sql, '', $module_name);

foreach ($list as $values) {
    $array_config[$values['config_name']] = $values['config_value'];
}