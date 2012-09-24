<?php
include getcwd() . '/application/models/Base_model.php';
class test_solution_model extends UnitTestCase{

	function setUp(){
		$this->_CI =& get_instance();
		$this->load->library( "picture_process" );
	}
	function tearDown(){}
	//测试上传一个图片
	function test_upload_file(){}
	//测试对于一个非jpg图片的格式转换处理
	function test_tran_format(){}
}

$test = new test_solution_model();
$test->run( new HtmlReporter() );
