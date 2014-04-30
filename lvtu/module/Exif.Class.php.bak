<?php
/**
 * 获取图片的EXIF信息
 * @author lwkai 2013-3-1 下午4:58:04
 *
 */
class Exif {
	
	/**
	 * 图片文件的类型数组
	 * @var array
	 * @author lwkai 2013-3-1 下午1:43:17
	 */
	private $_img_type = array(
			"", 
			"GIF", 
			"JPG", 
			"PNG", 
			"SWF", 
			"PSD", 
			"BMP", 
			"TIFF(intel byte order)", 
			"TIFF(motorola byte order)", 
			"JPC", 
			"JP2", 
			"JPX", 
			"JB2", 
			"SWC", 
			"IFF", 
			"WBMP", 
			"XBM"
	);
	
	/**
	 * 图片信息的照片方向数组
	 * @var array
	 * @author lwkai 2013-3-1 下午1:56:48
	 */
	private $_orientation = array(
			"", 
			"top left side", 
			"top right side", 
			"bottom right side", 
			"bottom left side", 
			"left side top", 
			"right side top", 
			"right side bottom", 
			"left side bottom"
	);
	
	/**
	 * 分辨率的单位
	 * @var array
	 * @author lwkai 2013-3-1 下午1:58:15
	 */
	private $_resolution_unit = array("", "", "英寸", "厘米");
	
	/**
	 * YCbCr位置控制
	 * @var array
	 * @author lwkai 2013-3-1 下午2:00:29
	 */
	private $_ycbcr_positioning = array("", "the center of pixel array", "the datum point");
	
	/**
	 * 暴光程序
	 * @var array
	 * @author lwkai 2013-3-1 下午2:01:51
	 */
	private $_exposure_program = array(
			"未定义", 
			"手动", 
			"标准程序", 
			"光圈先决", 
			"快门先决", 
			"景深先决", 
			"运动模式", 
			"肖像模式", 
			"风景模式"
	);
	
	/**
	 * 测光模式
	 * @var array
	 * @author lwkai 2013-3-1 下午2:04:21
	 */
	private $_metering_mode = array(
		"0"   => "未知",
		"1"   => "平均",
		"2"   => "中央重点平均测光",
		"3"   => "点测",
		"4"   => "分区",
		"5"   => "评估",
		"6"   => "局部",
		"255" => "其他"
	);
	
	/**
	 * 光源
	 * @var array
	 * @author lwkai 2013-3-1 下午2:20:02
	 */
	private $_lightsource = array(
		"0"   => "未知",
		"1"   => "日光",
		"2"   => "荧光灯",
		"3"   => "钨丝灯",
		"10"  => "闪光灯",
		"17"  => "标准灯光A",
		"18"  => "标准灯光B",
		"19"  => "标准灯光C",
		"20"  => "D55",
		"21"  => "D65",
		"22"  => "D75",
		"255" => "其他"
	);

	/**
	 * 闪光灯
	 * @var array
	 * @author lwkai 2013-3-1 下午2:26:07
	 */
	private $_flash = array(
			"0" => "flash did not fire",
			"1" => "flash fired",
			"5" => "flash fired but strobe return light not detected",
			"7" => "flash fired and strobe return light detected",
	);
	
	/**
	 * 读取出来的信息
	 * @var array
	 * @author lwkai 2013-3-1 下午2:34:03
	 */
	private $_info = array();
	
