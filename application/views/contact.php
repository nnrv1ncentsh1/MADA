<script type='text/javascript' src='/js/libs/jquery.validation.js'></script> 
<script type='text/javascript' src='/js/libs/autotips.js'></script> 

<script type="text/javascript" src="/js/libs/location/area.js"></script>
<script type="text/javascript" src="/js/libs/location/location.js"></script>

<?php
if( empty( $object_user_info ) ){
?>
<div id="welcome_header">
<!--用户登录表单-->
<div id="welcome_header_cont">
<fieldset id="login_form">
<form id="welcome_login" action="/do_login" method="post">
	 <div class="item span-3 margin-right"> 
	 	 <label for="loginemail">邮箱</label>
		 <input type="text" class="input" name="email" value="" id="loginemail" tabindex='1' onkeydown='press_enter_to_submit( "loginsubmit" );'/> 
		 <div id="auto_login">
			 <input type="checkbox" name="auto_login" class="auto_login_check_box" />
			 <span for="auto_login" class="color_666">下次自动登录</span>
	 	 </div>
	 </div>

	 <div class="item span-3 margin-right">
		 <label for="loginpassword">密码</label>
	 	 <span id="prev_loginpassword" ></span>
		 <input type="password" class="input" name="passwd" id="loginpassword" tabindex='2' onkeydown='press_enter_to_submit( "loginsubmit" );'/> 
	 	 <span class="left" id="lostpwdlink"><a href="/lostpwd">忘记密码 ?</a></span>
	 </div>

	 <div class="item span-1 submit">
	 <div class="button" id="loginsubmit" onclick='document.forms["welcome_login"].submit();'>
		 登录
	 </div>
         </div>
</form> 
</fieldset> 
	 <div class="logo">Logo</div>
</div>
</div>

<?php 
include( getcwd().'/application/views/div/welcome_sub_header.php' );
?>

<?php
echo "<style>#welcome_left{margin-top:40px;}</style>";
}else{

include( 'div/header.php' );
echo "<style>#welcome_left{margin:0;}</style>";
}
?>

<div class="container" style="height:750px;">

<div class="span-11" id="logo_picture">
<div id="welcome_left">

<h5>
联系我们
</h5>

<p>

</p>
</div>
</div>

<?php 
include( getcwd().'/application/views/div/welcome_right.php' );
?>

</div>

<script type="text/javascript"> 
$(document).ready(function(){
	showLocation();
});
$('#loginemail')[0].focus(); 

function press_enter_to_submit( button_name ){

 	 $(document).keypress( button_name , function(e) {

	 	 if ( e.which == 13 ){

			 $( '#' + button_name ).click();
		 }
	 });
}

$(document).ready( function(){
if($.browser.mozilla){
$( '#reg' ).find(':input').each( function(){
	
	this.value = null;
});
}
});
</script>




