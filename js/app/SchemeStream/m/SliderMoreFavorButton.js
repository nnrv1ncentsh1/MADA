//
// Favor(用户关注方案) model
// @author <judasnow@gmail.com>
//
define( [
        "underscore",
        "backbone"
],

function( _ , Backbone ){
//{{{
        var FavorModel = Backbone.Model.extend( {
                defaults: {
                        scheme_id: 0,
                        //同当前方案之间的关系 默认是没有关注关系的
                        has_favor: false
                },
                initialize: function(){
                //{{{
                        this.url = "/api/favor_api/info/scheme_id/" + this.get( "scheme_id" );
                }//}}}
        });
        return FavorModel;
});//}}}
