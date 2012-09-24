<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class buyopt extends CI_Controller{

        public function do_buy(){

                if( !$this->input->is_ajax_request() ){
                         
                        show_404();
                        return FALSE;
                }

                $this->load->library( 'user_auth' );

                //只有登录用户可以执行本操作
                if ( $this->user_auth->is_user_login() ){
                        
                        $subject_user_id = $this->session->userdata( 'user_id' );
                }else{
                
                        echo '{"res":false,"info":"没有登录不能执行购买操作."}';
                        return;
                }

                //得到传入的 solution_id 
                $solution_id = $this->input->post( 'solution_id' );

                if( empty( $solution_id ) ){
                
                        echo '{"res":false,"info":"方案编号非法，请重试."}'; 
                        return;
                }

                $this->load->library( 'transaction_process' , 
                        array( 'user_id'=>$subject_user_id , 'solution_id'=>$solution_id ) );

                //获得购买操作返回之结果
                echo $this->transaction_process->do_buy( $solution_id );
        }

}
