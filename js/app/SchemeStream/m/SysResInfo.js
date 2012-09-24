//
// SysResInfo (系统反馈信息) model
// @author <judasnow@gmail.com>
//
define( [
"underscore", 
"backbone",
"backboneLocalStorage"],

function( _ , Backbone ){
//{{{
        var SysResInfoModel = Backbone.Model.extend( {
                defaults:{
                        type: "success",
                        message: "没啥反应"
                },
                localStorage: new Backbone.LocalStorage( "SysResInfo" ),
        });
        return SysResInfoModel;
});//}}}

