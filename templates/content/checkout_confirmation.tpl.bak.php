<?php echo tep_get_design_body_header(HEADING_TITLE); ?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>


<tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
           <tr>
			<td class="main"><?php echo db_to_html('<b>订单资讯</b> '); ?></td>
		  </tr>
        </table></td>
      </tr>

      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
<?php
  if ($sendto != false) {
?>
            <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main">
				<!--出货地址<?php echo '<b>' . HEADING_DELIVERY_ADDRESS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?>-->
				<?php echo db_to_html('<b>电子参团凭证递送地址</b>'); ?>
				</td>
              </tr>
              <tr>
                <td class="main">
				<!--出货地址<?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br />'); ?>-->
				<?php echo db_to_html($order->customer['email_address']); ?>
				</td>
              </tr>
<?php
    if ($order->info['shipping_method'] && true == false) {	//出货方式 已取消
?>
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_SHIPPING_METHOD . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo db_to_html($order->info['shipping_method']); ?></td>
              </tr>
<?php
    }
?>
            </table></td>
<?php
  }
?>
            <td width="<?php echo (($sendto != false) ? '70%' : '100%'); ?>" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (sizeof($order->info['tax_groups']) > 1) {
?>
                  <tr>
                    <td class="main" colspan="2"><?php echo '<b>' . HEADING_PRODUCTS . '</b> <a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
                    <td class="smallText" align="right"><b><?php echo HEADING_TAX; ?></b></td>
                    <td class="smallText" align="right"><b><?php echo HEADING_TOTAL; ?></b></td>
                  </tr>
<?php
  } else {
?>
                  <tr>
                    <td class="main" colspan="3"><?php echo '<b>' . HEADING_PRODUCTS . '</b> <a href="' . tep_href_link(FILENAME_SHOPPING_CART) . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
                  </tr>
<?php
  }

  for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
    echo db_to_html('          <tr>' . "\n" .
         '            <td class="main" align="right" valign="top" width="30">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
         '            <td class="main" valign="top">' . $order->products[$i]['name']);

    if (STOCK_CHECK == 'true') {
      echo tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
    }
/*
    if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
      for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
        echo '<br /><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . ' ' . $order->products[$i]['attributes'][$j]['prefix'] . ' ' . $currencies->display_price($order->products[$i]['attributes'][$j]['price'], tep_get_tax_rate($products[$i]['tax_class_id']), 1)  . '</span></nobr>';
      }
    }
	  
	  echo  '<br /><span> - ' . $order->products[$i]['dateattributes'][0] . ' ' . $order->products[$i]['dateattributes'][3] . (($order->products[$i]['dateattributes'][4]!='') ? '$' : ''). $order->products[$i]['dateattributes'][4]. '</span>';
     if($order->products[$i]['dateattributes'][1]!='')
     echo  ' <br /><span> - ' . $order->products[$i]['dateattributes'][1].tep_draw_hidden_field('depart_timeone[]', $order->products[$i]['dateattributes'][1], 'size="4"')  . ' ' . $order->products[$i]['dateattributes'][2].tep_draw_hidden_field('depart_locationone[]', $order->products[$i]['dateattributes'][2], 'size="4"') . '</span>';
 	  if($order->products[$i]['roomattributes'][1]!='')
     echo  ' <nobr><span>' . $order->products[$i]['roomattributes'][1].tep_draw_hidden_field('roominfo[]', $order->products[$i]['roomattributes'][1], 'size="4"')  . ' ' . tep_draw_hidden_field('roomprice[]', $order->products[$i]['roomattributes'][0], 'size="4"') . '</span>';
*/
	echo  '<br /><span id="Departure_Date"> '.TEXT_SHOPPING_CART_DEPARTURE_DATE.' ' . tep_get_date_disp($order->products[$i]['dateattributes'][0]) . ' ' . $order->products[$i]['dateattributes'][3] . (($order->products[$i]['dateattributes'][4]!='') ? $currencies->format($order->products[$i]['dateattributes'][4]) : ''). '</span>';
		 if($order->products[$i]['dateattributes'][1]!='')
		 echo  ' <br /><span> '.TEXT_SHOPPING_CART_PICKP_LOCATION.' ' . $order->products[$i]['dateattributes'][1].tep_draw_hidden_field('depart_timeone[]', $order->products[$i]['dateattributes'][1], 'size="4"')  . ' ' . $order->products[$i]['dateattributes'][2].tep_draw_hidden_field('depart_locationone[]', $order->products[$i]['dateattributes'][2], 'size="4"') . '</span>';
		  if($order->products[$i]['roomattributes'][1]!='')
		 echo  db_to_html(' <span>' . $order->products[$i]['roomattributes'][1].tep_draw_hidden_field('roominfo[]', $order->products[$i]['roomattributes'][1], 'size="4"')  . ' ' . tep_draw_hidden_field('roomprice[]', $order->products[$i]['roomattributes'][0], 'size="4"') . '</span>');
	
		if ( (isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0) ) {
		  for ($j=0, $n2=sizeof($order->products[$i]['attributes']); $j<$n2; $j++) {
			if($order->products[$i]['attributes'][$j]['price'] > 0) {
				echo db_to_html('<br /><span> ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . ': ' . $order->products[$i]['attributes'][$j]['prefix'] . ' ' . $currencies->display_price($order->products[$i]['attributes'][$j]['price'], tep_get_tax_rate($products[$i]['tax_class_id']), 1)  . '</span>');
			}else{
				echo db_to_html('<br /><span> ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</span>');
			}
		  }
		}

    echo '</td>' . "\n";

    if (sizeof($order->info['tax_groups']) > 1) echo '            <td class="main" valign="top" align="right">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";

    echo '            <td class="main" align="right" valign="top">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . '</td>' . "\n" .
         '          </tr>' . "\n";
  }
