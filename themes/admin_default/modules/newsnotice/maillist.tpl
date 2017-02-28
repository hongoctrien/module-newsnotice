<!-- BEGIN: main -->
<div class="well">
	<form id="fsearch" action="" action="get" onsubmit="return false;">
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<input type="text" class="form-control text" name="email" placeholder="Email" />
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<select class="form-control" name="status">
						<option value="">---{LANG.status}---</option>
						<option value="1">{LANG.actived}</option>
						<option value="0">{LANG.noactive}</option>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-md-6">
				<div class="form-group">
					<input type="button" name="do" class="btn btn-primary" value="{LANG.search}" />
				</div>
			</div>
		</div>
	</form>
</div>

<table class="table table-striped table-bordered table-hover">
	<colgroup>
		<col class="w50" />
	</colgroup>
	<thead>
		<tr>
			<th class="text-center">STT</th>
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
			<td class="text-center">{ROW.stt}</td>
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
{GENERATE_PAGE}
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