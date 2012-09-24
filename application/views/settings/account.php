<script type='text/javascript' src='/js/libs/jquery.validation.js'></script> 

<div class="container"> 

<div id="left">
<div id="settings">
<fieldset> 
	 <!--设置导航-->
	 <div class="nav">
	 <ul>	 
	 	 <li><a href="/settings/">个人资料</a></li>
	 	 <li><a href="/settings/myface">头像设置</a></li>
	 	 <li><a href="/settings/snstools">社交工具</a></li>
		 <li><a href="/settings/account" class="active">帐号设置</a></li>
	 	 <li><a href="/settings/app_list">API Keys</a></li>
	 </ul>
	 </div>

	 <div class="inputs">

<!--修改用户账户信息表单-->
<form id="setting_account" action="do_settings_account" method="post"> 
	 <div class="input_cont">
	 	 <!--不能修改-->
		 <label for="email">邮箱：</label>
		 <b class="user_email"><?php echo $user_past_info['email'];?></b>
		 <input type="hidden" class="text input disable" name="email" value="<?php echo $user_past_info['email'] ?>" disabled="true" /><br />
	 </div>

         <div class="input_cont"> 
         	 <label for="domain">个性域名：</label>
		 <input type="text" name="domain" id="domain" <?php 
	 if( $user_past_info['domain_has_change'] != 0 ){
	 	 //个性域名已经被修改过
	 	 echo ' value="' . $user_past_info['domain'] . '" disabled="true" class="text input disable"';	 
	 }else{
		 //还没有设置
	 	 echo ' class="text input"'; 
	 }

	 	 ?>/><br />
		 <span class="hint">
	 	 只能修改一次，5至20位的英文或数字
	 	 </span>
	 </div>
<?php
if( $user_past_info['domain_has_change'] == 0 ){
?>
         <div class="submit_button"> 
		 <div class="button_outside_border_green" onclick="available_submit('setting_account');">
	 	 <a href="javascript:void(0);" class="chrome_link_hack">
		 <div class="button_inside_border_green">
		 保存设置
		 </div>
	 	 </a>
		 </div>
	 </div>
<?php 
}else{
?>
	 <div class="submit_button_for_domain"> 
		 <div class="button_outside_border_gray">
	 	 <a href="javascript:void(0);" class="chrome_link_hack">
		 <div class="button_inside_border_gray">
		 保存设置
		 </div>
	 	 </a>
		 </div>
	 </div>
<?php
}
?>	 
	 </div>
</form> 

<p>
</p>

<!--修改密码表单-->
<form id="settings_password" action="do_settings_password" method="post">

	 <div class="inputs">
	
	 <hr class="hr"/>

<?php 
if( is_array( $settings_password_info ) ){
	 	 
		 //$type = $settings_password_info['type'];
		 //echo "<div class='$type'>".$settings_password_info['content']."</div>";
?>
<script type="text/javascript">
show_sys_info( "<?php echo $settings_password_info['content'] ?>" , true );		 
</script>
<?php
	 }
	 ?>

	 <div class="input_cont">  
         	 <label for="old_password">旧密码：</label>
		 <input type="password" class="text input" name="old_password" value="" />
	 </div>

         <div class="input_cont"> 
          	 <label for="new_password">新密码：</label>
		 <input type="password" class="text input" name="new_password" value="" id="new_password"/>
	 	 <span class="hint">6至16位，区分大小写</span>
	 </div>

	 <p>
	 </p>

	 <div class="submit_button"> 
		 <div class="button_outside_border_green" onclick="available_submit('settings_password')";>
	 	 <a href="javascript:void(0);" class="chrome_link_hack">
		 <div class="button_inside_border_green">
		 修改密码
		 </div>
	 	 </a>
		 </div>
	 </div>
	 
	 </div>
</form> 

</fieldset> 

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

	$('#domain').validation({rule:'domain' , required:true} , '格式不正确' );
	$('#new_password').validation({rule:'password' , required:true} , '密码格式不正确' );
	
});

$(document).ready( function(){
if($.browser.mozilla){
$( '#settings' ).find('input[name=old_password]').each( function(){
	 
	 this.value = null;
});
}
});
</script>

</div>
</div>

<div id="right">
<br />
</div>

</div>


