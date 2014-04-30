<?php
/*
  $Id: links.php,v 1.00 2003/10/02 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('links');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
// define our link functions
  require(DIR_WS_FUNCTIONS . 'links.php');

  
  //设置友情连接为首页显示 by lwka 2012-10-16 add 
  if (isset($_GET['set_home_id']) && isset($_GET['set_home'])) {
  	$set_home_id = tep_db_input($_GET['set_home_id']);
  	$set_home = tep_db_input($_GET['set_home']);
  	$sql = "update `links` set `display_on_home_page`='" . $set_home . "' where `links_id`='" . $set_home_id . "'";
  	tep_db_query($sql);
  }
  //设置友情连接为首页功能结束 

  
  $links_statuses = array();
  $links_status_array = array();
  $links_status_query = tep_db_query("select links_status_id, links_status_name from " . TABLE_LINKS_STATUS . " where language_id = '" . (int)$languages_id . "'");
  while ($links_status = tep_db_fetch_array($links_status_query)) {
    $links_statuses[] = array('id' => $links_status['links_status_id'],
                               'text' => $links_status['links_status_name']);
    $links_status_array[$links_status['links_status_id']] = $links_status['links_status_name'];
  }

  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  $error = false;
  $processed = false;
  if (isset($_POST['action']) && $_POST['action'] == 'insert'){      
        $links_id = html_to_db(tep_db_prepare_input($HTTP_GET_VARS['lID']));
        $links_title = html_to_db(ajax_to_general_string(tep_db_prepare_input($HTTP_POST_VARS['links_title'])));
        $links_url = tep_db_prepare_input($HTTP_POST_VARS['links_url']);
        $links_category = tep_db_prepare_input($HTTP_POST_VARS['links_category']);
        $links_description = html_to_db(ajax_to_general_string(tep_db_prepare_input($HTTP_POST_VARS['links_description'])));
        $links_image_url = tep_db_prepare_input($HTTP_POST_VARS['links_image_url']);
        $links_contact_name = html_to_db(ajax_to_general_string(tep_db_prepare_input($HTTP_POST_VARS['links_contact_name'])));
        $links_contact_email = tep_db_prepare_input($HTTP_POST_VARS['links_contact_email']);
        $links_reciprocal_url = tep_db_prepare_input($HTTP_POST_VARS['links_reciprocal_url']);
        $links_status = tep_db_prepare_input($HTTP_POST_VARS['links_status']);
        $links_rating = tep_db_prepare_input($HTTP_POST_VARS['links_rating']);
        
        if (strlen($links_title) < ENTRY_LINKS_TITLE_MIN_LENGTH) {
          $error = true;
          $entry_links_title_error = true;
        } else {
          $entry_links_title_error = false;
        }

        if (strlen($links_url) < ENTRY_LINKS_URL_MIN_LENGTH) {
          $error = true;
          $entry_links_url_error = true;
        } else {
          $entry_links_url_error = false;
        }

        if (strlen($links_description) < ENTRY_LINKS_DESCRIPTION_MIN_LENGTH) {
          $error = true;
          $entry_links_description_error = true;
        } else {
          $entry_links_description_error = false;
        }

        if (strlen($links_contact_name) < ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH) {
          $error = true;
          $entry_links_contact_name_error = true;
        } else {
          $entry_links_contact_name_error = false;
        }

        if (strlen($links_contact_email) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
          $error = true;
          $entry_links_contact_email_error = true;
        } else {
          $entry_links_contact_email_error = false;
        }

        if (!tep_validate_email($links_contact_email)) {
          $error = true;
          $entry_links_contact_email_check_error = true;
        } else {
          $entry_links_contact_email_check_error = false;
        }
        
        if (strlen($links_reciprocal_url) < ENTRY_LINKS_URL_MIN_LENGTH) {
          $error = true;
          $entry_links_reciprocal_url_error = true;
        } else {
          $entry_links_reciprocal_url_error = false;
        }
        
        if ($error == false) {
          if (!tep_not_null($links_image_url) || ($links_image_url == 'http://')) {
            $links_image_url = '';
          }

          $sql_data_array = array('links_url' => $links_url,
                                  'links_image_url' => $links_image_url,
                                  'links_contact_name' => $links_contact_name,
                                  'links_contact_email' => $links_contact_email,
                                  'links_reciprocal_url' => $links_reciprocal_url, 
                                  'links_status' => $links_status, 
                                  'links_rating' => $links_rating,
                                  'links_date_added' => 'now()');

          tep_db_perform(TABLE_LINKS, $sql_data_array);
          $links_id = tep_db_insert_id();
          //scs changes bof
          $categories_query = tep_db_query("select link_categories_id from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_id = '" . $links_category . "'");
          if($categories = tep_db_fetch_array($categories_query))
          {
          
          }else {
  
            tep_db_query("insert into " . TABLE_LINK_CATEGORIES_DESCRIPTION . " (link_categories_id, link_categories_name) values ('" . (int)$links_category . "', '" . tep_get_categories_name($links_category) . "')");
      
          }
          
          
          
          $link_categories_id = $links_category;
            
            //scs change eof
          
         tep_db_query("insert into " . TABLE_LINKS_TO_LINK_CATEGORIES . " ( links_id, link_categories_id) values ('" . (int)$links_id . "', '" . (int)$link_categories_id . "')");$sql_data_array = array('links_title' => $links_title,
                                  'links_description' => $links_description);

         
            $insert_sql_data = array('links_id' => $links_id,
                                     'language_id' => $languages_id);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
            tep_db_perform(TABLE_LINKS_DESCRIPTION, $sql_data_array);
            if (isset($HTTP_POST_VARS['links_notify']) && ($HTTP_POST_VARS['links_notify'] == 'on')) {
            
            $links_status =  (int)$HTTP_POST_VARS['links_status'];
            if ($links_status == 2){                
                if (IS_QA_SITES){
                    $linkurl = 'http://qa.usitrip.com/links.html';
                }
                if (IS_DEV_SITES){
                    $linkurl = 'http://howard-dev.usitrip.com/links.html';
                }
                if (IS_LIVE_SITES){
                    $linkurl = 'http://www.usitrip.com/links.html';
                }
                $email_text = "您好，".$links_contact_name."：<br/> 您提交的友情链接申请已经审核通过并发布到以下页面，请登录以下页面查看你的网站信息：<br/> 链接发布页面URL地址:". $linkurl ."<br/>如链接信息有误，请联系我们的链接专员：<br/>QQ：2216364379<br/>MSN: usi4trip_charles@hotmail.com<br/>email: charles.huang@usitrip.com<br/>走四方网敬上";  
                tep_mail(db_to_html($links_contact_name), $links_contact_email, '', db_to_html($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
            }
          }
          /*if (isset($HTTP_POST_VARS['links_notify']) && ($HTTP_POST_VARS['links_notify'] == 'on')) {
            $email = sprintf(EMAIL_TEXT_STATUS_UPDATE, $links_contact_name, $links_status_array[$links_status]) . "\n\n" . STORE_OWNER . "\n" . STORE_NAME;

            tep_mail($links_contact_name, $links_contact_email, EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
          }*/
        echo '1';
          //tep_redirect(tep_href_link(FILENAME_LINKS, tep_get_all_get_params(array('lID', 'action')) . 'lID=' . $links_id));
        } 
        exit;
  }
  if (tep_not_null($action)) {
    switch ($action) {
      case 'insert':        
      case 'update':
        $links_id = tep_db_prepare_input($HTTP_GET_VARS['lID']);
        $links_title = tep_db_prepare_input($HTTP_POST_VARS['links_title']);
        $links_url = tep_db_prepare_input($HTTP_POST_VARS['links_url']);
        $links_category = tep_db_prepare_input($HTTP_POST_VARS['links_category']);
        $links_description = tep_db_prepare_input($HTTP_POST_VARS['links_description']);
        $links_image_url = tep_db_prepare_input($HTTP_POST_VARS['links_image_url']);
        $links_contact_name = tep_db_prepare_input($HTTP_POST_VARS['links_contact_name']);
        $links_contact_email = tep_db_prepare_input($HTTP_POST_VARS['links_contact_email']);
        $links_reciprocal_url = tep_db_prepare_input($HTTP_POST_VARS['links_reciprocal_url']);
        $links_status = tep_db_prepare_input($HTTP_POST_VARS['links_status']);
        $links_rating = tep_db_prepare_input($HTTP_POST_VARS['links_rating']);

        if (strlen($links_title) < ENTRY_LINKS_TITLE_MIN_LENGTH) {
          $error = true;
          $entry_links_title_error = true;
        } else {
          $entry_links_title_error = false;
        }

        if (strlen($links_url) < ENTRY_LINKS_URL_MIN_LENGTH) {
          $error = true;
          $entry_links_url_error = true;
        } else {
          $entry_links_url_error = false;
        }

        if (strlen($links_description) < ENTRY_LINKS_DESCRIPTION_MIN_LENGTH) {
          $error = true;
          $entry_links_description_error = true;
        } else {
          $entry_links_description_error = false;
        }

        if (strlen($links_contact_name) < ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH) {
          $error = true;
          $entry_links_contact_name_error = true;
        } else {
          $entry_links_contact_name_error = false;
        }

        if (strlen($links_contact_email) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
          $error = true;
          $entry_links_contact_email_error = true;
        } else {
          $entry_links_contact_email_error = false;
        }

        if (!tep_validate_email($links_contact_email)) {
          $error = true;
          $entry_links_contact_email_check_error = true;
        } else {
          $entry_links_contact_email_check_error = false;
        }

        if (strlen($links_reciprocal_url) < ENTRY_LINKS_URL_MIN_LENGTH) {
          $error = true;
          $entry_links_reciprocal_url_error = true;
        } else {
          $entry_links_reciprocal_url_error = false;
        }

        if ($error == false) {
          if (!tep_not_null($links_image_url) || ($links_image_url == 'http://')) {
            $links_image_url = '';
          }

          $sql_data_array = array('links_url' => $links_url,
                                  'links_image_url' => $links_image_url,
                                  'links_contact_name' => $links_contact_name,
                                  'links_contact_email' => $links_contact_email,
                                  'links_reciprocal_url' => $links_reciprocal_url, 
                                  'links_status' => $links_status, 
                                  'links_rating' => $links_rating);

          if ($action == 'update') {
            $sql_data_array['links_last_modified'] = 'now()';
          } else if($action == 'insert') {
            $sql_data_array['links_date_added'] = 'now()';
          }

          if ($action == 'update') {
            tep_db_perform(TABLE_LINKS, $sql_data_array, 'update', "links_id = '" . (int)$links_id . "'");
          } else if($action == 'insert') {
            tep_db_perform(TABLE_LINKS, $sql_data_array);

            $links_id = tep_db_insert_id();
          }


        
            //scs changes bof
          $categories_query = tep_db_query("select link_categories_id from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " where link_categories_id = '" . $links_category . "'");

    
          if($categories = tep_db_fetch_array($categories_query))
          {
          
          }else {
  
            tep_db_query("insert into " . TABLE_LINK_CATEGORIES_DESCRIPTION . " (link_categories_id, link_categories_name) values ('" . (int)$links_category . "', '" . tep_get_categories_name($links_category) . "')");
      
          }
          
          
          
          $link_categories_id = $links_category;
            
            //scs change eof
          if ($action == 'update') {
            tep_db_query("update " . TABLE_LINKS_TO_LINK_CATEGORIES . " set link_categories_id = '" . (int)$link_categories_id . "' where links_id = '" . (int)$links_id . "'");
          } else if($action == 'insert') {
            tep_db_query("insert into " . TABLE_LINKS_TO_LINK_CATEGORIES . " ( links_id, link_categories_id) values ('" . (int)$links_id . "', '" . (int)$link_categories_id . "')");
          }

          $sql_data_array = array('links_title' => $links_title,
                                  'links_description' => $links_description);

          if ($action == 'update') {
            tep_db_perform(TABLE_LINKS_DESCRIPTION, $sql_data_array, 'update', "links_id = '" . (int)$links_id . "' and language_id = '" . (int)$languages_id . "'");
          } else if($action == 'insert') {
            $insert_sql_data = array('links_id' => $links_id,
                                     'language_id' => $languages_id);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
            tep_db_perform(TABLE_LINKS_DESCRIPTION, $sql_data_array);
          }

          if (isset($HTTP_POST_VARS['links_notify']) && ($HTTP_POST_VARS['links_notify'] == 'on')) {
            
            $links_status =  (int)$HTTP_POST_VARS['links_status'];
            if ($links_status == 2){                
                if (IS_QA_SITES){
                    $linkurl = 'http://qa.usitrip.com/links.html';
                }
                if (IS_DEV_SITES){
                    $linkurl = 'http://howard-dev.usitrip.com/links.html';
                }
                if (IS_LIVE_SITES){
                    $linkurl = 'http://www.usitrip.com/links.html';
                }
                $email_text = "您好，".$links_contact_name."：<br/> 您提交的友情链接申请已经审核通过并发布到以下页面，请登录以下页面查看你的网站信息：<br/> 链接发布页面URL地址:". $linkurl ."<br/>如链接信息有误，请联系我们的链接专员：<br/>QQ：2216364379<br/>MSN: usi4trip_charles@hotmail.com<br/>email: charles.huang@usitrip.com<br/>走四方网敬上";  
                tep_mail(db_to_html($links_contact_name), $links_contact_email, '', db_to_html($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
            }
          }

          tep_redirect(tep_href_link(FILENAME_LINKS, tep_get_all_get_params(array('lID', 'action')) . 'lID=' . $links_id));
        } else if ($error == true) {
          $lInfo = new objectInfo($HTTP_POST_VARS);
          $processed = true;
        }

        break;
      case 'deleteconfirm':
        $links_id = tep_db_prepare_input($HTTP_GET_VARS['lID']);

        tep_remove_link($links_id);

        tep_redirect(tep_href_link(FILENAME_LINKS, tep_get_all_get_params(array('lID', 'action'))));
        break;
      default:
        $links_query = tep_db_query("select l.links_id, ld.links_title, l.links_url, ld.links_description, l.links_contact_email, l.links_status, l.links_image_url, l.links_contact_name, l.links_reciprocal_url, l.links_status, l.links_rating from " . TABLE_LINKS . " l left join " . TABLE_LINKS_DESCRIPTION . " ld on ld.links_id = l.links_id where ld.links_id = l.links_id and l.links_id = '" . (int)$HTTP_GET_VARS['lID'] . "' and ld.language_id = '" . (int)$languages_id . "'");
        $links = tep_db_fetch_array($links_query);

        $categories_query = tep_db_query("select lcd.link_categories_name as links_category,l2lc.link_categories_id from " . TABLE_LINKS_TO_LINK_CATEGORIES . " l2lc left join " . TABLE_LINK_CATEGORIES_DESCRIPTION . " lcd on lcd.link_categories_id = l2lc.link_categories_id where l2lc.links_id = '" . (int)$HTTP_GET_VARS['lID'] . "' and lcd.language_id = '" . (int)$languages_id . "'");
        $category = tep_db_fetch_array($categories_query);

        $lInfo_array = array_merge((array)$links, (array)$category);
        $lInfo = new objectInfo($lInfo_array);
    }
  }
  
  
  $search = '';
    if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
      $keywords = tep_db_input(tep_db_prepare_input($HTTP_GET_VARS['search']));
      if ($keywords != '输入网站名称搜索'){
        $search .= " and ld.links_title like BINARY('%" . $keywords . "%')";
      }
    }
    
    if (isset($_GET['added_start_date']) && tep_not_null($_GET['added_start_date'])){
        $added_start_data = tep_db_input(tep_db_prepare_input($_GET['added_start_date']));
    }    
    if (isset($_GET['added_end_date']) && tep_not_null($_GET['added_end_date'])){
        $added_end_data = tep_db_input(tep_db_prepare_input($_GET['added_end_date']));
        $search .= " AND l.links_date_added >= '". $added_start_data." 00:00:00'  AND l.links_date_added <= '". $added_end_data ." 23:59:59'";
    }
    
    $sortorder = '';
    if($HTTP_GET_VARS['sortorder'] == 'title'){
    $sortorder = ' ld.links_title, l.links_url';
    }else if ($HTTP_GET_VARS['sortorder'] == 'title-desc'){
     $sortorder = ' ld.links_title desc, l.links_url';
    }else {
    $sortorder = ' l.links_id DESC,ld.links_title, l.links_url';
    }

if(isset($HTTP_GET_VARS['lcPath']) && $HTTP_GET_VARS['lcPath'] !=''){

    $links_query_raw = "select l.display_on_home_page, l.links_id, l.links_url, l.links_image_url, l.links_date_added, l.links_last_modified, l.links_status, l.links_clicked, ld.links_title, ld.links_description, l.links_contact_name, l.links_contact_email, l.links_reciprocal_url, l.links_status from " . TABLE_LINKS . " as l, " . TABLE_LINKS_DESCRIPTION . " as ld, " . TABLE_LINKS_TO_LINK_CATEGORIES . " as l2lc  where l.links_id = ld.links_id AND l2lc.links_id = l.links_id AND l2lc.link_categories_id ='".(int)$HTTP_GET_VARS['lcPath']."' AND ld.language_id = '" . (int)$languages_id . "'" . $search . " order by ".$sortorder;

}else{
    $links_query_raw = "select l.display_on_home_page, l.links_id, l.links_url, l.links_image_url, l.links_date_added, l.links_last_modified, l.links_status, l.links_clicked, ld.links_title, ld.links_description, l.links_contact_name, l.links_contact_email, l.links_reciprocal_url, l.links_status from " . TABLE_LINKS . " l left join " . TABLE_LINKS_DESCRIPTION . " ld on l.links_id = ld.links_id where ld.language_id = '" . (int)$languages_id . "'" . $search . " order by ".$sortorder;
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
<script language="JavaScript" src="includes/javascript/calendar.js"></script>
<?php
  if ($action == 'edit' || $action == 'update' || $action == 'new' || $action == 'insert') {
?>
<script language="javascript"><!--

function check_form() {
  var error = 0;
  var error_message = "<?php echo JS_ERROR; ?>";
  
  var links_title = document.forms['links'].links_title.value;
  var links_url = document.links.links_url.value;
  var links_category = document.links.links_category.value;
  var links_description = document.links.links_description.value;
  var links_image_url = document.links.links_image_url.value;
  var links_contact_name = document.links.links_contact_name.value;
  var links_contact_email = document.links.links_contact_email.value;
  var links_reciprocal_url = document.links.links_reciprocal_url.value;
  var links_rating = document.links.links_rating.value;
 
  
  if (links_title == "" || links_title.length < <?php echo ENTRY_LINKS_TITLE_MIN_LENGTH; ?>) {
    error_message = error_message + "* " + "<?php echo ENTRY_LINKS_TITLE_ERROR; ?>" + "\n";
    error = 1;
  }
  
  if (links_url == "" || links_url.length < <?php echo ENTRY_LINKS_URL_MIN_LENGTH; ?>) {
    error_message = error_message + "* " + "<?php echo ENTRY_LINKS_URL_ERROR; ?>" + "\n";
    error = 1;
  }
  
  if (links_category == "") {
    error_message = error_message + "* " + "<?php echo 'Please select a category!\n'; ?>" + "\n";
    error = 1;
  }
  
  if (links_description == "" || links_description.length < <?php echo ENTRY_LINKS_DESCRIPTION_MIN_LENGTH; ?>) {
    error_message = error_message + "* " + "<?php echo ENTRY_LINKS_DESCRIPTION_ERROR; ?>" + "\n";
    error = 1;
  }

  if (links_contact_name == "" || links_contact_name.length < <?php echo ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH; ?>) {
    error_message = error_message + "* " + "<?php echo ENTRY_LINKS_CONTACT_NAME_ERROR; ?>" + "\n";
    error = 1;
  }

  if (links_contact_email == "" || links_contact_email.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
    error_message = error_message + "<?php echo JS_EMAIL_ADDRESS; ?>";
    error = 1;
  }

  if (links_reciprocal_url == "" || links_reciprocal_url.length < <?php echo ENTRY_LINKS_URL_MIN_LENGTH; ?>) {
    error_message = error_message + "* " + "<?php echo ENTRY_LINKS_RECIPROCAL_URL_ERROR; ?>" + "\n";
    error = 1;
  }

  if (links_rating == "") {
    error_message = error_message + "* " + "<?php echo ENTRY_LINKS_RATING_ERROR; ?>" + "\n";
    error = 1;
  }
  
  if (error == 1) {
    alert(error_message);
    return false;
  } else {
    return true;
  }
}
//--></script>
<?php
  }
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('links');
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
<?php
  if ($action == 'edit' || $action == 'update' || $action == 'new' || $action == 'insert') {
    if ($action == 'edit' || $action == 'update') {
      $form_action = 'update';
      $contact_name_default = '';
      $contact_email_default = '';
    } else {
      $form_action = 'insert';
      $contact_name_default = STORE_OWNER;
      $contact_email_default = STORE_OWNER_EMAIL_ADDRESS;
    }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo tep_draw_form('links', FILENAME_LINKS, tep_get_all_get_params(array('action')) . 'action=' . $form_action, 'post', 'onSubmit="return check_form();"'); ?>
        <td class="formAreaTitle"><?php echo CATEGORY_WEBSITE; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_LINKS_TITLE; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_links_title_error == true) {
      echo tep_draw_input_field('links_title', $lInfo->links_title, 'maxlength="64"') . '&nbsp;' . ENTRY_LINKS_TITLE_ERROR;
    } else {
      echo $lInfo->links_title . tep_draw_hidden_field('links_title');
    }
  } else {
    echo tep_draw_input_field('links_title', $lInfo->links_title, 'maxlength="64"', true);
  }
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LINKS_URL; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_links_url_error == true) {
      echo tep_draw_input_field('links_url', $lInfo->links_url, 'maxlength="255"') . '&nbsp;' . ENTRY_LINKS_URL_ERROR;
    } else {
      echo $lInfo->links_url . tep_draw_hidden_field('links_url');
    }
  } else {
    echo tep_draw_input_field('links_url', tep_not_null($lInfo->links_url) ? $lInfo->links_url : 'http://', 'maxlength="255"', true);
  }
