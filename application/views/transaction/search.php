<div class="container"> 

<div class="span-5">
<br />
</div>

<!--查询交易码-->
<div id="search_transaction_code" class="span-14">
	 	 
	 <h4>支付码查询</h4>

	 <form id="search_code" action="#" method="post">
		 <label for="tran_code"></label>
 	 	 <div id="input_container" class="span-14">
			 <input type="text" class="title" id="input_tran_code" name="transaction_code"/>
		 	 <span><img src="/picture/common/search.png" alt="查询" onclick="ajax_search();" /></span>
		 </div>
	 </form>

	 <p class="span-14 result_solution_info"> 
	 <div class="loading span-14">
	 </div>
	 <div class="res_info span-14">
	 </div>
	 <p class="opt span-14" style="display:none;">
		 <span class="search_confirm"><a href="#" onclick="confirm_for_search();">确定</a></span>
		 <span class="search_confirm"><a href="#" onclick="drop_search_result();">下次再说</a></span>
	 	 <span class="confirm_info"></span>
	 </p>
	 </p>

</div>


<div class="span-5 last">
<br />
</div>

</div>

<script>
//保存tran_code 
var tran_code = '';
function ajax_search(){
//清除上一次的消息	
$( '.confirm_info' ).html('');
$.ajax({
	 type: "POST",
	 dataType: "json",
	 url : "/transactionopt/do_search",
	 data : $('#search_code').serialize(),
	 error : function(){ 
		 alert( 'ajax error' );
	 } ,
	 beforeSend: function(XMLHttpRequest){

		 $( '.opt' ).hide();
	 	 $( '.res_info' ).html('');

	 	 $( '.loading' ).append( '<img src="/picture/common/ajax-loader2.gif" />' );
	 },
	 success : function( msg ){
	 	 
		 $( '.loading img' ).remove();
		 if( msg.res ){
		 //成功
	
			 tran_code = $('#search_code').serialize();
			 //方案信息
			 $( '.res_info' ).append( 
				 '<table><caption>对应方案信息</span></caption>'+
				 '<tr><td class="span-2">编号</td><td>'+msg.id+'</td></tr>'+
				 '<tr><td class="span-2">标题</td><td>'+msg.title+'</td></tr>'+
				 '<tr><td class="span-2">原价</td><td>'+msg.original_price+'</td></tr>'+
				 '<tr><td class="span-2">现价</td><td>'+msg.sum_now_price+'</td></tr>'+
				 '<tr><td class="span-2">折扣</td><td>'+msg.discount+'</td></tr>'+
				 '<tr><td class="span-2">描述</td><td>'+msg.describe+'</td></tr>' 
			 );
			 //买家信息
			 $( '.res_info' ).append( 
				 '<table><caption>买家信息</span></caption>'+
				 '<tr><td class="span-2">ID</td><td>'+msg.buyer_info.user_id+'</td></tr>'+
				 '<tr><td class="span-2">用户名</td><td>'+msg.buyer_info.nick+'</td></tr>'+
				 '<tr><td class="span-2">注册时间</td><td>'+msg.buyer_info.reg_time+'</td></tr>'+
				 '<tr><td class="span-2">域名</td><td>'+msg.buyer_info.domain+'</td></tr>'+
				 '<tr><td class="span-2">系统域名</td><td>'+msg.buyer_info.sys_domain+'</td></tr>'
			 );
			 //方案所属商品的信息
			 //遍历显示
			 
			 $.each( msg.product_infos , function( no , product_info ){
			 $( '.res_info' ).append( 
				 '<table><caption>方案包含商品信息</span></caption>'+
				 '<tr><td class="span-2">编号</td><td>'+product_info.id+'</td></tr>'+
				 '<tr><td class="span-2">标题</td><td>'+product_info.title+'</td></tr>'+
				 '<tr><td class="span-2">原价</td><td>'+product_info.original_price+'</td></tr>'+
				 '<tr><td class="span-2">描述</td><td>'+product_info.describe+'</td></tr>' 
			 );
			 });
		
		 	 $( '.opt' ).show();
		 }else{
		 //失败
			 $( '.res_info' ).append( msg.info );
			 //插入查询出的方案信息
		 }
	 }
});
}

function confirm_for_search(){

$.ajax({
	 type: "POST",
	 dataType: "json",
	 url : "/transactionopt/corfirm_for_search",
	 data : tran_code,
	 error : function(){ 
		 alert( 'ajax error' );
	 } ,
	 success : function( msg ){
	 
		 if( msg.res ){
	
		 	 $( '.confirm_info' ).append( '该交易状态已更新为已付款.' );
		 }else{
		 	 $( '.confirm_info' ).html('');
		 	 $( '.confirm_info' ).append( msg.info );
		 }
	 }
});
}

function drop_search_result(){

	 $( '.opt' ).hide();
	 $( '.res_info' ).html('');
}
</script>