	/**
	 * info中健对应的中文名称
	 * @var array
	 * @author lwkai 2013-3-1 下午4:14:40
	 */
	private $_cn_name = array(
		'FileName' => '文件名',	
		'FileType' => '文件类型',
		'MimeType' => '文件格式',
		'FileSize' => '文件大小',
		'FileDateTime' => '时间戳',
		'ImageDescription' => '图片说明',
		'Make' => '制造商',
		'Model' => '型号',
		'Orientation' => '方向',
		'XResolution' => '水平分辨率',
		'YResolution' => '垂直分辨率',
		'Software' => '创建软件',
		'DateTime' => '修改时间',
		'Atrist' => '作者',
		'YCbCrPositioning' => 'YCbCr位置控制',
		'Copyright' => '版权',
		'Copyright.Photographer' => '摄影版权',
		'Copyright.Editor' => '编辑版权',
		'ExifVersion' => 'Exif版本',
		'FlashPixVersion' => 'FlashPix版本',
		'DateTimeOriginal' => '拍摄时间',
		'DateTimeDigitized' => '数字化时间',
		'Height' => '拍摄分辨率高',
		'Width' => '拍摄分辨率宽',
		'ApertureValue' => '光圈',
		'ShutterSpeedValue' => '快门速度',
		'ApertureFNumber' => '快门光圈',
		'MaxApertureValue' => '最大光圈值',
		'ExposureTime' => '曝光时间',
		'F-Number' => 'F-Number',
		'MeteringMode' => '测光模式',
		'LightSource' => '光源',
		'Flash' => '闪光灯',
		'ExposureMode' => '曝光模式',
		'WhiteBalance' => '白平衡',
		'ExposureProgram' => '曝光程序',
		'ExposureBiasValue' => '曝光补偿',
		'ISOSpeedRatings' => 'ISO感光度',
		'ComponentsConfiguration' => '分量配置',
		'CompressedBitsPerPixel' => '图像压缩率',
		'FocusDistance' => '对焦距离',
		'FocalLength' => '焦距',
		'FocalLengthIn35mmFilm' => '等价35mm焦距',
		'UserCommentEncoding' => '用户注释编码',
		'UserComment' => '用户注释',
		'ColorSpace' => '色彩空间',
		'ExifImageLength' => 'Exif图像高度',
		'ExifImageWidth' => 'Exif图像宽度',
		'FileSource' => '文件来源',
		'SceneType' => '场景类型',
		'Thumbnail.FileType' => '缩略图文件格式',
		'Thumbnail.MimeType' => '缩略图Mime格式'
	);

