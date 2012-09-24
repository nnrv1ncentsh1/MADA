//
// 对应于 toolbar 中的 detail 按钮
// 存放相应的商品列表以及价格信息
//
define( [
        "underscore" ,
        "backbone"
] , 

function( _ , Backbone ){
        var SliderDetailModel = Backbone.Model.extend( {
                defaults: {
                        "scheme_id": "0",
                        "type": "detail",
                        "products": {},
                        "products_num": "0",
                        "sum_now_price": "0",
                        "discount": "10",
                        "original_price": "0",
                        "has_img": false
                },
                initialize: function(){
                //{{{
                        this.url = "api/scheme_api/slider_detail/scheme_id/" + this.get( "scheme_id" ) + "/format/json";
                },//}}}
        });
        return SliderDetailModel;
});

