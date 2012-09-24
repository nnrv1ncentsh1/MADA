<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class buy extends CI_Controller {

	public function index(){
	 	 
		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//判断用户是否已经登录系统，以显示
		//登录用户的相关信息在右上
		if ( $this->user_auth->is_user_login() ){
	 	 	 
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
		//bought 
		$bought_solutions_info = $this->Solutions->get_solutions_for_buy_page( 'bought' , 10 , 0 , $object_user_id );
		$bought_solutions = $bought_solutions_info[ 'solutions' ];
		$bought_min_id = $bought_solutions_info[ 'min_id' ];

		$this->session->set_userdata( 'bought_min_id' , $bought_min_id );

		//onsale 
	 	$paid_solutions_info = $this->Solutions->get_solutions_for_buy_page( 'paid' , 10 , 0 , $object_user_id );
		$paid_solutions = $paid_solutions_info[ 'solutions' ];
		$paid_min_id = $paid_solutions_info[ 'min_id' ];

		$this->session->set_userdata( 'paid_min_id' , $paid_min_id ); 
	 	
		$this->load->view( 'div/html_head_info' , array( 'title'=>'大智若愚 / 购买' ) );
		$this->load->view( 'div/header' , array( 'object_user_info'=>$object_user_info , 'page_name'=>'buy' ) );
		//当前登录用于就是页面的所有者
		$this->load->view( 'buy' , array( 'du_solutions'=>array( 'bought'=>$bought_solutions , 'paid'=>$paid_solutions ) , 'page_name'=>'buy' ,
			'subject_user_info'=>$object_user_info ) );
		$this->load->view( 'div/footer' );
	}

	/**
	 * 得到更多的方案
	 * 
	 * @param string $stat 需要获取的方案的状态
	 */
	public function get_more_solution( $object_user_id , $stat ){

		if( !$this->input->is_ajax_request() ){
			 
			show_404();
			return FALSE;
		}
	
		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//判断用户是否已经登录系统，以显示
		//登录用户的相关信息在右上
		if ( $this->user_auth->is_user_login() ){
	 	 	 
			$object_user_id = $this->session->userdata( 'user_id' );

		}else{
	
			echo json_encode( array() );
			exit;
		}

		$this->load->model( 'Solutions' , '' , TRUE );
		//得到最新方案编号
		$min_id = $this->session->userdata( $stat . '_min_id' );

		//没有了
		if( $min_id <= 1 ){

			echo json_encode( array() );
			exit();
		}

		$solutions_info = $this->Solutions->get_solutions_for_buy_page( $stat , 10 , $min_id , $object_user_id );
		$solutions = $solutions_info[ 'solutions' ];
		$min_id = $solutions_info[ 'min_id' ];

		//更新最小方案编号
		$this->session->set_userdata(  $stat . '_min_id' , $min_id );

		echo json_encode( $solutions );
	}
}


