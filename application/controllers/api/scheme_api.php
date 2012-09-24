<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
// Scheme(方案) RESTful api controller
// @auther <judasnow@gmail.com>
//
require( APPPATH . '/libraries/REST_Controller.php' );

class Scheme_api extends REST_Controller{

        //判断是否为合法的 scheme_id
        private function is_legal_scheme_id( $id ){
        //{{{
                if( empty( $id ) || !is_numeric( $id ) ){
                        $this->response( array( 'error' => 'scheme id is illegal.' ) , 400 );
                        return FALSE;
                }
                return TRUE;
        }//}}}

        //格式化时间( for slider time )
        private function format_time( $time ){
        /*{{{*/
                if( empty( $time ) ){
                        return '';
                }
                return date( 'G:s - Y.n.d' , strtotime( $time ) );
        }/*}}}*/

        //获取方案的基本信息
        public function info_get(){
        //{{{
                $scheme_id = $this->get( 'scheme_id' , TRUE );
                if( !$this->is_legal_scheme_id( $scheme_id ) ){
                        return;
                }
                $this->load->model( 'Scheme' , 'Scheme_m' , TRUE );
                $scheme = $this->Scheme_m->find( array( 'scheme_id'=>$scheme_id ) );
                if( !empty( $scheme ) ){
                        $this->response( $scheme , 200 );
                }else{
                        $this->response( array('error' => 'scheme could not be found.') , 404 );
                }
        }//}}}

        //提交新的方案信息
        public function info_post(){
        //{{{
                $this->load->model( 'Scheme' , 'scheme_m' , TRUE );
                $this->load->model( 'Product' , 'product_m' , TRUE );
                $this->load->library( 'user_auth' );
                if( !$this->user_auth->is_user_login() ){
                        $this->response( array('error' => 'user did not login yet.') , 500 );
                }else{
                        $holder_id = $this->session->userdata( 'user_id' );
                        //获取 holder_info 
                        $holder_info = $this->user_m->find( array( 'user_id'=>$holder_id ) );
                }
                $title = $this->post( 'title' , TRUE );
                $describe = $this->post( 'describe' , TRUE );
                $sum_ori_price = $this->post( 'sumOriPrice' , TRUE );
                $sum_now_price = $this->post( 'sumNowPrice' , TRUE );
                $discount = $this->post( 'discount' , TRUE );
                $products = $this->post( 'products' , TRUE );
                $img_name = $this->post( 'imgName' , TRUE );
                //如果
                //尝试插入方案
                //需要使用事务
                try{
                        $this->db->trans_start();
                        //写入方案信息本身
                        $this->scheme_m->add_new( array( 
                                'title'=>$title,
                                'describe'=>$describe,
                                'holder_id'=>$holder_id,
                                'sum_now_price'=>$sum_now_price,
                                'original_price'=>$sum_ori_price,
                                'img_name'=>$img_name
                        ));
                        $scheme_id = $this->db->insert_id();
                        //写入方案相关的商品信息
                        $this->product_m->add_news( $products , $scheme_id );
                        $this->db->trans_complete();
                        if( $this->db->trans_status() === FALSE ){
                                throw new Exception( '执行添加方案事务时出错.' );
                        }
                        //返回该方案的内部自动生成id，以及数据库时间
                        $this->response( array(
                                'scheme_id'=>$scheme_id,
                                'time'=>date( 'Y-m-d H:i:s' , $_SERVER['REQUEST_TIME'] ),
                                'holder'=>$holder_info
                        ) , 200 );
                }catch( Exception $e ){
                        log_message( "插入新方案时出错: " . $e->getMessage() );
                        $this->response( array('error' => 'add new scheme error.') , 500 );
                }
        }//}}}

        //修改一条已经存在的方案
        public function info_put(){
        //{{{
        }//}}}

        //
        //获取方案列表中的下拉菜单信息
        //关键词为 time
        //需要返回的数据为
        //$holder nick 方案所属用户昵称
        //$retwetts 方案所属用户粉丝
        //$favorites 方案所属用户关注用户数
        //$post time 方案 发布/转发 时间
        //$expire time 方案超时时间
        public function slider_time_get(){
        //{{{
                $scheme_id = $this->get( 'scheme_id' , TRUE );
                if( !$this->is_legal_scheme_id( $scheme_id ) ){
                        return;
                }
                $this->load->model( 'Scheme' , 'scheme_m' , TRUE );
                $this->load->model( 'User' , 'user_m' , TRUE );
                $this->load->model( 'Follow' , 'follow_m' , TRUE );
                try{
                        $scheme = $this->scheme_m->find( array( 'scheme_id'=>$scheme_id ) );
                        if( empty( $scheme ) ){
                                $this->response( array('error' => 'scheme could not be found.') , 404 );
                        }
                        $holder_id = $scheme['holder_id'];
                        //获取方案所有者信息
                        $holder = $this->user_m->find( array( 'user_id'=>$holder_id ) );
                        $retwetts = $this->follow_m->get_favorites_by_user_id( $holder_id );
                        $favorites = $this->follow_m->get_retwetts_by_user_id( $holder_id );
                        $expire_time = $this->scheme_m->get_expire_time( $scheme_id );
                        $this->response( array(
                                'holder_nick'=>$holder['nick'],
                                'retwetts'=>$retwetts,
                                'favorites'=>$favorites,
                                //格式化时间
                                'post_time'=>$this->format_time( $scheme['time'] ),
                                'expire_time'=>$this->format_time( $expire_time )
                        ) , 200 );
                }catch( Exception $e ){
                        log_message( 'error' , '获取方案下拉列表信息(关键词为 time )时发生异常' . $e->getMessage() );
                        $this->response( array('error' => 'fetch slider time data error.') , 500 );
                }
        }//}}}

