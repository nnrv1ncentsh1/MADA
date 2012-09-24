<?php
/**
 * 处理用户交易的相关操作
 * 
 * @author judasnow@gmail.com
 */
class Transaction_process{

	/**
	 * 交易编号
	 */
	private $_transaction_id;

	/**
	 * 涉及的方案编号
	 */
	private $_solution_id;

	/**
	 * 涉及的用户 id
	 */
	private $_user_id;

	private $CI;

	public function __construct( $param ){
		
	 	$this->CI =& get_instance();
		$this->CI->load->model( 'Transaction' , '' , TRUE );

		//设置了 solution_id 才初始化相应的 process 类 
		if( isset( $param['solution_id'] ) ){
		
			$this->_solution_id = $param['solution_id'];
			$this->CI->load->library( 'solution_process' , array( 'solution_id'=>$this->_solution_id ) );
		}
	 	 
		if( isset( $param['user_id'] ) ){
			
			$this->_user_id = $param['user_id'];
			$this->CI->Transaction->set_user_id( $this->_user_id ); 
		}
	}
	 
	/**
	 * 查看本方案是否可用，
	 * 即是否已经过期
	 *
	 * @return bool 未过期则返回true，反之则返回false
	 */
	private function check_solution_expire(){
	 	 
	 	 return $this->CI->solution_process->check_solution_expire();
	}

	/**
	 * 查看用户是否尝试购买自己
	 * 的方案
	 *
	 * @param string $this->_user_id 购买者用户id
	 * @return bool
	 */
	private function check_solution_holder(){
	
		 return $this->CI->solution_process->check_solution_holder( $this->_user_id );
	}
	 
	/**
	 * 判断本次购买操作是否可以执行
	 *
	 */
	private function is_it_can_be_bought(){

		if( !$this->check_solution_holder() ){
			 
			 //不能购买自己发布的方案
			 return '{"res":false , "info":"不能购买自己发布的方案"}';
		}

	 	//判断当前方案是否已经过期
		if( !$this->check_solution_expire() ){
			
			//已经过期
			return '{"res":false , "info":"本方案已经过期"}';
		}

		return '{"res":true}';
	}
	 
	/**
	 * 产生一个交易
	 */
	public function do_buy(){

		 $check_result_json = $this->is_it_can_be_bought();
		 $check_result = json_decode( $check_result_json , TRUE );

		 if( !$check_result['res'] ){
		 	 //未满足购买条件
		 	 return $check_result_json;
		 }

		 $this->CI->Transaction->set( 'solution_id' , $this->_solution_id );
		 $code = $this->CI->Transaction->create_code();

		 //插入数据到交易表中
		 $insert_transaction = $this->CI->Transaction->db->insert( 'transaction' ,
			 array( 'buyer_id'=>$this->_user_id , 'solution_id'=>$this->_solution_id , 'code'=>$code ) );

		 //更新solution表中的购买数
		 $update_solution = $this->CI->Solutions->change( 'plus' , 'has_bought' );

		 if( $insert_transaction && $update_solution ){
			 
			 //购买成功
	 	 	 return '{"res":true,"info":"购买成功."}';
		 }else{
			 
			 //操作失败
			 return '{"res":true,"info":"购买操作失败，请稍后再试."}';	 
		 }
	}

