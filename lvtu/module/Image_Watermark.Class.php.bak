<?php
class Image_Watermark {
	private $message; // 类处理当中出现的问题的汇总
	/**
	 * 图片资源句柄
	 *
	 * @var resources
	 */
	private $im;
	/**
	 * 图片宽度
	 *
	 * @var float
	 */
	private $x;
	/**
	 * 图片高度
	 *
	 * @var float
	 */
	private $y;
	private $image_type;//图片的类型
	private $water_im; // 水印图片句柄
	private $water_x; // 水印图片宽度
	private $water_y; // 水印图片高度
	private $save_path;//保存路径
	/**
	 * 定义输出的类型
	 *
	 * @var array
	 */
	private $all_type = array(
			"jpg" => array(
					"output" => "imagejpeg" 
			),
			"gif" => array(
					"output" => "imagegif" 
			),
			"png" => array(
					"output" => "imagepng" 
			),
			"wbmp" => array(
					"output" => "image2wbmp" 
			),
			"jpeg" => array(
					"output" => "imagejpeg" 
			) 
	);
	public function __construct() {
	}
	/**
	 * 生成图片句柄资源
	 *
	 * @param string $src
	 *        	//图片的路径
	 * @param 名称 $name
	 *        	//im==base,water==水印
	 */
	public function createImage($src, $name = 'im') {
		if (file_exists($src)) {
			$data = file_get_contents($src);
			$im = imagecreatefromstring($data);
			switch ($name) {
				case 'im' :
					$this->im = $im;
					$this->x = $this->getImgWidth($im);
					$this->y = $this->getImagHeight($im);
					$this->image_type=image_type_to_extension($im); 
					break;
				case 'water' :
					$this->water_im = $im;
					$this->water_x = $this->getImgWidth($im);
					$this->water_y = $this->getImagHeight($im);
					break;
				default :
					$this->addMessage('创建图片句柄时，没有提供正确的句柄名称');
			}
		} else {
			$this->addMessage($src . ' 路径的图片不存在');
		}
	}
	/**
	 * 魔术函数set
	 * 
	 * @param string $name
	 *        	名
	 * @param string $value
	 *        	值
	 * @return Image
	 */
	private function __set($name, $value) {
		if ($name == 'water_src' || $name == 'base_src' || $name = 'save_src') {
			$this->$name = $value;
			return $this;
		}
	}
	/**
	 * 获取图片宽度
	 *
	 * @param sorce $src        	
	 * @return int
	 */
	function getImgWidth($src) {
		return imagesx($src);
	}
	/**
	 * 获取图片的高度
	 * 
	 * @param boject $src
	 *        	图片的资源句柄
	 * @return number
	 */
	function getImagHeight($src) {
		return imagesy($src);
	}
	/**
	 * 加水印
	 * @param int $type
	 */
	public function addWater($type=4) {
		$this->createImage($this->base_src);
		if($this->water_im){
			$xy=$this->countWaterXY($type);
			imagecopymerge($this->im, $this->water_im, $xy['x'], $xy['y'], 0, 0, $this->water_x, $this->water_y, 30);
		}else{
			$this->addMessage('水印图片未创建，请先创建水印图片');
		}
	}
	/**
	 * 压缩图片
	 * @param float $x 压缩成的图片的宽度
	 * @param float $Y 压缩成的图片的高度
	 */
	public function zipImage($x,$y=null) {
		$num=func_num_args();
		switch($num){
			case 1 :
				
				break;
			case 2 :
				break;
			
		}
	}
	/**
	 * 保存图片
	 */
	public function save() {
		$this->all_type[$this->image_type]['output']($this->im,$this->save_path);
	}
	/**
	 * 设置图片保存的路径
	 * @param string $path 路径
	 */
	public function setSavePath($path){
		return $this->getDir($path);
	}
	/**
	 * 销毁图片
	 */
	public function destory($im) {
		imagedestroy($im);
	}
	/**
	 * 计算水印图片的位置
	 * 
	 * @param int $type
	 *        	类型 1左上，2右上，3左下，4右下，5上中，6右中，7下中，8左中
	 */
	public function countWaterXY($type) {
		switch ($type) {
			case 1 :
				$x = 0;
				$y = 0;
				break; // 左上
			case 2 :
				$y = 0;
				$x = $this->x - $this->water_x;
				break; // 右上
			case 3 :
				$x=0;
				$y=$this->y-$this->water_y;
				break; // 左下
			case 4 :
				$x=$this->x-$this->water_x;
				$y=$this->y-$this->water_y;
				break; // 右下
			case 5 :
				$x=($this->x-$this->water_x)/2;
				$y=0;
				break; // 上中
			case 6 :
				$x=$this->x-$this->water_x;
				$y=($this->y-$this->water_y)/2;
				break; // 右中
			case 7 :
				$x=($this->x-$this->water_x)/2;
				$y=$this->y-$this->water_y;
				break; // 下中
			case 8 :
				$x=0;
				$y=($this->y-$this->water_y)/2;
				break; // 左中
			case 9 :
				$x=($this->x-$this->water_x)/2;
				$y=$y=($this->y-$this->water_y)/2;
				break;
			default: $this->addMessage('错误的水印图片位置参数，请从新调整');
		}
		return array(
				'x' => $x,
				'y' => $y 
		);
	}
	/**
	 * 获取图片保存路径，不存在时创建
	 *
	 * @param string $path
	 *        	路径
	 */
	function getDir($path) {
		if (is_dir($path)) {
			if ($this->createDir($path)) {
				return $path;
			} else {
				$this->addMessage('保存图片的路径不存在，且创建不成功，请确认目录有写入权限');
			}
		} else {
			return $path;
		}
	}
	/**
	 * 创建路径
	 *
	 * @param string $path
	 *        	路径
	 */
	function createDir($path) {
		$mark = true;
		$path_arr = explode('/', $path);
		$root_path = array_shift($path_arr);
		if (($root_path != '.' || $root_path != '..') && ! is_file($path)) {
			$mark = @mkdir($root_path);
		}
		$dirlist = '';
		foreach ( $path_arr as $value ) {
			if ($value != '.' && $value != '..') {
				$dirlist .= "/" . $value;
				$dirpath = $root_path . $dirlist;
				if (! file_exists($dirpath)) {
					$mark = @mkdir($dirpath);
					if (! $mark)
						break;
					$mark = @chmod($dirpath, 0777);
					if (! $mark)
						break;
				}
			}
		}
		return $mark;
	}
	public function addMessage($msg) {
		$this->message .= $msg . '<br />';
	}
	public function getMessage() {
		return $this->message;
	}
}
?>