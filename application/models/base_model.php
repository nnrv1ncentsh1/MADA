<?php
//
// 基础的模型类
// 派生时确定数据库的表名，以及相应的类名
// 必须相同,比如 user 表对应于 user 类
//
// @todo 添加 memcache 的支持
// @author <judasnow@gmail.com>
//
//abstract
class Base_model{

        private $_CI;
        private $_table_name;

        public function __construct( $table_name ){
        //{{{
                $this->_CI =& get_instance();
                $this->_table_name = $table_name;
                $this->_CI->load->library( 'Memcached_lib' , '' , 'memcache' );
        }//}}}

        protected function get_query_hash( $cond = array() , $select = '' ){
        //{{{
                if( is_array( $cond ) ){
                        $hash = hash( 'md4' , implode( '' , $cond ) . $select . $this->_table_name );
                }else{
                        $hash = hash( 'md4' , $cond . $select . $this->_table_name );
                }
                return $hash;
        }//}}}

        //计算该数据表中符合要求的记录的个数
        //@param array $cond 需要符合的条件
        //@return int 符合条件的记录的个数
        public function count_all( $cond = array() ){
        //{{{
                $this->_CI->db->where( $cond );
                try{
                        $count = $this->_CI->db->count_all_results( $this->_table_name );
                }catch( Exception $e ){
                        //发生异常返回 0
                        $count = 0;
                        log_message( '统计总数时发生异常, sql with [' . $this->_CI->db->last_query() . ']' );
                }
                return $count;
        }//}}}

        //执行查询，若有错误发生，则抛出异常
        private function do_query( $cond ){
        //{{{
                $this->_CI->db->where( $cond );
                $query_result = $this->_CI->db->get( $this->_table_name );
                if( empty( $query_result ) ){
                        throw new RunTimeException( 'SQL error with [' . $this->_CI->db->last_query() . ']' );
                }
                return $query_result;
        }//}}}
        
        //根据提供的条件在数据库中
        //查询相应的记录信息，并生
        //成相应的记录的对象
        //@param array $cond 查找记录的条件
        //会直接应用于 sql 的 where 子句
        //@param string $select 对应于 select 子句
        //@param bool return_dyadic_array 当返回结果只有 1 条记录时，是否仍然返回 2 维数组, 默认不
        //@param bool cache $cache 是否按照查询条件缓存之 默认为缓存全部查询
        //@return mixed 查找用户的结果，如果结果唯一，则返回唯一的记录对象
        //否则返回全部对象的一个数组
        public function find( $cond = array() , $select = '' , $return_dyadic_array = FALSE , $cache = FALSE ){
        //{{{
                //尝试从 cache 中获取结果
                if( $cache == TRUE ){
                        $query_hash = $this->get_query_hash( $cond , $select );
                        $row = $this->_CI->memcache->get( $query_hash );
                        if( !empty( $row ) ){
                                return $row;
                        }
                }
                if( !empty( $select ) ){
                        $this->_CI->db->select( $select );
                }
                $query_result = $this->do_query( $cond );
                $num_rows = $query_result->num_rows();
                $row = $query_result->result_array();
                //判断返回的结果是一条还是多条
                if( $num_rows == 1 && !$return_dyadic_array ){
                        if( $cache == TRUE ){
                                $this->_CI->memcache->set( $query_hash , $row[0] );
                        }
                        return $row[0];
                }else{
                        if( $cache == TRUE ){
                                $this->_CI->memcache->set( $query_hash , $row );
                        }
                        return $row;
                }
        }//}}}

        //测试系统中满足某一条件的记录是否
        //已经存在
        //@param array $cond 需要满足的条件
        //@return bool 不存在返回FALSE，否则返回TRUE ( 如果存在 id 属性则返回 id )
        public function is_exists( $cond ){
        //{{{
                $res = $this->do_query( $cond );
                $row = $res->result_array();
                if( $res->num_rows() == 0 ){
                        return FALSE;
                }else{
                        return isset( $row['id'] ) ? $row['id'] : TRUE;
                }
        }//}}}

        //添加一条信息到数据库中
        //@param array $info 需要写入的记录
        //@return bool 写入成功则返回 TRUE，否则返回 FALSE ( 判断的标准为 查看影响的行数 是否为 1 ) 
        public function add_new( $info ){
        //{{{
                $this->_CI->db->insert( $this->_table_name , $info );
                $affected_rows = $this->_CI->db->affected_rows();
                if( $affected_rows != 1 ){
                        throw new Exception( "SQL error with SQL [" . $last_query = $this->_CI->db->last_query() . ']' );
                }
                return TRUE;
        }//}}}

