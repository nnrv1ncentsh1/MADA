//
// Scheme(方案) model
// @author <judasnow@gmail.com>
//
define( [
"underscore", 
"backbone"],

function( _ , Backbone ){
//{{{
        var SchemeModel = Backbone.Model.extend( {
                //方案属性的默认值
                defaults:{
                        "title": "没有标题",
                        "describe": "没有描述",
                        "time": "1900-10-10 00:00:00",
                        "holder": {
                                "id": 0,
                                "nick": "不存在的用户"
                        }
                },
                initialize: function(){
                //{{{
                        this.url = "/api/scheme_api/info/";
                        this.set( "holder" , this.toJSON()['holder'] );
                }//}}}
        });
        return SchemeModel;
});//}}}
