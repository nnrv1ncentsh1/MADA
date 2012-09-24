//
// Follow(用户关注关系) model
// @author <judasnow@gmail.com>
//
define( [
        "underscore",
        "backbone"
],

function( _ , Backbone ){
//{{{
        var FollowModel = Backbone.Model.extend( {
                defaults: {
                        //目标用户 id
                        user_id: 0,
                        //同当前登录用户之间的关系
                        has_follow: true
                },
                initialize: function(){
                //{{{
                        this.url = "/api/follow_api/info/user_id/" + this.get( "user_id" );
                }//}}}
        });
        return FollowModel;
});//}}}
