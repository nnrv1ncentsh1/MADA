<div class="container"> 

<div id="reg">
<!--用户注册表单-->
<form id="login" class="well form-horizontal" action="reg/do_reg" method="post">
<fieldset>

<!--显示注册时返回的错误信息-->
<?php if( isset( $reg_error_info ) ){ ?>
<div class="alter-error">
<?php 
foreach( $reg_error_info as $title=>$info ){
	echo '<span>' . $info . '</span><br />';
}
?>
</div>
<?php } ?>

	<div class="auth-title">
		<h5 class="auth-title-title_content">注册新用户</h5>
	</div>

	<div class="control-group">
		<label class="control-label" for="email">邮箱</label>
		<div class="controls">
			<input type="text" class="input-xlarge" id="email" name="email">
			<span class="help-block">登录及找回密码用，不会公开</span>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="nick">用户名</label>
		<div class="controls">
			<input type="text" class="input-xlarge" id="email" name="email" placeholder="<?php echo @$user_input_info['nick']; ?>">
			<span class="help-block">4至20个字符，支持"_"或减号</span>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="province_city">城市</label>
		<div class="controls">
			<select id="loc_province" name="province" class="span2" value="<?php echo $user_input_info['province']; ?>"></select>
			<input name="loc_province" type="hidden" />
			<select id="loc_city" name="city" class="span2" value="<?php echo $user_input_info['nick'] ?>"></select>
			<input name="loc_city" type="hidden" />
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="password">密码</label>
		<div class="controls">
			<input type="password" class="input-xlarge" id="email" name="email" placeholder="<?php echo $user_input_info['passwd']; ?>">
			<span class="help-block">6至16位，区分大小写</span>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="password">确认密码</label>
		<div class="controls">
			<input type="password" class="input-xlarge" name="check_password" placeholder="<?php echo $user_input_info['check_passwd']; ?>">
			<span class="help-block"></span>
		</div>
	</div>

	<div class="form-actions">
		<button class="btn btn-success">注册</button>
	</div>  
	
</fieldset> 
</form> 

</div>

</div>