?></td>
          </tr>
<?php
    $categories_array = array();
    $categories_query = tep_db_query("select lcd.link_categories_id, lcd.link_categories_name from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " lcd where language_id = '" . (int)$languages_id . "' order by lcd.link_categories_name");
    while ($categories_values = tep_db_fetch_array($categories_query)) {
      $categories_array[] = array('id' => $categories_values['link_categories_name'], 'text' => $categories_values['link_categories_name']);
    }
?>
    <?php 
   // echo tep_draw_hidden_field('links_category', '0');
    ?>
    
    <?php 
    // 应Charles要求取消友情链接的分类 2011-11-8 by panda
    ?>
        <tr>
            <td class="main"><?php echo ENTRY_LINKS_CATEGORY; ?></td>
            <td class="main">

<?php
  if ($error == true) {
    echo $lInfo->links_category . tep_draw_hidden_field('links_category');
  } else {
    echo tep_draw_pull_down_menu('links_category', tep_get_link_category($languages_id), $lInfo->link_categories_id, '', true);
  }
?></td>
          </tr>
    
          <tr>
            <td class="main"><?php echo ENTRY_LINKS_DESCRIPTION; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_links_description_error == true) {
      echo tep_draw_textarea_field('links_description', 'hard', 40, 5, $lInfo->links_description) . '&nbsp;' . ENTRY_LINKS_DESCRIPTION_ERROR;
    } else {
      echo $lInfo->links_description . tep_draw_hidden_field('links_description');
    }
  } else {
    echo tep_draw_textarea_field('links_description', 'hard', 40, 5, $lInfo->links_description) . TEXT_FIELD_REQUIRED;
  }
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LINKS_IMAGE; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    echo $lInfo->links_image_url . tep_draw_hidden_field('links_image_url');
  } else {
    echo tep_draw_input_field('links_image_url', tep_not_null($lInfo->links_image_url) ? $lInfo->links_image_url : 'http://', 'maxlength="255"');
  }
