<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * 处理用户注册相关操作之类
 *
 * @author judasnow@gmail.com
 */
class user_reg{

	/**
	 * 用户输入的注册信息
	 * @var $_user_input_info
	 */
	private $_user_input_info;

	public function __construct( $param )
	{		
		//获取CI实例，否则不能在自定义对象中使用CI基础类
		$this->CI =& get_instance(); 
		$this->_user_input_info = $param[ 'user_input_info' ]; 
		$this->CI->load->model( 'User' , '' , TRUE );
	}

	/**
	 * 判断用户输入的两次密码相同
	 *
	 * @access private 
	 * @return bool 相同返回true，否则返回false
	 */
	private function is_two_passwd_equal(){
		 return $this->_user_input_info['passwd'] == $this->_user_input_info['check_passwd'];
	}

	/**
	 * 判断用户输出的email地址格式是否有效
	 *
	 * @access private 
	 * @return bool 格式有效返回true，否则返回false
	 */
	private function is_email_format_ok(){
		return valid_email( $this->_user_input_info[ 'email' ] ); 
	}

	/**
	 * 判断email地址在系统中的唯一性
	 * 
	 * @access public 
	 * @return bool 唯一返回true，否则返回false
	 */
	public function is_email_unique(){
		//委托user模型中的相应方法
		return $this->CI->User->is_email_unique( $this->_user_input_info['email'] );
	}

	/**
	 * 判断用户输入的昵称是否在系统
	 * 中是唯一的
	 *
	 * @access public 
	 * @return bool 
	 * @see is_email_unique
	 */
	public function is_nick_unique(){
		//委托user模型中的相应方法
		return $this->CI->User->is_nick_unique( $this->_user_input_info['nick'] );
	}

	public function is_domain_unique(){
		//委托user模型中的相应方法
		return $this->CI->User->is_domain_unique( $this->_user_input_info['domain'] );
	}

	public function is_domain_valid(){
		//委托user模型中的相应方法
		return $this->CI->User->is_domain_valid( $this->_user_input_info['domain'] );
	}

	 
	/**
	 * 进行登录操作
	 *
	 * @access public 
	 * @return bool 成功完成注册返回true，否则抛出相应的异常
	 */
	public function do_reg(){
		/**
		 * 检查用户输入参数的有效性
		 */
		//检测用户两次输入密码是否相同
		if( !$this->is_two_passwd_equal() ){
			$reg_error_info['check_passwd'] = '密码不一致，请重新输入';
		}
		//检测用户输入的email唯一性
		if( !$this->is_email_format_ok() ){
			$reg_error_info['email_format'] = 'Email格式有误，请重新输入';
		}
		//检测用户输入的email在系统中的唯一性 
		if( !$this->is_email_unique() ){
			$reg_error_info['email_unique'] = '该Email已经注册过';
		}
		if( !empty( $reg_error_info ) ){
			 $this->CI->session->set_flashdata( 'reg_error_info', $reg_error_info );
			 //重定向到reg页面并显示错误消息
			 redirect( 'reg' , 'refresh' );
		}
		//设置用户个性域名
		//默认为用户id
		//$this->_user_input_info['domain'] = hash( 'md4' , $this->_user_input_info['email'] . $_SERVER['REQUEST_TIME'] );
		//写入注册信息到数据库中
		if ( $this->CI->User->add_new( $this->_user_input_info ) ){
			//如果插入数据成功，即用户注册成功
			return true;
		}else{
			return false;
		}
	}

}
