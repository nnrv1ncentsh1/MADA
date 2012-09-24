<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
// Schemes(方案集合) RESTful api controller
// @auther <judasnow@gmail.com>
//
require( APPPATH . '/libraries/REST_Controller.php' );

class Schemes_api extends REST_Controller{
        function home_get(){
        //{{{
                try{
                        $this->load->model( 'Scheme' , 'Scheme_m' , TRUE );
                        $page = $this->get( 'page' );
                        $subject_user_id = $this->get( 'subject_user_id' );
                        $page = !empty( $page ) ? $page : 1;
                        $schemes = $this->Scheme_m->get_schemes_for_home( $subject_user_id , $page );
                        if( !empty( $schemes ) ){
                                $this->response( $schemes , 200 );
                        }else{
                                $this->response( array( 'error' => 'wrong scheme id or subjet user id.' ) , 404 );
                        }
                }catch( RunTimeException $e ){
                        log_message( '获取 home 页面方案信息时发生异常:' , $e->getMessage() );
                        $this->response( array( 'error' => 'fetch schemes info for home page error.' ) , 500 );
                }
        }//}}}
}

