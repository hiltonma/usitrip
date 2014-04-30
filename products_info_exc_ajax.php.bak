<?php
require_once("includes/application_top.php");
require_once(DIR_FS_INCLUDES . 'ajax_encoding_control.php');

$products_info_sql = tep_db_query('SELECT products_notes FROM `products_description` WHERE products_id="'.(int)$_GET['products_id']. '" AND language_id="'.(int)$languages_id.'" ');
$products_info = tep_db_fetch_array($products_info_sql);
	

//行程注意信息 start
if(tep_not_null($products_info['products_notes'])){
?>

     <div class="pl_1 Travel_Note">
      <div>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
        <td valign="middle" class="Travel_Note_icon"><p class="cu"><?php echo db_to_html('注<br>意')?></p></td>
        <td valign="top" class="Travel_Note_text">
		<p><?php echo db_to_html($products_info['products_notes'])?></p> </td>

        </tr>
      </table>
      </div>
      </div>
<?php
}
//行程注意信息 end

//评论信息 start
$reviews_query = tep_db_query("select r.*, rd.reviews_title, rd.reviews_text from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '" . (int)$_GET['products_id'] . "' and r.reviews_id = rd.reviews_id and r.reviews_status='1' and rd.languages_id = '" . (int)$languages_id . "' order by r.reviews_id desc Limit 2");
$reviews = tep_db_fetch_array($reviews_query);
if((int)$reviews['reviews_id']){
	$href_reviews_all = tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=reviews&products_id='.(int)$_GET['products_id']);
	if(defined('USE_JS_SHOW_PRODUCT_DETAIL_CONTENT') && USE_JS_SHOW_PRODUCT_DETAIL_CONTENT=='true'){
		$display_fast=true;
		$href_reviews_all = 'javascript:void(0); shows_product_detail_content(\'c_review\'); scroll(0,400);';
	}
?>
<div id="reviews_for_c_study">
	<h2>
	<?php echo db_to_html('评价回访(<span id="total_reviews">0</span>)')?>
	<a href="<?php echo $href_reviews_all?>"><?php echo db_to_html('查看全部')?></a>
	</h2>
<?php
	$loop_do = 0;
	do{
		$loop_do++;
		if($loop_do%2==0){
			$review_box_class = 'review_box review_box_ou';
		}else{
			$review_box_class = 'review_box';
		}
?>
<div class="<?=$review_box_class?>">
	  <DIV class="pr_b_q">
	    <div class="pr_b_q_1">
	      <p><span><?php echo db_to_html($reviews['customers_name'])?></span> <?php echo '<u style="float:none">'.sprintf(tep_date_long_review($reviews['date_added'])).'</u>'; ?></p>
          
		  <div class="review_post11">

            <div class="review_post">
          <?php
		  if((int)$reviews['rating_total']){
		  	//笑脸图标
			$face_imgs = '<b>'.$reviews['rating_total'].'%</b>';
			if($reviews['rating_total']<90){
				$face_imgs = '<b class="level_1">'.$reviews['rating_total'].'%</b>';
			}
			if($reviews['rating_total']<80){
				$face_imgs = '<b class="level_2">'.$reviews['rating_total'].'%</b>';
			}
			echo $face_imgs;
		  }
		  ?>
			</div>
		    <u>
	      <?php
		  $li_array = get_reviews_array();
		  for($i=0; $i<count($li_array); $i++){
		  ?>
		      
	        <?php
		  
		  foreach($li_array[$i]['opction'] as $key_val => $text){
		  	if($reviews['rating_'.$i]==$key_val){
				echo db_to_html($li_array[$i]['title']).':'.db_to_html($text);//.$reviews['rating_'.$i]
				break;
			}
		  }
		  ?>
	        <?php
		  }
		  ?>
			</u>          </div>
		  
        </div>
	    <DIV class="pr_b_qq">

	  <p>
	  <b><?php echo db_to_html(tep_db_output($reviews['reviews_title']))?></b> <br />
		<?php
		$substr_text = cutword(db_to_html(tep_db_output($reviews['reviews_text'])),290);
		echo $substr_text;
		?>
		</p>
	  <p style="text-align:right; padding-right:5px;"><a href="<?php echo $href_reviews_all?>" class="sp3"><?php echo db_to_html('详细')?></a>
      </p>

	  
		  </DIV>
	  </DIV><DIV class="pr_b_qing"><DIV class="pr_b_qimg"></DIV></DIV>
	 </div>
<?php
	}while($reviews = tep_db_fetch_array($reviews_query));
?>
</div>
<div class="pr_b">
	  <div class="pr_b_t sp10 sp6">

  	    <div style="float: left;">
  	      <!-- // Points/Rewards Module V2.1rc2a start //-->
  	      <?php
		   if ((USE_POINTS_SYSTEM == 'true') && (tep_not_null(USE_POINTS_FOR_REVIEWS))) {
		?>
	  	    <?php 
				  
				  //howard edited
				  if((int)$customer_id){
					echo sprintf(REVIEW_HELP_LINK, USE_POINTS_FOR_REVIEWS, '<a href="' . tep_href_link('my_points.php','', 'SSL') . '" class="sp3" title="' . MY_POINTS_VIEW . '">' . MY_POINTS_VIEW . '</a>');
				  }else{
					echo sprintf(REVIEW_HELP_LINK, USE_POINTS_FOR_REVIEWS, '<a href="' . tep_href_link('points.php') . '" class="sp3" title="' . TEXT_MENU_JOIN_REWARDS4FUN . '">' . TEXT_MENU_JOIN_REWARDS4FUN . '</a>');
				  }
				  //howard edited end
				  ?>
	  	    <br>
  	      <?php
		  }
		?>
  	      <!-- // Points/Rewards Module V2.1rc2a eof //-->
          </div>
	    <div align="right">
		<?php if($display_fast!=true){?>
		<a style="CURSOR: pointer" onclick="javascript:showPopup(&quot;WriteNewReview&quot;,&quot;WriteNewReviewCon&quot;,1);" class="sp3" title="Click here to Submit Your Review"><?php echo tep_image_submit('button_write_review.gif', 'button_write_review.gif', ' id="button_write_review" ') ?></a>
		<?php
		}else{
		?>
		<a style="CURSOR: pointer" onclick="javascript:shows_product_detail_content('c_review'); showPopup(&quot;WriteNewReview&quot;,&quot;WriteNewReviewCon&quot;,1); scroll(0,400);" class="sp3" title="Click here to Submit Your Review"><?php echo tep_image_submit('button_write_review.gif', 'button_write_review.gif', ' id="button_write_review_1" ') ?></a>
		<?php
		}
		?>
		</div>
      </div></div>
<?php
}
//评论信息 end
?>

<?php
//已经被移到product_info.tpl.php文件最后
/*if($display_fast!=true){
	require_once('write_review_ajax.php');
}*/
?>