<?php
/*
  $Id: reviews.php,v 1.1.1.1 2004/03/04 23:38:56 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('article_reviews');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
	 case 'setflag':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
         
		  if (isset($HTTP_GET_VARS['rID'])) {
		  	$reviews_id = tep_db_prepare_input($HTTP_GET_VARS['rID']);
         	tep_db_query("update " . TABLE_ARTICLE_REVIEWS . " set reviews_status = '" . $HTTP_GET_VARS['flag'] . "' where reviews_id = '" . $reviews_id . "'");
          }
          
        }
       
		tep_redirect(tep_href_link(FILENAME_ARTICLE_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $reviews_id.(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')));
        break;
      case 'update':
        $reviews_id = tep_db_prepare_input($HTTP_GET_VARS['rID']);
        $reviews_rating = tep_db_prepare_input($HTTP_POST_VARS['reviews_rating']);
        $reviews_text = tep_db_prepare_input($HTTP_POST_VARS['reviews_text']);
		$reviews_status = tep_db_prepare_input($HTTP_POST_VARS['reviews_status']);
		$reviews_title = tep_db_prepare_input($HTTP_POST_VARS['reviews_title']);

        tep_db_query("update " . TABLE_ARTICLE_REVIEWS . " set reviews_rating = '" . tep_db_input($reviews_rating) . "', reviews_status='" .$reviews_status. "', last_modified = now() where reviews_id = '" . (int)$reviews_id . "'");
        tep_db_query("update " . TABLE_ARTICLE_REVIEWS_DESCRIPTION . " set reviews_title = '" . tep_db_input($reviews_title) . "', reviews_text = '" . tep_db_input($reviews_text) . "' where reviews_id = '" . (int)$reviews_id . "'");

        tep_redirect(tep_href_link(FILENAME_ARTICLE_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $reviews_id));
        break;
      case 'deleteconfirm':
        $reviews_id = tep_db_prepare_input($HTTP_GET_VARS['rID']);

        tep_db_query("delete from " . TABLE_ARTICLE_REVIEWS . " where reviews_id = '" . (int)$reviews_id . "'");
        tep_db_query("delete from " . TABLE_ARTICLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . (int)$reviews_id . "'");

        tep_redirect(tep_href_link(FILENAME_ARTICLE_REVIEWS, 'page=' . $HTTP_GET_VARS['page']));
        break;
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('article_reviews');
$list = $listrs->showRemark();
?>
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if ($action == 'edit') {
    $rID = tep_db_prepare_input($HTTP_GET_VARS['rID']);

    $reviews_query = tep_db_query("select r.reviews_id, r.articles_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read,r.reviews_status, rd.reviews_text, rd.reviews_title, r.reviews_rating from " . TABLE_ARTICLE_REVIEWS . " r, " . TABLE_ARTICLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . (int)$rID . "' and r.reviews_id = rd.reviews_id");
    $reviews = tep_db_fetch_array($reviews_query);

    $articles_query = tep_db_query("select articles_image from " . TABLE_ARTICLES . " where articles_id = '" . (int)$reviews['articles_id'] . "'");
    $articles = tep_db_fetch_array($articles_query);

    $articles_name_query = tep_db_query("select articles_name from " . TABLE_ARTICLES_DESCRIPTION . " where articles_id = '" . (int)$reviews['articles_id'] . "' and language_id = '" . (int)$languages_id . "'");
    $articles_name = tep_db_fetch_array($articles_name_query);

    $rInfo_array = array_merge($reviews, $articles, $articles_name);
    $rInfo = new objectInfo($rInfo_array);
	  if (!isset($rInfo->reviews_status)) $rInfo->reviews_status = '1';
				switch ($rInfo->reviews_status) {
				  case '0': $in_status = false; $out_status = true; break;
				  case '1':
				  default: $in_status = true; $out_status = false;
				}
?>
      <tr><?php echo tep_draw_form('review', FILENAME_ARTICLE_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $HTTP_GET_VARS['rID'] . '&action=preview'); ?>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main" valign="top"><b><?php echo ENTRY_ARTICLE; ?></b> <?php echo $rInfo->articles_name; ?><br><b><?php echo ENTRY_FROM; ?></b> <?php echo $rInfo->customers_name; ?><br><br><b><?php echo ENTRY_DATE; ?></b> <?php echo tep_date_short($rInfo->date_added); ?></td>
            <td class="main" align="right" valign="top"><?php echo tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $rInfo->articles_image, $rInfo->articles_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table witdh="100%" border="0" cellspacing="0" cellpadding="0">
		 <tr>
			<td class="main"><b><?php echo ENTRY_REVIEW_STATUS; ?></b>
			<?php 		
			echo tep_draw_radio_field('reviews_status', '1', $in_status) . '&nbsp;On&nbsp;' . tep_draw_radio_field('reviews_status', '0', $out_status).'Off'; ?>
			</td>
		  </tr>
		    <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      	  </tr>
		  
		  
		  <tr>
            <td class="main" valign="top"><b><?php echo ENTRY_REVIEW_TITLE; ?></b><br><?php echo tep_draw_input_field('reviews_title', $rInfo->reviews_title,'size=60'); ?></td>
          </tr>
		  <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      	  </tr>
          <tr>
            <td class="main" valign="top"><b><?php echo ENTRY_REVIEW; ?></b><br><br><?php echo tep_draw_textarea_field('reviews_text', 'soft', '60', '15', $rInfo->reviews_text); ?></td>
          </tr>
          <tr>
            <td class="smallText" align="right"><?php echo ENTRY_REVIEW_TEXT; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo ENTRY_RATING; ?></b>&nbsp;<?php echo TEXT_BAD; ?>&nbsp;<?php for ($i=1; $i<=5; $i++) echo tep_draw_radio_field('reviews_rating', $i, '', $rInfo->reviews_rating) . '&nbsp;'; echo TEXT_GOOD; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo tep_draw_hidden_field('reviews_id', $rInfo->reviews_id) . tep_draw_hidden_field('articles_id', $rInfo->articles_id) . tep_draw_hidden_field('customers_name', $rInfo->customers_name) . tep_draw_hidden_field('articles_name', $rInfo->articles_name) . tep_draw_hidden_field('articles_image', $rInfo->articles_image) . tep_draw_hidden_field('date_added', $rInfo->date_added) . tep_image_submit('button_preview.gif', IMAGE_PREVIEW) . ' <a href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $HTTP_GET_VARS['rID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </form></tr>
<?php
  } elseif ($action == 'preview') {
    if (tep_not_null($HTTP_POST_VARS)) {
      $rInfo = new objectInfo($HTTP_POST_VARS);
    } else {
      $rID = tep_db_prepare_input($HTTP_GET_VARS['rID']);

      $reviews_query = tep_db_query("select r.reviews_id, r.articles_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read, rd.reviews_text, rd.reviews_title, r.reviews_rating from " . TABLE_ARTICLE_REVIEWS . " r, " . TABLE_ARTICLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . (int)$rID . "' and r.reviews_id = rd.reviews_id");
      $reviews = tep_db_fetch_array($reviews_query);

      $articles_query = tep_db_query("select articles_image from " . TABLE_ARTICLES . " where articles_id = '" . (int)$reviews['articles_id'] . "'");
      $articles = tep_db_fetch_array($articles_query);

      $articles_name_query = tep_db_query("select articles_name from " . TABLE_ARTICLES_DESCRIPTION . " where articles_id = '" . (int)$reviews['articles_id'] . "' and language_id = '" . (int)$languages_id . "'");
      $articles_name = tep_db_fetch_array($articles_name_query);

      $rInfo_array = array_merge($reviews, $articles, $articles_name);
      $rInfo = new objectInfo($rInfo_array);
    }
?>
      <tr><?php echo tep_draw_form('update', FILENAME_ARTICLE_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $HTTP_GET_VARS['rID'] . '&action=update', 'post', 'enctype="multipart/form-data"'); ?>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main" valign="top"><b><?php echo ENTRY_ARTICLE; ?></b> <?php echo $rInfo->articles_name; ?><br><b><?php echo ENTRY_FROM; ?></b> <?php echo $rInfo->customers_name; ?><br><br><b><?php echo ENTRY_DATE; ?></b> <?php echo tep_date_short($rInfo->date_added); ?></td>
            <td class="main" align="right" valign="top"><?php echo tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $rInfo->articles_image, $rInfo->articles_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"'); ?></td>
          </tr>
        </table>
      </tr>
      <tr>
        <td><table witdh="100%" border="0" cellspacing="0" cellpadding="0">
		 <tr>
            <td class="main" valign="top"><b><?php echo ENTRY_REVIEW_TITLE; ?></b><br><?php echo tep_db_prepare_input($rInfo->reviews_title); ?></td>
          </tr>
		  <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      	  </tr>
          <tr>
            <td valign="top" class="main"><b><?php echo ENTRY_REVIEW; ?></b><br><br><?php echo nl2br(tep_db_output(tep_break_string($rInfo->reviews_text, 15))); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo ENTRY_RATING; ?></b>&nbsp;<?php echo tep_image(DIR_WS_CATALOG_IMAGES . 'stars_' . $rInfo->reviews_rating . '.gif', sprintf(TEXT_OF_5_STARS, $rInfo->reviews_rating)); ?>&nbsp;<small>[<?php echo sprintf(TEXT_OF_5_STARS, $rInfo->reviews_rating); ?>]</small></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
    if (tep_not_null($HTTP_POST_VARS)) {
/* Re-Post all POST'ed variables */
      reset($HTTP_POST_VARS);
      while(list($key, $value) = each($HTTP_POST_VARS)) echo tep_draw_hidden_field($key, $value);
