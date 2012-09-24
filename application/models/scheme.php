<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
// Scheme(方案) model
// @author <judasnow@gmail.com> 
//
require_once 'base_model.php';

class Scheme extends Base_model{

        private $_CI;
        private $_scheme_id;
        const PAGE_LIMIT = 7;
        const CACHE_COUNT_MAX = 10;

        public function __construct(){
        //{{{
                parent::__construct( 'scheme' );
                $this->_CI =& get_instance();
        }//}}}

        public function set_id( $_scheme_id ){
        //{{{
                $this->_scheme_id = $_scheme_id;
        }//}}}

        //按照需求获得方案以及方案 holder 的信息
        //@return String 满足条件方案信息的 json 串
        public function find( $cond = array() , $select = '' ){
        //{{{
                $this->_CI->load->model( 'User' , 'User_m' , TRUE );
                try{
                        $raw_schemes = parent::find( $cond , $select );
                        if( empty( $raw_schemes ) ){
                                return array();
                        }
                        if( empty( $raw_schemes[0] ) ){
                                //对于仅仅返回唯一结果的查询 需要将其弄到一个数组中 
                                //以简化操作
                                $num_rows = 1;
                                $schemes[0] = $raw_schemes;
                        }else{
                                $num_rows = 0;
                                $schemes = $raw_schemes;
                        }
                        foreach( $schemes as $scheme_no=>$scheme ){
                                $holder = $this->_CI->User_m->find( array( 'user_id'=>$scheme['holder_id'] ) );
                                $schemes[$scheme_no]['holder'] = $holder;
                                $schemes[$scheme_no]['formated_time'] = $this->format_time( $scheme['time'] );
                        }
                        if( $num_rows == 1 ){
                                return $schemes[0];
                        }else{
                                return $schemes;
                        }
                }catch( RunTimeException $e ){
                        log_message( 'error' , '获取方案信息时发生异常' . $e->getMessage() );
                        throw new RunTimeException( "获取方案信息时发生异常" . $e->getMessage() );
                }
        }//}}}

        //将数组变为 csv 串
        //@array 需要处理的数组
        //@key 需要连接的键
        private function array_to_csv( $array , $key ){
        //{{{
                if( empty( $array ) ){
                        return '';
                }
                return array_reduce( $array , 
                        function( $csv , $new_one ) use( $key ) {
                                return $csv . ',' . $new_one[$key];
                        } , 0 );
        }//}}}

        //获取指定用户转发的方案id
        private function scheme_ids_from_retweet( $user_id ){
        //{{{
                $this->_CI->load->model( 'Retweet' , 'retweet_m' , TRUE );
                $retweet_infos = $this->_CI->retweet_m->find( array( 'retweeter_id'=>$user_id ) , null , TRUE );
                //取出 time(转发时间) , scheme_id 用于同一排序
                return array_map( 
                        function( $retweet_info ){
                                return array( 
                                        'scheme_id'=>$retweet_info['scheme_id'],
                                        'time'=>$retweet_info['time'],
                                        'retweet_info'=>$retweet_info
                                );
                        } ,
                        $retweet_infos
                );
        }//}}}

        //获取指定用户发布的方案id(原创信息)
        private function scheme_ids_from_original( $user_id ){
                return parent::find( array( 'holder_id'=>$user_id ) , 'scheme_id,holder_id,time' , TRUE );
        }

        //获取指定用户关注的其他用户需要显示在指定用户主页上的信息
        //(原创+转发)
        private function scheme_ids_from_following( $user_id ){
        //{{{
                $this->_CI->load->model( 'Follow' , 'follow_m' , TRUE );
                $following_ids = $this->_CI->follow_m->find( array( 'follower_id'=>$user_id ) , 'user_id' , TRUE );
                $following_ids_csv = $this->array_to_csv( $following_ids , 'user_id' );
                //获取关注用户的直接信息
                $following_original_scheme_ids = array();
                if( !empty( $following_ids ) ){
                        $following_original_scheme_ids = parent::find( 'holder_id IN ( ' . $following_ids_csv . ')' , 'scheme_id,holder_id,time' , TRUE );
                }
                //获取关注用户转发的信息
                $following_retweet_scheme_ids = array();
                foreach( $following_ids as $i=>$following_id ){
                        $following_retweet_scheme_ids = array_merge( $following_retweet_scheme_ids , $this->scheme_ids_from_retweet( $following_id['user_id'] ) );
                }
                return array_merge( $following_retweet_scheme_ids , $following_original_scheme_ids );
        }//}}}

        //根据用户提供的页号 获取home页面的相应方案信息
        //需要显示的方案信息有
        //@param $page 页码数必须大于零
        public function get_schemes_for_home( $subject_user_id = 0 , $page = 1 ){
        //{{{
                $following_scheme_ids = $this->scheme_ids_from_following( $subject_user_id );
                $original_scheme_ids = $this->scheme_ids_from_original( $subject_user_id );
                $retweet_scheme_ids = $this->scheme_ids_from_retweet( $subject_user_id );
                //合并所有的 scheme_id
                $home_scheme_ids = array_merge( $following_scheme_ids , $original_scheme_ids , $retweet_scheme_ids );
                //根据页数信息截取需要的 scheme_id
                $home_scheme_ids_in_this_page = $this->slice_by_page(
                        $this->sort_by_time( $home_scheme_ids ) ,
                        $page ,
                        self::PAGE_LIMIT
                );
                $home_schemes = array();
                foreach( $home_scheme_ids_in_this_page as $i=>$home_scheme_id ){
                        //注意合并的顺序
                        $home_schemes[$i] = array_merge( $home_scheme_id , $this->find( array( 'scheme_id'=>$home_scheme_id['scheme_id'] ) ) );
                }
                return $home_schemes;
        }//}}}

        //增加方案对应的商品到数据库中
        //@todo 似乎没有必要判断数据库中是否存在该方案
        //@param array products 二维数组
        //@return bool 
        public function add_products( $product_infos ){
        //{{{
                $this->_CI->db->insert_batch( 'product' , $product_infos );
                if( $this->_CI->db->affected_rows() != count( $product_infos ) ){
                        throw new Exception( 'SQL error with [' . $this->_CI->db->last_query() . ']' );
                }
                return TRUE;
        }//}}}

        //获取制定方案的过期时间
        public function get_expire_time( $scheme_id ){
        //{{{
                $this->_CI->config->load( 'scheme' );
                $expire_time = $this->_CI->config->item( 'expire_time' );
                $scheme = parent::find( array( 'scheme_id'=>$scheme_id ) );
                if( empty( $scheme ) ){
                        return -1;
                }
                //获取方案 发布/转发 时间
                $time = strtotime( $scheme['time'] );
                return date( 'Y-m-d h:i:s' , $time + $expire_time );
        }//}}}

        public function format_status( $scheme = array() ){
        //{{{
                if( isset( $scheme['status'] ) ){
                        switch( $scheme['status'] ){
                                case 'ONSALE':
                                        $formated_status = '进行中';
                                        break;
                                case 'EXPIRE':
                                        $formated_status = '已过期';
                                        break;
                                default:
                                        //状态值异常
                                        log_message( '方案状态值异常，方案 scheme:' . json_encode( $scheme ) );
                                        $formated_status = '已过期';
                                        break;
                        }
                        $scheme['formated_status'] = $formated_status;
                }
                return $scheme;
        }//}}}
}
