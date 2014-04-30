<?php /*echo tep_get_design_body_header(HEADING_TITLE,1);*/ ?>
					 <!-- content main body start -->
					 		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
														
							  <tr>
								<td class="main">
											<table border="0" width="100%" align="center" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
												
												<?php
												// BOF: Lango Added for template MOD
												if (MAIN_TABLE_BORDER == 'yes'){
												table_image_border_top(false, false, $header_text);
												}
												// EOF: Lango Added for template MOD
												?>
												<?php
												  if ($messageStack->size('account') > 0) {
												?>
													  <tr>
														<td><?php echo $messageStack->output('account'); ?></td>
													  </tr>
													  <tr>
														<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
													  </tr>
												<?php
												  }
												
												  if (tep_count_customer_orders() > 0) {
												?>
													  <tr>
														<td><table border="0" cellspacing="0" cellpadding="2">
														  <tr>
															<td class="main"><b><?php echo OVERVIEW_TITLE; ?> <?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '" class="sp1">' . OVERVIEW_SHOW_ALL_ORDERS . '</a>'; ?></b><br/><?php echo TEXT_RESERVATION_NOTES_ACCOUNT;?>
															</td>
														  </tr>
														</table></td>
													  </tr>
													  <tr>
														<td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
														  <tr class="infoBoxContents">
															<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
															  <tr>
																<td class="main" align="center" valign="top" width="25%"><?php echo '<b>' . OVERVIEW_PREVIOUS_ORDERS . '</b><br />' . tep_image(DIR_WS_IMAGES . 'account_previouse_reservation.gif'); ?></td>
																<td><table border="0" width="100%" cellspacing="0" cellpadding="3">
												<?php
													$orders_query = tep_db_query("select o.orders_id, o.date_purchased, o.delivery_name, o.delivery_country, o.billing_name, o.billing_country, ot.text as order_total, s.orders_status_name from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS_STATUS . " s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' order by orders_id desc limit 3");
													while ($orders = tep_db_fetch_array($orders_query)) {
													  if (tep_not_null($orders['delivery_name'])) {
														$order_name = db_to_html($orders['delivery_name']);
														$order_country = db_to_html($orders['delivery_country']);
													  } else {
														$order_name = db_to_html($orders['billing_name']);
														$order_country = db_to_html($orders['billing_country']);
													  }
												?>
																  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href='<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'], 'SSL'); ?>'">
																	<td class="main" width="80"><?php echo tep_date_short($orders['date_purchased']); ?></td>
																	<td class="main"><?php echo '#' . $orders['orders_id']; ?></td>
																	<td class="main"><?php echo tep_output_string_protected($order_name) . ', ' . $order_country; ?></td>
																	<td class="main"><?php echo db_to_html($orders['orders_status_name']); ?></td>
																	<td class="main" align="right"><span class="sp1"><?php echo $orders['order_total']; ?></span></td>
																	<td class="main" align="center">
																	<?php echo '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . $orders['orders_id'], 'SSL') . '">' . tep_template_image_button('small_view.gif', SMALL_IMAGE_BUTTON_VIEW) . '</a>'; ?></td>
																  </tr>
												<?php
													}
												?>
																</table></td>
																<td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
															  </tr>
															</table></td>
														  </tr>
														</table></td>
													  </tr>
													  <tr>
														<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
													  </tr>
												<?php
												  }
												?>
													  <tr>
														<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
														  <tr>
															<td class="main"><b><?php echo MY_ACCOUNT_TITLE; ?></b></td>
														  </tr>
														</table></td>
													  </tr>
													  <tr>
														<td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
														  <tr class="infoBoxContents">
															<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
															  <tr>
																<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
																<td width="60"><?php echo tep_image(DIR_WS_IMAGES . 'account_personal.gif'); ?></td>
																<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
																<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
																  <tr>
																	<td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . MY_ACCOUNT_INFORMATION . '</a>'; ?></td>
																  </tr>
																   <tr>
																   <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_AFFILIATE_ACCOUNT, '', 'SSL'). '">' . BOX_AFFILIATE_ACCOUNT_MAIN . '</a>'; ?></td>
																   </tr>
																  <tr>
																	<td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . MY_ACCOUNT_ADDRESS_BOOK . '</a>'; ?></td>
																  </tr>
																  <tr>
																	<td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL') . '">' . MY_ACCOUNT_PASSWORD . '</a>'; ?></td>
																  </tr>
																</table></td>
																<td width="10" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
															  </tr>
															</table></td>
														  </tr>
														</table></td>
													  </tr>
													  <tr>
														<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
													  </tr>
													  <tr>
														<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
														  <tr>
															<td class="main"><b><?php echo MY_ORDERS_TITLE; ?></b></td>
														  </tr>
														</table></td>
													  </tr>
													  <tr>
														<td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
														  <tr class="infoBoxContents">
															<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
															  <tr>
																<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
																<td width="60"><?php echo tep_image(DIR_WS_IMAGES . 'account_orders.gif'); ?></td>
																<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
																<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
																  <tr>
																	<td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . MY_ORDERS_VIEW . '</a>'; ?></td>
																  </tr>
																</table></td>
																<td width="10" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
															  </tr>
															</table></td>
														  </tr>
														</table></td>
													  </tr>
													  <tr>
														<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
													  </tr>
														 <!-- amit added to refer friend start  --> 
													  
													   <tr>
														<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
														  <tr>
															<td class="main"><b><?php echo MY_REFERRALS_TITLE; ?></b></td>
														  </tr>
														</table></td>
													  </tr>
													  <tr>
														<td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
														  <tr class="infoBoxContents">
															<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
															  <tr>
																<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
																<td width="60"><?php echo tep_image(DIR_WS_IMAGES . 'account_referrals.gif'); ?></td>
																<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
																<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
																	<tr><td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') .' <a href="' . tep_href_link(FILENAME_AFFILIATE_SUMMARY, '', 'SSL') . '">' . BOX_AFFILIATE_SUMMARY . '</a>'; ?></td></tr>
																	 <tr>  <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_REFER_A_FRIEND, '', 'SSL'). '">' . BOX_AFFILIATE_REFER_FRIENDS . '</a>'; ?></td></tr>
																	 <tr>  <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS, '', 'SSL'). '">' . BOX_AFFILIATE_BANNERS . '</a>'; ?></td></tr>
																	 <tr> <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_AFFILIATE_SALES, '', 'SSL'). '">' . BOX_AFFILIATE_SALES . '</a>'; ?></td></tr>
																	 <tr> <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, '', 'SSL'). '">' . BOX_AFFILIATE_PAYMENT . '</a>'; ?></td></tr>
																 <!--    <tr> <td class="main"><?php //echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_AFFILIATE_CLICKS, '', 'SSL'). '">' . BOX_AFFILIATE_CLICKRATE . '</a>'; ?></td></tr> -->
																	 <tr>  <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_AFFILIATE_CONTACT, '', 'SSL'). '">' . BOX_AFFILIATE_CONTACT . '</a>'; ?></td></tr>
																	 <tr>  <td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_AFFILIATE_FAQ, '', 'SSL'). '">' . BOX_AFFILIATE_FAQ . '</a>'; ?></td></tr>
																 <?php // echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_AFFILIATE_LOGOUT). '">' . BOX_AFFILIATE_LOGOUT . '</a>';
																	?> 
																</table></td>
																<td width="10" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
															  </tr>
															</table></td>
														  </tr>
														</table></td>
													  </tr>
													  <tr>
														<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
													  </tr>
													  
													  <!-- amit added to refer friend end  -->
													  <tr>
														<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
														  <tr>
															<td class="main"><b><?php echo EMAIL_NOTIFICATIONS_TITLE; ?></b></td>
														  </tr>
														</table></td>
													  </tr>
													  <tr>
														<td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
														  <tr class="infoBoxContents">
															<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
															  <tr>
																<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
																<td width="60"><?php echo tep_image(DIR_WS_IMAGES . 'account_notifications.gif'); ?></td>
																<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
																<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
																  <tr>
																	<td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL') . '">' . EMAIL_NOTIFICATIONS_NEWSLETTERS . '</a>'; ?></td>
																  </tr>
																  <tr>
																	<td class="main"><?php echo tep_image(DIR_WS_IMAGES . 'arrow_green.gif') . ' <a href="' . tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'SSL') . '">' . EMAIL_NOTIFICATIONS_PRODUCTS . '</a>'; ?></td>
																  </tr>
																</table></td>
																<td width="10" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
															  </tr>
															</table></td>
														  </tr>
														</table></td>
													  </tr>
												<?php
												// BOF: Lango Added for template MOD
												if (MAIN_TABLE_BORDER == 'yes'){
												table_image_border_bottom();
												}
												// EOF: Lango Added for template MOD
												?>
													</table>


							 </td>
							  </tr>
							  <tr>
								<td height="15"></td>
							  </tr>
							 
							
							</table><!-- content main body end -->
<?php echo tep_get_design_body_footer();?>
