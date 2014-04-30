<?php
/**
 * 图片缩放，裁切
 * @author lwkai 2013-3-12 上午9:42:07
 *
 */
class Picture_Zoom {
	
	/**
	 * 图片类型
	 * @var string
	 * @author lwkai 2013-3-13 上午11:30:49
	 */
	private $_img_ext = '';
	
	/**
	 * 要处理的源图地址
	 * @var string
	 * @author lwkai 2013-3-13 下午5:36:52
	 */
	private $_img = '';
	
	/**
	 * 保存的图片地址
	 * @var string
	 * @author lwkai 2013-3-13 下午5:38:50
	 */
	private $_new_img = '';
	
	/**
	 * 生成的图片最大宽度
	 * @var int
	 * @author lwkai 2013-3-13 下午5:43:51
	 */
	private $_max_width = 0;
	
	/**
	 * 生成的图片最大高度
	 * @var int
	 * @author lwkai 2013-3-13 下午5:44:07
	 */
	private $_max_height = 0;
	
	/**
	 * 重采样模式缩放图片,默认是开启[true]
	 * @var boolean
	 * @author lwkai 2013-3-13 下午6:10:46
	 */
	private $_resampling = true;
	
	/**
	 * 生成图片的质量，默认是100最好的
	 * @var int
	 * @author lwkai 2013-3-13 下午6:13:38
	 */
	private $_quality = 100;
	
	
	public function __construct($arr) {
		if (isset($arr['img']) && $arr['img']) {
			if (file_exists($arr['img'])) {
				$this->_img = $arr['img'];
			} else {
				throw new Exception('image not find!');
			}
		}
		if (isset($arr['saveimg']) && $arr['saveimg']) {
			$this->_new_img = $arr['saveimg'];
		}
		$this->_max_height = ((isset($arr['maxheight']) && (int)$arr['maxheight'] > 0) ? (int)$arr['maxheight'] : 0);
		$this->_max_width = ((isset($arr['maxwidth']) && (int)$arr['maxwidth'] > 0) ? (int)$arr['maxwidth'] : 0);
		$this->_resampling = (isset($arr['resampling']) ? !!$arr['resampling'] : false);
		$this->_quality = ((isset($arr['quality']) && (int)$arr['quality'] > 0 && (int)$arr['quality'] <= 100) ? (int)$arr['quality'] : 100);
	}
	
	/**
	 * 设置需要处理的源图片地址
	 * @param string $img 要处理的图片地址
	 * @author lwkai 2013-3-13 下午5:54:05
	 */
	public function setImg($img) {
		if (file_exists($img) && !is_dir($img)) {
			$this->_img = $img;
		}
	}
	
	/**
	 * 处理后的图片名称[绝对物理地址]
	 * @param string $img
	 * @author lwkai 2013-3-13 下午5:56:08
	 */
	public function setSaveImgPath($img) {
		$this->_new_img = $img;
	}
	
	/**
	 * 设置处理后的图片最大宽度
	 * @param int $width 图片宽度
	 * @author lwkai 2013-3-13 下午5:58:02
	 */
	public function setMaxWidth($width) {
		if ((int)$width > 0) {
			$this->_max_width = (int)$width;
		}
	}
	
	/**
	 * 设置处理后的图片最大高度
	 * @param int $height 图片高度
	 * @author lwkai 2013-3-13 下午5:59:07
	 */
	public function setMaxHeight($height) {
		if ((int)$height > 0) {
			$this->_max_height = (int)$height;
		}
	}
	
	/**
	 * 设置重采样模式缩放图片
	 * @param boolean $bool
	 * @author lwkai 2013-3-13 下午6:12:38
	 */
	public function setResampling($bool) {
		$this->_resampling = !!$bool;
	}
	
	/**
	 * 设置生成图片的质量
	 * @param int $int 1-100的范围
	 * @author lwkai 2013-3-13 下午6:14:57
	 */
	public function setQuality($int) {
		if ((int)$int > 0 && (int)$int <= 100) {
			$this->_quality = (int)$int;
		}
	}
	
	/**
	 * 剪切图片
	 * @param bool $zoom 剪切的时候是否需要进行缩放
	 * @throws Exception 如果未指定源图与保存地址，则抛出异常
	 * @author lwkai 2013-3-14 下午2:38:55
	 */
	public function cut($zoom = false) {
		if (!$this->_img || !$this->_new_img) {
			throw new Exception('原图地址未设置，或者新图地址未设置！');
		}
		
		if ($zoom == true) {
			$this->zoom('min');
			// 取得源图片宽度
			list($picWidth,$picHeight) = getimagesize($this->_new_img);
			$source = $this->createSource($this->_new_img);
		} else {
			list($picWidth,$picHeight) = getimagesize($this->_img);
			$source = $this->createSource($this->_img);
		}
		$w = ($picWidth > $this->_max_width ? true : false);
		$h = ($picHeight > $this->_max_height ? true : false);
		$copy_start_x = $copy_start_y = 0;
		if ($w == true) {
			$copy_start_x = round(($picWidth - $this->_max_width) / 2);
		}
		if ($h == true) {
			$copy_start_y = round(($picHeight - $this->_max_height) / 2);
		}
		$_w = ($picWidth < $this->_max_width ? $picWidth : $this->_max_width);
		$_h = ($picHeight < $this->_max_height ? $picHeight : $this->_max_height);
		if (function_exists('imagecreatetruecolor')) {
			$newim = imagecreatetruecolor($_w, $_h);
		} else {
			throw new Exception('是否未安装GD库支持！');
		}
		if (function_exists('imagecopyresampled') && $this->_resampling == true) {
			imagecopyresampled($newim, $source, 0, 0, $copy_start_x, $copy_start_y, $_w, $_h, $_w, $_h);
		} else {
			imagecopyresized($newim, $source, 0, 0, $copy_start_x, $copy_start_y, $_w, $_h, $_w, $_h);
		}
		imagedestroy($source);
		ImageJpeg($newim,$this->_new_img,$this->_quality);
		imagedestroy($newim);
	}
	
