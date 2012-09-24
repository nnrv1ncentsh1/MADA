// 
// Scheme(方案) Collection 显示在 home 页面上的方案流
// @author <judasnow@gmail.com>
//
define( [
"underscore",
"backbone",
"app/SchemeStream/m/Scheme"
] , function(
        _,
        Backbone,
        Scheme
){
        var Schemes = Backbone.Collection.extend( {
                model: Scheme,
                url: "api/schemes_api/home/"
        });
        return Schemes;
});
