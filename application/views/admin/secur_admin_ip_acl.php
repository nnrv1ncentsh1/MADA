<form id="edit" action="/admin/do_secur_admin_ip_acl" method="post"> 

	 <h4>修改IP黑名单</h4>
	 <fieldset> 
	 <legend></legend> 

	 <p>
	 <a class="opt" href="#" onclick="$('#edit').submit();">提交</a>
	 </p>
	 
	 <p>
<?php 
if( !empty( $do_secur_admin_ip_acl_res ) ){
	 
	 $info = $do_secur_admin_ip_acl_res['info'];
	 switch( $do_secur_admin_ip_acl_res['res'] ){
	 
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
	 
 	 <div class="span-8" id="ip_acl_info">
	 <p>
	 <b class="input_type">IP黑名单</b>
	 </p>
         <p > 
		 <label for="ip_acl"></label><br />
		 <textarea class="input" id="ip_acl" name="ip_acl"><?php echo $ip_acl; ?></textarea>
	 </p>
	 </div>
 
</fieldset> 
</form> 



