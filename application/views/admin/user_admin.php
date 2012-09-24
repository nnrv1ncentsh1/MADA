<!--用户管理模块-->
<div id="user_admin">
<?php
if( !empty( $user_infos ) ){
//如果用户信息不为空
?>

<p>
	 <a href="/admin/user_admin/follow_count/asc" class="<?php echo @$now_sort_rule == 'follow_count_asc' ? 'now_sort_rule' : 'sort_lable'; ?>" >粉丝数升序</a>
	 <a href="/admin/user_admin/follow_count/desc" class="<?php echo @$now_sort_rule == 'follow_count_desc' ? 'now_sort_rule' : 'sort_lable'; ?>" >粉丝数降序</a>
	 <a href="/admin/user_admin/reg_time/asc" class="<?php echo @$now_sort_rule == 'reg_time_asc' ? 'now_sort_rule' : 'sort_lable'; ?>" >注册时间升序</a>
	 <a href="/admin/user_admin/reg_time/desc" class="<?php echo @$now_sort_rule == 'reg_time_desc' ? 'now_sort_rule' : 'sort_lable'; ?>" >注册时间降序</a>
</p>

<p>
<?php 
if( !empty( $opt_user_res ) ){
	 
	 $info = $opt_user_res['info'];
	 switch( $opt_user_res['res'] ){
	 
	 	 case TRUE:
			 echo "<div class='succ'>$info</div>";
			 break;
		 case FALSE:
			 //循环显示错误信息
			 foreach( $info as $no=>$single_info ){
				 echo "<div class='err'>$single_info</div><br />";
			 }
			 break;
	 }
}		
?>
</p>

  <table summary="用户信息表" border="0" cellspacing="0" cellpadding="0"> 

	 <caption>

用户信息列表
<?php 
if( isset( $total_rows ) ){
	 
	 include( getcwd().'/application/views/div/admin_search_result_total_rows.php' );
}	
?>
	 </caption> 


          <thead> 
            <tr> 
	      <th class="span-1">ID</th> 
	      <th class="span-3 last">邮箱</th> 
	      <th class="span-3 last">用户名</th> 
	      <th class="span-2 last">城市</th>
              <th class="span-2 last">粉丝数</th>
	      <th class="span-4 last">注册时间</th> 
	      <th class="span-2 last">个性域名</th>
	      <th class="span-2 last">系统域名</th>
	      <th class="span-2 last">上次登录IP</th>
	      <th class="span-2">编辑</th>
            </tr> 
          </thead> 
          <tfoot> 
            <tr> 
              <td colspan="2"></td> 
            </tr> 
          </tfoot> 
	  <tbody> 

<?php
foreach( $user_infos as $no=>$user_info ){
?>
            <tr> 
	    <td><?php echo $user_info['user_id']; ?></td> 
	    <td><?php echo $user_info['email']; ?></td> 
	    <td><a href="/<?php echo $user_info['sys_domain']; ?>"><?php echo $user_info['nick']; ?></a></td> 
	    <td><?php echo $user_info['loc_city']; ?></td> 
 	    <td><?php echo $user_info['follow_count'];?></td>
            <td><?php echo $user_info['reg_time']; ?></td> 
	    <td><?php echo $user_info['domain']; ?></td>
	    <td><?php echo $user_info['sys_domain']; ?></td>
	    <td><?php echo $user_info['last_login_ip']; ?></td>
	    <td id="id<?php echo $user_info['user_id']; ?>">
	 	 <a href="/admin/user_admin_info_edit/<?php echo $user_info['user_id']; ?>" class="opt">改</a>
		 <a href="/admin/do_delete_user/<?php echo $user_info['user_id']; ?>" class="opt">删</a>
<?php 
//判断用户当前的状态
if( $user_info['status'] == 'disable' ){	

	$disable = TRUE;	
}else{
	 
	$disable = FALSE;
}
?>	

<a href="javascript:void(0);" <?php
echo $disable ? ' style="display:none;" ' : '';
?>class="disable" onclick="disable_user( '<?php echo $user_info['user_id']?>' )" >禁</a>

<a href="javascript:void(0);" <?php
echo !$disable ? ' style="display:none;" ' : '';
?>class="undisable" onclick="enable_user( '<?php echo $user_info['user_id']?>' )" );" >解</a>

	    </td>
	    </tr> 
<?php
}
}
else{
	echo '系统中没有符合条件的用户.'; 
}
?>

          </tbody> 
	</table> 
<div class="span-24" id="pager">
<?php echo $this->pagination->create_links(); ?>
</div>
</div>

<script>
function disable_user( user_id ){

	$.ajax({
		 type: "POST",
		 dataType: "json",
		 url : "/admin/update_user_status/",
		 data : { 'user_id':user_id , 'status':'disable' } , 
		 error : function(){ 
			 alert( 'ajax error' );
		 } ,
		 success : function( msg ){

			 if( msg.res ){

				 //更改按钮
				 $( '#id'+user_id + ' .disable' ).hide();
				 $( '#id'+user_id + ' .undisable' ).show();
			 }	 	 
		 }
	 });
}

function enable_user( user_id ){

	$.ajax({
		 type: "POST",
		 dataType: "json",
		 url : "/admin/update_user_status/",
		 data : { 'user_id':user_id , 'status':'enable' } , 
		 error : function(){ 
			 alert( 'ajax error' );
		 } ,
		 success : function( msg ){

			 if( msg.res ){

				 $( '#id'+user_id + ' .undisable' ).hide();
				 $( '#id'+user_id + ' .disable' ).show();
			 }	 	 
		 }
	 });
}
</script>
