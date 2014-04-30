<?php
  if ($article_check['total'] < 1) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>" class="mainbodybackground">
     <tr>
        <td><?php new infoBox(array(array('text' => HEADING_ARTICLE_NOT_FOUND))); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
	  </table>
<?php
  } else {
    $article_info_query = tep_db_query("select a.articles_id,a.articles_image, a.articles_date_added, a.articles_date_available, a.authors_id, ad.articles_name, ad.articles_description, ad.articles_url, au.authors_name
                                        from " . TABLE_ARTICLES . " a,
                                             " . TABLE_ARTICLES_DESCRIPTION . " ad,
                                             " . TABLE_AUTHORS . " au
                                        where a.articles_status = '1'
                                          and a.articles_id = '" . (int)$HTTP_GET_VARS['articles_id'] . "'
                                          and a.authors_id = au.authors_id
                                          and ad.articles_id = a.articles_id
                                          and ad.language_id = '" . (int)$languages_id . "'");
    $article_info = tep_db_fetch_array($article_info_query);

    tep_db_query("update " . TABLE_ARTICLES_DESCRIPTION . " set articles_viewed = articles_viewed+1 where articles_id = '" . (int)$HTTP_GET_VARS['articles_id'] . "' and language_id = '" . (int)$languages_id . "'");

    $articles_name = $article_info['articles_name'];
    $articles_author_id = $article_info['authors_id'];
    $articles_author = $article_info['authors_name'];
 if (tep_not_null($articles_author)) $title_author = '<span class="smallText">' . TEXT_BY . '<a href="' . tep_href_link(FILENAME_ARTICLES,'authors_id=' . $articles_author_id) . '">' . $articles_author . '</a></span>';
?>

<?php 
$titletagstr = $articles_name .' - ' . $title_author;
echo tep_get_design_body_header($titletagstr); 


 if ($messageStack->size('friend') > 0) {
?>
     <table width="100%" align="center" cellpadding="0" cellspacing="0">
	  <tr>
        <td><?php echo $messageStack->output('friend'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
	  </table>
<?php
  }
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
     
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td class="main" valign="top">
          <?php //echo stripslashes($article_info['articles_description']); 
		  	//echo DIR_WS_IMAGES.$article_info['articles_image'];
			if(isset($article_info['articles_image'])&&$article_info['articles_image']!='')
			{
				echo tep_image(DIR_WS_IMAGES.$article_info['articles_image']);
			}
		  ?>
        </td>
      </tr>
	  <tr>
        <td class="main" valign="top">
          <p><?php echo stripslashes($article_info['articles_description']); ?></p>
        </td>
      </tr>
<?php
    if (tep_not_null($article_info['articles_url'])) {
?>
      <tr>
        <td class="main"><?php echo sprintf(TEXT_MORE_INFORMATION, tep_href_link(FILENAME_REDIRECT, 'action=url&amp;goto=' . urlencode($article_info['articles_url']), 'NONSSL', true, false)); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
    }

    if ($article_info['articles_date_available'] > date('Y-m-d H:i:s')) {
?>
      <tr>
        <td align="left" class="smallText"><?php echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($article_info['articles_date_available'])); ?></td>
      </tr>
<?php
    } else {
?>
      <tr>
        <td align="left" class="smallText"><?php echo sprintf(TEXT_DATE_ADDED, tep_date_long($article_info['articles_date_added'])); ?></td>
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
<?php
  if (ENABLE_ARTICLE_REVIEWS == 'true') {
    $reviews_query = tep_db_query("select count(*) as count from " . TABLE_ARTICLE_REVIEWS . " where articles_id = '" . (int)$HTTP_GET_VARS['articles_id'] . "' and reviews_status = '1'");
    $reviews = tep_db_fetch_array($reviews_query);
?>
      <tr>
        <td class="main"><?php echo TEXT_CURRENT_REVIEWS . ' ' . $reviews['count']; ?></td>
      </tr>

      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php //echo '<a href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS_WRITE, tep_get_all_get_params()) . '">' . tep_template_image_button('button_submit_reviews.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a> '; ?>
<?php //echo '<a href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS, tep_get_all_get_params()) . '">' . tep_template_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS) . '</a>'; ?>
<?php
							include("article_reviews_tabs_ajax.php");
							?>

<?php
							//include("article_friend_ajax.php");
							?>

<?php

  }
?>
      </tr>
<!-- tell_a_friend //-->
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
          <tr>
            <td>
<?php
  /*if (ENABLE_TELL_A_FRIEND_ARTICLE == 'true') {
    if (isset($HTTP_GET_VARS['articles_id'])) {
//echo '<a name="friend"></a>';
	 
	if (!tep_session_is_registered('customer_id')) 
	{
    	$tell_a_friend_text = TEXT_TELL_A_FRIEND . '<br />&nbsp;' .FORM_FIELD_FRIEND_EMAIL. tep_draw_input_field('to_email_address', '', 'size="30" maxlength="40" style="width: ' . (BOX_WIDTH-30) . 'px"'). '&nbsp;<span class="inputRequirement">*</span>' . '&nbsp;' .FORM_FIELD_CUSTOMER_EMAIL. tep_draw_input_field('from_email_address', '', 'size="30" maxlength="40" style="width: ' . (BOX_WIDTH-30) . 'px"'). '&nbsp;<span class="inputRequirement">*</span>' . '&nbsp;' . tep_draw_hidden_field('articles_id', $HTTP_GET_VARS['articles_id']) . tep_hide_session_id() . tep_template_image_submit('button_tell_a_friend.gif', BOX_HEADING_TELL_A_FRIEND) ;
	}
	else
	{
	$account_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customer_id . "'");
    $account = tep_db_fetch_array($account_query);
    $from_name = $account['customers_firstname'] . ' ' . $account['customers_lastname'];
    $from_email_address = $account['customers_email_address'];
	
		$tell_a_friend_text = TEXT_TELL_A_FRIEND . '<br />&nbsp;' .FORM_FIELD_FRIEND_EMAIL. tep_draw_input_field('to_email_address', '', 'size="30" maxlength="40" style="width: ' . (BOX_WIDTH-30) . 'px"'). '&nbsp;<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>' . '&nbsp;' .FORM_FIELD_CUSTOMER_EMAIL. tep_draw_input_field('from_email_address', $from_email_address, 'size="30" maxlength="40" style="width: ' . (BOX_WIDTH-30) . 'px"'). '&nbsp;<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>' . '&nbsp;' . tep_draw_hidden_field('articles_id', $HTTP_GET_VARS['articles_id']) . tep_hide_session_id() . tep_template_image_submit('button_tell_a_friend.gif', BOX_HEADING_TELL_A_FRIEND) ;
	}	
	
      $info_box_contents1 = array();
      $info_box_contents1[] = array('text' => BOX_TEXT_TELL_A_FRIEND);

      new infoBoxHeading($info_box_contents1, false, false);

      $info_box_contents1 = array();
      $info_box_contents1[] = array('form' => tep_draw_form('email_friend_article', tep_href_link(FILENAME_ARTICLE_INFO, ''),'post'),
                                   'align' => 'left',
                                   'text' => $tell_a_friend_text);

      new infoBox($info_box_contents1);
    }
$info_box_contents1 = array();
  $info_box_contents1[] = array('align' => 'left',
                                'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                              );
  new infoboxFooter($info_box_contents1, true, true);
  }*/
?>
            </td>
          </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>          
<!-- tell_a_friend_eof //-->
      <tr>
        <td>
<?php
//added for cross-sell
   if ( (USE_CACHE == 'true') && !SID) {
     include(DIR_FS_MODULES . FILENAME_ARTICLES_XSELL);
   } else {
     include(DIR_FS_MODULES . FILENAME_ARTICLES_XSELL);
    }
   }
?>
        </td>
      </tr>
    </table></td>
<!-- body_text_eof //-->

<?php echo tep_get_design_body_footer();?>