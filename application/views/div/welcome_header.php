<!--仅用在未登录页面的头部元素(包含了登录表单)-->
<div id="header">
<div class="navbar navbar-fixed-top">
<div class="navbar-inner">
	<div class="container">
	<!--页首用户登录表单-->
	<a class="brand" href="#">Heisenberg</a>
	<div class="nav-collapse">
		<ul class="nav pull-right">
			<li class="active"><button class="btn" class="do_login">登录</button></li>
		</ul>
	</div>
	<form class="navbar-form pull-right" id="header_login_form" action="auth/do_login" method="post">
		<input type="text" class="span2" name="email" id="loginemail" tabindex='1' placeholder="邮箱"/>
		<input type="password" class="span2" name="passwd" id="loginpassword" tabindex='2' placeholder="密码"/>
		<input type="submit" />
	</form>
	</div><!--end of container-->
</div><!--end of navbar-inner-->
</div><!--end of navbar-->
</div><!--end of header-->

<?php include( 'welcome_sub_header.php' ); ?>

<script type="text/javascript">
document.body.onload = function(){
	$( ".do_login" ).click( function(){
		alert( 213 );
		$( "#header_login_form" ).submit();
	});
}
</script>

