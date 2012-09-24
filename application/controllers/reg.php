<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class reg extends CI_Controller{

	public function index(){
/*{{{*/
		$this->load->library( 'ie' );
		$this->ie->browserwarn();

		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );

		//判断用户是否已经登录系统，若已经
		//登录系统则不允许访问本页面
		if ( $this->user_auth->is_user_login() ){
		
			header( 'Location: /' );
			exit;
		}

		//由首页定向而来的用户注册信息
		$email = $this->input->post( 'email' );
	 	$nick = $this->input->post( 'nick' );
		$passwd = $this->input->post( 'password' );
		$loc_province = $this->input->post( 'loc_province' );
		$loc_city = $this->input->post( 'loc_city' );
		$province = $this->input->post( 'province' );
		$city = $this->input->post( 'city' );
		$check_passwd = $this->input->post( 'check_password' );
		$user_input_info = array( 'email'=>$email , 'nick'=>$nick , 'passwd'=>$passwd , 'check_passwd'=>$check_passwd ,
			'city'=>$city , 'loc_city'=>$loc_city , 'province'=>$province , 'loc_province'=>$loc_province );

		
		$this->load->view( 'div/html_head_info' , array( 'title'=>'大智若愚 / 注册' ));
		if ( $this->user_auth->is_user_login() ){
			
			$this->load->view( 'div/header' , array( 'object_user_info'=>$object_user_info , 'page_name'=>'reg' ) );
		}else{
		
			$this->load->view( 'div/welcome_header' , array( 'page_name'=>'reg' ) ); 
		}

	 	//判断session中是否存在有reg_error_info，即注册错误消息
		if( $reg_error_info = $this->session->flashdata( 'reg_error_info' ) ){
		
			 $this->load->view( 'reg' , array( 'reg_error_info'=>$reg_error_info , 'user_input_info'=>$user_input_info ) );
		}else{
		
			$this->load->view( 'reg' , array( 'user_input_info'=>$user_input_info ) );
		}

		$this->load->view( 'div/footer' );
	}/*}}}*/

	public function do_reg(){

		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );

		//判断用户是否已经登录系统，若已经
		//登录系统则不允许访问本页面
		if ( $this->user_auth->is_user_login() ){
		
			header( 'Location: /' );
			exit;	
		}

		//得到用户输入信息
		$email = $this->input->post( 'email' );
	 	$nick = $this->input->post( 'nick' );
		$passwd = $this->input->post( 'password' );
		$loc_province = $this->input->post( 'loc_province' );
		$loc_city = $this->input->post( 'loc_city' );
		$province = $this->input->post( 'province' );
		$city = $this->input->post( 'city' );
		$check_passwd = $this->input->post( 'check_password' );
		$user_input_info = array( 'email'=>$email , 'nick'=>$nick , 'passwd'=>$passwd , 'check_passwd'=>$check_passwd ,
			'city'=>$city , 'loc_city'=>$loc_city , 'province'=>$province , 'loc_province'=>$loc_province );

		//加载框架自带的帮助类
		$this->load->helper('email');
	 	
		//初始化 user_reg 对象
		$this->load->library( 'user_reg' , array( 'user_input_info'=>$user_input_info ) );
		
		if ( $this->user_reg->do_reg() ){

			//注册成功

			//尝试为直接为用户登录
			//因为 user_auth 对象已经建立，只有再传递参数给他
			$this->user_auth->set_user_info( array( 'user_input_info'=>$user_input_info ) );

			//判断用户是否已经登录系统
	 		if ( !$this->user_auth->is_user_login() ){
			 	
				//还没有登录 尝试登录
				if( $this->user_auth->do_login() ){
					
					 //登录成功
	 	 	 	 	 header( 'Location: /' );
	 	 	 	 	 exit;
				}else{
				 	 //登录失败
					 $this->session->set_flashdata( 'auth_error_info', array( '0'=>"用户名或密码错误" ) );
					 //重定向到 login 页面并显示错误消息
					 header( 'Location: /login' );
					 exit;
				}

			}else{
	 	 	 	 
				//已经登录系统，也是跳转到主页
				header( 'Location: /' );
				exit;
			}
		}else{
			 
			//注册失败
			$this->session->set_flashdata( 'reg_error_info', array( '0'=>"注册失败，请再试一次" ) );
			//重定向到 login 页面并显示错误消息
			header( 'Location: /reg' );
			exit;
			//redirect( 'reg' , 'refresh' );
		}
	}

	//ajax验证用户输入email是否已经
	//在系统中注册
	public function has_this_email_reg(){
		
		if( !$this->input->is_ajax_request() ){
			 
			show_404();
			return FALSE;
		}

		$email = $this->input->post( 'email' );
		$user_input_info = array( 'email'=>$email );
		//初始化 user_reg 对象
		$this->load->library( 'user_reg' , array( 'user_input_info'=>$user_input_info ) );
		echo $res = $this->user_reg->is_email_unique() ? 'FALSE' : 'TRUE' ;
	}

	//ajax验证用户输入nick是否已经
	//在系统中注册
	public function has_this_nick_reg(){

		if( !$this->input->is_ajax_request() ){
			 
			show_404();
			return FALSE;
		}

		//比较是不是当前用户注册的nick
		//如果是则不反回错误
		$this->load->library( 'user_auth' );
	 	 		
		//判断用户是否已经登录系统,如果登录
		//则取出用户 nick 判断此次修改其是否改
		//变
		if ( $this->user_auth->is_user_login() ){
			 
			$object_user_id = $this->session->userdata( 'user_id' );
			$this->load->model( 'User' , '' , TRUE );
		 	$this->User->load( $object_user_id );
		 	$object_user_nick = $this->User->get( 'nick' );
		}else{
			 
			$object_user_nick = '';
		}

		$nick = $this->input->post( 'nick' );
		if( $object_user_nick == $nick ){
		
			echo 'FALSE';
		 	return;
		}

		$this->config->load( 'secur' );
		$domain_limit = $this->config->item( 'domain_limit' );
		$domain_limit_array = explode( '|' , $domain_limit );
		if( in_array( $nick , $domain_limit_array ) ){
			 
			echo 'TRUE';
	 	 	return;
		}

		$user_input_info = array( 'nick'=>$nick );
		//初始化 user_reg 对象
		$this->load->library( 'user_reg' , array( 'user_input_info'=>$user_input_info ) );
		echo $res = $this->user_reg->is_nick_unique() ? 'FALSE' : 'TRUE' ;
		return;
	}

	//验证domain是否已经被注册，以及是否可用
	public function has_this_domain_reg(){

		if( !$this->input->is_ajax_request() ){
			 
			show_404();
			return FALSE;
		}
	
		$domain = $this->input->post( 'domain' );
		$user_input_info = array( 'domain'=>$domain );
		//初始化 user_reg 对象
		$this->load->library( 'user_reg' , array( 'user_input_info'=>$user_input_info ) );
		$is_domain_unique = $this->user_reg->is_domain_unique();
		$is_domain_valid = $this->user_reg->is_domain_valid();
		echo ( $is_domain_unique && $is_domain_valid ) ? 'FALSE' : 'TRUE' ;
	}
}
