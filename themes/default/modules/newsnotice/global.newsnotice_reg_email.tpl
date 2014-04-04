<!-- BEGIN: main -->
<div style="margin: 5px 5px 10px 5px">
	<center><img src="{NV_BASE_SITEURL}themes/default/images/send_email_group_letter.png" width="70" /></center>
	<form action="{ACTION}" method="post">
		<p style="color: red; text-align: center; margin: 10px 0 10px 0">NHẬP EMAIL ĐỂ NHẬN ĐƯỢC NHỮNG THÔNG BÁO MỚI NHẤT TỪ CỔNG THÔNG TIN ĐÀO TẠO</P>
		<input type="text" name="email" style="width: 238px" />
		<input type="submit" value="Send" name="do" />
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('input[name=do]').click(function(){
			var nv_mailfilter  = /^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,6}$/;
			var email = $('input[name=email]').val();

			if( email == '' )
			{
				alert('{LANG.error_email_empty}');
				return false;
			}
			
			if( ! nv_mailfilter.test( email ) )
			{
				alert('{LANG.error_email_type}');
				return false;				
			}

			$.ajax({
		        type: "get",
		        url: '{NV_BASE_SITEURL}' + 'index.php?' + '{NV_NAME_VARIABLE}=newsnotice&{NV_OP_VARIABLE}=checkmail',
		        data: "email=" + email,
		        success: function (a) {
		        	if( a > 0 )
		        	{
		        		alert('{LANG.error_existed_email}');
		        	}
		        	else
		        	{
		        		window.location.href = '{NV_BASE_SITEURL}' + 'index.php?' + '{NV_NAME_VARIABLE}=newsnotice&status=success&email=' + email;
		        	}
				}
			});
			return false;
		});
	});
</script>
<!-- END: main -->