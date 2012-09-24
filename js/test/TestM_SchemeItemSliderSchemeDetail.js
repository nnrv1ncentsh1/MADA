define( [
"app/SchemeStream/m/SchemeItemSliderSchemeDetail"
],

function( SchemeItemSliderSchemeDetailModel ){
        module( "SchemeItemSliderSchemeDetail 模型测试" );
        test( "测试默认值" , function(){
                expect( 1 );
                var schemeDetail = new SchemeItemSliderSchemeDetailModel();
                equal( schemeDetail.get( "type" ) , "scheme_detail" , "断言 type 为scheme_detial" );
        });
        test( "测试新创建对象的时候，会根据传入的 scheme_id 设置正确的 url" , function(){
                expect( 1 );
                var schemeDetail = new SchemeItemSliderSchemeDetailModel( {"scheme_id": 1} );
                equal( schemeDetail.url , "api/scheme_api/slider_scheme_detail/scheme_id/1/format/json" , "url设置正确" );
        });
});
 
