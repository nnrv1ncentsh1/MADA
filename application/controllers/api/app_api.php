<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
// App (应用配置/初始化信息) RESTful api controller
// @auther <judasnow@gmail.com>
//
require( APPPATH . '/libraries/REST_Controller.php' );

class app_api extends REST_Controller{
        //获取页面应用所需的配置信息( 当前登录用户信息，当前页面所属信息等等)
        public function config_get(){
        //{{{
                $this->load->library( 'user_auth' );
                try{
                        if( $this->user_auth->is_user_login() ){
                                $object_user_id = $this->session->userdata( 'user_id' );
                                $object_user_info = $this->user_m->find( array( 'user_id'=>$object_user_id ) );
                                unset( $object_user_info['passwd'] );
                        }else{
                                $object_user_info = null;
                        }
                        $this->response( array( 'object_user_info'=>json_encode( $object_user_info ) ) , 200 );
                }catch( Exception $e ){
                        log_message( "获取 app config 信息时出错: " . $e->getMessage() );
                        $this->response( array( 'error'=>'get app config info error.' ) , 500 );
                }
        }//}}}
}
