//
// 定义 Scheme stream item slider retweets (即类型为 retweet 的方案下拉菜单) 视图
// @author<judasnow@gmail.com>
//
define( [
        "jquery",
        "underscore",
        "backbone",
        "mustache",
        "app/SchemeStream/m/SliderRetweetItem",
        "app/SchemeStream/c/SliderRetweetItems",
        "app/SchemeStream/v/SliderRetweetItem"
],
function(
        $,
        _,
        Backbone,
        Mustache,
        SliderRetweetItemModel,
        SliderRetweetItems,
        SliderRetweetItemView
){
        var SliderRetweetsView = Backbone.View.extend( {
                //总的容器模板
                template: $( "#scheme_item_slider_retweet_tpl" ).html(),
                initialize: function(){
                //{{{
                        _.bindAll( this , "render" , "addOneRetweetItem" );
                        //指定当前 item 中的 slider 元素
                        this.model.bind( "change" , this.render );
                },//}}}
                render: function(){
                //{{{
                        //渲染基本的框架先
                        this.$el.html( Mustache.to_html( this.template , this.model.toJSON() ));
                        var sliderRetweetItems = new SliderRetweetItems;
                        sliderRetweetItems.bind( "add" , this.addOneRetweetItem );
                        //渲染的时候使用 model 中的 retweets 先渲染转发信息出列表
                        //再用这个列表渲染总的容器模板
                        //首先需要从 scheme_info 中获取首个转发信息
                        var prev_retweet_id = this.model.get( "scheme_info" )["retweet_info"]["prev_retweet_id"];
                        sliderRetweetItems.add( new SliderRetweetItemModel( this.model.toJSON()["scheme_info"]["retweet_info"] ) );
                        //之后再根据其中的 prev_retweet_id 判断是否有必要获取转发链中的上一条记录
                        if( prev_retweet_id !== null ){
                                var prevSliderRetweetItem = new SliderRetweetItemModel();
                                prevSliderRetweetItem.fetch(
                                        {
                                                data: {
                                                        retweet_id: prev_retweet_id
                                                },
                                                success: function(){
                                                        prevSliderRetweetItem.set( {has_top_border: true} );
                                                        sliderRetweetItems.add( prevSliderRetweetItem );
                                                },
                                                //@todo 获取转发信息时错误需要如何处理？
                                                error: function(){}
                                        });
                        }
                        return this;
                },//}}}
                addOneRetweetItem: function( sliderRetweetItem ){
                //{{{
                        //在已经渲染的 slider 中添加一条新的 retweet 信息
                        var sliderRetweetItemView = new SliderRetweetItemView( {model: sliderRetweetItem} );
                        this.$el.find( ".retweet_items" ).append( sliderRetweetItemView.render().el );
                }//}}}
        });
        return SliderRetweetsView;
});
