<?php
//测试 lib/user_info_process
class test_user_info_process_lib extends UnitTestCase{

	function setUp(){
		$this->_CI =& get_instance();
		$this->_CI->load->library( 'user_info_process' );
		//添加测试数据
		$sql = "INSERT INTO `user` (`user_id`, `email`, `passwd`, `nick`, `sex`, `domain`, `sys_domain`, `domain_has_change`, `describe`, `motto`, `tel`, `province`, `city`, `town`, `loc_province`, `loc_city`, `loc_town`, `address`, `reg_time`, `last_login_ip`, `birthday`, `qq`, `tsina`, `tqq`, `tsohu`, `t163`, `custom`, `x1`, `y1`, `x2`, `y2`, `status`) VALUES
(0, 'test@test.com', '96e79218965eb72c92a549dd5a330112', '111111', NULL, '4108560252', '4108560252', 0, '123123', NULL, NULL, '', '', '', '', NULL, '', NULL, '2011-07-29 11:42:45', NULL, '0000-00-00', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 'enable')";
		if( !$this->_CI->db->query( $sql ) ){
			$this->_CI->db->last_query();
		}
	}

	function tearDown(){
		//清空数据表
		$sql = 'TRUNCATE `user`;';
		$this->_CI->db->query( $sql );
	}

	function test_set_user_id(){
		$this->_CI->user_info_process->set_user_id( 0 );
	}
}

$test = new test_user_info_process_lib();
$test->run( new HtmlReporter() );
