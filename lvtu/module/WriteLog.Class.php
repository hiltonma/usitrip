<?php
class WiterLog{
	private $file;
	private $file_name;
	function __construct($file_path){
		$this->file_name=$file_path;
		if(file_exists($file_path)){
			if(is_writable($file_path)){
				$this->file=fopen($file_path, 'w+');
			}else{
				new Exception("can't write in ");	
			}
		}else{
			new Exception('not find this Log File;pleace check the file path!!!');
		}
	}
	function witerIn($txt){
		
		if(file_put_contents($this->file_name, file_get_contents($this->file_name).'\n'.$txt)===false){
			new Exception('can\'t write in this file');
			return false;
		}else return true;
	}
	function dropLine(){
		
	}
	function createFile(){
		
	}
	function __destruct(){
		fclose($this->file);
		unset($this->file);
	}
}
?>