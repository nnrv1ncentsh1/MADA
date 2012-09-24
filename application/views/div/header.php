<!--用户登录后的页首-->
<div id="header">
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<div class="nav-collapse">
					<ul class="nav pull-right">
						<li><a href="/<?php echo $object_user_info['domain']; ?>/"><?php echo $object_user_info['nick'] ?></a></li>
						<li><a id="open_add_new_scheme_dialog" href='javascript:void(0);'>+发布新方案</a></li>
						<li><a href="/buy">购买</a></li>
						<li><a href="/sale">出售</a></li>
						<li><a href="/settings">设置</a></li>
						<li><a href="/logout">退出</a></li>
					</ul>
				</div>
			</div><!--container-->
		</div><!--/navbar-inner-->
	</div><!--/navbar-->
</div><!--/#header-->
<div id="under_header">
&nbsp;
</div>
<div id="sys_res_info"></div>
<div id="dialog_base" class="dialog_container"></div>
<div id="add_new_scheme_dialog" class="dialog_container"></div>
<div id="retweet_dialog" class="dialog_container"></div>
