<?php
include getcwd() . '/application/models/Base_model.php';
class test_base_model extends UnitTestCase{

	function setUp(){
		$this->_CI =& get_instance();
		$this->base_model = new Base_model( "test_table" );
		$sql = 'CREATE TABLE IF NOT EXISTS `test_table` ( `id` int , `string` varchar(16) );';
		$this->assertTrue( $this->_CI->db->query( $sql ) );
		say( 'Create test_table ok.' );
		$sql = 'INSERT INTO `test_table` SET `id`=1 , `string`="ttttt" ;';
		$this->assertTrue( $this->_CI->db->query( $sql ) );
		say( 'Insert one row to test_table ok.' );
	}

	function tearDown(){
		$sql = 'DROP TABLE IF EXISTS `test_table`;';
		$this->assertTrue( $this->_CI->db->query( $sql ) );
		say( 'Delete test_table ok.' );
	}

	function test_count_all(){
		//依数据库中已有一条记录而断言
		$this->assertEqual( $this->base_model->count_all() , 1 );
		//删除这条记录之后断言返回0
		$sql = "TRUNCATE TABLE `test_table`;";
		$this->assertTrue( $this->_CI->db->query( $sql ) );
		say( 'Truncate test_table ok.' );
		$this->assertEqual( $this->base_model->count_all() , 0 );
	}

	function test_find(){
		//按返回数组进行一次查询 断言获取唯一一条记录
		$result_array = $this->base_model->find( array( 'string'=>'ttttt' ) );
		$this->assertEqual( $result_array['id'] , 1 );
		//对于不存在的记录，断言返回空数组
		$result_array = $this->base_model->find( array( 'string'=>'papapa' ) );
		$this->assertTrue( empty( $result_array ) );
		//对于错误的SQL语句，断言会出现一个异常
		$this->expectException();
		$result_array = $this->base_model->find( array( 'nononono'=>'tttt' ) );
	}

	function test_is_exists(){
		//对于存在的记录 断言返回TRUE
		$this->assertTrue( $this->base_model->is_exists( array( 'id'=>1 ) ) );
		//对于不存在的记录当然就断言返回FALSE
		$this->assertFalse( $this->base_model->is_exists( array( 'id'=>2 ) ) );
	}

	function test_add_new(){
		//添加一条信息到数据库中 在成功的情况下 断言可以从数据库中取出该记录
		$this->assertTrue( $this->base_model->add_new( array( 'id'=>999 , 'string'=>'papapapa' ) ) );
		//对于错误的SQL语句（主要是存在错误的属性值）断言会抛出异常
		$this->expectException();
		$result_array = $this->base_model->add_new( array( 'nononono'=>'tttt' ) );
	}

	function test_del(){
		//删除一条存在的记录
		$this->assertTrue( $this->base_model->del( array( 'id'=>1 ) ) );
		//断言被删除的记录已经不存在了
		$this->assertFalse( $this->base_model->find( array( 'id'=>1 ) ) );
	}
}

$test = new test_base_model();
$test->run( new HtmlReporter() );
