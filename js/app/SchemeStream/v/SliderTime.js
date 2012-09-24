//
// 定义 Scheme stream item slider time (即类型为 time 的方案下拉菜单) 视图
// @author <judasnow@gmail.com>
//
define( [
"jquery",
"underscore",
"backbone",
"mustache"],

function( $ , _ , Backbone , Mustache ){
        var SchemeItemSliderTimeView = Backbone.View.extend( {
                initialize: function(){
                        _.bindAll( this , "render" );
                        //指定当前 item 中的 slider 元素
                        this.model.bind( "change" , this.render );
                },
                template: $( "#scheme_item_slider_time_tpl" ).html(),
                render: function(){
                        $( this.el ).html( Mustache.to_html( this.template , this.model.toJSON() ) );
                        return this;
                }
        });
        return SchemeItemSliderTimeView;
});
