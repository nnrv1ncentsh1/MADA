<!--左侧顶部用户信息-->
<div id="top_user_info">
<span class="user_nick"><b><a href="/<?php echo $subject_user_info['domain'] ?>" ><?php echo $subject_user_info['nick'];?></a></b>
<?php 
if( $page_name == 'contacts' ){
?>
<span>关注了 <b class="following_count_for<?php echo $subject_user_info['user_id']; ?>" style="font-weight:100;line-height:100%;" ><?php echo $subject_user_info['contacts_num']; ?></b> 个用户 </span>
<?php
}else if( $page_name == 'rev_contacts' ){
?>
<span> 有 <?php echo $subject_user_info['rev_contacts_num']; ?> 个粉丝</span>
<?php
}
?>
</span><!--/.user_nick-->
<span class="user_motto"><?php echo $subject_user_info['describe']; ?></span>
</div><!--/#top_user_info-->
