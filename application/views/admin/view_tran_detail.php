<!--交易查询结果-->
<div id="">
<?php
if( !empty( $tran_info ) ){
?>

<table summary="交易详细信息表" border="0" cellspacing="0" cellpadding="0"> 
	 <caption>交易详细信息表</caption> 
	 <thead> 
         <tr> 
		 <th class="span-2">交易ID</th> 
		 <th class="span-3">买家用户名</th> 
		 <th class="span-2">方案ID</th> 
		 <th class="span-3">编码</th>
		 <th class="span-4">购买时间</th> 
		 <th class="span-1">状态</th>
	 </tr> 
         </thead> 
         <tfoot> 
         <tr> 
         	 <td colspan="2"></td> 
         </tr> 
         </tfoot> 
	 <tbody> 
	 <tr> 
		 <td><?php echo $tran_info['id']; ?></td> 
		 <td><a href="/<?php echo $buyer_info['sys_domain']; ?>"><?php echo $tran_info['buyer_id']; ?></td> 
		 <td><a href="/<?php echo $holder_info['sys_domain']; ?>/<?php 
echo base64_encode( $tran_info['solution_id'] ); ?>"><?php echo $tran_info['solution_id']; ?></td>
		 <td><?php echo $tran_info['code']; ?></td>
		 <td><?php echo $tran_info['time']; ?></td>
		 <td><?php echo $tran_info['status']; ?></td>
	 </tr> 
	 </tbody> 
</table> 
<!--卖家信息-->	 
<table summary="卖家详细信息表" border="0" cellspacing="0" cellpadding="0"> 
	 <caption>卖家详细信息表</caption> 
	 <thead> 
         <tr> 
		 <th class="span-2">卖家ID</th> 
		 <th class="span-3">用户名</th> 
		 <th class="span-4">注册时间</th> 
		 <th class="span-3">个性域名</th>
		 <th class="span-3">系统域名</th> 
	 	 <th class="span-2">所在地</span>
	 </tr> 
         </thead> 
         <tfoot> 
         <tr> 
         	 <td colspan="2"></td> 
         </tr> 
         </tfoot> 
	 <tbody> 
	 <tr> 
		 <td><?php echo $holder_info['user_id']; ?></td> 
		 <td><a href="/<?php echo $holder_info['sys_domain']; ?>"><?php echo $holder_info['nick']; ?></td> 
		 <td><?php echo $holder_info['reg_time']; ?></td>
		 <td><?php echo $holder_info['domain']; ?></td>
		 <td><?php echo $holder_info['sys_domain']; ?></td>
	 	 <td><?php echo $holder_info['loc_city']; ?></td>
	 </tr> 
	 </tbody> 
</table> 
<!--买家信息-->	 
<table summary="买家详细信息表" border="0" cellspacing="0" cellpadding="0"> 
	 <caption>买家详细信息表</caption> 
	 <thead> 
         <tr> 
		 <th class="span-2">买家ID</th> 
		 <th class="span-3">用户名</th> 
		 <th class="span-4">注册时间</th> 
		 <th class="span-3">个性域名</th>
		 <th class="span-3">系统域名</th> 
	 	 <th class="span-2">所在地</span>
	 </tr> 
         </thead> 
         <tfoot> 
         <tr> 
         	 <td colspan="2"></td> 
         </tr> 
         </tfoot> 
	 <tbody> 
	 <tr> 
		 <td><?php echo $buyer_info['user_id']; ?></td> 
		 <td><a href="/<?php echo $buyer_info['sys_domain']; ?>"><?php echo $tran_info['buyer_id']; ?></td> 
		 <td><?php echo $buyer_info['reg_time']; ?></td>
		 <td><?php echo $buyer_info['domain']; ?></td>
		 <td><?php echo $buyer_info['sys_domain']; ?></td>
		 <td><?php echo $buyer_info['loc_city']; ?></td>
	 </tr> 
	 </tbody> 
</table> 
<!--方案信息-->	 
<table summary="方案详细信息表" border="0" cellspacing="0" cellpadding="0"> 
	 <caption>方案详细信息表</caption> 
	 <thead> 
         <tr> 
		<th class="span-1">ID</th> 
		<th class="span-3 last">标题</th> 
		<th class="span-4 last">提交时间</th> 
		<th class="span-1 last">原价</th> 
		<th class="span-1 last">现价</th>
		<th class="span-1 last">折扣</th>	 
		<th class="span-2 last">购买</th> 
		<th class="span-2 last">支付</th>
	 	<th class="span-2">所在地</span>
	 </tr> 
         </thead> 
         <tfoot> 
         <tr> 
         	 <td colspan="2"></td> 
         </tr> 
         </tfoot> 
	 <tbody> 
	 <tr> 
		<td><?php echo $solution_info['id']; ?></td> 
		 <td><a href="/<?php echo $holder_info['sys_domain']; ?>/<?php 
echo base64_encode( $tran_info['solution_id'] ); ?>"><?php echo $tran_info['solution_id']; ?></td>
		<td><?php echo $solution_info['time']; ?></td> 
		<td><?php echo $solution_info['original_price']; ?></td> 
		<td><?php echo $solution_info['sum_now_price']; ?></td> 
		<td><?php echo $solution_info['discount']; ?></td> 
		<td><?php echo $solution_info['has_bought']; ?></td> 
		<td><?php echo $solution_info['has_paid']; ?></td> 
	 	<td><?php echo $holder_info['loc_city']; ?></td>
	 </tr> 
	 </tbody> 
</table> 
<!--方案包含的商品信息-->	 
<table summary="方案包含的商品信息" border="0" cellspacing="0" cellpadding="0"> 
	 <caption>方案包含的商品信息</caption> 
	 <thead> 
         <tr> 
		 <th class="span-2">商品ID</th> 
		 <th class="span-3">标题</th> 
		 <th class="span-4">描述</th> 
		 <th class="span-3">原价</th>
	 </tr> 
         </thead> 
         <tfoot> 
         <tr> 
         	 <td colspan="2"></td> 
         </tr> 
         </tfoot> 
	 <tbody> 
<?php 
foreach( $product_infos as $no=>$product_info ){
if( $product_info['title'] == '' || $product_info['original_price'] == '' ){
	 
	 continue;	
}
?>
	 <tr> 
		 <td><?php echo $product_info['id']; ?></td> 
		 <td><?php echo $product_info['title']; ?></td> 
		 <td><?php echo $product_info['describe']; ?></td>
		 <td><?php echo $product_info['original_price']; ?></td>
	 </tr> 
<?php
}
?>
	 </tbody> 
</table>

<?php
}
else{
	echo '系统中没有符合条件的交易.'; 
}
?>
</div>
