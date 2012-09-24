<?php
/**
 * User(用户) model
 *
 * @author <judasnow@gmail.com>
 */
require_once 'base_model.php';

class User extends Base_model{

        private $_CI;
        private $_user_id;

        public function __construct(){
        //{{{
                parent::__construct( 'user' );
                $this->_CI = get_instance();
        }//}}}

        public function get_user_info( $user_id ){
        //{{{
                $this->_CI->load->model( 'Scheme' , 'scheme_m' , TRUE );
                $this->_CI->load->model( 'Follow' , 'follow_m' , TRUE );
                if( !is_numeric( $user_id ) ){
                        return array();
                }
                $user_info = parent::find( array( 'user_id'=>$user_id ) );
                unset( $user_info['passwd'] );
                //获取该用户的方案总数
                $user_info['scheme_count']  = $this->_CI->scheme_m->count_all( array( 'holder_id'=>$user_id ) );
                //获取该用户的被多少人关注
                $user_info['retweets_count'] = $this->_CI->follow_m->get_retwetts_by_user_id( $user_id );
                //获取该用户关注了多少人
                $user_info['favorites_count'] = $this->_CI->follow_m->get_favorites_by_user_id( $user_id );
                return $user_info;
        }//}}}

        //根据用户的 nick 获取用户 user_id
        public function get_user_id_by_nick( $user_nick = '' ){
        //{{{
                $user_id = NULL;
                if( !empty( $user_nick ) ){
                        $user_info = parent::find( array( 'nick'=>$user_nick ) );
                        if( empty( $user_info ) ){
                                throw new Exception( "查找的用户 nick 不存在，但是其方案信息却存在于用户页面上. user_nick=[$user_nick]" );
                        }
                        if( sizeof( $user_info ) > 1 ){
                                throw new Exception( "查找的用户 nick 在数据库中存在多条记录. user_nick=[$user_nick]" );
                        }
                        $user_id = $user_info['user_id'];
                }
                return $user_id;
        }//}}}
}

