//
// SchemeStreamApp (schemeStream入口) View
// @author <judasnow@gmail.com>
//
define( [
        "jquery",
        "underscore",
        "backbone",
        "mustache",
        "app/SchemeStream/m/Scheme",
        "app/SchemeStream/c/Schemes",
        "app/SchemeStream/v/SchemeItem",
        "app/SchemeStream/v/AddNewSchemeDialog",
        "app/SchemeStream/m/DialogBase",
        "app/SchemeStream/m/GetMoreButton",
        "app/SchemeStream/v/GetMoreButton"
],

function( 
        $,
        _,
        Backbone,
        Mustache,
        Scheme,
        Schemes,
        SchemeItemView,
        AddNewSchemeDialogView,
        DialogBaseModel,
        GetMoreButton,
        GetMoreButtonView
){
        //显示方案信息流的容器
        var streamEl = $( "#stream" );
        //获取当前登录用户的 user_id
        var subjectUserId = streamEl.find( ".subject_user_id" ).html();
        var moreSchemeItemEl = $( "#more_scheme_item" );
        //实例化一个 Scheme Collection
        //包含了本页面上显示的所有 Scheme
        window.schemes = new Schemes;
        var moreButtonNormalText = "更多方案";
        var moreButtonLoadingText = "读取中...";
        var getMoreButton = new GetMoreButton( {page:1, text: moreButtonNormalText} );
        var getMoreButtonView = new GetMoreButtonView( {model: getMoreButton} );
        //定义 SchemeStream 视图，顶级 app view
        SchemeStreamAppView = Backbone.View.extend( {
                //注意 scheme_list > stream
                el: $( "#scheme_list" ),
                initialize: function(){
                //{{{
                        _.bindAll( this , "addOne" , "addAll" , "getMore" , "fetchError" );
                        //从服务器加载初始数据并渲染之
                        this.initLoadInfoEl = streamEl.find( ".initialize_load_info" ).html( "方案列表加载中..." );
                        schemes.bind( "add", this.addOne );
                        schemes.bind( "reset", this.addAll );
                        schemes.insertFirst = $.proxy( this.insertFirst , this );
                        getMoreButtonView.$el.bind( "click" , this.getMore );
                        schemes.fetch(
                                {
                                        data: {
                                                subject_user_id: subjectUserId
                                        }, 
                                        success: function(){
                                                getMoreButtonView.render();
                                        },
                                        error: $.proxy( function(){
                                                this.initLoadInfoEl.html( "初始化方案时发生异常，请稍候再试." );
                                        } , this )
                                });
                        //使能添加新方案
                        var addNewSchemeDialogView = new AddNewSchemeDialogView( {model: new DialogBaseModel( {title: "添加新方案"} )} ); 
                },//}}}
                //在最上方添加新提交成功的方案
                insertFirst: function( scheme ){
                //{{{
                        streamEl.find( ".bar:first" ).show();
                        this.isNeedShowBar( scheme );
                        var schemeItemView = new SchemeItemView( {model: scheme} );
                        streamEl.prepend( schemeItemView.render().el );
                        streamEl.find( ".bar:first" ).hide();
                },//}}}
                addOne: function( scheme ){
                //{{{
                        this.isNeedShowBar( scheme );
                        var schemeItemView = new SchemeItemView( {model: scheme} );
                        streamEl.append( schemeItemView.render().el );
                },//}}}
                addAll: function(){
                //{{{
                        //从后台获取默认数据成功，准备渲染页面
                        this.initLoadInfoEl.html( "" );
                        schemes.each( this.addOne );
                        streamEl.find( ".bar:first" ).hide();
                },//}}}
                //获取新方案信息失败时的处理
                fetchError: function(){
                //{{{
                        getMoreButtonView.changeButtonStatus();
                        $( getMoreButtonView.el ).unbind( "click" );
                        return;
                },//}}}
                //获取指定页码的方案信息
                getMore: function(){
                //{{{
                        //改变按钮的状态
                        getMoreButton.set( {text: moreButtonLoadingText} );
                        //disable 绑定在按钮上的事件 防止用户重复点击
                        getMoreButtonView.$el.unbind( "click" );
                        //设置一个超时定时器
                        var timerId = setTimeout( this.fetchError , 5000 );
                        schemes.fetch(
                                        {
                                                data: {
                                                        //因为页面开始渲染的时候就已经是第 1 页了，so 这里因该先加在用
                                                        "page": getMoreButton.plusPage(),
                                                        "subject_user_id": subjectUserId
                                                },
                                                success: $.proxy( function(){
                                                        getMoreButton.set( {text: "更多方案"} );
                                                        getMoreButtonView.$el.bind( "click" , this.getMore );
                                                } , this ),
                                                error: $.proxy( function( coll , res ){
                                                        //根据 res code 进行判断
                                                        //404 表示已经没有后续内容了
                                                        //这时需要将 more 按钮 disable
                                                        if( res.status === 404 ){
                                                                getMoreButton.set( {disable: true, text: "没有了"} );
                                                                $( getMoreButtonView.el ).unbind( "click" );
                                                                clearTimeout( timerId );
                                                        }
                                                        //若为 500 则表示后台 API 返回信息有误
                                                        //@todo 暂时其他情况也视为 500
                                                        if( res.status === 500 || true ){
                                                                this.fetchError;
                                                        }
                                                } , this )
                                        });
                },//}}}
                //判断当前方案是否有显示 bar 的必要性
                //@param scheme model
                isNeedShowBar: function( scheme ){
                //{{{
                        //scheme.set( "showBar" , false );
                        return;
                }//}}}
        });
        return new SchemeStreamAppView;
});