        //获取方案列表中的下拉菜单信息
        //关键词为 retweet
        public function slider_retweet_get(){
        //{{{
                //根据提供的 retweet_id 获取相应的转发信息
                //再根据 prev_retweet_id 决定是否需要显示转发链中的
                //上一条信息
                return NULL;
        }//}}}

        //关键词为 scheme 的下拉菜单信息
        //需要获取
        //1 商品信息
        //2 原价信息
        //3 现价
        //4 折扣
        //5 配图
        public function slider_detail_get(){
        //{{{
                $scheme_id = $this->get( 'scheme_id' , TRUE );
                if( !$this->is_legal_scheme_id( $scheme_id ) ){
                        return;
                }
                $this->load->model( 'Product' , 'product_m' , TRUE );
                $this->load->model( 'Scheme' , 'scheme_m' , TRUE );
                try{
                        //获取商品信息
                        $products = $this->product_m->find( array( 'scheme_id'=>$scheme_id ) );
                        //为商品信息获取序号
                        foreach( $products as $index=>$product ){
                                $products[$index]['no'] = $index + 1;
                        }
                        //获取方案信息
                        $scheme = $this->scheme_m->find( array( 'scheme_id'=>$scheme_id ) );
                        $this->response( array( 
                                'scheme_id'=>$scheme_id,
                                'products'=>$products,
                                'products_num'=>count( $products ),
                                'sum_now_price'=>$scheme['sum_now_price'],
                                'original_price'=>$scheme['original_price'],
                                'discount'=>$scheme['discount'],
                                'img_name'=>$scheme['img_name']
                        ) , 200 );
                }catch( Exception $e ){
                        log_message( 'error' , '获取方案下拉列表信息(关键词为 schemeDetail )时发生异常' . $e->getMessage() );
                        $this->response( array('error' => 'fetch slider scheme detail data error.') , 500 );
                }
        }//}}}

        //关键词为 tran 时的下拉菜单信息
        //需要获取 @todo 注意数据的复用,这些数据大多数都是已经获取了的
        //1 方案状态
        //2 方案所属用户等级
        //3 方案价格信息
        //4 支付方式的选择( 硬编码 for now )
        //5 购买/支付/评价 数
        public function slider_tran_get(){
        //{{{
                $scheme_id = $this->get( 'scheme_id' , TRUE );
                if( !$this->is_legal_scheme_id( $scheme_id ) ){
                        return;
                }
                $this->load->model( 'user' , 'user_m' , TRUE );
                $this->load->model( 'product' , 'product_m' , TRUE );
                $this->load->model( 'scheme' , 'scheme_m' , TRUE );
                $this->load->model( 'transaction' , 'transaction_m' , TRUE );
                try{
                        $scheme = $this->scheme_m->find( array( "scheme_id"=>$scheme_id ) );
                        //格式化 scheme status
                        $scheme = $this->scheme_m->format_status( $scheme );
                        $holder = $this->user_m->find( array( 'user_id'=>$scheme['holder_id'] ) );
                        //获取相关的交易信息
                        $trans_all_num = $this->transaction_m->count_all_by_scheme_id( $scheme_id );
                        $trans_paid_num = $this->transaction_m->count_paid_by_scheme_id( $scheme_id );
                        $this->response( array( 
                                'scheme'=>$scheme,
                                'holder'=>$holder,
                                'trans_all_num'=>$trans_all_num,
                                'trans_paid_num'=>$trans_paid_num
                        ) , 200 );
                }catch( Exception $e ){
                        log_message( 'error' , '获取方案下拉列表信息(关键词为 TranDetail )时发生异常' . $e->getMessage() );
                        $this->response( array('error' => 'fetch slider tran detail data error.') , 500 );
                }
        }//}}}

        //关键词为 more 时的下拉菜单
        //唯一需要返回的数据就是当前的方案是否是当前登录用户的(以此判断是否需要显示出关注按钮)
        public function slider_more_get(){
        //{{{
                $this->load->model( 'Follow' , 'follow_m' , TRUE );
                $this->load->model( 'Scheme' , 'scheme_m' , TRUE );
                $this->load->model( 'User' , 'user_m' , TRUE );
                $this->load->library( 'user_auth' );
                try{
                        if( $this->user_auth->is_user_login() ){
                                //若用户已经登录
                                $object_user_id = $this->session->userdata( 'user_id' );
                                //获取 holder_info 
                                //$holder_info = $this->user_m->find( array( 'user_id'=>$holder_id ) );
                        }
                        //当前相关的 scheme_id
                        $scheme_id = $this->get( 'scheme_id' , TRUE );
                        //由 scheme_id 获取方案所有者等信息
                        $scheme_info = $this->scheme_m->find( array( 'scheme_id'=>$scheme_id ) );
                        //判断是否为当前用户所属的方案
                        $is_self_scheme = TRUE;
                        $scheme_holder_id = $scheme_info['holder_id'];
                        if( $scheme_holder_id !== $object_user_id ){
                                $is_self_scheme = FALSE;
                        }
                        $this->response(
                                array( 
                                        'is_self_scheme' => $is_self_scheme
                                ),
                                200
                        );
                }catch( Exception $e ){
                        log_message( 'error' , '获取关键词为 more 时的下拉菜单时发生异常.[' . $e->getMessage() . ']' );
                        $this->response( 
                                array(
                                        'error'=>'fetch slider more data error.'
                                ),
                                500
                        );
                }
        }//}}}
}
