//
// 定义 System Response Infomation(系统回馈信息) 视图
// @author<judasnow@gmail.com>
//
define( [
"jquery",
"underscore",
"backbone",
"mustache"],

function( $ , _ , Backbone , Mustache ){
        var INFO_HIDE_TIMEOUT = 3;
        var SysResInfoView = Backbone.View.extend( {
                el: $( "#sys_res_info" ),
                initialize: function(){
                        _.bindAll( this , "render" , "keepPosition" , "hideInfo" , "showInfo" );
                        //向 window 绑定事件,保持其位置在页首不变
                        $( window ).scroll( this.keepPosition );
                        this.model.bind( "change" , this.render );
                },
                events: {
                        "click .close_button": "hideInfo"
                },
                template: $( "#sys_res_info_tpl" ).html(),
                render: function(){
                        this.El = $( this.el );
                        this.El.html( Mustache.to_html( this.template , this.model.toJSON() ) );
                        this.showInfo();
                        //如果为可见状态 则 INFO_HIDE_TIMEOUT 之后淡出
                        if( this.El.is( ":visible" ) ){
                                setTimeout( $.proxy( function(){
                                        this.hideInfo();
                                } , this ) , 2000 );
                        }
                        return this;
                },
                keepPosition: function(){
                //{{{
                        this.El.stop( true ).animate( {"top": parseInt( 40 ) + parseInt( $(window).scrollTop() ) + "px"} , 100 );
                },//}}}
                showInfo: function(){
                //{{{
                        if( this.model.get( "message" ) === "nospecial." ){
                                return;
                        }
                        this.El.stop( true ).fadeIn( "fast" );
                },//}}}
                hideInfo: function(){
                //{{{
                        this.El.stop( true ).fadeOut( 
                                        "fast" , 
                                        $.proxy(
                                                function(){
                                                        this.model.set( "message" , "nospecial." );
                                                } , this ));
                },//}}}
        });
        return SysResInfoView;
});

