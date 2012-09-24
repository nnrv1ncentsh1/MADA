<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
// User(用户) RESTful api controller
// @auther <judasnow@gmail.com>
//
require( APPPATH . '/libraries/REST_Controller.php' );

class User_api extends REST_Controller{
        //获取用户的基本信息
        public function info_get(){
        //{{{
                $user_id = $this->get( 'id' , TRUE );
                if( empty( $user_id ) || !is_numeric( $user_id ) ){
                        $this->response( array( 'error' => 'user id is illegal.' ) , 400 );
                }
                $this->load->model( 'User' , 'User_m' , TRUE );
                $user = $this->User_m->find( array( 'user_id'=>$user_id ) );
                if( !empty( $user ) ){
                        $this->response( $user , 200 );
                }else{
                        $this->response( array('error' => 'user could not be found.') , 404 );
                }
        }//}}}
}
