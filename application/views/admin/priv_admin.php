<!--用户管理模块-->
<div id="user_admin">
<?php
if( !empty( $admin_infos ) ){
//如果用户信息不为空
?>

<p>
<?php 
if( !empty( $opt_priv_res ) ){
	 
	 $info = $opt_priv_res['info'];
	 switch( $opt_priv_res['res'] ){
	 
	 	 case TRUE:
			 echo "<div class='succ'>$info</div>";
			 break;
		 case FALSE:
			 //循环显示错误信息
			 if( is_array( $info ) ){
			
				 foreach( $info as $no=>$single_info ){
					 echo "<div class='err'>$single_info</div><br />";
				 }
			 }else{
			 
				 echo "<div class='err'>$info</div>";
			 }
			 break;
	 }
}		
?>
</p>

  <table summary="用户信息表" border="0" cellspacing="0" cellpadding="0"> 
          <caption>用户信息列表</caption> 
          <thead> 
            <tr> 
              <th class="span-1">ID</th> 
	      <th class="span-2">用户名</th> 
	      <th class="span-1">权限</th> 
	      <th class="span-3">授权时间</span>
	      <th class="span-4">最后登录时间</th> 
	      <th class="span-3">最后登录ip</th>
	      <th class="span-2">操作</th>
            </tr> 
          </thead> 
          <tfoot> 
            <tr> 
              <td colspan="2"></td> 
            </tr> 
          </tfoot> 
	  <tbody>

<?php
foreach( $admin_infos as $no=>$admin_info ){
?>
            <tr>
	    <td><?php echo $admin_info['id']; ?></td>
	    <td><?php echo $admin_info['admin_name']; ?></td>
	    <td><?php echo $admin_info['priv']; ?></td>
	    <td><?php echo $admin_info['grant_time']; ?></td>
	    <td><?php echo $admin_info['last_login_time']; ?></td>
	    <td><?php echo $admin_info['last_login_add']; ?></td>
	    <td>
	 	 <a href="/admin/edit_admin_info/<?php echo $admin_info['id']; ?>" class="opt">改<a>
		 <a href="/admin/do_delete_admin/<?php echo $admin_info['id']; ?>" class="opt">删<a>
	    </td>
	    </tr>
<?php
}
}
else{
	echo '系统中没有符合条件的管理员.'; 
}
?>
          </tbody> 
        </table> 
</div>
