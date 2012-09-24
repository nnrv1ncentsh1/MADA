//
// 类型为 more 的下拉菜单中单条私聊信息的 model 
// @author <judasnow@gmail.com>
// 
define( [
        "underscore",
        "backbone"
],
function(
        _,
        Backbone
){
        var SliderMorelReplyModel = Backbone.Model.extend( {
                defaults: {
                },
                initialize: function(){
                //{{{
                        this.url = "/";
                }//}}}
        });
        return SliderMorelReplyModel;
});
