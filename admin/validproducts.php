<?php
/*
  $Id: validproducts.php,v 0.01 2002/08/17 15:38:34 Richard Fielder

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com



  Copyright (c) 2002 Richard Fielder

  Released under the GNU General Public License
*/

require('includes/application_top.php');


?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title>Valid Categories/Products List</title>
<style type="text/css">
<!--
h4 {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; text-align: center}
p {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;}
th {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;}
td {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px;}
-->
</style>
</head>
<body>
<table width="550" border="1" cellspacing="1" bordercolor="gray">
<tr>
<td colspan="3">
<h4><?php echo TEXT_VALID_PRODUCTS_LIST; ?></h4>
</td>
</tr>
<?
    echo "<tr><th>". TEXT_VALID_PRODUCTS_ID . "</th><th>" . TEXT_VALID_PRODUCTS_NAME . "</th><th>" . TEXT_VALID_PRODUCTS_MODEL . "</th></tr><tr>";
    $result = tep_db_query("SELECT * FROM products, products_description WHERE products.products_id = products_description.products_id and products_description.language_id = '" . $languages_id . "' ORDER BY products_description.products_name");
    if ($row = tep_db_fetch_array($result)) {
        do {
            echo "<td>".$row["products_id"]."</td>\n";
            echo "<td>".$row["products_name"]."</td>\n";
            echo "<td>".$row["products_model"]."</td>\n";
            echo "</tr>\n";
        }
        while($row = tep_db_fetch_array($result));
    }
    echo "</table>\n";
?>
<br>
<table width="550" border="0" cellspacing="1">
<tr>
<td align=middle><input type="button" value="Close Window" onClick="window.close()"></td>
</tr></table>
</body>
</html>
