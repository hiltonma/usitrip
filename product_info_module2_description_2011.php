<div class="iconTip">
<ul>
		
<?php if($product_info['display_hotel_upgrade_notes'] == '1') { //升级酒店标签?>
<li class="standard"><?php echo db_to_html("此行程提供酒店升级")?></li>
<?php } ?>
	<li class="print"><a href="<?php echo tep_href_link('printitinerary.php', 'products_id='.$product_info['products_id']); ?>" target="_blank" rel="nofollow"><?php echo TEXT_HEADING_PRINTABLE_VERSION; ?></a></li>
</ul>
	<?php if($product_info['display_itinerary_notes'] == '1' || $product_info['display_hotel_upgrade_notes'] == '1') { ?>
		  <?php if($product_info['display_itinerary_notes'] == '1') { ?>
		   <h2 style="display:none"><?php echo TEXT_HEADING_TOURS_ITINERARY_HEADER_NOTES;?></h2>
		  <?php } ?>
	<?php } ?>
	<?php
	//大瀑布团签证提示 start
	if($product_info['is_visa_passport'] > 0){
		if($product_info['is_visa_passport'] == 1){
			echo '<h2 style="display:none">'.TEXT_VISA_PASS_NOTREQ.'</h2>';
		}
		if($product_info['is_visa_passport'] == 2){
			echo '<h2 style="display:none">'.TEXT_VISA_PASS_YREQ.'</h2>';
		}
	}
	//大瀑布团签证提示 end
	?>
	<?php
	//travel_tips	其它提示
	if(tep_not_null($product_info['travel_tips'])){
		echo '<h2 style="display:none">'.db_to_html($product_info['travel_tips']).'</h2>';
	}
	?>
</div>

<?php
//团剩余位置
include('products_remaning_seats.php');
?>
<?php
//行程注意事项 start
if(tep_not_null($product_info['products_notes'])){
?>
<div class="routeNote">
  <table cellspacing="0" cellpadding="0" border="0">
	<tr>
	  <td valign="top" class="content">
		<div class="right"><?php echo db_to_html($product_info['products_notes']);?></div> </td>
	</tr>
  </table>
</div>
<?php
}
//行程注意事项 end
?>

