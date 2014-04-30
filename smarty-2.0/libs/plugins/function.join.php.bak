<?php
function smarty_function_join($params, &$smarty)
{
	$array = $params['array'];
	$expt = $params['expt'];
	$dbtohtml = $params['dbtohtml'];
	$dbtohtml = $dbtohtml!='false'?true:false;
	if(is_array($array)){
		$array=join($expt,$array);
	}
	$dbtohtml && $array = db_to_html($array);
	return $array;

}
?>