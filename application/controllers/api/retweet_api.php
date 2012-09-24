<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
// Retweet (转发操作) RESTful api controller
// @auther <judasnow@gmail.com>
//
require( APPPATH . '/libraries/REST_Controller.php' );

class Retweet_api extends REST_Controller{

        public function is_legal_scheme_id(){
                
        }

        //添加一条新的转发信息
        public function info_post(){
        //{{{
                $content = $this->post( 'content' , TRUE );
                $img_name = $this->post( 'img_name' , TRUE );
                $from_info = $this->post( 'from_info' , TRUE );
                $this->load->model( 'Retweet' , 'retweet_m' , TRUE );
                $this->load->library( 'user_auth' );
                try{
                        //由用户输入组装新的转发信息
                        $new_retweet = array(
                                'content'=>$content ,
                                'img_name'=>$img_name 
                        );
                        if( !$this->user_auth->is_user_login() ){
                                $this->response( array('error' => 'user did not login yet.') , 500 );
                        }else{
                                $retweeter_id = $this->session->userdata( 'user_id' );
                                //获取 holder_info
                                $retweeter_info = $this->user_m->find( array( 'user_id'=>$retweeter_id ) );
                                $new_retweet['retweeter_id'] = $retweeter_id;
                        }
                        //转发涉及到的方案
                        if( isset( $from_info['scheme_id'] ) ){
                                $new_retweet['scheme_id'] = $from_info['scheme_id'];
                        }
                        //转发链中的上一条转发信息(若存在的话)
                        if( isset( $from_info['retweet_id'] ) ){
                                $new_retweet['prev_retweet_id'] = $from_info['retweet_id'];
                        }
                        $this->retweet_m->add_new( $new_retweet );
                        $this->response( array( 'info' => 'add new retweet ok.' ) , 200 );
                }catch( Exception $e ){
                        log_message( '执行转发操作时发生异常' , $e->getMessage() );
                        $this->response( array( 'error' => 'add new retweet error.' ) , 500 );
                }
        }//}}}

        //获取指定的转发信息
        public function info_get(){
        //{{{
                //@todo 判断 retweet_id 是否合法
                $retweet_id = $this->get( 'retweet_id' , TRUE );
                $this->load->model( 'Retweet' , 'retweet_m' , TRUE );
                try{
                        $this->response( $this->retweet_m->find( array( 'retweet_id'=>$retweet_id ) , '' ) , 200 );
                }catch( Exception $e ){
                        log_message( '获取转发信息时发生异常' , $e->getMessage() );
                        $this->response( array( 'error' => 'get retweet error.' ) , 500 );
                }
        }//}}}
}
