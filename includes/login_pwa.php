<?php
//BOF: MaxiDVD Returning Customer Info SECTION
//===========================================================
$returning_customer_title = HEADING_RETURNING_CUSTOMER;	// DDB - 040620 - PWA - change TEXT by HEADING
$returning_customer_info = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" id=\"AutoNumber1\">
  <tr>
    <td width=\"100%\" class=\"main\" colspan=\"3\">" . TEXT_RETURNING_CUSTOMER . "</td>
  </tr>
  <tr>
    <td width=\"100%\" class=\"main\" colspan=\"3\">" . tep_draw_separator('pixel_trans.gif', '100%', '10') . "</td>
  </tr>
  <tr>
    <td class=\"main\">

<table width=\"70%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\">
  <tr>
    <td class=\"main\">" . ENTRY_EMAIL_ADDRESS . "</td>
    <td>" . tep_draw_input_field('email_address','',' class="sign_in_box"') . "</td>
  </tr>
  <tr>
    <td class=\"main\">" . ENTRY_PASSWORD . "<br /><br /></td>
        <td>" . tep_draw_password_field('password','',' class="sign_in_box"') . "<br /><br /></td>
  </tr>
</table>
<table width=\"30%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"right\">
  <tr>
        <td align=\"center\" class=\"smalltext\">" . tep_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN) . "<br /><br />" . '<a href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '" style="text-decoration:underline;">' . TEXT_PASSWORD_FORGOTTEN . '</a>' . "<br /><br /></td>
  </tr>
</table>
</td>
  </tr>
</table>
";
//===========================================================
?>
<!-- login_pwa -->

    <tr>
     <td class="main" width="100%" valign="top" align="center">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $returning_customer_title );
  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $returning_customer_info);
  new infoBox($info_box_contents);
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                    'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                  );
  new infoboxFooter($info_box_contents, true, true);
?>
  </td>
 </tr>

<?php
//EOF: MaxiDVD Returning Customer Info SECTION
//===========================================================





//MaxiDVD New Account Sign Up SECTION
//===========================================================
$create_account_title = HEADING_NEW_CUSTOMER;
$create_account_info = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\"  width=\"100%\" id=\"AutoNumber1\">
  <tr>
    <td width=\"100%\" class=\"main\" colspan=\"3\">" . TEXT_NEW_CUSTOMER . '<br /><br />' . TEXT_NEW_CUSTOMER_INTRODUCTION . "</td>
  </tr>
  <tr>
    <td width=\"100%\" class=\"main\" colspan=\"3\">" . tep_draw_separator('pixel_trans.gif', '100%', '10') . "</td>
  </tr>
  <tr>
    <td width=\"33%\" class=\"main\"></td>
    <td width=\"33%\"></td>
    <td width=\"34%\" rowspan=\"3\" align=\"center\">" . '<a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL') . '">' . tep_image_button('button_create_account.gif', IMAGE_BUTTON_CREATE_ACCOUNT) . '</a>' . "<br /><br /></td>
  </tr>
</table>";
//===========================================================
?>
<?php echo tep_draw_separator('pixel_trans.gif', '100%', '15'); ?>
    <tr>
     <td class="main" width="100%" valign="top" align="center">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $create_account_title );
  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $create_account_info);
  new infoBox($info_box_contents);
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                    'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                  );
  new infoboxFooter($info_box_contents, true, true);
?>
  </td>
  </tr>

<?php
//EOF: MaxiDVD New Account Sign Up SECTION
//===========================================================





//BOF: MaxiDVD Purchase With-Out An Account SECTION
//===========================================================
$pwa_checkout_title = HEADING_CHECKOUT;
$pwa_checkout_info = "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" id=\"AutoNumber1\">
  <tr>
    <td width=\"100%\" class=\"main\" colspan=\"3\">" . TEXT_CHECKOUT_INTRODUCTION . "</td>
  </tr>
  <tr>
    <td width=\"100%\" class=\"main\" colspan=\"3\">" . tep_draw_separator('pixel_trans.gif', '100%', '10') . "</td>
  </tr>
  <tr>
    <td width=\"33%\" class=\"main\"></td>
    <td width=\"33%\"></td>
    <td width=\"34%\" rowspan=\"3\" align=\"center\">" . '<a href="' . tep_href_link(FILENAME_CHECKOUT, '', 'SSL') . '">' . tep_image_button('button_checkout.gif', IMAGE_BUTTON_CHECKOUT) . '</a>' . "<br /><br /></td>
  </tr>
</table>";
//===========================================================
?>
<?php echo tep_draw_separator('pixel_trans.gif', '100%', '15'); ?>

    <tr>
     <td class="main" width="100%" valign="top" align="center">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $pwa_checkout_title );
  new infoBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                               'text'  => $pwa_checkout_info);
  new infoBox($info_box_contents);
  $info_box_contents = array();
    $info_box_contents[] = array('align' => 'left',
                                  'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                );
  new infoboxFooter($info_box_contents, true, true);
?>
  </td>
  </tr>

  <?php
//EOF: MaxiDVD Purchase With-Out An Account SECTION
//===========================================================
?>
