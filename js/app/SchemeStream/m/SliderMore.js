//
// toolbar 中的 more 按钮所包含的数据 model
// @author<judasnow@gmail.com>
//
define( [
        "underscore",
        "backbone"
] , 

function(
        _,
        Backbone 
){
//{{{
        var SliderMorelModel = Backbone.Model.extend( {
                defaults: {
                        "scheme_id": "0",
                        "type": "more",
                        //当前方案所有者是否为当前登录用户
                        "is_self_scheme": false,
                        //若 isSelf 为假（当前方案部署于当前登录用户），则标记当前登录
                        //用户是否已经关注了当前方案的所有者
                        "has_follow": false
                },
                initialize: function(){
                //{{{
                        this.url = "api/scheme_api/slider_more/scheme_id/" + this.get( "scheme_id" );
                }//}}}
        });
        return SliderMorelModel;
});//}}}
