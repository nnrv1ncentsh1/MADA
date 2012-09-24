<?php include( getcwd() . '/application/views/div/welcome_header.php' ); ?>

<div class="container">

<!--欢迎页面左侧图片区-->
<div id="logo_picture">
<div id="welcome_left">
<p>

<h2><b>大智若愚，助您体验智慧生活。大智若愚是一个实时的（real-time）城市生活信息网络，时刻连接最新鲜，您最感兴趣的生活信息。只需要找到能够吸引您的信息源，并决定长期关注他们，便可以轻松接收符合您喜好的实时信息流。</b></h2>

<p><h2>当您规划家庭消费或者朋友聚会的时候，大智若愚为您提供无风险购买入口，您可以无所顾忌的购买并筛选感兴趣的方案信息，制定理智的消费计划。</h2></p>

<div style="text-align:right;margin-top:30px;"><i>
—— <b>dazery</b> for <b>Intelligent life</b>
</i></div>
</p>
</div><!--end of welcome_left-->
</div><!--end of logo_picture-->

<!--欢迎页面右侧区域-->
<div id="welcome">
<!--用户注册表单-->
<form action="reg" method="post" > 
<fieldset class="reg_form">
        <div class="title">
                <h5>第一次使用大智若愚？现在就加入我们吧!</h5>
        </div>

        <p class="input_cont"> 
                <label for="email"></label>
                <input type="text" class="text input" name="email" id="email" placeholder="邮箱" ><br />
        </p>

        <p class="input_cont">
                <label for="nick"></label>
                <input type="text" class="text input" name="nick" placeholder="用户名" id="nick"><br />
        </p>

        <p class="input_cont"> 
                <label for="password"></label>
                <input type="text" class="text input" name="default_password" placeholder="密码" id="default_password" autocomplete="off" >
                <input type="password" class="text input" name="password" value="" id="password" style="display:none;" autocomplete="off"><br />
        </p>

        <div class="submit">
                <button class="btn btn-success">注册</button>
        </div>
</fieldset>
</form>
</div><!--end of welcome-->
</div><!--end of container-->
