<div class="container">

<!--[if lt IE 9]>
<div id="top">
<br />
</div>
<![endif]-->


<fieldset id="admin_login">

<form id="adminloginform" action="/admin/dologin" method="post">

	 <h3> <span id="logo">dazery.com</span> 后台管理系统入口 </h3>
	 
	 <br />
	 <p>
<?php 
if( isset( $admin_login_res ) ){
	 if( !$admin_login_res['res'] ){
		 //失败
		 echo $admin_login_res['info'];
	 }	 
}
?>
	 </p>
	 <p>
	 	 <label for="admin">管理员</label><br>
	 	 <input type="text" class="text" id="admin" name="admin" value="" onkeydown='press_enter_to_submit( "adminlogin" )'>
	 </p>
	 
	 <p> 
	 	 <label for="password">密码</label><br> 
	 	 <input type="password" class="text" id="password" name="password" value="" onkeydown='press_enter_to_submit( "adminlogin" )'> 
	 </p> 

	 <a href="javascript:void(0);" class="chrome_link_hack">
	 <span class="button" id="adminlogin" onclick="$( '#adminloginform' ).submit();">登录</span>	 
	 </a>
	 
</form>
</fieldset>
<?php 
//if( @$login ){
//	 echo "<script> new Effect.Shake( $('cms_login') , {distance:30 , duration:0.4}); </script>";
//}
?>
</divi

</div>

<script>
$(document).ready( function(){
if($.browser.mozilla){
$( '#adminloginform' ).find(':input').each( function(){
	 
	 this.value = null;
});
}
});

function press_enter_to_submit( button_name ){

 	 $(document).keypress( button_name , function(e) {

	 	 if ( e.which == 13 ){

			 $( '#' + button_name ).click();
		 }
	 });
}
</script>

</body>
</html>


