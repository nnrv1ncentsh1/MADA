// 
// Slider retweets (方案下拉中的转发信息) Collection
// @author <judasnow@gmail.com>
//
define( [
"underscore" ,
"backbone" ,
"app/SchemeStream/m/SliderRetweetItem"
] , function( 
        _,
        Backbone,
        SliderRetweetItem 
){
        var SliderRetweetItems = Backbone.Collection.extend( {
                model: SliderRetweetItem
        });
        return SliderRetweetItems;
});
