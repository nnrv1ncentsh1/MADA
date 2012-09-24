<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
// 定义用户权限认证操作
// @author <judasnow@gmail.com>
//
class User_auth{

        private $_CI;
        private $_user_input_info;

        public function __construct( $param = array( 'user_input_info'=>'' ) ){
        //{{{
                $this->_CI =& get_instance();
                $this->_user_input_info = $param[ 'user_input_info' ]; 
                $this->_CI->load->model( 'User' , 'user_m' , TRUE );
        }//}}}

        public function set_user_info( $param = array( 'user_input_info'=>'' )  ){
        //{{{
                $this->_user_input_info = $param[ 'user_input_info' ]; 
        }//}}}

        //
        // 判断用户是否已经登录系统
        // @return bool 如果登录则返回true，否者返回false
        //
        public function is_user_login(){
        //{{{
                $user_id = $this->_CI->session->userdata( 'user_id' );
                if( !empty( $user_id ) ){
                        //获取登录用户信息
                        $user_info = $this->_CI->user_m->find( array( 'user_id'=>$user_id ) );
                        $domain = $user_info['domain'];
                        if( empty( $domain ) ){
                                setcookie( 'auth' , '' , $_SERVER['REQUEST_TIME']+295200 );
                                $this->_CI->session->unset_userdata( 'user_id' );
                                return false;
                        }
                        return true;
                }
                //cookies 是否设置以及是否有效  
                $this->_CI->load->helper('cookie');
                $cookies_auth_code = get_cookie( 'auth' );
                if( !empty( $cookies_auth_code ) ){
                        //如果设置了cookies 判断是否过期
                        $cookies_auth_code = base64_decode( $cookies_auth_code );
                        $cookies_auth_code = explode( '|' , $cookies_auth_code );
                        if( !isset( $cookies_auth_code[1] ) ){
                                //cookie code 格式不对
                                return false;
                        }
                        $email = $cookies_auth_code[0];
                        $passwd_hash = $cookies_auth_code[1];
                        //尝试执行一次登录操作
                        if( $user_id = $this->_CI->User->has_this_email_and_passwd( $email , $passwd_hash ) ){
                                //登录成功
                                $this->_CI->session->set_userdata( 'user_id' , $user_id );
                                return true;
                        }
                }
                return false;
        }//}}}

        //
        // 用户退出登录
        // @return bool
        //
        public function do_logout(){
        
                //判断用户当前是否处于登录状态
                if( $this->is_user_login() ){
                        //删除cookies
                        setcookie( 'auth' , '' , $_SERVER['REQUEST_TIME']+295200 );
                        $this->_CI->session->unset_userdata( 'user_id' );
                        return true;    
                }
                return false;
        }

        //
        // 进行登录操作
        // @return bool
        //
        public function do_login(){
        
                $email = $this->_user_input_info['email'];
                $passwd = md5( $this->_user_input_info['passwd'] );
                //尝试使用用户提供的信息查询数据库
                //登录时的查询不进行缓存
                //@todo 此处似乎考虑欠妥
                $user_info = $this->_CI->user_m->find( array( 'email'=>$email , 'passwd'=>$passwd ) , 'user_id' , FALSE );
                if ( !empty( $user_info ) ){
                        //登录成功
                        //保存用户 user_id 在 session 中
                        $this->_CI->session->set_userdata( 'user_id' , $user_info['user_id'] );
                        //更新用户登录ip
                        $this->_CI->user_m->update( array( 'last_login_ip'=>$this->_CI->input->ip_address() ) , array( 'user_id'=>$user_info['user_id'] ) );
                        return TRUE;
                }else{
                        return FALSE;
                }
        }
}
