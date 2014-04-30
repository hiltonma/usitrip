<?php
require_once("includes/application_top.php");

  require_once(DIR_FS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);

 $valid_to_checkout= true;
  $cart->get_products(true);
  require(DIR_FS_CLASSES . 'order.php');
  $order = new order;
 include('googlecheckout/gcheckout.php');
 exit;
?>