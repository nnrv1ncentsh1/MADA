<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
// 定义用户认证相关的操作
// @author <judasnow@gmail.com>
//
class Auth extends CI_Controller {

        public function login(){
        /*{{{*/
                //oauth 登录时 需要使用
                //如果设置了 call_back uri
                $call_back = $this->input->get( 'call_back' , TRUE );
                //尝试获得两外两个参数
                $requester_name = $this->input->get( 'requester_name' , TRUE );
                $requester_email = $this->input->get( 'requester_email' , TRUE );

                $this->load->library( 'ie' );
                $this->ie->browserwarn();
                /**
                 * 判断用户 ip 是否在黑名单内
                 */
                $this->load->library( 'Secur_process' );
                if( !$this->secur_process->is_this_client_can_access() ){
                
                         die( "ip 地址受限，不能访问本系统" );
                }
                //此处参数为空，不过不影响 is_user_login 函数的使用
                $this->load->library( 'user_auth' );
                //判断用户是否已经登录系统，若已经
                //登录系统则不允许访问本页面
                if ( $this->user_auth->is_user_login() ){
                         
                        if( empty( $call_back ) ){

                                 $url = '/';
                        }else{
                        
                                 $url = "$call_back?requester_name=$requester_name&&requester_email=$requester_email";
                        }
                        header( "Location: $url" );
                        exit();
                }               
                $this->load->view( 'div/html_head_info' , array( 'title'=>'大智若愚 / 登录' ) );
                if( $this->user_auth->is_user_login() ){
                         
                        $this->load->view( 'div/header' , array( 'page_name'=>'resetpassword' ) );
                }else{
                
                        $this->load->view( 'div/welcome_header' , array( 'page_name'=>'resetpassword' ) );
                }
                //根据是否为 oauth 的登录请求
                //决定采用哪一个 login 模板文件
                //判断的依据暂时为是否设置了三个相关
                //的参数
                $login_template = ( $call_back && $requester_name && $requester_email ) ? 'oauth2_login' : 'login';             
                //判断session中是否存在有reg_error_info，即注册错误消息
                if( $auth_error_info = $this->session->flashdata( 'auth_error_info' ) ){

                        $oauth2_info = $this->session->flashdata( 'oauth2_info' );
                        $this->load->view( 'auth/' . $login_template ,
                                array( 'auth_error_info'=>$auth_error_info ,
                                        'call_back'=>$oauth2_info['call_back'] , 'requester_name'=>$oauth2_info['requester_name'] ,
                                        'requester_email'=>$oauth2_info['requester_email'] ) );
                }else{
                
                        $this->load->view( 'auth/' . $login_template , 
                                array( 'call_back'=>$call_back , 'requester_name'=>$requester_name , 'requester_email'=>$requester_email ) );
                }

                $this->load->view( 'div/footer' );
        }/*}}}*/
         
        public function do_login(){
        /*{{{*/
                //如果设置了 callback 地址
                $call_back = $this->input->post( 'call_back' , TRUE );
                //尝试获得两外两个参数
                $requester_name = $this->input->post( 'requester_name' , TRUE );
                $requester_email = $this->input->post( 'requester_email' , TRUE );
                //返回之前的页面
                if( !empty( $_SERVER['HTTP_REFERER'] ) ){
                        $url = str_replace( 'http://' . $_SERVER['HTTP_HOST'] , '' , $_SERVER['HTTP_REFERER'] );
                }else{
                        $url = '/';
                }
                //$call_back 的优先级高于 referer
                $url = empty( $call_back ) ? $url : "http://$call_back?requester_name=$requester_name&&requester_email=$requester_email";
                //得到用户输入信息
                $email = $this->input->post( 'email' );
                $passwd = $this->input->post( 'passwd' );
                $user_input_info = array( 'email'=>$email , 'passwd'=>$passwd );
                $auto_login = $this->input->post( 'auto_login' );
                $this->load->library( 'user_auth' , array( 'user_input_info'=>$user_input_info ) );
                //判断用户是否已经登录系统
                if( !$this->user_auth->is_user_login() ){
                        //没有登录系统还
                        if( $this->user_auth->do_login() ){
                                //登录成功              
                                //如果设置了自动登录
                                if( $auto_login == 'on' ){
                                        $auth_code = base64_encode( $email . '|' . md5( $passwd ) );
                                        $this->load->helper('cookie');
                                        //设置cookie
                                        $cookie = array(
                                                 'name'   => 'auth' ,
                                                 'value'  => $auth_code ,
                                                 //默认一个月
                                                 'expire' => '295200',
                                                 'domain' => '',
                                                 'path'   => '/',
                                                 'prefix' => '',
                                        );
                                        set_cookie( $cookie );
                                }        
                                header( "Location: $url" );
                                exit;
                        }else{
                                //登录失败 
                                //失败消息中 包含 oauth 信息
                                //因为这些信息放在 get 中传递会出错
                                //view中获得不到变量值 还没有找到原因
                                $this->session->set_flashdata( 'auth_error_info' ,
                                        array( '0'=>"用户名或密码错误" ) );
                                $this->session->set_flashdata( 'oauth2_info' ,
                                        array( 'call_back'=>$call_back , 'requester_name'=>$requester_name ,
                                                'requester_email'=>$requester_email ) );
                                //重定向到 login 页面并显示错误消息
                                header( "Location: /login" );
                                exit;
                        }
                }else{
                        
                        header( "Location: $url" );
                        exit;
                }
        }/*}}}*/

