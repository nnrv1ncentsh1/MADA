//
// AppConfig (所有 App 的配置信息) model
// @author <judasnow@gmail.com>
//
define( [
"underscore", 
"backbone"],

function( 
        _,
        Backbone 
){
        var AppConfigModel = Backbone.Model.extend( {
                //方案属性的默认值
                defaults:{
                        //当前登录用户 info 
                        object_user_info: {},
                        //当前页面所有者 info 
                        subject_user_info: {}
                },
                initialize: function(){
                        this.url = "/api/app_api/config/";
                }
        });
        return AppConfigModel;
});
