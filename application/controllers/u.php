<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 登录用户首页 Controller
 * @author <judasnow@gmail.com>
 */
class u extends CI_Controller {
	public function index( $domain ){
		$this->load->library( 'ie' );
		$this->ie->browserwarn();
		//判断 用户 ip 是否在黑名单内
		$this->load->library( 'Secur_process' );
		if( !$this->secur_process->is_this_client_can_access() ){
			die( "ip 地址受限，不能访问本站" );
		}
		//得到 url 中的域名，判断用没有用户
		//与其相关联
		$this->load->model( 'User' , 'user_m' , TRUE );
		$subject_user_id = $this->user_m->get_user_id_by_domain( $domain );
		//判断用户是否存在
		if( $subject_user_id == -1 ){
			show_404();
			exit;
		}
		$this->load->library( 'user_auth' );
		//判断用户是否登录
		if ( $this->user_auth->is_user_login() ){
			//当前登录用户的信息
			$object_user_id = $this->session->userdata( 'user_id' );
			$object_user_info = $this->user_m->find( array( 'user_id'=>$object_user_id  ) );
			$this->load->model( 'Follow' , 'follow_m' , TRUE );
			$this->follow_m->set_user_id( $object_user_id );
			$is_follow = $this->follow_m->is_follow( $subject_user_id );
		}else{
			//如果没有登录，则不作判断，但是会显示关注标签
			//用户点击之后 会提示用户登录
			$is_follow = FALSE;
			$object_user_info = array();
		}
		//显示该用户的 sale 页面
		$subject_user_info = $this->user_m->find( array( 'user_id'=>$subject_user_id ) );
		//得到数据库中本用户的
		//以及本用户关注的其他人的最新
		//方案
		$this->load->model( 'Solutions' , 'solutions_m' , TRUE );
		//得到最新方案
		//onsale
		$onsale_solutions_info = $this->Solutions->get_solutions_for_sale_page( 'onsale' , 10 , 0 , $subject_user_id );
		$onsale_solutions = $onsale_solutions_info[ 'solutions' ];
		$onsale_min_id = $onsale_solutions_info[ 'min_id' ];
		$this->session->set_userdata( 'onsale_min_id' , $onsale_min_id );
		//expire 
		$expire_solutions_info = $this->Solutions->get_solutions_for_sale_page( 'expire' , 10 , 0 , $subject_user_id );
		$expire_solutions = $expire_solutions_info[ 'solutions' ];
		$expire_min_id = $expire_solutions_info[ 'min_id' ];
		$this->session->set_userdata( 'expire_min_id' , $expire_min_id );
		$title = isset( $subject_user_info['user_nick'] ) ? $subject_user_info['user_nick'] : $subject_user_info['sys_domain'] ;
		$this->load->view( 'div/html_head_info' , array( 'title'=>$title ) );
		if ( $this->user_auth->is_user_login() ){
			$this->load->view( 'div/header' , array( 'object_user_info'=>$object_user_info , 'page_name'=>'sale' ) );
		}else{
			$this->load->view( 'div/welcome_header' );
		}
		$this->load->view( 'sale' , array( 'du_solutions'=>array( 'onsale'=>$onsale_solutions , 'expire'=>$expire_solutions ) , 'page_name'=>'sale' ,
			'subject_user_info'=>$subject_user_info ,'is_follow'=>$is_follow ) );
		$this->load->view( 'div/footer' );
	}
}
