<!--方案信息流下拉框模板-->
<div id="home_stream_item-solution_detail_info_and_more_utility-template">
<!--方案所属用户详细信息-->
<div class="home_stream_item-solution_detail_info_and_more_utility-holder_info">
	<span class="display_none type">time</span>
	<div class="home_stream_item-solution_detail_info_and_more_utility-holder_nick">纹身特湿</div>
	<div class="home_stream_item-solution_detail_info_and_more_utility-holder_follow_info">
		<div class="home_stream_item-solution_detail_info_and_more_utility-holder_follow_info-left_item">
			<div class="home_stream_item-solution_detail_info_and_more_utility-holder_follow_info-hightlight contacts_count"></div>
			<div class="home_stream_item-solution_detail_info_and_more_utility-holder_follow_info-desc">RETWETTS</div>
		</div>	
		<div class="home_stream_item-solution_detail_info_and_more_utility-holder_follow_info-right_item">
			<div class="home_stream_item-solution_detail_info_and_more_utility-holder_follow_info-hightlight rev_contacts_count"></div>
			<div class="home_stream_item-solution_detail_info_and_more_utility-holder_follow_info-desc">FAVORITES</div>
		</div>
	</div>
	<hr class="blank"/>
	<div class="home_stream_item-solution_detail_info_and_more_utility-solution_expire">
		<div class="home_stream_item-solution_detail_info_and_more_utility-solution_expire-above_item"><i class="icon-time"></i><span class="solution_post_time"></span></div>
		<div class="home_stream_item-solution_detail_info_and_more_utility-solution_expire-under_item"><i class="icon-time"></i><span class="solution_expire_time"></span></div>
	</div>
</div><!--end of holder_info-->
<!--转发信息 none -->
<!--当前方案详情-->
<div class="home_stream_item-solution_detail_info_and_more_utility-solution_detail">
	<span class="display_none type">price</span>
	<div class="home_stream_item-solution_detail_info_and_more_utility-solution_detail-hightlight">商品列表</div>
	<div class="home_stream_item-solution_detail_info_and_more_utility-solution_detail-product_list">
		<ol class="home_stream_item-solution_detail_info_and_more_utility-solution_detail-product_list-ol"></ol>
	</div>
	<hr class="blank_no_top_margin" />
	<div class="home_stream_item-solution_detail_info_and_more_utility-solution_detail-price_detail">原价 ￥12 。现价 ￥34 。折扣 123</div>
	<div class="home_stream_item-solution_detail_info_and_more_utility-solution_detail-imgs">
		<img class="home_stream_item-solution_detail_info_and_more_utility-solution_detail-img" src="/picture/solution/test.jpg" />
	</div>
</div><!--end of solution_detail-->
<!--交易详情-->
<div class="home_stream_item-solution_detail_info_and_more_utility-tran_detail">
	<span class="display_none type">transaction</span>
	<div class="home_stream_item-solution_detail_info_and_more_utility-tran_detail-solution_status">交易状态:进行中</div>
	<div class="home_stream_item-solution_detail_info_and_more_utility-tran_detail-holder_credit">信誉评级:A (86分 9527人参与)</div>
	<hr class="blank" />
	<div class="home_stream_item-solution_detail_info_and_more_utility-solution_detail-price_detail">原价 ￥12 。现价 ￥34 。折扣 123</div>
	<div class="home_stream_item-solution_detail_info_and_more_utility-tran_detail-payment_desc">支付方式 请选择合适的支付方式</div>
	<div class="home_stream_item-solution_detail_info_and_more_utility-tran_detail-payment_detail">
		<div class="home_stream_item-solution_detail_info_and_more_utility-tran_detail-payment_detail-left">到店支付</div>
		<div class="home_stream_item-solution_detail_info_and_more_utility-tran_detail-payment_detail-right">同城支付</div>
	</div>
	<div class="home_stream_item-solution_detail_info_and_more_utility-tran_detail-history">
		<div class="home_stream_item-solution_detail_info_and_more_utility-tran_detail-history-desc">购买记录(123)</div>
		<div class="home_stream_item-solution_detail_info_and_more_utility-tran_detail-history-desc">支付记录(12)</div>
		<div class="home_stream_item-solution_detail_info_and_more_utility-tran_detail-history-desc"> 评论详情(5)</div>
	</div>
</div><!--end of tran_detail-->
<!--更多操作-->
<div class="home_stream_item-solution_detail_info_and_more_utility-more">
	<span class="display_none type">more</span>
	<div class="has_followed"><button class="btn btn-small">取消关注</button></div>
	<div class="has_not_followed"><button class="btn btn-success btn-small"><i class="icon-plus"></i>加关注</button></div>
	<ul class="home_stream_item-solution_detail_info_and_more_utility-more-functions">
		<li class="function"><i class="icon-share-alt"></i>转发</li>
		<li class="function"><i class="icon-heart"></i>收藏</li>
		<li class="function"><i class="icon-comments"></i>私聊</li>
		<li class="function"><i class="icon-ban-circle"></i>关闭</li>
		<li class="function"><i class="icon-ok-sign"></i>直接激活</li>
		<li class="function"><i class="icon-ok-circle"></i>修改激活</li>
		<li class="function"><i class="icon-remove-sign"></i>删除</li>
	</ul>
