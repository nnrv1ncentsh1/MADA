<script src="/js/libs/Jcrop/js/jquery.Jcrop.js" type="text/javascript"></script>
<link rel="stylesheet" href="/js/libs/Jcrop/css/jquery.Jcrop.css" type="text/css" />

<div class="container"> 

<div id="left">
<div id="settings">
<fieldset> 
	 <!--设置导航-->
	 <div class="nav">
	 <ul>	 
	 	 <li><a href="/settings/">个人资料</a></li>
	 	 <li><a href="/settings/myface" class="active">头像设置</a></li>
	 	 <li><a href="/settings/snstools">社交工具</a></li>
		 <li><a href="/settings/account">帐号设置</a></li>
	 	 <li><a href="/settings/app_list">API Keys</a></li>
	 </ul>
	 </div>

	 <div id="myface">
	 <?php if( isset( $upload_user_head_img_error_info ) ){
	 	 echo "<div class='error'>$upload_user_head_img_error_info</div>";
	 }
	 ?>

	 <div class="img">

	 <img src="/picture/user_head_img/<?php
if( file_exists( $_SERVER['DOCUMENT_ROOT'] . '/picture/user_head_img/' . $user_info['sys_domain'] . '_face.jpg' ) ){
	 echo $user_info['sys_domain'];
}else{
	 echo "anonymous";
}
?>_face.jpg?<?php echo $_SERVER['REQUEST_TIME'];?>" id="target" >


	 </div>
	 <div id="uploadfile">
	 	 <form id="update_user_profile" action="do_upload_myface" method="post" enctype="multipart/form-data">
		 <span class="title">从电脑中选择你喜欢的照片:</span>
		 <p>
			 你可以上传JPG、JPEG、GIF、PNG文件。<br />
	 	 	 请不要选择大于2M的图片文件。

	 	 </p>
		 <input type="file" name="userfile"/>
	 	 <div class='err'>
		 <?php 
	 	 if( !empty( $myface_error ) ){
	 	 	 
			 echo $myface_error['content'];
	 	 }
	 	 ?>
	 	 </div>
	 	 <input type="submit" class="button" value="上传图片" />
	 	 </form> 
	 	 <div class="preview">
		 <span class="title">这是你在ABC的头像</span><br />
	 	 	 	 	 
	 	 	 <div class="preview_img left">
			 <img  src="/picture/user_head_img/<?php 
if( file_exists( $_SERVER['DOCUMENT_ROOT'] . '/picture/user_head_img/' . $user_info['sys_domain'] . '_face.jpg' ) ){
	echo $user_info['sys_domain'];
}else{
	echo 'anonymous'; 
}
?>_face.jpg" id="preview" alt="Preview" />
			 </div>
			 <p id="crop_hint">
			 随意拖拽或缩放大图中的虚线方格，预览的小图即为保存后的头像图标。
	 	 	 <br /><br />
			 保存成功后若头像未即时更新，你可以用浏览器多刷新几次。
			 </p>

			 <p>

	 	 	 </p>
	 	 </div>
	 <div class="submit_button">
	 <!--传递用户裁剪值-->
	 <form action="/settings/do_crop_myface" name="crop" id="_crop" method="post">
	 <input type="hidden" size="4" id="x1" name="x1" /> 
	 <input type="hidden" size="4" id="y1" name="y1" /> 
	 <input type="hidden" size="4" id="x2" name="x2" /> 
	 <input type="hidden" size="4" id="y2" name="y2" /> 
	 <div> 
		 <div class="button_outside_border_green" onclick="$('#_crop').submit();">
	 	 <a href="javascript:void(0);" class="chrome_link_hack">
		 <div class="button_inside_border_green">
		 保存
		 </div>
	 	 </a>
		 </div>
	 </div>
	 </form>
	 </div>

	 </div>

</div> 
</fieldset>
</div>
</div>

<div id="right">
<br />
</div>

</div>

<script type="text/javascript">;
jQuery(function($){
// Create variables (in this scope) to hold the API and image size
var jcrop_api, boundx, boundy;

$('#target').Jcrop({
	onChange: updatePreview,
	onSelect: updatePreview,
	onSelect: showCoords,
	aspectRatio: 1 , 
	maxSize: [ 180, 180 ],
	setSelect: [ <?php echo $user_info['x1'].','.$user_info['y1'].','.$user_info['x2'].','.$user_info['y2']; ?> ] 
},function(){
	// Use the API to get the real image size
	var bounds = this.getBounds();
	boundx = bounds[0];
	boundy = bounds[1];
	// Store the API in the jcrop_api variable
	jcrop_api = this;
});

function showCoords(c)
{
	 $('#x1').val(c.x);
 	 $('#y1').val(c.y);
 	 $('#x2').val(c.x2);
 	 $('#y2').val(c.y2);
};

function updatePreview(c)
{
 	 if (parseInt(c.w) > 0)
	 {
 	 	 var rx = 50 / c.w;
 	 	 var ry = 50 / c.h;

	 	 $('#preview').css({
 			  width: Math.round(rx * boundx) + 'px',
 			  height: Math.round(ry * boundy) + 'px',
 	 		  marginLeft: '-' + Math.round(rx * c.x) + 'px',
 	 		  marginTop: '-' + Math.round(ry * c.y) + 'px'
	 	 });
	 }
};
});
</script>


