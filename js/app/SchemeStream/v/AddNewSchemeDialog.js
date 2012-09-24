//
// addNewSchemeDialog (添加新方案的对话框) view
// 扩展自 dialogBaseView
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
"app/SchemeStream/m/Scheme",
"app/SchemeStream/v/SchemeItem",
"md5",
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
        DialogBaseView,
        Scheme,
        SchemeItemView
){
        var AddNewSchemeDialogView = DialogBaseView.extend( {
                el: $( "#add_new_scheme_dialog" ),
                render: function(){
                //{{{
                        //使用 addNewSchemeDialogTpl 提供的 dialog_main 元素渲染基本的对话框
                        var addNewSchemeDialogTpl = $( "#add_new_scheme_dialog_tpl" ).html();
                        this.model.set( "dialog_main" , addNewSchemeDialogTpl );
                        this.model.set( "img_with" , "scheme" );
                        this.dialogEl.html( Mustache.to_html( this.template , this.model.toJSON() ) );
                },//}}}
                initialize: function(){
                //{{{
                        this.baseInitialize();
                        //合并 DialogBase 中的事件
                        this.events = $.extend( {
                                //添加一个新的商品栏
                                "click #add_new_product_link": "tryAddNewProductItem",
                                //使能提交方案信息
                                "click #submit_solution_button": "doCreateScheme"
                        }, this.baseEvents);
                        _.bindAll( this , "checkSumNowPrice" , "checkOriPrice" , "countingDiscount" , "doCreateScheme" );
                        this.model.set( "step" , 1 );
                        this.render();
                        this.bind( "change" , this.render );
                        //使能 header 中的按钮可以打开对话框
                        $( "#open_add_new_scheme_dialog" ).click( this.openDialog );
                        //使能拖动
                        this.enableDrag();
                        //使能输入框长度自动增长
                        this.dialogEl.find( "#scheme_title" ).autogrow();
                        this.dialogEl.find( "#scheme_describe" ).autogrow();
                        //使能字数限制
                        maxlength( $( "input[data-maxsize], textarea[data-maxsize]" ) );
                },//}}}
                //检查用户是否已经正确输入了方案标题 以及
                //方案描述等信息 若还没有正确的输入 则高亮之
                //必填项需要在 class 属性之中使用 notnull 标记之
                canAddNewProductItem: function(){
                //{{{
                        var canAdd = true;
                        $( ".notnull" ).each( function( i , el ){
                                (function(){
                                        if( this.val() === "" ){
                                                //需要同时传入输入框元素以及输入信息元素
                                                helper.hightLight( [this , this.next() , this.parent()] );
                                                canAdd = false;
                                                return false;
                                        }
                                }).call( $( el ) );
                        });
                        return canAdd;
                },//}}}
                //获取当前编辑状态
                //@todo 可放到本地保存之 而不是继续使用 DOM 来进行参数的传递
                //该值应该算作 model 的一部分
                getStep: function(){
                //{{{
                        return this.model.get( "step" );
                },//}}}
                //将编辑状态转换到step1
                changeStepTo1: function(){
                //{{{
                        this.model.set( "step" , 1 );
                        this.hideAllTip();
                        //隐藏所有step2元素
                        $( ".step2" ).hide();
                        //显示所有step1元素
                        $( ".step1" ).show();
                },//}}}
                //渲染方案精简信息
                renderSchemeSummaryInfo: function(){
                //{{{
                        var schemeInfoSummaryPlaceholder = $( "#scheme_info_summary" );
                        var schemeTitle = $( "#scheme_title" ).val();
                        var schemeDescribe = $( "#scheme_describe" ).val();
                        var schemeInfoSummaryTpl = $( "#scheme_info_summary_tpl" ).html();
                        schemeInfoSummaryPlaceholder.html( Mustache.to_html( schemeInfoSummaryTpl , {"title": schemeTitle, "describe": schemeDescribe} ) );
                        schemeInfoSummaryPlaceholder.click( $.proxy( this.changeStepTo1 , this ) );
                },//}}}
                //将编辑状态转换到step2
                changeStepTo2: function(){
                //{{{
                        //需要保证当前处于step1
                        if( this.getStep() !== 1 ){
                                return false;
                        }
                        //隐藏方案信息输入框
                        this.renderSchemeSummaryInfo();
                        $( ".step1" ).hide();
                        $( ".step2" ).show();
                        this.model.set( "step" , 2 );
                        //修改现价的时候会触发刷新折扣的行为
                        $( "input[name='sum_now_price']" ).change( this.countingDiscount );
                        this.showNeedsTip();
                        return true;
                },//}}}
                //添加一条新的商品item
                //并绑定相应的事件到新产生的 DOM 元素上
                addNewProductItem: function(){
                //{{{
                        //如果服务现价输入框需要显示错误信息，则需要先隐藏之
                        //待添加完成后再重新显示
                        this.hideAllTip();
                        //计算出当前编号 方法是获取前一个编号加一(如果没有前一个编号 则从一开始)
                        var product_no = parseInt( this.dialogEl.find( ".product_no" ).last().html() );
                        if( isNaN( product_no ) ){
                                product_no = 1;
                        }else{
                                product_no = parseInt( product_no ) + 1;
                        }
                        var productItemsEl = $( "#product_items" );
                        //商品信息模板
                        var productItemTpl = $( "#product_item_tpl" ).html();
                        //商品信息容器
                        var productContainerEl = $( "#product_container" );
                        productItemsEl.append( Mustache.to_html( productItemTpl , { "product_no": product_no } ) );
                        //在用户修改任何一个原价的时候都会触发刷新折扣的操作
                        productItemsEl.find( ".product_original_price" ).unbind( "change" ).change( this.countingDiscount );
                        //使能添加描述按钮
                        productItemsEl.find( ".add_product_describe_link" ).unbind( "click" ).click( function(){ $( this ).next().toggle(); });
                        this.showNeedsTip();
                },//}}}
                //尝试添加一条新的商品item
                tryAddNewProductItem: function(){
                //{{{
                        //检查用户的输入是否合法(方案标题以及方案描述)
                        if( this.canAddNewProductItem() === false ){
                                return false;
                        }
                        //占位元素
                        //对话框页脚提交按钮也要同时显示出来
                        $( "#add_new_scheme_submit" );
                        //当前处于step2(或者当前还没有添加任何商品)才添加新的item
                        if( this.getStep() !== 2 ){
                                if( this.dialogEl.find( ".product_item" ).html() === null ){
                                        this.addNewProductItem();
                                }
                                this.changeStepTo2();
                                return;
                        }
                        this.addNewProductItem();
                },//}}}
                //判断用户输入的服务现价是否合法(左下角的"现价"框)
                checkSumNowPrice: function(){
                //{{{
                        var sumNowPriceInputEl = this.dialogEl.find( "input[name='sum_now_price']" ).tooltip( {"title": "请输入数字", "trigger": "manual"} );
                        if( isNaN( sumNowPriceInputEl.val() ) ){
                                this.showTip( sumNowPriceInputEl );
                                return false;
                        }else{
                                if( sumNowPriceInputEl.tooltip != null ){
                                        this.hideTip( sumNowPriceInputEl );
                                }
                        }
                        return true;
                },//}}}
                //判断用户输入的原价是否合法
                checkOriPrice: function(){
                //{{{
                        //假设目前所有用户输入的原价都是有效的
                        var allAvailable = true;
                        this.dialogEl.find( ".product_original_price" ).each( $.proxy( function( i , el ){
                                var thisOriPriceInputEl = $( el );
                                //使能 tooltip
                                thisOriPriceInputEl.tooltip( {"title": "请输入数字" , "trigger": "manual"} );
                                if( isNaN( thisOriPriceInputEl.val() ) ){
                                        this.showTip( thisOriPriceInputEl );
                                        allAvailable = false;
                                        return true;
                                }else{
                                        if( thisOriPriceInputEl.tooltip != null ){
                                                this.hideTip( thisOriPriceInputEl );
                                        }
                                }
                        } , this ));
                        return allAvailable;
                },//}}}
                //计算并更新当前的原价
                countingSumPrice: function(){
                //{{{
                        var sumOriPrice = 0;
                        var sumOriPriceEl = this.dialogEl.find( "#sum_ori_price .figure" );
                        this.dialogEl.find( ".product_original_price" ).each( function( i , el ){
                                var thisOriPriceInputEl = $( el );
                                var thisOriPrice = thisOriPriceInputEl.val();
                                sumOriPrice = helper.FloatAdd( parseFloat( sumOriPrice ) , parseFloat( thisOriPrice ) );
                        });
                        sumOriPriceEl.text( isNaN( sumOriPrice ) ? "0" : sumOriPrice );
                },//}}}
                //计算折扣信息
                countingDiscount: function(){
                //{{{
                        //为了显示全部不合法的情况
                        //两个判断函数需要完全执行
                        var SumNowPriceIsOk = this.checkSumNowPrice();
                        var OriPriceIsAllOK = this.checkOriPrice();
                        this.countingSumPrice();
                        var sumNowPriceInputEl = this.dialogEl.find( "input[name='sum_now_price']" );
                        var sumOriPriceEl = this.dialogEl.find( "#sum_ori_price .figure" );
                        var discountEl = this.dialogEl.find( "#discount .figure" );
                        //总价为空也就没有计算的必要性了
                        if( !isNaN( sumOriPriceEl.val() ) && !isNaN( sumNowPriceInputEl.val() ) && sumNowPriceInputEl.val() != "" ){
                                var sumOriPriceFloat = parseFloat( sumOriPriceEl.text() );
                                var sumNowPriceFloat = parseFloat( sumNowPriceInputEl.val() );
                                if( sumOriPriceFloat != 0 && sumNowPriceFloat != 0 ){
                                        discountFormated = discountRaw = ( sumNowPriceFloat / sumOriPriceFloat );
                                        if( discountRaw >= 1 ){
                                                //大于有效值
                                                discountFormated = '10+';
                                        }else if( discountRaw < 0.01 ){
                                                //小于有效值
                                                discountFormated = '0.0';
                                        }else{
                                                //正常情况
                                                discountFormated = discountRaw * 10 + "" ;
                                                discountFormated = discountFormated.substring( 0 , 3 );
                                                //如果没有小数部分且只有一位，添加小数部分0
                                                if( discountFormated.lastIndexOf( '.' ) == -1 && discountFormated.length == 1 ){
                                                        discountFormated = discountFormated + '.0';
                                                }
                                        }
                                }else{
                                        discountFormated = 0;
                                }
                        }else{
                                discountFormated = "-";
                        }
                        discountEl.text( discountFormated );
                },//}}}
                //判断即将提交的 Scheme 各属性值是否合法
                validate: function(){
                //{{{
                        var allValidate = true;
                        //验证商品信息
                        $( ".product_item" ).each( function( i , el ){
                                var thisItemEl = $( el );
                                var thisTitleEl = thisItemEl.find( ".product_title" );
                                var thisDescEl = thisItemEl.find( ".product_describe" );
                                var thisOriPriceEl = thisItemEl.find( ".product_original_price" );
                                if( thisTitleEl.val() == "" ){
                                        helper.hightLight( [thisTitleEl] );
                                        allValidate = false;
                                        return false;
                                }
                                //为空或者提示错误
                                if( thisOriPriceEl.val() == "" || thisOriPriceEl.hasClass( "inTip" ) || thisOriPriceEl.hasClass( "need_show_tip" ) ){
                                        helper.hightLight( [thisOriPriceEl] );
                                        allValidate = false;
                                        return false;
                                }
                                if( thisDescEl.is( ":visible" ) && thisDescEl.val() == "" ){
                                        helper.hightLight( [ thisDescEl.parent() , thisDescEl ] );
                                        allValidate = false;
                                        return false;
                                }
                        });
                        //判断现价是否合法
                        if( allValidate === true ){
                                var sumNowPriceEl = this.dialogEl.find( "input[name='sum_now_price']" );
                                if( sumNowPriceEl.val() === "" || sumNowPriceEl.hasClass( "inTip" ) || sumNowPriceEl.hasClass( "need_show_tip" ) ){
                                        helper.hightLight( sumNowPriceEl );
                                        allValidate = false;
                                }
                        }
                        return allValidate;
                },//}}}
                getHash: function( newScheme ){
                //{{{
                        return hex_md5(
                                newScheme.get( "title" ) + 
                                newScheme.get( "describe" ) + 
                                newScheme.get( "sumOriPrice" ) + 
                                newScheme.get( "sumNowPrice" )
                        );
                },//}}}
                //提交方案信息
                doCreateScheme: function(){
                //{{{
                        if( !this.validate() ){
                                return false;
                        }
                        //创建一个新的 SchemeMode
                        //@todo 此处重复 须重构!!!
                        var sumNowPriceInputEl = this.dialogEl.find( "input[name='sum_now_price']" );
                        var sumOriPriceEl = this.dialogEl.find( "#sum_ori_price .figure" );
                        var discountEl = this.dialogEl.find( "#discount .figure" );
                        var imgName = this.dialogEl.find( "input[name='img_name']" );
                        //获取商品信息数组
                        var products = (function( productItemEls ){
                                var products = {};
                                productItemEls.each( function( index , itemEl ){
                                        products[index] = {};
                                        products[index].title = $( itemEl ).find( ".product_title" ).val();
                                        products[index].original_price = $( itemEl ).find( ".product_original_price" ).val();
                                        products[index].describe = $( itemEl ).find( ".product_describe" ).val();
                                });
                                return products;
                        })( this.dialogEl.find( ".product_item" ) );
                        var newScheme = new Scheme( {
                                title: $( "#scheme_title" ).val(),
                                describe: $( "#scheme_describe" ).val(),
                                sumNowPrice: sumNowPriceInputEl.val(),
                                sumOriPrice: sumOriPriceEl.html(),
                                discount: discountEl.html(),
                                products: products,
                                imgName: imgName.val()
                        });
                        //判断是否已经有相同的方案被发布，避免方案的重复
                        console.log(  this.model.get( "lastHash" ) + "==+==" + this.getHash( newScheme ) );
                        if( this.model.get( "lastHash" ) === this.getHash( newScheme ) ){
                                return false;
                        }
                        newScheme.save( null , {
                                //添加成功
                                success: $.proxy( function( newScheme , response ){
                                        //生成 hash 防止重复添加相同的方案
                                        this.model.set( "lastHash" , this.getHash( newScheme ) );
                                        newScheme.set( "scheme_id" , response.scheme_id );
                                        newScheme.set( "time" , response.time );
                                        newScheme.set( "holder" , response.holder );
                                        newScheme.set( "formated_time" , "刚刚" );
                                        newScheme.set( "sum_now_price" , newScheme.get( "sumNowPrice" ) );
                                        newScheme.set( "original_price" , newScheme.get( "sumOriPrice" ) );
                                        newScheme.set( "img_name" , newScheme.get( "imgName" ) );
                                        //屏蔽默认 add 函数
                                        schemes.add( newScheme , {silent: true} );
                                        //从上方插入最新方案
                                        schemes.insertFirst( newScheme );
                                        //关闭窗口
                                        this.closeDialog();
                                        //清空窗口中的内容
                                } , this ),
                                //添加失败
                                error: function(){
                                        //@todo 通过通用系统信息显示给用户
                                        sysResInfoModel.set( "message" , "添加方案失败，请稍后再试." );
                                }
                        });
                }//}}}
        });
        return AddNewSchemeDialogView;
})//}}};

