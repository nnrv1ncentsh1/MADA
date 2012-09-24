<script type='text/javascript' src='/js/libs/jquery.validation.js'></script> 
<script type="text/javascript" src="/js/libs/cal.js"></script> 
<link href="/css/calendar.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/js/libs/location/area.js"></script>
<script type="text/javascript" src="/js/libs/location/location.js"></script>

<script type="text/javascript" src="/js/libs/maxlength.js"></script>

<div class="container">

<!--更新用户信息-->
<div class="left">
<div id="settings">
<form id="update_user_info" action="/settings/do_settings_common" method="post"> 
<fieldset>

	 <!--设置导航-->
	 <div class="nav">
	 <ul>
	 	 <li><a href="/settings/" class="active">个人资料</a></li>
	 	 <li><a href="/settings/myface">头像设置</a></li>
	 	 <li><a href="/settings/snstools">社交工具</a></li>
		 <li><a href="/settings/account">帐号设置</a></li>
	 	 <li><a href="/settings/app_list">API Keys</a></li>
	 </ul>
	 </div>

	 <div class="inputs">
	
<?php 
if( is_array( $settings_common_info ) ){
?>
<script type="text/javascript">
show_sys_info( "<?php echo $settings_common_info['info'] ?>" , true );
</script>
<?php
}
?>

	 <div class="input_cont">
         	 <label for="nick">用户名：</label>
		 <input type="text" class="text spec_input input" name="nick" id="nick" value="<?php echo $user_past_info['nick']; ?>" /><br />
	 	 <div class="hint">4至20个字符，支持"_"或减号</div>
	 </div> 

         <div class="input_cont"> 
         	 <label for="describe">一句话简介：</label>
		 <input type="text" class="text input" name="describe" id="describe" value="<?php echo $user_past_info['describe']; ?>" />
	 	 <div class="hint">不超过40个字符</div>
	 </div>

         <div class="input_cont"> 
		 <label for="motto">个性签名：</label>
		 <textarea class="" id="motto" name="motto" data-maxsize="200" data-output="status2" wrap="virtual"><?php echo $user_past_info['motto']; ?></textarea>
	 	 <div id="status2" class="hint"></div>
	 </div>

	 <div class="input_cont" id="province_city">
	 	 <label for="city" class="spc">城市：</label>
		 <select id="loc_province"  name="province" class="text input city"></select>
		 <input name="loc_province" type="hidden" />

		 <select id="loc_city" style="width:100px;" name="city" class="input text"></select>
	 	 <input name="loc_city" type="hidden" />

		 <select id="loc_town" style="width:122px;" name="town" class="input text"></select>
		 <input name="loc_town" type="hidden" />
	 </div>

         <div class="input_cont"> 
         	 <label for="birthday">生日：</label>
		 <input type="text" class="text input" name="birthday" id="birthday" value="<?php echo $user_past_info['birthday']; ?>" id="birthday" />
	 </div>

	 <div class="input_cont"> 
         	 <label for="tel">电话：</label>
		 <input type="text" class="text input" name="tel" id="tel" value="<?php echo $user_past_info['tel']; ?>" />
	 </div>

	 <div class="input_cont"> 
		 <label for="address">地址：</label>
		 <textarea class="" id="address" name="address" data-maxsize="140" data-output="status1" wrap="virtual"><?php echo $user_past_info['address']; ?></textarea>
	 	 <div id="status1" class="hint"></div>
	 </div>

	 <div class="submit_button">
		 <div class="button_outside_border_green" onclick="available_submit( 'update_user_info' );">
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

</div>
</div>

<div id="right">
<br />
</div>

</div>

<script type="text/javascript"> 
$(document).ready(function(){
       
	$('#nick').validation({rule:'nick' , required:true} , '格式错误' );
	$('#describe').validation({rule:'describe' , required:true} , '不超过40字符' );
	$('#tel').validation({rule:'numeric' , required:false} ,  '仅能为数字' );
	$('#loc_city').validation({rule:'city' , required:true} ,  '选择城市' );	
	$('#birthday').simpleDatepicker({ startdate: 1912, enddate:2012 });
	
	showLocation();
	//设置城市选项的默认值
	$("select[name=province]").val( '<?php echo $user_past_info[ 'province' ];?>' );
	$("select[name=province]").trigger( 'change' );
	$("select[name=city]").val( '<?php echo $user_past_info[ 'city' ];?>' );
	$("select[name=city]").trigger( 'change' );
	$("select[name=town]").val( '<?php echo $user_past_info[ 'town' ];?>' );
});
</script> 



