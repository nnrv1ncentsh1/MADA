<script type='text/javascript' src='/js/libs/jquery.validation.js'></script> 
<div class="container"> 

<div id="auth">
<!--用户找重设码表单-->
<form id="send_mail" action="/auth/do_resetpassword" method="post"> 
<fieldset> 

	 <div class="title">
	 <h5>输入新密码</h5>
	 </div>

<!--显示错误信息-->
<?php 
if( !empty( $reset_passwd_res ) ){ 
$type = $reset_passwd_res['res'] ? 'success' : 'error' ;
echo "<div class='$type'>";
echo '<span>' . $reset_passwd_res['info'] . '</span><br />';
echo '</div>';
}
?>

	 <div class="inputs">

	 <div class="input_cont">
         	 <label for="password">密码</label>
		 <input type="password" class="text input" name="password" value="" id="password"><br />
	 	 <span class="hint">6至16位，区分大小写</span>
	 </p>

	 <div class="input_cont">
         	 <label for="check_password">密码</label>
		 <input type="password" class="text input" name="check_password" value="" id="check_password"><br />
		 <span class="hint">确认密码</span>
	 </p>

	 <div class="submit_button">
	 <input type="hidden" value="<?php echo $confirmation_code; ?>" name="confirmation_code"/>
	 	 <div class="button_outside_border_green" onclick="available_submit( 'send_mail' );">
		 <div class="button_inside_border_green">
		 修改
		 </div>
		 </div>
	 </p>

	 </div>
 
</fieldset> 
</form> 

</div>

</div>

<script type="text/javascript"> 
$(document).ready(function(){
       
	$('#password').validation({rule:'password' , required:true} , '密码格式不正确' );
	$('#check_password').validation({rule:'match' , el:'#password' } ,  '两次密码不匹配' );
	
})
</script>
