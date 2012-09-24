//
// dialog (对话框) view 基类
// 由于系统中需要的两个弹出窗口结构异常的相似
// 因此有必要将其抽象到一个基类中
// 基类提供的功能有:
// 1. open/close
// 2. draggable
// 3. tooltip
// 4. img upload
//
// @todo 但是在本类中只是定义了一些通用的属性以及操作
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
"bootstrap_tooltip",
"jqueryui",
"autogrow"],

function( 
        $,
        _,
        Backbone,
        Mustache,
        maxlength,
        helper,
        DialogBase
){
        var MAX_IMG_NUM = 1;
        var DialogBaseView = Backbone.View.extend( {
                el: $( "#dialog_base" ),
                //由所谓的派生类调用
                baseInitialize: function(){
                //{{{
                        if( typeof this.el === "undefined" ){
                                //需要设置 DOM 容器(一般情况为派生类指定的容器)
                                throw new Error( "dialogBaseView`s el is undefined." );
                        }
                        this.dialogEl = $( this.el );
                        _.bindAll( this , "keepDialogPosition"
                                , "render" , "openDialog" , "closeDialog" , "showTip" , "hideTip" , "hideAllTip" , "showNeedsTip" , "enableDrag" , "enableUploadPicture" , "doUploadPicture" );
                        //绑定事件顺便触发一下 初始化对话框的位置
                        $( window ).scroll( this.keepDialogPosition ).scroll();
                },//}}}
                template: $( "#dialog_base_tpl" ).html(),
                baseEvents: {
                        //关闭对话框
                        "click .dialog_header_close_button": "closeDialog",
                        //使能点击图标弹出上传图片文件选择窗口
                        "click #upload_picture": "enableUploadPicture",
                        //上传文件输入框值发生改变之后 会触发上传文件的动作
                        "change .upload_picture_input": "doUploadPicture"
                },
                openDialog: function(){
                //{{{
                        this.dialogEl.show();
                        this.showNeedsTip();
                },//}}}
                closeDialog: function(){
                //{{{
                        this.dialogEl.hide();
                        this.hideAllTip();
                },//}}}
                showTip: function( el ){
                //{{{
                        $( el ).tooltip( "show" ).addClass( "in_tip" );
                },//}}}
                hideTip: function( el ){
                //{{{
                        $( el ).tooltip( "hide" ).removeClass( "in_tip" );
                },//}}}
                //隐藏所有的 tip
                //通过有 tip class 的元素找到所有有可能存在 tip
                //的元素，隐藏之同时为其为其添加 need_show_tip class，用来在
                //回复 tip 的显示
                //@param zone 指定该函数的作用区域
                hideAllTip: function(){
                //{{{
                        this.dialogEl.find( ".in_tip" ).each( function( index , el ){
                                $( el ).addClass( "need_show_tip" ).removeClass( "in_tip" ).tooltip( "hide" );
                        });
                },//}}}
                showNeedsTip: function(){
                //{{{
                        this.dialogEl.find( ".need_show_tip" ).each( function( index , el ){
                                $( el ).removeClass( "need_show_tip" ).addClass( "in_tip" ).tooltip( "show" );
                        });
                },//}}}
                //@todo 需要传入两个回调 start stop
                enableDrag: function(){
                //{{{
                        this.dialogEl.draggable( {
                                handle: this.dialogEl.find( ".dialog_header" ),
                                cursor: "move",
                                start: this.hideAllTip,
                                stop: this.showNeedsTip
                        });
                },//}}}
                //使能点击 <i> 打开文件上传界面
                enableUploadPicture: function(){
                //{{{
                        //上传图片的按钮元素 <i>
                        this.uploadPictureButtonEl = this.dialogEl.find( "#upload_picture" );
                        if( this.dialogEl.find( ".imgs_item" ).length >= MAX_IMG_NUM ){
                                //通过图片栏中 图片的个数 来限制用户上传图片的个数
                                $( "#upload_picture" ).addClass( "disable" );
                                return;
                        }
                        this.uploadPictureButtonEl.removeClass( "disable" );
                        //上传文件的输入框
                        this.uploadPictureInputEl = this.dialogEl.find( ".upload_picture_input" );
                        //模拟点击上传文件按钮
                        //为了兼容 opera 要先 focus 之
                        if( $.browser.opera ){
                                uploadPictureInputEl.parent().show().hide();
                                uploadPictureInputEl.iocus();
                        }
                        this.uploadPictureInputEl.click();
                },//}}}
                doUploadPicture: function(){
                //{{{
                        if( $.isEmptyObject( this.uploadPictureInputEl ) ){
                                this.uploadPictureInputEl = this.dialogEl.find( ".upload_picture_input" );
                        }
                        if( this.uploadPictureInputEl.val() == "" ){
                                return;
                        }
                        //提交表单 提交结果将返回到指定的 iframe 中只要
                        //轮询检查其中的文本信息即可
                        $( "#upload_img_from" ).submit();
                        var ImgsEl = this.dialogEl.find( ".dialog_item_imgs" );
                        //图片模板文件
                        var imgTpl = $( "#dialog_small_img_tpl" ).html();
                        var uploadImgApcInfoInputEl = this.dialogEl.find( "input[name='APC_UPLOAD_PROGRESS']" );
                        var imgWithInputEl = this.dialogEl.find( "input[name='img_with']" );
                        //尝试获取上传的图片缩略图名称(上传成功之后会写入iframe中)
                        var intervalCount = 0;
                        var iNo = setInterval( $.proxy( function(){
                                if( intervalCount++ > 5 ){
                                        clearInterval( iNo );
                                        intervalCount = 0;
                                        throw new Error( "uploading img timeout." );
                                }
                                $.get(
                                        "/upload_img/ajax_do_get_upload_process/",
                                        {
                                                "APC_UPLOAD_PROGRESS": uploadImgApcInfoInputEl.val(),
                                                //确定是想关于 scheme 的还是 相关与 retweet 的
                                                "img_with": imgWithInputEl.val()
                                        } ,
                                        function( ajaxRes ){
                                                if( ajaxRes.res === false ){
                                                        sysResInfoModel.set( "message" , "上传图片失败，请稍候再试一次。" );
                                                        throw new Error( "uploading img error." );
                                                }
                                                if( ajaxRes.pre === 100 ){
                                                        clearInterval( iNo );
                                                        //图片上传成功之后 在方案详细框中进行显示
                                                        ImgsEl.animate( { "min-height": "42px" } , 100 );
                                                        ImgsEl.append( Mustache.to_html( imgTpl , 
                                                                        {
                                                                                "thumbnail_url": "/upload/" + ajaxRes.img_name + "_croped.jpg",
                                                                                "img_name": ajaxRes.img_name
                                                                        }));
                                                        ImgsEl.find( ".img_item:last" ).fadeIn();
                                                        //绑定删除图片的事件
                                                        ImgsEl.find( "i:last" ).click( function(){
                                                                var dialogEl = $( this );
                                                                dialogEl.parent().fadeOut().remove();
                                                                setTimeout( function(){
                                                                        //如果已经没有图片了 则回复其行高
                                                                        if( ImgsEl.find( ".img_item" ).length == 0 ){
                                                                                ImgsEl.animate( { "min-height": 0 } , 100 );
                                                                        }
                                                                } , 100 );
                                                        });
                                                }
                                        } , "json"
                                );
                        } , this ) , 1000 );
                },//}}}
                //使能对话框始终在屏幕的相同位置
                keepDialogPosition: function(){
                //{{{
                        this.dialogEl.stop( true ).animate( {"top": parseInt( 150 ) + parseInt( $(window).scrollTop() ) + "px"} , 200 );
                }//}}}
        });
        return DialogBaseView;
});


