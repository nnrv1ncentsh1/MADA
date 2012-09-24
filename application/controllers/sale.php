<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sale extends CI_Controller {

	/**
	 * 显示用户所有出售方案的页面
	 */
	public function index(){

		$this->load->library( 'ie' );
		$this->ie->browserwarn();

		/**
		 * 判断 用户 ip 是否在黑名单内
		 */
		$this->load->library( 'Secur_process' );
		if( !$this->secur_process->is_this_client_can_access() ){
		
			 die( "ip 地址受限，不能访问本系统" );
		}

		$this->load->library( 'user_auth' );
		//判断用户是否已经登录系统，以显示
		//登录用户的相关信息在右上
		if ( $this->user_auth->is_user_login() ){

			//登录用户的 id
			$object_user_id = $this->session->userdata( 'user_id' );
			$this->load->library( 'user_info_process' , array( 'user_id'=>$object_user_id ) );

			$object_user_info = $this->user_info_process->get_user_all_info();
		}else{
		
			//用户还没有登录
			header( 'Location: /login' );
			exit;
		}

		$this->load->model( 'Solutions' , '' , TRUE );
		
		//得到最新方案
		//onsale 
		$onsale_solutions_info = $this->Solutions->get_solutions_for_sale_page( 'onsale' , 10 , 0 , $object_user_id );
		$onsale_solutions = $onsale_solutions_info[ 'solutions' ];
		$onsale_min_id = $onsale_solutions_info[ 'min_id' ];

		$this->session->set_userdata( 'onsale_min_id' , $onsale_min_id );

		//expire 
	 	$expire_solutions_info = $this->Solutions->get_solutions_for_sale_page( 'expire' , 10 , 0 , $object_user_id );
		$expire_solutions = $expire_solutions_info[ 'solutions' ];
		$expire_min_id = $expire_solutions_info[ 'min_id' ];

		$this->session->set_userdata( 'expire_min_id' , $expire_min_id );
	 	
		$this->load->view( 'div/html_head_info' , array( 'title'=>'大智若愚 / 出售' ));
		$this->load->view( 'div/header' , array( 'object_user_info'=>$object_user_info , 'page_name'=>'sale' ) );
		//当前登录用户便是本页面的所有者,因此subject_user_info = object_user_info 
		$this->load->view( 'sale' , array( 'du_solutions'=>array( 'onsale'=>$onsale_solutions , 'expire'=>$expire_solutions ) ,
		       	'subject_user_info'=>$object_user_info , 'page_name'=>'sale' ) );
		$this->load->view( 'div/footer' );
	}

	/**
	 * 得到该用户更多的方案
	 *
	 * @param string $stat 需要获得方案的具体状态
	 */
	public function get_more_solution( $subject_user_id , $stat ){

		if( !$this->input->is_ajax_request() ){

			show_404();
			return FALSE;
		}

		$this->load->library( 'user_auth' );

		/*if ( !$this->user_auth->is_user_login() ){
			 
			//对于没有登录的用户,不返回任何结果
			echo json_encode( array() );
			exit;
		}*/

		$this->load->model( 'Solutions' , '' , TRUE );
		//得到最新方案编号
		$min_id = $this->session->userdata( $stat . '_min_id' );

		//没有了
		if( $min_id <= 1 ){
		
			echo json_encode( array() );
			exit();
		}

		//传入的用户id 为当前登录的用户id,即 object_user_id 
		$solutions_info = $this->Solutions->get_solutions_for_sale_page( $stat ,  10 , $min_id , $subject_user_id );
		$solutions = $solutions_info[ 'solutions' ];
		$min_id = $solutions_info[ 'min_id' ];

		//更新最小方案编号
		$this->session->set_userdata(  $stat . '_min_id' , $min_id );

		echo json_encode( $solutions );
	}
}


