<!-- BEGIN: main -->

<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<form action="{ACTION}" method="post" class="form-horizontal">
	<div class="panel panel-default">
		<div class="panel-heading">{LANG.system}</div>
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-3 text-right"><strong>{LANG.system_active}</strong></label>
				<div class="col-sm-21">
					<input name="active" type="checkbox" value="1" {DATA.ck_active} />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label"><strong>{LANG.title_email}</strong></label>
				<div class="col-sm-21">
					<input class="form-control" name="title_email" type="text" value="{DATA.title_email}" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label"><strong>{LANG.numperpage}</strong></label>
				<div class="col-sm-21">
					<input class="form-control" type="text" name="numperpage" value="{DATA.numperpage}" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 text-right"><strong>{LANG.active_required}</strong></label>
				<div class="col-sm-21">
					<label><input name="active_required" type="checkbox" value="1" {DATA.ck_active_required} />{LANG.active_required_note}</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 text-right"><strong>{LANG.active_thank}</strong></label>
				<div class="col-sm-21">
					<label><input name="active_thank" type="checkbox" value="1" {DATA.ck_active_thank} />{LANG.active_thank_note}</label>
				</div>
			</div>
		</div>
	</div>
	<div class="text-center">
		<input class="btn btn-primary" type="submit" name="save" value="{LANG.save}" />
	</div>
</form>
<!-- END: main -->