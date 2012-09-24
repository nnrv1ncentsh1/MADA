<!--方案管理模块-->
<div id="solution_admin">
<?php 
if( !empty( $solution_infos ) ){
?>
	 <p>
	 <a href="/admin/solution_admin/time/asc" class="<?php echo $now_sort_rule == 'time_asc' ? 'now_sort_rule' : 'sort_lable'; ?>" >时间升序</a>
	 <a href="/admin/solution_admin/time/desc" class="<?php echo $now_sort_rule == 'time_desc' ? 'now_sort_rule' : 'sort_lable'; ?>" >时间降序</a>
	 <a href="/admin/solution_admin/original_price/asc" class="<?php echo $now_sort_rule == 'original_price_asc' ? 'now_sort_rule' : 'sort_lable'; ?>" >原价升序</a>
	 <a href="/admin/solution_admin/original_price/desc" class="<?php echo $now_sort_rule == 'original_price_desc' ? 'now_sort_rule' : 'sort_lable'; ?>" >原价降序</a>
	 <a href="/admin/solution_admin/sum_now_price/asc" class="<?php echo $now_sort_rule == 'sum_now_price_asc' ? 'now_sort_rule' : 'sort_lable'; ?>" >现价升序</a>
	 <a href="/admin/solution_admin/sum_now_price/desc" class="<?php echo $now_sort_rule == 'sum_now_price_desc' ? 'now_sort_rule' : 'sort_lable'; ?>" >现价降序</a>
	 <a href="/admin/solution_admin/discount/asc" class="<?php echo $now_sort_rule == 'discount_asc' ? 'now_sort_rule' : 'sort_lable'; ?>" >折扣升序</a>
	 <a href="/admin/solution_admin/discount/desc" class="<?php echo $now_sort_rule == 'discount_desc' ? 'now_sort_rule' : 'sort_lable'; ?>" >折扣降序</a>
	 <a href="/admin/solution_admin/has_bought/asc" class="<?php echo $now_sort_rule == 'has_bought_asc' ? 'now_sort_rule' : 'sort_lable'; ?>" >购买数升序</a>
	 <a href="/admin/solution_admin/has_bought/desc" class="<?php echo $now_sort_rule == 'has_bought_desc' ? 'now_sort_rule' : 'sort_lable'; ?>" >购买数降序</a>
	 <a href="/admin/solution_admin/has_paid/asc" class="<?php echo $now_sort_rule == 'has_paid_asc' ? 'now_sort_rule' : 'sort_lable'; ?>" >支付数升序</a>
	 <a href="/admin/solution_admin/has_paid/desc" class="<?php echo $now_sort_rule == 'has_paid_desc' ? 'now_sort_rule' : 'sort_lable'; ?>" >支付数降序</a>
	 </p>

	 <p>
<?php 
if( !empty( $opt_solution_info_res ) ){
	 
	 $info = $opt_solution_info_res['info'];
	 switch( $opt_solution_info_res['res'] ){
	 
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

  <table summary="方案信息表" border="0" cellspacing="0" cellpadding="0"> 
	  <caption>
方案信息列表
<?php 
if( isset( $total_rows ) ){
	 
	 include( getcwd().'/application/views/div/admin_search_result_total_rows.php' );
}	
?>
	 </caption> 
          <thead> 
            <tr> 
              <th class="span-1">ID</th> 
	      <th class="span-3 last">标题</th> 
	      <th class="span-4 last">提交时间</th>
	      <th class="span-2">城市</th>
	      <th class="span-2">卖家ID</span> 
	      <th class="span-3">卖家用户名</span> 
	      <th class="span-1 last">原价</th> 
	      <th class="span-1 last">现价</th>
	      <th class="span-1 last">折扣</th>	 
	      <th class="span-2 last">购买</th> 
	      <th class="span-2 last">支付</th>
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
$CI =& get_instance();
$CI->load->library( 'str' );
//如果用户信息不为空
foreach( $solution_infos as $no=>$solution_info ){
?>
            <tr> 
	    <td><?php echo $solution_info['id']; ?></td> 
	    <td><a href="/<?php echo $solution_info['holder_sys_domain']; ?>/<?php 
echo rtrim( base64_encode( $solution_info['id'] ) , '=' ); 
?>"><?php 
if( strlen( $solution_info['title'] ) > 20 ){

	echo $CI->str->sys_sub_str( $solution_info['title'] , 20 , '...' ) ; 
}else{

	echo $solution_info['title'];
}
?></td> 
	    <td><?php echo $solution_info['time']; ?></td> 
	    <td><?php echo $solution_info['holder_loc_city']; ?></td>
	    <td><?php echo $solution_info['holder_id']; ?></td>
	    <td><a href="/<?php echo $solution_info['holder_sys_domain']; ?>"><?php echo @$solution_info['holder_nick']; ?></a></td> 
	    <td><?php echo $solution_info['original_price']; ?></td> 
	    <td><?php echo $solution_info['sum_now_price']; ?></td> 
	    <td><?php echo $solution_info['discount']; ?></td> 
	    <td><?php echo $solution_info['has_bought']; ?></td> 
	    <td><?php echo $solution_info['has_paid']; ?></td> 
	    <td id="id<?php echo $solution_info['id']; ?>"> 
	 	 <a href="/admin/solution_admin_info_edit/<?php echo $solution_info['id']; ?>" class="opt">改</a> 
		 <a href="/admin/do_delete_solution/<?php echo $solution_info['id']; ?>" class="opt">删</a>
		 <!--判断状态-->
<?php 
if( $solution_info['disable'] == 'false' ){

	 $disable = false;
}else{
	 $disable = true;
}
?>			
<a href="javascript:void(0);" <?php
echo $disable ? ' style="display:none;" ' : '';
?>class="disable" onclick="disable_solution( '<?php echo $solution_info['id'] ?>','<?php echo $solution_info['status'] ?>' );" >关</a>
<a href="javascript:void(0);" <?php
echo !$disable ? ' style="display:none;" ' : '';
?>class="undisable" onclick="undisable_solution( '<?php echo $solution_info['id'] ?>','<?php echo $solution_info['status'] ?>' );" >开</a>
	    </td>
	    </tr> 
<?php
}
}
else{
	echo '系统中没有任何满足要求的方案.';
}
?>

          </tbody> 
	</table> 

<div class="span-24" id="pager">
<?php echo $this->pagination->create_links(); ?>
</div>
</div>

<script>
function disable_solution( solution_id , solution_status ){

	if( solution_status == 'expire' ){
	
		 $.prompt( '方案已过期' , { buttons: { Ok: true, Cancel: false } });
		 return;
	}

	$.ajax({
		 type: "GET",
		 dataType: "json",
		 url : "/admin/do_disable/" + solution_id,
		 error : function(){ 
			 alert( 'ajax error' );
		 } ,
		 success : function( msg ){
			 if( msg.res ){

				 //更改按钮
				 $( '#id'+solution_id + ' .disable' ).hide();
				 $( '#id'+solution_id + ' .undisable' ).show();
			 }	 	 
		 }
	 });
}

function undisable_solution( solution_id , solution_status ){

	if( solution_status == 'expire' ){
	
		 $.prompt( '方案已过期' , { buttons: { Ok: true, Cancel: false } });
		 return;
	}

	$.ajax({
		 type: "GET",
		 dataType: "json",
		 url : "/admin/undo_disable/" + solution_id,
		 error : function(){ 
			 alert( 'ajax error' );
		 } ,
		 success : function( msg ){
			 if( msg.res ){

				 $( '#id'+solution_id + ' .undisable' ).hide();
				 $( '#id'+solution_id + ' .disable' ).show();
			 }	 	 
		 }
	 });
}
</script>
