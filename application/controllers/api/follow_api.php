<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
// Follow(用户社会化关系) RESTful api controller
// @auther <judasnow@gmail.com>
//
require( APPPATH . '/libraries/REST_Controller.php' );

class Follow_api extends REST_Controller{

        //获取指定用户 关注数/粉丝数
        public function count_get(){
        //{{{
                $user_id = $this->get( 'user_id' , TRUE );
                $key = $this->get( 'key' , TRUE );
                if( empty( $user_id ) || !is_numeric( $user_id ) ){
                        $this->response(
                                array( 
                                        'error'=>'user id is illegal.' 
                                ),
                                400
                        );
                }
                if( $key != 'retwetts' && $key != 'favorites' ){
                        $this->response(
                                array(
                                        'error'=>'key is illegal.' 
                                ),
                                200 
                        );
                }
                $this->load->model( 'Follow' , 'follow_m' , TRUE );
                $function_name = join( '' , array( 'get_' , $key , '_by_user_id' ) );
                $count = $this->follow_m->$function_name( $user_id );
                if( !empty( $count ) ){
                        $this->response( $count , 200 );
                }else{
                        $this->response(
                                array(
                                        'error'=>'user could not be found.'
                                ),
                                404
                        );
                }
        }//}}}

        //将关注的状态切换为指定的状态
        public function info_post(){
        //{{{
                $this->load->library( 'user_auth' );
                $this->load->model( 'Follow' , 'follow_m' , TRUE );
                try{
                        if( !$this->user_auth->is_user_login() ){
                                $this->response(
                                        array(
                                                'error'=>'user did not login yet.'
                                        ),
                                        500
                                );
                        }else{
                                $object_user_id = $this->session->userdata( 'user_id' );
                                $user_id = $this->post( 'user_id' , TRUE );
                                $has_follow = $this->post( 'has_follow' , TRUE );
                                switch( $has_follow ){
                                        case false:
                                                $func_name = 'do_follow';
                                                break;
                                        case true:
                                                $func_name = 'do_follow';
                                                break;
                                }
                                $this->follow_m->$func_name( $user_id , $object_user_id );
                                $this->response(
                                        array(), 
                                        200 
                                );
                        }
                }catch( Exception $e ){
                        log_message( 'error' , $e->getMessage() );
                        $this->response(
                                array(
                                        'error'=>''
                                ),
                                500
                        );
                }
        }//}}}  

        public function info_get(){
        //{{{
                $this->load->library( 'user_auth' );
                $this->load->model( 'Follow' , 'follow_m' , TRUE );
                $user_id = $this->get( 'user_id' , TRUE );
                if( !$this->user_auth->is_user_login() ){
                        $this->response( 
                                array(
                                        'error'=>'user did not login yet.'
                                ),
                                500
                        );
                }else{
                        $object_user_id = $this->session->userdata( 'user_id' );
                        if( $this->follow_m->has_follow( $user_id , $object_user_id ) ){
                                $has_follow = TRUE;
                        }else{
                                $has_follow = FALSE;
                        }
                        $this->response(
                                array(
                                        'has_follow'=>$has_follow
                                ),
                                200
                        );
                }
        }}//}}}

