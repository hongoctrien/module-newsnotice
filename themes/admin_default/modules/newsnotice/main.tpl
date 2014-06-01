<!-- BEGIN: main -->
<table class="tab1">
	<thead>
		<tr>
			<td width="50" align="center">ID</td>
			<td>{LANG.listpost}</td>
			<td width="150">{LANG.status}</td>
			<td width="120">{LANG.time_sended}</td>
			<td width="50"></td>
		</tr>
	</thead>
	
	<!-- BEGIN: loop -->
	<tbody>
		<tr>
			<td align="center">{DATA.id}</td>
			<td>
				<ul>
				<!-- BEGIN: listpost -->
				<li style="line-height: 20px;"><a style="text-decoration: none" href="{LISTPOST.link}" target="_blank">{LISTPOST.title}</a></li>
				<!-- END: listpost -->
				</ul>
			</td>
			<td>{DATA.status}</td>
			<td>{DATA.time_send}</td>
			<td align="center"><span class="delete_icon"><a href="javascript:void(0);" onclick="nv_module_del_stack({DATA.id});">{GLANG.delete}</a></span></td>
		</tr>
	</tbody>
	<!-- END: loop -->
</table>
<!-- BEGIN: generate_page -->
{GENERATE_PAGE}
<!-- END: generate_page -->
<!-- END: main -->
