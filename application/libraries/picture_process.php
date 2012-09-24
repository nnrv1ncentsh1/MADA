<?php
//
// 图片处理
// @author <judasnow@gmail.com>
//
class picture_process{
//{{{
	private $_config;
	private $_CI;

	public function __construct(){
	//{{{
		$this->_CI =& get_instance();
	}//}}}

	//修改用户上传文件的格式
	//统一为 jpg 格式
	//@param $temp_file_name 保存的临时文件名称
	private function do_tran_format_to_jpg( $temp_file_name ){
	//{{{
		$img_info = $this->_CI->upload->data();
		$file_ext = $img_info['file_ext'];
		$file_type = $img_info['file_type'];
		$file_name = $temp_file_name;
		//已上传文件的绝对路径
		$input = $img_info['full_path'];
		//输出的jpg文件路径
		$output = $img_info['file_path'] . $file_name . '.jpg';
		if( defined( 'ROOTPATH' ) && ROOTPATH ) return;
		switch( $file_type ){
			case 'image/jpeg':
				//如果是 jpeg 则统一其
				//后缀名为 jpg
				if ( file_exists( $output ) ){
					//删除原来的文件
					unlink( $output );
				}
				rename( $input , $output );
				break;
			case 'image/png':
				$image = imagecreatefrompng( $input );
				imagejpeg( $image , $output , 100 );
				imagedestroy( $image );
				break;
			case 'image/gif': 
				$image = imagecreatefromgif( $input );
				imagejpeg( $image , $output , 100 );
				imagedestroy( $image );
				break;
			case 'image/wbmp':
				$image = imagecreatefromwbmp( $input );
				imagejpeg( $image , $output , 100 );
				imagedestroy( $image );
				break;
		}
		@unlink( $input );
		return true;
	}//}}}

	//获取源文件的尺寸信息
	//@param string $source_img 源文件名称 
	//@return array
	private function get_img_info( $source_img_with_path ){
	//{{{
		$raw_source_img_info = getimagesize( $source_img_with_path );
		return array( 'width'=>$raw_source_img_info[0] , 'height'=>$raw_source_img_info[1] );
	}//}}}

	//处理用户图片上传
	//@param string $file_name 表单中file控件的name属性
	//@param string $temp_file_name 保存的临时文件的名称
	public function do_upload( $file_name , $temp_file_name ){
	//{{{
		$upload_config['overwrite'] = TRUE;
		$upload_config['upload_path'] = getcwd() . '/upload/';
		$upload_config['allowed_types'] = 'jpg|gif|png|jpeg|bmp';
		$upload_config['max_size'] = '2048';
		$upload_config['max_width'] = '';
		$upload_config['max_height'] = '';
		$upload_config['remove_spaces'] = TRUE;
		$upload_config['overwrite'] = TRUE;
		//调用文件上传库
		$this->_CI->load->library( 'upload' , $upload_config );
		//处理文件上传
		if ( !$this->_CI->upload->do_upload( $file_name ) ){
			//上传图片出现错误
			throw new Exception( '处理用户上传图片时出错:' . $this->_CI->upload->display_errors() );
		}
		//尝试转换格式
		$this->do_tran_format_to_jpg( $temp_file_name );
	}//}}}

	//根据需要重设图片的大小
	public function do_resize( $source_img , $new_img , $thumb_marker , $max_size ){
	//{{{
		//注意此处 因为肯行是在转换图片格式之后调用该函数的 因此可以大胆的假设格式为 jpg
		$source_img_with_path = join( '' , array( getcwd() , '/upload/' , $source_img . '.jpg' ) );
		if( !file_exists( $source_img_with_path ) ){
			throw new Exception( '重设图片大小时，指定的文件不存在:file_name=' . $source_img );
		}
		$source_img_info = $this->get_img_info( $source_img_with_path );
		if( empty( $new_img ) ){
			$new_img = uniqid( '' ) . '.jpg';
		}
		$img_config['image_library'] = 'gd2';
		$img_config['source_image'] = $source_img_with_path;
		$img_config['create_thumb'] = TRUE;
		$img_config['maintain_ratio'] = TRUE;
		$img_config['thumb_marker'] = $thumb_marker;
		$img_config['new_image'] = $new_img;

		$img_config['width'] = $source_img_info['width']>=$max_size ? $max_size : $source_img_info['width'];
		$img_config['height'] = $source_img_info['height']>=$max_size ? $max_size : $source_img_info['height'];

		$this->_CI->load->library( 'image_lib' , $img_config );
		if ( !$this->_CI->image_lib->resize() ){
			throw new Exception( "重设文件尺寸时出错:" . $this->_CI->image_lib->display_errors() );
		}
	}//}}}

	//根据用户提交的坐标对图片进行裁剪
	//@dimension array 裁剪的尺寸
	//@for_resize array 需要缩放的尺寸
	public function do_crop( $source_img , $dimension = array() , $have_to_resize = array( '48' ) ){
	//{{{
		$source_img_with_path = join( '' , array( getcwd() , '/upload/' , $source_img . '.jpg' ) );
		$croped_img_with_path = join( '' , array( getcwd() , '/upload/' , $source_img , '_croped.jpg' ) );
		if( !file_exists( $source_img_with_path ) ){
			throw new Exception( '裁剪图片时，指定的文件不存在:file_name=' . $source_img_with_path );
		}
		if( empty( $dimension ) ){
			//@todo 算法需要优化 使用 opencv
			$source_img_info = $this->get_img_info( $source_img_with_path );
			$dimension['x1'] = ceil( $source_img_info['width'] / 3 );
			$dimension['y1'] = ceil( $source_img_info['height'] / 3 );
			$dimension['x2'] = $dimension['x1'] + 64;
			$dimension['y2'] = $dimension['y1'] + 64;
		}
		$this->_CI->load->library( 'image_moo' );
		$this->_CI->image_moo->load( $source_img_with_path );
		$this->_CI->image_moo->crop( $dimension['x1'] , $dimension['y1'] , $dimension['x2'] , $dimension['y2'] );
		$this->_CI->image_moo->save( $croped_img_with_path , TRUE );
		//对裁剪过的图片进行缩放
		$this->_CI->image_moo->load( $croped_img_with_path );
		foreach( $have_to_resize as $i => $size ){
			$this->_CI->image_moo->resize( $size , $size );
			$this->_CI->image_moo->save( 
				join( '' , array( getcwd() , '/upload/' , $source_img , '_croped_' , $size , '.jpg' ) ), TRUE );
		}
		if( $this->_CI->image_moo->errors ){
			throw new Exception( "裁剪图片时出错" . $this->_CI->image_moo->display_errors() );
		}
	}//}}}
}//}}}


