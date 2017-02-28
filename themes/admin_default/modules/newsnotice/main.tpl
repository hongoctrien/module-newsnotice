<!-- BEGIN: main -->
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th width="50" class="text-center">ID</th>
				<th>{LANG.listpost}</th>
				<th width="150">{LANG.sended}</th>
				<th width="150">{LANG.status}</th>
				<th width="120">{LANG.time_sended}</th>
				<th width="50"></th>
			</tr>
		</thead>
		<tbody>
			<!-- BEGIN: loop -->
			<tr>
				<td class="text-center">{DATA.id}</td>
				<td>
					<ul>
						<!-- BEGIN: listpost -->
						<li style="line-height: 20px;"><a style="text-decoration: none" href="{LISTPOST.link}" target="_blank">{LISTPOST.title}</a></li>
						<!-- END: listpost -->
					</ul>
				</td>
				<td>{DATA.totalsended} / {DATA.countsended}</td>
				<td>{DATA.status}</td>
				<td>{DATA.time_sended}</td>
				<td class="text-center"><span class="delete_icon"><a href="javascript:void(0);" onclick="nv_module_del_stack({DATA.id});">{GLANG.delete}</a></span></td>
			</tr>
			<!-- END: loop -->
		<tbody>
	</table>
</div>

<!-- BEGIN: generate_page -->
<div class="text-center">{GENERATE_PAGE}</div>
<!-- END: generate_page -->

<!-- END: main -->