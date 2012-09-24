<?php
class ie{

	public function __coustruct(){}
	public function browserwarn(){
		
	       /**
	 	* 整站屏蔽IE
 	 	*/
		if( @empty( $_SERVER["HTTP_USER_AGENT"] ) ||
			strpos( @$_SERVER["HTTP_USER_AGENT"] , "MSIE 6.0" ) /*|| 
			strpos( @$_SERVER["HTTP_USER_AGENT"] , "MSIE 7.0" ) */ ){

		 	header( 'Location: /browserwarn' );
			exit;
	 	}	
	}
}
