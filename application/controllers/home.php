<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
// 用户主页
// @author <judasnow@gmail.com>
//
class Home extends CI_Controller{
	public function index(){
	//{{{
		$page_name = 'home';
		//屏蔽不支持的浏览器
		$this->load->library( 'ie' );
		$this->ie->browserwarn();
		//用户ip是否在黑名单中
		$this->load->library( 'Secur_process' );
		if( !$this->secur_process->is_this_client_can_access() ){
			die( "ip 地址受限，不能访问本系统" );
		}
		$this->load->library( 'user_auth' );
		//判断用户是否已经登录系统，以显示
		//登录用户的相关信息在右上
		if( $this->user_auth->is_user_login() ){
			//获取当前登录用户的 id
			$object_user_id = $this->session->userdata( 'user_id' );
			$this->load->model( 'User' , 'user_m' , TRUE );
			//获取当前登录用户的信息
			$object_user_info = $this->user_m->get_user_info( $object_user_id );
			$this->load->view( 'div/html_head_info' , array( 'title'=>'大智若愚 / 首页' ) );
			//用于头部信息显示的永远都是 object_user_info , 即当前登录用户的信息
			$this->load->view( 'div/header' , array( 'object_user_info'=>$object_user_info , 'page_name'=>$page_name ) );
			//传入页面主体的用户信息 subject_user_info 为当前页面的所有者，
			//在 home 页面中也就是当前登录用户
			$this->load->view( 'home' , array( 'subject_user_info'=>$object_user_info , 'page_name'=>$page_name ) );
			$this->load->view( 'div/footer' );
		}else{
			//用户还没有登录
			//显示欢迎页面
			//header( 'Location: /welcome' );
			$this->load->view( 'div/html_head_info' );
			$this->load->view( 'welcome' );
			$this->load->view( 'div/footer' );
		}
	}//}}}
}


