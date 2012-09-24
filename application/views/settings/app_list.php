<script type='text/javascript' src='/js/libs/jquery.validation.js'></script>

<div class="container"> 

<div id="left">
<div id="settings">
	 <!--设置导航-->
	 <div class="nav">
	 <ul>
	 	 <li><a href="/settings/">个人资料</a></li>
	 	 <li><a href="/settings/myface">头像设置</a></li>
	 	 <li><a href="/settings/snstools">社交工具</a></li>
	 	 <li><a href="/settings/account">帐号设置</a></li>
	 	 <li><a href="/settings/app_list" class="active">API Keys</a></li>
	 </ul>
	 </div>
<?php if( !empty( $consumer_access_token_list ) ){ ?>
<table id="app_keys_list">
<tr>
	<th class="center">应用名称</th>
	<th class="center">授权时间</th>
	<th class="center">操作</th>
</tr>
<?php
if( !empty( $consumer_access_token_list ) ){
	foreach( $consumer_access_token_list as $no=>$item ){
?>
<tr>
	 <td class="center"><?php echo $item['application_title'];?></td>
	 <td class="center"><?php echo $item['authorize_time'];?></td>
	 <td class="center"><a href="/settings/do_del_access_token?token=<?php echo $item['token'];?>">取消授权</a></td>
</tr>
<?php
}}}
?>
</table>
</div>
</div>

<div id="right">
<br />
</div>

</div>



