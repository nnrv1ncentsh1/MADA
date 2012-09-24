<div class="container">

<div id="auth">
<!--用户登录表单-->
<form id="login" class="well form-horizontal" action="/do_login" method="post">
<!--oauth call_back uri etc.-->
<input type="hidden" name="call_back" value="<?php echo @$call_back;?>">
<input type="hidden" name="requester_name" value="<?php echo @$requester_name;?>">
<input type="hidden" name="requester_email" value="<?php echo @$requester_email;?>">
<fieldset>

	<div class="auth-title">
		<h5 class="auth-title-title_content">登录大智若愚</h5>
		<span class="auth-title-reg_link"><a href="/reg">注册新用户</a></span>
	</div>

<!--显示登录时的错误信息-->
<?php if( isset( $auth_error_info ) ){ ?>
<div class="alert alert-error">
<?php
	foreach( $auth_error_info as $title=>$info ){
		echo '<span>' . $info . '</span><br />';
	}
?>
</div>
<?php } ?>

	<div class="control-group">
		<label class="control-label" for="email">邮箱</label>
		<div class="controls">
			<input type="text" class="input-xlarge" id="email" name="email">
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="password">密码</label>
		<div class="controls">
			<input type="password" class="input-xlarge" id="password" name="password">
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<label class="checkbox" for="auto_login"><input type="checkbox" name="auto_login" />下次自动登录</label>
		</div>
	</div>

	<div class="form-actions">
		<button class="btn btn-success" type="do_login">登录</button>
	</div>

</fieldset>
</form>
</div>
</div>
