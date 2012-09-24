<form id="edit" action="/admin/do_post_domain_limit" method="post"> 

	 <h4>修改屏蔽规则</h4>
	 <fieldset> 
	 <legend></legend> 

	 <p>
	 <a class="opt" href="#" onclick="$('#edit').submit();">提交</a>
	 </p>
	 
	 <p>
<?php 
if( !empty( $do_post_domain_limit_res ) ){
	 
	 $info = $do_post_domain_limit_res['info'];
	 switch( $do_post_domain_limit_res['res'] ){
	 
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
	 
 	 <div class="span-8" id="post_limit_info">
	 <p>
	 <b class="input_type">发言关键词过滤</b>
	 </p>
         <p > 
		 <label for="post_limit"></label><br />
		 <textarea class="input" id="post_limit" name="post_limit"><?php echo $post_limit; ?></textarea>
	 </p>
	 </div>

 	 <div class="span-8 last" id="domain_limit_info">
	 <p>
	 <b class="input_type">用户个性域名限制</b>
	 </p>
         <p > 
		 <label for="domain_limit"></label><br />
	 	 <textarea class="input" id="domain_limit" name="domain_limit"><?php echo $domain_limit; ?></textarea>
	 </p>
	 </div>
 
</fieldset> 
</form> 



