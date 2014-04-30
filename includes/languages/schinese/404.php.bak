<?php

  $seo_extension = '.html';

  require_once('includes/configure.php');

  mysql_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
  mysql_select_db(DB_DATABASE);


  // remove leading junk (eg "/" or "/test/")
  //$req = substr($_SERVER['REQUEST_URI'], 19); // local development
  $req = substr($_SERVER['REQUEST_URI'], 1); // local test site
  //$req = substr($_SERVER['REQUEST_URI'], 7); // test2 site

 // $req = substr($_SERVER['REQUEST_URI'], 1); // live server

	
  	$req = str_replace('-reviews','',$req);
	$req = str_replace('-compare-price','',$req);
	$req = str_replace('-question-answer','',$req);
	$req = str_replace('-resources','',$req);
	$req = str_replace('-product-details','',$req);
	$req = str_replace('-coupons-deals','',$req);	

 function checkformnu($checkstring,$full_url){
	
	 $newurl=explode($checkstring,$full_url);
	 if($newurl[0] != $full_url){
		return $checkstring;
	  }else{
	  	return '';
	  }
	 
	}

  $error404 = false;


  // new url handling

  // there might be a query string after the product's name. Extract it and set GET variables
  if(strpos($req, '?') !== false) {
      list($req, $query_string) = explode('?', $req);
      parse_str($query_string, $HTTP_GET_VARS);
      // globals will be set later
  }
  
 //check for pagin star
 
 //check for paging vlaue
	if(checkformnu('-p-',$_SERVER['REQUEST_URI']) == '-p-'){
  		$reqimplode = explode('-p-',$req);
		//$req = trim($reqimplode[0]).'.html';
		$HTTP_GET_VARS['page'] = trim(str_replace('.html','',$reqimplode[1]));
		$req = $_SERVER['REQUEST_URI'] = trim(str_replace('-p-'.$HTTP_GET_VARS['page'],'',$req));
  }//pagin value end
	

	if(checkformnu('/p-',$_SERVER['REQUEST_URI']) == '/p-'){
		
		$reqimplode = explode('/p-',$req);
		//$req = trim($reqimplode[0]);
		$reqexplodepage = explode('/',$reqimplode[1]);
		$HTTP_GET_VARS['page'] = trim($reqexplodepage[0]);
		$req = $_SERVER['REQUEST_URI'] = trim(str_replace('p-'.$HTTP_GET_VARS['page'].'/','',$req));
  }
 if(checkformnu('/s-',$_SERVER['REQUEST_URI']) == '/s-'){
		
		$reqimpsort = explode('/s-',$req);
		//$req = trim($reqimplode[0]);
		$reqexplodesort = explode('/',$reqimpsort[1]);
		$HTTP_GET_VARS['sort'] = trim($reqexplodesort[0]);
		$req = $_SERVER['REQUEST_URI'] = trim(str_replace('s-'.$HTTP_GET_VARS['sort'].'/','',$req));
  }
 
//check for pagin end

	
  if(checkformnu('/reviews.html',$_SERVER['REQUEST_URI']) == '/reviews.html'){
  		$reqimplode = explode('/',$req);
		$req = trim($reqimplode[0]).'.html';
		$_SERVER['PHP_SELF'] = $HTTP_SERVER_VARS['PHP_SELF'] = $PHP_SELF = 'stores_reviews.php';
  }else if(checkformnu('products.html',$_SERVER['REQUEST_URI']) == 'products.html'){
  	$reqimplode = explode('/',$req);
		$req = trim($reqimplode[0]).'.html';
		$_SERVER['PHP_SELF'] = $HTTP_SERVER_VARS['PHP_SELF'] = $PHP_SELF = 'stores_products.php';
  }else if(checkformnu('/products/',$_SERVER['REQUEST_URI']) == '/products/'){
  		$reqimplode = explode('/',$req);
		$req = trim($reqimplode[0]).'.html';
		$HTTP_GET_VARS['cPath'] = trim(str_replace('.html','',$reqimplode[2]));
		$_SERVER['PHP_SELF'] = $HTTP_SERVER_VARS['PHP_SELF'] = $PHP_SELF = 'stores_products.php';
  }else if(checkformnu('/reviews-details.html',$_SERVER['REQUEST_URI']) == '/reviews-details.html'){
  		$reqimplode = explode('/',$req);
		$req = trim($reqimplode[0]).'.html';
		$HTTP_GET_VARS['reviews_id'] = trim($reqimplode[1]);		
		$_SERVER['PHP_SELF'] = $HTTP_SERVER_VARS['PHP_SELF'] = $PHP_SELF = 'stores_reviews_details.php';
  }
  //echo basename($PHP_SELF);
  
  if(basename($PHP_SELF) == '404.php') {
  if(substr($req, 0 - strlen($seo_extension)) == $seo_extension) { // product
		$sql = "select p.products_id, p2c.categories_id from products p, products_to_categories p2c where p.products_id = p2c.products_id and products_urlname = '" . mysql_real_escape_string(substr($req, 0, strlen($req) - strlen($seo_extension))) . "'";
		$product = mysql_query($sql);
		if(list($products_id, $current_category_id) = mysql_fetch_row($product)) {
		  $HTTP_GET_VARS['products_id'] = $products_id;
		  $_SERVER['PHP_SELF'] = $HTTP_SERVER_VARS['PHP_SELF'] = $PHP_SELF = 'product_info.php';
		} else {
		  $error404 = true;
		}
  } else { // category  
			//$req = str_replace('/','',$req);
			$sql = "select categories_id from categories where categories_urlname = '" . mysql_real_escape_string($req) . "'";
			$cat = mysql_query($sql);
			if(list($current_category_id) = mysql_fetch_row($cat)) {
			  $_SERVER['PHP_SELF'] = 'index.php';
			  $HTTP_SERVER_VARS['PHP_SELF'] = 'index.php';
			  $PHP_SELF = 'index.php';
			} elseif(substr($req, -1) != '/') {
			  $sql = "select categories_id from categories where categories_urlname = '" . mysql_real_escape_string($req .'/') . "'";
			  $cat = mysql_query($sql);
			  if(list($current_category_id) = mysql_fetch_row($cat)) {
				header('HTTP/1.1 301 Moved Permanently');
				header('Location: ' . ((!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS']!="on") ? 'http://' : 'https://') . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '/');
				exit;
			  } else {
				$error404 = true;
			  }
			} else {
			  $error404 = true;
			}
  }
  }else{
  				$urlname = substr($req, 0, strlen($req) - strlen($seo_extension));
			    $sql = "select stores_id from stores where stores_urlname = '" . mysql_real_escape_string($urlname) . "'";
				$store = mysql_query($sql);
				if(list($stores_id) = mysql_fetch_row($store)) { // store
				$HTTP_GET_VARS['sId'] = $stores_id;
				}
  
  }




  if($error404 == true) {
    $PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'index.php';
    $HTTP_GET_VARS['error_message'] = 'Error 404 - The page you are looking for was not found on this server.';
    require('index.php');
  } else {
    header("HTTP/1.0 200 OK");
    header("Status: 200 OK");

    // set globals from GET vars:
    foreach($HTTP_GET_VARS as $key => $value) $$key = $value;
    $_GET = $HTTP_GET_VARS;

    
    include($_SERVER['PHP_SELF']);
  }
// ####################################################################
?>
