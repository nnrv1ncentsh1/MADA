<div class="container"> 

<div class="span6">
<br />
</div>

<div id="solution_submit" class="span14">
<!--用户提交方案表单-->
<form id="solution_submit" action="reg/do_solution_submit" method="post"> 
<fieldset>
	 <!--显示错误信息-->
	 <?php if( isset( $solution_submit_error_info ) ){ ?>
	 <div class="error">
	 <ol>
	 <?php 
	 	 foreach( $solution_submit_error_info as $title=>$info ){
			 echo '<li>' . $info . '</li>';
	 	 }
	 ?>
	 <ol>
	 </div>
	 <?php } ?>

	 <div class="span1">
	 <br />
	 </div>

	 <div class="span10">

         <p> 
         	 <label for="title">标题</label><span>添加新方案标题</span>
		 <textarea name="title" id="title"></textarea>
	 </p>

         <p> 
         	 <label for="descript">描述</label><span>提供更多方案的细节</span>
		 <textarea name="descript" id="descript"></textarea>
	 </p>
	 
	 <!--产品服务表单容器-->
	 <div id="add_product_form">
	 <label for="products">商品或服务列表</label><span>填写商品或服务名称，初始售价及相关表述</span>
	 </div>
	 <!--增加一条产品或服务-->
	 <span class="add_new_product_link" onClick="show_new_add_product_form();">点击添加商品或服务</span><br />
	 
	 <p class="submit">
	 	 <div class="button_outside_border_green" onclick="available_submit();">
	  	 <div class="button_inside_border_green">发布</div>
		 </div>
	 </p>
	 
	 </div>

	 <div class="span-1 last">
	 <br />
	 </div>

		 
<script>
function remove_default_value(){
	 //each遍历文本框
	 $(".input").each(function() {
	 //保存当前文本框的值
		 var vdefault = this.value;
        	 $(this).focus(function() {
            	 //获得焦点时，如果值为默认值，则设置为空
            	 if (this.value == vdefault) {
                	 this.value = "";
            	 }
        });
        $(this).blur(function() {
            //失去焦点时，如果值为空，则设置为默认值
            if (this.value == "") {
                this.value = vdefault;
            }
        });
	 });
	}

function show_new_add_product_form(){
		$('#add_product_form').append( '<div class="add_products"><p><input type="text"  value="商品或服务名称" class="input product_name" /><input type="text" value="初始售价" class="price input"/><br /><textarea name="descript" id="descript"></textarea></p></div>' );
		remove_default_value();
	}
</script>


</fieldset> 
</form> 

</div>

<div class="span6 last">
<br />
</div>

</div>

