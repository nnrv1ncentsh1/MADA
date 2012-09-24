//
// 一些辅助函数
// @author <judasnow@gmail.com>
//
define( [
        "jquery",
        "jquery_color"
] , function( $ ){
        var helper = {
                //浮点数乘法
                floatmul: function( arg1 , arg2 ){
                        var m=0,s1=arg1.tostring(),s2=arg2.tostring(); 
                        try{m+=s1.split(".")[1].length}catch(e){} 
                        try{m+=s2.split(".")[1].length}catch(e){} 
                        return number(s1.replace(".",""))*number(s2.replace(".",""))/math.pow(10,m) 
                },
                //浮点数加法
                floatadd: function( arg1 , arg2 ){   
                        var r1 , r2 , m;
                        try{
                                r1=arg1.toString().split(".")[1].length;
                        }catch(e){
                                r1=0; 
                        }
                        try{
                                r2=arg2.toString().split(".")[1].length;
                        }catch(e){
                                r2=0
                        }
                        m = Math.pow(10,Math.max(r1,r2));
                        return ( parseInt( this.FloatMul( arg1 , m ) ) + parseInt( this.FloatMul( arg2 , m ) ) ) / m;
                },
                //计算用户剩余可输入字数
                //先变为数组
                //@param object el 需要操作的元素
                count_length: function( el ){
                        //var reg = new RegExp('[\u4E00-\u9FA5]');
                        var reg = new RegExp( '[^\x00-\xff]' );
                        var not_cc_count = 0;
                        //计算用户剩余可输入字数
                        //先变为数组
                        var user_input_string_array = el.val().split( '' );
                        var length = 0;
                        for ( var i in user_input_string_array ){
                                if( reg.test( user_input_string_array[i] ) ? true : false ){
                                        //为汉字加1
                                        length++;
                                }else{
                                        //不为汉字 计数器加1
                                        not_cc_count++;	 
                                        if( not_cc_count == 2 ){
                                                length++;
                                                not_cc_count = 0;
                                        }
                                }
                        }
                        if( not_cc_count == 1 ){
                                length++;
                        } 
                        return length;
                },
                substr: function( str , len ){     
                        if(!str || !len) { return ''; }      
                        var a = 0;      //循环计数     
                        var i = 0;      //临时字串     
                        var temp = '';      
                        for (i=0;i<str.length;i++){         
                                if (str.charcodeat(i)>255){                     
                                        a+=2;         
                                }else{            
                                        a++;         
                                }
                                //如果增加计数后长度大于限定长度，就直接返回临时字符串
                                if(a > len) { return temp; }  
                                temp += str.charat(i);     
                        }   
                        return str; 
                },
                //高亮指定元素
                //@param array
                hightLight: function( els ){
                        $.each( els , function( i , el ){
                                var thisEl =  $( el );
                                thisEl.stop(true)
                                        .animate( { backgroundColor: "#fee" }, 100 )
                                        .animate( { backgroundColor: "#fcc" }, 100 )
                                        .animate( { backgroundColor: "#fff" }, 100 )
                                        .animate( { backgroundColor: "#fee" }, 100 )
                                        .animate( { backgroundColor: "#fcc" }, 100 )
                                        .animate( { backgroundColor: "#fff" }, 100 );
                        });
                },
                //中文取子串
                chineseSubStr: (function( str , begin , num ) {
                        var ascRegexp = /[^\x00-\xFF]/g, i = 0;
                        while(i < begin) (i ++ && str.charAt(i).match(ascRegexp) && begin --);
                        i = begin;
                        var end = begin + num;
                        while(i < end) (i ++ && str.charAt(i).match(ascRegexp) && end --);
                        return str.substring(begin, end);
                })
        }
        return helper;
});
