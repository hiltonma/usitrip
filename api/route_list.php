<?php
require('route.global.php');
$dom = createDom();
$domroot = cel("routeIds",$dom);
foreach($productsData as $id=>$model){
	$idItem = cel("idItem");
	$idItem->setAttribute("id", $id);
	$idItem->setAttribute("no", $model);
	//$idItem->appendChild(cval($model));
}
outDom($dom);
?>