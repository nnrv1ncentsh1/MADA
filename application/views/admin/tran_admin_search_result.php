<link href="/js/libs/tablesorter/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/libs/tablesorter/jquery.tablesorter.min.js"></script> 

<!--交易管理模块-->
<div id="tran_admin">
<?php
if( !empty( $tran_infos ) ){
//如果方案信息不为空
?>

<p>
<?php 
if( !empty( $opt_tran_res ) ){
	 
	 $info = $opt_tran_res['info'];
	 switch( $opt_tran_res['res'] ){
	 
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

<table summary="交易信息表" border="0" cellspacing="0" cellpadding="0" id="tran_search_result" class="tablesorter"> 
	  <caption>
交易信息列表 
<?php 
if( isset( $total_rows ) ){
	 
	 include( getcwd().'/application/views/div/admin_search_result_total_rows.php' );
}	
?>
	 </caption> 
          <thead> 
            <tr> 
              <th class="span-2">交易ID</th> 
	      <th class="span-2">买家用户名</th>
	      <th class="span-2">卖家用户名</th>
	      <th class="span-2">方案ID</th> 
	      <th class="span-3">支付码</th>
	      <th class="span-4">购买时间</th> 
	      <th class="span-1">状态</th>
	      <th class="span-2">买家城市</th>
	      <th class="span-2">卖家城市</th>
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
foreach( $tran_infos as $no=>$tran_info ){
?>
            <tr> 
	    <td><?php echo $tran_info['id']; ?></td> 
	    <td ><a href="/<?php echo $tran_info['buyer_info']['sys_domain']; ?>" ><?php echo $tran_info['buyer_info']['nick']; ?></a></td>
	    <td><a href="/<?php echo $tran_info['holder_info']['sys_domain']; ?>" ><?php echo $tran_info['holder_info']['nick']; ?></a></td>
	    <td><a href="/<?php echo $tran_info['holder_info']['sys_domain']; ?>/<?php
echo rtrim( base64_encode( $tran_info['solution_id'] ) , '=' ); ?>" ><?php echo $tran_info['solution_id']; ?></td>
	    <td><?php echo $tran_info['code']; ?></td>
	    <td><?php echo $tran_info['time']; ?></td>
	    <td><?php echo $tran_info['status']; ?></td>
	    <td><?php echo $tran_info['buyer_info']['loc_city']; ?></td>
	    <td><?php echo $tran_info['holder_info']['loc_city']; ?></td>
	    <td>
		 <a href="/admin/do_delete_tran/<?php echo $tran_info['id']; ?>" class="opt">删<a>
	 	 <a href="/admin/view_tran_detail/<?php echo $tran_info['id']; ?>" class="opt">详<a>
	    </td>
	    </tr> 
<?php
}
?>

          </tbody> 
	</table> 

<div class="span-24" id="pager">
<?php echo $this->pagination->create_links(); ?>
</div>

<?php
}else{
	echo '系统中没有符合条件的交易.'; 
}
?>
</div>

<script>
$("#tran_search_result").tablesorter( {sortList: [[0,1]]} ); 
</script>