	/**
	 * 根据用户提供的 交易code 
	 * 查询相应的交易是否存在
	 *
	 * @return bool
	 */
	public function do_search( $transaction_code ){

		if( empty( $transaction_code ) ){
		
			return '{"res":false,"info":"编码不能为空."}';
		}

		//判断用户是否已经登录
		$this->CI->load->library( 'user_auth' );

		if ( $this->CI->user_auth->is_user_login() ){
	 		
		       	$object_user_id = $this->CI->session->userdata( 'user_id' );	
		}else{

			echo '{"res":"未登录用户不能进行查询操作."}';
			return;
		}
	
		//根据交易码，以及卖方id （当前登录用户id）获得交易信息
		
		//根据用户id获得其所有的方案信息
		$this->CI->db->where( 'holder_id' , $object_user_id );
		$res = $this->CI->db->get( 'solution' );
		$solution_infos = $res->result_array();
		//仅取出id
		$solution_ids = array( '' );
		foreach( $solution_infos as $no=>$solution_info ){
				 
			 $solution_ids[$no] = $solution_info['id'];
		}

		$this->CI->db->where( 'code' , $transaction_code  );
		$this->CI->db->where_in( 'solution_id' , $solution_ids );
		$res = $this->CI->db->get( 'transaction' );
	 	 
		//echo $this->CI->db->last_query();
		//#todo 用户重复购买现象 ..
		if( $res->num_rows() == 0 ){
		
			return '{"res":false,"info":"编码指定的交易不存在."}';
		}

		$row = $res->result_array();

		//得到并判断交易的状态
		$tran_status = $row[0]['status'];
		if( $tran_status != 'bought' ){
		
			//交易不是 已购买 状态
			return '{"res":false,"info":"本交易已经结束."}';
		}

		//判断交易是否已经过期
		
		//分别得到当前日期以及交易产生的
		//日期
		$tran_time = strtotime( $row[0]['time'] );
		$now_time = $_SERVER['REQUEST_TIME'];
		//读取交易时限
		$this->CI->config->load( 'tran' );
		$tran_expire = $this->CI->config->item( 'transaction_expire_time' );
		if( ( $now_time - $tran_time ) >= $tran_expire ){		 
	
			//已经过期，在系统中标记为过期
			$this->CI->Transaction->db->where( 'id' , $row[0]['id'] );
			//并删除编码
			$this->CI->Transaction->db->update( 'transaction' , array( 'status'=>'expire' , 'code'=>'' ) );

			return '{"res":false,"info":"本交易已经过期."}'; 
		}

		//得到并判断当前用户是否为本次交易的
		//卖家
	 	 
		//先得到 solution_id
		$tran_solution_id = $row[0]['solution_id'];
		$buyer_id = $row[0]['buyer_id'];
		//根据 solution_id 得到相应方案的所有人 holder_id 
		$this->CI->load->model( 'Solutions' , '' , TRUE );
		$this->CI->Solutions->load( $tran_solution_id );

		$holder_id = $this->CI->Solutions->get( 'holder_id' );

		if( $holder_id != $this->_user_id ){
		
			//方案所有人不是当前登录的用户
			return '{"res":false,"info":"没有权限查看本次交易信息."}';
		}

		$solution_info = $this->CI->Solutions->get_all_propertites();

		//可以放到Solution 类中
		//得到相关商品信息
		$this->CI->db->where( 'solution_id' , $tran_solution_id  );
		$res = $this->CI->db->get( 'product' );
		$product_infos = $res->result_array();
		//var_dump( $product_infos );
		//得到相关买家信息
		$this->CI->db->where( 'user_id' , $buyer_id );
		$res =$this->CI->db->get( 'user' );
		$row = $res->result_array();
		$buyer_info = $row[0];

		//交易可以成功进行

		//返回交易涉及的方案信息
		echo json_encode( array_merge( 
			array( 'buyer_info'=>$buyer_info ) , $solution_info , array( 'product_infos'=>$product_infos ) , array( 'res'=>true , 'info'=>'查询成功.' ) ) );
	}

	public function corfirm_for_search( $transaction_code ){

		//根据交易码，获得交易信息
		$res = $this->CI->Transaction->db->get_where( 'transaction' , array( 'code'=>$transaction_code ) );

		if( $res->num_rows() != 1 ){
		
			return '{"res":false,"info":"编码指定的交易不存在."}';
		}

		$row = $res->result_array();
		$this->_solution_id = $row[0]['solution_id'];	

		//得到并判断交易的状态
		$tran_status = $row[0]['status'];
		if( $tran_status != 'bought' ){
		
			//交易不是 已购买 状态
			return '{"res":false,"info":"本交易已经结束."}';
		}
	
		//修改交易状态为 已付款
	 	$this->CI->Transaction->db->where( 'id' , $row[0]['id'] );
		$this->CI->Transaction->db->update( 'transaction' , array( 'status'=>'paid' , 'code'=>'' ) );

		//更新solution表中的支付数
		$this->CI->load->model( 'Solutions' , '' , TRUE );
		$this->CI->Solutions->load( $this->_solution_id );
		$this->CI->Solutions->change( 'plus' , 'has_paid' );
	 	return '{"res":true}';
	}
}
