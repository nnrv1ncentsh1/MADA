<!--添加新方案弹出窗-->
<div id="add_new_solution_container">
<div id="add_new_solution_container-header">
	<b id="add_new_solution_container-header-title">发布新的方案</b>
	<span id="add_new_solution_container-header-close">&times;</span>
</div><!--/top_info-->

<div id="add_new_solution">
<!--用户提交方案表单-->
<form id="add_new_solution-form" action="javascript:void(0);" method="post"> 
<fieldset>

	<div class="add_new_solution-form-item">
		<label for="title">输入一个标题</label>
		<div class="add_new_solution-form-item-textarea_container">
			<textarea name="solution_title" id="solution_title" data-maxsize="60" data-output="status1" wrap="virtual"></textarea>
			<span id="status1" class="help-block pull-right"></span>
		</div>
	</div>

	<div class="add_new_solution-form-item"> 
		<label for="describe">添加更多细节</label>
		<div class="add_new_solution-form-item-textarea_container">
			<textarea name="solution_describe" id="solution_describe" data-maxsize="140" data-output="status2" wrap="virtual"></textarea>
			<div class="add_new_solution-form-item-textarea_container-imgs"></div>
			<span id="status2" class="help-block pull-right"></span>
		</div>
	</div>

	<div class="add_new_solution-form-item"> 
		<div id="upload_pic_progress" class="progress progress-info" style="display:none;">
			<div class="bar" style=""></div>
		</div>
	</div>

	<!--添加服务相关产品表单容器-->
	<div id="add_product_container" class="add_new_solution-form-item">
		<p class="product_form_ban">
			 <span>商品或服务列表。填写商品或服务名称，初始售价及相关表述</span>
		</p>
		<div id="add_product_container-inputs">
		<!--动态增加商品输入-->
		</div><!--/add_product_container-inputs-->
	</div><!--/add_product_container-->

	<!--默认显示的功能栏-->
	<div id="add_new_solution-form-default_tools_bar">
		<i id="upload_solution_picture" class="icon-camera"></i>
		<span id="add_new_solution-form-default_tools_bar-add_new_product_link" class="pull-right">+ 添加商品或服务</span>
	</div>

	<div id="add_new_solution-submit">
		<!--动态显示方案的价格-->
		<div id="solution_info">
			<div class="solution_info-item">
				<span for="input_sum_now_price">现价</span>
				<input type="text" name="sum_now_price" maxlength="8" id="input_sum_now_price" class="span1"/>
			</div>
			<div class="solution_info-item">
				<span id="sum_ori_price">原价 <span class="figure">0</span>￥</span>
			</div>
			<div class="solution_info-item">
				<span id="discount">折扣 <span class="figure"></span></span>
			</div>
		</div>
		<input type="hidden" name="discount" value="" />
		<input type="hidden" name="sum_ori_price" value="0" />
		<button id="submit_solution_button" class="btn btn-primary pull-right btn-large">发布</button>
		<button id="cancel_submit_solution_button" class="btn pull-right btn-large">取消</button>
	</div>
</fieldset>
</form>
<iframe id="upload_file_res" name="upload_file_res" style="display:none"></iframe>
<form id="upload_upload_solution_attachment" action="/solution/ajax_do_upload_picture/" enctype="multipart/form-data" target="upload_file_res" method="post" style="display:none;">
	<input type="hidden" name="APC_UPLOAD_PROGRESS" class="APC_UPLOAD_PROGRESS" value="<?php echo $_SERVER['REQUEST_TIME'] . uniqid( '' ); ?>"/>
	<input type="file" name="solution_picture" class="upload_picture"/>
</form>
</div><!--/add_new_solution-->
</div><!--add_new_solution_container-->
<!--templates-->
<!--用户上传图片信息-->
<div id="templates" class="hidden">
<div id="add_new_solution-form-item-textarea_container-imgs-item-template">
<div class="add_new_solution-form-item-textarea_container-imgs-item">
	<img class="pull-left" src="">
	<i class='icon-remove'></i>
</div>
</div>
<!--商品输入容器-->
<div id="add_new_solution-form-item-template">
<div class="add_new_solution-form-item">
	<div class="input-prepend">
		<span class="add-on"></span><input class="product_title" name="product_title" type="text">
		<input class="product_original_price" id="" size="" type="text" placeholder="原价">
		<span class="help-block add_product_describe_link">添加描述</span>
		<div class="add_product_describe-textarea_container" >
			<textarea name="product_describe" class="product_describe" data-maxsize=""></textarea>
			<span class="help-block pull-right"></span>
		</div>
	</div>
</div><!--/add_new_solution-form-item-->
</div><!--/add_new_solution-form-item-template-->
</div><!--/templates-->
