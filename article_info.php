<?php
/*
  $Id: article_info.php, v1.0 2003/12/04 12:00:00 ra Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_ARTICLE_INFO);
   $valid_article = "false";
   if(isset($_POST['articles_id']))
   {
   	$HTTP_GET_VARS['articles_id'] = $_POST['articles_id'];
   }
  $article_check_query = tep_db_query("select count(*) as total from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_DESCRIPTION . " ad where a.articles_status = '1' and a.articles_id = '" . (int)$HTTP_GET_VARS['articles_id'] . "' and ad.articles_id = a.articles_id and ad.language_id = '" . (int)$languages_id . "'");
  $article_check = tep_db_fetch_array($article_check_query);


/*foreach($_POST as $key=>$val)
{
	echo "$key=>$val";
}*/
if (isset($_POST['to_email_address'])) 
  {   
    $error = "false";
	$tell_articles_id = $_POST['articles_id'];
    $_POST['to_email_address'] = preg_replace( "/\n/", " ", $_POST['to_email_address'] );
    //$_POST['to_name'] = preg_replace( "/\n/", " ", $_POST['to_name'] );
    $_POST['to_email_address'] = preg_replace( "/\r/", " ", $_POST['to_email_address'] );
    //$_POST['to_name'] = preg_replace( "/\r/", " ", $_POST['to_name'] );
    $_POST['to_email_address'] = str_replace("Content-Type:","",$_POST['to_email_address']);
    //$_POST['to_name'] = str_replace("Content-Type:","",$_POST['to_name']);
    
    $to_email_address = tep_db_prepare_input($_POST['to_email_address']);
    //exit;
	$to_name_explode = explode('@',$to_email_address);
	//exit;
	$to_name = $to_name_explode[0];
    $from_email_address = tep_db_prepare_input($_POST['from_email_address']);
	$from_name_explode = explode('@',$from_email_address);
	$from_name = $from_name_explode[0];
    //$from_name = tep_db_prepare_input($HTTP_GET_VARS['from_name']);
    //$message = tep_db_prepare_input($HTTP_GET_VARS['message']);
    
       /*if (empty($from_name)) {
          $error = "true";
          $messageStack->add('friend', ERROR_FROM_NAME);
        }*/

        if (!tep_validate_email($from_email_address)) {
          $error = "true";
          $messageStack->add('friend', ERROR_FROM_ADDRESS);
        }
    
        /*if (empty($to_name)) {
          $error = "true";
          $messageStack->add('friend', ERROR_TO_NAME);
        }*/
    
        if (!tep_validate_email($to_email_address)) {
          $error = "true";
          $messageStack->add('friend', ERROR_TO_ADDRESS);
        }
if($error=="false")
{
$article_info_query = tep_db_query("select p.articles_seo_url, pd.articles_name from " . TABLE_ARTICLES . " p, " . TABLE_ARTICLES_DESCRIPTION . " pd where p.articles_status = '1' and p.articles_id = '" . (int)$_POST['articles_id'] . "' and p.articles_id = pd.articles_id and pd.language_id = '" . (int)$languages_id . "'");
    if (tep_db_num_rows($article_info_query)) {
      $valid_article = "true";
      $article_info = tep_db_fetch_array($article_info_query);
    } else {
     $valid_article = "false";
 //    tep_redirect(tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $tell_articles_id));
    }
          $email_subject = sprintf(TEXT_EMAIL_SUBJECT, $from_name, STORE_NAME);
		  //exit;
          $email_body_article = sprintf(TEXT_EMAIL_INTRO, $to_name, $from_name, $article_info['articles_name'], STORE_NAME) . "\n\n";
          if (tep_not_null($message)) {
            $email_body_article .= $message . "\n\n";
          }
     if (TELL_ARTICLE_EMAIL_USE_HTML == 'false') {
            //$email_body_article .= TEXT_EMAIL_LINK_TEXT . '<a href="' . HTTP_SERVER  . DIR_WS_CATALOG . FILENAME_ARTICLE_INFO . '?articles_id='.$tell_articles_id .'">' .  HTTP_SERVER  . DIR_WS_CATALOG . FILENAME_ARTICLE_INFO . '?articles_id='. $tell_articles_id . '</a>' . "\n\n";
			$email_body_article .= TEXT_EMAIL_LINK_TEXT . '<a href="' . HTTP_SERVER  . DIR_WS_CATALOG .'articles/'. $article_info['articles_seo_url'] . '">' .  HTTP_SERVER  . DIR_WS_CATALOG .'articles/'. $article_info['articles_seo_url'] . '</a>' . "\n\n";
      $email_body_article .= TEXT_EMAIL_SIGNATURE. STORE_NAME . "\n" . '<a href="' . HTTP_SERVER  . DIR_WS_CATALOG .'">' .  HTTP_SERVER  . DIR_WS_CATALOG . '</a>';
 
    }else{ 
            $email_body_article .= TEXT_EMAIL_LINK . '<a href="' . HTTP_SERVER  . DIR_WS_CATALOG .'articles/'. $article_info['articles_seo_url'] . '">' .  HTTP_SERVER  . DIR_WS_CATALOG .'articles/'. $article_info['articles_seo_url'] . '</a>' . "\n\n";
      $email_body_article .= TEXT_EMAIL_SIGNATURE. STORE_NAME . "\n" . '<a href="' . HTTP_SERVER  . DIR_WS_CATALOG .'">' .  HTTP_SERVER  . DIR_WS_CATALOG . '</a>';
      
                }
                
         $mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));
  
 
          if (TELL_ARTICLE_EMAIL_USE_HTML == 'false') {
              $mimemessage->add_text($email_body_article);
           } else {
              $mimemessage->add_html($email_body_article);
           }                
  
            $mimemessage->build_message();
            $mimemessage->send($to_name, $to_email_address, $from_name, $from_email_address, $email_subject);

/*echo $to_name;
echo '<br />';
echo $to_email_address;
echo '<br />';
echo $from_name;
echo '<br />';
echo $from_email_address;
echo '<br />';
echo $email_subject;
echo '<br /><br />';
echo $email_body_article;*/
//exit;

  	$messageStack->add_session('header', sprintf(TEXT_EMAIL_SUCCESSFUL_SENT, $article_info['articles_name'], tep_output_string_protected($to_name)), 'success');  
    tep_redirect(tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $tell_articles_id));
	
 }
}         

  $content = CONTENT_ARTICLE_INFO;
 
  $javascript = $content . '.js';
  
  require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);  
  require(DIR_FS_INCLUDES . 'application_bottom.php'); ?>
