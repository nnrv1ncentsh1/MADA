<!--右侧用户信息-->
<div id="user_info">
         <!--统计信息-->
         <div class="stat_info">
                <div class="favorites_count">
                        <span>
                                <a
                                class="count following_count_for<?php echo $subject_user_info['user_id']; ?>"
                                href="/<?php echo $subject_user_info['domain']; ?>/following/"><?php echo @$subject_user_info['favorites_count']; ?>
                                </a>
                        </span><br />
                        <span>关注</span>
                </div>
                <div class="retweets_count">
                        <span>
                                <a class="count" 
                        href="/<?php echo $subject_user_info['domain']; ?>/followers/"><?php echo @$subject_user_info['retweets_count']; ?></a>
                        </span><br />
                        <span>粉丝</span>
                </div>
                <div class="scheme_count">
                        <span>
                                <a 
                                class="count"
                                href="/<?php echo $subject_user_info['domain']; ?>"><?php echo $subject_user_info['scheme_count']; ?></a>
                        <span><br />
                        <span>方案</span>
                </div>
        </div>
         <!--用户 home 链接-->
         <div class="home_link">
                <i class="icon-globe"></i> <a href="http://dazery.com/<?php echo $subject_user_info['domain']; ?>">dazery.com/<?php echo $subject_user_info['domain']; ?></a>
         </div><!--/.user_home_link-->
        <!--用户城市-->
        <div class="city">
        <i class="icon-map-marker"></i>
<?php echo $subject_user_info['loc_province']; ?>
        <span>
<?php 
//直辖市则不显示城市信息 
$display_none_city = array( '北京市'=>'' , '上海市'=>'' , '重庆市'=>'' , '天津市'=>'' );
if( !array_key_exists( $subject_user_info['loc_city'] , $display_none_city ) ){
        echo $subject_user_info['loc_city'];
}
?>
        </span>
        </div><!--/.city-->
        <div class="notice">
                <h5>通知中心</h5>
        </div><!--/.notice-->
        <div class="about">
                <h5>关于 <span class="nick"><?php echo $subject_user_info['nick']; ?></span></h5>
                <p>
                        <?php echo $subject_user_info['motto']; ?>
                </p>
        </div><!--/.about-->
        <div class="hot_user">
                <h5>热门用户推荐</h5>
        </div>
</div><!--/.user_info-->
<div id="right_footer_info">
        <span>&copy;大智若愚</span>
        <span><a href="/about">移动客户端</a></span>
        <span><a href="/contact">商业合作</a></span>
        <span><a href="/check">支付码查询</a></span>
</div><!--/#right_footer_info-->
