//
// Retweet(转发信息) model
// @author <judasnow@gmail.com>
//
define( [
"underscore",
"backbone"],

function( _ , Backbone ){
//{{{
        var RetweetModel = Backbone.Model.extend( {
                initialize: function(){
                //{{{
                        this.url = "/api/retweet_api/info/";
                }//}}}
        });
        return RetweetModel;
});//}}}
