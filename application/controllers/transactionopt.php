<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class transactionopt extends CI_Controller{

	public function search(){

		$this->load->library( 'user_auth' );
		//判断用户是否已经登录系统，以显示
		//登录用户的相关信息在右上
		if ( $this->user_auth->is_user_login() ){
	 	 	
			//登录用户 id
			$object_user_id = $this->session->userdata( 'user_id' );
			$this->load->library( 'user_info_process' , array( 'user_id'=>$object_user_id ) );
			
			$object_user_info = $this->user_info_process->get_user_all_info();
		}else{
		
			//用户还没有登录
			//重定向到登录页面 
			header( 'Location: /login' );
			exit;
		}
	
		$this->load->view( 'div/html_head_info' );
		//用于头部信息显示的永远都是 subject_user_info , 即当前登录用户的信息
		$this->load->view( 'div/header' , array( 'object_user_info'=>$object_user_info , 'page_name'=>'transaction_search') );
		$this->load->view( 'transaction/search' );
		$this->load->view( 'div/footer' );
	}

	public function do_search(){

		if( !$this->input->is_ajax_request() ){
			 
			show_404();
			return FALSE;
		}

		//获得用户输入的交易码
		$transaction_code = $this->input->post( 'transaction_code' );

		//判断用户是否已经登录
		$this->load->library( 'user_auth' );

		if ( $this->user_auth->is_user_login() ){
	 		
		       	$object_user_id = $this->session->userdata( 'user_id' );	
		}else{

			echo '{"res":"未登录用户不能进行查询交易码操作."}';
			return;
		}

		//执行查询操作
		$this->load->library( 'transaction_process' , array( 'user_id'=>$object_user_id ) );
		echo $this->transaction_process->do_search( $transaction_code );
	}

	public function corfirm_for_search(){

		if( !$this->input->is_ajax_request() ){
			 
			show_404();
			return FALSE;
		}
		 
		//获得用户输入的交易码
		$transaction_code = $this->input->post( 'transaction_code' );

		//判断用户是否已经登录
		$this->load->library( 'user_auth' );

		if ( $this->user_auth->is_user_login() ){
	 		
		       	$object_user_id = $this->session->userdata( 'user_id' );	
		}else{

			echo '{"res":"未登录用户不能进行确认查询操作."}';
			return;
		}

		//执行查询操作
		$this->load->library( 'transaction_process' , array( 'user_id'=>$object_user_id ) );
		echo $this->transaction_process->corfirm_for_search( $transaction_code );
	}

	public function do_cancel( $code ){

		if( !$this->input->is_ajax_request() ){
			 
			show_404();
			return FALSE;
		}

		$this->load->library( 'user_auth' );
		//判断用户是否已经登录系统，以显示
		//登录用户的相关信息在右上
		if ( $this->user_auth->is_user_login() ){
	 	 	
			//登录用户 id
			$object_user_id = $this->session->userdata( 'user_id' );
			$this->load->library( 'user_info_process' , array( 'user_id'=>$object_user_id ) );
			
			$object_user_info = $this->user_info_process->get_user_all_info();
		}else{
		
			//用户还没有登录
			echo '{"res":false,"info":"未登录用户不能执行本操作"}';
		 	exit;		
		}
	
		//得到相应的交易码
	 	//得到相应的方案信息
		$this->db->where( 'code' , $code );
		$res = $this->db->get( 'transaction' );
	 	 
		if( $res->num_rows() < 1 ){
		
			echo '{"res":false,"info":"不存在该方案"}';
			exit;
		}

		$transaction_info = $res->result_array();

		//取消相应的交易
	 	$this->db->where( 'code' , $code );
		$this->db->delete( 'transaction' );

		//更新solution表中的相应数据（购买数）
		$this->load->model( 'Solutions' , '' , TRUE );
		$this->Solutions->load( $transaction_info[0]['solution_id'] );
	 	$this->Solutions->change( 'minus' , 'has_bought' );

		if( $this->db->affected_rows() == 1 ){

			echo '{"res":true,"info":"删除成功"}';
			exit;
		}
		
		echo '{"res":false,"info"="系统内部原因导致的删除失败，请稍后再试"}';
		exit;	
	}
}
