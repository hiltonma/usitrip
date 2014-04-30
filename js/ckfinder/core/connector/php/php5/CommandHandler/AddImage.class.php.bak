<?php
class AddImage {
	private $water_dir; // Ë®Ó¡Í¼Æ¬µÄÂ·¾¶
	private $base_dir; // Òª¼ÓË®Ó°
	private $water_info;
	private $base_info;
	private $x;
	private $y;
	private $image_obj;
	private $water_obj;
	private $dirArray;
	public function __construct($water_dir='black.png'){
// 		
		$this->water_dir=$water_dir;
		//echo $water_dir;
		$this->water_info=getimagesize($this->water_dir);
		
		if(!$this->water_info)
			exit;
		$this->water_obj=$this->image_create_from_ext($this->water_dir);
	}
	public function imageLoad($img_src) {
	}
	public function countImageXY() {
	}
	public function findDirFile($src_name = 'input') {
		//$dirArray = array();
		if (false !== ($handle = opendir($src_name))) {

			while ( false !== ($file = readdir($handle)) ) {
				
				
				if ($file != '.' && $file != '..') {
					if(is_dir($src_name.'/'.$file)){
						
						$this->findDirFile($src_name.'/'.$file);
					}else{
						//echo $file.'<br />';
					$this->dirArray[] = $src_name.'/'.$file;
					}
				}
			}
		}
		closedir($handle);
		//return $dirArray;
	}
	private function readBaseImage($image_dir) {
		$this->base_dir=$image_dir;
		$this->base_info=getimagesize($this->base_dir);
		$this->image_obj=$this->image_create_from_ext($this->base_dir);
// 		echo $this->water_obj;
	}
	public function addWater($dir) {
// 		$this->findDirFile();
// 		$file_arr=$this->dirArray;
		$file_arr=array($dir);
// 		print_r($file_arr);
// 		die();
		
// 		print_r($place);
		foreach($file_arr as $value){
			$savefile=$value;
			$this->readBaseImage($value);
			$place=$this->createPlace(5);
			imagecopy($this->image_obj, $this->water_obj, $place[0], $place[1], 0, 0, $this->water_info[0], $this->water_info[1]);
			switch ($this->base_info[2]) {
				case 1: imagegif($this->image_obj, $savefile); break;
				case 2: imagejpeg($this->image_obj, $savefile); break;
				case 3: imagepng($this->image_obj, $savefile); break;
				default: return -5;  //±£´æÊ§°Ü
			}
			imagedestroy($this->image_obj);	
		}
	}
	private function saveImage(){
		
	}
	private function createPlace($positon) {
		switch ($positon) {
			// 1¶¥²¿¾Ó×ó
			case 1 :
				$x = $y = 0;
				break;
			// 2¶¥²¿¾ÓÓÒ
			case 2 :
				$x = -($this->base_info[0]-$this->water_info[0]);
				//print_r($this->water_info);
				//echo $this->base_info[0],'----',$this->water_info[0];
				$y = 0;
				break;
			// 3¾ÓÖÐ
			case 3 :
				$x = ($this->base_info[0] - $this->water_info[0]) / 2;
				$y = ($this->base_info[1] - $this->water_info[1]) / 2;
				break;
			// 4µ×²¿¾Ó×ó
			case 4 :
				$x = 0;
				$y = $this->base_info[1] - $this->water_info[1];
				break;
			// 5µ×²¿¾ÓÓÒ
			case 5 :
				$x = -($this->base_info[0]-$this->water_info[0]);
				$y = -($this->base_info[1] - $this->water_info[1]);
				break;
			default :
				$x = $y = 0;
		}
		return array($x*-1,$y*-1);
	}
	function image_create_from_ext($imgfile)
	{
		$info = getimagesize($imgfile);
		$im = null;
		switch ($info[2]) {
			case 1: $im=imagecreatefromgif($imgfile); break;
			case 2: $im=imagecreatefromjpeg($imgfile); break;
			case 3: $im=imagecreatefrompng($imgfile); break;
		}
		return $im;
	}
	function __destruct(){
		//imagedestroy($this->water_obj);
	}
}