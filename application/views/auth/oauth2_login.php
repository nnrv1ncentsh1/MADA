<script type='text/javascript' src='/js/libs/jquery.validation.js'></script> 
<script type='text/javascript' src='/js/libs/autotips.js'></script> 

<div class="container"> 

<div id="auth">
<!--oauth2 专用登录表单-->
<form id="login" action="/do_login" method="post"> 
<!--oauth call_back uri etc.-->
<input type="hidden" name="call_back" value="<?php echo $call_back;?>">
<input type="hidden" name="requester_name" value="<?php echo $requester_name;?>">
<input type="hidden" name="requester_email" value="<?php echo $requester_email;?>">
<fieldset>

	 <div class="title">
	 <h5>用大智若愚账号登陆 L</h5>
	 </div>

	 <div class="inputs">

         <div class="input_cont"> 
         	 <label for="email">邮箱</label>
		 <input type="text" class="text input" name="email" id="email" onkeydown='press_enter_to_submit( "loginsubmit" );'/>
	 </div>

         <div class="input_cont"> 
         	 <label for="password">密码</label>
		 <input type="password" class="text input" name="passwd" value="" id="password" onkeydown='press_enter_to_submit( "loginsubmit" );'/> 
	 </div>

	 <div class="submit_button"> 
		 <div class="left">
	 	 <a href="javascript:viod(0);" class="chrome_link_hack">
	 	 <div class="button_outside_border_green" onclick="available_submit( 'login' );" id="loginsubmit" >
		 <div class="button_inside_border_green">
		 登录
		 </div>
		 </div>
	 	 </a>	
	 	 </div>	
		 <span class="lostpwd left"><a href="/lostpwd">忘记密码 ？</a></span>
         </div>

	 </div>

	 <!--显示错误信息-->
	 <?php if( isset( $auth_error_info ) ){ ?>
	 <div class="error login_error">
	 <?php
	 	 foreach( $auth_error_info as $title=>$info ){
			 echo '<span>' . $info . '</span><br />';
	 	 }
	 ?>
	 </div>
	 <?php } ?>

</fieldset> 
</form> 

</div>

<script type="text/javascript"> 
$(document).ready(function(){
       
	 //each遍历文本框
	 $(".input").each(function() {
	 //保存当前文本框的值
		 var vdefault = this.value;
        	 $(this).focus(function() {
            	 //获得焦点时，如果值为默认值，则设置为空
            	 if (this.value == vdefault) {
                	 this.value = "";
            	 }
        });
        $(this).blur(function() {
            //失去焦点时，如果值为空，则设置为默认值
            if (this.value == "") {
                this.value = vdefault;
            }
        });
        });

	$('#email').validation({rule:'just_email' , required:true} , 'Email为空或格式错误' );
	$('#password').validation({rule:'required'} , '请输入密码' );
});

function press_enter_to_submit( button_name ){

 	 $(document).keypress( button_name , function(e) {

	 	 if ( e.which == 13 ){

			 $( '#' + button_name ).click();
		 }
	 });
}
</script>

</div>
