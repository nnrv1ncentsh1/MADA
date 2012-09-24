<script type="text/template" id="scheme_item_slider_time_tpl">
<!--方案时间轴中元素下拉菜单模板文件-->
<!--方案所属用户详细信息(关注/粉丝 人数)以及该方案过期时间-->
<!--保存当前下拉框类型-->
<span class="type">{{type}}</span>
<div class="time">
        <div class="holder_nick">{{holder_nick}}</div>
        <div class="sns_info">
                <div class="retweets_count">
                        <span>{{retweets_count}}</span>
                        <div class="l">RETWEETS</div>
                </div>
                <div class="favorites_count">
                        <span>{{favorites_count}}</span>
                        <div class="l">FAVORITES</div>
                </div>
        </div><!--/.sns_info-->
        <!--方案 发布/转发 时间-->
        <div class="post_time"><i class="icon-time"></i> {{post_time}}</div>
        <!--方案过期时间-->
        <div class="expire_time"><i class="icon-time"></i> {{expire_time}}</div>
</div><!--/.time-->
</script>

<!--转发信息容器-->
<script type="text/template" id="scheme_item_slider_retweet_tpl">
<!--保存当前下拉框类型-->
<span class="type">{{type}}</span>
<div class="retweet_items">
</div>
</script>

<!--下拉菜单中单条转发信息-->
<script type="text/template" id="scheme_item_slider_retweet_item_tpl">
<div class="retweet_item {{#has_top_border}}top_border{{/has_top_border}}" style="{{#img_name}}min-height: 200px{{/img_name}}">
        <img class="user_head" src="/picture/user_head_img/{{retweeter.sys_domain}}_face_32.jpg" />
        <div class="content_con">
                <span class="retweeter_nick">{{retweeter.nick}}</span>
                <b>:</b>
                <span class="content">{{content}}</span>
        </div>
        {{#img_name}}<img src="/picture/retweet/{{img_name}}_resized.jpg">{{/img_name}}
        <div class="more_con">
                <div class="time">{{formated_time}}</div>
                <div class="opt">
                        <a href="javascript:void(0)" class="retweet">转发</a>
                        <a href="javascript:void(0)" class="favor">收藏</a>
                        <a href="javascript:void(0)" class="delete">删除</a>
                </div>
        </div>
</div><!--/.retweet-->
</script>

<!--当前方案详情-->
<script type="text/template" id="scheme_item_slider_detail_tpl">
<!--保存当前下拉框类型-->
<span class="type">{{type}}</span>
<div class="detail">
        <!--商品列表-->
        <div class="product_list">
        <b>商品列表({{products_num}})</b><br />
        {{#products}}
                <div class="product_item">
                        <span class="no">{{no}}</span> .
                        <span class="title">{{title}}</span>
                        原价￥<span class="price">{{original_price}}</span>
                </div>
        {{/products}}
        </div>
        <hr />
        <div class="price_detail">
                原价￥{{original_price}} · 现价￥{{sum_now_price}} · 折扣{{discount}}
        </div>
        <!--方案附加图片-->
        {{#scheme_info.img_name}}
                <img src="/picture/scheme/{{scheme_info.img_name}}_resized.jpg" />
        {{/scheme_info.img_name}}
</div><!--/.detail-->
</script>

<!--交易详情-->
<script type="text/template" id="scheme_item_slider_tran_tpl">
<!--保存当前下拉框类型-->
<span class="type">{{type}}</span>
<div class="tran">
        <p><span>方案状态: {{scheme.formated_status}}</span></p>
        <p><span>信誉评级: <b>{{holder.grade}}</b> ({{review_join_num}}0人参加)</span></p>
        <hr />
        <p class="price_detail">
                原价￥{{scheme.original_price}} · 现价￥{{scheme.sum_now_price}} · 折扣 {{scheme.discount}}
        </p>
        <p class="how_to_pay">
        支付方式 请选择合适的支付方式
        <p>到店支付 同城支付</p>
        </p>
        <p class="tran_history">
                购买记录({{trans_all_num}}) · 支付记录({{trans_paid_num}}) · 评论详情({{review_count}})
        </p>
</div><!--/.tran-->
</script>

<!--更多功能-->
<script type="text/template" id="scheme_item_slider_more_tpl">
<!--保存当前下拉框类型-->
<span class="type">{{type}}</span>
<div class="more">
        <p class="follow_buttons"></p>
        <ul class="functions">
                <li class="item retweet"><i class="icon-retweet"></i>转发</li>
                <div class="favor_items"></div>
                <li class="item reply"><i class="icon-comments"></i>私聊</li>
                <li class="item"><i class="icon-ban-circle"></i>关闭</li>
                <li class="item"><i class="icon-ok-sign"></i>直接激活</li>
                <li class="item"><i class="icon-ok-circle"></i>修改激活</li>
                <li class="item"><i class="icon-remove-sign"></i>删除</li>
        </ul>
        <!--私聊信息-->
        <div class="reply_cont"></div>
</div><!--/.more-->
</script>

<script type="text/template" id="scheme_item_slider_more_follow_buttons_tpl">
{{^has_follow}}<button class="btn btn-mini btn-success do_follow">关注</button>{{/has_follow}}
{{#has_follow}}<button class="btn btn-mini undo_follow">取消关注</button>{{/has_follow}}
</script>

<script type="text/template" id="scheme_item_slider_more_favor_buttons_tpl">
{{^has_favor}}<li class="item do_favor"><i class="icon-heart-empty"></i>收藏</li>{{/has_favor}}
{{#has_favor}}<li class="item undo_favor"><i class="icon-heart"></i>已收藏</li>{{/has_favor}}
</script>

<script type="text/template" id="scheme_item_slider_more_reply_tpl">
<textarea class="reply_input" name="reply_content"></textarea>
<button class="btn btn-mini btn-info do_reply pull-right">回复</button>
<!--私聊的内容-->
<div class="reply_items"></div>
</script>

<script type="text/template" id="scheme_item_slider_more_reply_item_tpl">
<div class="reply_item">
        <a href="javascript:void(0)" class="user">{{replier.nick}}</a>
        <span class="content">{{content}}</span>
        <span class="time">{{formated_time}}</span>
        <p class="opt">
                <a href="javascript:void(0)" class="del pull-right">查看对话</a>
                <a href="javascript:void(0)" class="reply_this_reply pull-right">回复</a>
        </p>
</div>
</script>

