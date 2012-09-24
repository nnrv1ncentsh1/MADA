<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * 处理用户更新个人信息之请求
 *
 * @author judasnow@gmail.com
 */
class User_settings{

	/**
	 * @var array $_face_proc 用户输入的需要修改的信息
	 * @var $_user_input_info_for_update
	 */
	private $_user_input_info_for_update;

	private $_user_id;

	public function __construct( $param ){

		//获取CI实例，否则不能在自定义对象中使用CI基础类
		$this->CI =& get_instance();

		$this->_user_input_info_for_update = $param[ 'user_input_info' ];
		$this->CI->load->model( 'User' , '' , TRUE );

		//此处 user_id 应由 $param 传入
		$this->_user_id = $param['user_id'];
		$this->CI->User->load( $param['user_id'] );
	}

	/**
	 * 更新用户普通信息
	 * 对应于页面 settings/[common] 
	 *
	 * @access public 
	 * @return bool 
	 */
	public function do_settings_common(){

		//循环设置非空元素到 User_model 中
		foreach( $this->_user_input_info_for_update as $key=>$value ){

			if( !empty( $value ) ){

				$this->CI->User->set( $key , $value );
			}else{

				$this->CI->User->set( $key , null );
			}
		}

		if( !$this->CI->User->save() ){

			//保存信息出错
			$this->CI->session->set_userdata( 'settings_common_info' , array( 'res'=>FALSE , 'info'=>'更改信息出错，请稍后再试' , 'type'=>'error' ) );
			header( 'Location: /settings/' ); 
			exit;		
		}else{

			//保存成功
			//定向到 settings 页面
			$this->CI->session->set_userdata( 'settings_common_info' , array( 'res'=>TRUE , 'info'=>'个人资料更新成功' , 'type'=>'seccess' ) );
			header( 'Location: /settings/' ); 
			exit;
		}
	}

	/**
	 * 设置用户账号相关信息
	 * 对用于页面 settings/account
	 * 当前只是用来保存一次 用户个性域名
	 *
	 * @accress private 
	 */
	public function do_settings_account(){

		$domain = $this->_user_input_info_for_update['domain'];
		$this->CI->User->set( 'domain' , $domain );
		$this->CI->User->set( 'domain_has_change' , 1 );

		if( !$this->CI->User->save() ){

			//保存信息出错
			echo "save error"; 	 
		}else{
			//保存成功
			//定向到 settings/account 页面
			$this->CI->session->set_flashdata( 'settings_ok' , TRUE );
			header( 'Location: /settings/account' );
			exit;
		}
	}

	/**
	 * 更新用户登录密码
	 *
	 * @accress public 
	 */
	public function do_settings_password(){

		//判断旧密码是否正确
		$password_hash_in_db = $this->CI->User->get( 'passwd' );
		if( md5( $this->_user_input_info_for_update['old_password'] ) != $password_hash_in_db ){

			//提供的旧密码不正确
			$this->CI->session->set_flashdata( 'settings_password_info' , array( 'content'=>'提供的旧密码不正确' , 'type'=>'error' ) );
			header( 'Location: /settings/account' );
			exit;
		}

		$this->CI->User->set( 'passwd' , md5( $this->_user_input_info_for_update['new_password'] ) );

		if( !$this->CI->User->save() ){

			//保存信息出错
			die( "save error" );	 
		}else{
			//保存成功
			//定向到 settings/account 页面
			$this->CI->session->set_flashdata( 'settings_password_info' , array( 'content'=>'密码修改成功' , 'type'=>'success' ) );
			header( 'Location: /settings/account' );
			exit;
		}
	}

	public function do_settings_snstools(){

		//循环设置非空元素到 User_model 中
		foreach( $this->_user_input_info_for_update as $key=>$value ){

			if( !empty( $value ) ){

				$this->CI->User->set( $key , $value );
			}else{

				$this->CI->User->set( $key , null );
			}
		}

		if( !$this->CI->User->save() ){

			//保存信息出错
			$this->CI->session->set_flashdata( 'settings_snstools_info' , array( 'res'=>FALSE , 'info'=>'更改信息出错，请稍后再试' , 'type'=>'error' ) );
			header( 'Location: /settings/snstools' );
			exit;	 
		}else{
			//保存成功
			//定向到 settings 页面
			$this->CI->session->set_flashdata( 'settings_snstools_info' , array( 'content'=>'社交工具更新成功' , 'type'=>'success' ) );
			header( 'Location: /settings/snstools' );
			exit;
		}
	}
}
