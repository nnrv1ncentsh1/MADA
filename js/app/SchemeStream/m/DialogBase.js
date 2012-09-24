//
// DialogBase (基本对话框) model
// @author <judasnow@gmail.com>
//
define( [
"underscore", 
"backbone",
"backboneLocalStorage"],

function( _ , Backbone ){
//{{{
        var DialogBaseModel = Backbone.Model.extend( {
                defaults:{
                        title: "大智若愚",
                        //上次提交表单所有值的 hash
                        lastHash: ""
                },
                localStorage: new Backbone.LocalStorage( "Dialog" ),
        });
        return DialogBaseModel;
});//}}}
