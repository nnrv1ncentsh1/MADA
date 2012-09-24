//
// 对应于 toolbar 中的 retweet 按钮
// 显示详细的转发信息的 model 
// @author <judasnow@gmail.com>
//
define( [
"underscore" ,
"backbone"] , 

function( _ , Backbone ){
        var SliderRetweetsModel = Backbone.Model.extend( {
                defaults: {
                        type: "retweets"
                },
                initialize: function(){
                //{{{
                        //不返回任何信息
                        this.url = "api/scheme_api/slider_retweet/";
                }//}}}
        });
        return SliderRetweetsModel;
});



