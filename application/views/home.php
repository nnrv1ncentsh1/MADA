<div class="container">
	<div id="left">
		<!--方案列表-->
		<div class="scheme_list">
			<!--顶部的用户信息-->
			<?php include( 'div/top_user_info.php' ); ?>
			<!--方案流占位符-->
			<div id="stream">
				<!--方案流初始化信息，显示 loading.. 等-->
				<span class="initialize_load_info"></span>
				<!--@todo 可以放到 page_config 框中-->
				<span class="subject_user_id"><?php echo $subject_user_info['user_id']; ?></span>
			</div><!--/#stream-->
			<!--获取更多方案按钮的占位符-->
			<div id="more_scheme_item">
			</div><!--/#more_scheme_item-->
		</div><!--/.scheme_list-->
	</div><!--/#left-->
	<div id="right">
	<!--用户信息-->
	<?php include( 'div/user_info.php' ); ?>
	</div><!--/.right-->
</div><!--/.container-->
