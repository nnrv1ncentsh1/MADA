<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
// Favor(用户收藏) RESTful api controller
// @auther <judasnow@gmail.com>
//
require( APPPATH . '/libraries/REST_Controller.php' );

class Favor_api extends REST_Controller{

        //做统一的初始化工作 并且判断用户是否已经登录系统
        private function _init(){
                $this->load->library( 'user_auth' );
                $this->load->model( 'Favor' , 'favor_m' , TRUE );
                if( !$this->user_auth->is_user_login() ){
                        $this->response(
                                array(
                                        'error'=>'user did not login yet.'
                                ),
                                500
                        );
                }
        }

        public function info_get(){
                $this->_init();
                $scheme_id = $this->get( 'scheme_id' , TRUE );
                $object_user_id = $this->session->userdata( 'user_id' );
                $this->response(
                        array( 
                                'has_favor'=>$this->favor_m->is_exists(
                                        array(
                                                'scheme_id'=>$scheme_id,
                                                'user_id'=>$object_user_id
                                        )
                                )
                        ), 
                        200 
                );
        }

        public function info_post(){
        //{{{
                $this->_init();
                $scheme_id = $this->get( 'scheme_id' , TRUE );
                $object_user_id = $this->session->userdata( 'user_id' );
                $has_favor = $this->post( 'has_favor' , TRUE );
                switch( $has_favor ){
                        case TRUE:
                                $func_name = 'do_favor';
                                break;
                        case FALSE:
                                $func_name = 'undo_favor';
                                break;
                        default:
                                throw new Exception( '无效的 has_favor 状态.' );
                }
                $this->favor_m->$func_name( array( 'user_id'=>$object_user_id , 'scheme_id'=>$scheme_id ) );
                $this->response(
                        array(),
                        200
                );
        }//}}}
}
