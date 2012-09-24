<script type="text/template" id="add_new_scheme_dialog_tpl">
<!--用户提交方案表单-->
<form id="add_new_scheme_form" action="javascript:void(0);" method="post"> 
<fieldset>
	<!--方案信息输入框-->
	<div id="scheme_info_inputs" class="step1">
		<div class="dialog_item">
			<label for="title">输入一个标题</label>
			<div class="dialog_item_textarea_container">
				<textarea  id="scheme_title" name="scheme_title" data-maxsize="60" data-output="status1" wrap="virtual" class="notnull"></textarea>
				<span id="status1" class="help-block pull-right"></span>
			</div>
		</div>
		<div class="dialog_item">
			<label for="describe">添加更多细节</label>
			<div class="dialog_item_textarea_container">
				<textarea id="scheme_describe" name="scheme_describe" data-maxsize="140" data-output="status2" wrap="virtual" class="notnull"></textarea>
				<!--上传的图片缩略图-->
				<div id="scheme_imgs" class="dialog_item_imgs"></div>
				<span id="status2" class="help-block pull-right"></span>
			</div>
		</div>
	</div><!--/scheme_info_inputs-->
	<!--精简的方案信息-->
	<div id="scheme_info_summary" class="step2"></div>
	<!--相关产品表单容器-->
	<div id="product_container" class="dialog_item step2">
		<p class="product_form_ban">
			 <span>商品或服务列表。填写商品或服务名称，初始售价及相关表述</span>
		</p>
		<!--商品输入框组-->
		<div id="product_items">
		</div><!--/product_items-->
	</div><!--/product_container-->
	<!--默认显示的功能栏-->
	<div id="scheme_default_tools_bar" class="tools_bar">
		<i id="upload_picture" class="icon-camera step1"></i>
		<span id="add_new_product_link" class="pull-right">+ 添加商品或服务</span>
	</div>
</fieldset>
</form>
<div id="add_new_scheme_submit" class="dialog_footer step2">
<!--提交表单按钮-->
<div class="dialog_item">
	<!--动态显示方案的价格-->
	<div id="scheme_info">
		<div class="scheme_info_item">
			<span>现价</span>
			<input type="text" name="sum_now_price" maxlength="8" class="span1"/>
		</div>
		<div class="scheme_info_item">
			<span id="sum_ori_price">原价 <span class="figure">0</span>￥</span>
		</div>
		<div class="scheme_info_item">
			<span id="discount">折扣 <span class="figure"></span></span>
		</div>
	</div>
	<input type="hidden" name="discount" value="" />
	<input type="hidden" name="sum_ori_price" value="0" />
	<button id="submit_solution_button" class="btn btn-primary pull-right btn-large">发布</button>
	<button id="cancel_submit_solution_button" class="btn pull-right btn-large">取消</button>
</div>
</div><!--/dialog_footer-->
</div><!--dialog_main-->
</script>

<!--添加方案的相关商品信息模板-->
<!--一条商品信息-->
<script type="text/template" id="product_item_tpl">
<div class="product_item step2">
	<div class="input-prepend">
		<!--商品编号-->
		<span class="add-on product_no">{{product_no}}</span>
		<input class="product_title" name="product_title" type="text">
		<input class="product_original_price" id="" size="" type="text" placeholder="原价">
		<!--点击显示填写商品详细信息的输入框-->
		<span class="help-block add_product_describe_link">添加描述</span>
		<div class="product_textarea_container">
			<textarea name="product_describe" class="dialog_item_textarea_container product_describe" data-maxsize=""></textarea>
			<span class="help-block pull-right"></span>
		</div>
	</div>
</div>
</script>

<!--step2中的方案信息摘要-->
<script type="text/template" id="scheme_info_summary_tpl">
<span class="title">{{title}}。</span>&nbsp;<span class="describe">{{describe}}</span>
</script>
