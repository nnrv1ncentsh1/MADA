//
// 定义 Scheme stream item slider more (即类型为 more 的方案下拉菜单) 视图
// @author <judasnow@gmail.com>
//
define( [
        "jquery",
        "underscore",
        "backbone",
        "mustache",
        "app/SchemeStream/m/DialogBase",
        "app/SchemeStream/v/RetweetDialog",
        "app/SchemeStream/m/SliderMoreFollowButton",
        "app/SchemeStream/v/SliderMoreFollowButton",
        "app/SchemeStream/m/SliderMoreFavorButton",
        "app/SchemeStream/v/SliderMoreFavorButton",
        "app/SchemeStream/m/SliderMoreReply",
        "app/SchemeStream/v/SliderMoreReply"
],
function(
        $ ,
        _ ,
        Backbone,
        Mustache,
        DialogBaseModel,
        RetweetDialogView,
        FollowButton,
        FollowButtonView,
        FavorButton,
        FavorButtonView,
        Reply,
        ReplyView
){
        var SchemeItemSliderMoreView = Backbone.View.extend( {
                template: $( "#scheme_item_slider_more_tpl" ).html(),
                events: {
                        "click .functions .retweet": "showRetweetDialog",
                        "click .functions .reply": "toggleReply"
                },
                initialize: function(){
                //{{{
                        _.bindAll( this , "render" , "initFavorButton" , "initFollowButton" , "initReply" );
                        this.model.bind( "change" , this.render );
                },//}}}
                toggleReply: function(){
                //{{{
                        var $replyCont = this.$el.find( ".reply_cont" );
                        if( $replyCont.is( ":hidden" ) ){
                                //若之前处于隐藏状态 则重新渲染之
                                this.initReply();
                                $replyCont.show();
                        }else{
                                $replyCont.hide();
                        }
                },//}}}
                //初始化私聊
                initReply: function(){
                //{{{
                        var reply = new Reply( {
                                scheme_id: this.model.get( "scheme_id" )
                        });
                        var replyView = new ReplyView( {
                                el: this.$el,
                                model: reply
                        });
                        replyView.render();
                        reply.fetch();
                },//}}}
                initFollowButton: function(){
                //{{{
                        var followButton = new FollowButton( {
                                user_id: this.model.toJSON()["scheme_info"]["holder"]["user_id"]
                        });
                        var followButtonView = new FollowButtonView( {
                                //指定作用范围为当前的 slider
                                el: this.$el,
                                model: followButton
                        });
                        followButtonView.render();
                        followButton.fetch();
                },//}}}
                initFavorButton: function(){
                //{{{
                        //初始化关注按钮
                        var favorButton = new FavorButton( {
                                scheme_id: this.model.get( "scheme_id" )
                        });
                        var favorButtonView = new FavorButtonView( {
                                el: this.$el,
                                model: favorButton
                        });
                        favorButtonView.render();
                        favorButton.fetch();
                },//}}}
                render: function(){
                //{{{
                        this.$el.html( Mustache.to_html( this.template , this.model.toJSON() ) );
                        //如果该方案不归当前登录用户所有，则需要显示 关注/取消关注 按钮
                        if( this.model.get( "is_self_scheme" ) === false ){
                                this.initFollowButton();
                        }
                        this.initFavorButton();
                        return this;
                },//}}}
                showRetweetDialog: function(){
                //{{{
                        var retweetDialogView = new RetweetDialogView( 
                                {
                                        id: "from_scheme_retweet_dialog",
                                        model: new DialogBaseModel(
                                                {
                                                        title: "转发方案",
                                                        //转发针对的对象信息
                                                        //可以是 scheme 也可以是另一条 retweet
                                                        fromInfo: this.model.get( "scheme_info" )
                                                }
                                        )
                                }
                        );
                }//}}}
        });
        return SchemeItemSliderMoreView;
});
