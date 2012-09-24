<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class followopt extends CI_Controller{

	/**
	 * 关注某个用户，只允许通过 ajax 调用
	 * 
	 */
	public function do_follow(){

		if( !$this->input->is_ajax_request() ){
			 
			show_404();
			return FALSE;
		}

		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//只有登录用户可以访问本页面
		if ( $this->user_auth->is_user_login() ){
	 	 	
			$user_id = $this->session->userdata( 'user_id' );
		}else{
		
			echo '{"res":"FALSE","message":"没有登录不能执行关注操作."}';
			return;
		}

		//得到需要关注用户的 id
		$another_user_id = $this->input->post( 'another_user_id' );
		$this->load->model( 'Follow' , '' , TRUE );

		$this->Follow->set_user_id( $user_id );

	 	//@todo 判断是否已经有关注关系
		if ( $this->Follow->do_follow( $another_user_id ) ){
		
			echo '{"res":"TRUE","message":"已经关注该用户"}';
		}else{
		
			echo '{"res":"FALSE","message":"系统故障请稍后再试."}';
		}
	}

	/**
	 * 取消对某用户的关注，只允许通过 ajax 调用
	 *
	 */
	public function undo_follow(){

		if( !$this->input->is_ajax_request() ){
			 
			show_404();
			return FALSE;
		}
	 
		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//只有登录用户可以访问本页面
		if ( $this->user_auth->is_user_login() ){
	 	 	 
			$user_id = $this->session->userdata( 'user_id' );
		}else{
		
		 	echo '{"res":"FALSE","message":"没有登录不能执行关注操作."}';
			return;
		}
	 
		$another_user_id = $this->input->post( 'another_user_id' );
		$this->load->model( 'Follow' , '' , TRUE );

		$this->Follow->set_user_id( $user_id );

	 	//@todo 判断是否已经有关注关系

		if( $this->Follow->undo_follow( $another_user_id ) ){
		
			echo '{"res":"TRUE","message":"已经取消关注该用户"}';
		}else{
		
			echo '{"res":"FALSE","message":"系统故障请稍后再试."}';
		}
	}

	function count_all_contacts(){
	
		if( !$this->input->is_ajax_request() ){
			 
			show_404();
			return FALSE;
		}

		$user_id = $this->input->post( 'user_id' );
		
		$this->load->model( 'Follow' , '' , TRUE );
		$this->Follow->set_user_id( $user_id );

		//得到被关注用户的信息
		$contacts_num = $this->Follow->count_all_contacts();
		echo '{"res":"TRUE","info":"' . $contacts_num . '"}';
		return;
	}
	 
	//显示指定用户关注的人
	public function contacts( $subject_user_domain ){

		//根据用户提供的个性域名获得用户的id
		$this->load->model( 'User' , '' , TRUE );
		$subject_user_id = $this->User->get_user_id_by_domain( $subject_user_domain );

		//获得当前页面所有者用户的信息
		$this->load->library( 'user_info_process' , array( 'user_id'=>$subject_user_id  ) );
		$subject_user_info = $this->user_info_process->get_user_all_info();

		if( $subject_user_id == -1 ){

			show_404();
			 
			//此用户不存在
			header( 'Location: /login');
			exit;
		}
	 	 
		//判断是否已经有用户登录
		//用于header显示
		
		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//判断用户是否已经登录系统，以显示
		//登录用户的相关信息在右上
		$is_follow = false;
		if ( $this->user_auth->is_user_login() ){
	 	 	 
			$object_user_id = $this->session->userdata( 'user_id' );
			$this->user_info_process->load( $object_user_id );

			$object_user_info = $this->user_info_process->get_user_all_info();

			$this->load->model( 'Follow' , '' , TRUE );
			$this->Follow->set_user_id( $object_user_id );

			$is_follow = $this->Follow->is_follow( $subject_user_id );
		}else{
		
			$object_user_info = array();
		}

		//根据此 id 获得该用户关注其他用户的信息
		$this->load->model( 'Follow' , '' , TRUE );
		$this->Follow->set_user_id( $subject_user_id );

		//得到被关注用户的信息
		$contacts_num = $this->Follow->count_all_contacts();

	 	//分页显示
	 	$this->load->library('pagination');

	 	$config['base_url'] = '/followopt/contacts/' . $subject_user_info['domain'].'?';
		$config['total_rows'] = $contacts_num;
		$config['per_page'] = '20'; 
		$config['page_query_string'] = TRUE;

		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';

		$config['prev_link'] = '上一页';
		$config['next_link'] = '下一页';
		$config['last_link'] = TRUE;
		$config['first_link'] = TRUE;
		$config['cur_tag_open'] = '<span>';
		$config['cur_tag_close'] = '</span>';
		$config['first_link'] = '第一页';
		$config['last_link'] = '最后一页';

		$this->pagination->initialize( $config );
	        $offset = $this->input->get( 'per_page' );	
		if( empty( $offset ) ){
		
			 $offset = 0;
		}

		$contacts = $this->Follow->get_contacts( $config['per_page'] , $offset );

		//对于已经有用户登录的页面，要判断该用户和当前登录用户
		//之间是否存在关注的关系
		if ( $this->user_auth->is_user_login() ){
	 	 	
			$object_user_id = $this->session->userdata( 'user_id' );
			$this->Follow->set_user_id( $object_user_id );
			foreach( $contacts as $no => $user_info ){
			
				$is_this_contacts_follow = $this->Follow->is_follow( $user_info['user_id'] );
	 	 	 	$contacts[$no]['is_object_user_follow'] = $is_this_contacts_follow;
			}
		}

		$this->load->view( 'div/html_head_info' , array( 'title'=>@$subject_user_info['user_nick'] . ' / 关注' , 'page_name'=>'contacts' ));
		if ( $this->user_auth->is_user_login() ){
			
			$this->load->view( 'div/header' , array( 'object_user_info'=>$object_user_info ) );
		}else{
		
			$this->load->view( 'div/welcome_header' ); 
		}	 
		$this->load->view( 'follow/contacts' , 
			array( 'contacts'=>$contacts , 'object_user_info'=>$object_user_info ,
		       	 	'subject_user_info'=>$subject_user_info , 'is_follow'=>$is_follow ) );
		$this->load->view( 'div/footer' ); 
	}

	//显示关注指定用户的人
	public function rev_contacts( $subject_user_domain ){

		//根据用户提供的个性域名获得用户的id
		$this->load->model( 'User' , '' , TRUE );
		$subject_user_id = $this->User->get_user_id_by_domain( $subject_user_domain );

		//获得当前页面所有者用户的信息
		$this->load->library( 'user_info_process' , array( 'user_id'=>$subject_user_id  ) );

		$subject_user_info = $this->user_info_process->get_user_all_info();

		if( $subject_user_id == -1 ){

	 	 	show_404();

			//此用户不存在
			header( 'Location: /login');
			exit;
		}

		//判断是否已经有用户登录
		//用于header显示
		
		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//判断用户是否已经登录系统，以显示
		//登录用户的相关信息在右上
		$is_follow = false;
		if ( $this->user_auth->is_user_login() ){
	 	 	 
			$object_user_id = $this->session->userdata( 'user_id' );
			$this->user_info_process->load( $object_user_id );

			$object_user_info = $this->user_info_process->get_user_all_info();

			$this->load->model( 'Follow' , '' , TRUE );
			$this->Follow->set_user_id( $object_user_id );

			$is_follow = $this->Follow->is_follow( $subject_user_id );
		}else{
		
			$object_user_info = array();
		}

		//根据此 id 获得该用户关注其他用户的信息
		$this->load->model( 'Follow' , '' , TRUE );
		$this->Follow->set_user_id( $subject_user_id );

		//得到被关注用户的信息数量
		$rev_contacts_num = $this->Follow->count_all_rev_contacts();

	 	//分页显示
	 	$this->load->library('pagination');

	 	$config['base_url'] = '/followopt/rev_contacts/' . $subject_user_info['domain'].'?';
		$config['total_rows'] = $rev_contacts_num;
		$config['per_page'] = '20'; 
		$config['page_query_string'] = TRUE;

		$config['full_tag_open'] = '<p>';
		$config['full_tag_close'] = '</p>';

		$config['prev_link'] = '上一页';
		$config['next_link'] = '下一页';
		$config['last_link'] = TRUE;
		$config['first_link'] = TRUE;
		$config['cur_tag_open'] = '<span>';
		$config['cur_tag_close'] = '</span>';
		$config['first_link'] = '第一页';
		$config['last_link'] = '最后一页';

		$this->pagination->initialize( $config );
	        $offset = $this->input->get( 'per_page' );	
		if( empty( $offset ) ){
		
			 $offset = 0;
		}

		$contacts = $this->Follow->get_rev_contacts( $config['per_page'] , $offset );

		//对于已经有用户登录的页面，要判断该用户和当前登录用户
		//之间是否存在关注的关系
		if ( $this->user_auth->is_user_login() ){
	 	 	
			$object_user_id = $this->session->userdata( 'user_id' );
			$this->Follow->set_user_id( $object_user_id );
			foreach( $contacts as $no => $user_info ){
			
				$is_this_contacts_follow = $this->Follow->is_follow( $user_info['user_id'] );
	 	 	 	$contacts[$no]['is_object_user_follow'] = $is_this_contacts_follow;
			}
		}

		$this->load->view( 'div/html_head_info' , array( 'title'=>@$subject_user_info['user_nick'] . ' / 粉丝' , 'page_name'=>'rev_contacts' ) );
		if ( $this->user_auth->is_user_login() ){
			
			$this->load->view( 'div/header' , array( 'object_user_info'=>$object_user_info ) );
		}else{
		
			$this->load->view( 'div/welcome_header' ); 
		}
		$this->load->view( 'follow/rev_contacts' , 
			array( 'contacts'=>$contacts , 'object_user_info'=>$object_user_info , 
				 'subject_user_info'=>$subject_user_info , 'is_follow'=>$is_follow ) );
		$this->load->view( 'div/footer' ); 
	}
} 
