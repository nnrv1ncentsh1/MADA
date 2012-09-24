//对应于 toolbar 中的 tran 按钮
//存放相应的商品列表以及价格信息
define( [
"underscore" ,
"backbone"] , 

function( _ , Backbone ){
        var SliderTranModel = Backbone.Model.extend( {
                defaults: {
                        "scheme_id": "0",
                        "type": "tran",
                        "scheme": {},
                        "holder": {},
                        "trans_all_num": 0,
                        "trans_paid_num": 0
                },
                initialize: function(){
                        this.url = "api/scheme_api/slider_tran/scheme_id/" + this.get( "scheme_id" ) + "/format/json";
                }
        });
        return SliderTranModel;
});
