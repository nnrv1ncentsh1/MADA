<script type="text/template" id="dialog_base_tpl">
<div class="dialog_header">
	<b class="dialog_header_title">{{title}}</b>
	<span class="dialog_header_close_button">×</span>
</div><!--/dialog_header-->
<div class="dialog_content">
<div class="dialog_main">
{{&dialog_main}}
</div><!--/.dialog_main-->
</div><!--/.dialog_content-->

<!--实现上传文件的隐藏frame-->
<iframe class="upload_file_res" name="upload_file_res" style="display:none;"></iframe>
<form id="upload_img_from" action="/upload_img/ajax_do_upload_picture/" enctype="multipart/form-data" target="upload_file_res" method="post" style="display:none;">
	<input type="hidden" name="APC_UPLOAD_PROGRESS" class="APC_UPLOAD_PROGRESS" value="<?php echo $_SERVER['REQUEST_TIME'] . uniqid( '' ); ?>"/>
	<input type="hidden" name="img_with" value="{{img_with}}"/>
	<input type="file" name="picture" class="upload_picture_input"/>
</form>
</script>

<!--添加新方案时 显示在方案详细框中的上传图片缩略图模板-->
<script type="text/template" id="dialog_small_img_tpl">
<div class="img_item">
	<input name="img_name" type="hidden" value="{{img_name}}"/>
	<img class="pull-left" src="{{thumbnail_url}}">
	<i class='icon-remove'></i>
</div>
</script>

