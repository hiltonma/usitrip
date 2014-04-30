<?php
/**
 * 文件类，
 * 读取文件内容，
 * 保存内容到文件，创建文件夹，
 * 删除文件和文件夹
 * @author lwkai 2012-4-15 <1275124829@163.com>
 * @version 1.0
 */
class File {
	/**
	 * 需要读取的文件
	 *
	 * @var string|array
	 */
	protected  $_files = null;
	
	/**
	 * 保存读取的文件内容，或者待写入的内容
	 * 如果读取的是数组，则该属性为数组
	 * 写入时，只能是字符串
	 *
	 * @var string|array
	 */
	protected $_string = '';
	
	/**
	 * 构造函数 初始化该类时 可传入需要读取的文件
	 *
	 * @param array|string $file
	 */
	public function __construct($file = array()) {
		$this->setFiles($file);
	}
	
	/**
	 * 设置需要读取的文件
	 *
	 * @param string|array $file
	 */
	public function setFiles($file = array()) {
		if (is_array($file)) {
			foreach ($file as $key => $val) {
				if (is_string($val)) {
					$this->_files[$key] = $val;
				}
			}
		} elseif (is_string($file)) {
			$this->_files = $file;
		}
	}
	
	/**
	 * 打开一个文件并读取内容,如果内容正确读取,则返回true 否则抛出异常。
	 * 
	 * @param string $keyName 需要打开的文件名,如果初始化时传入的是数组,则传入需要读取的数组键名,如果传入的是文件地址，则打开这个文件
	 * @param string $type 文件内容以数组还是字符串返回
	 * @return boolean 
	 */
	private function open($keyName, $type = 'array'){
		if (file_exists($keyName)) {
			$file = $keyName;
		} elseif (isset($this->_files[$keyName])) {
			$file = $this->_files[$keyName];
		} elseif (is_string($this->_files)) {
			$file = $this->_files;
		}
		if (!file_exists($file)) {
			My_Exception::mythrow('IOError', '[' . $file . ']文件不存在!');
		}
		if ($type == 'array') {
			$this->_string = file($file);
		} else {
			$this->_string = file_get_contents($file);
		}
		return true;
		
	}
	
	/**
	 * 创建目录，一次可创建多层
	 * @param string $path 要创建的地址[绝对路径]
	 * @author lwkai 2013-1-31 下午2:18:33
	 */
	public function createDir($path) {
		if (empty($path)) return;
		$dir = explode(DS,$path);
		$temp = $dir[0];
		for($i = 1,$len = count($dir); $i < $len; $i++) {
			if (is_dir($temp . DS . $dir[$i])) {
				$temp .= DS . $dir[$i];
			} else {
				try {
					mkdir($temp . DS . $dir[$i]);
					$temp .= DS . $dir[$i];
				} catch(Exception $e) {
					My_Exception::mythrow('IOError', $e->getMessage());
				}
			}
		}
	}
	
	/**
	 * 读取一个文件,返回指定的 array 或者 string
	 * @param string $file 文件或者初始化时传进来的数组KEY值
	 * @param array $type 返回的数据类型
	 */
	public function read($file,$type = 'string') {
		$this->open($file,$type);
		return $this->_string;
	}
	
	/**
	 * 设置需要写入的内容
	 * @param string $content 要写入的内容
	 * @return File[对象本身]
	 * @author lwkai 2013-1-31 下午3:19:12
	 */
	public function setContent($content) {
		$this->_string = $content;
		return $this;
	}
	
	/**
	 * 把指定的字符串写入文件
	 *
	 * @param string $fileName 写入的文件名[完整路径]
	 * @param string $mode 写入方式 a w 等等
	 * @param string $content 要写入的内容[可选]
	 * @return boolean 成功返回真 否则抛出异常
	 */
	public function write($fileName = '', $mode = 'a', $content = '') {
		$this->createDir(dirname($fileName));
		// 如果文件存在并且不可写 
		if (file_exists($fileName) && !is_writable($fileName)) {
			My_Exception::mythrow('IOError', '文件存在,但不可写!');
		}
		if (!$handle = fopen($fileName,$mode)) {
			My_Exception::mythrow('IOError', '不能打开文件[' . $fileName . ']');
		}
		if (fwrite($handle,$content ? $content : $this->_string) == false) {
			My_Exception::mythrow('IOError', '不能写入文件到[' . $fileName . ']');
		}
		fclose($handle);
		return true;
	}
	
	/**
	 * 删除文件
	 * 
	 * @author lwkai 2013-1-31 下午2:48:46
	 */
	private function deleteFile($file) {
		if (file_exists($file)) {
			unlink($file);
		}
	}
	
	/**
	 * 删除文件夹,同时删除文件夹下的文件
	 * 
	 * @author lwkai 2013-1-31 下午2:48:56
	 */
	private function deleteFolder($path){
		if (is_dir($path)) {
			foreach(scandir($path) as $val) {
				if ($val != "." && $val != "..") {
					if (is_dir($path . DS . $val)) {
						$this->deleteFolder($path . DS . $val);
					} else {
						$this->deleteFile($path . DS . $val);
					}
				}
			}
			rmdir($path);
		}
	}
	
	/**
	 * 根据参数来执行删除，是目录，则清空目录下的文件再删聊文件夹，
	 * 否则直接删除文件
	 * @param string $file 要删除的对象
	 * @author lwkai 2013-1-31 下午2:56:18
	 */
	private function _delete($file) {
		if (is_dir($file)) {
			$this->deleteFolder($file);
		} else {
			$this->deleteFile($file);
		}
	}
	
	/**
	 * 删除文件或者文件夹
	 * @param string $file 文件或者文件夹路径
	 * @author lwkai 2013-1-31 下午2:08:36
	 */
	public function delete($file) {
		if (is_array($file)) {
			foreach ($file as $key => $val) {
				$this->_delete($val);
			}
		} else {
			$this->_delete($file);
		}
	}
}