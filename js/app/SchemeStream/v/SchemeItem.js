//
// Scheme stream item 视图
// @author <judasnow@gmail.com>
//
define( [
        "jquery",
        "underscore",
        "backbone",
        "mustache",
        //下拉菜单相应项的 view 和相应的 model
        "app/SchemeStream/v/SliderTime",
        "app/SchemeStream/m/SliderTime",
        "app/SchemeStream/v/SliderRetweets",
        "app/SchemeStream/m/SliderRetweets",
        "app/SchemeStream/v/SliderDetail",
        "app/SchemeStream/m/SliderDetail",
        "app/SchemeStream/v/SliderTran",
        "app/SchemeStream/m/SliderTran",
        "app/SchemeStream/v/SliderMore",
        "app/SchemeStream/m/SliderMore"
],
function(
        $,
        _,
        Backbone,
        Mustache,
        SliderTimeView,
        SliderTimeModel,
        SliderRetweetsView,
        SliderRetweetsModel ,
        SliderDetailView,
        SliderDetailModel,
        SliderTranView,
        SliderTranModel,
        SliderMoreView,
        SliderMoreModel
){
        var SchemeItemView = Backbone.View.extend( {
                ClassName: "scheme_item",
                template: $( "#scheme_item_tpl" ).html(),
                //给相应的元素绑定事件，但是需要注意的是
                //toolbar 中的按钮和slider中用于显示信息的类
                //是同名的，thus，可以简单的实现点击按钮以及点击
                //slider中的显示区域关闭当前slider的功能
                events: {
                        "click .scheme_toolbar .time": "showTime",
                        "click .scheme_toolbar .retweets": "showRetweets",
                        "click .scheme_toolbar .detail": "showDetail",
                        "click .scheme_toolbar .tran": "showTran",
                        "click .scheme_toolbar .more": "showMore"
                },
                initialize: function(){
                        _.bindAll( this , "_doToggleSlider" , "render" );
                },
                render: function(){
                        this.$el.html( Mustache.to_html( this.template , this.model.toJSON() ) );
                        return this;
                },
                showTime: function(){
                        this.toggleSlider( "time" );
                },
                showRetweets: function(){
                        this.toggleSlider( "retweets" );
                },
                showDetail: function(){
                        this.toggleSlider( "detail" );
                },
                showTran: function(){
                        this.toggleSlider( "tran" );
                },
                showMore: function(){
                        this.toggleSlider( "more" );
                },
                _doToggleSlider: function( arg ){
                //{{{
                        //暂时 disable button 上的 click 事件
                        var buttonEl = this.$el.find( ".scheme_toolbar ." + arg.type );
                        this.$el.undelegate( ".scheme_toolbar ." + arg.type , "click" );
                        var thisSchemeId = this.model.get( "scheme_id" );
                        var thisSliderEl = $( this.el ).find( ".slider" );
                        var model = eval( "new " + arg.modelName + "( {scheme_id: thisSchemeId} )" );
                        //保存方案本身的信息
                        model.set( "scheme_info" , this.model.toJSON() );
                        //告诉用户正在 loading (从数据库抓取数据)
                        var oriText = buttonEl.html();
                        buttonEl.html( "Loading..." );
                        model.fetch(
                                {
                                        success: $.proxy( function(){
                                                var sliderView = eval( "new " + arg.viewName + "( {el: thisSliderEl , model: model})" );
                                                sliderView.render();
                                                //@todo 此处稍有重复也无所谓(只有两处)
                                                buttonEl.html( oriText );
                                                this.$el.delegate(
                                                        ".scheme_toolbar ." + arg.type , 
                                                        "click" , 
                                                        $.proxy( this["show" + this._changeStringFirstWordIntoUppercase( arg.type )] , this )
                                                );
                                        } , this ),
                                        error: $.proxy(function(){
                                                window.sysResInfoModel.set( "message" , "获取信息失败." );
                                                buttonEl.html( oriText );
                                                this.$el.delegate(
                                                        ".scheme_toolbar ." + arg.type , 
                                                        "click" , 
                                                        $.proxy( this["show" + this._changeStringFirstWordIntoUppercase( arg.type )] , this )
                                                );
                                        } , this )
                                });
                },//}}}
                _changeStringFirstWordIntoUppercase: function( string ){
                        return string[0].toUpperCase() + string.slice( 1 );
                },
                //执行 显示/隐藏 下拉框操作
                //其实就是根据相应的 type 建立不同的 slider model
                //@param type 下拉框显示的种类
                toggleSlider: function( type ){
                //{{{
                        //判断是否为当前类型
                        if( $( this.el ).find( ".type" ).html() === type ){
                                //如果当前类型的 slider 已经显示
                                //则本次点击会被视为关闭操作
                                $( this.el ).find( ".slider" ).html( "" );
                                return;
                        }
                        this._doToggleSlider( {
                                "type": type,
                                "modelName": "Slider" + this._changeStringFirstWordIntoUppercase( type ) + "Model",
                                "viewName": "Slider" + this._changeStringFirstWordIntoUppercase( type ) + "View"
                        });
                }//}}}
        });
        return SchemeItemView;
});
