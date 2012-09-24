<form id="edit" action="/admin/do_add_new_admin" method="post"> 

<?php
if( !empty( $admin_info ) ){
?>

	 <h4>添加新的管理员</h4>
	 <fieldset> 
	 <legend></legend> 

	 <p>
	 <a class="opt" href="#" onclick="$('#edit').submit();">提交</a>
	 </p>
	 
	 <p>
<?php 
if( !empty( $do_add_new_admin_res ) ){
	 
	 $info = $do_add_new_admin_res['info'];
	 switch( $do_add_new_admin_res['res'] ){
	 
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
         	 <label for="nick">管理员账号</label><br />
		 <input type="text" class="text input" name="admin_name" id="admin_name" value="" /><br />
	 </p>
	 <p>
         	 <label for="paswd">管理员密码</label><br />
		 <input type="text" class="text input" name="passwd" id="passwd" value="" /><br />
	 </p>
	 <p>
         	 <label for="priv">管理员权限</label><br />
		 <select name="priv">
			 <option value="common">普通管理员</option>
			 <option value="super">超级管理员</option>
	 	 </select>
	 </p>
	 </div>
 
</fieldset> 
<?php
}
?>
 </form> 

