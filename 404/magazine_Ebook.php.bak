<?php 
if ($error404 === false) return;
if(preg_match('~^magazine/~is', $req)){
	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'magazine.php';
	$error404 = false;
	$req_array = explode('/',$req);
	foreach($req_array as $rk=>$rv){
		list($gk,$gv)=explode('-',$rv);
		if($gv && $gk)${$gk}=$HTTP_GET_VARS[$gk]=$_GET[$gk]=$gv;
	}
}
if(preg_match('~^Ebook/~is', $req)){
	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'Ebook.php';
	$error404 = false;
	$req_array = explode('/',$req);
	foreach($req_array as $rk=>$rv){
		list($gk,$gv)=explode('-',$rv);
		if($gv && $gk)${$gk}=$HTTP_GET_VARS[$gk]=$_GET[$gk]=$gv;
	}
}
?>