<!-- BEGIN: main -->
<form class="form-inline" action="{ACTION}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<tbody>
				<tr>
					<td width="150">{LANG.system_active}</td>
					<td><input name="active" type="checkbox" value="1" {CHECKED} /></td>
				</tr>
				<tr>
					<td>{LANG.title_email}</td>
					<td><input class="form-control" name="title_email" type="text" style="width: 300px" value="{DATA.title_email}" /></td>
				</tr>
				<tr>
					<td>{LANG.numperpage}</td>
					<td><input class="form-control" type="text" name="numperpage" value="{DATA.numperpage}" /></td>
				</tr>
				<tr>
					<td><input class="btn btn-primary" type="submit" name="save" value="{LANG.save}" /></td>
					<td>{ERROR}</td>
				</tr>
			</tbody>
		</table>
	</div>
</form>
<!-- END: main -->