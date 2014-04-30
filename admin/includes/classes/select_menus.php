<?php
/*
本档的类用於各个下拉功能表的选项
参数说明$now_type是指当前选择的ID，$dis是指是显示到选择功能表还是把当前选择的ID阵列的值直接显示出来，0为下接列表，其他值为直接显示
*/

class SelectMenusObj{
	//$array为选择功能表的选项值集合
	function selected_option($array,$now_type=0,$dis=0){
		//print_r($array);
		$now_type = strip_tags($now_type);
		$option_str="";
		$selected="";
		if($dis==0){
			foreach((array)$array as $key => $val){
				if($now_type==$key){$selected=' selected="selected" ';}
				$option_str.='<option value="'.$key.'" '.$selected.'>'.$val.'</option>';
				unset($selected);
			}
		}else{
			$option_str = $array[$now_type];
		}
		return $option_str;
	}
	//$table=db_table_name,$id=显示的功能表的值,$text=显示的功能表选项文字
	function selected_menus($table,$id,$text, $now_type=0,$dis=0,$begin='PleaseSelect', $parameters=''){
		$array=array();
		$array[0]=$begin;
		$query = tep_db_query("select $id , $text  from " . $table .$parameters);
		$row = tep_db_fetch_array($query);
		do{
			$k=preg_replace('/^.*\./','',$id);
			$t=preg_replace('/^.*\./','',$text);
			$array[(int)$row[$k]] = tep_output_string($row[$t]);
		} while ($row = tep_db_fetch_array($query));
		return $this->selected_option($array,$now_type,$dis);
	}
	
	
	//……依此类推
}
?>