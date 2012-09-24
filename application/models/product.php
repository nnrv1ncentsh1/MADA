<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
// Product (服务所属的商品) Model
// @author <judasnow@gmail.com>
//
require_once 'base_model.php';
class Product extends Base_model{
        private $_CI;
        public function __construct(){
                parent::__construct( 'product' );
                $this->_CI =& get_instance();
        }

        //
        //一次插入方案所有相关的记录
        //@param $products array
        //@param $scheme_id int 相关的方案 id
        public function add_news( $products , $scheme_id ){
                foreach( $products as $index=>$product ){
                        //describe 是可选的
                        if( empty( $product['title'] ) || empty( $product['original_price'] ) /* || empty( $product['describe'] ) */ ){
                                continue;
                        }
                        $product['scheme_id'] = $scheme_id;
                        $this->add_new( $product );
                }
        }
}
