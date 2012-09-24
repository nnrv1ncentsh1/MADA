<div class="container"> 

<div id="left">

<?php 
$border = TRUE;
include( getcwd().'/application/views/div/top_user_info.php' );
?>

<!--当前用户关注人的列表-->
<div id="contacts">

<ol>
<?php 
include( getcwd().'/application/views/div/contact_items.php' );
?>
</ol>

<div id="pager">
<?php echo $this->pagination->create_links(); ?>
</div>

</div>
</div>

<div id="right">
<?php 
include( getcwd().'/application/views/div/user_info.php' );
?>
</div>

</div>

</div>

<script src="/js/followopt.js" type="text/javascript"></script>