?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_CONTACT; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_LINKS_CONTACT_NAME; ?></td>
            <td class="main">
<?php
    if ($error == true) {
      if ($entry_links_contact_name_error == true) {
        echo tep_draw_input_field('links_contact_name', $lInfo->links_contact_name, 'maxlength="64"', true) . '&nbsp;' . ENTRY_LINKS_CONTACT_NAME_ERROR;
      } else {
        echo $lInfo->links_contact_name . tep_draw_hidden_field('links_contact_name');
      }
    } else {
      echo tep_draw_input_field('links_contact_name', tep_not_null($lInfo->links_contact_name) ? $lInfo->links_contact_name : "Sam's Rebates", 'maxlength="64"', true);
    }
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_links_contact_email_error == true) {
      echo tep_draw_input_field('links_contact_email', $lInfo->links_contact_email, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR;
    } elseif ($entry_links_contact_email_check_error == true) {
      echo tep_draw_input_field('links_contact_email', $lInfo->links_contact_email, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_CHECK_ERROR;
    } else {
      echo $lInfo->links_contact_email . tep_draw_hidden_field('links_contact_email');
    }
  } else {
    echo tep_draw_input_field('links_contact_email', tep_not_null($lInfo->links_contact_email) ? $lInfo->links_contact_email : 'service@samsrebates.com', 'maxlength="96"', true);
  }
?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_RECIPROCAL; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_LINKS_RECIPROCAL_URL; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_links_reciprocal_url_error == true) {
      echo tep_draw_input_field('links_reciprocal_url', $lInfo->links_reciprocal_url, 'maxlength="255"') . '&nbsp;' . ENTRY_LINKS_RECIPROCAL_URL_ERROR;
    } else {
      echo $lInfo->links_reciprocal_url . tep_draw_hidden_field('links_reciprocal_url');
    }
  } else {
    echo tep_draw_input_field('links_reciprocal_url', tep_not_null($lInfo->links_reciprocal_url) ? $lInfo->links_reciprocal_url : 'http://', 'maxlength="255"', true);
  }
