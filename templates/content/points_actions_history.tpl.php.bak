<?php //echo tep_get_design_body_header(HEADING_TITLE,1); ?>
					 <!-- content main body start -->
<!-- body //-->
<style type="text/css">
h2{ line-height:24px;}
.pointsIntro li{ list-style:disc inside none; padding-left:15px; color:#F7860F; line-height:18px;}
p{ padding-left:15px; line-height:18px;}
.titleSmall h3{ margin-top:2px;}
.infoBox{ border:1px solid #C5E6F9; border-top:0;}
td.productListing-heading{ padding:0 5px; background:#F3FAFF; line-height:24px;}
td.productListing-data{ padding:0 5px; line-height:30px; border-bottom:1px solid #e1e1e1;}
</style>

<table border="0" width="100%" cellspacing="3" cellpadding="3" style="border:1px solid #AED5FF;">
  <tr>
    
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <?php /* ?><tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main"><b><?php echo HEADING_TITLE; ?></b></td>
            <td class="main" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'money.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr><?php */ ?>
	  <tr>
	  	<td>
		<?php
		//require('includes/rewards4fun_page_navi.php');
		?>
		</td>
	  </tr>
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="2" style=" padding:0 0 10px; font-size:14px;">           


<?php
  $has_point = tep_get_shopping_points($customer_id);
  if ($has_point > 0) {
?>
              <td class="main" style="border:1px solid #ffc75f;background:#fffee9;padding:8px 15px;"><?php echo sprintf(MY_POINTS_CURRENT_BALANCE, '<strong class="color_orange">' . number_format($has_point,POINTS_DECIMAL_PLACES) . '</strong>','<strong class="color_orange">' . $currencies->format(tep_calc_shopping_pvalue($has_point)) . '</strong>'); ?>
			  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo db_to_html('将要过期的积分');?> <b style="color:#FF0000">0</b><?php echo db_to_html('（一个月内）');?>
			  </td>
<?php
  } else {
         echo'<td class="main"><b>' . TEXT_NO_POINTS . '</b></td>';
  }
?>
            </tr>
          </table>
		  
<div class="title titleSmall">
	<b></b><span></span>
	<h3><?php echo TEXT_INTRO_STRING_TOP; ?></h3>
</div>
		  
		  
<?php
    $pending_points_query = "select unique_id, orders_id, points_pending, points_comment, date_added, points_status, points_type, feedback_other_site_url, products_id, admin_id from " . TABLE_CUSTOMERS_POINTS_PENDING . " where customer_id = '" . (int)$customer_id . "' order by unique_id desc";
    $pending_points_split = new splitPageResults($pending_points_query, MAX_DISPLAY_POINTS_RECORD);
    $pending_points_query = tep_db_query($pending_points_split->sql_query);

    if (tep_db_num_rows($pending_points_query)) {
?><div id="dataTable">
          <table border="0" width="100%" cellspacing="1" cellpadding="0" class="" style="background:#dbdbdb">

			<tr class="productListing-heading">
              <td height="33" align="center" style="background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/user_bg11.gif)"><?php echo HEADING_ORDER_DATE; ?></td>
              <td width="10%" align="center" nowrap="nowrap" style="background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/user_bg11.gif)"><?php echo HEADING_ORDERS_NUMBER; ?></td>
              <td align="center" nowrap="nowrap"  style="background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/user_bg11.gif)"><?php echo HEADING_POINTS_COMMENT; ?></td>
              <td align="center" style="background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/user_bg11.gif)"><?php echo HEADING_POINTS_STATUS; ?></td>
              <td align="center" style="background:url(<?= DIR_WS_TEMPLATE_IMAGES;?>nav/user_bg11.gif)"><?php echo HEADING_POINTS_TOTAL; ?></td>
            </tr>
			<tr>
<?php
    while ($pending_points = tep_db_fetch_array($pending_points_query)) {
	    $orders_status_query = tep_db_query("select o.orders_id, o.orders_status, s.orders_status_name_1 from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = '" . (int)$pending_points['orders_id'] . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "'");
	    $orders_status = tep_db_fetch_array($orders_status_query);
	    
	    if ($pending_points['points_status'] == '1') $points_status_name = TEXT_POINTS_PENDING;
	    if ($pending_points['points_status'] == '2') $points_status_name = TEXT_POINTS_CONFIRMED;
	    if ($pending_points['points_status'] == '3') $points_status_name = '<span class="pointWarning">' . TEXT_POINTS_CANCELLED . '</span>';
	    if ($pending_points['points_status'] == '4') $points_status_name = '<span class="pointWarning">' . TEXT_POINTS_REDEEMED . '</span>';
	    
	    if ($orders_status['orders_status'] == 2 && $pending_points['points_status'] == 1 || $orders_status['orders_status'] == 3 && $pending_points['points_status'] == 1) {
		    $points_status_name = TEXT_POINTS_PROCESSING;
	    }
		
	    if (tep_not_null($pending_points['points_comment']) && defined($pending_points['points_comment'])) {
		    $pending_points['points_comment'] = constant($pending_points['points_comment']);
	    }
	    
		/*
	    if (($pending_points['points_type'] == 'SP') && ($pending_points['points_comment'] == 'TEXT_DEFAULT_COMMENT')) {
		    $pending_points['points_comment'] = TEXT_DEFAULT_COMMENT;
	    }
		if (($pending_points['points_type'] == 'TP') && ($pending_points['points_comment'] == 'USE_POINTS_EVDAY_FOR_TOP_TRAVEL_COMPANION_TEXT')) {
			$pending_points['points_comment'] = USE_POINTS_EVDAY_FOR_TOP_TRAVEL_COMPANION_TEXT;
		}
		
		if ($pending_points['points_comment'] == 'TEXT_DEFAULT_REDEEMED') {
			$pending_points['points_comment'] = TEXT_DEFAULT_REDEEMED;
		}
		if ($pending_points['points_comment'] == 'TEXT_WELCOME_POINTS_COMMENT') {
			$pending_points['points_comment'] = TEXT_WELCOME_POINTS_COMMENT;
		}
		if ($pending_points['points_comment'] == 'TEXT_VALIDATION_ACCOUNT_POINT_COMMENT') {
			$pending_points['points_comment'] = TEXT_VALIDATION_ACCOUNT_POINT_COMMENT;
		}		
		if ($pending_points['points_comment'] == 'TEXT_DEFAULT_REVIEWS_PHOTOS') {
			$pending_points['points_comment'] = TEXT_DEFAULT_REVIEWS_PHOTOS;
		}
		if ($pending_points['points_comment'] == 'TEXT_DEFAULT_FEEDBACK_APPROVAL') {
			$pending_points['points_comment'] = TEXT_DEFAULT_FEEDBACK_APPROVAL;
		}
		if ($pending_points['points_comment'] == 'TEXT_DEFAULT_ANSWER') {
			$pending_points['points_comment'] = TEXT_DEFAULT_ANSWER;
		}
		//会员积分卡 begin
		if ($pending_points['points_comment'] == 'TEXT_POINTCARD_REGISTER') {
			$pending_points['points_comment'] = TEXT_POINTCARD_REGISTER;
		}
		if ($pending_points['points_comment'] == 'TEXT_POINTCARD_PROFILE') {
			$pending_points['points_comment'] = TEXT_POINTCARD_PROFILE;
		}
		if ($pending_points['points_comment'] == 'TEXT_POINTCARD_LOGIN') {
			$pending_points['points_comment'] = TEXT_POINTCARD_LOGIN;
		}
		//会员积分卡 end
		*/
		$referred_customers_name = '';
		if ($pending_points['points_type'] == 'RF') {
			$referred_name_query = tep_db_query("select customers_name from " . TABLE_ORDERS . " where orders_id = '" . (int)$pending_points['orders_id'] . "' limit 1");
			$referred_name = tep_db_fetch_array($referred_name_query);
			/*if ($pending_points['points_comment'] == 'TEXT_DEFAULT_REFERRAL') {
				$pending_points['points_comment'] = TEXT_DEFAULT_REFERRAL;
			}*/
			$referred_customers_name = $referred_name['customers_name'];
		}
	/*
	if (($pending_points['points_type'] == 'RV') && ($pending_points['points_comment'] == 'TEXT_DEFAULT_REVIEWS')) {
		$pending_points['points_comment'] = TEXT_DEFAULT_REVIEWS;
	}
	
	if(($pending_points['points_type'] == 'VT') && ($pending_points['points_comment'] == 'TEXT_VOTE_POINTS_COMMENT')){
		$pending_points['points_comment'] = TEXT_VOTE_POINTS_COMMENT;
	}
	*/
	if (($pending_points['orders_id'] > '0') && (($pending_points['points_type'] == 'SP')||($pending_points['points_type'] == 'RD'))) {
?>
        <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href='<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $pending_points['orders_id'], 'SSL'); ?>'" title="<?php echo TEXT_ORDER_HISTORY .'&nbsp;' . $pending_points['orders_id']; ?>">
<?php
	}
	
	if ($pending_points['points_type'] == 'RV') {
?>
        <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href='<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $pending_points['products_id'].'&mnu=reviews', 'NONSSL'); ?>'" title="<?php echo TEXT_REVIEW_HISTORY; ?>">
<?php
	}
	
	if ($pending_points['points_type'] == 'PH') {
?>
        <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href='<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $pending_points['products_id'].'&mnu=photos', 'NONSSL'); ?>'" title="<?php echo TEXT_REVIEW_HISTORY; ?>">
<?php
	}
	if ($pending_points['points_type'] == 'FA') {
?>
        <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="window.open('<?php echo $pending_points['feedback_other_site_url']; ?>');" title="">
<?php
	}
	if ($pending_points['points_type'] == 'QA') {
        $get_products_id = tep_db_query("select products_id from ".TABLE_QUESTION." where que_id='".$pending_points['orders_id']."'");
		$row_products_id = tep_db_fetch_array($get_products_id);
?>

		<tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href='<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $row_products_id['products_id'].'&mnu=qanda', 'NONSSL'); ?>'" title="<?php echo TEXT_REVIEW_HISTORY; ?>">
<?php
	}
	
	
	if (($pending_points['orders_id'] == 0) || ($pending_points['points_type'] == 'RF') || ($pending_points['points_type'] == 'RV') || ($pending_points['points_type'] == 'PH') || ($pending_points['points_type'] == 'QA')) {
		$orders_status['orders_status_name_1'] = '<span class="pointWarning">' . TEXT_STATUS_ADMINISTATION . '</span>';
		$pending_points['orders_id'] = '<span class="pointWarning">' . TEXT_ORDER_ADMINISTATION . '</span>';
	}
?>
                  <td align="center" class="productListing-data"><?php echo tep_date_short($pending_points['date_added']); ?></td>
                  <td align="center" nowrap="nowrap" class="productListing-data"><?php echo '#' . $pending_points['orders_id'] . '&nbsp;&nbsp;' . db_to_html($orders_status['orders_status_name_1']); ?></td>
                  <td align="center" nowrap="nowrap" class="productListing-data">
				  	<?php 
						if($pending_points['admin_id']!=0) {
							echo db_to_html(nl2br($pending_points['points_comment'])) .'&nbsp;' . db_to_html($referred_customers_name); 
						}else{
							echo nl2br($pending_points['points_comment']) .'&nbsp;' . db_to_html($referred_customers_name); 
						}
						
					?>				  </td>
                  <td align="center" class="productListing-data"><?php echo  $points_status_name; ?></td>
                  <td align="center" class="productListing-data"><?php echo number_format($pending_points['points_pending'],POINTS_DECIMAL_PLACES); ?></td>
                </tr>
<?php
	}
?>

          </table></div>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText" valign="top"><?php echo $pending_points_split->display_count(TEXT_DISPLAY_NUMBER_OF_RECORDS); ?></td>
            <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $pending_points_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
	  <!--<tr>
	  	<Td class="main" style="padding-top:10px;"><?php echo TEXT_INTRO_STRING_BOTTOM; ?></Td>
	  </tr>-->

    </table></td>
<!-- body_text_eof //-->
   
  </tr>
</table>
<!-- body_eof //-->

<?php echo tep_get_design_body_footer();?>