<div id="sub_header">
<span>加入ABC，ABC帮助您体验智慧的生活···</span>
</div>

<div class="container"> 

<div class="span-5">
<br />
</div>

<!--显示系统返回给用户的信息-->
<div id="message" class="span-14">
<?php
if( isset( $message ) && is_array( $message )){

	 $type = $message['type'];
	 $content = $message['content']; 
	 $url = $message['url'];
	 //url描述
	 $url_desc = $message['url_desc'];

	 echo "<p class='$type'>";
	 echo "<span>$content</span>, <span><a href='$url'>$url_desc</span>";	 
	 echo "</p>";
}else{

	echo "<p class='info'>
		<span>还没有提供给你的信息.</span>
	 	<span><a href='/'>返回首页?<a></span></p>";
}
?>
</div>

<div class="span-5 last">
<br />
</div>

</div>
