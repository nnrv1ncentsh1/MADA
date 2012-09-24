<div id="welcome" 
<?php 
//判断是否登录
if( empty( $object_user_info ) ){
//未登录
?>
style="margin-top:40px;"
<?php
}else{
//已登录
?>
style="margin-top:0;"
<?php
}
?>
>
<div id="welcome_right">
<p>
<a href="/about">关于我们</a>
</p>

<p>
<a href="/contact">联系我们</a>
</p>
</div>
</div>
</div>
