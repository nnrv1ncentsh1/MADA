<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
// 用户对于方案的私聊(reply) RESTful api controller
// @auther <judasnow@gmail.com>
//
require( APPPATH . '/libraries/REST_Controller.php' );

class Reply_api extends REST_Controller{

        //获取当前登录用户的 user_id
        //@return int $subject_user_id 
        private function get_subject_user_id(){
                $this->load->library( 'user_auth' );
                if( $this->user_auth->is_user_login() ){
                        return $this->session->userdata( 'user_id' );
                }else{
                        $this->response( 'not auth yet.' , 500 );
                }
        }

        //针对于提供的 scheme_id 获取与其相关的私聊信息
        public function infos_get(){
                $scheme_id = $this->get( 'scheme_id' , TRUE );
                $page = $this->get( 'page' , TRUE );

                $this->load->model( 'Reply' , 'reply_m' , TRUE );
                if( empty( $page ) || !is_numeric( $page ) ){
                        $page = 1;
                }
                try{
                        $subject_user_id = $this->get_subject_user_id();
                        $replys_info = $this->reply_m->get_replies_by_scheme_id( $scheme_id , $page , $subject_user_id );
                        $this->response( $replys_info , 200 );
                }catch( Exception $e ){
                        log_message( 'error' , '获取方案相关私聊信息时出错.[' . $e->getMessage() . ']' );
                        $this->response( 'fetch replies info error.' , 500 );
                }
        }

        //接受 reply_id 返回单条的私聊记录
        public function info_get(){
                $reply_id = $this->get( 'reply_id' , TRUE );

                $this->load->model( 'Reply' , 'reply_m' , TRUE );
                try{
                        $reply_info = $this->reply_m->find( array(
                                'reply_id'=>$reply_id
                        ));
                        $this->response( $reply_info , 200 );
                }catch( Exception $e ){
                        log_message( 'error' , '' );
                        $this->response( '' , 500 );
                }
        }

        //添加一条新的私聊信息
        public function info_post(){
                $subject_user_id = $this->get_subject_user_id();
                $content = $this->post( 'content' , TRUE );
                $prev_reply_id = $this->post( 'prev_reply_id' , TRUE );
                $scheme_id = $this->post( 'scheme_id' , TRUE );

                $this->load->model( 'Reply' , 'reply_m' , TRUE );
                try{
                        $this->reply_m->add_new(
                                array(
                                        'replier_id'=>$subject_user_id,
                                        'prev_reply_id'=>$prev_reply_id,
                                        'scheme_id'=>$scheme_id,
                                        'content'=>$content
                                )
                        );
                        $this->response( '' , 200 );
                }catch( Exception $e ){
                        log_message( 'error' , '' );
                        $this->response( '' , 500 );
                }
        }
}
