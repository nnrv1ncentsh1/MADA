<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// 
// 用户对于方案的私聊(reply) model
// @author <judasnow@gmail.com>
//
require_once 'base_model.php';

class Reply extends Base_model{

        private $_CI;
        const PAGE_LIMIT = 7;

        function __construct(){
                $this->_CI =& get_instance();
                parent::__construct( 'reply' );
        }

        //返回指定 方案编号 的指定 页码 的私聊信息
        public function get_replies_by_scheme_id( $scheme_id , $page = 1 , $user_id ){
                $this->_CI->load->model( 'Scheme' , 'scheme_m' , TRUE );
                $this->_CI->load->model( 'User' , 'user_m' , TRUE );
                //首先需要判断当前方案是否为当前登录用户所有
                if( $this->_CI->scheme_m->is_exists( array( 'scheme_id'=>$scheme_id , 'holder_id'=>$user_id ) ) ) {
                        //如果为当前登录用户所有 需要返回全部的私聊记录
                        $replies_info = $this->find( array( 'scheme_id'=>$scheme_id ) );
                }else{
                        //如果不为当前登录用户所有 需要使用 user_id 作为 replise_id 将结果进行过滤
                        $replies_info = $this->find( array( 'scheme_id'=>$scheme_id , 'replise_id'=>$user_id ) );
                }
                if( empty( $replies_info ) ){
                        return array();
                }
                //根据 page 信息进行分页
                $raw_replies_info = $this->slice_by_page(
                        $this->sort_by_time( $replies_info ),
                        $page,
                        self::PAGE_LIMIT
                );
                //添加发送私聊的用户信息
                $repiler_info = array();
                foreach( $raw_replies_info as $raw_replie_info ){
                        $repiler_info = $this->user_m->find( $raw_replie_info['repiler_id'] );

                }
        }
}

