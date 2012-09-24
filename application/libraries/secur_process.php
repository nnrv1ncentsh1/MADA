<?php
/**
 * 安全处理相关类
 */
class secur_process{

	 public function __construct(){
	
	 	 $this->CI =& get_instance();
	 }

	 /**
	  * 判断用户是否能够访问，即
	  * 用户ip是否在黑名单上
	  *
	  * @return bool
	  */
	 public function is_this_client_can_access(){
	 	 
		 $this->CI->config->load( 'ip_acl' );
		 $ip_acl = $this->CI->config->item( 'ip_acl' );
		 $ip_black_list = explode( '|' , $ip_acl );
	 	 
		 $client_ip = $this->CI->input->ip_address();

		 foreach( $ip_black_list as $i=>$black_ip ){
		 
			 if( $black_ip == $client_ip ){
			 	 
				 return false;
			 }
		 }	 
		 return true;
	 }

	 /**
	  * 过滤用户输入中的敏感词
	  */
	 public function filter_user_input( $input_content ){

		 $this->CI->config->load( 'secur' );
		 $post_limit = $this->CI->config->item( 'post_limit' );
		 $sensitive_words = explode( '|' , $post_limit );
	
		 foreach( $sensitive_words as $i=>$word ){
		 
			 //执行替换
			 $input_content = str_replace( $word , '*' , $input_content );
		 }

		 return $input_content;
	 }

	 /**
	  * 判断用户个性域名是否可用
	  */
	 public function is_this_domain_valid( $user_domain ){

		 $this->CI->config->load( 'secur' );
		 $domain_limit = $this->CI->config->item( 'domain_limit' );

		 $unvalid_domains = explode( '|' , $domain_limit );
	
		 foreach( $unvalid_domains as $i=>$domain ){
		 
			 if( $domain == $user_domain ){

				 //不可用
				 return false;
			 }
		 }

		 return true;
	 }
}
