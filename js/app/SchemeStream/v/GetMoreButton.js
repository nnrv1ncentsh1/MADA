//
//get more (获取更多方案信息) 按钮
//@author <judasnow@gmail.com>
//
define( [
"jquery",
"underscore",
"backbone",
"mustache"],

function(
        $,
        _, 
        Backbone,
        Mustache
){
        var GetMoreButtonView = Backbone.View.extend( {
                el: $( "#more_scheme_item" ),
                template: $( "#get_more_scheme_item_tpl" ).html(),
                initialize: function(){
                        _.bindAll( this , "render" );
                        this.model.bind( "change" , this.render );
                },
                render: function(){
                        $( this.el ).html( Mustache.to_html( this.template, this.model.toJSON() ) );
                        return this;
                },
                //将当前 getMore 按钮状态替换为 error 状态，
                //或执行相反的操作
                changeButtonStatus: function(){
                        $( this.el ).find( ".get_more_scheme" ).toggle();
                        $( this.el ).find( ".get_more_scheme_error" ).toggle();
                }
        });
        return GetMoreButtonView;
});
