<?php
function smarty_function_formatwgroup($params, &$smarty)
{
	global $expertSlefWritingsGroup,$expertsWritingsGroup;
	$groupid = $params['groupid'];
	$sex = $params['sex'];
	$expertslef = $params['expertslef'];
	$text = '';
	if($expertslef){
		$text = $expertSlefWritingsGroup[$groupid]['name'];
	}else{
		$text = $expertsWritingsGroup[$groupid]['name'];
	}
	if($sex=='Фа'){
		$text = str_replace(db_to_html("Ы§"),db_to_html("Ыћ"),$text);	
	}
	
	return $text; 
}
?>