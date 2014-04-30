<?php
//10版评价回访

?>
<script type="text/javascript"> 
document.oncontextmenu=function(e){return false;} 
document.onselectstart=function(e){return false;} 
document.oncopy=function(e){return false;}
</script> 
<div class="review">
    <?php
	if ($reviews_split->number_of_rows > 0) {
    	if ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) {
	?>
	<div id="split_page_top">
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
		  <tr>
			<td class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?></td>
			<td align="right" class="smallText"><?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></td>
		  </tr>
		</table>
	</div>
	<?php
		}
		//print_r($reviews_split->sql_query);
		
		$reviews_query = tep_db_query($reviews_split->sql_query);
		$reviews_row_cnts = 0;
   		while ($reviews = tep_db_fetch_array($reviews_query)) {
			if($reviews_row_cnts  % 2 == 0){
				$review_box_class = 'review_box';
			}else{
				$review_box_class = 'review_box review_box_ou';
			}
			$reviews_row_cnts++;
			
			//笑脸图标
			$face_imgs = '<b>'.$reviews['rating_total'].'%</b>';
			if($reviews['rating_total']<90){
				$face_imgs = '<b class="level_1">'.$reviews['rating_total'].'%</b>';
			}
			if($reviews['rating_total']<80){
				$face_imgs = '<b class="level_2">'.$reviews['rating_total'].'%</b>';
			}
			if($reviews['rating_total']<1){
				$face_imgs = '<b>90%</b>';
			}
			
			
			ob_start();
	?>
    
    <div class="user-reply">
        <div class="user-review">
            <p>满意度</p>
            <em><?php echo $reviews['rating_total']?>%</em>
        </div>
        
        <div class="user-review-cnt">
        	<div class="user-review-tit"><span class="user-review-reply"><?php
		  $ul_array = get_reviews_array();
		  $dinggou = $xincheng = '';
		  
		  for($i=0; $i<count($ul_array); $i++){
			  foreach($ul_array[$i]['opction'] as $key_val => $text){
				if($reviews['rating_'.$i] == $key_val && ($ul_array[$i]['title'] == '预定' || $ul_array[$i]['title'] == '行程')){
					if ($ul_array[$i]['title'] == '预定') {
						echo '订购：' . $text . '&nbsp;&nbsp;';
					} else {
						echo '行程：' . $text . '&nbsp;&nbsp;';
					}
					//echo $ul_array[$i]['title'] . ':' . $text . '&nbsp;';//.$reviews['rating_'.$i]
					break;
				}
			  }
		  }
		?></span><span class="user-name-date"><b><?php echo tep_db_output(ucfirst(($reviews['customers_name'])))?></b>(<?php echo date('Y/m/d',strtotime($reviews['date_added']))?>)</span></div>
            <div class="user-review-text"><p><?php echo tep_db_output(ucfirst(($reviews['reviews_title'])))?><br/>
            <?php echo (tep_db_output($reviews['reviews_text']))?>
            </p></div>
			<?php if((int)$reviews['products_id']){?>
			<div class="user-review-product">
			<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $reviews['products_id'] . '&mnu=reviews').'">' . $reviews['products_name'] . '</a>'; ?>
			</div>
			<?php }?>
        </div>
    </div>
    <?php echo db_to_html(ob_get_clean()) ?>
    <?php 
	// 以下是原来的代码 by lwkai add 2012-04-23 start { 
	/*
	?>
	<div class="<?=$review_box_class ?>">
    <div class="pr_b_q">
    <div class="pr_b_q_1"><p><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $reviews['products_id'] . '&mnu=reviews').'">' . db_to_html($reviews['products_name']) . '</a>'; ?> <u><?php echo $reviews['add_time'];?></u></p></div>
    <div class="pr_b_qq"><div class="review_post"><?php echo '<span class="name_review">'.ucfirst(db_to_html($reviews['customers_name'])).'</span>'. $face_imgs?></div>
	<div class="review_pj">
		<?php
		  $ul_array = get_reviews_array();
		  for($i=0; $i<count($ul_array); $i++){
			  foreach($ul_array[$i]['opction'] as $key_val => $text){
				if($reviews['rating_'.$i]==$key_val){
					echo db_to_html($ul_array[$i]['title']).':'.db_to_html($text).'&nbsp;';//.$reviews['rating_'.$i]
					break;
				}
			  }
		  }
		?>
	</div>
    <p>
	<b><?php echo ucfirst(db_to_html($reviews['reviews_title']))?></b><br />
	<?php echo db_to_html(tep_db_output($reviews['reviews_text']))?>
	</p>
    </div>
    </div>
    <div class="pr_b_qing">
    <div class="pr_b_qimg"></div>
    </div>
    </div>
	<?php
	*/
	// 以上是原来的代码 by lwkai add 2012-04-23 end }
		}
		if ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3')) {
	?>
	<div id="split_page_bottum">
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
		  <tr>
			<td class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info'))); ?></td>
			<td align="right" class="smallText"><?php echo $reviews_split->display_count(TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></td>
		  </tr>
		</table>
	</div>
    <?php
		}
	}
	?>
	
	
    
    </div>
<?php
//10版评价回访end
?>
