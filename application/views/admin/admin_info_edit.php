<form id="edit" action="/admin/do_user_admin_info_edit" method="post"> 

<?php
if( !empty( $admin_info ) ){
?>

	 <h4>编辑管理员信息</h4>
	 <fieldset> 
	 <legend></legend> 

	 <p>
	 <a class="opt" href="#" onclick="$('#user_admin_info_edit').submit();">提交修改</a>
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
	 <b class="input_type">管理员信息</b>
	 </p>
	 <p>
         	 <label for="nick">管理员ID( 系统自动分配 ， 不可修改 )</label><br />
		 <input type="text" class="text" name="" id="user_id" disabled="true" value="<?php echo $admin_info['id']; ?>" /><br />
	 	 <input type="hidden" name="user_id" value="<?php echo $admin_info['id']; ?>" />
	 </p>
	 <p>
         	 <label for="nick">管理员账号</label><br />
		 <input type="text" class="text input" name="nick" id="nick" value="<?php echo $admin_info['admin_name']; ?>" /><br />
	 </p>
	 <p>
         	 <label for="nick">管理员密码</label><br />
		 <input type="text" class="text input" name="nick" id="nick" value="" /><br />
	 </p>
	 <p>
         	 <label for="nick">管理员权限</label><br />
		 <input type="text" class="text input" name="nick" id="nick" value="<?php echo $admin_info['priv']; ?>" /><br />
	 </p>
	 </div>
 
</fieldset> 
<?php
}
?>
 </form> 

