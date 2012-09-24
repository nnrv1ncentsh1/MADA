<?php
/**
 * 处理方案相关的操作
 *
 * @author judasnow@gmail.com
 */
class solution_process{

	private $CI;
	private $_solution_id;

	public function __construct( $param ){

		$this->CI =& get_instance();

		$this->CI->load->model( 'Solutions' , '' , TRUE );

		$this->_solution_id = $param['solution_id'];
	 	$this->CI->Solutions->load( $this->_solution_id );
	}

	/**
	 * 判断是否为自己买自己的商品
	 *
	 * @return bool 
	 */
	public function check_solution_holder( $user_id ){

		//获得当前方案的id
		$holder_id = $this->CI->Solutions->get( 'holder_id' );
		if( $user_id == $holder_id ){
			 
			//购买者id 同该方案所有者相同，判定为同一用户
			//拒绝购买操作
			return false;
		}
		
		return true;
	} 

	/**
	 * 查看当前购买方案是否已经过期
	 *
	 * @return bool
	 */
	public function check_solution_expire(){

		return $this->CI->Solutions->check_solution_expire( $this->_solution_id );
	}
}
