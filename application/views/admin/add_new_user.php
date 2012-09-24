<script type="text/javascript" src="/js/libs/location/area.js"></script>
<script type="text/javascript" src="/js/libs/location/location.js"></script>

<form id="edit" action="/admin/do_add_new_user" method="post"> 

	 <h4>添加新用户</h4>
	 <fieldset> 
	 <legend></legend> 

	 <p>
	 <a class="opt" href="#" onclick="$('#edit').submit();">提交</a>
	 </p>
	 
	 <p>
<?php 
if( !empty( $add_new_user_info_res ) ){
	 
	 $info = $add_new_user_info_res['info'];
	 switch( $add_new_user_info_res['res'] ){
	 
	 	 case TRUE:
			 echo "<div class='succ'>$info</div>";
			 break;
		 case FALSE:
			 //循环显示错误信息
			 if( is_array( $info) ){
				 foreach( $info as $no=>$single_info ){
					 echo "<div class='err'>$single_info</div>";
				 }
			 }else{
			 
			 	 echo "<div class='err'>$info</div>";
			 }
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
         	 <label for="email">用户邮箱( 登录系统时使用 )</label><br />
		 <input type="text" class="text" name="email" id="email" /><br />
	 </p>

	 <p>
         	 <label for="password">密码</label><br />
		 <input type="password" class="text input" name="passwd" id="passwd" /><br />
	 </p>

	 <p>
         	 <label for="nick">用户名</label><br />
		 <input type="text" class="text input" name="nick" id="nick" /><br />
	 </p>


         <p> 
         	 <label for="describe">一句话简介</label><br />
		 <input type="text" class="text input" name="describe" id="describe"  />
	 </p>

         <p > 
		 <label for="motto">个性签名</label><br />
	 	 <textarea class="input" id="motto" name="motto"></textarea>
	 </p>

         <p> 
         	 <label for="birthday">生日</label><br />
		 <input type="text" class="text input" name="birthday" id="birthday"  id="birthday" />
	 </p>

	 <p> 
         	 <label for="tel">电话</label><br />
		 <input type="text" class="text input" name="tel" id="tel"  />
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
		 <input type="text" class="text input" name="address" id="address" />
	 </p>
	 </div>
	 
	 <div class="span-8" id="user_snstools_info">
	 <p>
	 <b class="input_type">社交工具</b>
	 </p>

	 <p>
         	 <label for="email">QQ</label><br />
		 <input type="text" id="qq" class="text input" name="qq" />
	 </p>

         <p> 
         	 <label for="email">新浪微博</label><br />
		 <input type="text" id="tsina" class="text input" name="tsina" />
	 </p>

         <p > 
		 <label for="tqq">腾讯微博</label><br />
		 <input type="text" id="tqq" class="text input" name="tqq" />
	 </p>
	 
	 <p > 
		 <label for="custom">自定义</label><br />	 
	 	 <textarea name="custom"></textarea>
	 </p>

	 </div>

	 <div class="span-6 last" id="user_domain_info">

	 <p>
	 <b class="input_type">个性域名</b>
	 </p>

         <p> 
		 <label for="tqq">个性域名( 谨慎修改 )</label><br />
		 <input type="text" id="domain" class="text input" name="domain" />
	 </p>

 	 <p> 
         	 
	 </p>  

	 </div>
 
</fieldset> 
</form> 

<script type="text/javascript">
$(document).ready(function() {
	showLocation();
});
</script>
