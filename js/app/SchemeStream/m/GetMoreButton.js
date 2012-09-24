//
// 方案流中的 get more (获取更多方案信息) 按钮
// @author <judasnow@gmail.com>
//
define( [
"underscore",
"backbone",
"backboneLocalStorage"],

function( _ , Backbone ){
//{{{
        var GetMoreButton = Backbone.Model.extend( {
                defaults: {
                        //默认页码
                        page: 1,
                        //默认显示在 button 中的 文字/图片 信息
                        text: "更多方案",
                        disable: false
                },
                localStorage: new Backbone.LocalStorage( "SchemeStreamCurrentPage" ),
                //对当前 page 执行加一操作
                plusPage: function(){
                        this.set( "page", parseInt( this.get( "page" ) ) + 1 );
                        return this.get( "page" );
                }
        });
        return GetMoreButton;
});//}}}
