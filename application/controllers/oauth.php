<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * oauth 服务器端
 *
 * @author <judasnow@gmail.com>
 */
class oauth extends CI_Controller{
	function __construct(){
		parent::__construct();
		require_once( APPPATH . 'libraries/oauth/OAuthStore.php' );
		require_once( APPPATH . 'libraries/oauth/OAuthServer.php' );
		require_once( APPPATH . 'libraries/oauth/OAuthRequester.php' );
		$this->config->load( 'oauth' );
		$db_options = array(
			'server' => $this->config->item( 'db_server' ),	 
			'username' => $this->config->item( 'db_username' ),
			'password' => $this->config->item( 'db_password' ),
			'database' => $this->config->item( 'db_name' )
		);
		$this->oauth_store = OAuthStore::instance( 'MySQL' , $db_options );
		$this->oauth_server = new OAuthServer(); 
	}

	private function is_auth_available(){
		$this->client_uri = $this->session->userdata( 'client_uri' );
		if( empty( $this->client_uri ) ){
			die( 'client_uri is empty' );
		}
	}

	private function get_client_uri(){

		if( empty( $_SERVER[ 'HTTP_REFERER' ] ) ){
			 die( '$_SERVER[ \'HTTP_REFERER\' ]  is empty' );
		}else{
			 $spilt = preg_split( '/[\/]/' , $_SERVER[ 'HTTP_REFERER' ] );
			 return $spilt[2];
		}
	}
	 
	//申请一个新的 key 
	public function apply(){
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
		$this->load->view( 'div/html_head_info' , array( 'title'=>'大智若愚 / 申请大智若愚API key' , 'object_user_info'=>$object_user_info , 'page_name'=>'api apply' ) );
		$this->load->view( 'div/header' );
		$this->load->view( 'oauth/apply' );
		$this->load->view( 'div/footer' );
	}

	//注册一个新的 key 
	public function do_register(){
		$this->load->library( 'user_auth' );
		//客户端传入的参数
		$requester_name = $this->input->post( 'requester_name' , TRUE );
		$requester_email = $this->input->post( 'requester_email' , TRUE );
		//判断用户是否已经登录系统，若
		//未登录系统则不允许访问本页面
		if ( !$this->user_auth->is_user_login() ){
			//登录成功之后返回本页面
			header( "Location: /login?call_back={$_SERVER['HTTP_HOST']}/oauth/apply2&&requester_name=$requester_name&&requester_email=$requester_email" );
			exit();	
		}
		if( empty( $requester_name ) || empty( $requester_email ) ){
			echo "apply fail (注意表单中的必填项目)";
			return;
		}
		//当前登录用户
		$apply_user_id = $this->session->userdata( 'user_id' );
		//来自用户表单
		$consumer = array(
		    //下面两项必填
		    'requester_name'         => $requester_name,
		    'requester_email'        => $requester_email,
		    //以下均为可选
		    'callback_uri'           => $this->input->post( 'callback_uri' ),
		    'application_uri'        => $this->input->post( 'application_uri' ),
		    'application_title'      => $this->input->post( 'application_title' ),
		    'application_descr'      => $this->input->post( 'application_descr' ),
		    'application_notes'      => $this->input->post( 'application_notes' ),
		    'application_type'       => $this->input->post( 'application_type' ),
		    'application_commercial' => $this->input->post( 'application_commercial' )
		);
		//注册消费方
		$key = $this->oauth_store->updateConsumer( $consumer , $apply_user_id );
		//获取消费方信息
		$consumer = $this->oauth_store->getConsumer( $key , $apply_user_id );
		//消费方注册后得到的 App Key 和 App Secret
echo		"你的当前登录用户id : " . $apply_user_id = $consumer['user_id'];
echo		'</br>';
echo		"key : " . $consumer_key = $consumer['consumer_key'];
echo		'</br>';
echo		"私钥 : " . $consumer_secret = $consumer['consumer_secret'];

		header( "Location: /settings/app_list/" );
		exit;
	}

	/**
	 * 请求一个新的 token
	 */
	function request_token(){
		$this->oauth_server->requestToken();
	}

	//显示请求用户确认是否提供权限的
	//页面
	function authorize(){
		 //此处参数为空，不过不影响 is_user_login 函数的使用
		 $this->load->library( 'user_auth' );
		 //判断用户是否已经登录系统
		 if ( !$this->user_auth->is_user_login() ){
	 	 	 header( 'Location: /login' );
	 	 	 exit;
		 }
		 //得到由 uri 中传递而来的 consumer_key 以及
		 //没有认证的 token
		 $consumer_key = $this->input->get( 'consumer_key' );
		 $token['oauth_token'] = $this->input->get( 'oauth_token' );
		 $apply_user_id = $this->input->get( 'apply_user_id' );
		 
		 $consumer_info = $this->oauth_store->getConsumer( $consumer_key , $apply_user_id );

		 $this->load->view( 'div/html_head_info' , array( 'title'=>'大智若愚 / 授权' ) );
		 $this->load->view( 'div/header' , array( 'page_name'=>'authorize' ) );
		 //需要先得到 request_token 
		 $this->load->view( 'oauth/authorize' , 
			 array( 'apply_user_id'=>$apply_user_id , 'consumer_key'=>$consumer_key , 'token'=>$token , 'client_uri'=>$consumer_info['application_uri'] ) );
		 $this->load->view( 'div/footer' );
	}

	//请求用户授权 token
	function do_authorize(){
		//$this->is_auth_available();
		//此处参数为空，不过不影响 is_user_login 函数的使用
		$this->load->library( 'user_auth' );
		//判断用户是否已经登录系统
		if ( !$this->user_auth->is_user_login() ){
	 	 	 header( 'Location: /login' );
	 	 	 exit;
		}
echo		$apply_user_id = $this->input->post( 'apply_user_id' );
echo		$consumer_user_id = $this->session->userdata( 'user_id' );
echo 		$consumer_key = $this->input->post( 'consumer_key' );
echo 		$oauth_token = $this->input->post( 'oauth_token' );
		try{
			 //检查当前请求中是否包含一个合法的请求token
			 //返回一个数组, 包含consumer key, consumer secret, token, token secret 和 token type.
			 $rs = $this->oauth_server->authorizeVerify();
			 if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){
				 //判断用户是否点击了 "allow" 按钮(或者你可以自定义为其他标识)
				 $authorized = array_key_exists( 'allow' , $_POST );
				 $consumer_info = $this->oauth_store->getConsumer( $consumer_key , $apply_user_id );
				 $_SESSION['verify_oauth_callback'] = "{$consumer_info['application_uri']}/oauth/callback?consumer_user_id=" 
				 	 . $consumer_user_id . '&&consumer_key=' . $consumer_key . '&&oauth_token=' . $oauth_token . '&&apply_user_id=' . $apply_user_id;
				 $_GET['oauth_token'] = $_SESSION['verify_oauth_token'];
				 //设置token的认证状态(已经被认证或者尚未认证)
				 //如果存在 oauth_callback 参数, 重定向到客户(消费方)地址
				 $res = $this->oauth_server->authorizeFinish( $authorized , $consumer_user_id );
				 var_dump( $_SESSION );die;
			 }else{
				 echo 'auth fail';
			 }
		}catch ( OAuthException $e ){
			 echo $e->getMessage();
		}
	}

	//获取 access token 
	function access_token(){
		 $this->oauth_server->accessToken();
	}

}
