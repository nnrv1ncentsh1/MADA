<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * oauth server 
 *
 * @author judasnow@gmail.com
 */
class Oauth_server{

	private $_CI;

	function __construct(){

		require_once( APPPATH . 'libraries/oauth/OAuthStore.php' );
		require_once( APPPATH . 'libraries/oauth/OAuthServer.php' );
		require_once( APPPATH . 'libraries/oauth/OAuthRequester.php' );	 
		$this->_CI =& get_instance();
		$this->_CI->config->load( 'oauth' );
		$db_options = array(
			'server' =>$this->_CI->config->item( 'db_server' ),	 
			'username' => $this->_CI->config->item( 'db_username' ),
			'password' => $this->_CI->config->item( 'db_password' ),
			'database' => $this->_CI->config->item( 'db_name' )
		);
		$this->oauth_store = OAuthStore::instance( 'MySQL' , $db_options );
		$this->oauth_server = new OAuthServer(); 
	}
	
	//列出当前用户授权应用列表
	public function get_consumer_access_token( $user_id ){
		 $token_list_of_this_user = $this->oauth_store->listConsumerTokens( $user_id );
		 //获取状态为access的token
		 foreach( $token_list_of_this_user as $no=>$token_of_this_user ){
			 $access_token_info_list[] = $this->oauth_store->getConsumerAccessToken( $token_of_this_user['token'] , $user_id );
		 }
		 return is_array( @$access_token_info_list ) ? $access_token_info_list : '';
	}

	public function del_access_token( $token , $user_id ){
		return $this->oauth_store->deleteConsumerAccessToken( $token , $user_id );
	}
}
