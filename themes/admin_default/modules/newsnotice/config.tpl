<!-- BEGIN: main -->
<form action="{ACTION}" method="post">
	<table class="tab1">
		<tbody>
			<tr>
				<td width="150">{LANG.system_active}</td>
				<td><input name="active" type="checkbox" value="1" {CHECKED} /></td>
			</tr>
		</tbody>
		
		<tbody>
			<tr>
				<td>{LANG.title_email}</td>
				<td><input name="title_email" type="text" style="width: 300px" value="{DATA.title_email}" /></td>
			</tr>
		</tbody>
		
		<tbody>
			<tr>
				<td>{LANG.numperpage}</td>
				<td><input type="text" name="numperpage" value="{DATA.numperpage}" /></td>
			</tr>
		</tbody>
		
		<tbody>
			<tr>
				<td>{LANG.nummail}</td>
				<td><input type="text" name="nummail" value="{DATA.nummail}" /></td>
			</tr>
		</tbody>
		
		<tbody>
			<tr>
				<td><input type="submit" name="save" value="{LANG.save}" /></td>
				<td>{ERROR}</td>
			</tr>
		</tbody>
	</table>
</form>
<!-- END: main -->
