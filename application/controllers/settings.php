<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class settings extends CI_Controller{
	 
	public function index(){
		//登录用户才可以访问本页面
		//根据用户登录后保存在 session 中的 user_id 
		//读取信息加以显示
		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//判断用户是否已经登录系统，以显示
		//登录用户的相关信息在右上
		if ( $this->user_auth->is_user_login() ){
	 	 	
			$user_id = $this->session->userdata( 'user_id' );
			$this->load->library( 'user_info_process' , array( 'user_id'=>$user_id ) );

			//获得用户之前的属性
			$nick = $this->user_info_process->get( 'nick' );
			$describe = $this->user_info_process->get( 'describe' );
			$motto = $this->user_info_process->get( 'motto' );
			$birthday = $this->user_info_process->get( 'birthday' );
			$sex = $this->user_info_process->get( 'sex' );
			$tel = $this->user_info_process->get( 'tel' );
			$loc_city = $this->user_info_process->get( 'loc_city' );
			$loc_town = $this->user_info_process->get( 'loc_town' );
			$loc_province = $this->user_info_process->get( 'loc_province' );
			$city = $this->user_info_process->get( 'city' );
			$town = $this->user_info_process->get( 'town' );
			$province = $this->user_info_process->get( 'province' );
			$address = $this->user_info_process->get( 'address' );
			$domain = $this->user_info_process->get( 'domain' );

			$user_info = array( 'user_id'=>$user_id , 'user_nick'=>$nick , 'domain'=>$domain );
		}else{
		
			header( 'Location: /login' );
			exit;
		}

		$user_past_info = array( 'user_id'=>$user_id , 'nick'=>$nick , 'describe'=>$describe , 'motto'=>$motto , 
			'birthday'=>$birthday , 'sex'=>$sex , 'tel'=>$tel , 'address'=>$address , 'loc_city'=>$loc_city , 
			'loc_town'=>$loc_town , 'loc_province'=>$loc_province , 'province'=>$province ,
			'city'=>$city , 'town'=>$town );

		//如果是从 do_settings 页面定向而来
		//获得保存设置的结果
		$settings_common_info = $this->session->userdata( 'settings_common_info' );
	 	$this->session->unset_userdata( 'settings_common_info' );

		$this->load->view( 'div/html_head_info' , array( 'title'=>'大智若愚 / 个人资料' ) );
		$this->load->view( 'div/header' , array( 'object_user_info'=>$user_info , 'page_name'=>'settings' ) );
		$this->load->view( 'settings/common' , array( 'user_past_info'=>$user_past_info , 'settings_common_info'=>$settings_common_info ) );
		$this->load->view( 'div/footer' );
	}

	public function do_settings_common(){

		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//只有登录用户可以访问本页面
		if ( $this->user_auth->is_user_login() ){
	 	 	 
			$user_id = $this->session->userdata( 'user_id' );

		}else{
		
			header( 'Location: /login' );
			exit;
		}
		//得到用户输入
		$nick = $this->input->post( 'nick' );
		$describe = $this->input->post( 'describe' );
		$motto = $this->input->post( 'motto' );
		$birthday = $this->input->post( 'birthday' );
		$sex = $this->input->post( 'sex' );
		$tel = $this->input->post( 'tel' );
		$loc_city = $this->input->post( 'loc_city' );
		$loc_town = $this->input->post( 'loc_town' );
		$loc_province = $this->input->post( 'loc_province' );
		$city = $this->input->post( 'city' );
		$town = $this->input->post( 'town' );
		$province = $this->input->post( 'province' );
		$address = $this->input->post( 'address' );

		$user_input_info = array( 'user_id'=>$user_id , 'nick'=>$nick , 'describe'=>$describe , 'motto'=>$motto , 'birthday'=>$birthday , 
			'sex'=>$sex , 'tel'=>$tel , 'address'=>$address ,  'loc_city'=>$loc_city , 'city'=>$city , 'town'=>$town , 'province'=>$province ,
			'loc_town'=>$loc_town , 'loc_province'=>$loc_province );

		//得到之前该用户的信息
		$this->db->where( 'user_id' , $user_id );
		$res = $this->db->get( 'user' );
		$row = $res->result_array();
	        $past_user_info = $row[0];	

		//如果用户的nick改变了的话
		//就需要判断是否系统中已经存在相同的 nick
		if( $past_user_info['nick'] != $user_input_info['nick'] ){
	       
		  	 $this->db->where( 'nick' , $user_input_info['nick'] );
			 $res = $this->db->get( 'user' );
	 	 	 
			 if( $res->num_rows() != 0 ){
			 	 
				 $this->session->set_userdata( 'settings_common_info' , array( 'res'=>FALSE , 'info'=>'该 nick 已经存在于系统中' ) );
				 header( 'Location: /settings' );
				 exit;
			 }
		}

		$this->load->library( 'User_settings' , array( 'user_input_info'=>$user_input_info , 'user_id'=>$user_id ) );
		$this->user_settings->do_settings_common();
	}

	public function myface(){

	 	header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" , $_SERVER['REQUEST_TIME'] - 100000 ) . " GMT" );
	 	header( "Cache-Control: no-cache, must-revalidate" );
		header( "Pragma: no-cache" );

	 	//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//判断用户是否已经登录系统，以显示
		//登录用户的相关信息在右上
		if ( $this->user_auth->is_user_login() ){
	 	 	 
			$user_id = $this->session->userdata( 'user_id' );
			$this->load->library( 'user_info_process' , array( 'user_id'=>$user_id ) );

			$sys_domain = $this->user_info_process->get( 'sys_domain' );

			$nick = $this->user_info_process->get( 'nick' );
			//获得用户头像之前的坐标
			$x1 = ( $this->user_info_process->get( 'x1' ) == 0 ) ? 0 : $this->user_info_process->get( 'x1' );
			$y1 = ( $this->user_info_process->get( 'y1' ) == 0 ) ? 0 : $this->user_info_process->get( 'y1' );
			$x2 = ( $this->user_info_process->get( 'x2' ) == 0 ) ? 128 : $this->user_info_process->get( 'x2' );
			$y2 = ( $this->user_info_process->get( 'y2' ) == 0 ) ? 128 : $this->user_info_process->get( 'y2' );

			$domain = $this->user_info_process->get( 'domain' );

			$user_info = array( 'user_id'=>$user_id , 'sys_domain'=>$sys_domain , 'user_nick'=>$nick ,
			       	 	 	'x1'=>$x1 , 'y1'=>$y1 , 'x2'=>$x2 , 'y2'=>$y2 , 'domain'=>$domain );
		}else{
		
			header( 'Location: /login' );
			exit;
		}

	 	//判断有误错误信息需要显示
		$myface_error = $this->session->flashdata( 'myface_error' );

		//如果设置刷新页面
		$this->load->view( 'div/html_head_info' , array( 'title'=>'大智若愚 / 头像设置' , 'nocache'=>true ));
		$this->load->view( 'div/header' , array( 'object_user_info'=>$user_info , 'page_name'=>'settings' ) );
		$this->load->view( 'settings/myface' , array( 'user_info'=>$user_info , 'myface_error'=>$myface_error ) );
		$this->load->view( 'div/footer' );
	}

	public function do_upload_myface(){
		
		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//只有登录用户可以访问本页面
		if ( $this->user_auth->is_user_login() ){
	 	 	 
			$object_user_id = $this->session->userdata( 'user_id' );
			$this->load->library( 'user_info_process' , array( 'user_id'=>$object_user_id ) );
			
			$object_user_info = $this->user_info_process->get_user_all_info();
			$sys_domain = $object_user_info['sys_domain'];

		}else{
		
			header( 'Location: /login' );
			exit;
		}

		//用户表单中 文件input的名字
		$field_name = 'userfile';

		/**
		 * 处理用户上传图片
		 */
		$this->load->library( 'face_process' , array( 'user_id'=>$object_user_id , 'sys_domain'=>$sys_domain ) );
		//上传图片到 upload 文件夹
		if( $this->face_process->do_upload() ){
			 //上传文件成功
			 //处理文件，调整文件大小
			 //并且移动问见到 picture/user_head_img 文件夹
			 $this->face_process->do_resize( getcwd()."/upload/$sys_domain.jpg" , getcwd()."/picture/user_head_img/" , '_face' , 180 );
		}

		header( 'Location: /settings/myface' );
		exit;
	}

	public function do_crop_myface(){

		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//只有登录用户可以访问本页面
		if ( $this->user_auth->is_user_login() ){
	 	 	 
			$object_user_id = $this->session->userdata( 'user_id' );
			$this->load->library( 'user_info_process' , array( 'user_id'=>$object_user_id ) );
			
			$object_user_info = $this->user_info_process->get_user_all_info();
			$sys_domain = $object_user_info['sys_domain'];
		}else{
		
			//若用户没有登录本系统
			header( 'Location: /login' );
			exit;
		}
	
		//用户裁剪的坐标
		$x1 = $this->input->post( 'x1' );
		$y1 = $this->input->post( 'y1' );
		$x2 = $this->input->post( 'x2' );
		$y2 = $this->input->post( 'y2' );

		$user_crop = array( 'x1'=>$x1 , 'y1'=>$y1 , 'x2'=>$x2 , 'y2'=>$y2 );

		//并没有传入其他数据,只有用户的裁剪坐标
		$this->load->library( 'face_process' , array( 'user_input_info'=>$user_crop , 'user_id'=>$object_user_id , 'sys_domain'=>$sys_domain ) );
		$this->face_process->do_crop();

		header( 'Location: /settings/myface' );
		exit;
	}

	public function account(){

		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//只有登录用户可以访问本页面
		if ( $this->user_auth->is_user_login() ){
	 	 	 
			$user_id = $this->session->userdata( 'user_id' );
			$this->load->library( 'user_info_process' , array( 'user_id'=>$user_id ) );

			//获得用户之前的属性
			$email = $this->user_info_process->get( 'email' );
			$domain = $this->user_info_process->get( 'domain' );
			$nick = $this->user_info_process->get( 'nick' );
			//是否已经被修改
			$domain_has_change = $this->user_info_process->get( 'domain_has_change' );
	 	 	 
			$user_info = array( 'user_id'=>$user_id , 'user_nick'=>$nick , 'domain'=>$domain );
			$user_past_info = array( 'email'=>$email , 'user_id'=>$user_id , 'domain'=>$domain , 'domain_has_change'=>$domain_has_change );
		}else{
	
			header( 'Location: /login' );
			exit;
		}

		//显示用户之前修改信息的处理结果
	 	$settings_password_info = $this->session->flashdata( 'settings_password_info' );
	 	
		$this->load->view( 'div/html_head_info' , array( 'title'=>'大智若愚 / 账号设置' ));
		$this->load->view( 'div/header' , array( 'object_user_info'=>$user_info , 'page_name'=>'settings' ) );
		$this->load->view( 'settings/account' , array( 'user_past_info'=>$user_past_info , 'settings_password_info'=>$settings_password_info ) );
		$this->load->view( 'div/footer' );
	}

	public function do_settings_account(){

		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//只有登录用户可以访问本页面
		if ( $this->user_auth->is_user_login() ){
	 	 	 
			$user_id = $this->session->userdata( 'user_id' );
		}else{
		
			header( 'Location: /login' );
			exit;
		}
		 
		//得到用户输入值
		$domain = $this->input->post( 'domain' );

		$user_input_info = array( 'domain'=>$domain );

		$this->load->library( 'User_settings' , array( 'user_input_info'=>$user_input_info , 'user_id'=>$user_id ) );
		$this->user_settings->do_settings_account();
	}

	public function do_settings_password(){
	
		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//只有登录用户可以访问本页面
		if ( $this->user_auth->is_user_login() ){
	 	 	 
			$user_id = $this->session->userdata( 'user_id' );
		}else{
		
			header( 'Location: /login' );
			exit;

		}
	
		//得到用户输入值
		$old_password = $this->input->post( 'old_password' );
		$new_password = $this->input->post( 'new_password' );

		$user_input_info = array( 'old_password'=>$old_password , 'new_password'=>$new_password );

		$this->load->library( 'User_settings' , array( 'user_input_info'=>$user_input_info , 'user_id'=>$user_id ) );
		$this->user_settings->do_settings_password();
	}

	public function snstools(){

		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//只有登录用户可以访问本页面
		if ( $this->user_auth->is_user_login() ){
	 	 	
			$user_id = $this->session->userdata( 'user_id' );
			$this->load->library( 'user_info_process' , array( 'user_id'=>$user_id ) );

			//获得用户之前的属性
			$nick = $this->user_info_process->get( 'nick' );
			$qq = $this->user_info_process->get( 'qq' );
			$tsina = $this->user_info_process->get( 'tsina' );
			$tqq = $this->user_info_process->get( 'tqq' );
			$tsohu = $this->user_info_process->get( 'tsohu' );
			$t163 = $this->user_info_process->get( 't163' );
			$custom = $this->user_info_process->get( 'custom' );
			$domain = $this->user_info_process->get( 'domain' );

			$user_info = array( 'user_id'=>$user_id , 'user_nick'=>$nick , 'domain'=>$domain );
			$user_past_info = array( 'qq'=>$qq , 'tsina'=>$tsina , 'tqq'=>$tqq , 'tsohu'=>$tsohu , 't163'=>$t163 , 'custom'=>$custom , 'domain'=>$domain );
		}else{
		
			header( 'Location: /login' );
			exit;
		}

		//显示用户之前修改信息的处理结果
	 	$settings_snstools_info = $this->session->flashdata( 'settings_snstools_info' );

		$this->load->view( 'div/html_head_info' , array( 'title'=>'大智若愚 / 社交工具' ));
		$this->load->view( 'div/header' , array( 'object_user_info'=>$user_info , 'page_name'=>'settings' ) );
		$this->load->view( 'settings/snstools' , array( 'user_past_info'=>$user_past_info , 'settings_snstools_info'=>$settings_snstools_info ) );
		$this->load->view( 'div/footer' );
	}

	public function do_settings_snstools(){
	
		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//只有登录用户可以访问本页面
		if ( $this->user_auth->is_user_login() ){
	 	 	 
			$user_id = $this->session->userdata( 'user_id' );
		}else{
		
			header( 'Location: /login' );
			exit;
		}
	
		//得到用户输入值
		$qq = $this->input->post( 'qq' );
		$tsina = $this->input->post( 'tsina' );
		$tqq = $this->input->post( 'tqq' );
		$tsohu = $this->input->post( 'tsohu' );
		$t163 = $this->input->post( 't163' );
		$custom = $this->input->post( 'custom' );

		$user_input_info = array( 'qq'=>$qq , 'tsina'=>$tsina , 'tqq'=>$tqq , 'tsohu'=>$tsohu , 't163'=>$t163 , 'custom'=>$custom );

		$this->load->library( 'User_settings' , array( 'user_input_info'=>$user_input_info , 'user_id'=>$user_id ) );
		$this->user_settings->do_settings_snstools();
	}

	//显示已经授权的应用的列表
	public function app_list(){
		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//只有登录用户可以访问本页面
		if ( $this->user_auth->is_user_login() ){
			$user_id = $this->session->userdata( 'user_id' );
			$this->load->library( 'user_info_process' , array( 'user_id'=>$user_id ) );
			//获得用户之前的属性
			$nick = $this->user_info_process->get( 'nick' );
			$domain = $this->user_info_process->get( 'domain' );
			$user_info = array( 'user_id'=>$user_id , 'user_nick'=>$nick , 'domain'=>$domain );
			//获取已授权应用列表信息
			$this->load->library( 'oauth_server' );
			$consumer_access_token_list = $this->oauth_server->get_consumer_access_token( $user_id );
		}else{
			header( 'Location: /login' );
			exit;
		}
		//显示用户之前修改信息的处理结果
	 	$settings_snstools_info = $this->session->flashdata( 'settings_snstools_info' );
		$this->load->view( 'div/html_head_info' , array( 'title'=>'大智若愚 / 社交工具' ));
		$this->load->view( 'div/header' , array( 'object_user_info'=>$user_info , 'page_name'=>'settings' ) );
		$this->load->view( 'settings/app_list' , array( 'consumer_access_token_list'=>$consumer_access_token_list ) );
		$this->load->view( 'div/footer' );
	}

	public function do_del_access_token(){
		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//只有登录用户可以访问本页面
		if ( $this->user_auth->is_user_login() ){
			$user_id = $this->session->userdata( 'user_id' );
			//获取已申请应用列表信息
			$this->load->library( 'oauth_server' );		
			$token = $this->input->get( 'token' , TRUE );
			$this->oauth_server->del_access_token( $token , $user_id );
			header( 'Location: /settings/app_list/' );
			exit;
		}else{
			header( 'Location: /login' );
			exit;
		}
	}
}

