<?php
foreach ( $contacts as $no=>$contact_user_info ){
?>
<li id="userid<?php echo $contact_user_info['user_id']; ?>">
<div class="user_head_img">
<!--用户头像链接-->
<a href="/<?php echo $contact_user_info[ 'domain' ]?>" title=""><img src="/picture/user_head_img/<?php
//判断用户头像是否存在
if( !file_exists( $_SERVER['DOCUMENT_ROOT'] . '/picture/user_head_img/' . $contact_user_info['sys_domain'] . '_face_small.jpg' ) ){
	
	//不存在 显示默认头像  
	echo "anonymous_face.jpg";
}else{

	echo $contact_user_info['sys_domain'] . '_face_small.jpg'; 
}
?>" /></a>
</div>
<div class="user_info">
<span><a href="/<?php echo $contact_user_info[ 'domain' ]?>" title=""><?php echo $contact_user_info['nick']; ?></a></span>
<span><?php echo $contact_user_info['describe']; ?></span>
<span><?php echo $contact_user_info['loc_province']; ?>

<?php

//直辖市则不显示城市信息 
$display_none_city = array( '北京市'=>'' , '上海市'=>'' , '重庆市'=>'' , '天津市'=>'' );

if( !array_key_exists( $contact_user_info['loc_city'] , $display_none_city ) ){
		 
	echo $contact_user_info['loc_city'];
}
	
?>

</span>
</div>
<?php 
if( isset( $object_user_info ) && !empty( $object_user_info ) ){
//该用户不是当前登录用户才会显示关注按钮
if( $contact_user_info['user_id'] != $object_user_info['user_id'] ){
?>
<div class="cancel_follow">

<div class="is_follow" <?php if( !$contact_user_info['is_object_user_follow'] ) echo 'style="display:none"'; ?>>
<a href="javascript:void(0);" onclick="follow.follow_opt( 'undo' , '<?php echo $contact_user_info['user_id']; ?>' , <?php 
//只有在自己的页面上,且不是 rev_contacts 页面 取消关注某人后 才会
//消失 
if( isset( $subject_user_info ) && ($subject_user_info['user_id'] != $object_user_info['user_id']) || ($page_name == 'rev_contacts') ){

	echo "'switch_follow_button'";
}else{	
	 
	echo "'delete_this_follow_item'";
}
?>, <?php echo $object_user_info['user_id']; ?> )">取消关注</a>
</div>	 

<div class="isnot_follow" <?php if( $contact_user_info['is_object_user_follow'] ) echo 'style="display:none"'; ?>>
<a href="javascript:void(0);" onclick="follow.follow_opt( 'do' , '<?php echo $contact_user_info['user_id']; ?>' , 'switch_follow_button' , <?php echo $object_user_info['user_id']; ?> )"><b>+</b> 加关注</a>
</div>

<?php
}
}else{
?>
<div class="cancel_follow">

<div class="isnot_follow">
<a href="javascript:void(0);" onclick="hight_light( $( '#loginemail') );hight_light( $( '#loginpassword') );"><b>+</b> 加关注</a>
</div>

</div>
</li>
<?php
}
}
?>

