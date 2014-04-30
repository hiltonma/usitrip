<?php
  $seo_extension = '.html';

  require_once('includes/configure.php');
  $req = substr($_SERVER['REQUEST_URI'], 1); // live site

  $error403 = true;
  if($error403 == true) {
    header("HTTP/1.0 404 OK");
    header("Status: 404 OK");
	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'] = $_SERVER['PHP_SELF'] = 'index.php';
    $HTTP_GET_VARS['error_message'] = "Error 404 - The page you are looking for was not found on this server.....".$req;
    require('index.php');
  } else {
    header("HTTP/1.0 200 OK");
    header("Status: 200 OK");

    // set globals from GET vars:
    foreach($HTTP_GET_VARS as $key => $value) $$key = $value;
    $_GET = $HTTP_GET_VARS;

    include($_SERVER['PHP_SELF']);

  }
?>