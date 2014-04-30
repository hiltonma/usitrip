<?php 
function smarty_function_html_input($params, &$smarty){
	$name = $params['name'];
	global $$name;
	$value = $params['value'].'';
	$$name = $value;
	$parameters = $params['parameters'].'';
	
	$type = $params['type'];
	!$type && $type='text';
	
	$reinsert_value = $params['reinsert_value'];
	$reinsert_value = $reinsert_value!='false'?true:false;
	
	$enterkey = $params['enterkey'];
	$enterkey = $enterkey!='true'?false:true;
	unset($params['name'],$params['value'],$params['parameters'],$params['type'],$params['reinsert_value'],$params['enterkey']);
	foreach($params as $key=>$value){
		$parameters .= ' '.$key.'="'.$value.'"';
	}
	$parameters = db_to_html($parameters);
	return tep_draw_input_field($name, $value, $parameters, $type, $reinsert_value,$enterkey);
}
?>