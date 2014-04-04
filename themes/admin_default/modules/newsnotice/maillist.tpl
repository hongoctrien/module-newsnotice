<!-- BEGIN: main -->
<form id="fsearch" action="" action="get" onsubmit="return false;">
	<table class="tab1">
		<tr>
			<td width="40">Email</td>
			<td width="100"><input type="text" class="text" name="email" /></td>
			<td width="80">{LANG.status}</td>
			<td width="120">
				<select name="status">
					<option value="">{LANG.all}</option>
					<option value="1">{LANG.actived}</option>
					<option value="0">{LANG.noactive}</option>
				</select>
			</td>
			<td><input type="button" name="do" value="{LANG.search}"/></td>
		</tr>
	</table>
</form>
<table class="tab1">
	<thead>
		<tr>
			<td>STT</td>
			<td>{LANG.email}</td>
			<td>{LANG.time_reg}</td>
			<td>{LANG.time_active}</td>
			<td>{LANG.status}</td>
			<td></td>	
		</tr>
	</thead>
	
	<!-- BEGIN: maillist -->
	<tbody {CLASS}>
		<tr>
			<td>{ROW.stt}</td>
			<td>{ROW.email}</td>
			<td>{ROW.time_reg}</td>
			<td>{ROW.time_active}</td>
			<td width="150" align="center">
				<select id="change_status_{ROW.id}" onchange="nv_chang_status('{ROW.id}');">
					<!-- BEGIN: status -->
					<option value="{STATUS.key}"{STATUS.selected}>{STATUS.val}</option>
					<!-- END: status -->
				</select>
			</td>
			<td width="100" align="center"><span class="delete_icon"><a href="javascript:void(0);" onclick="nv_module_del({ROW.id});">{GLANG.delete}</a></span></td>
		</tr>
	</tbody>
	<!-- END: maillist -->
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
