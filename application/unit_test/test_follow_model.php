<?php
include getcwd() . '/application/models/Base_model.php';
class test_solution_model extends UnitTestCase{

	function setUp(){
		$this->_CI =& get_instance();
		$this->_CI->load->model( 'solution_model' , '' , TRUE );
		$sql = "CREATE TABLE IF NOT EXISTS `solution` (
			  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
			  `holder_id` int(20) NOT NULL,
			  `describe` varchar(280) COLLATE utf8_unicode_ci NOT NULL,
			  `original_price` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
			  `sum_now_price` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
			  `discount` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
			  `has_bought` int(20) NOT NULL DEFAULT '0',
			  `has_paid` int(20) NOT NULL DEFAULT '0',
			  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  `status` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'onsale',
			  `disable` varchar(6) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'false',
			  PRIMARY KEY (`id`),
			  KEY `id_2` (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$this->assertTrue( $this->_CI->db->query( $sql ) );
		say( 'Create test_solution_table ok.' );
		$sql = "CREATE TABLE IF NOT EXISTS `product` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`solution_id` int(20) NOT NULL,
			`title` varchar(280) COLLATE utf8_unicode_ci NOT NULL,
			`describe` varchar(280) COLLATE utf8_unicode_ci NOT NULL,
			`original_price` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
			`time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			UNIQUE KEY `id` (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1006 ;";
		$this->assertTrue( $this->_CI->db->query( $sql ) );
		say( 'Create test_product_table ok.' );
		$sql = "INSERT INTO `solution` 
			( `title`, `holder_id`, `describe`, `original_price`, `sum_now_price`, `discount`, `has_bought`, `has_paid`, `time`, `status`, `disable`) 
			VALUES( 'test', 4, 'test', '638', '300', '4.7', 5, 1, '2011-08-27 01:26:40', 'expire', 'false') ,
				( '123', 1, '12322', '233', '11', '0.4', 0, 0, '2011-08-27 01:28:24', 'expire', 'false')";
		$this->assertTrue( $this->_CI->db->query( $sql ) );
		say( 'Insert one row to test_solution_table ok.' );
	}

	function tearDown(){
		$sql = 'DROP TABLE IF EXISTS `solution`;';
		$this->assertTrue( $this->_CI->db->query( $sql ) );
		say( 'Delete test_solution_model ok.' );
	}

	function test_add_products(){
		//断言对于一个已经存在的方案 可以向其中添加商品
		//设置关联的服务编号
		$this->_CI->solution_model->set_solution_id( '4' );
		$test_product_info = array( '0'=>array( 'solution_id'=>'4' , 'original_price'=>'123' , 'title'=>'123' , 'describe'=>'321' ) );
		$this->assertTrue( $this->_CI->solution_model->add_products( $test_product_info ) );
		//断言可以处理同时插入多个商品
		$test_product_infos[] = array( 'solution_id'=>'4' , 'original_price'=>'123' , 'title'=>'123' , 'describe'=>'321' );
		$test_product_infos[] = array( 'solution_id'=>'4' , 'original_price'=>'3' , 'title'=>'4' , 'describe'=>'555' );
		$this->assertTrue( $this->_CI->solution_model->add_products( $test_product_infos ) );
	}

	function test_get_solutions(){
	
	}
}

$test = new test_solution_model();
$test->run( new HtmlReporter() );