?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="formAreaTitle"><?php echo CATEGORY_OPTIONS; ?></td>
      </tr>
      <tr>
        <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo ENTRY_LINKS_STATUS; ?></td>
            <td class="main">
<?php 
  $link_statuses = array();
  $links_status_array = array();
  $links_status_query = tep_db_query("select links_status_id, links_status_name from " . TABLE_LINKS_STATUS . " where language_id = '" . (int)$languages_id . "'");
    
  while ($links_status = tep_db_fetch_array($links_status_query)) {
    $link_statuses[] = array('id' => $links_status['links_status_id'],
                               'text' => $links_status['links_status_name']);
    $links_status_array[$links_status['links_status_id']] = $links_status['links_status_name'];
  }

  echo tep_draw_pull_down_menu('links_status', $link_statuses, $lInfo->links_status); 

  if ($action == 'edit' || $action == 'update') {
    echo '&nbsp;&nbsp;' . ENTRY_LINKS_NOTIFY_CONTACT;
    echo tep_draw_checkbox_field('links_notify');
  }
?></td>
          </tr>
          <tr>
            <td class="main"><?php echo ENTRY_LINKS_RATING; ?></td>
            <td class="main">
<?php
  if ($error == true) {
    if ($entry_links_rating_error == true) {
      echo tep_draw_input_field('links_rating', $lInfo->links_rating, 'size ="2" maxlength="2"') . '&nbsp;' . ENTRY_LINKS_RATING_ERROR;
    } else {
      echo $lInfo->links_rating . tep_draw_hidden_field('links_rating');
    }
  } else {
    echo tep_draw_input_field('links_rating', tep_not_null($lInfo->links_rating) ? $lInfo->links_rating : '0', 'size ="2" maxlength="2"', true);
  }
