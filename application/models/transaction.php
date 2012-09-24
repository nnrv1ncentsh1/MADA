<?php
//
// Transaction (交易) Model
// @author <judasnow@gmail.com>
//
require_once 'base_model.php';
class Transaction extends Base_model{
        private $_CI;
        public function __construct(){
                parent::__construct( 'transaction' );
                $this->_CI =& get_instance();
        }
        public function count_all_by_scheme_id( $scheme_id ){
                return $this->count_all( array( 'scheme_id'=>$scheme_id ) );
        }
        public function count_paid_by_scheme_id( $scheme_id ){
                return $this->count_all( array( 'scheme_id'=>$scheme_id , 'status'=>'PAID' ) );
        }
}

