/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Wed, 19 Mar 2014 13:32:54 GMT
 */

$(document).ready(function() {
	$('#frm-addmaillist').submit(function(e) {
		e.preventDefault();
		$.ajax({
			type : 'POST',
			url : $(this).attr('action'),
			data : 'submit=1&emaillist=' + $('#maillist').val(),
			success : function(data) {
				var r_split = data.split('_');
				alert(r_split[1]);
				if (r_split[0] == 'OK') {
					window.location.href = window.location.href;
				} else {
					$('#maillist').focus();
				}
			}
		});
	});
});

function nv_chang_status(vid) {
	var nv_timer = nv_settimeout_disable('change_status_' + vid, 5000);
	var new_status = document.getElementById('change_status_' + vid).options[document.getElementById('change_status_' + vid).selectedIndex].value;
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=change_status&nocache=' + new Date().getTime(), 'id=' + vid + '&new_status=' + new_status, function(res) {
		nv_chang_weight_res(res);
	});
	return;
}

function nv_chang_weight_res(res) {
	var r_split = res.split("_");
	if (r_split[0] != 'OK') {
		alert(nv_is_change_act_confirm[2]);
		clearTimeout(nv_timer);
	} else {
		window.location.href = window.location.href;
	}
	return;
}

function nv_module_del(did) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=maillist&nocache=' + new Date().getTime(), 'delete=1&id=' + did, function(res) {
			nv_module_del_result(res);
		});
	}
	return false;
}

function nv_module_del_stack(did) {
	if (confirm(nv_is_del_confirm[0])) {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&nocache=' + new Date().getTime(), 'delete=1&id=' + did, function(res) {
			nv_module_del_result(res);
		});
	}
	return false;
}

function nv_module_del_result(res) {
	var r_split = res.split("_");
	if (r_split[0] == 'OK') {
		window.location.href = window.location.href;
	} else {
		alert(nv_is_del_confirm[2]);
	}
	return false;
}

function nv_list_action(action, url_action, del_confirm_no_post) {
	var listall = [];
	$('input.post:checked').each(function() {
		listall.push($(this).val());
	});

	if (listall.length < 1) {
		alert(del_confirm_no_post);
		return false;
	}

	if (action == 'delete_list_id') {
		if (confirm(nv_is_del_confirm[0])) {
			$.ajax({
				type : 'POST',
				url : url_action,
				data : 'delete_list=1&listall=' + listall,
				success : function(data) {
					var r_split = data.split('_');
					if (r_split[0] == 'OK') {
						window.location.href = window.location.href;
					} else {
						alert(nv_is_del_confirm[2]);
					}
				}
			});
		}
	}

	return false;
}

function nv_add_email(addemail) {
	$.ajax({
		type : 'POST',
		url : script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=mailcontent',
		cache : !1,
		dataType : "html"
	}).done(function(a) {
		$('#sitemodal .modal-dialog').addClass('modal-lg');
		modalShow(addemail, a)
	});
}