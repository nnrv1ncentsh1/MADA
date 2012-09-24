<?php
/**
 * 定义用户更新头像时的相关
 * 操作
 *
 * @author judasnow@gmail.com 
 */
class face_process{
/*{{{*/
	/**
	 * @var array 图片处理的相关参数
	 * @accress private 
	 */
	private $_config;
	/**
	 * @var CI 对象实例
	 * @accress private 
	 */
	private $CI;
	/**
	 * @var string 当前用户ID
	 * @accress private 
	 */
	private $_user_id;
	private $_sys_domain;
	/**
	 * 用户输入的信息
	 */
	private $_user_input_info;
	/**
	 * 用户头像图片信息
	 */
	private $_face_img_info;
	private $_is_upload;

	public function __construct( $param ){
	/*{{{*/
		$this->CI =& get_instance();
		$this->_user_id = $param['user_id']; 
		$this->_sys_domain = $param['sys_domain'];
		$this->_user_input_info_for_update = isset( $param['user_input_info'] ) ? $param['user_input_info'] : array();
	}/*}}}*/
	
	/**
	 * 得到图片信息的方式有两种
	 * 第一，如果用户新上传的图片，则直接从 CI 中获得 
	 * 第二，如果用户用的是以前图片，则从文件中读取信息
	 */
	public function get_img_info(){
	/*{{{*/
		if( $this->_is_upload ){
			$this->_face_img_info = $this->CI->upload->data();
		}else{
			$img_info = getimagesize( getcwd()."/picture/user_head_img/$this->_sys_domain".'_face.jpg' );
			$this->_face_img_info = array( 'image_width'=>$img_info[0] , 'image_height'=>$img_info[1] );
		}
	}/*}}}*/

	/**
	 * 修改用户上传文件的格式
	 * 统一为 jpg 格式,且文件名
	 * 均以 user_id 作为索引
	 */
	private function do_tran_format_to_jpg(){
	/*{{{*/
		$img_info = $this->CI->upload->data();
		$file_ext = $img_info['file_ext'];
		$file_type = $img_info['file_type'];
		$file_name = $this->_sys_domain;
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
		return true;
	}/*}}}*/

	/**
	 * 处理用户头像上传
	 *
	 * @accress public 
	 */
	public function do_upload(){
	/*{{{*/
		$this->_is_upload = TRUE;
		$upload_config['overwrite'] = TRUE;
		$upload_config['upload_path'] = getcwd().'/upload/';
		$upload_config['allowed_types'] = 'jpg|gif|png|jpeg|wbmp';
		$upload_config['max_size'] = '2048';
		$upload_config['max_width']  = '';
		$upload_config['max_height']  = '';
		$upload_config['remove_spaces']  = TRUE;
		//调用文件上传库
		$this->CI->load->library( 'upload' , $upload_config );
		//处理文件上传
		if ( !$this->CI->upload->do_upload() ){
			//上传图片出现错误
			//show_error( $this->CI->upload->display_errors() );
			$myface_error = array( 'content' => $this->CI->upload->display_errors( '<span>' , '</span>' ) );
			//设置 session 保存错误信息
			$this->CI->session->set_flashdata( 'myface_error' , $myface_error );
			return false;
		}
		//尝试转换格式
		$this->do_tran_format_to_jpg();
		return true;
	}/*}}}*/

	/**
	 * 根据需要重设图片的大小
	 *
	 * @accress public 
	 */
	public function do_resize( $source_img , $new_image , $thumb_marker , $max_size ){
	/*{{{*/
		//上传文件到upload文件夹成功
		//根据返回上传文件信息，以判断是否需要调整大小
		$this->get_img_info();
		//如果需要，调整图像大小
		$img_config['image_library'] = 'gd2';
		$img_config['source_image'] = $source_img;
		$img_config['create_thumb'] = TRUE;
		$img_config['maintain_ratio'] = TRUE;
		$img_config['thumb_marker'] = $thumb_marker;
		$img_config['new_image'] = $new_image;

		$img_config['width'] = $this->_face_img_info['image_width']>=$max_size ? $max_size : $this->_face_img_info['image_width'];
		$img_config['height'] = $this->_face_img_info['image_height']>=$max_size ? $max_size : $this->_face_img_info['image_height'];

		$this->CI->load->library( 'image_lib' , $img_config );
		if ( !$this->CI->image_lib->resize() ){

			$myface_error = array( 'content' => $this->CI->image_lib->display_errors() );	 	
			//设置 session 保存错误信息
			$this->CI->session->set_flashdata( 'myface_error' , $myface_error );
		}
		$this->do_crop();
		//删除原文件
		$this->CI->load->helper( 'file' );
		@unlink( $source_img );
	}/*}}}*/

	/**
	 * 根据用户提交的坐标对图片进行裁剪
	 *
	 * @accress public 
	 */
	public function do_crop(){
	/*{{{*/
		//获取x，y坐标
		$x1 = @$this->_user_input_info_for_update['x1'];
		$y1 = @$this->_user_input_info_for_update['y1'];
		$x2 = @$this->_user_input_info_for_update['x2'];
		$y2 = @$this->_user_input_info_for_update['y2'];

		$x1 = !empty( $x1 ) ? $x1 : '0' ;
		$y1 = !empty( $y1 ) ? $y1 : '0' ;
		$x2 = !empty( $x2 ) ? $x2 : '64' ;
		$y2 = !empty( $y2 ) ? $y2 : '64' ;
		//生成图片成功
		//保存xy坐标信息到 user 表中
		$this->CI->load->model( 'User' , '' , TRUE );
		$this->CI->User->load( $this->_user_id );
		$this->CI->User->set( 'x1' , $x1 );
		$this->CI->User->set( 'y1' , $y1 );
		$this->CI->User->set( 'x2' , $x2 );
		$this->CI->User->set( 'y2' , $y2 );
		$this->CI->User->save();

		$this->CI->load->library( 'image_moo' );
		$this->CI->image_moo->load( getcwd()."/picture/user_head_img/$this->_sys_domain".'_face.jpg' );
		$this->CI->image_moo->crop( $x1 , $y1 , $x2 , $y2 );
		$this->CI->image_moo->save( getcwd()."/picture/user_head_img/$this->_sys_domain".'_face_crop.jpg' , TRUE );
		$this->CI->image_moo->load( getcwd()."/picture/user_head_img/$this->_sys_domain".'_face_crop.jpg' );
		$this->CI->image_moo->resize( 48 , 48 );
		$this->CI->image_moo->save( getcwd()."/picture/user_head_img/$this->_sys_domain".'_face_small.jpg' , TRUE );
		$this->CI->image_moo->resize( 32 , 32 );
		$this->CI->image_moo->save( getcwd()."/picture/user_head_img/$this->_sys_domain".'_face_very_small.jpg' , TRUE );

		if( $this->CI->image_moo->errors ){
		
			print $this->CI->image_moo->display_errors();
		}
	}/*}}}*/
}/*}}}*/

