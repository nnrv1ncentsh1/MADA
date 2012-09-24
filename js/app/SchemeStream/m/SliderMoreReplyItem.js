//
// 类型为 more 的下拉菜单中单条私聊信息的 model 
// @author <judasnow@gmail.com>
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
                        replier_id: 0,
                        prev_reply_id: null,
                        scheme_id: 0,
                        content: null,
                        time: 0000-00-00
                },
                initialize: function(){
                //{{{
                        this.url = "api/reply_apy/info/scheme_id/" + this.get( "scheme_id" );
                }//}}}
        });
        return SliderMorelReplyModel;
});
