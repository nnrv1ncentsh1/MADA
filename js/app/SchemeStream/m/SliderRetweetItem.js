//
// 单条转发信息的 model
// @author <judasnow@gmail.com>
//
define( [
        "underscore",
        "backbone"
],
function(
        _,
        Backbone
){
        var SliderRetweetItemModel = Backbone.Model.extend( {
                defaults: {
                        content: "null",
                        formated_time: "00.00",
                        hightlight: "0",
                        img_name: null,
                        prev_retweet_id: null,
                        prev_retweet_user: {},
                        retweet_id: "0",
                        retweeter_id: "0",
                        scheme_id: "0",
                        has_top_border: false
                },
                url: "/api/retweet_api/info/"
        });
        return SliderRetweetItemModel;
});
