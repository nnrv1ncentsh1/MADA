//对应于 toolbar 中的 time 按钮
//存放方案所属用户的 关注人数/粉数 信息
//以及该方案的过期时间
define( [
"underscore" ,
"backbone"] , 

function( _ , Backbone ){
//{{{
        var SliderTimeModel = Backbone.Model.extend( {
                defaults: {
                        "scheme_id": "0",
                        "type": "time",
                        "holder_nick": "不存在的用户",
                        "retwetts": "0",
                        "favorites": "0",
                        "post_time": "00:00 2012-1-1",
                        "expire_time": "00:00 2012-1-1"
                },
                initialize: function(){
                //{{{
                        this.url = "api/scheme_api/slider_time/scheme_id/" + this.get( "scheme_id" ) + "/format/json";
                },//}}}
        });
        return SliderTimeModel;
});//}}}
