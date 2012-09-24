<?php
//测试 scheme model
class test_scheme_model extends UnitTestCase{

	function setUp(){
		$this->_CI =& get_instance();
		$this->_CI->load->model( 'Scheme' , 'scheme_m' , TRUE );
		//添加测试数据
		$sql = "INSERT INTO `scheme`
			( `id` , `title`, `holder_id`, `describe`, `original_price`, `sum_now_price`, `discount`, `has_bought`, `has_paid`, `time`, `status`, `disable`) 
			VALUES( '4' , 'test', 4, 'test', '638', '300', '4.7', 666, 666, '2011-08-27 01:26:40', 'expire', 'false') ,
				( '5' ,'123', 1, '12322', '233', '11', '0.4', 0, 0, '2011-08-27 01:28:24', 'expire', 'false')";
		if( !$this->_CI->db->query( $sql ) ){
			$this->_CI->db->last_query();
		}
	}

	function tearDown(){
		//清空数据表
		$sql = 'TRUNCATE `scheme`;';
		$this->_CI->db->query( $sql );
		$sql = 'TRUNCATE `product`';
		$this->_CI->db->query( $sql );
		$this->_CI->scheme_m->set_id( '' );
	}

	//测试新建一个 scheme_model 并设置其 scheme_id
	function test_set_scheme_id_to_schmem_model(){
		$this->_CI->scheme_m->set_id( '1' );
	}
	
	function test_add_products(){
		//断言对于一个已经存在的方案 可以向其中添加商品
		//设置关联的服务编号
		$test_product_info = array( '0'=>array( 'scheme_id'=>'4' , 'original_price'=>'123' , 'title'=>'123' , 'describe'=>'321' ) );
		$this->assertTrue( $this->_CI->scheme_m->add_products( $test_product_info ) );
		//断言可以处理同时插入多个商品
		$test_product_infos[] = array( 'scheme_id'=>'4' , 'original_price'=>'123' , 'title'=>'123' , 'describe'=>'321' );
		$test_product_infos[] = array( 'scheme_id'=>'4' , 'original_price'=>'3' , 'title'=>'4' , 'describe'=>'555' );
		$this->assertTrue( $this->_CI->scheme_m->add_products( $test_product_infos ) );
	}
}

$test = new test_scheme_model();
$test->run( new HtmlReporter() );