	/**
	 * 分析图片的EXIF信息
	 * @param string $img 图片的绝对物理地址
	 * @return void 
	 * @author lwkai 2013-3-1 下午4:53:21
	 */
	public function __construct($img) {
		if (!file_exists($img)) {
			return;
		} 
		$exif = exif_read_data($img,"IFD0");
		if ($exif === false) {
			return;
		} else {
			$exif = exif_read_data ($img,0,true);
			/* 文件信息 */
			$this->_info['file'] = array(
				'FileName' => (isset($exif['FILE']['FileName']) ? $exif['FILE']['FileName'] : ''),
				'FileType' => (isset($exif['FILE']['FileType']) ? $this->_img_type[$exif['FILE']['FileType']] : ''),
				'MimeType' => (isset($exif['FILE']['MimeType']) ? $exif['FILE']['MimeType'] : ''),
				'FileSize' => (isset($exif['FILE']['FileSize']) ? $exif['FILE']['FileSize'] : ''),
				'FileDateTime' => (isset($exif['FILE']['FileDateTime']) ? date("Y-m-d H:i:s",$exif['FILE']['FileDateTime']) : '')
			);
			/* 图片信息 */
			$this->_info['picture'] = array(
				'ImageDescription' => (isset($exif['IFD0']['ImageDescription']) ? $exif['IFD0']['ImageDescription'] : ''),
				'Make' => (isset($exif['IFD0']['Make']) ? $exif['IFD0']['Make'] : ''),
				'Model' => (isset($exif['IFD0']['Model']) ? $exif['IFD0']['Model'] : ''),
				'Orientation' => (isset($exif['IFD0']['Orientation']) ? $this->_orientation[$exif['IFD0']['Orientation']] : ''),
				'XResolution' => (isset($exif['IFD0']['XResolution']) ?  $exif['IFD0']['XResolution'] : '') . (isset($exif['IFD0']['ResolutionUnit']) ? $this->_resolution_unit[$exif['IFD0']['ResolutionUnit']] : ''),
				'YResolution' => (isset($exif['IFD0']['YResolution']) ? $exif['IFD0']['YResolution'] : '')  . (isset($exif['IFD0']['ResolutionUnit']) ? $this->_resolution_unit[$exif['IFD0']['ResolutionUnit']] : ''),
				'Software' => (isset($exif['IFD0']['Software']) ? $exif['IFD0']['Software'] : ''),
				'DateTime' => (isset($exif['IFD0']['DateTime']) ? $exif['IFD0']['DateTime'] : ''),
				'Atrist' => (isset($exif['IFD0']['Artist']) ? $exif['IFD0']['Artist'] : ''),
				'YCbCrPositioning' => (isset($exif['IFD0']['YCbCrPositioning']) ? $this->_ycbcr_positioning[$exif['IFD0']['YCbCrPositioning']] : ''),
				'Copyright' => (isset($exif['IFD0']['Copyright']) ? $exif['IFD0']['Copyright'] : ''),
				'Copyright.Photographer' => (isset($exif['COMPUTED']['Copyright.Photographer']) ? $exif['COMPUTED']['Copyright.Photographer'] : ''),
				'Copyright.Editor' => (isset($exif['COMPUTED']['Copyright.Editor']) ? $exif['COMPUTED']['Copyright.Editor'] : '')
			);
			/* 拍摄信息 */
			$this->_info['shooting'] = array(
				'ExifVersion' => (isset($exif['EXIF']['ExifVersion']) ? $exif['EXIF']['ExifVersion'] : ''),
				'FlashPixVersion' => (isset($exif['EXIF']['FlashPixVersion']) ? "Ver. " . number_format($exif['EXIF']['FlashPixVersion']/100, 2) : ''),
				'DateTimeOriginal' => (isset($exif['EXIF']['DateTimeOriginal']) ? $exif['EXIF']['DateTimeOriginal'] : ''),
				'DateTimeDigitized' => (isset($exif['EXIF']['DateTimeDigitized']) ? $exif['EXIF']['DateTimeDigitized'] : ''),
				'Height' => (isset($exif['COMPUTED']['Height']) ? $exif['COMPUTED']['Height'] : ''),
				'Width' => (isset($exif['COMPUTED']['Width']) ? $exif['COMPUTED']['Width'] : ''),
				/*
				 The actual aperture value of lens when the image was taken.
				Unit is APEX.
				To convert this value to ordinary F-number(F-stop),
				calculate this value's power of root 2 (=1.4142).
				For example, if the ApertureValue is '5', F-number is pow(1.41425,5) = F5.6.
				*/
				'ApertureValue' => (isset($exif['EXIF']['ApertureValue']) ? $exif['EXIF']['ApertureValue'] : ''),
				'ShutterSpeedValue' => (isset($exif['EXIF']['ShutterSpeedValue']) ? $exif['EXIF']['ShutterSpeedValue'] : ''),
				'ApertureFNumber' => (isset($exif['COMPUTED']['ApertureFNumber']) ? $exif['COMPUTED']['ApertureFNumber'] : ''),
				'MaxApertureValue' => (isset($exif['EXIF']['MaxApertureValue']) ? "F" . $exif['EXIF']['MaxApertureValue'] : ''),
				'ExposureTime' => (isset($exif['EXIF']['ExposureTime']) ? $exif['EXIF']['ExposureTime'] : ''),
				'F-Number' => (isset($exif['EXIF']['FNumber']) ? $exif['EXIF']['FNumber'] : ''),
				'MeteringMode' => (isset($exif['EXIF']['MeteringMode']) ? $this->getImageInfoVal($exif['EXIF']['MeteringMode'],$this->_metering_mode) : ''),
				'LightSource' => (isset($exif['EXIF']['LightSource']) ? $this->getImageInfoVal($exif['EXIF']['LightSource'], $this->_lightsource) : ''),
				'Flash' => (isset($exif['EXIF']['Flash']) ? $this->getImageInfoVal($exif['EXIF']['Flash'], $this->_flash) : ''),
				'ExposureMode' => (isset($exif['EXIF']['ExposureMode']) ? ($exif['EXIF']['ExposureMode'] == 1 ? "手动" : "自动") : ''),
				'WhiteBalance' => (isset($exif['EXIF']['WhiteBalance']) ? ($exif['EXIF']['WhiteBalance'] == 1 ? "手动" : "自动") : ''),
				'ExposureProgram' => (isset($exif['EXIF']['ExposureProgram']) ? $this->_exposure_program[$exif['EXIF']['ExposureProgram']] : ''),
					/*
					 Brightness of taken subject, unit is APEX. To calculate Exposure(Ev) from BrigtnessValue(Bv), you must add SensitivityValue(Sv).
					Ev=Bv+Sv   Sv=log((ISOSpeedRating/3.125),2)
					ISO100:Sv=5, ISO200:Sv=6, ISO400:Sv=7, ISO125:Sv=5.32.
					*/
				'ExposureBiasValue' => (isset($exif['EXIF']['ExposureBiasValue']) ? $exif['EXIF']['ExposureBiasValue'] . "EV" : ''),
				'ISOSpeedRatings' => (isset($exif['EXIF']['ISOSpeedRatings']) ? $exif['EXIF']['ISOSpeedRatings'] : ''),
				'ComponentsConfiguration' => (isset($exif['EXIF']['ComponentsConfiguration']) ? (bin2hex($exif['EXIF']['ComponentsConfiguration']) == "01020300" ? "YCbCr" : "RGB") : ''),//'0x04,0x05,0x06,0x00'="RGB" '0x01,0x02,0x03,0x00'="YCbCr"
				'CompressedBitsPerPixel' => (isset($exif['EXIF']['CompressedBitsPerPixel']) ? $exif['EXIF']['CompressedBitsPerPixel'] . "Bits/Pixel" : ''),
				'FocusDistance' => (isset($exif['COMPUTED']['FocusDistance']) ? $exif['COMPUTED']['FocusDistance'] . "m" : ''),
				'FocalLength' => (isset($exif['EXIF']['FocalLength']) ? $exif['EXIF']['FocalLength'] . "mm" : ''),
				'FocalLengthIn35mmFilm' => (isset($exif['EXIF']['FocalLengthIn35mmFilm']) ? $exif['EXIF']['FocalLengthIn35mmFilm'] . "mm" : ''),
					/*
					 Stores user comment. This tag allows to use two-byte character code or unicode. First 8 bytes describe the character code. 'JIS' is a Japanese character code (known as Kanji).
					'0x41,0x53,0x43,0x49,0x49,0x00,0x00,0x00':ASCII
					'0x4a,0x49,0x53,0x00,0x00,0x00,0x00,0x00':JIS
					'0x55,0x4e,0x49,0x43,0x4f,0x44,0x45,0x00':Unicode
					'0x00,0x00,0x00,0x00,0x00,0x00,0x00,0x00':Undefined
					*/
				'UserCommentEncoding' => (isset($exif['COMPUTED']['UserCommentEncoding']) ? $exif['COMPUTED']['UserCommentEncoding'] : ''),
				'UserComment' => (isset($exif['COMPUTED']['UserComment']) ? $exif['COMPUTED']['UserComment'] : ''),
				'ColorSpace' => (isset($exif['EXIF']['ColorSpace']) ? ($exif['EXIF']['ColorSpace'] == 1 ? "sRGB" : "Uncalibrated") : ''),
				'ExifImageLength' => (isset($exif['EXIF']['ExifImageLength']) ? $exif['EXIF']['ExifImageLength'] : ''),
				'ExifImageWidth' => (isset($exif['EXIF']['ExifImageWidth']) ? $exif['EXIF']['ExifImageWidth'] : ''),
				'FileSource' => (isset($exif['EXIF']['FileSource']) ? (bin2hex($exif['EXIF']['FileSource']) == 0x03 ? "digital still camera" : "unknown") : ''),
				'SceneType' => (isset($exif['EXIF']['SceneType']) ? (bin2hex($exif['EXIF']['SceneType']) == 0x01 ? "A directly photographed image" : "unknown") : ''),
				'Thumbnail.FileType' => (isset($exif['COMPUTED']['Thumbnail.FileType']) ? $exif['COMPUTED']['Thumbnail.FileType'] : ''),
				'Thumbnail.MimeType' => (isset($exif['COMPUTED']['Thumbnail.MimeType']) ? $exif['COMPUTED']['Thumbnail.MimeType'] : '')
			);
		}
	}
	
