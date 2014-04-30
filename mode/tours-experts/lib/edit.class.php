<?php
class expertsAjax{
	private $_title = '';
	private $_content = '';
	private $_outdata = array();
	function addData($name,$value){
		$this->_outdata[$name]=$value;
	}
	function delData($name){
		unset($this->_outdata[$name]);
	}
	function initData(){
		$this->_outdata=array();
	}
	function output($echo=true){
		$outString='';
		foreach($this->_outdata as $name=>$value){
			$outString.='['.$name.']'.$value.'[/'.$name.']';
		}
		if($echo){
			echo $outString;
			return true;
		}else{
			return $outString;
		}
	}
}
?>