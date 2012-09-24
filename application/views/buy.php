<script src="/js/libs/jquery.tabbie.js" type="text/javascript"></script>

<div class="container">

<div id="left">
	 <!--顶部的用户信息-->
	 <?php
	 include( 'div/top_user_info.php' );
	 ?>

	 <!--tabs-->
	 <ul id="tabs" class="border_bottom_999">
		<li class="active"><a href="javascript:void(0);" class="bought" rel="bought">已购买</a></li>
		<li><a href="javascript:void(0);" class="paid" rel="paid">已支付</a></li>
	 </ul>
	
	 <!--显示已买的方案-->
	 <div id="bought" class="content">
	 <div class="solution_list">
	 <div class="stream" id="home_stram">

<?php
$solutions = $du_solutions['bought'];
$stat = 'bought';
$subject_user_id = $subject_user_info['user_id'];
include ( 'div/solution_stream_ol.php' );
?>
	
	</div>
	</div>
	</div>
	
	<!--显示已经支付的方案-->
	<div id="paid" class="content">
	<div class="solution_list" style="display:none;">
	<div class="stream" id="home_stram">

<?php
$solutions = $du_solutions['paid'];
$stat = 'paid';
$subject_user_id = $subject_user_info['user_id'];
include ( 'div/solution_stream_ol.php' );
?>

	</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready( function(){
	$('#tabs').tabbie({event:'click'});
	//加载完成之后显示非默认页面
	$('#paid .solution_list').show();
});
</script>

</div>

<div id="right">
<!--用户信息-->
<?php include( 'div/user_info.php' ); ?>
</div>

</div>


