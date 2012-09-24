//
// 定义 Scheme more (即类型为 more 的方案下拉菜单中的关注按钮 ) 视图
// @author<judasnow@gmail.com>
//
define( [
        "jquery",
        "underscore",
        "backbone",
        "mustache",
        "app/SchemeStream/m/SliderMoreFollowButton",
],
function( 
        $,
        _,
        Backbone,
        Mustache,
        FollowButton
){
        var SliderRetweetItemView = Backbone.View.extend( {
                model: FollowButton,
                template: $( "#scheme_item_slider_more_follow_buttons_tpl" ).html(),
                initialize: function(){
                        _.bindAll( this , "render" , "doFollow" , "undoFollow" );
                        this.model.bind( "change" , this.render );
                },
                events: {
                        "click .do_follow": "doFollow",
                        "click .undo_follow": "undoFollow"
                },
                render: function(){
                //{{{
                        this.$el.find( ".follow_buttons" ).html( Mustache.to_html( this.template , this.model.toJSON() ) );
                },//}}}
                doFollow: function(){
                //{{{
                        this.model
                                .set( "has_follow" , true )
                                .save( {
                                        success: function(){

                                        },
                                        error: function(){
                                                alert( "follow opt fail." );
                                        }
                                });
                },//}}}
                undoFollow: function(){
                //{{{
                        this.model
                                .set( "has_follow" , false )
                                .save( {
                                        success: function(){

                                        },
                                        error: function(){
                                                alert( "follow opt fail." );
                                        }
                                });
                }//}}}
        });
        return SliderRetweetItemView;
});
