//
// 单条私聊信息
// @author<judasnow@gmail.com>
//
define( [
        "jquery",
        "underscore",
        "backbone",
        "mustache",
        "app/SchemeStream/m/SliderMoreReplyItem"
],
function(
        $,
        _,
        Backbone,
        Mustache,
        ReplyItem
){
        var SliderReplyItemView = Backbone.View.extend( {
                model: ReplyItem,
                template: $( "#scheme_item_slider_more_reply_item_tpl" ).html(),
                initialize: function(){
                        _.bindAll( this , "render" , "toggleOptBar" , "addReplyChain" );
                        this.model.bind( "change" , this.render );
                },
                events: {
                        "mouseover": "toggleOptBar",
                        "mouseout": "toggleOptBar",
                        "click .reply_this_reply": "addReplyChain"
                },
                toggleOptBar: function(){
                        this.$el.find( ".opt" ).toggle();
                },
                //添加转发链信息 
                addReplyChain: function(){
                        alert( "will add chain of reply" );
                },
                render: function(){
                //{{{
                       this.$el.html( Mustache.to_html( this.template , this.model.toJSON() ) );
                       return this; 
                }//}}}
        });
        return SliderReplyItemView;
});