        public function del( $cond ){
        //{{{
                if( !$this->_CI->db->delete( $this->_table_name , $cond ) ){
                        throw new Exception( "SQL error with SQL [" . $last_query = $this->_CI->db->last_query() . ']' );
                }
                //对于不存在的记录的删除操作时需要记录 log 中的
                if( $this->_CI->db->affected_rows() == 0 ){
                        log_message( 'error' , '尝试对不存在的记录进行了删除操作.' );
                }
                return TRUE;
        }//}}}

        //更新相应条件制定记录中的信息
        //@param array $value 需要更新值的 hash 表
        //@param array $cond 确定需要更新的记录
        public function update( $value , $cond ){
        //{{{
                $this->_CI->db->where( $cond );
                if( !$this->_CI->db->update( $this->_table_name , $value ) ){
                        
                        throw new Exception( "SQL error with SQL [" . $last_query = $this->_CI->db->last_query() . ']' );
                }
                return TRUE;
        }//}}}

        //对某个数据库中的键值 执行加(由 $value 指定)操作
        public function increment( $cond , $key , $value = 1 ){
        //{{{
                $where ='';
                foreach( $cond as $k=>$v ){
                        $where .= " `$k`='$v' ";
                }
                $sql = "UPDATE `$this->_table_name` SET `$key` = `$key`+$value WHERE $where";
                if( !$this->_CI->db->query( $sql ) ){
                
                        throw new Exception( "SQL error with SQL [" . $last_query = $this->_CI->db->last_query() . ']' );
                }
                return TRUE;
        }//}}}

        //对某个数据库中的键值 执行减(由 $value 指定)操作
        //actionRecord 会自动在 update 的值加 \' so 只有直接
        //使用 sql 语句
        public function decrement( $cond , $key , $value = 1 ){
        //{{{
                foreach( $cond as $key=>$value ){
                        $where .= " `$k`='$v' ";
                }
                $sql = "UPDATE `$this->_table_name` SET `$key` = `$key`+$value WHERE $where";
                if( !$this->_CI->db->query( $sql ) ){
                
                        throw new Exception( "SQL error with SQL [" . $last_query = $this->_CI->db->last_query() . ']' );
                }
                return TRUE;
        }//}}}

        //格式化 方案/转发 的时间(在toolbar上显示，格式为 刚刚, XX分钟前 ，昨天 ，前天 ，月份.日期)
        protected function format_time( $raw_post_time ){
        //{{{
                $now_time = $_SERVER['REQUEST_TIME'];
                $post_time = strtotime( $raw_post_time );
                $during_post = $now_time - $post_time;
                if( $during_post < 60 ){
                        //一分钟之内
                        $formated_time = "刚刚";
                }else if( $during_post >= 60 && $during_post < 3600 ){
                        //大于一分钟但是在一个小时之内
                $formated_time = ceil( ( $during_post / 60 ) ) . "分钟前";
                }else if( $during_post >= 3600 && $during_post < 86400 ){
                        //大于一个小时但是在一天之内
                        $formated_time =  ceil( ( $during_post / 3600 ) ) . "小时前";
                }else if( $during_post >= 86400 && $during_post < 172800 ){
                        //大于一天但是小于两天
                        $formated_time = "昨天";
                }else if( $during_post >= 172800 && $during_post < 295200 ){
                        //大于两天但是小于三天
                        $formated_time = "前天";
                }else{
                        $formated_time = date( 'n.d' , $post_time );
                }
                return $formated_time;
        }//}}}

        //一些特殊的分页显示函数
                
        //根据时间信息对 hash 进行排序
        //默认的顺序是降序
        protected function sort_by_time( $hash , $order = 'DESC' ){
        //{{{
                //所提供的 hash 必须有 time 属性
                if( !isset( $hash[0] ) || !isset( $hash[0]['time'] ) ){
                        throw new RunTimeException( '提供的 hash 中没有正确的设置 time 属性.' );
                }
                uasort(
                        $hash, 
                        function( $a , $b ){
                                if( $a['time'] == $b['time'] ){
                                        return 0;
                                }
                                if( $a['time'] < $b['time'] ){
                                        return 1;
                                }else{
                                        return -1;
                                }
                        }
                );
                return $hash;
        }//}}}

        //按照页码信息对 hash 进行分片处理
        protected function slice_by_page( $hash , $page = 1 , $page_limit ){
        //{{{
                if( $page <= 0 ){
                        throw new RunTimeException( '尝试获取方案的页码数小于或等于零了.$page=' . $page );
                }
                return array_slice( $hash , ( ( $page - 1 ) * $page_limit ) , $page_limit );
        }//}}}
}
