<form id="edit" action="/admin/do_setting_tran_expire_time" method="post"> 

	 <h4>修改交易过期时间</h4>
	 <fieldset> 
	 <legend></legend> 

	 <p>
	 <a class="opt" href="#" onclick="$('#edit').submit();">提交</a>
	 </p>
	 
	 <p>
<?php 
if( !empty( $opt_tran_info_res ) ){
	 
	 $info = $opt_tran_info_res['info'];
	 switch( $opt_tran_info_res['res'] ){
	 
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
	 
 	 <div class="span-8" id="ip_acl_info">
	 <p>
	 <b class="input_type">交易过期时间</b>
	 </p>
         <p > 
		 <label for="expire">单位为天(默认为30天)</label><br />
		 <input class="input" name="expire" value="<?php echo @$tran_expire_time; ?>" />
	 </p>
	 </div>
 
</fieldset> 
</form> 



