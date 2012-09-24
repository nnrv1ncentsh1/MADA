define( [
"app/SchemeStream/m/SchemeItemSliderTime"
],

function( SchemeItemSliderTimeModel ){
        module( "SchemeItemSliderTime 模型测试" );
        test( "测试默认值" , function(){
                sliderTimeModel = new SchemeItemSliderTimeModel();
                equal( sliderTimeModel.get( "type" ) , "time" , "默认为time类型的slider" );
                equal( sliderTimeModel.get( "holder_nick" ) , "不存在的用户" );
                equal( sliderTimeModel.get( "scheme_id" ) , "0" , "方案id默认为0" );
        });
        test( "测试初始化之后url被设置为想要的值" , function(){
                sliderTimeModel = new SchemeItemSliderTimeModel( {"scheme_id": 1} );
                equal( sliderTimeModel.url , "api/scheme_api/slider_time/scheme_id/1/format/json" , "url" );
        });
});