</div><!--/more-->
</div><!--/template-->

<!--方案信息流-->
<!--锚点-->
<div id="solution_view_top">
</div>
<ol>
<!--循环显示方案-->
<?php
//没有方案可用来显示
if( empty( $solutions ) ){
	//根据不同的页面显示不同的提示信息
	if( $page_name == 'buy' ){
		//购买页面
	 	echo '<p>' . $subject_user_info['user_nick'] . '&nbsp您尚无购买记录</p></ol>';
	}else if( $page_name == '_home' || $page_name = 'sale' ){
		//购买页面
	 	echo '<p>' . $subject_user_info['user_nick'] . '&nbsp尚未发布方案</p></ol>';
	}
}else{
foreach( $solutions as $no=>$solution_info ){
//判断何时应该显示时间
//当本此时间和上次时间不同时显示时间
$this_time = 0;	
$last_time = 0;
if( $no >= 1 ){
	$last_time =  preg_replace( '/(^0)/' , '' , date( 'm.d' , strtotime( $solutions[$no-1]['time'] ) ) );
	$this_time =  preg_replace( '/(^0)/' , '' , date( 'm.d' , strtotime( $solutions[$no]['time'] ) ) );
}
?>
<?php
//如果是第一个就不显示
if( $no != 0 ){
?>
<li class="date_and_bar" <?php echo isset( $solution_info['code'] ) ? 'id="' . $solution_info['code'] . 'bar"' : '' ;?>>
<!--发布时间-->
<?php
if( $last_time != $this_time ){
?>
	<div class="date" <?php 
if( strlen( $last_time ) >= 5 ){
	 
	echo " style='right:37px;' ";
}

?> ><?php echo $last_time; ?></div>
	<div class="date_bar last"></div>
<?php
}else{
?>
	<div class="bar last"></div>
<?php
}
?>
</li>
<?php
}
?>

<li <?php 
if ( isset( $solution_info['code'] ) ){

	echo 'id="' . $solution_info['code'] . '"';
}else{

	echo 'id="' . $solution_info['id'] . '"';
}
?>>

<!--发帖/过期时间-->
<div class="post_time display_none"><?php echo $solution_info['time']; ?></div>
<div class="expire_time display_none"><?php print( $solution_info['expire_time'] ); ?></div>
<!--原价/现价/折扣-->
<div class="original_price display_none"><?php echo $solution_info['original_price']; ?></div>
<div class="sum_now_price display_none"><?php echo $solution_info['sum_now_price']; ?></div>
<div class="discount display_none"><?php echo $solution_info['discount']; ?></div>
<?php
if( $page_name != 'sale' && $page_name != 'u' ){
//这两个页面为用户私有，不显示冗余信息
?>
<!--用户头像链接-->
<div class="user_head_img pull-left">
<a class="user_header_a" href="/<?php echo $solution_info['holder_domain']; ?>" class="span-2"><img src="/picture/user_head_img/<?php
	//后台已经过滤不存在的头像为默认头像
	echo $solution_info['holder_sys_domain'] . '_face_small.jpg';
?>" /></a>
</div>
<?php
}
?>

<div class="<?php
if( $page_name != 'sale' ){
	echo 'home_stream_item'; 
}else{
	echo 'no_home_stream_item';
}
?>">

<!--用户名链接-->
<?php 
if( $page_name != 'sale' && $page_name != 'u' ){
?>
<div class="user_nick">
<a href="/<?php echo $solution_info['holder_domain']; ?>" class="author"><?php echo $solution_info['holder_nick']; ?></a> 
<!--用户描述-->
<span class="desc"><?php echo $solution_info['holder_describe']; ?></span>
</div>
<?php
}
?>
<!--分栏显示 折扣和内容-->
<div>

<!--用户方案内容-->
<!--用户方案标题，链接到方案详细页面-->
<div class="content">
<span class="title">
<a href="/<?php echo $solution_info['holder_domain']; ?>/<?php echo $solution_info['id'] ?>"><?php echo $solution_info['title']; ?></a>
</span>
<?php echo $solution_info['describe']; ?>
</div>
</div>

<!--功能栏显示区域-->
<div class="home_stream_item-solution_detail_info_and_more_utility">
</div>

<div>
<!--功能栏-->
<span class="toolbar">
<span class="home_stream_item-toolbar-time home_stream_item-toolbar-span"><?php 
if( strlen( $solution_info['time_from_published'] ) > 10 ){
	echo substr( $solution_info['time_from_published'] , 0 , strlen( $solution_info['time_from_published'] ) - 3 );
}
?></span>
<span class="home_stream_item-toolbar-forward home_stream_item-toolbar-span">转发自 纹身特湿</span>
<span class="home_stream_item-toolbar-price">价格(<?php echo $solution_info['sum_now_price'] , '/' , $solution_info['original_price']; ?>)</span> ·
<span class="home_stream_item-toolbar-transaction home_stream_item-toolbar-span">购买(<?php echo $solution_info['has_paid'] , '/' , $solution_info['has_bought']; ?>)</span>
<span class="home_stream_item-toolbar-more">更多</span>
</span>
</div>

</li>
<?php
}
?>

<li class="date_and_bar last_solution_of_this_page" <?php echo isset( $solution_info['code'] ) ? 'id="' . $solution_info['code'] . 'bar"' : '' ;?>>
<?php
//如果第一页能显示全部方案就显示
//时间
if( $no < 9 || true ){
	if( $no > 0 ){
		 $last_solution_time = $this_time;
	}else{
		$last_solution_time = preg_replace( '/(^0)/' , '' , date( 'm.d' , strtotime( $solutions[0]['time'] ) ) );
	}
?>
	<div class="date" <?php 
if( strlen( $last_solution_time ) >= 5 ){
	 
	echo " style='right:37px;' ";
}
?> ><?php echo $last_solution_time; ?></div>
	<div class="date_bar"></div>
<?php
}else{
?>
	<div class="bar"></div>
<?php
}
?>
</li>
</ol>

<!--more 按钮-->
<!--用于指示前台 本页面最后方案的日期-->
<input type="hidden" value="<?php echo isset( $this_time ) ? $this_time : 0 ; ?>" name="last_solution_time"/>
<div class="stream_more_container">

<!--读取错误 显示错误信息-->
<p class="loading_error">
读取信息错误,可能是服务器暂时过载
请<span onclick="get_more_solution( <?php
echo !empty( $subject_user_id ) ? "'$subject_user_id'" : '';
echo !empty( $subject_user_id ) && isset( $stat ) ? ',' : '';
echo isset( $stat ) ? "'$stat'" : '' ;
echo !empty( $page_name ) && isset( $stat ) ? ',' : '';
echo "'$page_name' ";
echo !empty( $page_name ) && !empty( $object_user_info['user_id'] ) ? ',' : '';
echo !empty( $object_user_info['user_id'] ) ? "'" . $object_user_info['user_id'] . "'" : '';
?>);">再试一次</span>.<span class='loading_img' style="display:none;"><img src="/picture/common/ajax-loader.gif" /></span>
</p>

