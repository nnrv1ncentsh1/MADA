<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//
// Upload_img(上传图片，用于添加新方案或者转发方案时) controller
// @auther <judasnow@gmail.com>
//
class Upload_img extends CI_Controller {
//{{{
	private static $_legal_img_with = array( 'retweet' , 'scheme' );
	//前端获取文件上传进度用的接口
	//!记得设置 apc.rfc1867 = 1
	public function ajax_do_get_upload_process(){
	//{{{
		$temp_file_name = $this->input->get( 'APC_UPLOAD_PROGRESS' , TRUE );
		$status = apc_fetch( 'upload_' . $temp_file_name );
		if( is_array( $status ) && $status['total'] > 0 ){
			$pre = $status['current'] / $status['total'] * 100;
			$res = array(
				'res'=>true , 
				'pre'=>$pre
			);
			if( $pre === 100 ){
				//上传图片的名称
				$res['img_name'] = $temp_file_name;
			}
			echo json_encode( $res );
		}else{
			echo json_encode( array( 'res'=>false ) );
		}
	}//}}}
	public function ajax_do_upload_picture(){
	//{{{
		$this->load->library( "picture_process" );
		$temp_file_name = $this->input->post( 'APC_UPLOAD_PROGRESS' , TRUE );
		$img_with = $this->input->post( 'img_with' , TRUE );
		try{
			if( !in_array( $img_with , self::$_legal_img_with ) ){
				throw new Exception( "传入的 img_with 值非法." );
			}
			$this->picture_process->do_upload( 'picture' , $temp_file_name );
			//缩放到合适的尺寸
			$this->picture_process->do_resize( $temp_file_name , $temp_file_name . '_resized.jpg' , '' , 200 );
			//裁剪小图片 并将uri返回给前端
			$this->picture_process->do_crop( $temp_file_name );
			//根据 $img_with 值将上传的图片转移到相应的文件夹中
			$base_path = getcwd();
			$upload_path = $base_path . '/upload/';
			//原始图片
			$ori_img_name = "$temp_file_name.jpg";
			//缩小后的图片
			$resized_img_name = "$temp_file_name" . "_resized.jpg";
			$target_path = $base_path . "/picture/$img_with/";
			switch( $img_with ){
				case 'retweet':
					rename( "$upload_path/$ori_img_name" , "$target_path/$ori_img_name" );
					rename( "$upload_path/$resized_img_name" , "$target_path/$resized_img_name" );
					break;
				case 'scheme':
					rename( "$upload_path/$ori_img_name" , "$target_path/$ori_img_name" );
					rename( "$upload_path/$resized_img_name" , "$target_path/$resized_img_name" );
					break;
			}
		}catch( Exception $e ){
			log_message( 'error' , '上传文件时发生错误' . $e->getMessage() );
		}
	}//}}}
}//}}}



