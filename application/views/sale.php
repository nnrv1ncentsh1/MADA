<!--用于分栏显示-->
<script src="/js/libs/jquery.tabbie.js"></script>

<div class="container">
	 
<div id="left">
	 <!--顶部的用户信息-->
	 <?php
	 $border = true; 
	 include( 'div/top_user_info.php' );
	 ?>
	 <!--tabs-->
	 <!--ul id="tabs" class="border_bottom_999">
		<li class="active"><a href="#" class="onsale" rel="onsale">进行中</a></li>
		<li><a href="#" class="expire_" rel="expire">已过期</a></li>
	 </ul-->

	 <!--显示在售的方案-->
	 <div id="onsale" class="content">
	
	 <div class="solution_list">
	 <div class="stream" id="home_stram">

<?php
$solutions = $du_solutions['onsale'];
$stat = 'onsale';
$subject_user_id = $subject_user_info['user_id'];
include ( 'div/solution_stream_ol.php' );
?>
	 
	</div>

	</div>

	</div>
	
	<!--显示已经过期的方案-->
	<!--div id="expire" class="content">
	
	<div class="solution_list" style="display:none;">
	<div class="stream" id="home_stram">

<?php
/**
$solutions = $du_solutions['expire'];
$stat = 'expire';
$subject_user_id = $subject_user_info['user_id'];
include ( 'div/solution_stream_ol.php' );
*/
?>
	
	</div>

	</div>

	</div-->

<script type="text/javascript">
//$(document).ready( function(){
//	$('#tabs').tabbie({event:'click'});
//	//加载完成之后显示非默认页面
//	$('#expire .solution_list').show();
//});
</script>

</div>

<div id="right">
<!--用户信息-->
<?php include( 'div/user_info.php' )?>
</div>

</div>


