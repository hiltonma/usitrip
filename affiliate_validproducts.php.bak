<?php
/*
  $Id: affiliate_validproducts.php,v 2.00 2003/10/12

  OSC-Affiliate

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_AFFILIATE_BANNERS_BUILD);

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link(FILENAME_AFFILIATE_BANNERS_BUILD));

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title>All Products - usitrip</title>
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_STYLE;?>">
<link rel="stylesheet" href="<?php echo DIR_WS_TEMPLATES.TEMPLATE_NAME;?>/css/main.css" type="text/css"/>
<head>
<body>
<table width="760" align="center" class="infoBoxContents">
<tr>
<td colspan="2" align="right" >
<?php echo '<a class="sp3" href="' . tep_href_link(FILENAME_AFFILIATE_VALIDCATS) . '"><b>View Categories</b></a>'; ?>
</td>
</tr>
<tr>
<td colspan="2"    class="infoBoxHeading" align="center"><?php echo TEXT_VALID_PRODUCTS_LIST; ?></td>
</tr>
<?php     echo "<tr><td width='10%'><b>". TEXT_VALID_PRODUCTS_ID . "</b></td><td><b>" . TEXT_VALID_PRODUCTS_NAME . "</b></td></tr><tr>";
    $result = mysql_query("SELECT * FROM products, products_description WHERE products.products_id = products_description.products_id and products_description.language_id = '" . $languages_id . "' ORDER BY products_description.products_name");
    if ($row = mysql_fetch_array($result)) {

        do {
		$product_id_h = 'products_id='.$row['products_id'];
            echo "<td nowrap class='infoBoxContents'>".$row["products_id"]."</td>\n";
            echo "<td  nowrap class='infoBoxContents'><a target='_blank' href='". tep_href_link(FILENAME_PRODUCT_INFO, $product_id_h). "'>".db_to_html($row["products_name"])."</a></td>\n";
            echo "</tr>\n";
        }
        while($row = mysql_fetch_array($result));
    }
    echo "</table>\n";
?>
<p class="smallText" align="right"><?php echo '<a href="javascript:window.close()">' . TEXT_CLOSE_WINDOW . '</a>'; ?>&nbsp;&nbsp;&nbsp;</p>
<br>
</body>
</html>
<?php require('includes/application_bottom.php'); ?>