<?php /*echo tep_get_design_body_header(HEADING_TITLE,1);*/ ?>
					 <!-- content main body start -->
					 		<table width="100%"  border="0" cellspacing="0" cellpadding="0">


							  <tr>
								<td align="left" class="main">

	 <table border="0" width="99%" align="center" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">


<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>

							<?php /*
							<tr>
								<td>
									<table  border="0" cellspacing="0" cellpadding="0">
									<tr>
									<td ><img src="image/p2.gif" alt="" title=""></td><td>&nbsp;<?php echo TEXT_HEADING_RED_REPEAT_CUSTOMERS_NOTES; ?></td>
									</tr>
									</table>
								</td>
							</tr>
							*/?>
      <tr>
        <td>
<?php
  $orders_total = tep_count_customer_orders();

  if ($orders_total > 0) {
    $history_query_raw = "select o.orders_id, o.date_purchased, o.delivery_name, o.billing_name, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_subtotal' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' order by orders_id DESC";
    //echo $history_query_raw;
    $history_split = new splitPageResults($history_query_raw, MAX_DISPLAY_ORDER_HISTORY);
    $history_query = tep_db_query($history_split->sql_query);

    while ($history = tep_db_fetch_array($history_query)) {
      $products_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS_PRODUCTS . " where orders_id = '" . (int)$history['orders_id'] . "'");
      $products = tep_db_fetch_array($products_query);

      if (tep_not_null($history['delivery_name'])) {
        $order_type = TEXT_ORDER_SHIPPED_TO;
        $order_name = db_to_html($history['delivery_name']);
      } else {
        $order_type = TEXT_ORDER_BILLED_TO;
        $order_name = db_to_html($history['billing_name']);
      }
?>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td class="main"><?php echo '<b>' . TEXT_ORDER_NUMBER . '</b> ' . $history['orders_id']; ?></td>
              <td class="main" align="right">
		<?php

		 $result_echo_ss=tep_get_orders_status_name($history['orders_id']);
		 //echo $result_echo_ss;
		 echo '<b>' . TEXT_ORDER_STATUS . '</b> ' .db_to_html($result_echo_ss);


		?>
			  </td>
            </tr>
          </table>
          <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
            <tr class="infoBoxContents">
              <td><table border="0" width="100%" cellspacing="2" cellpadding="4">
                <tr>
                  <td class="main" width="50%" valign="top">
	<?php
				  echo '<b>' . TEXT_ORDER_DATE . '</b> ' . tep_date_long($history['date_purchased']) . '<br><b>' . $order_type . '</b> ' . tep_output_string_protected($order_name); ?></td>
<?php
$loyal_discount_query = "select orders_id,text from ".TABLE_ORDERS_TOTAL." where orders_id='".$history['orders_id']."' and class='ot_customer_discount'";
$res_loyal_discount = tep_db_query($loyal_discount_query);
if(tep_db_num_rows($res_loyal_discount)>0)
{

$row_loyal_discount = tep_db_fetch_array($res_loyal_discount);

$final_cost_query = tep_db_query("select orders_id,text from ".TABLE_ORDERS_TOTAL." where orders_id='".$history['orders_id']."' and class='ot_total'");
$row_final_cost = tep_db_fetch_array($final_cost_query);
?>
																				  <td class="main" width="35%" valign="top" nowrap="nowrap"><?php echo '<b>' . TEXT_ORDER_PRODUCTS . '</b> ' . $products['count'] . '<br><b>' . TEXT_ORDER_COST . '</b> ' . strip_tags($history['order_total']).'<br /><b>'.SUB_TITLE_LOYAL_CUSTOMER_DISCOUNT.' </b><span class="productSpecialPrice">'.strip_tags($row_loyal_discount['text']).'</span><br /><b>'.TEXT_ORDER_FINAL_COST.' </b><span class="sp3_no_decoration">'.strip_tags($row_final_cost['text']).'</span>'; ?></td>

<?php
}
else
{
?>
																				  <td class="main" width="35%" valign="top"><?php echo '<b>' . TEXT_ORDER_PRODUCTS . '</b> ' . $products['count'] . '<br><b>' . TEXT_ORDER_COST . '</b> ' . strip_tags($history['order_total']); ?></td>

<?php
}
?>

                  <td class="main" width="20%"><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, (isset($HTTP_GET_VARS['page']) ? 'page=' . $HTTP_GET_VARS['page'] . '&' : '') . 'order_id=' . $history['orders_id'], 'SSL') . '">' . tep_template_image_button('small_view.gif', SMALL_IMAGE_BUTTON_VIEW) . '</a>'; ?></td>
                </tr>
              </table></td>
            </tr>
          </table>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
          </table>
<?php
    }
  } else {
?>
          <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
            <tr class="infoBoxContents">
              <td><table border="0" width="100%" cellspacing="2" cellpadding="4">
                <tr>
                  <td class="main"><?php echo TEXT_NO_PURCHASES; ?></td>
                </tr>
              </table></td>
            </tr>
          </table>
<?php
  }
?>
        </td>
      </tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
<?php
  if ($orders_total > 0) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="smallText" valign="top"><?php echo $history_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
            <td class="smallText" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $history_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td><?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . tep_template_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>


								</td>
							  </tr>
							  <tr>
								<td height="15"></td>
							  </tr>


							</table><!-- content main body end -->
<?php //echo tep_get_design_body_footer();?>
