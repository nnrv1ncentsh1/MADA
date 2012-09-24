//
// 定义 Scheme more (即类型为 more 的方案下拉菜单中的关注按钮 ) 中用户关注方案操作的视图
// @author <judasnow@gmail.com>
//
define( [
        "jquery",
        "underscore",
        "backbone",
        "mustache",
        "app/SchemeStream/m/SliderMoreFavorButton",
],
function( 
        $,
        _,
        Backbone,
        Mustache,
        FavorButton
){
        var SliderMoreFavorButton = Backbone.View.extend( {
                model: FavorButton,
                template: $( "#scheme_item_slider_more_favor_buttons_tpl" ).html(),
                initialize: function(){
                        _.bindAll( this , "render" , "doFavor" , "undoFavor" );
                        this.model.bind( "change" , this.render );
                },
                events: {
                        "click .do_favor": "doFavor",
                        "click .undo_favor": "undoFavor"
                },
                render: function(){
                //{{{
                        this.$el.find( ".functions .favor_items" ).html( Mustache.to_html( this.template , this.model.toJSON() ) );
                },//}}}
                doFavor: function(){
                //{{{
                        this.model
                                .set( "has_favor" , true )
                                .save( {
                                        success: function(){

                                        },
                                        error: function(){
                                                alert( "favor opt fail." );
                                        }
                                });
                },//}}}
                undoFavor: function(){
                //{{{
                        this.model
                                .set( "has_favor" , false )
                                .save( {
                                        success: function(){
                                                alert();
                                        },
                                        error: function(){
                                                alert( "favor opt fail." );
                                        }
                                });
                }//}}}
        });
        return SliderMoreFavorButton;
});
