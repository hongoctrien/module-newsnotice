/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @Createdate Wed, 19 Mar 2014 13:32:54 GMT
 */

function nv_chang_status(vid){
	var nv_timer = nv_settimeout_disable( 'change_status_' + vid, 5000 );
	var new_status = document.getElementById( 'change_status_' + vid ).options[document.getElementById( 'change_status_' + vid ).selectedIndex].value;
	nv_ajax( "post", script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=change_status&id=' + vid + '&new_status=' + new_status + '&num=' + nv_randomPassword( 8 ), '', 'nv_chang_weight_res' );
	return;
}

function nv_chang_weight_res(res)
{
	var r_split = res.split( "_" );
	if( r_split[0] != 'OK' ){
		alert( nv_is_change_act_confirm[2] );
		clearTimeout( nv_timer );
	}else{
		window.location.href = window.location.href;
	}
	return;
}

function nv_module_del(did){
	if (confirm(nv_is_del_confirm[0])){
		nv_ajax( 'post', script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=delete&id=' + did, '', 'nv_module_del_result' );
	}
	return false;
}

function nv_module_del_stack(did){
	if (confirm(nv_is_del_confirm[0])){
		nv_ajax( 'post', script_name, nv_name_variable+'='+nv_module_name+'&'+nv_fc_variable + '=del_stack&id=' + did, '', 'nv_module_del_result' );
	}
	return false;
}

function nv_module_del_result(res){
	var r_split = res.split( "_" );
	if(r_split[0] == 'OK'){
		window.location.href = window.location.href;
	}else{
		alert(nv_is_del_confirm[2]);
	}
	return false;
}