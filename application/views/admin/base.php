<div id="main_nav"> 
<ul>
	<li><span><a href="/admin/dologout" class="exit">退出后台</a></span></li>
	<li><a href="/admin/user_admin" <?php if( @$active=='user' ) { ?> class="active" <?php } ?> >用户管理</a></li>
	<li><a href="/admin/solution_admin" <?php if( @$active=='solution' ) { ?> class="active" <?php } ?>>方案管理</a></li>
	<li><a href="/admin/tran_admin" <?php if( @$active=='tran' ) { ?> class="active" <?php } ?>>交易管理</a></li>
	<li><a href="/admin/secur_admin" <?php if( @$active=='secur' ) { ?> class="active" <?php } ?>>安全设置</a></li>
	<li><a href="/admin/priv_admin" <?php if( @$active=='priv' ) { ?> class="active" <?php } ?>>权限管理</a></li>
</ul>
</div>

<div id="sub_nav" class="span-24">
<?php 
if( isset( $sub_nav ) ){
	 include ( $sub_nav . '.php' );
}
?>
</div>

<div class="container">

<div id="left" class="span-0">
<br />
</div>

<div id="admin" class="span-24">

<?php 

if( isset( $include_page ) ){
	 include ( $include_page . '.php' );
}

?>

</div>

<div id="right" class="span-0">
<br />
</div>

</div>

<div id="footer">
<!--img src="/picture/common/sphinx_blog.png" width="80" height="15" border="0" alt="powered by Sphinx"><br/-->
<?php 
if( isset( $admin_info ) ){
?>
<span class="admin_info">
	 <span>管理员<?php echo $admin_info['admin_name'] ?></span><br/>
	 <span>上次登录时间<?php echo $admin_info['last_login_time'] ?></span><br/>
	 <span>上次登录ip<?php echo $admin_info['last_login_add'] ?></span><br/>
	 <span>权限<?php echo $admin_info['priv'] ?></span>
<span>
<?php
}
?>
</div>

</body>
</html>
