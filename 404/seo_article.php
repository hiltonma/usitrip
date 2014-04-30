<?php 
if ($error404 === false) return;
//SEO首页和列表页
if(preg_match('/^default\//', $req)){
	$PHP_SELF = 'seo_news.php';
	$req_array = explode('/',$req);
	for($i=0; $i<count($req_array); $i++){
		if(preg_match('/cid\-([0-9]+)/', $req_array[$i], $m)){
			$HTTP_GET_VARS['class_id'] = $_GET['class_id'] = $m[1];
			$PHP_SELF = 'article_news_list.php';
		}
		if(preg_match('/p\-([0-9]+)/', $req_array[$i], $m)){
			$HTTP_GET_VARS['page'] = $_GET['page'] = $m[1];
			$PHP_SELF = 'article_news_list.php';
		}
	}
	unset($_GET['cid']);
	unset($HTTP_GET_VARS['cid']);
	$HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = $PHP_SELF;
	$error404 = false;
}
//SEO文章页面
if(preg_match('~^article/~is', $req)){
	$PHP_SELF = 'article_news_content.php';
	$req_array = explode('/',$req);
	for($i=0; $i<count($req_array); $i++){
		if(preg_match('/nid\-([0-9]+)/', $req_array[$i], $m)){
			$HTTP_GET_VARS['news_id'] = $_GET['news_id'] = $m[1];
		}
	}
	$HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = $PHP_SELF;
	$error404 = false;
}

?>