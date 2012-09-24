<script type='text/javascript' src='/js/libs/jquery.validation.js'></script>

<div class="container"> 

<div id="auth">
<!--用户找回密码表单-->
<form id="send_mail" action="/auth/do_sendmail" method="post"> 
<fieldset> 

	 <div class="title">
	 <h5>发送找回密码的邮件</h5>
	 </div>

<!--显示错误信息-->
<?php 
if( isset( $lostpwd_info ) ){ 
$type = $lostpwd_info['type'];
echo "<div class='$type'>";
echo '<span>' . $lostpwd_info['content'] . '</span><br />';
echo '</div>';
}
?>
	 <div class="inputs">

	 <div class="input_cont">
         	 <label for="lostpwd_email">邮箱</label>
		 <input type="text" class="text input" name="lostpwd_email" value="" id="lostpwd_email" /> 
	 </div>

	 <div class="lostpwd_submit">
	 	 <a href="javascript:viod(0);" class="chrome_link_hack">
	 	 <div class="button_outside_border_green" onclick="available_submit( 'send_mail' );">
		 <div class="button_inside_border_green">
		 找回密码
		 </div>
		 </div>
	 	 </a>
	 </div>

	 </div>
 
</fieldset> 
</form> 

</div>

<script type="text/javascript"> 
$(document).ready(function(){
      
$('#lostpwd_email').validation({rule:'lostpwd_email' , required:true} , 'Email为空或格式错误' );

$('#email').qtip({
content: '登录时用的邮箱',
position: {
	 corner: {
          	 target: 'rightMiddle',
          	 tooltip: 'leftMiddle'
         }
},
style: { 
	 border: {
         	 width: 5,
         	 radius: 0
         },
	 padding: 5, 
	 textAlign: 'center',
	 tip: true, 
	 name: 'blue' 
}
});

});
</script>

</div>
