<script type="text/template" id="retweet_dialog_tpl">
<div id="retweet_scheme_info" class="dialog_item">
	<img class="head_img pull-left" src="/picture/user_head_img/{{fromInfo.userInfo.sys_domain}}_face_small.jpg" />
	<div class="pull-left">
		<span class="user_nick">{{fromInfo.userInfo.nick}}</span>
		<span class="user_motto">{{fromInfo.userInfo.describe}}</span>
	</div>
	<br />
	<div>
		{{fromInfo.describe}}
	</div>
</div><!--./retweet_input-->
<div id="retweet_input" class="dialog_item">
	<textarea id="retweet" name="retweet" data-maxsize="140" data-output="status1" wrap="virtual" class="notnull retweet"></textarea>
	<!--上传的图片缩略图-->
	<div id="retweet_imgs" class="dialog_item_imgs"></div>
	<span id="status1" class="help-block pull-right"></span>
</div><!--./retweet_input-->
<!--默认显示的功能栏-->
<div id="" class="tools_bar">
	<i id="upload_picture" class="icon-camera"></i>
</div>
<div class="dialog_footer">
	<!--提交表单按钮-->
	<div class="dialog_item">
		<button id="" class="do_retweet btn btn-info btn-small pull-right">转发</button>
	</div>
</div><!--/dialog_footer-->
</script>

