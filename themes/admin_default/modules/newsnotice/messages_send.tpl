<!-- BEGIN: main -->

<!-- BEGIN: error -->
<div style="color: red">{ERROR}</div>
<!-- END: error -->

<form action="{ACTION}" method="post" id="frm_send">
	<table class="tab1">
		<tbody>
			<tr>
				<td width="100">{LANG.message_title}</td>
				<td><input type="text" name="title" value="{DATA.title}" style="width: 50%" /></td>
			</tr>
		</tbody>
		
		<tbody>
			<tr>
				<td>{LANG.message_content}</td>
				<td>{CONTENT}</td>
			</tr>
		</tbody>
		
		<tbody>
			<tr>
				<td>{LANG.message_option}</td>
				<td>
					<!-- BEGIN: option -->
					<label><input type="radio" name="option" value="{OPTION.value}" {OPTION.checked} />{OPTION.title}</label>
					<!-- END: option -->
				</td>
			</tr>
		</tbody>
		
		<tbody>
			<tr>
				<td>{LANG.message_object}</td>
				<td>
					<!-- BEGIN: object -->
					<label><input type="radio" name="object" value="{OBJECT.value}" {OBJECT.checked} />{OBJECT.title}</label>
					<!-- END: object -->
				</td>
			</tr>
		</tbody>
		
		<tfoot>
			<tr>
				<td colspan="2"><input type="submit" name="submit" value="{LANG.send}" /></td>
			</tr>
		</tfoot>
	
	</table>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		//$('#frm_sending').hide();
		
		$( "#frm_send" ).submit(function() {
			$('#frm_sending').show();
		});
	});
</script>

<!-- END: main -->