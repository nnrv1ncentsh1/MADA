<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
// Follow(用户社交信息) model
// @author <judasnow@gmail.com>
//
require_once 'base_model.php';

class Follow extends Base_model{

        private $_CI;

        public function __construct(){
        //{{{
                parent::__construct( 'follow' );
                $this->_CI =& get_instance();
        }//}}}

        private function get_interval_cond(){
        
        }

        //判断提供的 user_id 是否有效
        private function is_legal_user_id( $user_id , $follower_id ){
                if( !is_numeric( $user_id ) || !is_numeric( $follower_id ) ){
                        log_message( '提供的 user_id  非法.[user=' . $user_id . ']' );
                        return FALSE;
                }else{
                        return TRUE;
                }
        }

        //通过提供的用户 id 获取用户粉数
        //@param interval 获取用户粉丝数的时间期限
        public function get_retwetts_by_user_id( $user_id , $interval = '' ){
        //{{{
                $res = $this->find( array( 'follower_id'=>$user_id ) , 'id' );
                return count( $res );
        }//}}}

        //通过提供的用户 id 获取该用户关注的人数
        //@param interval 获取用户粉丝数的时间期限
        public function get_favorites_by_user_id( $user_id , $interval = '' ){
        //{{{
                $res = $this->find( array( 'user_id'=>$user_id ) , 'id' );
                return count( $res );
        }//}}}

        //判断某用户($follower_id)是否已经关注了某用户($user_id)
        public function has_follow( $user_id , $follower_id ){
        //{{{
                if( $this->is_exists(
                        array(
                                'user_id'=>$user_id, 
                                'follower_id'=>$follower_id 
                        )
                )){
                        return TRUE;
                }else{
                        return FALSE;
                }
        }//}}}

        //@todo 执行关注操作 此处不像 api 中的相似方法 不能够进行合并
        //因为虽然 do 以及 undo 操作有部分相似 但都需要各自进行
        //很多和自身相关的操作 合并的话 是得不偿失的
        public function do_follow( $user_id , $follower_id ){
        //{{{
                if( !is_numeric( $user_id ) || !is_numeric( $follower_id ) ){
                        log_message( '使能一个用户关注所给目标用户时,提供的 user_id 非法.[user=' . $user_id . ']' );
                        return FALSE;
                }
                //判断是否已经存在相应的关注数据了
                //如果已经存在的话 则不进行任何操作 但是需要记录 log
                if( $this->has_follow( $user_id , $follower_id ) ){
                        log_message( 'error' , '尝试添加已经存在的关注关系' );
                        return TRUE;
                }else{
                        return $this->add_new(
                                array(
                                        'user_id' => $user_id ,
                                        'follower_id' => $follower_id
                                )
                        );
                }
        }//}}}

        public function undo_follow( $user_id , $follower_id ){
        //{{{
                if( !is_numeric( $user_id ) || !is_numeric( $follower_id ) ){
                        log_message( '使能一个用户关注所给目标用户时,提供的 user_id 非法.[user=' . $user_id . ']' );
                        return FALSE;
                }
                return $this->del(
                        array( 
                                'user_id' => $user_id ,
                                'follower_id' => $follower_id
                        )
                );
        }//}}}
}
