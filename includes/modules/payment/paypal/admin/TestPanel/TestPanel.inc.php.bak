<?php
/*
  $Id: TestPanel.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/
?>
<form name="ipn" method="POST" action="<?php echo tep_catalog_href_link('ipn.php'); ?>">
<input type="hidden" name="business" value="<?php echo MODULE_PAYMENT_PAYPAL_BUSINESS_ID; ?>"/>
<input type="hidden" name="receiver_email" value="<?php echo MODULE_PAYMENT_PAYPAL_ID; ?>"/>
<input type="hidden" name="verify_sign" value="PAYPAL_SHOPPING_CART_IPN-TEST_TRANSACTION-00000000000000"/>
<input type="hidden" name="payment_date" value="<?php echo date("H:i:s M d, Y T"); ?>">
<input type="hidden" name="digestKey" value="<?php echo PayPal_IPN::digestKey(); ?>">
<table border="0" cellspacing="0" cellpadding="2" class="main">
<?php if (MODULE_PAYMENT_PAYPAL_IPN_TEST_MODE == 'Off') { ?>
  <tr>
    <td>
      <table border="0" cellspacing="0" cellpadding="0" style="padding: 4px; border:1px solid #aaaaaa; background: #ffffcc;">
        <tr>
          <td><?php echo $page->image('icon_error_40x40.gif','Error icon'); ?></td>
          <td><br class="text_spacer"></td>
          <td class="pperrorbold" style="text-align: center; width:100%;">Test Mode must be enabled!</td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td><br class="h10"></td>
  </tr>
<?php } ?>
  <tr>
    <td style="text-align: right;"><a href="<?php echo tep_href_link(FILENAME_PAYPAL,'action=itp&mode=advanced'); ?>">Advanced</a>&nbsp;&nbsp;&nbsp;<a href="#" onclick="openWindow('<?php echo tep_href_link('paypal.php','action=itp-help'); ?>');">Help with this page</a>&nbsp;<a href="#" onclick="openWindow('<?php echo tep_href_link('paypal.php','action=itp-help'); ?>');"><img src="<?php echo $page->imagePath('help.gif')?>" border="0" hspace="0" align="top"></a></td>
  </tr>
  <tr>
    <td>
      <table border="0" cellspacing="0" cellpadding="2" class="ppheaderborder" width="100%">
        <tr>
          <td align="center">
            <table border="0" cellspacing="0" cellpadding="3" class="testpanelinfo">
              <tr>
                <td class="pptextbold" nowrap>Primary PayPal Email Address</td>
                <td class="pptextbold" nowrap>Business ID</td>
                <td class="pptextbold" nowrap>Debug Email Address</td>
              </tr>
              <tr>
                <td nowrap><?php echo MODULE_PAYMENT_PAYPAL_ID; ?></td>
                <td nowrap><?php echo MODULE_PAYMENT_PAYPAL_BUSINESS_ID; ?></td>
                <td nowrap><?php echo MODULE_PAYMENT_PAYPAL_IPN_DEBUG_EMAIL; ?></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td><br class="h10"></td>
  </tr>
  <tr valign="top">
    <td>
      <table border="0" cellspacing="0" cellpadding="5" class="testpanel">
        <tr valign="top">
          <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr><td nowrap>First Name</td><td nowrap><input type="text" name="first_name" value="John"></td></tr>
              <tr><td nowrap>Last Name</td><td nowrap><input type="text" name="last_name" value="Doe"></td></tr>
              <tr><td nowrap>Business Name</td><td nowrap><input type="text" name="payer_business_name" value="ACME Inc."></td></tr>
              <tr><td nowrap>Email Address</td><td nowrap><input type="text" name="payer_email" value="root@localhost"></td></tr>
              <tr><td nowrap>Payer ID</td><td nowrap><input type="text" name="payer_id" value="PAYERID000000"></td></tr>
              <tr><td nowrap>Payer Status</td><td nowrap align="right"><select name="payer_status"><option value="verified">verified</option><option value="unverified">unverified</option></select></td></tr>
              <tr><td nowrap>Invoice</td><td nowrap><input type="text" name="invoice" value=""></td></tr>
            </table>
          </td>
          <td>
            <table border="0" cellspacing="0" cellpadding="2">
              <tr valign="top"><td nowrap>Address Name</td><td nowrap><input type="text" name="address_name" value="John Doe"></td></tr>
              <tr><td nowrap>Address Street</td><td nowrap><input type="text" name="address_street" value="1 Way Street"></td></tr>
              <tr><td nowrap>Address City</td><td><input type="text" name="address_city" value="NeverNever"></td></tr>
              <tr><td nowrap>Address State</td><td nowrap><input type="text" name="address_state" value="CA"></td></tr>
              <tr><td nowrap>Address Zip</td><td><input type="text" name="address_zip" value="12345"></td></tr>
              <tr><td nowrap>Address Country</td><td nowrap><input type="text" name="address_country" value="United States"></td></tr>
              <tr><td nowrap>Address Status</td><td nowrap align="right"><select name="address_status"><option value="confirmed">confirmed</option><option value="unconfirmed">unconfirmed</option></select></td></tr>
            </table>
          </td>
          <td>
            <table border="0" cellspacing="0" cellpadding="2">
              <tr><td nowrap>Payment Type</td><td nowrap align="right"><select name="payment_type"><option value="instant">instant</option><option value="echeck">echeck</option></select></td></tr>
              <tr><td nowrap>Transaction Type</td><td nowrap align="right"><select name="txn_type"><option value="">--select--</option><option value="cart">cart</option><option value="web_accept">web_accept</option><option value="send_money">send_money</option></select></td></tr>
              <tr><td nowrap>Custom</td><td nowrap><input type="text" name="custom" value="1" maxlength="32"></td></tr>
              <tr><td nowrap>Transaction ID</td><td nowrap><input type="text" name="txn_id" value="PAYPAL00000000000" maxlength="17"></td></tr>
              <tr><td nowrap>Parent Transaction ID</td><td nowrap><input type="text" name="parent_txn_id" value="" maxlength="17"></td></tr>
              <tr><td nowrap>No. Cart Items</td><td><input type="text" name="num_cart_items" value="1"></td></tr>
              <tr><td nowrap>Notify Version</td><td nowrap align="right"><select name="notify_version"><option value="1.6" selected>1.6</option></select></td></tr>
              <tr><td nowrap>Memo</td><td nowrap><input type="text" name="memo" value="PAYPAL_SHOPPING_CART_IPN TEST"></td></tr>
            </table>
          </td>
        </tr>
        <tr valign="top">
          <td>
            <table border="0" cellspacing="0" cellpadding="2">
              <tr><td nowrap>MC Currency</td><td align="right"><select name="mc_currency"><option value="USD">USD</option><option value="GBP">GBP</option><option value="EUR">EUR</option><option value="CAD">CAD</option><option value="JPY">JPY</option></select></td></tr>
              <tr><td nowrap>MC Gross</td><td align="right"><input type="text" name="mc_gross" value="0.01"></td></tr>
              <tr><td nowrap>MC Fee</td><td align="right"><input type="text" name="mc_fee" value="0.01"></td></tr>
            </table>
          </td>
          <td>
            <table border="0" cellspacing="0" cellpadding="2">
              <tr><td nowrap>Settle Amount</td><td align="right"><input type="text" name="settle_amount" value="0.00"></td></tr>
              <tr><td nowrap>Settle Currency</td><td align="right"><select name="settle_currency"><option value=""></option><option value="USD">USD</option><option value="GBP">GBP</option><option value="EUR">EUR</option><option value="CAD">CAD</option><option value="JPY">JPY</option></select></td></tr>
              <tr><td nowrap>Exchange Rate</td><td align="right"><input type="text" name="exchange_rate" value="0.00"></td></tr>
            </table>
          </td>
          <td>
            <table border="0" cellspacing="0" cellpadding="2">
              <tr><td nowrap>Payment Status</td><td align="right"><select name="payment_status"><option value="Completed">Completed</option><option value="Pending">Pending</option><option value="Failed">Failed</option><option value="Denied">Denied</option><option value="Refunded">Refunded</option><option value="Reversed">Reversed</option><option value="Canceled_Reversal">Canceled_Reversal</option></select></td></tr>
              <tr><td nowrap>Pending Reason</td><td align="right"><select name="pending_reason"><option value=""></option><option value="echeck">echeck</option><option value="multi_currency">multi_currency</option><option value="intl">intl</option><option value="verify">verify</option><option value="address">address</option><option value="upgrade">upgrade</option><option value="unilateral">unilateral</option><option value="other">other</option></select></td></tr>
              <tr><td nowrap>Reason Code</td><td align="right"><select name="reason_code"><option value=""></option><option value="chargeback">chargeback</option><option value="guarantee">guarantee</option><option value="buyer_complaint">buyer_complaint</option><option value="refund">refund</option><option value="other">other</option></select></td></tr>
            </table>
          </td>
        </tr>
<?php if (isset($HTTP_GET_VARS['mode']) && $HTTP_GET_VARS='Advanced') { ?>
        <tr valign="top">
          <td>
            <table border="0" cellspacing="0" cellpadding="2">
              <tr><td nowrap>Tax</td><td align="right"><input type="text" name="tax" value="0.00"></td></tr>
            </table>
          </td>
          <td>
            <table border="0" cellspacing="0" cellpadding="2">
              <tr><td nowrap>For Auction</td><td align="right"><select name="for_auction"><option value="">No</option><option value="true">Yes</option></select></td></tr>
              <tr><td nowrap>Auction Buyer ID</td><td align="right"><input type="text" name="auction_buyer_id" value=""></td></tr>
              <tr><td nowrap>Auction Closing Date</td><td align="right"><input type="text" name="auction_closing_date" value="<?php echo date("H:i:s M d, Y T"); ?>"></td></tr>
              <tr><td nowrap>Auction Multi-Item</td><td align="right"><input type="text" name="auction_multi_item" value=""></td></tr>
            </table>
          </td>
          <td>
            <table border="0" cellspacing="0" cellpadding="2">
              <tr><td nowrap>Item Name</td><td align="right"><input type="text" name="item_name" value=""></td></tr>
              <tr><td nowrap>Item Number</td><td align="right"><input type="text" name="item_number" value=""></td></tr>
              <tr><td nowrap>Quantity</td><td align="right"><input type="text" name="quantity" value=""></td></tr>
            </table>
          </td>
        </tr>
<?php } ?>
      </table>
    </td>
  </tr>
  <tr><td><hr class="solid"/></td></tr>
  <tr><td class="buttontd"><input class="ppbuttonsmall" type="submit" name="submit" value="Test IPN"></td></tr>
</table>
<form>
