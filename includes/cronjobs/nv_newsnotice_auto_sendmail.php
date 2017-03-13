<?php

/**
 * @Project NUKEVIET 4.x
 * @Author mynukeviet (contact@mynukeviet.com)
 * @Copyright (C) 2014 mynukeviet. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-10-2010 18:49
 */
if (! defined('NV_MAINFILE'))
    die('Stop!!!');

if (! defined('NV_IS_CRON'))
    die('Stop!!!');

function cron_newsnotice_send_mail($module, $tablenews)
{
    global $db, $global_config, $db_config, $nv_Cache;
    
    $check = true;
    $tablenews = $db_config['prefix'] . "_" . $tablenews;
    
    $time = $db->query("SELECT time_stacked FROM " . NV_PREFIXLANG . "_" . $module . " ORDER BY id DESC LIMIT 0,1 ")->fetchColumn();
    
    if ($time) {
        $time_stacked = $time;
    } else {
        $time_stacked = NV_CURRENTTIME - (1440 * 60);
        // Neu chua gui lan nao thi mac dinh lay cac bai viet dc gui trc do 1 ngay
    }
    
    $sql = "SELECT config_name, config_value FROM " . NV_PREFIXLANG . "_" . $module . "_config";
    $list = $nv_Cache->db($sql, '', $module);
    
    $nv_module_config = array();
    foreach ($list as $values) {
        $nv_module_config[$values['config_name']] = $values['config_value'];
    }
    
    if (! empty($tablenews) and $nv_module_config['active']) {
        $sql = "SELECT SQL_CALC_FOUND_ROWS id FROM " . $tablenews . " WHERE status= 1 AND publtime >= " . $time_stacked . " AND publtime <= " . NV_CURRENTTIME . " ORDER BY publtime DESC";
        $result = $db->query($sql);
        $numrows = $result->rowCount();
        
        if ($numrows > 0) {
            $listid = array();
            while ($row = $result->fetch()) {
                $listid[] = $row['id'];
            }
            
            $listid = implode(",", $listid);
            
            $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module . "( id, listid, time_stacked, totalsended, status ) VALUES ( NULL, " . $db->quote($listid) . ", " . NV_CURRENTTIME . ", 0, 0)";
            $id_insert = $db->insert_id($sql);
            
            if ($id_insert == 0) {
                $check = false;
            }
        }
    } else {
        $check = false;
    }
    
    return $check;
}

function cron_newsnotice_start_send_mail($module, $tablenews)
{
    global $global_config, $db_config, $db, $nv_Cache;
    
    $tablenews = $db_config['prefix'] . "_" . $tablenews;
    
    $sql = "SELECT config_name, config_value FROM " . NV_PREFIXLANG . "_" . $module . "_config";
    $list = $nv_Cache->db($sql, '', $module);
    
    $nv_module_config = array();
    foreach ($list as $values) {
        $nv_module_config[$values['config_name']] = $values['config_value'];
    }
    
    $sql_email = "SELECT id, email FROM " . NV_PREFIXLANG . "_" . $module . "_emaillist WHERE status = 1";
    $result_email = $db->query($sql_email);
    $nummail = $result_email->rowCount();
    
    if ($nummail > 0) {
        $sql_send = "SELECT id, listid FROM " . NV_PREFIXLANG . "_" . $module . " WHERE status = 0";
        $result_send = $db->query($sql_send);
        
        include (NV_ROOTDIR . "/modules/" . $module . "/language/" . NV_LANG_INTERFACE . ".php");
        
        while (list ($id, $listid) = $result_send->fetch(3)) {
            if (! empty($listid)) {
                $listidmail = array();
                $count = 0;
                while ($mail = $result_email->fetch()) {
                    if ($count == 20) {
                        $count = 0;
                        sleep(2);
                        // Nghi 2 giay roi gui tiep
                    } else {
                        $result_key = $db->query("SELECT check_key FROM " . NV_PREFIXLANG . "_" . $module . "_emaillist WHERE email = " . $db->quote($mail['email']));
                        $key = $result_key->fetchColumn();
                        
                        $cancellink = $global_config['site_url'] . "/index.php?" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=main&status=cancel&email=" . $mail['email'] . "&key=" . $key;
                        
                        $content = "<div style='line-height: 25px'>";
                        $content .= sprintf($lang_module['sendmail_content_new_post'], $mail['email'], $global_config['site_name'], $global_config['site_url']);
                        $content .= '<ul>';
                        $content .= GetNews($listid, $tablenews);
                        $content .= '</ul>';
                        $content .= '------------------------------<br />';
                        $content .= "<em>" . sprintf($lang_module['sendmail_content_note'], $global_config['site_name'], $global_config['site_url'], $cancellink) . "</em>";
                        $content .= '</div>';
                        
                        $from = array(
                            $global_config['site_name'],
                            $global_config['site_email']
                        );
                        
                        if (nv_sendmail($from, $mail['email'], $nv_module_config['title_email'], $content)) {
                            $listidmail[] = $mail['id'];
                        }
                        $count ++;
                    }
                }
                
                $update = "UPDATE " . NV_PREFIXLANG . "_" . $module . " SET listsended = " . $db->quote(implode(",", $listidmail)) . ", time_sended = " . NV_CURRENTTIME . ", totalsended = " . $nummail . ", status = 1 WHERE id = " . $id;
                $db->query($update);
            }
        }
    }
    return true;
}

function GetNews($listid, $tablenews)
{
    global $db;
    
    $newsmodule = explode("_", $tablenews);
    $newsmodule = array_map("trim", $newsmodule);
    
    $listid = explode(",", $listid);
    $listid = array_diff($listid, array(
        ''
    ));
    
    $global_array_cat_news = array();
    $sql = "SELECT catid, alias FROM " . NV_PREFIXLANG . "_" . $newsmodule[2] . "_cat ORDER BY weight ASC";
    $result_cat = $db->query($sql);
    while (list ($catid, $alias) = $result_cat->fetch(3)) {
        $global_array_cat_news[$catid] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=news&amp;" . NV_OP_VARIABLE . "=" . $alias;
    }
    
    $fulllink = '';
    foreach ($listid as $id) {
        $sql = "SELECT id, title, alias, catid FROM " . $tablenews . " WHERE id = " . $id;
        $result = $db->query($sql);
        $row = $result->fetch();
        
        $link = NV_MY_DOMAIN . $global_array_cat_news[$row['catid']] . "/" . $row['alias'] . "-" . $row['id'];
        $fulllink .= "<li><a target=\"_blank\" href='" . $link . "'>" . $row['title'] . "</a></li>";
    }
    return $fulllink;
}
