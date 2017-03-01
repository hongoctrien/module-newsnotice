<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2015 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 02 Dec 2015 08:26:04 GMT
 */
define('NV_SYSTEM', true);

// Xac dinh thu muc goc cua site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __file__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';
require NV_ROOTDIR . '/includes/core/user_functions.php';

// Duyệt tất cả các ngôn ngữ
$language_query = $db->query('SELECT lang FROM ' . $db_config['prefix'] . '_setup_language WHERE setup = 1');
while (list ($lang) = $language_query->fetch(3)) {
    $mquery = $db->query("SELECT title, module_data FROM " . $db_config['prefix'] . "_" . $lang . "_modules WHERE module_file = 'newsnotice'");
    while (list ($mod, $mod_data) = $mquery->fetch(3)) {
        
        $_sql = array();
        
        $_sql[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_config (config_name, config_value) VALUES ('active_required', '1');";
        
        $_sql[] = "ALTER TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_emaillist CHANGE check_key check_key VARCHAR(50) NOT NULL DEFAULT '';";
        
        $_sql[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $mod_data . "_config (config_name, config_value) VALUES ('active_thank', '0');";
        
        if (! empty($_sql)) {
            foreach ($_sql as $sql) {
                try {
                    $db->query($sql);
                } catch (PDOException $e) {
                    //
                }
            }
            $nv_Cache->delMod($mod);
        }
    }
}

die('OK');
