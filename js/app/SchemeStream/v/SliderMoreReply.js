//
// 下拉菜单中整个私聊功能的容器 View
// @author<judasnow@gmail.com>
//
define( [
        "jquery",
        "underscore",
        "backbone",
        "mustache",
        "app/SchemeStream/m/SliderMoreReply",
        "app/SchemeStream/m/SliderMoreReplyItem",
        "app/SchemeStream/v/SliderMoreReplyItem",
        "app/SchemeStream/c/SliderMoreReplyItems",
        "autogrow"
],
function(
        $,
        _,
        Backbone,
        Mustache,
        Reply,
        ReplyItem,
        ReplyItemView,
        ReplyItems
){
        var replyItems = new ReplyItems;
        var SliderReplyView = Backbone.View.extend( {
                template: $( "#scheme_item_slider_more_reply_tpl" ).html(),
                initialize: function(){
                        _.bindAll( this , "render" , "addOne" , "addAll" , "doReply" );
                        this.model.bind( "change" , this.render );
                },
                events: {
                        //提交新的私聊信息
                        "click .reply_cont .do_reply": "doReply"
                },
                addOne: function( replyItem ){
                        var replyItemView = new ReplyItemView( {model: replyItem} );
                        this.$replyItems.append( replyItemView.render().el );
                },
                addAll: function(){
                        replyItems.each( this.addOne );
                },
                render: function(){
                //{{{
                        //渲染框架
                        //获取框架元素对象
                        this.$replyCont = this.$el.find( ".reply_cont" );
                        this.$replyCont.html( Mustache.to_html( this.template , this.model.toJSON() ) );
                        this.$replyItems = this.$el.find( ".reply_items" );
                        //获取输入框元素的引用
                        this.$replyInput = this.$replyCont.find( ".reply_input" );
                        this.$replyInput.autogrow();
                        //获取集合 分别渲染之
                        replyItems.bind( "add", this.addOne );
                        replyItems.bind( "reset", this.addAll );
                        replyItems.fetch( 
                                {
                                        data: {
                                              scheme_id: this.model.get( "scheme_id" )
                                        },
                                        error: function(){
                                                window.sysResInfoModel( "获取私聊信息错误." );
                                        }
                                }
                        );
                        return this;
                },//}}}
                doReply: function(){
                //{{{
                        var content = this.$replyInput.val();
                        var pre_reply_id;
                        var scheme_id;
                }//}}}
        });
        return SliderReplyView;
});
