<BR><BR><table border="0" align='center' width="100%" cellspacing="1" cellpadding="0">
			<TR>
				<TD class='tableHeading'>usitrip Site Map</TD>
			</TR>
			<TR>
				<TD class='tableHeading'><hr size='1'></TD>
			</TR>
			<TR>
				<TD class='smallText'><A HREF="<?=HTTP_SERVER.DIR_WS_HTTP_CATALOG;?>">home&nbsp;&nbsp;&nbsp;&nbsp;</A>
				<?php 					echo(
					'<a href="about_us.php" class="foot">' . BOX_INFORMATION_ABOUT_US. '</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="privacy-policy.php" class="foot">Privacy Policy</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="payment-faq.php" class="foot">Payment FAQ</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="copy-right.php" class="foot">Copy Right</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="customer-agreement.php" class="foot">' . 'Customer Agreement' . '</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="links.php" class="foot"> Link to us</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="' . tep_href_link('cancellation-and-refund-policy.php') . '" class="foot">' . 'Cancellation and Refund Policy' . '</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="site-map.php" class="foot">' . BOX_INFORMATION_SITE_MAP . '</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="' . tep_href_link(FILENAME_CONTACT_US) . '" class="foot">' . BOX_INFORMATION_CONTACT . '</a>');
				?>
				</TD>
			</TR>
			<TR>
				<TD class='tableHeading'>&nbsp;</TD>
			</TR>
			<TR>
				<TD class='tableHeading'>Tours by Categories</TD>
			</TR>
			<TR>
				<TD class='tableHeading'><hr size='1'></TD>
			</TR>
</table>

<?php // bof of display products.
	$products_query = tep_db_query("select p.featured_products, p.products_urlname, p.products_model, p.products_id, pd.products_name, p.products_is_regular_tour, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by pd.products_name");
		echo "select p.featured_products, p.products_urlname, p.products_model, p.products_id, pd.products_name, p.products_is_regular_tour, p.products_image, p.products_price, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by pd.products_name";
	?>

	<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>

 <?PHP 
 while ($products = tep_db_fetch_array($products_query)) {

	  $rows++;
      $sPath_new = 'products_id=' . $products['products_id'];
      $width = (int)(100 / 4) . '%';
      echo '<td align="left"  width="' . $width . '" valign="top" bgcolor="#FFFFFF" class="smallText">';
	  
	  	  echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO,$sPath_new) . '" class="smallText">' . $products ['products_name'] .' </a>&nbsp;&nbsp;</td>';

	  
      if ((($rows / 4) == floor($rows / 4)) && ($rows != $number_of_categories)) {
        echo '</tr>' ;
        echo '<tr>' ;
      }

 }
 //eof of display products.
 ?>

   </table>

<!--bof of display categories.-->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  
   $categories_query = tep_db_query( "select c.categories_status, c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
   echo "select c.categories_status, c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name";
  if (!tep_db_num_rows($categories_query)) { // there is no news
    echo '<!-- ' . 'No categories' . ' -->';
  } else {
  
  
	$tr = 0;
    while ($categories = tep_db_fetch_array($categories_query)) {
		if(($tr%4) == 0){echo "<tr>";}
		?>
	<td class='main'>
	<?php 	$cName = str_replace(" & ","-",$categories['categories_name']);
	$cName = str_replace(" ","-",$cName);
	
	$cPath_new = 'cId=' . $categories['categories_id'];
	if($HTTP_GET_VARS['cPath']){$last_words = '';}
	else{$last_words = '&nbsp;&nbsp;';}
      //echo '<a class="blackText2Copy" href="site-map.php?cId='.$categories['categories_id'].'">'.$categories['categories_name'].' Tours</a>';
	  echo '<a href="' . tep_href_link("site-map.php", tep_get_path($categories['categories_id'])) . '">' .  $categories['categories_name'] . $last_words . '&nbsp;';
	  $tr ++ ;
	  ?>
	  </td>
	  <?php   }
?>
</TABLE>
<?}?>
</td>
 
	<?if(isset($_GET['cPath'])){?>
   </tr>
    <tr><td colspan='5' align='right' class='tableHeading'><A HREF="javascript:history.back(-1)">Return Back</A>&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
	<?}?>
   </table>
<!--eof of display categories.-->