        public function do_logout(){
        /*{{{*/
                $this->load->library( 'user_auth' , '' );
                 
                //判断用户是否已经登录系统
                if ( $this->user_auth->is_user_login() ){
                        
                        //用户已经登录，删除
                        $this->user_auth->do_logout();
                        //redirect( '/login' , 'refresh' );
                        header( 'Location: /login' );
                }else{
                         
                        //用户还没有登录系统
                        //定向到首页
                        //redirect( '/login' , 'refresh' );
                        header( 'Location: /login' );
                }
        }/*}}}*/

        public function lostpwd(){
        /*{{{*/
                //此处参数为空，不过不影响 is_user_login 函数的使用
                $this->load->library( 'user_auth' );

                //判断用户是否已经登录系统，若已经
                //登录系统则不允许访问本页面
                if ( $this->user_auth->is_user_login() ){
                
                        redirect( '/' , 'refresh' );    
                }

                $this->load->view( 'div/html_head_info' , array( 'title'=>'大智若愚 / 找回密码' ));
                 
                if( $this->user_auth->is_user_login() ){
                         
                        $this->load->view( 'div/header' , array( 'page_name'=>'lostpwd' ) );
                }else{
                
                        $this->load->view( 'div/welcome_header' , array( 'page_name'=>'lostpwd' ) );
                }
                //判断session中是否存在有 lostpwd_error_info
                if( $lostpwd_info = $this->session->flashdata( 'lostpwd_info' ) ){
                
                         $this->load->view( 'auth/lostpwd' , array( 'lostpwd_info'=>$lostpwd_info ) );
                }else{
                
                         $this->load->view( 'auth/lostpwd' );
                }
                
                $this->load->view( 'div/footer' );
        }/*}}}*/

