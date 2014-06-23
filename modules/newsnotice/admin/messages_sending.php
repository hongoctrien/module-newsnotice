<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @Createdate Wed, 19 Mar 2014 13:32:54 GMT
 */

if ( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
$contents = '';
$nv_config_module = GetConfigValue();

$contents .= "<div id=\"progress\" style=\"width:100%; height: 12px; border:1px solid #ccc;\"></div><div id=\"information\" style=\"width\">cdgh</div>";
$contents .= "<div id='img_loading'><img src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\" alt=\"Loading\"/></div>";
$contents .= "<div style=\"width: 99%; height: 200px; overflow: auto; background-color:#fff; padding: 5px; margin-top: 10px; border: 2px solid #ddd\" id=\"log\"></div>";

echo nv_admin_theme( $contents );

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array_data['title'] = $nv_Request->get_string( 'title', 'post' , '' );
	$array_data['content'] = nv_editor_filter_textarea( 'content', '', NV_ALLOWED_HTML_TAGS );
	$array_data['option'] = $nv_Request->get_string( 'option', 'post' , '' );
	$array_data['object'] = $nv_Request->get_string( 'object', 'post' , '' );
	
/*
	if( empty( $array_data['title'] ) )
	{
		$error = $lang_module['message_error_title_empty'];
	}
	elseif( empty( $array_data['content'] ) )
	{
		$error = $lang_module['message_error_content_empty'];
	}
	elseif( empty( $array_data['option'] ) )
	{
		$error = $lang_module['message_error_option_empty'];
	}
	elseif( empty( $array_data['object'] ) )
	{
		$error = $lang_module['message_error_object_empty'];
	}

	else*/
	
	if( 1 == 1 )
	{
		$array_data['content'] = nv_editor_nl2br( $array_data['content'] );
		
		// Gui ngay lap tuc
		if( $array_data['option'] == 'now' )
		{
			// Gui cho danh sach dang ky nhan tin
/*
			if( $array_data['object'] == 'maillist' )
			{
				$sql_email = "SELECT `id`, `email` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_emaillist` WHERE `status` = 1";
				$result_email = $db->sql_query( $sql_email );
				
				$count = 0;
				while( $row = $db->sql_fetchrow( $result_email ) )
				{
					if( $count == $nv_config_module['nummail'] )
					{
						$count = 0;
						sleep( 5 );
					}
					else 
					{
						$count++;
						$xtpl->assign( 'DATA', $row );
						$xtpl->parse( 'main.loop' );
					}
				}
			}*/
			
			if (ob_get_level() == 0) ob_start();
			
			$textarea = '';
			$report = array();
			$total = 3;
			for ($i = 1; $i<=$total; $i++){
				if( $count == $nv_config_module['nummail'] )
				{
					$count = 0;
					sleep( 2 );
				}
				else
				{
					$count++;
					$percent = intval($i/$total * 100)."%";
					ob_flush();    
			        flush();
					sleep(1);
					
					$mail = 'mail@mail.com';
					$status = SendMail( $mail );
					if( $status )
					{
						$report['success'][] = $mail;
					}
					else
					{
						// Gui 3 lan neu khong thanh cong thi bo qua
						$sended = 0;
						for( $j == 0; $j < 2; $j++  )
						{
							if( SendMail( $mail ) )
							{
								$sended = 1;
								break;
							}
						}
						
						if( $sended )
						{
							$status = 1;
							$report['success'][] = $mail;
						}
						else
						{
							$status = 0;
							$report['failed'][] = $mail;
						}
					}
					
					// Processing bar
				    echo '<script language="javascript">
				    document.getElementById("progress").innerHTML="<div style=\"width:' . $percent . ';background-color:#ddd;\">&nbsp;</div>";
				    document.getElementById("information").innerHTML="' . $i . '/' . $total . ' row(s) processed.";</script>';
					
					// Log
					$class = $status ? '' : 'class=\'error\'';
					$textarea .= '<span ' . $class . '>' . $i . '-' . ( $status ? 'Thanh cong' : 'That bai' ) . '</span><br />';
				    echo '<script language="javascript">
				    document.getElementById("log").innerHTML="' . $textarea . '";
				    </script>';
					
			        echo str_pad('',4096)."\n";
				}
			}

		    echo '<script language="javascript">
		    document.getElementById("img_loading").innerHTML="Tong: ' . $total . '. Thanh cong: ' . count( $report['success'] ) . '. That bai: ' . count( $report['failed'] ) . '";
			</script>';
			
			ob_end_flush();
		}
	}
}

function SendMail( $mail )
{
	return rand(0, 1);
}

?>