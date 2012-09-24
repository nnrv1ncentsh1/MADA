//
// 定义 Scheme stream item slider retweet (即类型为 retweet 的方案下拉菜单中的单个 retweet ) 视图
// @author<judasnow@gmail.com>
//
define( [
        "jquery",
        "underscore",
        "backbone",
        "mustache",
        "app/SchemeStream/m/SliderRetweetItem",
        "app/SchemeStream/m/DialogBase",
        "app/SchemeStream/v/RetweetDialog"
],
function( 
        $,
        _,
        Backbone,
        Mustache,
        SliderRetweetItemModel,
        DialogBaseModel,
        RetweetDialogView
){
        var SliderRetweetItemView = Backbone.View.extend( {
                events: {
                        "mouseover": "showOpt",
                        "mouseout": "hideOpt",
                        "click .opt .retweet": "showRetweetDialog",
                        "click .opt .favor": "toggleFavor",
                        "click .opt .delect": "doDelect"
                },
                template: $( "#scheme_item_slider_retweet_item_tpl" ).html(),
                render: function(){
                        this.$el.html( Mustache.to_html( this.template , this.model.toJSON() ) );
                        return this;
                },
                showOpt: function(){
                        this.$el.find( ".opt" ).show();
                },
                hideOpt: function(){
                        this.$el.find( ".opt" ).hide();
                },
                showRetweetDialog: function(){
                        var retweetDialogView = new RetweetDialogView( {
                                id: "from_another_retweet_retweet_dialog",
                                model: new DialogBaseModel( {
                                        title: "转发方案",
                                        fromInfo: this.model.toJSON()
                                })
                        });
                },
                toggleFavor: function(){
                },
                doDelect: function(){
                }
        });
        return SliderRetweetItemView;
});
