<script type="text/javascript" src="/js/libs/location/area.js"></script>
<script type="text/javascript" src="/js/libs/location/location.js"></script>

<form id="edit" action="/admin/do_user_admin_info_edit" method="post"> 

<?php
if( !empty( $user_info ) ){
?>

	 <h4>编辑用户信息</h4>
	 <fieldset> 
	 <legend></legend> 

	 <p>
	 <a class="opt" href="#" onclick="$('#edit').submit();">提交修改</a>
	 </p>
	 
	 <p>
<?php 
if( !empty( $update_user_info_res ) ){
	 
	 $info = $update_user_info_res['info'];
	 switch( $update_user_info_res['res'] ){
	 
	 	 case TRUE:
			 echo "<div class='succ'>$info</div>";
			 break;
		 case FALSE:
			 echo "<div class='err'>$info</div>";
			 break;
	 }
}		
?>
	 </p>
	 
 	 <div class="span-8" id="user_common_info">
	 <p>
	 <b class="input_type">用户基本信息</b>
	 </p>

	 <p>
         	 <label for="nick">用户ID( 注册时系统自动分配，不可更改 )</label><br />
		 <input type="text" class="text" name="" id="user_id" disabled="true" value="<?php echo $user_info['user_id']; ?>" /><br />
	 	 <input type="hidden" name="user_id" value="<?php echo $user_info['user_id']; ?>" />
	 </p>

	 <p>
         	 <label for="email">邮箱</label><br />
	 	 <input type="text" name="email" class="text" value="<?php echo $user_info['email']; ?>" />
	 </p>

	 <p>
         	 <label for="nick">重设密码</label><br />
		 <input type="password" class="text input" name="passwd" id="passwd" /><br />
	 </p>

	 <p>
         	 <label for="nick">用户名</label><br />
		 <input type="text" class="text input" name="nick" id="nick" value="<?php echo $user_info['nick']; ?>" /><br />
	 </p>

         <p> 
         	 <label for="describe">一句话简介</label><br />
		 <input type="text" class="text input" name="describe" id="describe" value="<?php echo $user_info['describe']; ?>" />
	 </p>

         <p > 
		 <label for="motto">个性签名</label><br />
	 	 <textarea class="input" id="motto" name="motto"><?php echo $user_info['motto']; ?></textarea>
	 </p>

         <p> 
         	 <label for="birthday">生日</label><br />
		 <input type="text" class="text input" name="birthday" id="birthday" value="<?php echo $user_info['birthday']; ?>" id="birthday" />
	 </p>

	 <p> 
         	 <label for="tel">电话</label><br />
		 <input type="text" class="text input" name="tel" id="tel" value="<?php echo $user_info['tel']; ?>" />
	 </p>
	
	 <p> 
	 	 <label for="city">城市</label><br />
		 <select id="loc_province" style="width:80px;" name="province"></select>
		 <input name="loc_province" type="hidden" />

		 <select id="loc_city" style="width:100px;" name="city"></select>
	 	 <input name="loc_city" type="hidden" />

		 <select id="loc_town" style="width:120px;" name="town"></select>
	 	 <input name="loc_town" type="hidden" />
	 </p>

	 <p> 
         	 <label for="address">地址</label><br />
		 <input type="text" class="text input" name="address" id="address" value="<?php echo $user_info['address']; ?>" />
	 </p>
	 </div>
	 
	 <div class="span-8" id="user_snstools_info">
	 <p>
	 <b class="input_type">社交工具</b>
	 </p>

	 <p>
         	 <label for="email">QQ</label><br />
		 <input type="text" id="qq" class="text input" name="qq" value="<?php echo $user_info['qq']; ?>" >
	 </p>

         <p> 
         	 <label for="email">新浪微博</label><br />
		 <input type="text" id="tsina" class="text input" name="tsina" value="<?php echo $user_info['tsina']; ?>" />
	 </p>

         <p > 
		 <label for="tqq">腾讯微博</label><br />
		 <input type="text" id="tqq" class="text input" name="tqq" value="<?php echo $user_info['tqq']; ?>" />
	 </p>
	 
	 <p > 
		 <label for="custom">自定义</label><br />	 
	 	 <textarea name="custom"><?php echo $user_info['custom']; ?></textarea>
	 </p>

	 </div>

	 <div class="span-6 last" id="user_domain_info">

	 <p>
	 <b class="input_type">个性域名</b>
	 </p>

         <p> 
		 <label for="tqq">个性域名( 谨慎修改 )</label><br />
		 <input type="text" id="domain" class="text input" name="domain" value="<?php echo $user_info['domain']; ?>" />
	 </p>

 	 <p> 
         	 
	 </p>  

	 </div>
 
</fieldset> 
<?php
}
?>
</form> 
<script type="text/javascript">
$(document).ready(function(){
	showLocation();

	//设置城市选项的默认值
	$("select[name=province]").val( '<?php echo !empty( $user_info[ 'province' ] ) ? $user_info[ 'province' ] : null ;?>' );
	$("select[name=province]").trigger( 'change' );
	$("select[name=city]").val( '<?php echo !empty( $user_info[ 'city' ] ) ? $user_info[ 'city' ] : null ;?>' );
	$("select[name=city]").trigger( 'change' );
	$("select[name=town]").val( '<?php echo !empty( $user_info[ 'town' ] ) ? $user_info[ 'town' ] : null ;?>' );
});
</script>
