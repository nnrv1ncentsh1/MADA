<?php
/**
 * 提供对用户信息查询更改等操作
 * 用户登录之后session中保存的便是此对象
 * @author <judasnow@gmail.com>
 */
class User_info_process{

	//用户id
	private $_user_id;
	//用于获取 user model
	private $_CI; 

	public function __construct(){
	
		$this->_CI =& get_instance();
		$this->_CI->load->model( 'User' , 'user_m' , TRUE );
	}

	public function load( $user_id  ){

		 $this->_user_id = $user_id;
		 $this->_CI->user_m->load( $user_id );
	}

	/**
	 * 返回用户全部信息
	 * @return array 
	 */
	public function get_user_all_info(){

		$nick = $this->_CI->user_m->get( 'nick' );

		//用户 nick 不可能为空 
		//先用 nick 是否为空来判断该用户是否存在
		if( empty( $nick ) ){
			//改用户不存在
			$user_info = array();
			return $user_info;
		}

		$email = $this->_CI->User->get( 'email' );
		$time = $this->_CI->User->get( 'reg_time' );
		$describe = $this->_CI->User->get( 'describe' );
		$motto = $this->_CI->User->get( 'motto' );
		$address = $this->_CI->User->get( 'address' );
		$tel = $this->_CI->User->get( 'tel' );
		$reg_time = $this->_CI->User->get( 'reg_time' );
		$domain = $this->_CI->User->get( 'domain' );
		$sys_domain = $this->_CI->User->get( 'sys_domain' );
		$loc__CIty = $this->_CI->User->get( 'loc__CIty' );
		$loc_province = $this->_CI->User->get( 'loc_province' );

		$this->_CI->load->model( 'Follow' , 'Follow_m' , TRUE );
		$this->_CI->load->model( 'Solutions' , '' , TRUE );
		//获得关注的人数
		$contacts_num = $this->_CI->Follow->get_retwetts_by_user_id( $user_id );
		//获得被关注的人数
		$rev_contacts_num = $this->_CI->Follow->get_favorites_by_user_id( $user_id );
		//获得发布的方案数

		//处理用户snstools信息到一个数组中
		$snstools['qq'] =  $this->_CI->User->get( 'qq' );
		$snstools['tsina'] = $this->_CI->User->get( 'tsina' );
		$snstools['tqq'] = $this->_CI->User->get( 'tqq' );
		$snstools['tsohu'] = $this->_CI->User->get( 'tsohu' );
		$snstools['t163'] =  $this->_CI->User->get( 't163' );
		$snstools['custom'] = $this->_CI->User->get( 'custom' );

		$user_info = array( 'email'=>$email , 'user_id'=>$this->_user_id , 'user_nick'=>$nick , 'reg_time'=>$reg_time , 'domain'=>$domain , 
			'contacts_num'=>$contacts_num , 'rev_contacts_num'=>$rev_contacts_num , 'solution_num'=>$solution_num , 'sys_domain'=>$sys_domain , 
			'describe'=>$describe , 'motto'=>$motto , 'address'=>$address , 'tel'=>$tel , 'snstools'=>$snstools , 
			'loc__CIty'=>$loc__CIty , 'loc_province'=>$loc_province );

		return $user_info;
	}
}

