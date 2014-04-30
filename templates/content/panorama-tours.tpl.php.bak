<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
 
<div class="leftside"> 
	  <a href="#"><img src="image/banner_4.jpg"/></a>
      <div class="left_bg"><h1><?php echo db_to_html($cat_row['categories_name'])?></h1>
        <div class="introduction"><h4><?php echo db_to_html('È«¾°ÓÎÍÆ¼ö')?></h4> <div class="jingpin_left2"><div class="middle_img4"><img src="image/brige_jingmen.jpg" /></div>
<?php echo db_to_html($cat_row['categories_description'])?>
<ul>

<?php
$recom_sql = tep_db_query('SELECT * FROM `products` p, `products_description` pd, `products_to_categories` ptc WHERE p.products_id=pd.products_id AND ptc.products_id=p.products_id AND ptc.categories_id="'.$current_category_id.'" AND p.products_status=1');

while($recom_rows = tep_db_fetch_array($recom_sql)){
?>
<li>-<a class="ff_a" title="<?php echo db_to_html(tep_db_output($recom_rows['products_name']))?>" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $recom_rows['products_id']);?>"><?php echo cutword(db_to_html(tep_db_output($recom_rows['products_name'])),30)?></a><span class="sp1"> [<?php echo db_to_html(tep_db_output($recom_rows['products_model']))?>]</span></li>
<?php 
}
?>
</ul>

<div class="clear"></div>
</div></div>
     
		<div id="div_product_listing" class="main" style="width:95%;">
				   <?php 								
				 		include(DIR_FS_MODULES . 'product_listing_index_products.php'); 						
					?>
		</div>
				 
</div>
	  
	 </div> 

	</td>
    <td valign="top">
<?php require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/column_right.php'); ?>

	</td>
  </tr>
</table>