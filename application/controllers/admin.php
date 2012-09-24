<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class admin extends CI_Controller {

        /**
         * 判断管理员是否已经登录
         *
         * @return mixed 管理员成功登录后则会返回当前管理员信息 
         */
        private function is_admin_login(){

                //判断管理员是否已经登录
                $admin_info = $this->session->userdata( 'admin_info' );

                if( !isset( $admin_info ) || empty( $admin_info ) ){

                        if( $this->input->is_ajax_request() ){

                                //如果是ajax请求的话
                                echo '{"res":"FALSE","info":"未登录管理员不能执行此操作."}';
                                exit;
                        }

                        //还没有登录
                        show_404();
                        //header( 'Location: /admin/login' );
                        exit;
                }

                return $admin_info;
        }

        public function index(){

                $admin_info = $this->is_admin_login();


                $this->load->view( 'div/admin_html_info.php' );
                $this->load->view( 'admin/base' , 
                        array( 'admin_info'=>$admin_info , 'include_page'=>'welcome' ) );
        }

        public function login(){

                $this->load->view( 'div/admin_html_info.php' );

                //显示登录窗口之前先判断是否有上次遗留下来的
                //错误信息
                if( $admin_login_res = $this->session->flashdata( 'admin_login_res' ) ){

                        $this->load->view( 'admin/login' , 
                                array( 'admin_login_res'=>$admin_login_res ) ); 
                }else{


                        $this->load->view( 'admin/login' );
                }
        }       

        public function dologin(){

                //判断来自的页面,如果不是预定的登录页面
                //则始终显示登录错误
                if( !strstr( $_SERVER['HTTP_REFERER'] , 'no/war/we/we' ) ){

                        $this->session->set_flashdata( 'admin_login_res' , 
                                array( 'res'=>false , 'info'=>'登录失败,用户名密码错误' ) );

                        show_404();
                        //header( 'Location: /admin/login' );
                        exit;
                }

                $this->load->database();

                //登录操作
                $admin_name = $this->input->post( 'admin' );
                $passwd = $this->input->post( 'password' );

                $this->db->where( array( 'admin_name'=>$admin_name , 'passwd'=>md5( $passwd ) ) );
                $res = $this->db->get( 'admini' );

                if( $res->num_rows() == 1 ){

                        //登录成功
                        //保存登录状态

                        //得到登录管理员的信息
                        $row = $res->result_array();
                        $admin_info = $row[0];

                        //更新管理员状态         
                        $this->db->where( array( 'id'=>$row[0]['id'] ) );
                        $this->db->update( 'admini' ,
                                array( 'last_login_add'=>$_SERVER['REMOTE_ADDR'] , 
                                'last_login_time'=>date( "Y-m-d H:i:s" ) ) );


                        $this->session->set_userdata( 'admin_info' , $admin_info );

                        //默认为用户管理
                        header( 'Location: /admin/user_admin' );
                        exit;
                }else{   

                        $this->session->set_flashdata( 'admin_login_res' , 
                                array( 'res'=>false , 'info'=>'登录失败，用户名或密码错误' ) );
                        //登录失败
                        header( 'Location: /no/war/we/we/' );
                        exit;
                }
        }

        public function dologout(){

                $admin_info = $this->is_admin_login();

                $this->session->unset_userdata( 'admin_info' );

                header( 'Location: /' );
                exit;
        }

        private function str_split_utf8($str){

                $split=1;
                $array = array();
                for ( $i=0; $i < strlen( $str ); ){
                        $value = ord($str[$i]);
                        if($value > 127){
                                if($value >= 192 && $value <= 223)
                                        $split=2;
                                elseif($value >= 224 && $value <= 239)
                                        $split=3;
                                elseif($value >= 240 && $value <= 247)
                                        $split=4;
                        }else{
                                $split=1;
                        }
                        $key = NULL;
                        for ( $j = 0; $j < $split; $j++, $i++ ) {
                                $key .= $str[$i];
                        }
                        array_push( $array, $key );
                }
                return $array;
        }

        public function user_admin( $key='reg_time' , $order_by='desc' ){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                $total_rows = $this->db->count_all( 'user' );

                //分页显示
                $this->load->library('pagination');

                $config['base_url'] = "/admin/user_admin/$key/$order_by?";               
                $config['total_rows'] = $total_rows;
                $config['per_page'] = 25;
                $config['page_query_string'] = TRUE;

                $config['full_tag_open'] = '<p>';
                $config['full_tag_close'] = '</p>';

                $config['prev_link'] = '上一页';
                $config['next_link'] = '下一页';
                $config['last_link'] = TRUE;
                $config['first_link'] = TRUE;
                $config['cur_tag_open'] = '<span>';
                $config['cur_tag_close'] = '</span>';
                $config['first_link'] = '第一页';
                $config['last_link'] = '最后一页';

                $this->pagination->initialize( $config );
                $offset = $this->input->get( 'per_page' );
                if( empty( $offset ) ){

                        $offset = 0;
                }

                //如果需要以 follow_count 来排序
                if( $key == 'follow_count' ){

                        $sql = "SELECT * , 
                                (SELECT COUNT('user_id') AS `follow_count` 
                                FROM `follow` WHERE `follow` = `user`.`user_id`) AS `follow_count` 
                                FROM `user` ORDER BY `follow_count` $order_by LIMIT $offset , 25";
                        $res =$this->db->query( $sql );
                        $user_infos = $res->result_array();      
                }else{

                        //获得全部用户信息
                        $this->db->order_by( $key , $order_by );
                        $res = $this->db->get( 'user' , 25 , $offset );
                        $user_infos = $res->result_array();

                        //追加粉丝数
                        foreach( $user_infos as $no=>$user_info ){

                                $this->db->where( 'follow' , $user_info['user_id'] );
                                $res = $this->db->get( 'follow' );
                                $follow_count = $res->num_rows();

                                $user_infos[$no]['follow_count'] = $follow_count;
                        }
                }
                $opt_user_res = $this->session->flashdata( 'opt_user_res' );

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'active'=>'user' , 'include_page'=>'user_admin' , 'sub_nav'=>'user_sub_nav' , 'sub_active'=>'user_admin' , 'opt_user_res'=>$opt_user_res , 
                        'admin_info'=>$admin_info , 'user_infos'=>$user_infos , 'now_sort_rule'=>$key . '_' . $order_by , 'total_rows'=>$total_rows ) );
        }

        //编辑用户信息
        public function user_admin_info_edit( $user_id ){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                //获得用户id
                if( !empty( $user_id ) ){

                        //没有用户 id 传入 
                        $user_info = array();
                }

                //获得全部用户信息
                $this->db->where( array( 'user_id'=>$user_id ) );
                $res = $this->db->get( 'user' );
                $user_info = $res->result_array();

                //判断有没有需要显示的处理结果
                $update_user_info_res = $this->session->flashdata( 'update_user_info_res' );

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'active'=>'user' , 'include_page'=>'user_admin_info_edit' ,  'sub_nav'=>'user_sub_nav' ,
                        'admin_info'=>$admin_info , 'user_info'=>$user_info[0] , 'update_user_info_res'=>$update_user_info_res ) );
        }

        public function do_user_admin_info_edit(){

                //判断用户输入
                $user_id = $this->input->post( 'user_id' );
                $email = $this->input->post( 'email' );
                $passwd = $this->input->post( 'passwd' );
                $nick = $this->input->post( 'nick' );

                /**
                 * 对于用户输入数据的检查
                 */
                //判断如果用户 id 没有设置则出错
                if( empty( $user_id ) ){

                        die( '没有设置用户id' );
                }

                if( empty( $email ) || !$this->check_email( $email ) ){

                        //邮箱地址有误
                        $this->session->set_flashdata( 'update_user_info_res' , array( 'res'=>FALSE , 'info'=>'邮箱地址有误.' ) );
                        header( 'Location: /admin/user_admin_info_edit/' . $user_id );
                        exit;
                }

                if( empty( $nick ) ){

                        //用户昵称为空
                        $this->session->set_flashdata( 'update_user_info_res' , array( 'res'=>FALSE , 'info'=>'昵称不能为空.' ) );
                        header( 'Location: /admin/user_admin_info_edit/' . $user_id );
                        exit;
                }else{
                        $cc = 0;
                        $not_cc = 0;
                        $leng = 0;
                        $count = 0;
                        //判断nick格式是否正确
                        $nick_array = $this->str_split_utf8( $nick );
                        foreach( $nick_array as $no=>$value ){

                                //汉字
                                if( preg_match( '/[\x{4e00}-\x{9fa5}]/u' , $value ) ){

                                        $cc++;
                                        $leng++;
                                }else{
                                        $count++;
                                        $not_cc++;

                                        if( $count == 2 ){

                                                $count = 0;
                                                $leng++;
                                        }
                                }
                        }

                        if( ( $cc < 2 && $not_cc < 4 ) ){

                                $this->session->set_flashdata( 'update_user_info_res' , array( 'res'=>FALSE , 'info'=>'昵称格式错误.(太短 ,最短2个汉字或4个字符)' ) );
                                header( 'Location: /admin/user_admin_info_edit/' . $user_id );
                                exit;    
                        }
                        if( $leng > 10 ){

                                $this->session->set_flashdata( 'update_user_info_res' , array( 'res'=>FALSE , 'info'=>'昵称格式错误.(太长 ,最长10个汉字或20个字符)' ) );
                                header( 'Location: /admin/user_admin_info_edit/' . $user_id );
                                exit;   
                        }
                }

                $this->load->database();         
                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                //读出用户以前的数据
                $this->db->where( 'user_id' , $user_id );
                $res = $this->db->get( 'user' );
                $row = $res->result_array();
                $past_user_info = $row[0];

                $user_info = array();
                //管理员输入的不为空的话则进行相应的更新
                foreach( $_POST as $key=>$value ){

                        if( empty( $value ) ){
                                //如果输入值为空
                                continue;
                        }

                        //通过框架的到相应的值
                        $user_info[ $key ] = $this->input->post( $key );
                }
                if( !empty( $passwd ) ){

                        //需要设置密码  
                        $user_info['passwd'] = md5( $user_info['passwd'] );
                }

                //检测 nick ，email 的唯一性

                //如果用户的nick改变了的话
                if( $past_user_info['nick'] != $user_info['nick'] ){

                        $this->db->where( 'nick' , $user_info['nick'] );
                        $res = $this->db->get( 'user' );

                        if( $res->num_rows() != 0 ){

                                $this->session->set_flashdata( 'update_user_info_res' , array( 'res'=>FALSE , 'info'=>'该昵称已经存在于系统中.' ) );
                                header( 'Location: /admin/user_admin_info_edit/' . $user_id );
                                exit;
                        }
                }

                //如果用户的domain改变了的话
                if( $past_user_info['domain'] != $user_info['domain'] ){

                        //不允许纯数字的域名
                        if( eregi("^[0-9]+$" , $user_info['domain'] ) ){

                                $this->session->set_flashdata( 'update_user_info_res' , array( 'res'=>FALSE , 'info'=>'个性域名不能为纯数字.' ) );
                                header( 'Location: /admin/user_admin_info_edit/' . $user_id );
                                exit;
                        }

                        //个性域名的格式,不能有符号
                        if( eregi("[^a-zA-Z0-9]" , $user_info['domain'] ) ){

                                $this->session->set_flashdata( 'update_user_info_res' , array( 'res'=>FALSE , 'info'=>'个性域名不能存在特殊符号.' ) );
                                header( 'Location: /admin/user_admin_info_edit/' . $user_id );
                                exit;
                        }

                        $this->config->load( 'secur' );
                        $domain_limit = $this->config->item( 'domain_limit' );
                        $domain_limit_array = explode( '|' , $domain_limit );

                        $this->db->where( 'domain' , $user_info['domain'] );
                        $res = $this->db->get( 'user' );

                        $this->db->where( 'sys_domain' , $user_info['domain'] );
                        $res_sys_domian = $this->db->get( 'user' );

                        if( $res_sys_domian->num_rows() != 0 || $res->num_rows() != 0 || in_array( $user_info['domain'] , $domain_limit_array ) ){

                                $this->session->set_flashdata( 'update_user_info_res' , array( 'res'=>FALSE , 'info'=>'该个性域名已经存在于系统中.' ) );
                                header( 'Location: /admin/user_admin_info_edit/' . $user_id );
                                exit;
                        }
                }

                //如果用户的email改变了的话
                if( $past_user_info['email'] != $user_info['email'] ){

                        $this->db->where( 'email' , $user_info['email'] );
                        $res = $this->db->get( 'user' );

                        if( $res->num_rows() != 0 ){

                                $this->session->set_flashdata( 'update_user_info_res' , array( 'res'=>FALSE , 'info'=>'该邮箱已经存在于系统中.' ) );
                                header( 'Location: /admin/user_admin_info_edit/' . $user_id );
                                exit;
                        }
                }

                $this->db->where( array( 'user_id'=>$user_info['user_id'] ) );
                $this->db->update( 'user' , $user_info );
                $affected_rows = $this->db->affected_rows();
                //判断更新操作是否成功执行
                if( $affected_rows == 1 || $affected_rows == 0 ){

                        $this->session->set_flashdata( 'update_user_info_res' , 
                                array( 'res'=>TRUE , 'info'=>'更新成功.' ) );
                }else{

                        $this->session->set_flashdata( 'update_user_info_res' ,
                                array( 'res'=>FALSE , 'info'=>'更新失败.' ) );
                }       

                header( 'Location: /admin/user_admin_info_edit/' . $user_info['user_id'] );
        }

        public function update_user_status(){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login(); 

                $user_id = $this->input->post( 'user_id' );

                //需要转换的状态
                $status = $this->input->post( 'status' );

                //修改用户的状态为 disable 
                $this->db->where( 'user_id' , $user_id );
                $this->db->update( 'user' , array( 'status'=>$status ) );

                //刷新用户查询结果
                $this->session->set_userdata( 'user_infos' , '' );

                echo '{"res":"TRUE","info":"用户已修改为' . $status . '状态"}';
                return;
        } 

        public function user_admin_search(){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                //重置搜索结果 
                $this->session->set_userdata( 'user_infos' , '' );

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'active'=>'user' , 'sub_active'=>'user_admin_search' , 
                        'include_page'=>'user_admin_search' ,  'sub_nav'=>'user_sub_nav' , 'admin_info'=>$admin_info ) );
        }

        public function user_admin_search_result(){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                //得到通过 url 传递的结果总数
                $total_rows = $this->input->get( 't' );
                //得到通过 session 保存的上次查询的结果，如果存在的话
                $user_infos = $this->session->userdata( 'user_infos' );

                if( empty( $user_infos ) ){

                        //获得用户搜索条件
                        $cond = $this->input->get( 'cond' );
                        //获得用户输入的关键词
                        $key_word = $this->input->get( 'key_word' );

                        //用户输入的城市信息 code
                        $city = $this->input->get( 'city' );

                        $follow_count_min = $this->input->get( 'follow_count_min' );
                        $follow_count_max = $this->input->get( 'follow_count_max' );

                        $reg_time_start = $this->input->get( 'reg_time_start' );
                        $reg_time_end = $this->input->get( 'reg_time_end' );

                        //单独执行特殊的查询
                        if( !empty( $follow_count_min ) ){

                                $where_follow_count_min = "`follow_count` >= '$follow_count_min'";
                        }else{

                                $where_follow_count_min = 1;
                        }
                        if( !empty( $follow_count_max ) ){

                                $where_follow_count_max = "`follow_count` <= '$follow_count_max'";
                        }else{

                                $where_follow_count_max = 1;
                        }

                        if( !empty( $where_follow_count_min ) || !empty( $where_follow_count_min ) ){

                                //设置了粉丝数作为条件
                                $sql = "SELECT * , 
                                        (SELECT COUNT('user_id') AS `follow_count` 
                                        FROM `follow` WHERE `follow` = `user`.`user_id`) AS `follow_count` 
                                        FROM `user` 
                                        HAVING $where_follow_count_min AND $where_follow_count_max
                                        ORDER BY `follow_count` DESC";
                                $res =$this->db->query( $sql );
                                $user_infos_by_follow_count = $res->result_array();      
                        }

                        //设置的注册时间作为条件
                        if( !empty( $reg_time_start ) ){

                                $this->db->where( 'reg_time >=' , $reg_time_start );
                        }
                        if( !empty( $reg_time_end ) ){

                                $this->db->where( 'reg_time <=' , $reg_time_end );
                        }

                        if( !empty( $key_word ) ){

                                $this->db->where( $cond , $key_word );
                        }

                        //如果设置了城市信息
                        if( !empty( $city ) ){

                                $this->db->where( 'city' , $city );
                        }

                        $this->db->order_by( 'user_id' , 'DESC' );
                        $res = $this->db->get( 'user' );
                        $user_infos_by_user_info = $res->result_array();
                        //追加粉丝数
                        foreach( $user_infos_by_user_info as $no=>$user_info ){

                                $this->db->where( 'follow' , $user_info['user_id'] );
                                $res = $this->db->get( 'follow' );
                                $follow_count = $res->num_rows();

                                $user_infos_by_user_info[$no]['follow_count'] = $follow_count;
                        }

                        //取两种结果的并集
                        $user_infos = array();
                        if( !empty( $user_infos_by_follow_count ) ){

                                foreach( $user_infos_by_user_info as $no_1=>$user_info_by_user_info ){

                                        foreach( $user_infos_by_follow_count as $no_2=>$user_info_by_follow_count ){

                                                if( $user_info_by_user_info['user_id'] == $user_info_by_follow_count['user_id'] ){

                                                        //有交集
                                                        $user_infos[$no_1] = $user_info_by_user_info;
                                                }
                                        }
                                }
                        }
                        $this->session->set_userdata( 'user_infos' , $user_infos );
                        $total_rows = count( $user_infos );
                        $this->session->set_userdata( 'total_rows' , $total_rows );
                }

                $total_rows = $this->session->userdata( 'total_rows' );

                //分页显示
                $this->load->library('pagination');

                $config['base_url'] = "/admin/user_admin_search_result?t=$total_rows";
                //每页显示数
                $config['total_rows'] = $total_rows;
                $config['per_page'] = 25;
                $config['page_query_string'] = TRUE;

                $config['full_tag_open'] = '<p>';
                $config['full_tag_close'] = '</p>';

                $config['prev_link'] = '上一页';
                $config['next_link'] = '下一页';
                $config['last_link'] = TRUE;
                $config['first_link'] = TRUE;
                $config['cur_tag_open'] = '<span>';
                $config['cur_tag_close'] = '</span>';
                $config['first_link'] = '第一页';
                $config['last_link'] = '最后一页';

                $this->pagination->initialize( $config );
                $offset = $this->input->get( 'per_page' );
                if( empty( $offset ) ){

                        $offset = 0;
                }

                //取出数组中的一部分来显示
                $user_infos_per_page = array_slice( $user_infos , $offset , 25 , TRUE );

                $opt_user_res = $this->session->flashdata( 'opt_user_res' );
                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'active'=>'user' , 'include_page'=>'user_admin_search_result' ,
                        'sub_nav'=>'user_sub_nav' , 'admin_info'=>$admin_info ,
                        'user_infos'=>$user_infos_per_page , 'opt_user_res'=>$opt_user_res , 'total_rows'=>$total_rows ) );
        }

        public function add_new_user(){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                $add_new_user_info_res = $this->session->flashdata( 'add_new_user_info_res' );

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'active'=>'user' , 'sub_active'=>'add_new_user' , 'include_page'=>'add_new_user' , 'add_new_user_info_res'=>$add_new_user_info_res ,
                        'sub_nav'=>'user_sub_nav' , 'admin_info'=>$admin_info ) ); 
        }

        private function check_email( $email ){

                return ( ereg( "^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+" , $email ) ); 
        } 

        public function do_add_new_user(){

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                //判断用户输入
                $email = $this->input->post( 'email' );
                $passwd = $this->input->post( 'passwd' );
                $nick = $this->input->post( 'nick' );
                $domain = $this->input->post( 'domain' );

                if( empty( $email ) || !$this->check_email( $email ) ){

                        //邮箱地址有误
                        $this->session->set_flashdata( 'add_new_user_info_res' , array( 'res'=>FALSE , 'info'=>'邮箱地址有误.' ) );
                        header( 'Location: /admin/add_new_user' );
                        exit;
                }

                if( empty( $passwd ) || strlen( $passwd ) < 6 ){

                        //密码为空或格式有误
                        $this->session->set_flashdata( 'add_new_user_info_res' , array( 'res'=>FALSE , 'info'=>'密码格式有误（最少6位）.' ) );
                        header( 'Location: /admin/add_new_user' );
                        exit;
                }

                if( empty( $nick ) ){

                        //用户昵称为空
                        $this->session->set_flashdata( 'add_new_user_info_res' , array( 'res'=>FALSE , 'info'=>'昵称不能为空.' ) );
                        header( 'Location: /admin/add_new_user' );
                        exit;
                }

                $this->load->database();

                $user_info = array();

                //管理员输入的不为空的话则进行相应的更新
                foreach( $_POST as $key=>$value ){

                        if( empty( $value ) ){
                                //如果输入值为空
                                continue;
                        }
                        if( $key == 'city' || $key == 'town' || $key == 'province' ){

                                continue;
                        }

                        //通过框架的到相应的值
                        $user_info[ $key ] = $this->input->post( $key );
                }
                //hash 密码
                $user_info[ 'passwd' ] = md5( $passwd );

                //检测 nick ，email 的唯一性
                $this->db->where( 'nick' , $user_info['nick'] );
                $res = $this->db->get( 'user' );

                if( $res->num_rows() != 0 ){

                        $add_new_user_info_res['info'][0] = '该 nick 已经存在于系统中.';
                }

                $this->db->where( 'email' , $user_info['email'] );
                $res = $this->db->get( 'user' );

                if( $res->num_rows() != 0 ){

                        $add_new_user_info_res['info'][1] = '该 email 已经存在于系统中.';
                }

                if( !empty( $add_new_user_info_res ) ){

                        //有错误存在
                        $add_new_user_info_res['res'] = FALSE;
                        $this->session->set_flashdata( 'add_new_user_info_res' , $add_new_user_info_res );
                        header( 'Location: /admin/add_new_user' );
                        exit;
                }

                //执行添加用户操作
                $this->db->insert( 'user' , $user_info );

                $res = $user_id = $this->db->query( 'SELECT LAST_INSERT_ID() as `user_id`' );
                $row = $res->result_array();

                //生成伪随机默认域名 sys_domain
                $time = time();
                $r = mt_rand( 1 , 99 );
                $user_id = $row[0]['user_id'];

                $sys_domain = ltrim( substr( ( $time + $r ) * $user_id , -10 ) );

                //检测 sys_domain 唯一性
                while( !$this->is_sys_domain_unique( $sys_domain ) ){

                        //冲突
                        $r = mt_rand( 1 , 99 );
                        $time = time();
                        $sys_domain = ltrim( substr( ( $time + $r ) * $user_id , -10 ) );
                }

                //默认增加一条关注，否则目前的sql不能正常工作
                $this->db->insert( 'follow' , array( 'user_id'=>$user_id , 'follow'=>'0' ) );

                $this->db->where( 'user_id' , $user_id );

                //如果设置了用户个性域名
                if( !empty( $domain ) ){

                        //不允许纯数字的域名
                        if( eregi("^[0-9]+$" , $domain ) ){

                                $this->session->set_flashdata( 'add_new_user_info_res' , array( 'res'=>FALSE , 'info'=>'个性域名不能为纯数字.' ) );
                                header( 'Location: /admin/add_new_user/' . $user_id );
                                exit;
                        }

                        //个性域名的格式,不能有符号
                        if( eregi("[^a-zA-Z0-9]" , $domain  ) ){

                                $this->session->set_flashdata( 'update_user_info_res' , array( 'res'=>FALSE , 'info'=>'个性域名不能存在特殊符号.' ) );
                                header( 'Location: /admin/user_admin_info_edit/' . $user_id );
                                exit;
                        }

                        $this->config->load( 'secur' );
                        $domain_limit = $this->config->item( 'domain_limit' );
                        $domain_limit_array = explode( '|' , $domain_limit );

                        $this->db->where( 'domain' , $domain );
                        $res = $this->db->get( 'user' );

                        $this->db->where( 'sys_domain' , $domain );
                        $res_sys_domian = $this->db->get( 'user' );

                        if( $res_sys_domian->num_rows() != 0 || $res->num_rows() != 0 || in_array( $domain , $domain_limit_array ) ){

                                $this->session->set_flashdata( 'add_new_user_info_res' , array( 'res'=>FALSE , 'info'=>'该个性域名已经存在于系统中.' ) );
                                header( 'Location: /admin/add_new_user/' . $user_id );
                                exit;
                        }
                }else{

                        $domain = $sys_domain;
                }

                $this->db->update( 'user' ,
                        array( 'domain'=>$domain , 'sys_domain'=>$sys_domain ) );

                $this->session->set_flashdata( 'opt_user_res' , array( 'res'=>TRUE , 'info'=>'添加用户成功.' ) );
                header( 'Location: /admin/user_admin' );
        }

        private function is_sys_domain_unique( $sys_domain ){

                $res = $this->db->get_where( 'user' , array( 'sys_domain'=>$sys_domain ) , 1 );
                return $res->num_rows() == 0;
        }

        public function do_delete_user( $user_id ){

                $admin_info = $this->is_admin_login();

                $this->load->database();

                $this->db->where( 'user_id' , $user_id );
                $this->db->delete( 'user' );

                //删除用户相关的关注信息
                $this->db->where( 'follow' , $user_id );
                $this->db->or_where( 'user_id' , $user_id );
                $this->db->delete( 'follow' );

                //删除用户的方案信息
                $this->db->where( 'holder_id' , $user_id );
                $this->db->delete( 'solution' );

                //删除相应的商品信息

                //删除用户的交易
                $this->db->where( 'buyer_id' , $user_id );
                $this->db->delete( 'solution' );

                $this->session->set_flashdata( 'opt_user_res' , array( 'res'=>TRUE , 'info'=>'删除用户成功.' ) );

                header( 'Location: ' . $_SERVER['HTTP_REFERER'] );
                exit;
        }

        /**
         * 方案管理
         */
        public function solution_admin( $key='time' , $order_by='desc' , $status='' ){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                //按照相应的状态获得全部方案信息
                if( !empty( $status ) ){

                        $this->db->where( 'status' , $status );
                }

                $total_rows = $this->db->count_all( 'solution' );

                //分页显示
                $this->load->library('pagination');

                $config['base_url'] = "/admin/solution_admin/$key/$order_by?";
                //每页显示数
                $config['total_rows'] = $total_rows;
                $config['per_page'] = 25;
                $config['page_query_string'] = TRUE;

                $config['full_tag_open'] = '<p>';
                $config['full_tag_close'] = '</p>';

                $config['prev_link'] = '上一页';
                $config['next_link'] = '下一页';
                $config['last_link'] = TRUE;
                $config['first_link'] = TRUE;
                $config['cur_tag_open'] = '<span>';
                $config['cur_tag_close'] = '</span>';
                $config['first_link'] = '第一页';
                $config['last_link'] = '最后一页';

                $this->pagination->initialize( $config );
                $offset = $this->input->get( 'per_page' );
                if( empty( $offset ) ){

                        $offset = 0;
                }

                $this->db->order_by( $key , $order_by );
                $res = $this->db->get( 'solution' , 25 , $offset );
                if( is_object( $res ) ){

                        $solution_infos = $res->result_array();
                }else{

                        $solution_infos = array();
                }

                //取得每个方案所有者的昵称 以及城市信息
                foreach( $solution_infos as $no=>$solution_info ){

                        $this->db->where( 'user_id' , $solution_info['holder_id'] );
                        $res = $this->db->get( 'user' );
                        $holder_info = $res->result_array();

                        if( empty( $holder_info[0]['nick'] ) ){

                                $solution_infos[$no]['holder_nick'] = '';
                        }else{

                                $solution_infos[$no]['holder_nick'] = $holder_info[0]['nick'];
                        }
                        if( empty( $holder_info[0]['sys_domain'] ) ){

                                $solution_infos[$no]['holder_sys_domain'] = '';
                        }else{

                                $solution_infos[$no]['holder_sys_domain'] = $holder_info[0]['sys_domain'];
                        }
                        if( empty( $holder_info[0]['loc_city'] ) ){

                                $solution_infos[$no]['holder_loc_city'] = '';
                        }else{

                                $solution_infos[$no]['holder_loc_city'] = $holder_info[0]['loc_city'];
                        }
                }

                $opt_solution_info_res = $this->session->flashdata( 'opt_solution_info_res' );

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'include_page'=>'solution_admin' , 'sub_nav'=>'solution_sub_nav' , 'active'=>'solution' ,
                        'sub_active'=>'solution_admin' , 'opt_solution_info_res'=>$opt_solution_info_res ,
                        'admin_info'=>$admin_info , 'solution_infos'=>$solution_infos , 'now_sort_rule'=>$key . '_' . $order_by , 'total_rows'=>$total_rows ) );
        }

        public function setting_solution_expire_time(){

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                $this->config->load( 'solution' );
                $solution_expire_time = $this->config->item( 'solution_expire_time' );

                $opt_solution_info_res = $this->session->flashdata( 'opt_solution_info_res' );

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'include_page'=>'setting_solution_expire_time' , 'sub_nav'=>'solution_sub_nav' , 'active'=>'solution' ,
                        'sub_active'=>'setting_solution_expire_time' , 'opt_solution_info_res'=>$opt_solution_info_res ,
                        'admin_info'=>$admin_info , 'solution_expire_time'=>( $solution_expire_time/86400 ) ) );
        }

        public function do_setting_solution_expire_time(){

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                $solution_expire_time = $this->input->post( 'expire' );
                //判断用户输入
                if( !is_numeric( $solution_expire_time ) ){

                        $this->session->set_flashdata( 'opt_solution_info_res' , array( 'res'=>FALSE , 'info'=>'用户输入非法.' ) );
                        header( 'Location: /admin/setting_solution_expire_time' );
                        exit;
                }

                //换算成秒
                $solution_expire_time = $solution_expire_time * 86400;

                //写入文件
                $config_file_content ='<?php  if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');
                /**
                 * 方案配置文件 
                 *
                 */
                $config[\'solution_expire_time\'] = \''. $solution_expire_time . '\';';

                $this->load->helper( 'file' );

                write_file( getcwd().'/application/config/solution.php' , $config_file_content );

                $this->session->set_flashdata( 'opt_solution_info_res' , array( 'res'=>TRUE , 'info'=>'修改配置成功.' ) );
                header( 'Location: /admin/setting_solution_expire_time' );
                exit;
        }

        public function solution_admin_info_edit( $solution_id ){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                //获得方案id
                if( !empty( $solution_id ) ){

                        //没有用户 id 传入 
                        $solution_infos = array();
                }

                //得到方案本身的信息
                $this->db->where( 'id' , $solution_id );
                $res = $this->db->get( 'solution' );
                $solution_info = $res->result_array();

                //得到相应方案包含的商品
                $this->db->where( 'solution_id' , $solution_id );
                $this->db->order_by( 'id' , 'asc' );
                $res = $this->db->get( 'product' );
                $product_infos = $res->result_array();

                $opt_solution_info_res = $this->session->flashdata( 'opt_solution_info_res' );

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'include_page'=>'solution_admin_info_edit' ,  'sub_nav'=>'solution_sub_nav' , 'opt_solution_info_res'=>$opt_solution_info_res ,
                        'active'=>'solution' , 'sub_active'=>'solution_admin_info_edit' ,
                        'admin_info'=>$admin_info , 'solution_info'=>$solution_info[0] , 'product_infos'=>$product_infos ) );
        }

        public function do_solution_admin_info_edit(){

                $this->load->database();

                //得到方案本身的消息
                $solution_id = $this->input->post( 'solution_id' );
                $solution_title = $this->input->post( 'solution_title' );
                $solution_describe = $this->input->post( 'solution_describe' );
                $sum_ori_price = $this->input->post( 'sum_ori_price' );
                $sum_now_price = $this->input->post( 'sum_now_price' );
                $discount = $this->format_discount( $this->input->post( 'discount' ) );

                $holder_id = $this->input->post( 'holder_id' );

                //验证用户输入的信息是否有敏感词汇
                //并进行过滤
                $this->load->library( 'secur_process' );
                $solution_title = $this->secur_process->filter_user_input( $solution_title );
                $solution_describe = $this->secur_process->filter_user_input( $solution_describe );

                $solution_info = array( 'title'=>$solution_title , 'describe'=>$solution_describe , 
                        'holder_id'=>$holder_id , 'original_price'=>$sum_ori_price , 'sum_now_price'=>$sum_now_price , 'discount'=>$discount );

                $product_no = 0;
                do{
                        $title_key = 'title' . $product_no;
                        $original_price_key = 'original_price' . $product_no;
                        $describe_key = 'describe' . $product_no;

                        //获得 product 信息
                        $title = $this->input->post( $title_key );
                        $original_price = $this->input->post( $original_price_key );
                        $describe = $this->input->post( $describe_key );

                        //过滤用户输入的敏感词
                        $title = $this->secur_process->filter_user_input( $title );
                        $describe = $this->secur_process->filter_user_input( $describe );

                        $product_infos[$product_no] = 
                                array( 'title'=>$title , 'original_price'=>$original_price , 'describe'=>$describe ); 

                        $product_no += 1;

                }while( isset( $_POST['title' . $product_no ] ) && isset( $_POST['original_price' . $product_no ] ) );

                //更新方案信息
                $this->db->where( 'id' , $solution_id );
                $this->db->update( 'solution' , $solution_info );

                //删除以前的方案商品信息
                $this->db->where( 'solution_id' , $solution_id );
                $this->db->delete( 'product' );

                //添加新的方案信息
                foreach( $product_infos as $no=>$info ){

                        if( !empty( $info ) ){
                                //逐条写入
                                $this->db->insert( 'product' ,
                                        array( 'solution_id'=>$solution_id , 
                                        'original_price'=>$info['original_price'] , 
                                        'title'=>$info['title'] , 
                                        'describe'=>$info['describe']
                                ));
                        }
                }

                $this->session->set_flashdata( 'opt_solution_info_res' , 
                        array( 'res'=>TRUE , 'info'=>'修改方案成功.' ) );
                header( 'Location: /admin/solution_admin_info_edit/' . $solution_id );
                exit;
        }

        public function solution_admin_search(){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'include_page'=>'solution_admin_search' , 'sub_nav'=>'solution_sub_nav' ,
                        'active'=>'solution' , 'sub_active'=>'solution_admin_search' ,
                        'admin_info'=>$admin_info ) );
        }

        public function solution_admin_search_result(){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                //如过时分页显示，查询条件已经设置
                $q = $this->input->get( 'q' );
                $t = $this->input->get( 't' );
                if( !empty( $q ) && !empty( $t ) ){

                        $total_rows = $t;
                        $sql = base64_decode( $q );      
                }else{

                        //获得用户搜索条件

                        //按卖家
                        $saler_info_cond = $this->input->get( 'saler_info_cond' );
                        $by_saler_key_word = $this->input->get( 'by_saler_key_word' );
                        $saler_reg_time_start = $this->input->get( 'saler_reg_time_start' );
                        $saler_reg_time_end = $this->input->get( 'saler_reg_time_end' );

                        //用户输入的卖家城市信息 code
                        $city = $this->input->get( 'city' );

                        //按买家 
                        $buyer_info_cond = $this->input->get( 'buyer_info_cond' );
                        $by_buyer_key_word = $this->input->get( 'by_buyer_key_word' );
                        $buyer_reg_time_start = $this->input->get( 'buyer_reg_time_start' );
                        $buyer_reg_time_end = $this->input->get( 'buyer_reg_time_end' );

                        //按方案
                        $by_solution_key_word = $this->input->get( 'by_solution_key_word' );
                        $solution_info_cond = $this->input->get( 'solution_info_cond' );
                        $solution_post_time_start = $this->input->get( 'solution_post_time_start' );
                        $solution_post_time_end = $this->input->get( 'solution_post_time_end' );
                        $solution_discount_min = $this->input->get( 'solution_discount_min' );
                        $solution_discount_max = $this->input->get( 'solution_discount_max' );
                        $solution_has_bought_min = $this->input->get( 'solution_has_bought_min' );
                        $solution_has_bought_max = $this->input->get( 'solution_has_bought_max' );
                        $solution_has_paid_min = $this->input->get( 'solution_has_paid_min' );
                        $solution_has_paid_max = $this->input->get( 'solution_has_paid_max' );
                }

                //按卖家条件
                //先要执行一次子查询，找到相关用户的id
                //是否设置的注册时间限制?
                if( !empty( $saler_reg_time_start ) ){

                        $this->db->where( 'reg_time >=' , $saler_reg_time_start );
                }
                if( !empty( $saler_reg_time_end ) ){

                        $this->db->where( 'reg_time <=' , $saler_reg_time_end );
                }
                if( !empty( $city ) ){

                        $this->db->where( 'city' , $city );
                }

                //具体条件查询
                if( !empty( $saler_info_cond ) && !empty( $by_saler_key_word ) ){
                        //只有ID是精确查询 其他关键词 全部采用like
                        if( $saler_info_cond == 'user_id' ){

                                $this->db->where( $saler_info_cond , $by_saler_key_word );
                        }else{

                                //先变成精确的
                                $this->db->where( $saler_info_cond , $by_saler_key_word );
                        }
                }
                //如果设置了条件，则进行查询
                if( !empty( $saler_reg_time_start ) || !empty( $saler_reg_time_end ) || !empty( $city ) || !empty( $by_saler_key_word ) ){
                        $holder_ids_by_saler_info = array();
                        $res = $this->db->get( 'user' );
                        $holder_infos = $res->result_array();

                        //仅取出id
                        foreach( $holder_infos as $no=>$holder_info ){

                                $holder_ids_by_saler_info[$no] = $holder_info['user_id'];
                        }
                }
                //echo $this->db->last_query();
                //var_dump( $holder_ids_by_saler_info );
                //die;

                //按买家查询
                //无论什么情况都必须进行子查询
                if( !empty( $by_buyer_key_word ) ){

                        //如果设置了主条件
                        if( $buyer_info_cond == 'user_id' ){

                                //直接得到一个 buyer_id
                                $this->db->where_in( 'buyer_id' , $by_buyer_key_word );
                        }else{

                                //其他条件使用 like 查询
                                $this->db->where( $buyer_info_cond , $by_buyer_key_word );
                                if( !empty( $buyer_reg_time_start ) ){

                                        $this->db->where( 'reg_time >=' , $buyer_reg_time_start );
                                }
                                if( !empty( $buyer_reg_time_end ) ){

                                        $this->db->where( 'reg_time <=' , $buyer_reg_time_end );
                                }

                                $res = $this->db->get( 'user' );
                                $buyer_infos = $res->result_array();
                                //仅取出id
                                foreach( $buyer_infos as $no=>$buyer_info ){

                                        $buyer_ids_by_buyer_info[$no] = $buyer_info['user_id'];
                                }

                                //通过买家条件得到的 user_id 作为 buyer_id
                                $this->db->where_in( 'buyer_id' , $buyer_ids_by_buyer_info );
                        }
                }
                //如果任何条件都没有设置，查询无效
                if( !empty( $by_buyer_key_word ) || !empty( $buyer_reg_time_end ) || !empty( $buyer_reg_time_end ) ){

                        $solution_ids_by_buyer_info = array();
                        $res = $this->db->get( 'transaction' );
                        $tran_infos = $res->result_array();
                        //仅取出 solution_id 
                        foreach( $tran_infos as $no=>$tran_info ){

                                $solution_ids_by_buyer_info[$no] = $tran_info['solution_id'];
                        }
                }
                //echo $this->db->last_query();
                //var_dump( $solution_ids_by_buyer_info );
                //die;

                //按方案查询
                if( !empty( $by_solution_key_word ) ){

                        if( $solution_info_cond == 'id' ){

                                $this->db->where( 'id', $by_solution_key_word );
                        }else{

                                //其他条件使用 like 查询
                                $this->db->where( $solution_info_cond , $by_solution_key_word );         
                        }
                }
                if( !empty( $solution_post_time_start ) ){

                        $this->db->where( 'time >=' , $solution_post_time_start );
                }
                if( !empty( $solution_post_time_end ) ){

                        $this->db->where( 'time <=' , $solution_post_time_end );
                }
                if( !empty( $solution_discount_min ) ){

                        $this->db->where( "discount >= $solution_discount_min" );
                }
                if( !empty( $solution_discount_max ) ){

                        $this->db->where( "discount <= $solution_discount_max" );
                }
                if( !empty( $solution_has_bought_min ) ){

                        $this->db->where( "has_bought >= $solution_has_bought_min" );
                }
                if( !empty( $solution_has_bought_max ) ){

                        $this->db->where( "has_bought <= $solution_has_bought_max" );
                }
                if( !empty( $solution_has_paid_min ) ){

                        $this->db->where( "has_paid >= $solution_has_paid_min" );
                }
                if( !empty( $solution_has_paid_max ) ){

                        $this->db->where( "has_paid <= $solution_has_paid_max" );
                }

                //根据之前的条件获取
                //方案信息      
                if( isset( $solution_ids_by_buyer_info ) ){

                        if( !empty( $solution_ids_by_buyer_info ) ){

                                $this->db->where_in( 'id' , $solution_ids_by_buyer_info );
                        }else{

                                $this->db->where_in( 'id' , '' );
                        }
                }
                if( isset( $holder_ids_by_saler_info ) ){

                        if( !empty( $holder_ids_by_saler_info ) ){

                                $this->db->where_in( 'holder_id' , $holder_ids_by_saler_info );
                        }else{

                                $this->db->where_in( 'holder_id' , '' );
                        }
                }
                if( empty( $total_rows ) && empty( $sql ) ){

                        $res = $this->db->get( 'solution' );
                        if( is_object( $res ) ){

                                $solution_infos = $res->result_array();

                                //记录下sql语句
                                $sql = $this->db->last_query();
                                $q = urlencode( base64_encode( $sql ) );
                                $total_rows = $res->num_rows();
                        }else{

                                $solution_infos = array();
                        }
                }
                //echo $this->db->last_query();
                //var_dump( $solution_infos );
                //die;

                $this->load->library('pagination');
                $config['base_url'] = "/admin/solution_admin_search_result/?t=$total_rows&&q=" . urlencode( $q );                
                $config['per_page'] = 25;
                $config['total_rows'] = $total_rows;
                $config['page_query_string'] = TRUE;

                $config['full_tag_open'] = '<p>';
                $config['full_tag_close'] = '</p>';

                $config['prev_link'] = '上一页';
                $config['next_link'] = '下一页';
                $config['last_link'] = TRUE;
                $config['first_link'] = TRUE;
                $config['cur_tag_open'] = '<span>';
                $config['cur_tag_close'] = '</span>';
                $config['first_link'] = '第一页';
                $config['last_link'] = '最后一页';

                $this->pagination->initialize( $config );
                $offset = $this->input->get( 'per_page' );
                if( empty( $offset ) ){

                        $offset = 0;
                }

                $res = $this->db->query( $sql . " ORDER BY `id` DESC LIMIT $offset , 25"  );
                //echo $this->db->last_query();die;
                if( is_object( $res ) ){

                        $solution_infos = $res->result_array();
                }else{

                        $solution_infos = array();
                }

                //取得每个方案所有者的昵称
                foreach( $solution_infos as $no=>$solution_info ){

                        $this->db->where( 'user_id' , $solution_info['holder_id'] );
                        $res = $this->db->get( 'user' );
                        $holder_info = $res->result_array();
                        if( empty( $holder_info[0]['nick'] ) ){

                                $solution_infos[$no]['holder_nick'] = '';
                        }else{

                                $solution_infos[$no]['holder_nick'] = $holder_info[0]['nick'];
                        }
                        if( empty( $holder_info[0]['sys_domain'] ) ){

                                $solution_infos[$no]['holder_sys_domain'] = '';
                        }else{

                                $solution_infos[$no]['holder_sys_domain'] = $holder_info[0]['sys_domain'];
                        }

                        $solution_infos[$no]['holder_loc_city'] = $holder_info[0]['loc_city'];
                }

                $opt_solution_info_res = $this->session->flashdata( 'opt_solution_info_res' );

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'include_page'=>'solution_admin_search_result' , 'sub_nav'=>'solution_sub_nav' , 'active'=>'solution' ,  
                        'admin_info'=>$admin_info , 'solution_infos'=>$solution_infos , 
                        'opt_solution_info_res'=>$opt_solution_info_res , 'total_rows'=>$total_rows ) );
        }

        public function add_new_solution(){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                $opt_solution_info_res = $this->session->flashdata( 'opt_solution_info_res' ); 

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'include_page'=>'add_new_solution' , 'active'=>'solution' , 'sub_active'=>'add_new_solution' , 
                        'opt_solution_info_res'=>$opt_solution_info_res , 'sub_nav'=>'solution_sub_nav' , 'admin_info'=>$admin_info ) );
        }

        private function format_discount( $discount ){

                if( $discount < 0.01 ){

                        return '0.0';  

                }else if( $discount > 1 ){

                        return '10+';
                }else{

                        return rtrim( substr( $discount * 10 , 0 , 3 ) , '.'  );
                }
        }

        public function do_add_new_solution(){

                $this->load->database();

                //得到方案本身的消息
                $solution_title = $this->input->post( 'solution_title' );
                $solution_describe = $this->input->post( 'solution_describe' );
                $sum_ori_price = $this->input->post( 'sum_ori_price' );
                $sum_now_price = $this->input->post( 'sum_now_price' );
                $discount = $this->format_discount( $this->input->post( 'discount' ) );

                //用以确定卖家信息
                $holder_form = $this->input->post( 'holder_form' );
                $holder_input_info = $this->input->post( 'holder_input_info' );

                //确定卖家是否存在
                $this->db->where( $holder_form , $holder_input_info );
                $res = $this->db->get( 'user' );
                //是否用户存在
                if( $res->num_rows() != 1 ){

                        //用户不存在
                        $this->session->set_flashdata( 'opt_solution_info_res' , 
                                array( 'res'=>FALSE , 'info'=>'该用户（卖家）不存在，请确认卖家信息。' ) );
                        header( 'Location: /admin/add_new_solution' );
                        exit;
                }else{

                        if( $holder_form != 'id' ){

                                $holder_info = $res->result_array();
                                $holder_id = $holder_info[0]['user_id'];
                        }else{

                                //用户直接提供的id
                                $holder_id = $holder_input_info;
                        }
                }

                //验证用户输入的信息是否有敏感词汇
                //并进行过滤
                $this->load->library( 'secur_process' );
                $solution_title = $this->secur_process->filter_user_input( $solution_title );
                $solution_describe = $this->secur_process->filter_user_input( $solution_describe );

                $solution_info = array( 'title'=>$solution_title , 'describe'=>$solution_describe , 
                        'holder_id'=>$holder_id , 'original_price'=>$sum_ori_price , 'sum_now_price'=>$sum_now_price , 'discount'=>$discount );

                //循环得到该方案的商品信息
                $product_no = 0;
                do{
                        $title_key = 'title' . $product_no;
                        $original_price_key = 'original_price' . $product_no;
                        $describe_key = 'describe' . $product_no;

                        //获得 product 信息
                        $title = $this->input->post( $title_key );
                        $original_price = $this->input->post( $original_price_key );
                        $describe = $this->input->post( $describe_key );

                        //过滤用户输入的敏感词
                        $title = $this->secur_process->filter_user_input( $title );
                        $describe = $this->secur_process->filter_user_input( $describe );

                        $product_infos[$product_no] = 
                                array( 'title'=>$title , 'original_price'=>$original_price , 'describe'=>$describe ); 

                        $product_no += 1;

                }while( isset( $_POST['title' . $product_no ] ) && isset( $_POST['original_price' . $product_no ] ) );

                //插入方案信息
                $this->db->insert( 'solution' , $solution_info );

                //获得刚插入的方案编号
                $res = $this->db->query( 'SELECT LAST_INSERT_ID() AS `solution_id`' );
                $row = $res->result_array();
                $solution_id = $row[0]['solution_id'];

                //添加新的方案信息
                foreach( $product_infos as $no=>$info ){

                        if( !empty( $info ) ){
                                //逐条写入
                                $this->db->insert( 'product' ,
                                        array( 'solution_id'=>$solution_id , 
                                        'original_price'=>$info['original_price'] , 
                                        'title'=>$info['title'] , 
                                        'describe'=>$info['describe']
                                ));
                        }
                }

                $this->session->set_flashdata( 'opt_solution_info_res' , 
                        array( 'res'=>TRUE , 'info'=>'添加方案成功.' ) );
                header( 'Location: /admin/solution_admin/time/desc' );
                exit;
        }

        public function do_disable( $solution_id  ){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                $this->db->where( 'id' , $solution_id );
                $this->db->update( 'solution' , array( 'disable'=>'true' ) );

                if ( $this->db->affected_rows() == 1 ){

                        echo '{"res":true,"info":"操作成功"}';
                        return;
                }

                echo '{"res":false,"info":"操作失败"}';
                return;
        }

        public function undo_disable( $solution_id ){   

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                $this->db->where( 'id' , $solution_id );
                $this->db->update( 'solution' , array( 'disable'=>'false' ) );

                if ( $this->db->affected_rows() == 1 ){

                        echo '{"res":true,"info":"操作成功"}';
                        return;
                }

                echo '{"res":false,"info":"操作失败"}';
                return;
        }

        public function do_delete_solution( $solution_id ){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                $this->db->where( 'id' , $solution_id );
                $this->db->delete( 'solution' );

                $opt_tran_res = $this->session->set_flashdata( 'opt_solution_info_res' ,
                        array( 'res'=>TRUE , 'info'=>'删除成功' ) );

                //删除方案之后回到原先的页面
                header( 'Location: ' . $_SERVER['HTTP_REFERER'] );
                exit;
        }

        /**
         * 交易管理
         *
         */

        public function tran_admin(){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                $total_rows = $this->db->count_all( 'transaction' );
                //分页显示
                $this->load->library('pagination');

                $config['base_url'] = "/admin/tran_admin?";              
                $config['total_rows'] = $total_rows;
                $config['per_page'] = 25;
                $config['page_query_string'] = TRUE;

                $config['full_tag_open'] = '<p>';
                $config['full_tag_close'] = '</p>';

                $config['prev_link'] = '上一页';
                $config['next_link'] = '下一页';
                $config['last_link'] = TRUE;
                $config['first_link'] = TRUE;
                $config['cur_tag_open'] = '<span>';
                $config['cur_tag_close'] = '</span>';
                $config['first_link'] = '第一页';
                $config['last_link'] = '最后一页';

                $this->pagination->initialize( $config );
                $offset = $this->input->get( 'per_page' );
                if( empty( $offset ) ){

                        $offset = 0;
                }

                //获得全部交易信息
                $this->db->order_by( 'id' , 'DESC' );
                $res = $this->db->get( 'transaction' , 25 , $offset );
                if( is_object( $res ) ){

                        $tran_infos = $res->result_array();
                }else{

                        $tran_infos = array();
                }

                //循环得到其他相关信息
                foreach( $tran_infos as $no=>$tran_info ){

                        $this->db->where( 'id' , $tran_info['solution_id'] );
                        $res = $this->db->get( 'solution' );
                        $row = $res->result_array();
                        @$solution_info = $row[0];

                        //得到卖家昵称以及城市信息
                        $this->db->where( 'user_id' , $solution_info['holder_id'] );
                        $res = $this->db->get( 'user' );
                        $row = $res->result_array();
                        @$holder_info = $row[0];

                        //得到买家信息
                        $this->db->where( 'user_id' , $tran_info['buyer_id'] );
                        $res = $this->db->get( 'user' );
                        $row = $res->result_array();
                        @$buyer_info = $row[0];

                        $tran_infos[$no]['holder_info'] = $holder_info;
                        $tran_infos[$no]['buyer_info'] = $buyer_info;
                } 

                $opt_tran_res = $this->session->flashdata( 'opt_tran_res' );

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'active'=>'tran' , 'include_page'=>'tran_admin' , 'sub_nav'=>'tran_sub_nav' , 'sub_active'=>'tran_admin' , 'opt_tran_res'=>$opt_tran_res , 
                        'admin_info'=>$admin_info , 'tran_infos'=>$tran_infos , 'total_rows'=>$total_rows ) );
        } 

        public function setting_tran_expire_time(){

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                $this->config->load( 'tran' );
                $tran_expire_time = $this->config->item( 'transaction_expire_time' );

                $opt_tran_info_res = $this->session->flashdata( 'opt_tran_info_res' );

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'include_page'=>'setting_tran_expire_time' , 'sub_nav'=>'tran_sub_nav' , 'active'=>'tran' ,
                        'sub_active'=>'setting_tran_expire_time' , 'opt_tran_info_res'=>$opt_tran_info_res ,
                        'admin_info'=>$admin_info , 'tran_expire_time'=>( $tran_expire_time/86400 ) ) );
        }

        public function do_setting_tran_expire_time(){

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                $tran_expire_time = $this->input->post( 'expire' );

                //判断用户输入
                if( !is_numeric( $tran_expire_time ) ){

                        $this->session->set_flashdata( 'opt_tran_info_res' , array( 'res'=>FALSE , 'info'=>'用户输入非法.' ) );
                        header( 'Location: /admin/setting_tran_expire_time' );
                        exit;
                }

                //换算成秒
                $tran_expire_time = $tran_expire_time * 86400;

                //写入文件
                $config_file_content ='<?php  if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');
                /**
                 * 方案配置文件 
                 *
                 */
                $config[\'transaction_expire_time\'] = \''. $tran_expire_time . '\';';

                $this->load->helper( 'file' );

                write_file( getcwd().'/application/config/tran.php' , $config_file_content );

                $this->session->set_flashdata( 'opt_tran_info_res' , array( 'res'=>TRUE , 'info'=>'修改配置成功.' ) );
                header( 'Location: /admin/setting_tran_expire_time' );
                exit;
        }

        public function do_delete_tran( $tran_id ){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                $this->db->where( 'id' , $tran_id );
                $this->db->delete( 'transaction' );

                $opt_tran_res = $this->session->set_flashdata( 'opt_tran_res' ,
                        array( 'res'=>TRUE , 'info'=>'删除成功' ) );

                header( 'Location: ' . $_SERVER['HTTP_REFERER'] );
                exit;
        }

        public function tran_admin_search(){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'active'=>'tran' , 'include_page'=>'tran_admin_search' , 'sub_nav'=>'tran_sub_nav' ,
                        'sub_active'=>'tran_admin_search' , 'admin_info'=>$admin_info ) );
        }

        /**
         * 根据用户输入的交易编码找到相应的
         * 交易信息，并显示
         */
        public function do_tran_admin_search(){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                //如过时分页显示，查询条件已经设置
                $q = $this->input->get( 'q' );
                $t = $this->input->get( 't' );
                if( !empty( $q ) && !empty( $t ) ){

                        $total_rows = $t;
                        $sql = base64_decode( $q );      
                }else{

                        //得到用户输入
                        $tran_code = trim( $this->input->get( 'tran_code' ) );
                        $loc_city = trim( $this->input->get( 'loc_city' ) );
                        $holder_info_key = $this->input->get( 'holder_info_key' );
                        $holder_info_value = $this->input->get( 'holder_info_value' );
                        $buyer_info_key = $this->input->get( 'buyer_info_key' );
                        $buyer_info_value = $this->input->get( 'buyer_info_value' );
                        $bought_time_start = $this->input->get( 'bought_time_start' );
                        $bought_time_end = $this->input->get( 'bought_time_end' );

                        $tran_status = $this->input->get( 'tran_status' );

                        //得到买家卖家的城市信息
                        $city_saler = $this->input->get( 'city_saler' );
                        $city_buyer = $this->input->get( 'city_buyer' );

                        //设置了买家信息
                        if( !empty( $city_buyer ) ){

                                $this->db->where( 'city' , $city_buyer );
                        }
                        if( !empty( $buyer_info_value ) ){

                                if( $buyer_info_key == 'user_id' ){

                                        $this->db->where( $buyer_info_key , $buyer_info_value );
                                }else{

                                        //其他条件使用 like 查询
                                        $this->db->where( $buyer_info_key , $buyer_info_value );
                                }
                        }
                        if( !empty( $city_buyer ) || !empty( $buyer_info_value ) ){

                                $buyer_ids_by_saler_info = array();
                                $res = $this->db->get( 'user' );
                                $buyer_infos = $res->result_array();
                                //仅取出 user_id
                                foreach( $buyer_infos as $no=>$buyer_info ){

                                        $buyer_ids[$no] = $buyer_info['user_id'];
                                }
                        }
                        //echo $this->db->last_query();
                        //var_dump( $buyer_ids );
                        //die;

                        //设置了卖家用户信息
                        if( !empty( $city_saler ) ){

                                $this->db->where( 'city' , $city_saler );
                        }
                        if( !empty( $holder_info_value ) ){

                                if( $holder_info_key == 'user_id' ){

                                        $this->db->where( $holder_info_key , $holder_info_value );
                                }else{
                                        //其他条件使用 like 查询                
                                        $this->db->where( $holder_info_key , $holder_info_value );
                                }
                        }
                        if( !empty( $holder_info_value ) || !empty( $city_saler ) ){

                                $holder_ids_by_saler_info = array();
                                $res = $this->db->get( 'user' );
                                $holder_infos = $res->result_array();

                                //仅取出id
                                foreach( $holder_infos as $no=>$holder_info ){

                                        $holder_ids_by_saler_info[$no] = $holder_info['user_id'];
                                }

                                //得到符合条件的所有用户的所有方案
                                if( !empty( $holder_ids_by_saler_info ) ){

                                        $solution_ids_by_saler_info = array();
                                        $this->db->where_in( 'holder_id' , $holder_ids_by_saler_info );
                                        $res = $this->db->get( 'solution' );
                                        $solution_infos = $res->result_array();

                                        //仅取出 solution 的 id
                                        foreach( $solution_infos as $no=>$solution_info ){

                                                $solution_ids_by_saler_info[$no] = $solution_info['id'];
                                        }
                                }
                        }
                        //echo $this->db->last_query();
                        //var_dump( $solution_ids_by_saler_info );
                        //die;

                        //按交易信息
                        if( !empty( $tran_code ) ){

                                $this->db->where( 'code' , $tran_code );
                        }
                        if( !empty( $bought_time_start ) ){

                                $this->db->where( 'time >=' , $bought_time_start );
                        }
                        if( !empty( $bought_time_end ) ){

                                $this->db->where( 'time <=' , $bought_time_end );
                        }
                        if( !empty( $tran_status ) ){

                                $this->db->where( 'status' , $tran_status );
                        }

                        if( isset( $buyer_ids ) ){

                                if( !empty( $buyer_ids ) ){

                                        $this->db->where_in( 'buyer_id' , $buyer_ids );
                                }else{
                                        $this->db->where_in( 'buyer_id' , '' );
                                }
                        }
                        if( isset( $solution_ids_by_saler_info ) ){

                                if( !empty( $solution_ids_by_saler_info ) ){

                                        $this->db->where_in( 'solution_id' , $solution_ids_by_saler_info );
                                }else{

                                        $this->db->where_in( 'solution_id' , '' );
                                }
                        }               

                        //获得相关交易的信息

                        //现获得相关交易数量 用户分页显示
                        $res = $this->db->get( 'transaction' );
                        //记录下sql语句
                        $sql = $this->db->last_query();
                        $q = urlencode( base64_encode( $sql ) );
                        $total_rows = $res->num_rows();
                }

                //分页显示
                $this->load->library('pagination');
                $config['base_url'] = "/admin/do_tran_admin_search?t=$total_rows&&q=" . urlencode( $q );         
                $config['per_page'] = 25;
                $config['total_rows'] = $total_rows;
                $config['page_query_string'] = TRUE;

                $config['full_tag_open'] = '<p>';
                $config['full_tag_close'] = '</p>';

                $config['prev_link'] = '上一页';
                $config['next_link'] = '下一页';
                $config['last_link'] = TRUE;
                $config['first_link'] = TRUE;
                $config['cur_tag_open'] = '<span>';
                $config['cur_tag_close'] = '</span>';
                $config['first_link'] = '第一页';
                $config['last_link'] = '最后一页';

                $this->pagination->initialize( $config );
                $offset = $this->input->get( 'per_page' );
                if( empty( $offset ) ){

                        $offset = 0;
                }

                $res = $this->db->query( $sql . "ORDER BY `id` DESC LIMIT $offset , 25"  );
                //echo $this->db->last_query();die;
                if( is_object( $res ) ){

                        $tran_infos = $res->result_array();
                }else{

                        $tran_infos = array();
                }

                //循环得到其他相关信息
                foreach( $tran_infos as $no=>$tran_info ){

                        $this->db->where( 'id' , $tran_info['solution_id'] );
                        $res = $this->db->get( 'solution' );
                        $row = $res->result_array();
                        //@todo 
                        @$solution_info = $row[0];

                        //得到卖家昵称以及城市信息
                        $this->db->where( 'user_id' , $solution_info['holder_id'] );
                        $res = $this->db->get( 'user' );
                        $row = $res->result_array();
                        @$holder_info = $row[0];

                        //得到买家信息
                        $this->db->where( 'user_id' , $tran_info['buyer_id'] );
                        $res = $this->db->get( 'user' );
                        $row = $res->result_array();
                        @$buyer_info = $row[0];

                        $tran_infos[$no]['holder_info'] = $holder_info;
                        $tran_infos[$no]['buyer_info'] = $buyer_info;
                } 

                $opt_tran_res = $this->session->flashdata( 'opt_tran_res' );

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'active'=>'tran' , 'include_page'=>'tran_admin_search_result' , 'sub_nav'=>'tran_sub_nav' ,
                        'sub_active'=>'tran_admin_search_result' , 'opt_tran_res'=>$opt_tran_res , 
                        'admin_info'=>$admin_info , 'tran_infos'=>$tran_infos , 'total_rows'=>$total_rows ) );

        }

        public function view_tran_detail( $tran_id ){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                //根据得到的交易编号，
                //显示交易的详细信息
                $this->db->where( 'id' , $tran_id );

                //获得相关交易的信息
                $res = $this->db->get( 'transaction' );

                if( $res->num_rows() != 1 ){

                        $this->session->set_flashdata( 'tran_search_res' , array( "res"=>false,"info"=>"对应的交易不存在." ) ); 
                        header( 'Location: /admin/tran_admin_search' );
                        exit;
                }
                $tran_info = $res->result_array();
                $tran_info = $tran_info[0];

                //得到方案的详细信息
                $solution_id = $tran_info['solution_id'];
                $this->db->where( 'id' , $solution_id );
                $res = $this->db->get( 'solution' );
                $solution_info = $res->result_array();
                $solution_info = $solution_info[0];

                //得到方案相关商品的消息
                $this->db->where( 'solution_id' , $solution_id );
                $res = $this->db->get( 'product' );
                $product_infos = $res->result_array();

                //得到卖家的信息
                $holder_id = $solution_info['holder_id'];
                $this->db->where( 'user_id' , $holder_id );
                $res = $this->db->get( 'user' );
                $holder_info = $res->result_array();
                $holder_info = $holder_info[0];

                //得到买家的信息
                $this->db->where( 'user_id' , $tran_info['buyer_id'] );
                $res = $this->db->get( 'user' );
                $buyer_info = $res->result_array();
                $buyer_info = $buyer_info[0];

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'active'=>'tran' , 'include_page'=>'view_tran_detail' , 'sub_nav'=>'tran_sub_nav' ,
                        'sub_active'=>'' , 'admin_info'=>$admin_info , 'buyer_info'=>$buyer_info , 'tran_info'=>$tran_info ,
                        'holder_info'=>$holder_info , 'solution_info'=>$solution_info , 'product_infos'=>$product_infos ) );
        }

        /**
         * 安全设置
         */
        public function secur_admin(){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                $do_post_domain_limit_res = $this->session->flashdata( 'do_post_domain_limit_res' );

                //从配置文件中读取以前的设置信息
                $this->config->load( 'secur' );

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'active'=>'secur' , 'include_page'=>'secur_admin' , 'sub_nav'=>'secur_sub_nav' ,
                        'sub_active'=>'secur_admin' , 'admin_info'=>$admin_info , 'do_post_domain_limit_res'=>$do_post_domain_limit_res ,
                        'domain_limit'=>$this->config->item( 'domain_limit' ) , 'post_limit'=>$this->config->item( 'post_limit' ) ) );
        }

        /**
         * 设置域名以及用户输入中的敏感词
         */
        public function do_post_domain_limit(){

                $domain_limit = $this->input->post( 'domain_limit' );
                $post_limit = $this->input->post( 'post_limit' );

                $config_file_content ='<?php  if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');
                /**
                 * 过滤配置文件
                 *
                 */
                $config[\'domain_limit\'] = \''. $domain_limit . '\';' . "\n" .
                        '$config[\'post_limit\'] = \'' . $post_limit . '\';';

                //写入配置文件 secur.php
                $this->load->helper( 'file' );

                write_file( getcwd().'/application/config/secur.php' , $config_file_content );

                //返回设置页面
                $this->session->set_flashdata( 'do_post_domain_limit_res' , array( 'res'=>TRUE , 'info'=>'更改设置成功.' ) );
                header( 'Location: /admin/secur_admin' );
        }

        /**
         * 设置ip访问规则
         *
         */
        public function secur_admin_ip_acl(){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                $do_secur_admin_ip_acl_res = $this->session->flashdata( 'do_secur_admin_ip_acl' );

                //从配置文件中读取以前的设置信息
                $this->config->load( 'ip_acl' );

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'active'=>'secur' , 'include_page'=>'secur_admin_ip_acl' , 'sub_nav'=>'secur_sub_nav' ,
                        'sub_active'=>'secur_admin_ip_acl' , 'admin_info'=>$admin_info , 'do_secur_admin_ip_acl_res'=>$do_secur_admin_ip_acl_res ,
                        'ip_acl'=>$this->config->item( 'ip_acl' ) ) );
        }

        public function do_secur_admin_ip_acl(){

                $ip_acl = $this->input->post( 'ip_acl' );

                $config_file_content ='<?php  if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');
                /**
                 * ip黑名单配置文件
                 *
                 */
                $config[\'ip_acl\'] = \''. $ip_acl . '\';' ;

                //写入配置文件 secur.php
                $this->load->helper( 'file' );

                write_file( getcwd().'/application/config/ip_acl.php' , $config_file_content );

                //返回设置页面
                $this->session->set_flashdata( 'do_secur_admin_ip_acl' , array( 'res'=>TRUE , 'info'=>'更改设置成功.' ) );
                header( 'Location: /admin/secur_admin_ip_acl' );
        }

        /**
         * 权限管理 
         */
        public function priv_admin(){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                $opt_priv_res = $this->session->flashdata( 'opt_priv_res' );

                //得到全部的管理员信息
                $this->db->order_by( 'last_login_time' , 'DESC' );
                $res = $this->db->get( 'admini' );
                $admin_infos = $res->result_array();

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'active'=>'priv' , 'include_page'=>'priv_admin' , 'sub_nav'=>'priv_sub_nav' ,
                        'sub_active'=>'priv_admin' , 'admin_info'=>$admin_info , 'opt_priv_res'=>$opt_priv_res ,
                        'admin_infos'=>$admin_infos ) );
        }

        public function edit_admin_info( $admin_id ){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                //判断管理员权限
                $this->check_admin_priv( $admin_info , 'super' , '/admin/priv_admin' , 'opt_priv_res' );

                $do_secur_admin_ip_acl_res = $this->session->flashdata( 'do_secur_admin_ip_acl' );

                //得到全部的管理员信息
                $this->db->where( 'id' , $admin_id );
                $res = $this->db->get( 'admini' );
                $admin_info = $res->result_array();

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' ,
                        array( 'active'=>'priv' , 'include_page'=>'admin_info_edit' , 'sub_nav'=>'priv_sub_nav' , 
                        'sub_active'=>'' , 'admin_info'=>$admin_info , 'do_secur_admin_ip_acl_res'=>$do_secur_admin_ip_acl_res ,
                        'admin_info'=>$admin_info[0] ) );
        }

        public function add_new_admin(){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                //判断管理员权限
                $this->check_admin_priv( $admin_info , 'super' , '/admin/add_new_admin' , 'do_add_new_admin_res' );

                $do_add_new_admin_res = $this->session->flashdata( 'do_add_new_admin_res' );

                $this->load->view( 'div/admin_html_info.php' );
                //传入需要显示的页面
                $this->load->view( 'admin/base' , 
                        array( 'active'=>'priv' , 'include_page'=>'add_new_admin' , 'sub_nav'=>'priv_sub_nav' ,
                        'sub_active'=>'add_new_admin' , 'admin_info'=>$admin_info , 'do_add_new_admin_res'=>$do_add_new_admin_res ) );
        }

        /**
         * 检测管理员的权限是否满足本操作的要求
         *
         * @param array $admin_info 当前登录管理员的信息
         * @param string $need_priv 需要的权限等级
         * @param string $loction_url 发生错误时需要定向到得页面
         * @param string $err_var 保存在 session 中的错误信息变量名
         */
        private function check_admin_priv( $admin_info , $need_priv , $loction_url , $err_var ){

                //判断管理员权限
                if( $admin_info['priv'] != $need_priv ){

                        $this->session->set_flashdata( $err_var , 
                                array( 'res'=>FALSE , 'info'=>'当前管理员账号没有权限执行本操作.' ) );
                        header( "Location: $loction_url" );
                        exit;
                }
        }

        public function do_add_new_admin(){

                $this->load->database();

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                //判断管理员权限
                $this->check_admin_priv( $admin_info , 'super' , '/admin/add_new_admin' , 'do_add_new_admin_res' );

                $admin_name = $this->input->post( 'admin_name' );
                $passwd = $this->input->post( 'passwd' );
                $priv = $this->input->post( 'priv' );

                if( empty( $admin_name ) ){

                        $this->session->set_flashdata( 'do_add_new_admin_res' , 
                                array( 'res'=>FALSE , 'info'=>'请填写管理员账号名.' ) );
                        header( 'Location: /admin/add_new_admin' );
                        exit;
                } 
                if( empty( $passwd ) ){

                        $this->session->set_flashdata( 'do_add_new_admin_res' ,
                                array( 'res'=>FALSE , 'info'=>'请填写管理员密码.' ) );
                        header( 'Location: /admin/add_new_admin' );
                        exit;
                } 

                //判断有没有管理员命名冲突
                $this->db->where( 'admin_name' , $admin_name );
                $res = $this->db->get( 'admini' );
                if( $res->num_rows() != 0 ){

                        $this->session->set_flashdata( 'do_add_new_admin_res' ,
                                array( 'res'=>FALSE , 'info'=>'此管理员名已经存在于系统中.' ) );
                        header( 'Location: /admin/add_new_admin' );
                        exit;
                }

                //写入数据库
                $this->db->insert( 'admini' , 
                        array( 'admin_name'=>$admin_name , 'priv'=>$priv , 'passwd'=>md5($passwd) ) );

                if( $this->db->affected_rows() == 1 ){

                        $this->session->set_flashdata( 'opt_priv_res' ,
                                array( 'res'=>TRUE , 'info'=>'添加管理员成功.' ) );
                        header( 'Location: /admin/priv_admin' );
                        exit;
                }
        }

        public function do_delete_admin( $admin_id ){

                //判断管理员是否登录系统
                $admin_info = $this->is_admin_login();

                //判断管理员权限
                $this->check_admin_priv( $admin_info , 'super' , '/admin/priv_admin' , 'opt_priv_res' );

                if( !is_numeric( $admin_id ) ){

                        //传入的管理员id不合法 
                        exit;
                }

                $this->load->database();

                //执行删除操作
                $this->db->where( 'id' , $admin_id );
                $this->db->delete( 'admini' );

                if( $this->db->affected_rows() == 1 ){

                        $this->session->set_flashdata( 'opt_priv_res' ,
                                array( 'res'=>TRUE , 'info'=>'删除管理员成功.' ) );
                        header( 'Location: /admin/priv_admin' );
                        exit;
                }else{

                        $this->session->set_flashdata( 'opt_priv_res' ,
                                array( 'res'=>FALSE , 'info'=>array( 0=>'改管理员可能已经不存在，请刷新本页面.' ) ) );
                        header( 'Location: /admin/priv_admin' );
                        exit;
                }
        }
}