	/**
	 * 缩放图片
	 * @param string $way min|max
	 * 			'min'则是以小比例来进行缩放。这时候缩放出来的图片很可能会超过你给定的宽高
	 * 			'max'则是以大比例来进行缩放.这时候缩放出来的图片不会超过你给定的宽高					
	 * @throws Exception 如果缩放的时候，没有源图地址和保存地址，则抛出异常
	 * @author lwkai 2013-3-14 上午8:46:15
	 */
	public function zoom($way = 'max') {
		if (!$this->_img || !$this->_new_img) {
			throw new Exception('原图地址未设置，或者新图地址未设置！');
		}
		$wh = self::zoomImage($this->_max_width, $this->_max_height, $this->_img,$way);
		$picHeight = $wh['source_h'];
		$picWidth = $wh['source_w'];
		$_w = $wh['new_w'];
		$_h = $wh['new_h'];
		$wimage = $this->createSource($this->_img);
		// 检测是否支持高清缩放，并且设置了启用高清，并且里面的创建高清图像不支持GIF
		if (function_exists('imagecopyresampled') && $this->_resampling && $this->_img_ext != 'gif') {
			$newim = imagecreatetruecolor($_w,$_h);
			imagecopyresampled($newim, $wimage, 0, 0, 0, 0, $_w, $_h, $picWidth, $picHeight);	
		} else {
			if (function_exists('imagecreatetruecolor')) {
				$newim = imagecreatetruecolor($_w, $_h);
			} else {
				$newim = imagecreate($_w, $_h); // 用这种方式出来的图片颜色丢失很严重
			}
			imagecopyresized($newim, $wimage, 0, 0, 0, 0, $_w, $_h, $picWidth, $picHeight);
		}
		ImageJpeg($newim,$this->_new_img,$this->_quality);
		imagedestroy($wimage);
		imagedestroy($newim);
	}
	
	/**
	 * 按比例缩放图片，返回缩放后的大小。
	 * @param int $width 图片绽放后不能超过的宽度
	 * @param int $height 图片绽放后不能超过的高度
	 * @param string $img 需要按比例计算的图片[绝对路径]
	 * @param string $way min|max 默认是max
	 * 			'min'则是以小比例来进行缩放。这时候缩放出来的图片很可能会超过你给定的宽高
	 * 			'max'则是以大比例来进行缩放.这时候缩放出来的图片不会超过你给定的宽高
	 * @return array('new_w'=>计算后的宽度,'new_h'=>计算后的高度,'source_w' => 原图宽度, 'source_h' => 原图高度)
	 * @author lwkai 2013-4-19 下午4:24:40
	 */
	public static function zoomImage($width, $height, $img, $way = 'max') {
		$rtn = array('new_w' => 0, 'new_h' => 0);
		list($picWidth,$picHeight) = getimagesize($img);
		$rtn['source_w'] = $picWidth;
		$rtn['source_h'] = $picHeight;
		// 算比例
		$sw =  $picWidth / $width;
		$sh = $picHeight / $height;
		if ($way == 'min') {
			$scale = ($sw < $sh ? $sw : $sh);
		} else {
			$scale = ($sw > $sh ? $sw : $sh);
		}
		if ($scale > 1) {
			// 新图的宽
			$rtn['new_w'] = $picWidth / $scale;
			// 新图的高
			$rtn['new_h'] = $picHeight / $scale;
		} else {
			$rtn['new_w'] = $picWidth;
			$rtn['new_h'] = $picHeight;
		}
		return $rtn;
	}
	
	/**
	 * 旋转图片
	 * @param int $orientation 图片方向[1-8]  图片方向是根据 EXIF里的规定来的
	 * @author lwkai 2013-3-13 上午10:43:16
	 */
	public function rotate($orientation = 1) {
		if (!$this->_img || !$this->_new_img) {
			throw new Exception('源图地址为空或者保存地址为空！');
		}
		$degrees = 0;
		switch((int)$orientation) {
			case 3:
				$degrees = 180;
				break;
			case 6:
				$degrees = 270;
				break;
			case 8:
				$degrees = 90;
				break;
		}
		if ($degrees == 0) {
			return false;
		}
		
		$source = $this->createSource($this->_img);
		$rotate = imagerotate($source, $degrees, 0);
		$newImageName = $this->_new_img;
		// Output
		imagejpeg($rotate,$newImageName,$this->_quality);
		imagedestroy($source);
		imagedestroy($rotate);
		return true;
	}
	
	/**
	 * 创建图片资源
	 * @param string $img 图片地址
	 * @return NULL | resource
	 * @author lwkai 2013-3-13 下午3:13:55
	 */
	private function createSource($img) {
		// 取源图信息
		$wimage_data = GetImageSize($img);
		$wimage = null;
		switch($wimage_data[2])	{
			case 1:
				$wimage = ImageCreateFromGIF($img);
				$this->_img_ext = '.gif';
				break;
			case 2:
				$wimage = ImageCreateFromJPEG($img);
				$this->_img_ext = '.jpg';
				break;
			case 3:
				$wimage = ImageCreateFromPNG($img);
				$this->_img_ext = '.png';
				break;
		}
		return $wimage;
	}
}
?>