	/**
	 * 从枚举数组中找出对应的值
	 * @param string $image_info 要查找的键名
	 * @param array $val_arr 枚举的数组
	 * @return string
	 * @author lwkai 2013-3-1 下午2:06:17
	 */
	private function getImageInfoVal($image_info,$val_arr) {
		$InfoVal    =    "未知";
		if (!is_array($val_arr) && !is_object($val_arr)) {
			return $InfoVal;
		}
		foreach ($val_arr as $name=>$val) {
			if ($name == $image_info) {
				$InfoVal = $val;
				break;
			}
		}
		return $InfoVal;
	}

	/**
	 * 取得图片的EXIF信息
	 * @return array
	 * @author lwkai 2013-3-1 下午4:56:11
	 */
	public function getExif() {
		return $this->_info;
	}
	
	/**
	 * 根据getExif方法获得的数组中的KEY来取得对应的中文名称
	 * @param string $key
	 * @return string
	 * @author lwkai 2013-3-1 下午4:57:11
	 */
	public function getCnName($key) {
		return $this->_cn_name[$key];
	}
	
	/**
	 * 根据对应的字符串，取得对应的KEY，如果未找到，则返回FLASE，
	 * 注意判断的时候用===，返回索引为0的时候也是FLASH，所有要用绝对等于
	 * @param string $orientation 要查找的字符串，注意这里区分大小写
	 * @return 对应的KEY或者FLASH
	 * @author lwkai 2013-3-13 上午10:53:53
	 */
	public function getOrientationOfNumber($orientation) {
		if ($orientation) {
			return array_search($orientation, $this->_orientation);
		}
		return false;
	}
}