        public function do_sendmail(){
        /*{{{*/
                //判断用户输入的邮箱是否已经注册
        
                //此处参数为空，不过不影响 is_user_login 函数的使用
                $this->load->library( 'user_auth' );

                //判断用户是否已经登录系统，若已经
                //登录系统则不允许访问本页面
                if ( $this->user_auth->is_user_login() ){
                
                        redirect( '/' , 'refresh' );    
                }

                //得到用户输入email信息
                $email = $this->input->post( 'lostpwd_email' );
                $user_input_info = array( 'email'=>$email );

                //尝试得到该用户的信息
                $this->db->where( 'email' , $email );
                $res = $this->db->get( 'user' );
                if( $res->num_rows() != 1 ){
                
                        //email 不存在
                        return false;
                }
                $row = $res->result_array();
                $user_info = $row[0];

                $user_id = $user_info['user_id'];
                //取出 passwd 以生成验证信息
                $passwd = $user_info['passwd'];
                //验证编码
                $confirmation_code = base64_encode( $user_id . '.' . hash( 'md4' , ( $email . $passwd ) ) );

                $this->load->library( 'user_reg' , array( 'user_input_info'=>$user_input_info ) );
                
                //用户输入的邮箱已经被注册，即系统中存在
                //该用户
                if( !$this->user_reg->is_email_unique() ){
                
                        $this->session->set_flashdata( 'lostpwd_info' , array(  'type'=>'success' , 'content'=>'邮件已发送! 请查阅您的邮箱并重设密码. ' ) );
                        
                        $this->load->library('email');
                        
                        $config['protocol'] = 'smtp'; 
                        $config['smtp_host'] = 'smtp.sina.com';
                        $config['smtp_user'] = 'dazeryservice@sina.cn';
                        $config['smtp_pass'] = 'dazeryservice';
                        $config['smtp_port'] = '25';
                        $config['smtp_timeout'] = '3';
                        $config['newline'] = "/r/n";
                        $config['crlf'] = "/r/n";

                        $this->email->initialize($config);
        
                        $this->email->from('dazeryservice@sina.cn');
                        $this->email->to( $email );
                        $this->email->subject( 'dazery.com 重设密码邮件' );
                        $this->email->message( "亲爱的用户:

您的密码重设要求已经得到验证。请点击以下链接输入您新的密码：

(pleae click on the following link to reset your password:)

http://dazery.com/resetpassword?confirmation=$confirmation_code

如果您的email程序不支持链接点击,请将上面的地址拷贝至您的浏览器(例如IE).

感谢对大智若愚的支持，再次希望您在大智若愚的体验有益和愉快。

(这是一封自动产生的email,请勿回复).                             
" );
                        $this->email->send();
                  
                        $this->email->print_debugger();
                 
                        header( 'Location: /lostpwd' );
                        exit;
                }else{
                
                        //还没有被注册
                        //返回错误消息
                        $this->session->set_flashdata( 'lostpwd_info' , array(  'type'=>'error' , 'content'=>'这个email地址还没有注册过' ) );
                        header( 'Location: /lostpwd' );
                        exit;
                }
        }/*}}}*/

        public function resetpassword(){
        /*{{{*/
                //此处参数为空，不过不影响 is_user_login 函数的使用
                $this->load->library( 'user_auth' );

                //判断用户是否已经登录系统，若已经
                //登录系统则不允许访问本页面
                if ( $this->user_auth->is_user_login() ){
                
                        redirect( '/' , 'refresh' );    
                }

                $this->load->view( 'div/html_head_info' , array( 'title'=>'大智若愚 / 重设密码' ));
                if( $this->user_auth->is_user_login() ){
                         
                        $this->load->view( 'div/header' , array( 'page_name'=>'resetpassword' ) );
                }else{
                
                        $this->load->view( 'div/welcome_header' , array( 'page_name'=>'resetpassword' ) );
                }

                $confirmation_code = $this->input->get( 'confirmation' );
                $reset_passwd_res = $this->session->flashdata( 'reset_passwd_res' );
                
                $this->load->view( 'auth/resetpassword' , 
                        array( 'confirmation_code'=>$confirmation_code , 'reset_passwd_res'=>$reset_passwd_res ) );
                
                $this->load->view( 'div/footer' );
        }/*}}}*/

        public function do_resetpassword(){
        /*{{{*/
                //判断用户输入的邮箱是否已经注册
        
                //此处参数为空，不过不影响 is_user_login 函数的使用
                $this->load->library( 'user_auth' );

                //判断用户是否已经登录系统，若已经
                //登录系统则不允许访问本页面
                if ( $this->user_auth->is_user_login() ){
                
                        redirect( '/' , 'refresh' );    
                }

                //判断用户输入的密码
                $passwd = $this->input->post( 'password' );
                $check_passwd = $this->input->post( 'check_password' );

                if( $passwd != $check_passwd ){
                
                        //两次密码不同
                        $this->session->set_flashdata( 'reset_passwd_res' , array( 'res'=>FALSE , 'info'=>'两次密码不同' ) );
                        header( 'Location: /resetpassword' );
                        exit;
                }
        
                $confirmation_code = $this->input->post( 'confirmation_code' );
                if( empty( $confirmation_code ) ){
                
                        //验证码有误
                        $this->session->set_flashdata( 'reset_passwd_res' , array( 'res'=>FALSE , 'info'=>'重设密码的链接没有设置' ) );
                        header( 'Location: /resetpassword' );
                        exit;
                }

                //包含两部分信息 user_id . hash( 'email' . 'passwd' )
                $user_info = explode( '.' , base64_decode( $confirmation_code ) );
                //执行验证
                $this->load->model( 'User' , '' , TRUE );
                $this->User->load( $user_info[0] );

                $email = $this->User->get( 'email' );
                $old_passwd = $this->User->get( 'passwd' );

                if( hash( 'md4' , ($email . $old_passwd) ) == $user_info[1] ){
                        
                        //成功，重新设置密码
                        $this->db->where( 'user_id' , $user_info[0] );
                        $this->db->update( 'user' , array( 'passwd'=>md5( $passwd ) ) );

                        if( $this->db->affected_rows() == 1 ){
                         
                                 $this->session->set_flashdata( 'reset_passwd_res' , array( 'res'=>TRUE , 'info'=>'设置成功' ) );
                                 header( 'Location: /resetpassword' );
                                 exit;
                        }else{
                        
                                //操作失败
                                $this->session->set_flashdata( 'reset_passwd_res' , array( 'res'=>FALSE , 'info'=>'重设密码操作失败，请稍后再试' ) );
                                header( 'Location: /resetpassword' );
                                exit;
                        }
                }else{
                
                        //信息验证错误
                        $this->session->set_flashdata( 'reset_passwd_res' , array( 'res'=>FALSE , 'info'=>'重设密码的链接有错误' ) );
                        header( 'Location: /resetpassword' );
                        exit;
                }
        }/*}}}*/
}

