<?php
session_start();
//
// 测试 (retweet) 转发 api
// @author <judasnow@gmail.com>
//
require_once( getcwd() . "/application/controllers/api/retweet_api.php" );
class test_api_retweet extends UnitTestCase{
	function setUp(){
		$this->_CI =& get_instance();
		$this->api = new retweet_api();
	}
	function tearDown(){
	}
	//成功获取一条转发信息
	function test_get_one_retweet_info_ok(){
		$_GET = array( 'retweet_id'=>1 );
	}
	//获取转发信息失败	
	//成功添加一条转发信息（一转）
	function test_add_new_retweet_ok(){
		$_POST = array( 
			'scheme_id'=>1,
			'content'=>'for test',
			'retweeter_id'=>'1',
		);
		$this->assertTrue( $this->api->info_post() );
	}
	//添加一条转发信息操作失败
}
$test = new test_api_retweet();
$test->run( new HtmlReporter() );


