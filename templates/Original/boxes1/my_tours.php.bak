<?php
/* 这些功能已经被集中到includes/functions/index.php
if(basename($PHP_SELF)=='index.php' && (int)$_GET['cPath']<1){	//for index.php
?>

<?php //销售排行榜?>
<?php
$prod_id_str ='';
if(defined('TOURS_HOMEPAGE_BEST_SELLERS')){
	$prod_id_str = TOURS_HOMEPAGE_BEST_SELLERS;
}

if(tep_not_null($prod_id_str)){
	$prod_id_array = explode(',',$prod_id_str);
	
	//取得上一个月的时间月分
	$this_moth = date('m');
	$this_year = (string)date('Y');
	$last_moth = $this_moth-1;
	if($last_moth<1){ $last_moth = 12; $this_year--;}
?>

<div class="my_tours">
      <div class="leftside-top"></div>
        <div class="leftside-box">
	    <h3><?php echo db_to_html('热销').'TOP5('.$this_year.'-'.$last_moth.')'?></h3>
	    <div class="dongtai">
	      <ul>
    <?php
	$j=1;
	for($i=0; $i<min(5,count($prod_id_array)); $i++){
	$products_sql = tep_db_query("select p.products_id,products_name FROM products as p, products_description as pd WHERE p.products_id=pd.products_id AND p.products_id ='".$prod_id_array[$i]."' ");
	while($products_rows = tep_db_fetch_array($products_sql)){
		$li_dongtai = 'li_dongtai2';
		if($j%2==0){$li_dongtai = 'li_dongtai';}
	?>
	<li class="<?php echo $li_dongtai ?>"><a title="<?php echo db_to_html(tep_db_output(($products_rows['products_name']))) ?>" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products_rows['products_id']);?>" ><?php echo cutword(db_to_html(tep_db_output(($products_rows['products_name']))),36) ?></a></li>
	<?php
	$j++;
	}
	}
	?>

	  	  </ul>
	      </div>
       
	<?php
	//特价start
	$p_id_str = '970,719,411';
	if(defined('TOURS_HOMEPAGE_SPECIAL_OFFERS')){
		$p_id_str = TOURS_HOMEPAGE_SPECIAL_OFFERS;
	}

	$specials_sql = tep_db_query("select s.specials_new_products_price, p.products_image, p.products_id,pd.products_name, p.products_price,p.products_tax_class_id from ".TABLE_SPECIALS." as s, ".TABLE_PRODUCTS." as p, ".TABLE_PRODUCTS_DESCRIPTION." as pd  where s.products_id in(".$p_id_str.") and s.products_id = p.products_id AND p.products_id = pd.products_id and pd.language_id='".(int) $languages_id."' and p.products_status=1 and s.status=1 Order BY s.specials_date_added DESC limit 4 ");
	$specials_rows = tep_db_fetch_array($specials_sql);
	if((int)$specials_rows['products_id']){
	?>   
	   <h3 class="left-side-title"><?php echo db_to_html('特价')?><span style="padding-left:195px" ><!--<a href="#" class="xiaozi" >全部</a>-->&nbsp;</span></h3>
       <div class="tejia-home">
	      <ul>
	        <?php
			$do_loop = 0;
			do{
				$li_style = '';
				if($do_loop>=2){
					$li_style = ' style="padding-bottom:8px;" ';
				}
			?>
			<li <?=$li_style?>><div class="middle_img2"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials_rows['products_id']);?>"><?php echo tep_image(DIR_WS_IMAGES . $specials_rows['products_image'], db_to_html($specials_rows['products_name']), 49, 38) ;?></a></div>
            <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials_rows['products_id']);?>" title="<?php echo db_to_html(tep_db_output($specials_rows['products_name']))?>"><?php echo cutword(db_to_html(tep_db_output($specials_rows['products_name'])),30,'')?></a><p><i><?php echo str_replace('.00','',$currencies->display_price($specials_rows['products_price'], tep_get_tax_rate($specials_rows['products_tax_class_id'])))?></i> <b class="highline-txt"><?php echo str_replace('.00','',$currencies->display_price($specials_rows['specials_new_products_price'],tep_get_tax_rate($specials_rows['products_tax_class_id'])))?></b>  <font class="xiaozi highline-txt"><?php echo db_to_html('省'.$currencies->display_price(($specials_rows['products_price']-$specials_rows['specials_new_products_price']),tep_get_tax_rate($specials_rows['products_tax_class_id'])))?></font> </p></li>
			<?php
				$do_loop++;
			}while($specials_rows = tep_db_fetch_array($specials_sql));
			?>
	  
	  </ul>
	      </div>
	<?php
	}
	//特价end
	?>
  </div>
    <div class="leftside-bottom"></div>
   <div class="clear"></div></div>
   

<?php
}
//销售排行榜end
?>


<?php }*/?>