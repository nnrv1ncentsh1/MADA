<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function say( $str ){
	echo "$str<br />";
}
class unit_test extends CI_Controller{
	function __construct(){
		parent::__construct();
		$dsn = 'mysql://test:test@172.17.0.46/test_heidelberg';
		$this->load->database( $dsn );
		//使用 simpletest ，抛弃了 CI 原生的测试类
		require_once( APPPATH . '/libraries/simpletest/unit_tester.php' );
		require_once( APPPATH . '/libraries/simpletest/reporter.php' );
		require_once( APPPATH . '/libraries/simpletest/mock_objects.php' );
		require_once( APPPATH . '/libraries/simpletest/web_tester.php' );
	}
	function index(){
		$test_name = $this->input->get( 'test_name' , TRUE );
		$test_full_path = APPPATH . '/unit_test/' . $test_name;
		if( !empty( $test_name ) && file_exists( $test_full_path ) ) {
			include( $test_full_path );
		}else{
			$this->load->helper('directory');
			$map = directory_map( APPPATH . '/unit_test/' , 1 );
			/**
			 * 显示所有可用的测试的链接
			 * 每一个函数对应于一个链接
			 */
			echo "<h2>Heisenberg unit test.</h2><hr />";
			echo "<ol>";
			foreach( $map as $no=>$test_name ){
				if( preg_match( '/^[test].*/' , $test_name ) ){
					//输出全部以test开头的测试方法的链接
					echo "<li><a href='/unit_test/?test_name=$test_name'>$test_name</a></li>";
				}
			}
			echo "</ol>";
			return;
		}
	}
}
