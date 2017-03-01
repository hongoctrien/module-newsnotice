<!-- BEGIN: main -->
<div class="well">
	<form id="fsearch" action="" action="get" onsubmit="return false;">
		<div class="row">
			<div class="col-xs-12 col-md-4">
				<div class="form-group">
					<input type="text" class="form-control text" name="email" placeholder="Email" />
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="form-group">
					<select class="form-control" name="status">
						<option value="">---{LANG.status}---</option>
						<option value="1">{LANG.actived}</option>
						<option value="0">{LANG.noactive}</option>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-2">
				<div class="form-group">
					<input type="button" name="do" class="btn btn-primary" value="{LANG.search}" />
				</div>
			</div>
		</div>
	</form>
</div>

<form class="form-inline m-bottom">
	<select class="form-control" id="action_top">
		<!-- BEGIN: action_top -->
		<option value="{ACTION.key}">{ACTION.value}</option>
		<!-- END: action_top -->
	</select>
	<button class="btn btn-primary" onclick="nv_list_action( $('#action_top').val(), '{BASE_URL}', '{LANG.error_empty_data}' ); return false;">{LANG.perform}</button>
	<button class="btn btn-primary" onclick="nv_add_email('{LANG.addemail}'); return false;">{LANG.addemail}</button>
</form>

<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
	<table class="table table-striped table-bordered table-hover">
		<colgroup>
			<col class="w50" />
		</colgroup>
		<thead>
			<tr>
				<th class="text-center w50"><input name="check_all[]" type="checkbox" value="yes" onclick="nv_checkAll(this.form, 'idcheck[]', 'check_all[]',this.checked);"></th>
				<th>{LANG.email}</th>
				<th>{LANG.time_reg}</th>
				<th>{LANG.time_active}</th>
				<th>{LANG.status}</th>
				<th>&nbsp;</th>
			</tr>
		</thead>

		<tbody>
			<!-- BEGIN: maillist -->
			<tr>
				<td class="text-center"><input type="checkbox" onclick="nv_UncheckAll(this.form, 'idcheck[]', 'check_all[]', this.checked);" value="{ROW.id}" name="idcheck[]" class="post"></td>
				<td>{ROW.email}</td>
				<td>{ROW.time_reg}</td>
				<td>{ROW.time_active}</td>
				<td width="150" class="text-center"><select class="form-control" id="change_status_{ROW.id}" onchange="nv_chang_status('{ROW.id}');">
						<!-- BEGIN: status -->
						<option value="{STATUS.key}"{STATUS.selected}>{STATUS.val}</option>
						<!-- END: status -->
				</select></td>
				<td width="100" class="text-center"><em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="javascript:void(0);" onclick="nv_module_del({ROW.id});">{GLANG.delete}</a></td>
			</tr>
			<!-- END: maillist -->
		<tbody>
	</table>
</form>

<form class="form-inline m-bottom">
	<select class="form-control" id="action_bottom">
		<!-- BEGIN: action_bottom -->
		<option value="{ACTION.key}">{ACTION.value}</option>
		<!-- END: action_bottom -->
	</select>
	<button class="btn btn-primary" onclick="nv_list_action( $('#action_bottom').val(), '{BASE_URL}', '{LANG.error_empty_data}' ); return false;">{LANG.perform}</button>
</form>

<!-- BEGIN: generate_page -->
<div class="text-center">{GENERATE_PAGE}</div>
<!-- END: generate_page -->

<script type="text/javascript">
$(document).ready(function(){
	$('input[name=do]').click(function(){
		var email = $('input[name=email]').val();
		var status = $('select[name=status]').val();
		if ( email != '' || status != '' ){
			$('#fsearch input, #fsearch select').attr('disabled', 'disabled');
			window.location = '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=maillist&search&email=' + email + '&status=' + status;
		}
	});
});
</script>
<!-- END: main -->