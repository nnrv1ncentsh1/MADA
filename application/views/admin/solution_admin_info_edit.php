<script src="/js/header_script.js"></script>

<script src="/js/libs/Impromptu/jquery-impromptu.js"></script>
<link rel="stylesheet" href="/js/libs/Impromptu/default.css" type="text/css" media="screen, projection">

<script src="/js/libs/md5.js"></script>
<script src="/js/libs/jquery.inputlimitor.1.0.js"></script>

<form id="edit" action="/admin/do_solution_admin_info_edit" method="post"> 

<?php
if( !empty( $solution_info ) ){
?>

	 <h4>编辑方案</h4>
	 <fieldset> 
	 <legend></legend> 

	 <p>
	 <a href="#" class="opt" onclick="check_and_submit_update( 'edit' );">提交修改</a>
	 </p>

	 <p class="price_info">
 	 	 <span class="sum_ori_price"></span>
		 <span class="sum_now_price"></span>
		 <span class="page_discount"></span>

	 	 <!--需要时才会显示-->
		 <input type="hidden" name="discount" value="1" />
	 	 <input type="hidden" name="sum_ori_price" value="0" />
	 </p>

	 <p>
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
         	 <label for="solution_id">方案编号(添加新方案时系统自动生成,不可更改)</label><br />
		 <input type="text" class="text" name="" id="solution_id" disabled="true" value="<?php echo $solution_info['id']; ?>" /><br />
	 	 <input type="hidden" name="solution_id" value="<?php echo $solution_info['id']; ?>" />
	 </p>

	 <p>
         	 <label for="holder_id">发布者</label><br />
		 <input type="text" class="text" name="holder_id" id="holder_id" value="<?php echo $solution_info['holder_id']; ?>" /><br />
	 </p>

	 <p>
         	 <label for="title">标题</label><br />
		 <input type="text" class="text" id="solution_title" name="solution_title" value="<?php echo $solution_info['title']; ?>" /><br />
	 </p>

	 <p>
         	 <label for="describe">描述</label><br />
		 <textarea name="solution_describe" id="solution_describe"><?php echo $solution_info['describe']; ?></textarea>
	 </p>

	 <p>
         	 <label for="sum_now_price">现价</label><br />
		 <input type="text" class="text input_sum_now_price" name="sum_now_price" id="sum_now_price" 
	 	 	onchange="refresh_discount();" value="<?php echo $solution_info['sum_now_price']; ?>" /><br />
	 </p>
	 </div>
	 
	 <div class="span-8 last" id="product_info">
	 <p>
	 <b class="input_type">商品信息</b>
	 </p>

	 <div>
	 <div id="product_forms">
<?php 
	 if( !empty( $product_infos ) ){
	 $i = 0;
	 foreach( $product_infos as $no=>$product_info ){
		 if( $product_info['title'] == '' ){
		 	 
			 continue;
		 }
?>
	 <div class="one_product" id="<?php echo $i ; ?>">
	 
	 <span><?php 
	 echo $i+1;
	 ?>.</span>
	 <p>
         	 <label for="original_price">标题</label><br />
		 <input type="text" class="text" name="title<?php echo $i;?>" id="title<?php echo $i;?>" value="<?php echo $product_info['title']; ?>" /><br />
	 </p>

	 <p>
         	 <label for="original_price">描述</label><br />
	 	 <textarea id="describe" name="describe<?php echo $i;?>"><?php echo $product_info['describe']; ?></textarea>
	 </p>

	 <p>
         	 <label for="original_price">价格</label><br />
		 <input type="text" class="text original_price" name="original_price<?php echo $i;?>" id="original_price<?php echo $i;?>"
	 	 	 onchange="refresh_ori_sum_price();" onblur="refresh_ori_sum_price();" value="<?php echo $product_info['original_price'];?>" /><br />
	 </p>

	 <p>
	 <a href="#" class="opt" onclick="delete_product_form( <?php echo $i ; ?> );">删除</a>
	 </p>

	 </div>
<?php
	 $i++;	 
}
}
?>
	 </div>
	 <!--目前最大编号-->
	 <span style="display:none;" id="max_product_no"><?php echo $i; ?></span>
	 </div>
	 	 
	 <br />
	 
	 <p>
	 <span href="#" class="opt" onclick="add_new_product_form();">添加一个商品</span>
	 </p>

	 </div>
 
</fieldset> 
<?php
}
?>
</form> 

<script>
//商品编号
var no = $( '#max_product_no' ).html();
no = !isNaN(no) ? no : 0;
function add_new_product_form(){
	
	no = !isNaN(no) ? no : 0;
	var form_html = '<div class="one_product" id="'+ no +'"><p>'+ (parseInt(no)+1) +'.</p><p><label for="original_price">标题</label><br /><input type="text" class="text" name="title'+ no +'" id="title'+ no +'" /><br /></p><p><label for="original_price">描述</label><br /><textarea id="describe" name="describe'+ no +'"></textarea></p><p><label for="original_price">价格</label><br /><input type="text" class="text original_price" name="original_price'+ no +'" id="original_price'+ no +'" value="" onchange="refresh_ori_sum_price();" onblur="refresh_ori_sum_price();"/><br /></p><span class="opt" onclick="delete_product_form( '+ no +' );">删除</span></div>';
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

refresh_ori_sum_price();
refresh_discount();
</script>
