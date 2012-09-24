<script type='text/javascript' src='/js/libs/jquery.validation.js'></script> 
<script type='text/javascript' src='/js/libs/autotips.js'></script> 

<script type="text/javascript" src="/js/libs/location/area.js"></script>
<script type="text/javascript" src="/js/libs/location/location.js"></script>

<?php
if( empty( $object_user_info ) ){
?>
<div id="welcome_header">
<!--用户登录表单-->
<div id="welcome_header_cont">
<fieldset id="login_form">
<form id="welcome_login" action="/do_login" method="post">
	 <div class="item span-3 margin-right"> 
	 	 <label for="loginemail">邮箱</label>
		 <input type="text" class="input" name="email" value="" id="loginemail" tabindex='1' onkeydown='press_enter_to_submit( "loginsubmit" );'/> 
		 <div id="auto_login">
			 <input type="checkbox" name="auto_login" class="auto_login_check_box" />
			 <span for="auto_login" class="color_666">下次自动登录</span>
	 	 </div>
	 </div>

	 <div class="item span-3 margin-right">
		 <label for="loginpassword">密码</label>
	 	 <span id="prev_loginpassword" ></span>
		 <input type="password" class="input" name="passwd" id="loginpassword" tabindex='2' onkeydown='press_enter_to_submit( "loginsubmit" );'/> 
	 	 <span class="left" id="lostpwdlink"><a href="/lostpwd">忘记密码 ?</a></span>
	 </div>

	 <div class="item span-1 submit">
	 <div class="button" id="loginsubmit" onclick='document.forms["welcome_login"].submit();'>
		 登录
	 </div>
         </div>
</form> 
</fieldset> 
	 <div class="logo">Logo</div>
</div>
</div>

<?php 
include( getcwd().'/application/views/div/welcome_sub_header.php' );
?>

<?php
echo "<style>#welcome_left{margin-top:40px;}</style>";
}else{

include( 'div/header.php' );
echo "<style>#welcome_left{margin:0;}</style>";
}
?>

<div class="container" style="height:750px;*height:820px;">

<div class="span-11" id="logo_picture">
<div id="welcome_left">

<h5>
关于大智若愚
</h5>

<p>

<b>
大智若愚，助您体验智慧生活。
</b>

<p><b>
大智若愚是一个实时的（real-time）城市生活信息网络，时刻连接最新鲜，您最感兴趣的生活信息。只需要找到能够吸引您的信息源，并决定长期关注他们，便可以轻松接收符合您喜好的实时信息流。
</b></p>

<p>
当您规划家庭消费或者朋友聚会的时候，大智若愚为您提供无风险购买入口，您可以无所顾忌的购买并筛选感兴趣的方案信息，制定理智的消费计划。
</p>

<hr />

<b>
激活商业属性
</b>

<p>
自然人从出生开始，便获得天然的商业属性，在成长过程中，商业属性会无数次显现出来，比如你需要和同学交换文具，需要上街购买生活用品等等。在所有的商业行为中，均存在两种角色：出售方和购买方。你可以通过大智若愚发布方案信息，此时作为出售方，可以尽情展示你的商业理念和哲学。你也可以什么都不发布，仅作为购买方，通过大智若愚获取理想的生活智慧和价值。甚至你会发现，即便是出售方与购买方的角色转换，你都可以通过大智若愚轻松驾驭。
</p>

<hr />

<b>
盲从与懒惰
</b>

<p>
人们总是在盲目的试图寻找让人眼前一亮的商品或服务，又或者只能下意识的跟随着人潮涌动。大智若愚希望帮助人们更加方便的获取优秀的备选方案，并且绝大多数都符合其喜好，不再盲从。
商人们总是一边打着麻将，一边抱怨生意难做，又或者门脸永远贴着那张早已褪色的优惠公告。在我们看来，这是懒惰！大智若愚希望帮助懒惰的商人逐渐勤奋起来，与消费者真诚的互动，更大程度的体会商业的乐趣。
</p>

<hr />

<b>
差异化的魅力
</b>

<p>
商人可以通过大智若愚制定不同的商品或服务组合，我们简称为“套餐”。不同规格的套餐制定不一样的价格，满足不同需求的客户，甚至同一种商品，同一种套餐亦可以制定若干不同的价格。价格的差异能给人们带来更多的惊喜，给商家带来更多的关注。
</p>

<hr />

<b>
新的理念，新的生态
</b>

<p>
大智若愚的经济学理念有助于最大程度的避免商品同质化竞争及价格战，我们希望建立一个更加公平，智慧的商业生态。
</p>

<hr />

</p>
</div>
</div>


<?php 
include( getcwd().'/application/views/div/welcome_right.php' );
?>

<script type="text/javascript"> 
$(document).ready(function(){
	showLocation();
});
$('#loginemail')[0].focus(); 

function press_enter_to_submit( button_name ){

 	 $(document).keypress( button_name , function(e) {

	 	 if ( e.which == 13 ){

			 $( '#' + button_name ).click();
		 }
	 });
}

$(document).ready( function(){
if($.browser.mozilla){
$( '#reg' ).find(':input').each( function(){
	
	this.value = null;
});
}
});
</script>



