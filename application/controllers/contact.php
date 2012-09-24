<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class contact extends CI_Controller {

	public function index(){

		$this->load->library( 'ie' );
		$this->ie->browserwarn();
	 	 
	 	//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );

		//判断用户是否已经登录系统，若已经
		//登录系统则不允许访问本页面
		if ( $this->user_auth->is_user_login() ){
		
			$object_user_id = $this->session->userdata( 'user_id' );
			$this->load->library( 'user_info_process' , array( 'user_id'=>$object_user_id ) );
			$object_user_info = $this->user_info_process->get_user_all_info();

		}else{
		
			$object_user_info = array();
		}
		
		$this->load->view( 'div/html_head_info' , array( 'title'=>'大智若愚 / 联系我们' , 'object_user_info'=>$object_user_info , 'page_name'=>'about' ) );

		$this->load->view( 'contact' );

		$this->load->view( 'div/footer' );
	}
}

