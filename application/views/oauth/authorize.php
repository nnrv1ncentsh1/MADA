<div class="container">

<div id="left">
<div id="request_for_permission">
<h2>API 认证授权</h2>

<h3>
第三方应用 L 希望操作你在大智若愚上的数据。
</h3>

<p>
请确保你是从 L 提供的页面跳转到本页面，否则强烈建议你拒绝本次授权。
</p>

<form action="do_authorize" method="post" id="do_authorize">
<input type="hidden" name="apply_user_id" value="<?php echo $apply_user_id; ?>" />
<input type="hidden" name="consumer_key" value="<?php echo $consumer_key; ?>" />
<input type="hidden" name="oauth_token" value="<?php echo $token['oauth_token']; ?>" />
<input type="hidden" name="oauth_callback" value="http://127.0.0.1:81/oauth2/access_token/" />
<input type="hidden" name="allow" value="TURE" />
</form>
<div class="span-2 left">
<div id="allow" class="button_outside_border_green">
	 <div class="button_inside_border_green" style="background-color:#72ac58;border-top:solid #98c584 1px;">
	 同意
	 </div>
</div>
</div>

<div class="span-2 last">
<div id="deny" class="button_outside_border_green">
	 <div class="button_inside_border_green" style="background-color:#72ac58;border-top:solid #98c584 1px;">
	 不同意
	 </div>
</div>
</div>

</div>
</div>

</div>

<script type="text/javascript">
(function(){

	$( '#allow' ).click( function(){
	
		 $( '#do_authorize' ).submit();
	});

	$( '#deny' ).click( function(){

		 //deny 登录
		window.location.href = 'http://<?php echo $client_uri; ?>/auth/deny/';
	});
})();
</script>




