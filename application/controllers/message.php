<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class message extends CI_Controller {

	public function index()
	{
		$this->load->view( 'div/html_head_info' , array( 'title'=>'系统消息' ));
		$this->load->view( 'div/header' , array(  'page_name'=>'buy' ));

		//加载session
		$this->load->library( 'session' );

	 	//判断session中是否存在有需要 messsage页面显示的信息
		if( $message = $this->session->flashdata( 'message' ) ){

			$this->load->view( 'message' , array( 'message'=>$message ) );
		}else{
		
			$this->load->view( 'message' );
		}
		$this->load->view( 'div/footer' );
	}
}