?>
      <tr>
        <td align="right" class="smallText"><?php echo '<a href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $rInfo->reviews_id . '&action=edit') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> ' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $rInfo->reviews_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </form></tr>
<?php
    } else {
      if (isset($HTTP_GET_VARS['origin'])) {
        $back_url = $HTTP_GET_VARS['origin'];
        $back_url_params = '';
      } else {
        $back_url = FILENAME_ARTICLE_REVIEWS;
        $back_url_params = 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $rInfo->reviews_id;
      }
?>
      <tr>
        <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
    }
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ARTICLES; ?></td>
				  <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS_EMAIL; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_RATING; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
				 <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_REVIEW_STATUS; ?></td>              
                 <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $reviews_query_raw = "select reviews_id, articles_id, date_added, last_modified, reviews_rating, reviews_status,customers_email from " . TABLE_ARTICLE_REVIEWS . " order by date_added DESC";
    $reviews_split = new splitPageResults($HTTP_GET_VARS['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $reviews_query_raw, $reviews_query_numrows);
    $reviews_query = tep_db_query($reviews_query_raw);
    while ($reviews = tep_db_fetch_array($reviews_query)) {
      if ((!isset($HTTP_GET_VARS['rID']) || (isset($HTTP_GET_VARS['rID']) && ($HTTP_GET_VARS['rID'] == $reviews['reviews_id']))) && !isset($rInfo)) {
        $reviews_text_query = tep_db_query("select r.reviews_read, r.customers_name, length(rd.reviews_text) as reviews_text_size from " . TABLE_ARTICLE_REVIEWS . " r, " . TABLE_ARTICLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . (int)$reviews['reviews_id'] . "' and r.reviews_id = rd.reviews_id");
        $reviews_text = tep_db_fetch_array($reviews_text_query);

        $articles_image_query = tep_db_query("select articles_image from " . TABLE_ARTICLES . " where articles_id = '" . (int)$reviews['articles_id'] . "'");
        $articles_image = tep_db_fetch_array($articles_image_query);

        $articles_name_query = tep_db_query("select articles_name from " . TABLE_ARTICLES_DESCRIPTION . " where articles_id = '" . (int)$reviews['articles_id'] . "' and language_id = '" . (int)$languages_id . "'");
        $articles_name = tep_db_fetch_array($articles_name_query);

        $reviews_average_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_ARTICLE_REVIEWS . " where articles_id = '" . (int)$reviews['articles_id'] . "'");
        $reviews_average = tep_db_fetch_array($reviews_average_query);

        $review_info = array_merge($reviews_text, $reviews_average, $articles_name);
        $rInfo_array = array_merge($reviews, $review_info, $articles_image);
        $rInfo = new objectInfo($rInfo_array);
      }

      if (isset($rInfo) && is_object($rInfo) && ($reviews['reviews_id'] == $rInfo->reviews_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ARTICLE_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $rInfo->reviews_id . '&action=preview') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ARTICLE_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $reviews['reviews_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $reviews['reviews_id'] . '&action=preview') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' ; ?><?php echo "<a target='_blank' href=".HTTP_CATALOG_SERVER."/".seo_get_articles_path($reviews['articles_id'], true).">".tep_get_articles_name($reviews['articles_id'])."</a>"; ?></td>
                <td class="dataTableContent" ><?php echo $reviews['customers_email']; ?></td>
				<td class="dataTableContent" align="right"><?php echo tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif'); ?></td>
               
				
				
				<td class="dataTableContent" align="right"><?php echo tep_date_short($reviews['date_added']); ?></td>
                <td class="dataTableContent" align="center">
				<?php
					  if ($reviews['reviews_status'] == '1') {
						echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS, 'action=setflag&flag=0&rID=' . $reviews['reviews_id'].(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : '').(isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
					  } else {
						echo '<a href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS, 'action=setflag&flag=1&rID=' . $reviews['reviews_id'] .(isset($HTTP_GET_VARS['page']) ? '&page=' . $HTTP_GET_VARS['page'] . '' : ''). (isset($HTTP_GET_VARS['sortorder']) ? '&sortorder=' . $HTTP_GET_VARS['sortorder'] . '' : '')) .'">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
					  }
				?>				</td>
			    <td class="dataTableContent" align="right"><?php if ( (is_object($rInfo)) && ($reviews['reviews_id'] == $rInfo->reviews_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $reviews['reviews_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $reviews_split->display_count($reviews_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_REVIEWS_ARTICLE); ?></td>
                    <td class="smallText" align="right"><?php echo $reviews_split->display_links($reviews_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();

    switch ($action) {
      case 'delete':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_REVIEW . '</b>');

        $contents = array('form' => tep_draw_form('reviews', FILENAME_ARTICLE_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $rInfo->reviews_id . '&action=deleteconfirm'));
        $contents[] = array('text' => TEXT_INFO_DELETE_REVIEW_INTRO);
        $contents[] = array('text' => '<br><b>' . $rInfo->articles_name . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $rInfo->reviews_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      default:
      if (isset($rInfo) && is_object($rInfo)) {
        $heading[] = array('text' => '<b>' . $rInfo->articles_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $rInfo->reviews_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS, 'page=' . $HTTP_GET_VARS['page'] . '&rID=' . $rInfo->reviews_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($rInfo->date_added));
			
        if (tep_not_null($rInfo->last_modified)) $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($rInfo->last_modified));
        $contents[] = array('text' => '<br>' . tep_info_image($rInfo->articles_image, $rInfo->articles_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT));
        $contents[] = array('text' => '<br>' . TEXT_INFO_REVIEW_AUTHOR . ' ' . $rInfo->customers_name);
		if (tep_not_null($rInfo->customers_email)){
        $contents[] = array('text' =>  TEXT_INFO_REVIEW_AUTHOR_EMAIL . ' ' . $rInfo->customers_email);			
		}
		$contents[] = array('text' => TEXT_INFO_REVIEW_RATING . ' ' . tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . 'stars_' . $rInfo->reviews_rating . '.gif'));
        $contents[] = array('text' => TEXT_INFO_REVIEW_READ . ' ' . $rInfo->reviews_read);
        $contents[] = array('text' => '<br>' . TEXT_INFO_REVIEW_SIZE . ' ' . $rInfo->reviews_text_size . ' bytes');
        $contents[] = array('text' => '<br>' . TEXT_INFO_ARTICLES_AVERAGE_RATING . ' ' . number_format($rInfo->average_rating, 2) . '%');
      }
        break;
    }

    if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
      echo '            <td width="25%" valign="top">' . "\n";

      $box = new box;
      echo $box->infoBox($heading, $contents);

      echo '            </td>' . "\n";
    }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
