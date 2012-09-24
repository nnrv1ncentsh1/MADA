// 
// 类型为 more 的下拉菜单中 私聊信息的 Collection
// @author <judasnow@gmail.com>
//
define( [
        "underscore",
        "backbone",
        "app/SchemeStream/m/SliderMoreReplyItem"
] , function(
        _,
        Backbone,
        ReplyItem
){
        var SliderReplyItems = Backbone.Collection.extend( {
                model: ReplyItem,
                url: "/api/reply_api/infos/"
        });
        return SliderReplyItems;
});

