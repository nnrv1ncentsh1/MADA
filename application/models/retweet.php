<?php
//
// Retweet(转发) model 
// @author <judasnow@gmail.com>
//
require_once 'base_model.php';

class Retweet extends Base_model{

        private $_CI;
        public function __construct(){
        //{{{
                parent::__construct( 'retweet' );
                $this->_CI =& get_instance();
        }//}}}

        //
        //按照需求获取转发以及“转发自”用户的信息
        //特别需要注意的就是 获取的转发自 信息来自于 prev_retweet_id 指向的转发记录
        //这时 有两种情况
        //1. 用户是一转，则挂载方案所有者的信息
        //2. 用户是二转，则挂载转发链上一条记录的转发者的信息
        //还有一条 同一个用户多次转发相同的方案时 只会返回最新的一条
        public function find( $cond = array() , $select = '' , $return_dyadic_array = FALSE ){
        //{{{
                $this->_CI->load->model( 'User' , 'user_m' , TRUE );
                $this->_CI->load->model( 'Scheme' , 'scheme_m' , TRUE );
                $retweets = parent::find( $cond , $select , TRUE );
                if( empty( $retweets ) ){
                        return array();
                }
                //获取的 $retweets 就是按 retweet_id 的升序排列
                //只需要在遍历时 查询余下部分是否有包含相同 scheme_id 的
                //数据并清除之便可
                //@todo 为了节约时间 可以利用的优势就是 返回的集合不需要是有序的 ( 因为之后还会再按照时间排序 )
                foreach( $retweets as $i=>$retweet ){
                        //@todo 一切都是为了所谓的合并转发 此算法有改进的余地 起码不必每次都计算 sizeof
                        if( sizeof( $retweets ) > 1 ){
                                //标志是否已经删除了某条记录
                                $has_unset = FALSE;
                                //取余下的数组
                                array_walk(
                                        array_slice( $retweets , $i ),
                                        function( $value , $key ) use( &$retweets , $retweet , $i , &$has_unset ){
                                                if( $retweet['scheme_id'] === $value['scheme_id'] ){
                                                        //有重复 删除当前的( 下标为$i ) 的记录
                                                        unset( $retweets[$i] );
                                                        $has_unset = TRUE;
                                                }
                                        }
                                );
                                if( $has_unset === TRUE ){
                                        continue;
                                }
                        }
                        $prev_retweet_id = $retweet['prev_retweet_id'];
                        if( is_numeric( $prev_retweet_id ) && $prev_retweet_id > 0 ){
                                //有效的 prev_retweet_id 意味着是 二转 或以上
                                //此处如果调用本函数的话 会形成一个递归 获取完整的转发链
                                //但是目前（只使用二转的情况）是没有必要的
                                $prev_retweet = parent::find( array( 'retweet_id'=>$prev_retweet_id ) );
                                //此处的断言是 prev_retweet 信息是一定存咋的 若不存在 则需要触发一个异常
                                if( empty( $prev_retweet  ) ){
                                        throw new Exception( '通过有效的 prev_retweet_id 获取了一个空的 retweet.[prev_retweet_id=' . $prev_retweet_id . ']' );
                                }
                                //获取相关用户信息
                                $prev_retweet_user = $this->_CI->user_m->find( array( 'user_id'=>$prev_retweet['retweeter_id'] ) );
                        }else{
                                //无效的 prev_retweet_id 意味着是一转
                                //"转发自"用户信息就是方案的所有用户
                                $scheme = $this->_CI->scheme_m->find( array( 'scheme_id'=>$retweet['scheme_id'] ) );
                                $prev_retweet_user = $scheme['holder'];
                        }
                        //获取当前 retweeter 用户的信息
                        $retweets[$i]['retweeter'] = $this->_CI->user_m->find( array( 'user_id'=>$retweet['retweeter_id'] ) );
                        $retweets[$i]['prev_retweet_user'] = $prev_retweet_user;
                        $retweets[$i]['formated_time'] = $this->format_time( $retweet['time'] );
                }
                if( $return_dyadic_array == FALSE && sizeof( $retweets ) == 1 ){
                        return array_shift( $retweets );
                }
                return $retweets;
        }//}}}
}
