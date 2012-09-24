//定义 Scheme stream item slider detail (即类型为 detail 的方案下拉菜单) 视图
define( [
"jquery",
"underscore",
"backbone",
"mustache"],

function( $ , _ , Backbone , Mustache ){
        var SchemeItemSliderDetailView = Backbone.View.extend( {
                initialize: function(){
                        _.bindAll( this , "render" );
                        //指定当前 item 中的 slider 元素
                        this.model.bind( "change" , this.render );
                },
                template: $( "#scheme_item_slider_detail_tpl" ).html(),
                render: function(){
                        $( this.el ).html( Mustache.to_html( this.template , this.model.toJSON() ) );
                        return this;
                }
        });
        return SchemeItemSliderDetailView;
});