?>
                </table></td>
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
			<td class="main"><?php echo '<b>' . TEXT_GUEST_INFO_FLIGHT_INFO . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
		  </tr>
        </table></td>
      </tr>
	  <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0"  width="100%" align='center' cellspacing="0" cellpadding="2">
              
			  	<?php 
				for ($i=0, $n=sizeof($order->products); $i<$n; $i++) 
				{
					echo db_to_html('<tr>
					<td colspan="4" class="main"><b>'.$order->products[$i]['name'].'</b>
					</td>
					</tr>');
					
					//amit added to check helicopter tour 
					if(tep_get_product_type_of_product_id((int)$order->products[$i]['id']) == 2){
					
						if($order->products[$i]['roomattributes'][2] != '')
							{
								$m=$order->products[$i]['roomattributes'][2];
								for($h=0; $h<$m; $h++)
								{
									 if(($h%2)==0)
									 echo '<tr>';
								?>								
									<td class="main" width="20%">
									<?php echo TEXT_INFO_GUEST_NAME;?> <?php echo ($h+1).'). '?><br />
									<?php echo db_to_html("顾客护照英文名 ").($h+1).")." ?>
									<br />
									<?php echo TEXT_INFO_GUEST_BODY_WEIGHT;?> <?php echo ($h+1).'). '?>
									<?php
									if(isset($_SESSION['guestchildage'.$h][$i]) && $_SESSION['guestchildage'.$h][$i] != ''){
									echo "<br />".db_to_html('出生日期 ').($h+1).").";
									}
									?>
									</td>
									<td class="main" width="30%">
									<?php echo $_SESSION['guestname'.$h][$i].' '.$_SESSION['guestsurname'.$h][$i].tep_draw_hidden_field('guestname'.$h, $_SESSION['guestname'.$h][$i].''.$_SESSION['guestsurname'.$h][$i], ''); ?>
									<br />
									<?php echo $_SESSION['GuestEngName'.$h][$i].tep_draw_hidden_field('GuestEngName'.$h, $_SESSION['GuestEngName'.$h][$i], ''); ?>
									<br />
									<?php echo $_SESSION['guestbodyweight'.$h][$i].tep_draw_hidden_field('guestbodyweight'.$h, $_SESSION['guestbodyweight'.$h][$i], ''); ?>
									<?php
									if(isset($_SESSION['guestchildage'.$h][$i]) && $_SESSION['guestchildage'.$h][$i] != ''){										
										echo ' <br />'.$_SESSION['guestchildage'.$h][$i];																					
									}
									?>
									</td>
									
								<?php
									if(($h%2)!=0)
									echo '</tr>';
								}// end of for($h=0; $h<$m; $h++)
							}
					
					}else{
					
								if($order->products[$i]['roomattributes'][2] != '')
								{
									$m=$order->products[$i]['roomattributes'][2];
									for($h=0; $h<$m; $h++)
									{
										 if(($h%2)==0)
										 echo '<tr>';
									?>
									
										<td class="main" width="20%">
										<?php echo TEXT_INFO_GUEST_NAME;?> <?php echo ($h+1).'). '?><br />
										<?php echo db_to_html("顾客护照英文名 ").($h+1).")." ?>
										<?php
										if(isset($_SESSION['guestchildage'.$h][$i]) && $_SESSION['guestchildage'.$h][$i] != ''){
										echo "<br />".db_to_html('出生日期 ').($h+1).").";
										}
										?>
										</td>
										<td class="main" width="30%">
										<?php echo $_SESSION['guestname'.$h][$i].' '.$_SESSION['guestsurname'.$h][$i].tep_draw_hidden_field('guestname'.$h, $_SESSION['guestname'.$h][$i].' '.$_SESSION['guestsurname'.$h][$i], ''); ?><br />
										<?php echo $_SESSION['GuestEngName'.$h][$i].tep_draw_hidden_field('GuestEngName'.$h, $_SESSION['GuestEngName'.$h][$i], ''); ?>
										<?php
										if(isset($_SESSION['guestchildage'.$h][$i]) && $_SESSION['guestchildage'.$h][$i] != ''){										
											echo ' <br />'.$_SESSION['guestchildage'.$h][$i];																					
										}
										?>
										</td>
										
									<?php
										if(($h%2)!=0)
										echo '</tr>';
									}// end of for($h=0; $h<$m; $h++)
								}
					
					}
					
					echo ' <tr> <td colspan="4" class="main">&nbsp;</td></tr>
					<tr> <td colspan="4" class="main">';
					?>
					<table border="0" width="100%" cellspacing="0" cellpadding="2">
					  <tr>
						<td class="main" width="20%"><?php echo TEXT_ARRIVAL_AIRLINE_NAME;?>:</td>
						<td class="main" width="30%"><?php echo nl2br(tep_output_string_protected($order->info['airline_name'][$i])) . tep_draw_hidden_field('airline_name['.$i.']', $order->info['airline_name'][$i]); ?></td>
						<td class="main" width="20%"><?php echo TEXT_DEPARTURE_AIRLINE_NAME;?>:</td>
						<td class="main" width="30%"><?php echo nl2br(tep_output_string_protected($order->info['airline_name_departure'][$i])) . tep_draw_hidden_field('airline_name_departure['.$i.']', $order->info['airline_name_departure'][$i]); ?></td>
					  </tr>
					  <tr>
						<td class="main"><?php echo TEXT_ARRIVAL_FLIGHT_NUMBER;?>:</td>
						<td class="main"><?php echo nl2br(tep_output_string_protected($order->info['flight_no'][$i])) . tep_draw_hidden_field('flight_no['.$i.']', $order->info['flight_no'][$i]);?></td>
						<td class="main"><?php echo TEXT_DEPARTURE_FLIGHT_NUMBER;?>:</td>
						<td class="main"><?php echo nl2br(tep_output_string_protected($order->info['flight_no_departure'][$i])) . tep_draw_hidden_field('flight_no_departure['.$i.']', $order->info['flight_no_departure'][$i]);?></td>
					  </tr>
					  <tr>
						<td class="main"><?php echo TEXT_ARRIVAL_AIRPORT_NAME;?>:</td>
						<td class="main"><?php echo nl2br(tep_output_string_protected($order->info['airport_name'][$i])) . tep_draw_hidden_field('airport_name['.$i.']', $order->info['airport_name'][$i]);?></td>
						<td class="main"><?php echo TEXT_DEPARTURE_AIRPORT_NAME;?>:</td>
						<td class="main"><?php echo nl2br(tep_output_string_protected($order->info['airport_name_departure'][$i])) . tep_draw_hidden_field('airport_name_departure['.$i.']', $order->info['airport_name_departure'][$i]);?></td>
					  </tr>
					  <tr>
						<td class="main"><?php echo TEXT_ARRIVAL_DATE;?>:</td>
						<td class="main"><?php echo nl2br(tep_output_string_protected($_POST['arrival_date'.$i])) . tep_draw_hidden_field('arrival_date'.$i, $_POST['arrival_date'.$i]);?></td>
						<td class="main"><?php echo TEXT_DEPARTURE_DATE;?>:</td>
						<td class="main"><?php echo nl2br(tep_output_string_protected($_POST['departure_date'.$i])) . tep_draw_hidden_field('departure_date'.$i, $_POST['departure_date'.$i]);?></td>
					 </tr>
					  <tr>
						<td class="main"><?php echo TEXT_ARRIVAL_TIME;?>:</td>
						<td class="main"><?php echo nl2br(tep_output_string_protected($order->info['arrival_time'][$i])) . tep_draw_hidden_field('arrival_time['.$i.']', $order->info['arrival_time'][$i]);?></td>
						<td class="main"><?php echo TEXT_DEPARTURE_TIME;?>:</td>
						<td class="main"><?php echo nl2br(tep_output_string_protected($order->info['departure_time'][$i])) . tep_draw_hidden_field('departure_time['.$i.']', $order->info['departure_time'][$i]);?></td>
					  </tr>
					  
					</table>
					<?php
					echo '</td></tr> <tr> <td colspan="4" class="main"><hr style="color:#108BCE;" size="1" /></td></tr>';
				}
				?>
				
			  
            </table>
			 <table border="0"  width="50%" cellspacing="0" cellpadding="2">
																		<tr><td colspan="2" class="main"></td></tr>
																		<tr><td colspan="2" width="60%" class="main"><b><?php echo TEXT_EMERGENCY_CONTACT_NUM;?></b></td><td><?php echo tep_customers_cellphone($customer_id);?></td></tr>
																		<tr><td class="main" height="10"></td></tr>
															 </table>
			</td>
          </tr>
        </table></td>
      </tr>
	   <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
	  
	  
      <tr>
        <td class="main"><b><?php echo HEADING_BILLING_INFORMATION; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td width="30%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_BILLING_ADDRESS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo db_to_html(tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br />')); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo '<b>' . HEADING_PAYMENT_METHOD . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo db_to_html($order->info['payment_method']); ?></td>
              </tr>
            </table></td>
            <td width="70%" valign="top" align="right"><table border="0" cellspacing="0" cellpadding="2">
<?php
  if (MODULE_ORDER_TOTAL_INSTALLED) {
    $order_total_modules->process();
    echo $order_total_modules->output();
	//echo 'order of zhh';
  }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>

<?php
// BOF: Lango modified for print order mod
  if (is_array($payment_modules->modules)) {
    if ($confirmation = $payment_modules->confirmation()) {
      $payment_info = $confirmation['title'];
      if (!tep_session_is_registered('payment_info')) tep_session_register('payment_info');
// EOF: Lango modified for print order mod
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo HEADING_PAYMENT_INFORMATION; ?></b></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" colspan="4"><?php echo $confirmation['title']; ?></td>
              </tr>
<?php
      for ($i=0, $n=sizeof($confirmation['fields']); $i<$n; $i++) {
?>
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><?php echo $confirmation['fields'][$i]['title']; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main"><?php echo $confirmation['fields'][$i]['field']; ?></td>
              </tr>
<?php
      }
?>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
    }
  }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
  if (tep_not_null($order->info['comments'])) {
?>
      <tr>
        <td class="main"><?php echo '<b>' . HEADING_ORDER_COMMENTS . '</b> <a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '"><span class="orderEdit">(' . TEXT_EDIT . ')</span></a>'; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo nl2br(tep_output_string_protected($order->info['comments'])) . tep_draw_hidden_field('comments', $order->info['comments']); ?></td>
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

<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
	 <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo HEADING_SHIPPING_INFORMATION ; ?></b></td>
      </tr>
	 
	 <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
	  
	<tr>
        <td>
		<table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td>
			  <table border="0" width="100%" cellspacing="0" cellpadding="2">
				<tr>
				<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
				<td class="main" colspan="2">
				
						<table border="0"  cellspacing="1" cellpadding="3" width="655">
						<tr>
						  <td align="left" class="main"><b><?php echo TEXT_SHIPPING_METHOD;?> <?php echo TEXT_SIMPLE_DIS_EMAIL;?></b>
						  <table align="center" border="0" cellspacing="1" cellpadding="2" width="95%">
						  <tr>
							<td width="35%" align="right" class="main"><?php echo TEXT_DIS_CUSTIMER;?></td>
							<td width="65%" align="left" class="main"><?php 
							
							if (isset($order->billing['firstname']) && tep_not_null($order->billing['firstname'])) {
							  $firstname = tep_output_string_protected($order->billing['firstname']);
							  $lastname = tep_output_string_protected($order->billing['lastname']);
							} elseif (isset($order->billing['name']) && tep_not_null($order->billing['name'])) {
							  $firstname = tep_output_string_protected($order->billing['name']);
							  $lastname = '';
							} else {
							  $firstname = '';
							  $lastname = '';
							}
							
							echo db_to_html($firstname.' '.$lastname);

							?></td>
						  </tr>
						  <tr>
							<td align="right" class="main"><?php echo TEXT_DIS_EMAIL;?></td>
							<td align="left" class="main"><font color="red"><b><?php echo db_to_html($order->customer['email_address']); ?></b></font></td>
						  </tr>
						  <tr>
							<td colspan="2" align="center" class="main"><?php echo TEXT_DIGITAL_PRODUCTS;?></td>
						  </tr>
						  </table>
						  </td>
						</tr>
						</table>
					</td>
				<td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
				</tr>
				</table>
            </td>
          </tr>
        </table>
		</td>
      </tr>

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td>
<?php
  if (isset($$payment->form_action_url)) {
    $form_action_url = $$payment->form_action_url;
  } else {
    $form_action_url = tep_href_link(FILENAME_CHECKOUT_PROCESS, '', 'SSL');
  }

  echo tep_draw_form('checkout_confirmation', $form_action_url, 'post','id="checkout_confirmation"'); //onsubmit="return checkCheckBox(this)"

  if (is_array($payment_modules->modules)) {
    echo $payment_modules->process_button();
  }
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (ACCOUNT_CONDITIONS_REQUIRED == 'true') {
?>
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" colspan="2"><?php echo CONDITION_AGREEMENT; ?> <input type="checkbox" class="check-required" value="0" name="agree" id="agree" /></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
<?php
}
?>
		
		
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td class="main" align="right">
				<?php //echo '<b>'.TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br />' . TEXT_CONTINUE_CHECKOUT_PROCEDURE; ?>
				<span id="submit_msn" style="color:#FF6600; display:<?php echo 'none';?>"><?php echo db_to_html("正在提交订单，这个过程需要花费您的一些时间，请耐心等候...");?></span>
				</td>
                <td class="main" align="right"><?php  echo tep_template_image_submit('button_confirm_order.gif', IMAGE_BUTTON_CONFIRM_ORDER) . "\n";?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table>
			<?php 
															
if(isset($_POST['gv_redeem_code_royal_customer_reward']) && $_POST['gv_redeem_code_royal_customer_reward']!='')
{
	echo tep_draw_hidden_field('gv_redeem_code_royal_customer_reward', $_POST['gv_redeem_code_royal_customer_reward']);
}
										?>
			</form>
            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
    			  <tr>
														<td><table border="0" width="100%" cellspacing="0" cellpadding="0">
														  <tr>
														
															<td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
															  <tr>
																<td width="50%" align="right"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
																<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
															  </tr>
															</table></td>
															  <?php /* 
															<td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
															*/ ?>
															<td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
															  <tr>
																<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
																<td><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
																<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
															  </tr>
															</table></td>
															<td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
															  <tr>
																<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
																<td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
															  </tr>
															</table></td>
														  </tr>
														  <tr>
															<?php /* <td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_DELIVERY . '</a>'; ?></td>*/?>
															<td align="center" width="25%" class="checkoutBarFrom"><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_PAYMENT, '', 'SSL') . '" class="checkoutBarFrom">' . CHECKOUT_BAR_PAYMENT . '</a>'; ?></td>
															<td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
															<td align="center" width="25%" class="checkoutBarTo"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
														  </tr>
														</table></td>
													  </tr>
													</table>
													<script type="text/javascript">
			function formCallback(result, form) {
				window.status = "valiation callback for form '" + form.id + "': result = " + result;
			}
			
			var valid = new Validation('checkout_confirmation', {immediate : true,useTitles:true, onFormValidate : formCallback});
			
			Validation.add('check-required', '<?php echo CONDITION_AGREEMENT_WARNING; ?>', function(v,elm) { 
				return (elm.checked == true);
			});
			
		</script>
		

<?php echo tep_get_design_body_footer();?>
