//
// retweet dialog (转发方案的对话框) view
// 扩展自 dialogBaseView
//
// @note 注意,这个view不仅仅是针对于 scheme 同时也针对于其他 retweet.
// 因此 describe（也就是方案描述的获取途径就有两种 1.来自于 scheme 2.来自于直接相关的 retweet）
//
// @author <judasnow@gmail.com>
//
define( [
        "jquery",
        "underscore",
        "backbone",
        "mustache",
        "maxlength",
        "helper",
        "app/SchemeStream/v/DialogBase",
        "app/SchemeStream/m/Retweet",
        "bootstrap_tooltip",
        "jqueryui",
        "autogrow"
],
function(
        $,
        _,
        Backbone,
        Mustache,
        maxlength,
        helper,
        DialogBaseView,
        RetweetModel 
){
        //对话框中原始信息的长度
        var MAX_DESCRIBE_LENGTH_ON_RETWEET_DIALOG = 65;
        var RetweetDialogView = DialogBaseView.extend( {
                formatFromInfo: function( fromInfoRaw ){
                //{{{
                        //获取本次转发自何处,目前有两种情况
                        //1. from a scheme 
                        //2. from another retweet
                        //判断的依据就是 如果是 2 则传递过来的 json 中
                        //会存在一条 retweet_id 属性
                        if( typeof fromInfoRaw.retweet_id === "undefined" ){
                                //第 1 种情况
                                fromInfoRaw.userInfo = fromInfoRaw.holder;
                                fromInfoRaw.text = fromInfoRaw.describe;
                        }else{
                                //第 2 种情况
                                fromInfoRaw.userInfo = fromInfoRaw.retweeter;
                                fromInfoRaw.text = fromInfoRaw.content;
                        }
                        if( fromInfoRaw.text.length >= MAX_DESCRIBE_LENGTH_ON_RETWEET_DIALOG ){
                                fromInfoRaw.describe = helper.chineseSubStr( fromInfoRaw.text , 0 , 130 ) + " ······";
                        }
                        return fromInfoRaw;
                },//}}}
                render: function(){
                //{{{
                        var fromInfoRaw = this.model.get( "fromInfo" );
                        this.model.set( "fromInfo" , this.formatFromInfo( fromInfoRaw ) );
                        var retweetDialogTpl = Mustache.to_html( $( "#retweet_dialog_tpl" ).html() , this.model.toJSON() );
                        this.model.set( "dialog_main" , retweetDialogTpl );
                        this.model.set( "img_with" , "retweet" );
                        this.$el.html( Mustache.to_html( this.template , this.model.toJSON() ) ).show();
                        //设置对话框底部的最小高度为 30px 
                        this.$el.find( ".dialog_footer" ).attr( "style" , "min-height:30px;" );
                        this.enableDrag();
                        //使能输入框长度自动增长
                        this.$el.find( "#retweet_input textarea" ).autogrow();
                        //使能字数限制
                        maxlength( $( "input[data-maxsize] , textarea[data-maxsize]" ) );
                        return this;
                },//}}}
                initialize: function(){
                //{{{
                        this.baseInitialize();
                        //合并 DialogBase 中的事件
                        this.events = $.extend( {
                                "click .do_retweet": "doCreateRetweet"
                        } , this.baseEvents );
                        this.bind( "change" , this.render );
                        this.render();
                },//}}}
                validate: function( attrs ){
                //{{{
                        
                },//}}}
                doCreateRetweet: function(){
                //{{{
                        var retweetContentInputEl = this.dialogEl.find( ".retweet" );
                        var imgNameEl = this.dialogEl.find( "input[name='img_name']" );
                        //防止 undefined 被自动转换为 0
                        if( typeof this.dialogEl.find( "input[name='img_name']" ).val() === "undefined" ){
                                imgName = null;
                        }else{
                                imgName = imgNameEl.val();
                        }
                        var prevRetweetIdEl = this.dialogEl.find();
                        //创建新的转发对象
                        var newRetweetModel = new RetweetModel(
                                        {
                                                from_info: this.model.toJSON()["fromInfo"],
                                                content: retweetContentInputEl.val(),
                                                img_name: imgName
                                        }
                        );
                        newRetweetModel.save( null , {
                                success: $.proxy( function(){
                                        //@todo 即时显示( home 页面 (即用户个人主页) ) , 只提示信息 ( 非 home 页面 ) 同时需要 更新转发计数
                                        this.closeDialog();
                                        window.sysResInfoModel.set( {message: "转发成功"} );
                                } , this ) ,
                                error: function(){
                                        window.sysResInfoModel.set( "message" , "操作失败，请稍候再试." );
                                }
                        });
                },//}}}
        });
        return RetweetDialogView;
})//}}};

