<?php
/**
 * 文件下载类，此类目前不用初始化，静态调用即可
 * @author Howard
 * 此类暂时不用，未经过验证
 */
class download{
	/**
	 * 文件下载函数
	 * @param string $realpath 源文件路径，如/some/absolute/path.exe
	 * @param string $output_name 下载保存的文件名，如path.exe
	 * @author Howard
	 */
	public static function ouput($realpath, $output_name=''){
		if(!file_exists($realpath)){
			echo 'No find '.$realpath;
		}
		if($output_name==''){
			$output_name = $realpath;	//输出的文件名
		}
		//$output_name = basename($output_name);
		if(strpos($output_name,'/')!==false){
			$output_name = preg_replace('/.*\//','',$output_name);
		}
		if(strpos($output_name,'\\')!==false){
			$output_name = preg_replace('/.*'.preg_quote('\\').'/','',$output_name);
		}

		$mtime = ($mtime = filemtime($realpath)) ? $mtime : gmtime();
		$size = intval(sprintf("%u", filesize($realpath)));

		if (intval($size + 1) > self::return_bytes(ini_get('memory_limit')) && intval($size * 1.5) <= 1073741824) { //Not higher than 1GB
			ini_set('memory_limit', intval($size * 1.5));
		}

		@apache_setenv('no-gzip', 1);
		@ini_set('zlib.output_compression', 0);
		$fileext  = substr(strrchr($realpath,'.'),1);
		header('Content-Type: '.$fileext);
		header("Content-Type: application/force-download");
		header('Content-Type: application/octet-stream');
		header("Content-Type: application/download");
		header("Content-Transfer-Encoding:binary");
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');
		header("Content-Disposition: attachment; filename=\"" . $output_name . '"; modification-date="' . date('r', $mtime) . '";');
		header("Content-Length: " . $size);

		set_time_limit(300);

		$chunksize = 1 * (1024 * 1024); // how many bytes per chunk
		if ($size > $chunksize) {
			$handle = fopen($realpath, 'rb');
			$buffer = '';
			while (!feof($handle)) {
				$buffer = fread($handle, $chunksize);
				echo $buffer;
				ob_flush();
				flush();
			}
			fclose($handle);
		} else {
			readfile($realpath);
		}
		exit;
	}

	/**
	 * 返回bytes格式的大小数值
	 *
	 * @param string $val
	 * @return int
	 */
	private static function return_bytes($val) {
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}
		return $val;
	}
}
?>