<a href="javascript:void(0);" class="chrome_link_hack">
<div class="stream_more span-13" onclick="get_more_solution( <?php
echo !empty( $subject_user_id ) ? "'$subject_user_id'" : '' ;
echo !empty( $subject_user_id ) && isset( $stat ) ? ',' : '';
echo isset( $stat ) ? "'$stat'" : '' ;
echo !empty( $page_name ) && isset( $stat ) ? ',' : '';
echo "'$page_name' ";
echo !empty( $page_name ) && !empty( $object_user_info['user_id'] ) ? ',' : '';
echo !empty( $object_user_info['user_id'] ) ? "'" . $object_user_info['user_id'] . "'" : '';
?>);" onmousedown="$( '.stream_more' ).attr( 'style' , 'filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#e0e0e0\', endColorstr=\'#e0e0e0\', GradientType=\'0\');background:#e0e0e0;' );" onmouseup="$( '.stream_more' ).attr( 'style' , 'filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#f7f7f7\', endColorstr=\'#e0e0e0\', GradientType=\'0\');background-image: -webkit-gradient(linear,left bottom,left top,color-stop(0.18, rgb(224,224,224)),color-stop(0.59, rgb(247,247,247)));' );" onmouseout="$( '.stream_more' ).attr( 'style' , 'filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#f7f7f7\', endColorstr=\'#e0e0e0\', GradientType=\'0\');background-image: -webkit-gradient(linear,left bottom,left top,color-stop(0.18, rgb(224,224,224)),color-stop(0.59, rgb(247,247,247)));' );">
查看更多
</div>
</a>

<a href="javascript:void(0);" class="chrome_link_hack">
<div class="go_to_top">
<span class="go_to_anchor" onclick="go_to_top()"  onmousedown="$( '.ii' ).attr( 'style' , 'background:url(/picture/common/gotop.png) no-repeat;background-position:0 -100px;' );" onmouseup="$( '.ii' ).attr( 'style' , 'background: url(/picture/common/gotop.png) no-repeat;background-position:0 0;' );" onmouseover="$( '.ii' ).attr( 'style' , 'background: url(/picture/common/gotop.png) no-repeat;background-position:-100px 0;' );" onmouseout="$( '.ii' ).attr( 'style' , 'background: url(/picture/common/gotop.png) no-repeat;' );"><i class="ii"></i></span>
</div>
</a>

</div>

<?php 
} 
//是否可插入的标志，只有在自己的页面上才允许
//动态更新最新消息
if( isset( $object_user_info ) && !empty( $object_user_info ) ){
if( $object_user_info['user_id'] == $subject_user_info['user_id'] ){	 
	echo '<div id="update_switch" style="display:none;">allow_update</div>';
}
}
?>