<?php
//标准化的行程介绍
$products_travel_sql = tep_db_query('SELECT * FROM `products_travel` WHERE products_id="'.(int)$products_id.'" and langid="'.(int)$languages_id.'"  ORDER BY `travel_index` ASC ');
$products_travel = tep_db_fetch_array($products_travel_sql);
$products_travel_rows = tep_db_num_rows($products_travel_sql);
if((int)$products_travel['travel_index'] && tep_not_null($products_travel['travel_content'])){
?>
        <ul class="route">
		<?php
		$day_num = 0;
		do{
			$day_num++;
			if($products_travel_rows>1){
				$day_num_string = db_to_html('第'.$day_num.'天');
			}else{ $day_num_string =""; }
		?>
          <li>
            <?php
			if(tep_not_null($day_num_string)){
			?>
			<h2><b><?php echo $day_num_string;?></b> <?php echo db_to_html($products_travel['travel_name']);?></h2>
            <?php
			}
			?>
			<?php
			if(tep_not_null($products_travel['travel_img'])){
				if(basename($products_travel['travel_img']) != $products_travel['travel_img']){
					$images_src = $products_travel['travel_img'];
				}else{
					$images_src = 'images/'.$products_travel['travel_img'];
				}
				if(tep_not_null($images_src)){
			?>
			<img src="<?= $images_src;?>" class="routeImg" alt="<?= db_to_html($products_travel['travel_imgalt']);?>" title="<?= db_to_html($products_travel['travel_imgalt']);?>" />
            <?php
				}
			}
			$p_header = "<p>";
			$p_footer = "</p>";
			if(!tep_not_null($images_src)){
				$p_header = "<br />";
				$p_footer = "<br />";
			}
			?>
			<?php echo $p_header.nl2br(db_to_html($products_travel['travel_content'])).$p_footer;?>
            <?php
			if(tep_not_null($products_travel['travel_hotel'])){
				$hotel_array = explode("\n",$products_travel['travel_hotel']);
				$hotel_string = "";
				for($n=0; $n<sizeof($hotel_array); $n++){
					//处理酒店星级
					if(tep_not_null($hotel_array[$n])){
						$hotel_infos = explode('|', $hotel_array[$n]);
						$h_sql = tep_db_query('SELECT hotel_id, hotel_name, products_id FROM `hotel` WHERE hotel_name="'.tep_db_output(trim($hotel_infos[0])).'" Limit 1');
						$h_row = tep_db_fetch_array($h_sql);
						$hotel_img = '<img src="image/icons/icon_hotal.gif" /> '; 
						if($hotel_infos[1]>1){
							$hotel_img = '<img src="image/icons/upgrad_hotel.gif" /> ';
						}
						if((int)$h_row['hotel_id']){
							if((int)$h_row['products_id']){	//有酒店产品就优先用产品页面的url地址 Howard fixed by 2013-04-02
								$hotel_string.=$hotel_img.'<a href="'.tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $h_row['products_id']).'" target="_blank" class="sp3">'.$hotel_infos[0].'</a>, ';
							}else{	//否则就只列酒店资料的网页
								$hotel_string.=$hotel_img.'<a href="'.tep_href_link('hotel.php','hotel_id='.(int)$h_row['hotel_id'].'&products_id='.(int)$product_info['products_id']).'" target="_blank" class="sp3">'.$hotel_infos[0].'</a>, ';
							}
						}else{
							$hotel_string.=$hotel_img.$hotel_infos[0].', ';
						}
					}
				}
				$hotel_string = substr($hotel_string,0,-2);
			?>
			<div class="hotel">
			<?php
			//显示酒店信息Howard
			if(strpos($hotel_string,'邮轮上')!==false || $isCruises==true){
				echo db_to_html("住宿: ".$hotel_string);
			}else{
				echo db_to_html("酒店: ".$hotel_string."或者同等级酒店");
			}
			?>
			</div>
			<?php
			}
			?>
          </li>
        <?php
		}while($products_travel = tep_db_fetch_array($products_travel_sql));
		?>
		</ul>
<?php
}else{
//非标准化的行程介绍
?>
<!--product_info_module2_description start-->
<div class="route">

<?php
$h_sql = tep_db_query('SELECT hotel_id, hotel_name FROM `hotel` Order By hotel_name_length DESC ');
$pat = array();
$rep = array();
$pattens = array();
$replace = array();
$separator = '###';

while($h_rows = tep_db_fetch_array($h_sql)){
	$pat[count($pat)] = "/(".preg_quote($h_rows['hotel_name']).")/";
	$str = $h_rows['hotel_name'];
	$len = mb_strlen($str,'GB2312');
	$str_sp = '';
	for($i=0;$i<$len;$i++){
		$str_sp .= mb_substr($str,$i,1,'GB2312').$separator;
	}
	$str_sp = substr($str_sp,0,(strlen($str_sp)-strlen($separator)));

	$rep[count($rep)] = $str_sp;
	$pattens[count($pattens)] = "/(".preg_quote($str_sp).")/";
	//$replace[count($replace)] = '<a href="'.tep_href_link('hotel.php','hotel_id='.(int)$h_rows['hotel_id'].'&products_id='.(int)$product_info['products_id']).'" target="_blank" class="sp3">'.str_replace($separator,'',$str_sp).'</a>';
	$replace[count($replace)] = str_replace($separator,'',$str_sp);
}

$products_description = stripslashes2($product_info['products_description']);
$products_description = preg_replace($pat,$rep,$products_description);
$products_description = preg_replace($pattens,$replace,$products_description);
$rep_text_on= false;
if(count($key_pert)>0 && $rep_text_on==true){
	$products_description = preg_replace($key_pert,$key_rep,$products_description);
}
echo db_to_html($products_description);
?>
<div style="clear:both;"></div>
</div>
<?php
}
?>

