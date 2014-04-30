<?php echo tep_get_design_body_header(HEADING_TITLE,1); ?>
					 <!-- content main body start -->
<!-- body //-->
<table border="0" width="100%" cellspacing="3" cellpadding="3">
  <tr>
    
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
	  	<td class="main"><b><?php echo SUB_TITLE ?></b></td>
	  </tr>
	  <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">            
			<tr>
				<td class="main"><?php echo TEXT_INTRO; ?></td>
			</tr>
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
            </tr>

<?php
  $has_point = tep_get_customer_credits_balance($customer_id);
  if ($has_point > 0) {
?>
              <td class="main"><?php echo sprintf(MY_CREDITS_CURRENT_BALANCE, number_format($has_point,2)); ?></td>
<?php
  } else {
         echo'<td class="main"><b>' . TEXT_NO_POINTS . '</b></td>';
  }
?>
            </tr>
          </table>
<?php
    $pending_points_query = "select unique_id, orders_id, products_id, credit_bal, date_added, credit_comment from " . TABLE_CUSTOMERS_CREDITS . " where customers_id = '" . (int)$customer_id . "' order by unique_id desc";
    $pending_points_split = new splitPageResults($pending_points_query, MAX_DISPLAY_POINTS_RECORD);
    $pending_points_query = tep_db_query($pending_points_split->sql_query);
	//$pending_points_query = tep_db_query($pending_points_query);

    if (tep_db_num_rows($pending_points_query)) {
?>
          <table border="0" width="100%" cellspacing="1" cellpadding="2" class="productListing-heading">
            <tr class="productListing-heading">
              <td class="productListing-heading"width="15%"><?php echo HEADING_ORDER_DATE; ?></td>
              <td class="productListing-heading"width="15%"><?php echo HEADING_ORDERS_NUMBER; ?></td>
			  <td class="productListing-heading" nowrap="nowrap" width="20%"><?php echo HEADING_POINTS_STATUS; ?></td>
              <td class="productListing-heading" width="25%"><?php echo HEADING_POINTS_COMMENT; ?></td>              
              <td class="productListing-heading" align="right" nowrap="nowrap" width="25%"><?php echo HEADING_POINTS_TOTAL; ?></td>
            </tr>
          </table>
          <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
            <tr class="infoBoxContents">
              <td><table border="0" width="100%" cellspacing="1" cellpadding="2">
                <tr>
<?php
    while ($pending_points = tep_db_fetch_array($pending_points_query)) {
	if ($pending_points['orders_id'] > 0 && $pending_points['credit_bal']<0) {
?>
        <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href='<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $pending_points['orders_id'], 'SSL'); ?>'" title="<?php echo TEXT_ORDER_HISTORY .'&nbsp;' . $pending_points['orders_id']; ?>">
<?php
	}else{
		?>
		<tr class="moduleRow">
		<?php
	}
?>
                  <td class="productListing-data"width="15%"><?php echo tep_date_short($pending_points['date_added']); ?></td>
                  <td class="productListing-data"width="15%"><?php echo '#' . $pending_points['orders_id']; ?></td>
                  <td class="productListing-data" width="20%"><?php echo  tep_get_products_model($pending_points['products_id']); ?></td>                    
				  <td class="productListing-data" width="25%"><?php echo  nl2br($pending_points['credit_comment']); ?></td>                    
                  
                  <td class="productListing-data" align="right" width="25%"><?php echo number_format($pending_points['credit_bal'],2); ?></td>                    
                </tr>
<?php
	}
?>
              </table></td>
            </tr>
          </table>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
          </table>
        </td>
      </tr>
      <?php  ?><tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText" valign="top"><?php echo $pending_points_split->display_count(TEXT_DISPLAY_NUMBER_OF_RECORDS); ?></td>
            <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $pending_points_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td><?php  ?>
      </tr>
<?php
  }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
	  <tr>
	  	<Td class="main"><?php echo TEXT_INTRO_REDEEM_POINTS; ?> <br><br>

<?php echo TEXT_INTRO_AFF_PROG; /* ?> <a href="#" class="sp3">Click here</a> to learn more. <?php */ ?>
</Td>
	  </tr>
	  <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="">
          <tr class="">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
		        <td><a href="javascript:history.go(-1)" class="btn"><span></span><?php echo "·µ»ØÉÏÒ»Ò³"?><?php # echo tep_image_button('button_back.gif', IMAGE_BUTTON_BACK); ?></a></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
   
  </tr>
</table>
<!-- body_eof //-->

<?php echo tep_get_design_body_footer();?>