?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo (($action == 'edit') ? tep_image_submit('button_update.gif', IMAGE_UPDATE) : tep_image_submit('button_insert.gif', IMAGE_INSERT)) . ' <a href="' . tep_href_link(FILENAME_LINKS, tep_get_all_get_params(array('action'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr></form>
<?php
  } else {
?>
    <table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?> <input type="button" name="addlinks1" id="addlinks_ajax" value="Add New Link" onClick="showAddlink();"/></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="smallText" align="right"><?php // echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?></td>
          </tr>
          
          
          
            <tr>
                <td>
                <?php echo tep_draw_form('search', FILENAME_LINKS, '', 'get'); ?>
                    <div class="search">
                        <label>Date Added:</label>
                        <input type="text" class="time" name="added_start_date"  onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" style="ime-mode: disabled;" value="<?php echo $added_start_data ?>" />
                        至
                        <input type="text" class="time" name="added_end_date"  style="ime-mode: disabled;" onClick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"  value="<?php echo $added_end_data ?>"/>	
                        <input type="text" class="text"  name="search" onKeyDown="this.style.color='#333'" onBlur="if(this.value==''){this.value='输入网站名称搜索';this.style.color='#777'}" onFocus="if(this.value!='输入网站名称搜索'){this.style.color='#333'}else{this.value='';this.style.color='#333'}" value="输入网站名称搜索" style="color: #777;"/>
                        
                        <input type="submit" class="btn btnGrey" value="搜 索" />
                    </div>
                 </form>   
                </td>
                <td  width="30%" colspan="3" align="right" class="main">             
              <?php
                define('TEXT_TOP','---Select---');
                echo tep_draw_form('goto', 'links.php','','get');
                
                echo 'Go to:&nbsp;&nbsp;' . tep_draw_pull_down_menu('lcPath', tep_get_category_tree(), $HTTP_GET_VARS['lcPath'], 'onChange="this.form.submit();"');
                ?>
                <input type="hidden"  name="<?php echo tep_session_name();?>" value="<?php echo tep_session_id();?>">
                <?php
                echo '</form>';
                ?>
              </td>
            </tr>	
          
          
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
              <!-- heading title----------------------------------------------------------------->
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TITLE; ?><br><?php echo "<a  href=links.php?sortorder=title&".tep_get_all_get_params(array('cPath','selected_box','sortorder'))."><b>".Asc."</b></a>"; ?>&nbsp;&nbsp;<?php echo "<a  href=links.php?sortorder=title-desc&".tep_get_all_get_params(array('cPath','selected_box','sortorder'))."><b>".Desc."</b></a>"; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_URL; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_CLICKS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent">Date Added</td>
				<td class="dataTableHeadingContent">首页显示</td>
                 <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    

    function get_param($arr){
		if (is_array($arr)) {
			foreach($arr as $key => $val) {
				if (empty($val) && $val != '0') {
					unset($_GET[$key]);
				} else {
					$_GET[$key] = $val;
				}
			}
		}
		$rtn = array();
		if (is_array($_GET)) {
			foreach ($_GET as $key => $val) {
				$rtn[] = $key . '=' . $val;
			}
		}
		return join('&',$rtn);
	}

    $links_split = new splitPageResults($HTTP_GET_VARS['page'], 50, $links_query_raw, $links_query_numrows);
    
    $links_query = tep_db_query($links_query_raw);
    while ($links = tep_db_fetch_array($links_query)) {
      if ((!isset($HTTP_GET_VARS['lID']) || (isset($HTTP_GET_VARS['lID']) && ($HTTP_GET_VARS['lID'] == $links['links_id']))) && !isset($lInfo)) { 
        $categories_query = tep_db_query("select lcd.link_categories_name as links_category from " . TABLE_LINKS_TO_LINK_CATEGORIES . " l2lc left join " . TABLE_LINK_CATEGORIES_DESCRIPTION . " lcd on lcd.link_categories_id = l2lc.link_categories_id where l2lc.links_id = '" . (int)$links['links_id'] . "' and lcd.language_id = '" . (int)$languages_id . "'");       
        $category = tep_db_fetch_array($categories_query);        
        $lInfo_array = array_merge((array)$links, (array)$category);
        $lInfo = new objectInfo($lInfo_array);
      }

      if (isset($lInfo) && is_object($lInfo) && ($links['links_id'] == $lInfo->links_id)) {
        echo '          <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LINKS, tep_get_all_get_params(array('lID', 'action')) . 'lID=' . $lInfo->links_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_LINKS, tep_get_all_get_params(array('lID')) . 'lID=' . $links['links_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $links['links_title']; ?></td>
                <td class="dataTableContent"><?php echo $links['links_url']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $links['links_clicked']; ?></td>
                <td class="dataTableContent"><?php echo $links_status_array[$links['links_status']]; ?></td>
                  <td class="dataTableContent">
                  <?php 
                  $one = $links['links_date_added'];
                $date = ereg_replace('[^0-9]', '', $one);
                $date_year = substr($one,0,4);
                $date_month    = substr($one,5,2);
                $date_day = substr($one,8,2);
                $date_hour = substr($one,11,2);
                $date_minute = substr($one,14,2);
                $date_second = substr($one,17,2);
                
                $linkdate = str_replace('-','/',date('Y-m-d', mktime($date_hour, $date_minute, $date_second, $date_month, $date_day, $date_year)));
                
                  echo $linkdate; ?>
                  </td>
				  <td>
				  <?php 
				 // 添加 设置为首页显示的功能按钮区 by lwkai 2012-10-16
				  if ($links['display_on_home_page'] == '1') {
					?>
				  <img width="10" height="10" border="0" title=" Active " alt="Active" src="images/icon_status_green.gif">
				  <a href="<?php echo tep_href_link('links.php',get_param(array('set_home'=>'0','set_home_id'=>$links['links_id'])))?>"><img width="10" height="10" border="0" title=" Set Inactive " alt="Set Inactive" src="images/icon_status_red_light.gif"></a>
				  <?php } else {?>
				  <a href="<?php echo tep_href_link('links.php',get_param(array('set_home'=>'1','set_home_id'=>$links['links_id'])))?>"><img width="10" height="10" border="0" title=" Set Active " alt="Set Active" src="images/icon_status_green_light.gif"></a>
				  <img width="10" height="10" border="0" title=" Inactive " alt="Inactive" src="images/icon_status_red.gif">
				  <?php }
				  //添加 设置为首页显示的功能按钮区 end 
				  ?>
				  </td>
                <td class="dataTableContent" align="right"><?php if (isset($lInfo) && is_object($lInfo) && ($links['links_id'] == $lInfo->links_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_LINKS, tep_get_all_get_params(array('lID')) . 'lID=' . $links['links_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $links_split->display_count($links_query_numrows, 50, $HTTP_GET_VARS['page'], TEXT_DISPLAY_NUMBER_OF_LINKS); ?></td>
                    <td class="smallText" align="right"><?php echo $links_split->display_links($links_query_numrows, 50, MAX_DISPLAY_PAGE_LINKS, $HTTP_GET_VARS['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'lID'))); ?></td>
                  </tr>
                  <tr>
<?php
    if (isset($HTTP_GET_VARS['search']) && tep_not_null($HTTP_GET_VARS['search'])) {
?>
                    <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_LINKS) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                    <td align="right"><?php //echo '<a href="' . tep_href_link(FILENAME_LINKS, 'page=' . $HTTP_GET_VARS['page'] . '&action=new') . '">' . tep_image_button('button_new_link.gif', IMAGE_NEW_LINK) . '</a>'; ?></td>
<?php
    } else {
?>
                    <td align="right" colspan="2"></td>
<?php
    }
?>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'confirm':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_LINK . '</b>');

      $contents = array('form' => tep_draw_form('links', FILENAME_LINKS, tep_get_all_get_params(array('lID', 'action')) . 'lID=' . $lInfo->links_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><b>' . $lInfo->links_url . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_LINKS, tep_get_all_get_params(array('lID', 'action')) . 'lID=' . $lInfo->links_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    case 'check':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_CHECK_LINK . '</b>');

      $url = $lInfo->links_reciprocal_url;

      if (file($url)) {
        $file = fopen($url,'r');

        $link_check_status = false;

        while (!feof($file)) {
          $page_line = trim(fgets($file, 4096));
            if (eregi(LINKS_CHECK_PHRASE, $page_line)) {
            $link_check_status = true;
            break;
          }
        }

        fclose($file);

        if ($link_check_status == true) {
          $link_check_status_text = TEXT_INFO_LINK_CHECK_FOUND;
        } else {
          $link_check_status_text = TEXT_INFO_LINK_CHECK_NOT_FOUND;
        }
      } else {
        $link_check_status_text = TEXT_INFO_LINK_CHECK_ERROR;
      }

      $contents[] = array('text' => TEXT_INFO_LINK_CHECK_RESULT . ' ' . $link_check_status_text);
      $contents[] = array('text' => '<br><b>' . $lInfo->links_reciprocal_url . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_LINKS, tep_get_all_get_params(array('lID', 'action')) . 'lID=' . $lInfo->links_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
      break;
    default:
      if (isset($lInfo) && is_object($lInfo)) {
        $heading[] = array('text' => '<b>' . $lInfo->links_title . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_LINKS, tep_get_all_get_params(array('lID', 'action')) . 'lID=' . $lInfo->links_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_LINKS, tep_get_all_get_params(array('lID', 'action')) . 'lID=' . $lInfo->links_id . '&action=confirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_LINKS, tep_get_all_get_params(array('lID', 'action')) . 'lID=' . $lInfo->links_id . '&action=check') . '">' . tep_image_button('button_check_link.gif', IMAGE_CHECK_LINK) . '</a> <a href="' . tep_href_link(FILENAME_LINKS_CONTACT, 'link_partner=' . $lInfo->links_contact_email) . '">' . tep_image_button('button_email.gif', IMAGE_EMAIL) . '</a>');

        $contents[] = array('text' => '<br>' . TEXT_INFO_LINK_STATUS . ' '  . $links_status_array[$lInfo->links_status]);
        $contents[] = array('text' => '<br>' . TEXT_INFO_LINK_CATEGORY . ' '  . $lInfo->links_category);
        $contents[] = array('text' => '<br>' . tep_link_info_image($lInfo->links_image_url, $lInfo->links_title, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br>' . $lInfo->links_title);
        $contents[] = array('text' => '<br>' . TEXT_INFO_LINK_CONTACT_NAME . ' '  . $lInfo->links_contact_name);
        $contents[] = array('text' => '<br>' . TEXT_INFO_LINK_CONTACT_EMAIL . ' ' . $lInfo->links_contact_email);
        $contents[] = array('text' => '<br>' . TEXT_INFO_LINK_CLICK_COUNT . ' ' . $lInfo->links_clicked);
        $contents[] = array('text' => '<br>' . TEXT_INFO_LINK_DESCRIPTION . ' ' . $lInfo->links_description);
        $contents[] = array('text' => '<br>' . TEXT_DATE_LINK_CREATED . ' ' . tep_date_short($lInfo->links_date_added));

        if (tep_not_null($lInfo->links_last_modified)) {
          $contents[] = array('text' => '<br>' . TEXT_DATE_LINK_LAST_MODIFIED . ' ' . tep_date_short($lInfo->links_last_modified));
        }
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
<style>

.success .btnCenter {clear: both; height: 25px;    text-align: center; }
.success .prompt {line-height:24px; }
.success .promptImg { margin-left:10px; float:left; }
/*橙色button*/
.btnOrange {width: 80px;}
.btnOrange {background: url("/image/button_bg.gif") repeat scroll 0 0 transparent;border: 1px solid #F8B709;  font-weight: bold;}
/*灰色button*/
.btnGrey {width: 80px;}
.btnGrey {background: url("/image/button_bg.gif") repeat scroll 0 -46px transparent;border: 1px solid #E4E4E4;width: auto;}
.btn {cursor: pointer; display: inline-block; height: 23px;line-height: 23px;overflow: hidden;white-space: nowrap;}
</style>
<div id="popupBg" class="popupBg"></div>
<div class="popup" id="popupMap">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
<tr><td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td><td class="con" style="float:none;margin:0;width:auto;">
  <div class="popupCon" id="popupConMap" style="width:500px;">
    <div class="popupConTop" id="dragMap">
      <h3 style=" padding-left:0px;"><b><?= db_to_html("Add Links");?></b></h3>
      <span onClick="closePopup('popupMap')"></span>      
    </div>
    <div class="success">
        
        <div class="prompt">
            <div style="height:500px">
                
                <table width="100%" cellspacing="0" cellpadding="2" border="0">
      <tbody>
      <tr>
        <td><img width="1" height="10" border="0" alt="" src="images/pixel_trans.gif"></td>
      </tr>
      <tr><form onSubmit="return check_form();" method="post" action="" name="links__ajax"></form>        <td class="formAreaTitle">Website Details</td>
      </tr>
      <tr>
        <td class="formArea"><table cellspacing="2" cellpadding="2" border="0">
          <tbody><tr>
            <td class="main">Site Title:</td>
            <td class="main">
<input type="text" maxlength="64" name="links_title_ajax" id="links_title" onBlur="this.value = simplized(this.value);"></td>
          </tr>
          <tr>
            <td class="main">URL:</td>
            <td class="main">
<input type="text" maxlength="255" value="http://" name="links_url_ajax" id="links_url" onBlur="this.value = simplized(this.value);"></td>
          </tr>
    <input type="hidden" value="0" name="links_category_ajax" id="links_category">    
            <tr>
            <td class="main">Category:</td>
            <td class="main">
                <?php
    $categories_array = array();
    $categories_query = tep_db_query("select lcd.link_categories_id, lcd.link_categories_name from " . TABLE_LINK_CATEGORIES_DESCRIPTION . " lcd where language_id = '" . (int)$languages_id . "' order by lcd.link_categories_name");
    while ($categories_values = tep_db_fetch_array($categories_query)) {
      $categories_array[] = array('id' => $categories_values['link_categories_name'], 'text' => $categories_values['link_categories_name']);
    }
?>
    <?php 
    echo tep_draw_pull_down_menu('links_category_ajax', tep_get_link_category($languages_id), $lInfo->link_categories_id, "id='links_category_ajax'", true);
    ?>
            </td>
          </tr>
    
          <tr>
            <td class="main">Description:</td>
            <td class="main">
<textarea rows="5" cols="40" wrap="hard" name="links_description_ajax" id="links_description" onBlur="this.value = simplized(this.value)"></textarea>&nbsp;<span class="fieldRequired">* Required</span></td>
          </tr>
          <tr>
            <td class="main">Image URL:</td>
            <td class="main">
<input type="text" maxlength="255" value="http://" name="links_image_url_ajax" id="links_image_url" onBlur="this.value = simplized(this.value);"></td>
          </tr>
        </tbody></table></td>
      </tr>
      <tr>
        <td><img width="1" height="10" border="0" alt="" src="images/pixel_trans.gif"></td>
      </tr>
      <tr>
        <td class="formAreaTitle">Contact</td>
      </tr>
      <tr>
        <td class="formArea"><table cellspacing="2" cellpadding="2" border="0">
          <tbody><tr>
            <td class="main">Full Name:</td>
            <td class="main">
<input type="text" maxlength="64" value="Sam's Rebates" name="links_contact_name_ajax" id="links_contact_name" onBlur="this.value = simplized(this.value);"></td>
          </tr>
          <tr>
            <td class="main">E-Mail Address:</td>
            <td class="main">
<input type="text" maxlength="96" value="service@samsrebates.com" name="links_contact_email_ajax"  id="links_contact_email" onBlur="this.value = simplized(this.value);"></td>
          </tr>
        </tbody></table></td>
      </tr>
      <tr>
        <td><img width="1" height="10" border="0" alt="" src="images/pixel_trans.gif"></td>
      </tr>
      <tr>
        <td class="formAreaTitle">Reciprocal Page Details</td>
      </tr>
      <tr>
        <td class="formArea"><table cellspacing="2" cellpadding="2" border="0">
          <tbody><tr>
            <td class="main">Reciprocal Page:</td>
            <td class="main">
<input type="text" maxlength="255" value="http://" name="links_reciprocal_url_ajax" id="links_reciprocal_url" onBlur="this.value = simplized(this.value);"></td>
          </tr>
        </tbody></table></td>
      </tr>
      <tr>
        <td><img width="1" height="10" border="0" alt="" src="images/pixel_trans.gif"></td>
      </tr>
      <tr>
        <td class="formAreaTitle">Options</td>
      </tr>
      <tr>
        <td class="formArea"><table cellspacing="2" cellpadding="2" border="0">
          <tbody><tr>
            <td class="main">Status:</td>
            <td class="main">
<select name="links_status_ajax" id="links_status"><option value="1">Pending</option><option value="2">Approved</option><option value="3">Disabled</option></select>
    &nbsp;&nbsp;Notify Contact:
<input type="checkbox" name="links_notify" id='links_notify_for_ajax'>
</td>
          </tr>
          <tr>
            <td class="main">Rating:</td>
            <td class="main">
<input type="text" maxlength="2" size="2" value="0" name="links_rating_ajax" id="links_rating" onBlur="this.value = simplized(this.value);"></td>
          </tr>
        </tbody></table></td>
      </tr>
      <tr>
        <td><img width="1" height="10" border="0" alt="" src="images/pixel_trans.gif"></td>
      </tr>
      
    </tbody></table>
            
            </div>
        </div>
        <div class="btnCenter">
            <span id="ajax_loging"></span>&nbsp;
            <a id="PopupErrorClose" href="javascript:" class="btn btnOrange" style="width:80px; height:25px" onClick="addlinks();"> Add </a>&nbsp;
            <a id="PopupErrorClose" href="javascript:closePopup('popupMap');" class="btn btnGrey" style="width:80px; height:25px"> Cancel </a>
        </div>
    </div>


</div>
</td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
</table>
</div>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/popup.js"></script>
<script type="text/javascript">
function showAddlink(){
    showPopup('popupMap','popupConMap','50','true');
}

function addlinks(){
    var links_title =  jQuery("#links_title").val();
    var links_url =  jQuery("#links_url").val();
    var links_category = jQuery("#links_category_ajax").val();   
    var links_description =  jQuery("#links_description").val();
    var links_image_url =  jQuery("#links_image_url").val();
    var links_contact_name =  jQuery("#links_contact_name").val();
    var links_contact_email =  jQuery("#links_contact_email").val();
    var links_reciprocal_url =  jQuery("#links_reciprocal_url").val();
    var links_status =  jQuery("#links_status").val();
    var links_rating =  jQuery("#links_rating").val();
    var links_notify_for_ajax = jQuery("#links_notify_for_ajax").attr("checked");
    var links_notify = '';
    if (links_notify_for_ajax){
        links_notify = 'on';
    }
    var error_msg = '';
    if (links_title.length < 2){
        error_msg = 'Link title must contain a minimum of 2 characters.\r\n';
        alert(error_msg);
        return false;
    }
    if (links_url.length == 0 || !CheckUrl(links_url)){
        error_msg = 'URL must contain a minimum of 10 characters.\r\n';
        alert(error_msg);
        return false;
    }
    if (links_description.length < 10){
        error_msg = 'Description must contain a minimum of 10 characters.\r\n';
        alert(error_msg);
        return false;
    }
    
    if (links_image_url.length == 0){
        error_msg = 'Image url must contain a minimum of 10 characters.\r\n';
        alert(error_msg);
        return false;
    }
    
    if (links_contact_name.length == 0){
        error_msg = 'Your Full Name must contain a minimum of 2 characters.\r\n';
        alert(error_msg);
        return false;
    }
    
    if (links_contact_email.length == 0 || !email(links_contact_email)){
        error_msg = 'Email must contain a minimum of 6 characters.\r\n';
        alert(error_msg);
        return false;
    }
    
    if (links_reciprocal_url.length == 0 || !CheckUrl(links_reciprocal_url)){
        error_msg = 'Reciprocal page must contain a minimum of 10 characters.\r\n';
        alert(error_msg);
        return false;
    }
    
    jQuery.ajax({
                url: "<?php echo tep_href_link('links.php', 'ajax=true') ?>",
                data: "action=insert&ajax=true&links_title="+links_title+"&links_url="+links_url+"&links_description="+links_description+"&links_image_url="+links_image_url+"&links_contact_name="+links_contact_name+"&links_contact_email="+links_contact_email+"&links_reciprocal_url="+links_reciprocal_url+"&links_status="+links_status+"&links_rating="+links_rating+"&links_category="+links_category+"&links_notify="+links_notify,
                type: "POST",
                cache: false,
                dataType: "html",
                beforeSend: function(){
                    jQuery("#ajax_loging").html("<img src='/image/ajax-loading.gif'/>");
                },
                success: function (data){                     
                    if (data == 1){
                        jQuery("#ajax_loging").html("");
                        window.location.href = 'links.php';
                    }
                },
                error: function (msg){
                    alert(msg);
                }
                
        })
    
}
// reg email 
function email(value){
   var reg = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i;
        return reg.test(value)    
}
// reg url
function CheckUrl(str) {
    var RegUrl = new RegExp();
    RegUrl.compile("^[A-Za-z]+://[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+$");
    if (!RegUrl.test(str)) {
        return false;
    }
    return true;
}
</script>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>

</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
