<script type='text/javascript' src='/js/libs/jquery.validation.js'></script>

<div class="container"> 

<div id="left">
<div id="settings">
<form id="settings_snstools" action="do_settings_snstools" method="post"> 
<fieldset> 
	 <!--设置导航-->
	 <div class="nav">
	 <ul>	 
	 	 <li><a href="/settings/">个人资料</a></li>
	 	 <li><a href="/settings/myface">头像设置</a></li>
	 	 <li><a href="/settings/snstools" class="active">社交工具</a></li>
		 <li><a href="/settings/account">帐号设置</a></li>
		 <li><a href="/settings/app_list">API Keys</a></li>
	 </ul>
	 </div>

	 <div class="inputs">

<?php 
if( @is_array( $settings_snstools_info ) ){
?>
<script type="text/javascript">
show_sys_info( "<?php echo $settings_snstools_info['content']; ?>" , true );		 
</script>
<?php
		 //$type = $settings_snstools_info['type'];
	 	 //echo "<div class='$type'>".$settings_snstools_info['content']."</div>";
	 }
	 ?>

	 <div class="input_cont">
         	 <label for="email">QQ：</label>
		 <input type="text" id="qq" class="text input" name="qq" value="<?php echo $user_past_info['qq']; ?>" >
	 </div>

         <div class="input_cont"> 
         	 <div for="email" class="above_input_con">新浪微博：</div>
		 <div class="input_con"><span class="prix">weibo.com/</span><input type="text" id="tsina" name="tsina" value="<?php echo $user_past_info['tsina']; ?>" /></div>
	 </div>

         <div class="input_cont"> 
		 <div for="tqq" class="above_input_con">腾讯微博：</div>
		 <div class="input_con"><span class="prix">t.qq.com/</span><input type="text" id="tqq" name="tqq" style="width:220px;" value="<?php echo $user_past_info['tqq']; ?>" /></div>
	 </div>

         <div class="input_cont"> 
		 <div for="tsohu" class="above_input_con">搜狐微博：</div>
		 <div class="input_con"><span class="prix">t.sohu.com/</span><input type="text" id="tsohu" name="tsohu"  style="width:208px;" value="<?php echo @$user_past_info['tsohu']; ?>" /></div>
	 </div>

         <div class="input_cont"> 
		 <div for="t163" class="above_input_con">网易微博：</div>
	 	 <div class="input_con"><span class="prix">t.163.com/</span><input type="text" id="t163" name="t163" style="width:212px;" value="<?php echo @$user_past_info['t163']; ?>" /></div>
	 </div>
	 
	 <div>
	 <hr class="hr"/>
	 </div>

	 <div class="input_cont"> 
		 <label for="custom">自定义：</label>	 
	 	 <textarea name="custom"><?php echo $user_past_info['custom']; ?></textarea>
	 </div>

	 <div class="submit_button">
		 <div class="button_outside_border_green" onclick="available_submit( 'settings_snstools' );">
	 	 <a href="javascript:void(0);" class="chrome_link_hack"> 
		 <div class="button_inside_border_green">
		 保存
		 </div>
	 	 </a>
		 </div>
         </div>

	 </div> 
 
</fieldset> 
</form> 

<script type="text/javascript"> 
$(document).ready(function(){
      
	$('#qq').validation({rule:'numeric' , required:false} , '格式错误' );
//	$('#tsina').validation({rule:'tsina' } , '格式应该为 http://weibo.com/用户名' );
//	$('#tqq').validation({rule:'tqq' } ,  '格式应该为 http://t.qq.com.com/用户名' );
//	$('#custom').validation({rule:'custom'} );
})
</script>

</div>
</div>

<div id="right">
<br />
</div>

</div>



