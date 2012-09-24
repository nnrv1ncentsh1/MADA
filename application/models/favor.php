<?php
//
// Favor(用户收藏) model
//
// @author <judasnow@gmail.com>
//
require_once 'base_model.php';

class Favor extends Base_model{

        private $_CI;

        public function __construct(){
        //{{{
                parent::__construct( 'favor' );
                $this->_CI = get_instance();
        }//}}}

        //@param $record 其中包含了 user_id 以及 scheme_id 
        public function do_favor( $record ){
        //{{{
                try{
                        if( !$this->is_exists( $record ) ){
                                $this->add_new( $record );
                        }else{
                                throw new Exception( '尝试插入了重复的收藏信息.' );
                        }
                }catch( Exception $e ){
                        log_message( 'error' , '尝试插入新的收藏信息时出错.' );
                }
        }//}}}

        //@see function do_favor
        public function undo_favor( $record ){
        //{{{
                try{
                        $this->del( $record );
                }catch( Exception $e ){
                        log_message( 'error' , '删除已有收藏信息时出错.' );
                }
        }//}}}
}

