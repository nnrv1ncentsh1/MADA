<script src="/js/header_script.js"></script>

<script src="/js/libs/Impromptu/jquery-impromptu.js"></script>
<link rel="stylesheet" href="/js/libs/Impromptu/default.css" type="text/css" media="screen, projection">

<script src="/js/libs/md5.js"></script>
<script src="/js/libs/jquery.inputlimitor.1.0.js"></script>

<form id="edit" action="/admin/do_add_new_solution" method="post"> 

	 <h4>添加新方案</h4>
	 <fieldset> 
	 <legend></legend> 

	 <p>
	 <a href="#" class="opt" onclick="check_and_submit_update( 'edit' );">提交</a>
	 </p>

	 <p class="price_info">
 	 	 <span class="sum_ori_price"></span> 
		 <span class="sum_now_price"></span> 
		 <span class="page_discount"></span>

	 	 <!--需要时才会显示-->
		 <input type="hidden" name="discount" value="1" />
	 	 <input type="hidden" name="sum_ori_price" value="0" />
	 </p>

	 <p id="res_info">
<?php 
if( !empty( $opt_solution_info_res ) ){
	 
	 $info = $opt_solution_info_res['info'];
	 switch( $opt_solution_info_res['res'] ){
	 
	 	 case TRUE:
			 echo "<div class='succ'>$info</div>";
			 break;
		 case FALSE:
			 echo "<div class='err'>$info</div>";
			 break;
	 }
}		
?>

	 </p>
	 
	 <div class="span-10" id="solution_common_info">
	 <p>
	 <b class="input_type">方案基本信息</b>
	 </p>

	 <p>
		 <label for="holder">发布者</label><br />
	 	 <!--确定卖家的形式-->
		 <select name="holder_form">
			 <option value="user_id">ID</option>
			 <option value="nick">用户名</option>
	 	 	 <option value="sys_domain">系统域名</option>
		 </select>
	 	 <br />
	 	 <input type="text" class="text" name="holder_input_info" id="holder_input_info" /><br />
	 </p>

	 <p>
         	 <label for="title">标题</label><br />
		 <input type="text" class="text" id="solution_title" name="solution_title"  /><br />
	 </p>

	 <p>
         	 <label for="describe">描述</label><br />
		 <textarea name="solution_describe" id="solution_describe"></textarea>
	 </p>

	 <p>
         	 <label for="sum_now_price">现价</label><br />
		 <input type="text" class="text input_sum_now_price" name="sum_now_price" id="sum_now_price" 
	 	 	onchange="refresh_discount();" /><br />
	 </p>
	 </div>
	 
	 <div class="span-8 last" id="product_info">
	 <p>
	 <b class="input_type">商品信息</b>
	 </p>

	 <div>
	 <div id="product_forms">
	 <div class="one_product" id="0">
	 
	 <span>1.</span>
	 <p>
         	 <label for="original_price">标题</label><br />
		 <input type="text" class="text" name="title0" id="title0"  /><br />
	 </p>

	 <p>
         	 <label for="original_price">描述</label><br />
	 	 <textarea id="describe" name="describe0"></textarea>
	 </p>

	 <p>
         	 <label for="original_price">价格</label><br />
		 <input type="text" class="text original_price" name="original_price0"
	 	 	 onchange="refresh_ori_sum_price();" onblur="refresh_ori_sum_price();" /><br />
	 </p>

	 </div>

	 </div>
	 <!--目前最大编号-->
	 <span style="display:none;" id="max_product_no">1</span>
	 </div>
	 	 
	 <br />
	 
	 <p>
	 <span href="#" class="opt" onclick="add_new_product_form();">添加一个商品</span>
	 </p>

	 </div>
 
</fieldset> 

</form> 

<script>
//商品编号
var no = $( '#max_product_no' ).html();
no = !isNaN(no) ? no : 0;
function add_new_product_form(){
	
	no = !isNaN(no) ? no : 0;
	var form_html = '<div class="one_product" id="'+ no +'"><p>'+(parseInt(no)+1)+'.</p><p><label for="original_price">标题</label><br /><input type="text" class="text" name="title'+ no +'" id="title'+ no +'" /><br /></p><p><label for="original_price">描述</label><br /><textarea id="describe" name="describe'+ no +'"></textarea></p><p><label for="original_price">价格</label><br /><input type="text" class="text original_price" name="original_price'+ no +'" id="original_price'+ no +'" value="" onchange="refresh_ori_sum_price();" onblur="refresh_ori_sum_price();"/><br /></p><span class="opt" onclick="delete_product_form( '+ no +' );">删除</span></div>';
	no++;
	$( '#product_forms' ).append( form_html );
}
function delete_product_form( product_no ){

	$( '#' + product_no ).remove();

	//更新编号
	$.each( $( '.one_product' ) , function( i , v ){
		
		var new_no = i + 1;

		$.each( $( v ).find( ':input' ) , function( i , v ){ 
		
			//替换所有的input编号
			//原有的name
			var ori_name = $( v ).attr( 'name' );
			if( ori_name.match( 'title' ) ){
				 
				new_name = 'title' + new_no;
			}
			if( ori_name.match( 'describe' ) ){
				 
				new_name = 'describe' + new_no;
						}
			if( ori_name.match( 'original_price' ) ){
				 
				new_name = 'original_price' + new_no;
			}

		 	$( v ).attr( 'name' , new_name );
		});
	});
}

function check_and_submit_update( id ){

	if ( !can_it_submit() ){
	
		 return false;
	}

	$( '#' + id ).submit();
}
</script>




