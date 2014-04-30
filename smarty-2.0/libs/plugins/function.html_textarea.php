<?php 
function smarty_function_html_textarea($params, &$smarty){
	$name = $params['name'];
	global $$name;
	$value = $params['value'].'';
	$$name = $value;
	$parameters = $params['parameters'].'';
	
	$wrap = $params['wrap'];
	$cols = $params['cols'];
	$rows = $params['rows'];

	
	$reinsert_value = $params['reinsert_value'];
	$reinsert_value = $reinsert_value!='false'?true:false;
	unset($params['name'],$params['value'],$params['parameters'],$params['wrap'],$params['reinsert_value'],$params['cols'],$params['rows']);
	foreach($params as $key=>$value){
		$parameters .= ' '.$key.'="'.$value.'"';
	}
	$parameters = db_to_html($parameters);
	return tep_draw_textarea_field($name, $wrap, $cols, $rows, $value, $parameters, $reinsert_value);
}
?>