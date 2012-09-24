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
		$sql = "CREATE TABLE IF NOT EXISTS `forward` (
			`forward_id` bigint(20) NOT NULL AUTO_INCREMENT,
			`solution_id` bigint(20) NOT NULL,
			`content` varchar(140) NOT NULL,
			`img` varchar(140) NOT NULL,
			`forwarder_id` bigint(20) NOT NULL,
			`time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`chain` varchar(64) NOT NULL,
			`hightlight` tinyint(1) NOT NULL,
			PRIMARY KEY (`forward_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
		$this->assertTrue( $this->_CI->db->query( $sql ) );
		say( 'Create test_forward_table ok.' );
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
		$sql = 'DROP TABLE IF EXISTS `forward`;';
		$this->assertTrue( $this->_CI->db->query( $sql ) );
		say( 'Delete test_tables ok.' );
	}

	function test_add_products(){
	}
}

$test = new test_solution_model();
$test->run( new HtmlReporter() );
