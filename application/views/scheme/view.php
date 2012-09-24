<div class="container"> 

<div id="left">

<?php 
$border = TRUE;
include( getcwd().'/application/views/div/top_user_info.php' );
?>

<div id="solution_view">

<!--方案的详细信息-->
<div class="stream" id="home_stram">

<ol>

<!--方案一般信息-->
<li class="no_home_stram_item">
<!--用户方案标题，链接到方案详细页面-->
<span class="solution_title">
<b><?php echo $solution_info['title']; ?></b>
</span>

<!--分栏显示 折扣和内容-->
<div>
<!--高亮显示折扣-->
<!--div class="<?php
if( $solution_info['status'] == 'expire' || $solution_info['disable'] == 'true' ){
	 echo 'expire discount';
}else{
	 echo 'hight_light_discount discount';
}
?>"><?php echo $solution_info['discount']; ?></div-->
<!--用户方案内容-->
<div class="content"><?php echo $solution_info['describe']; ?></div>
</div>
</li>

<?php $this_time = trim( ltrim( date( 'm.d' , strtotime( $solution_info['time'] ) ) , '0' ) ); ?>
<li class="date_and_bar"
<!--发布时间-->	 
<div class="date" <?php 
if( strlen( $this_time ) >= 5 ){
	 
	echo " style='right:37px;' ";
}
?> >
<?php 
echo $this_time;
?>
</div>
<div class="solution_view_date_bar last"></div>
</li>


<!--方案所有的商品信息-->
<?php
foreach( $products_info as $no=>$product_info ){
	if( $product_info['title'] == null ){
		 
		continue;
	}
?>
<li>
<div class="product">

<!--商品名称-->
<span class="product_title"><?php echo $product_info['title']; ?></span>
<!--商品原价-->
<span class="ori_proice">原价 : <?php echo $product_info['original_price']; ?></span>
<!--商品描述内容-->
<span class="product_content">
<div>
<?php echo $product_info['describe']; ?>
</div>
</span>
</div>
</li>
<?php
}
?>

<li class="opt_and_info">

<!--操作-->
<div class="opt">
<?php
if( empty( $user_info ) ){
?>
<a href="javascript:void(0);" class="chrome_link_hack">
<div <?php
	if( $solution_info['status'] == 'expire' || $solution_info['disable'] == 'true' ){
		 echo 'class="button_outside_border_gray"';
	}else{
		 echo 'class="button_outside_border_green" onclick="do_buy( ' . $solution_info['id'] . ' );"';
	}
?>>
	<div class="<?php
	if( $solution_info['status'] == 'expire' || $solution_info['disable'] == 'true' ){
		 echo 'button_inside_border_gray';
	}else{
		 echo 'button_inside_border_green';
	}
?>">
	购买
	</div>
	</div>
</div>
</a>

<div class="price_info">
<span>原价 : <?php echo $solution_info['original_price']?></span>
<span>现价 : <?php echo $solution_info['sum_now_price']?></span>
</div>

</li>
<?php
}else if( $user_info['user_id'] != $holder_info['user_id'] ){
?>
<a href="javascript:void(0);" class="chrome_link_hack">
<div class="button_outside_border_green" onclick="do_buy( <?php echo $solution_info['id']; ?> );">
	<div class="button_inside_border_green">
	购买
	</div>
	</div>
</div>
</a>
<div class="price_info">
<span>原价:</span>
<span>现价:</span>
</div>

</li>
<?php
}
?>
</ol>

</div>
</div>
</div>


<div id="right">
<?php 
include( getcwd().'/application/views/div/user_info.php' );
?>
</div>

</div>

<script src="/js/followopt.js" type="text/javascript"></script>
<script src="/js/libs/jquery.timers-1.0.0.js" type="text/javascript"></script>
<script type="text/javascript">
/**
 * 执行购买操作
 */
//初始情况可以进行购买操作
var can_do_buy = true;
//倒计时 10s
var count_sec = 9;
function do_buy( solution_id ){

//如果不能购买则出现提示信息
if( !can_do_buy ){

	close_add_new_solution_res_cont();
	//不能买 计时器没有超时
	show_sys_info( '请理智消费 <span class="buy_count_sec">'+ count_sec +'</span style="width:15px;"> 秒后可以继续购买。' );
	return;	
}
$.ajax({
	 type: "POST",
	 dataType: "json",
	 url : "/buyopt/do_buy/",
	 data : "solution_id=" + solution_id ,
	 error : function(){ 
		 alert( 'ajax error' );
	 } ,
	 success : function( msg ){

		 if( msg.res ){
			 //无效化购买
			 can_do_buy = false;

			 //购买成功 
			 show_sys_info( msg.info + ' 前往我的 <a href="/buy">购买页面</a>' , true );

			 $('body').stopTime( 'count_sec' );
			
			 //启动定时器 10S 之内不能购买本方案
			 $('body').everyTime( '1s' , 'count_sec' , function(){
	 	 	 	 //每秒减一 
				 count_sec--;
				 //并更新
				 $( '.buy_count_sec' ).text( count_sec );
				 if( count_sec <= 0 ){

				 	 //提示消失
					 close_add_new_solution_res_cont();
					 can_do_buy = true;
					 //重置计数
					 count_sec = 9;
				 }
			 });
		 }else{

			 show_sys_info( msg.info , true );
		 }
	 	 
	 }
}); 	
}
</script>



