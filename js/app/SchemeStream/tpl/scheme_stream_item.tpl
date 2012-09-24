<script type="text/template" id="scheme_item_tpl">
<!--方案时间轴模版文件-->
<!--一条方案信息-->
<div class="bar">
	<hr class="bar_hr" />
</div>
<div class="scheme_item">
	<!--用户头像链接-->
	<div class="head_img pull-left">
		<a href="#"><img src="/picture/user_head_img/{{holder.sys_domain}}_face_small.jpg" /></a>
	</div><!--/scheme_item-head_img-->
	<!--当前方案所有人信息-->
	<div class="holder_info">
		<!--用户名-->
		<span class="user_nick">
			<a href="#">{{holder.nick}}</a>
		</span><!--/user_nick-->
		<span class="user_desc">
			{{holder.describe}}
		</spon>
	</div>
	<!--方案本身-->
	<div class="scheme_main pull-left">
		<!--方案信息-->
		<div class="scheme_info pull-left">
			<!--方案标题-->
			<span class="scheme_title">
				<a href="#">{{title}}</a>
			</span><!--/scheme_title-->
			<!--方案描述-->
			<span class="scheme_describe">
				{{describe}}
			</span>
		</div><!--/scheme_main-->
		<!--用于显示下拉菜单-->
		<div class="slider"></div><!--/slider-->
		<!--功能栏-->
		<div class="scheme_toolbar pull-left">
			<!--显示用户的详细信息以及该方案过期时间等-->
			<span class="time">{{formated_time}}</span>
			<!--显示转发信息-->
			{{#retweet_info}}
				转发自 <span class="retweets">{{prev_retweet_user.nick}}</span>
			{{/retweet_info}}
			<!--显示方案详细内容-->
			<span class="detail">价格({{sum_now_price}}|{{original_price}})</span> ·
			<!--显示交易详细信息-->
			<span class="tran">购买({{has_paid}}|{{has_bought}})</span>
			<span class="more">更多</span>
		</div><!--/scheme_toolbar-->
	</div><!--/scheme_main-->
</div><!--/scheme_item-->
</script>

<script type="text/template" id="get_more_scheme_item_tpl">
<button class="get_more_scheme btn btn-small {{#disable}}disabled{{/disable}}">{{text}}</button>
<div class="get_more_scheme_error">获取方案信息失败，请稍候再试</div>
</script>
