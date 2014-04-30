<?php
/*
  $Id: articles.php, v1.0 2003/12/04 12:00:00 ra Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  // 备注添加删除
  if($_GET['ajax']=="true"){
  	include DIR_FS_CLASSES . 'Remark.class.php';
  	$remark = new Remark('articles');
  	$remark->checkAction($_GET['action'], $login_id);//添加删除动作，统一在方法里面处理了
  }
  $action = (isset($HTTP_GET_VARS['action']) ? $HTTP_GET_VARS['action'] : '');

  if (tep_not_null($action)) {
    switch ($action) {
      case 'setflag':
        if ( ($HTTP_GET_VARS['flag'] == '0') || ($HTTP_GET_VARS['flag'] == '1') ) {
          if (isset($HTTP_GET_VARS['aID'])) {
            tep_set_article_status($HTTP_GET_VARS['aID'], $HTTP_GET_VARS['flag']);
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('topics');
          }
        }

        tep_redirect(tep_href_link(FILENAME_ARTICLES, 'tPath=' . $HTTP_GET_VARS['tPath'] . '&aID=' . $HTTP_GET_VARS['aID']));
        break;
      case 'new_topic':
      case 'edit_topic':
        $HTTP_GET_VARS['action']=$HTTP_GET_VARS['action'] . '_ACD';
        break;
      case 'insert_topic':
      case 'update_topic':
        if ( ($HTTP_POST_VARS['edit_x']) || ($HTTP_POST_VARS['edit_y']) ) {
          $HTTP_GET_VARS['action'] = 'edit_topic_ACD';
        } else {
        if (isset($HTTP_POST_VARS['topics_id'])) $topics_id = tep_db_prepare_input($HTTP_POST_VARS['topics_id']);
          if ($topics_id == '') {
            $topics_id = tep_db_prepare_input($HTTP_GET_VARS['tID']);
            }
        $sort_ordera = tep_db_prepare_input($HTTP_POST_VARS['sort_order']);

        $sql_data_array = array('sort_order' => $sort_ordera);

        if ($action == 'insert_topic') {
          $insert_sql_data = array('parent_id' => $current_topic_id,
                                   'date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          tep_db_perform(TABLE_TOPICS, $sql_data_array);

          $topics_id = tep_db_insert_id();
        } elseif ($action == 'update_topic') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          tep_db_perform(TABLE_TOPICS, $sql_data_array, 'update', "topics_id = '" . (int)$topics_id . "'");
        }

        $languages = tep_get_languages(true);
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {

          $language_id = $languages[$i]['id'];

          $sql_data_array = array('topics_name' => tep_db_prepare_input($HTTP_POST_VARS['topics_name'][$language_id]),
                                  'topics_heading_title' => tep_db_prepare_input($HTTP_POST_VARS['topics_heading_title'][$language_id]),
                                  'topics_description' => tep_db_prepare_input($HTTP_POST_VARS['topics_description'][$language_id]));

          if ($action == 'insert_topic') {
            $insert_sql_data = array('topics_id' => $topics_id,
                                     'language_id' => $languages[$i]['id']);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_TOPICS_DESCRIPTION, $sql_data_array);
          } elseif ($action == 'update_topic') {
            tep_db_perform(TABLE_TOPICS_DESCRIPTION, $sql_data_array, 'update', "topics_id = '" . (int)$topics_id . "' and language_id = '" . (int)$languages[$i]['id'] . "'");
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('topics');
        }

        tep_redirect(tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&tID=' . $topics_id));
        break;
        }
      case 'delete_topic_confirm':
        if (isset($HTTP_POST_VARS['topics_id'])) {
          $topics_id = tep_db_prepare_input($HTTP_POST_VARS['topics_id']);

          $topics = tep_get_topic_tree($topics_id, '', '0', '', true);
          $articles = array();
          $articles_delete = array();

          for ($i=0, $n=sizeof($topics); $i<$n; $i++) {
            $article_ids_query = tep_db_query("select articles_id from " . TABLE_ARTICLES_TO_TOPICS . " where topics_id = '" . (int)$topics[$i]['id'] . "'");

            while ($article_ids = tep_db_fetch_array($article_ids_query)) {
              $articles[$article_ids['articles_id']]['topics'][] = $topics[$i]['id'];
            }
          }

          reset($articles);
          while (list($key, $value) = each($articles)) {
            $topic_ids = '';

            for ($i=0, $n=sizeof($value['topics']); $i<$n; $i++) {
              $topic_ids .= "'" . (int)$value['topics'][$i] . "', ";
            }
            $topic_ids = substr($topic_ids, 0, -2);

            $check_query = tep_db_query("select count(*) as total from " . TABLE_ARTICLES_TO_TOPICS . " where articles_id = '" . (int)$key . "' and topics_id not in (" . $topic_ids . ")");
            $check = tep_db_fetch_array($check_query);
            if ($check['total'] < '1') {
              $articles_delete[$key] = $key;
            }
          }

// removing topics can be a lengthy process
          tep_set_time_limit(0);
          for ($i=0, $n=sizeof($topics); $i<$n; $i++) {
            tep_remove_topic($topics[$i]['id']);
          }

          reset($articles_delete);
          while (list($key) = each($articles_delete)) {
            tep_remove_article($key);
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('topics');
        }

        tep_redirect(tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath));
        break;
      case 'delete_article_confirm':
        if (isset($HTTP_POST_VARS['articles_id']) && isset($HTTP_POST_VARS['article_topics']) && is_array($HTTP_POST_VARS['article_topics'])) {
          $article_id = tep_db_prepare_input($HTTP_POST_VARS['articles_id']);
          $article_topics = $HTTP_POST_VARS['article_topics'];

          for ($i=0, $n=sizeof($article_topics); $i<$n; $i++) {
            tep_db_query("delete from " . TABLE_ARTICLES_TO_TOPICS . " where articles_id = '" . (int)$article_id . "' and topics_id = '" . (int)$article_topics[$i] . "'");
          }

          $article_topics_query = tep_db_query("select count(*) as total from " . TABLE_ARTICLES_TO_TOPICS . " where articles_id = '" . (int)$article_id . "'");
          $article_topics = tep_db_fetch_array($article_topics_query);

          if ($article_topics['total'] == '0') {
            tep_remove_article($article_id);
          }
        }

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('topics');
        }

        tep_redirect(tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath));
        break;
      case 'move_topic_confirm':
        if (isset($HTTP_POST_VARS['topics_id']) && ($HTTP_POST_VARS['topics_id'] != $HTTP_POST_VARS['move_to_topic_id'])) {
          $topics_id = tep_db_prepare_input($HTTP_POST_VARS['topics_id']);
          $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_topic_id']);

          $path = explode('_', tep_get_generated_topic_path_ids($new_parent_id));

          if (in_array($topics_id, $path)) {
            $messageStack->add_session('search', ERROR_CANNOT_MOVE_TOPIC_TO_PARENT, 'error');

            tep_redirect(tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&tID=' . $topics_id));
          } else {
            tep_db_query("update " . TABLE_TOPICS . " set parent_id = '" . (int)$new_parent_id . "', last_modified = now() where topics_id = '" . (int)$topics_id . "'");

            if (USE_CACHE == 'true') {
              tep_reset_cache_block('topics');
            }

            tep_redirect(tep_href_link(FILENAME_ARTICLES, 'tPath=' . $new_parent_id . '&tID=' . $topics_id));
          }
        }

        break;
      case 'move_article_confirm':
        $articles_id = tep_db_prepare_input($HTTP_POST_VARS['articles_id']);
        $new_parent_id = tep_db_prepare_input($HTTP_POST_VARS['move_to_topic_id']);

        $duplicate_check_query = tep_db_query("select count(*) as total from " . TABLE_ARTICLES_TO_TOPICS . " where articles_id = '" . (int)$articles_id . "' and topics_id = '" . (int)$new_parent_id . "'");
        $duplicate_check = tep_db_fetch_array($duplicate_check_query);
        if ($duplicate_check['total'] < 1) tep_db_query("update " . TABLE_ARTICLES_TO_TOPICS . " set topics_id = '" . (int)$new_parent_id . "' where articles_id = '" . (int)$articles_id . "' and topics_id = '" . (int)$current_topic_id . "'");

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('topics');
        }

        tep_redirect(tep_href_link(FILENAME_ARTICLES, 'tPath=' . $new_parent_id . '&aID=' . $articles_id));
        break;
      case 'insert_article':
      case 'update_article':
        if (isset($HTTP_POST_VARS['edit_x']) || isset($HTTP_POST_VARS['edit_y'])) {
          $action = 'new_article';
        } else {
          if (isset($HTTP_GET_VARS['aID'])) $articles_id = tep_db_prepare_input($HTTP_GET_VARS['aID']);
          $articles_date_available = tep_db_prepare_input($HTTP_POST_VARS['articles_date_available']);

          $articles_date_available = (date('Y-m-d') < $articles_date_available) ? $articles_date_available : 'null';
		  
		  /*$articles_image = new upload('articles_image');
          $articles_image->set_destination(DIR_FS_CATALOG_IMAGES);
          if ($articles_image->parse() && $articles_image->save()) {
          $articles_image_name = $articles_image->filename;
		  }*/

          //$articles_seo_url = str_replace(HTTP_CATALOG_SERVER,'',tep_db_prepare_input($HTTP_POST_VARS['articles_seo_url']));
		  //$db_seo_url = 
		  $sql_data_array = array('articles_date_available' => $articles_date_available,
                                  'articles_status' => tep_db_prepare_input($HTTP_POST_VARS['articles_status']),
                                  'authors_id' => tep_db_prepare_input($HTTP_POST_VARS['authors_id']),
								  'articles_image' => tep_db_prepare_input($HTTP_POST_VARS['articles_image']),
								  'articles_seo_url' => tep_db_prepare_input($HTTP_POST_VARS['articles_seo_url']),
								  'article_type' => tep_db_prepare_input($HTTP_POST_VARS['article_type'])
								  //'current_topic_id' => tep_db_prepare_input($HTTP_POST_VARS['current_topic_id'])
								  );

          if ($action == 'insert_article') {
            // If expected article then articles_date _added becomes articles_date_available
            if (isset($HTTP_POST_VARS['articles_date_available']) && tep_not_null($HTTP_POST_VARS['articles_date_available'])) {
              $insert_sql_data = array('articles_date_added' => tep_db_prepare_input($HTTP_POST_VARS['articles_date_available']));
            } else {
              $insert_sql_data = array('articles_date_added' => 'now()');
            }
            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
			
			//print_r($sql_data_array);exit();

            tep_db_perform(TABLE_ARTICLES, $sql_data_array);
            $articles_id = tep_db_insert_id();

            tep_db_query("insert into " . TABLE_ARTICLES_TO_TOPICS . " (articles_id, topics_id) values ('" . (int)$articles_id . "', '" . (int)$current_topic_id . "')");
          } elseif ($action == 'update_article') {
            $update_sql_data = array('articles_last_modified' => 'now()');
			
			/*if($HTTP_POST_VARS['articles_image'])
			{
			  $articles_image = new upload('articles_image');
			  $articles_image->set_destination(DIR_FS_CATALOG_IMAGES);
			  if ($articles_image->parse() && $articles_image->save()) {
			  $articles_image_name = $articles_image->filename;
			  }
			 }*/
            // If expected article then articles_date _added becomes articles_date_available
            if (isset($HTTP_POST_VARS['articles_date_available']) && tep_not_null($HTTP_POST_VARS['articles_date_available'])) {
              $update_sql_data = array('articles_date_added' => tep_db_prepare_input($HTTP_POST_VARS['articles_date_available']));
            }

            $sql_data_array = array_merge($sql_data_array, $update_sql_data);
/*foreach($HTTP_POST_VARS as $key=>$val)
			{
				echo "$key=>$val";
			}
			exit;*/
            tep_db_perform(TABLE_ARTICLES, $sql_data_array, 'update', "articles_id = '" . (int)$articles_id . "'");
          }

          $languages = tep_get_languages(true);
          for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
            $language_id = $languages[$i]['id'];

            $sql_data_array = array(
									'articles_name' => tep_db_prepare_input($HTTP_POST_VARS['articles_name'][$language_id]),
                                    'articles_description' => tep_db_prepare_input($HTTP_POST_VARS['articles_description'][$language_id]),
                                    'articles_url' => tep_db_prepare_input($HTTP_POST_VARS['articles_url'][$language_id]),
                                    'articles_head_title_tag' => tep_db_prepare_input($HTTP_POST_VARS['articles_head_title_tag'][$language_id]),
                                    'articles_head_desc_tag' => tep_db_prepare_input($HTTP_POST_VARS['articles_head_desc_tag'][$language_id]),
                                    'articles_head_keywords_tag' => tep_db_prepare_input($HTTP_POST_VARS['articles_head_keywords_tag'][$language_id]));

            if ($action == 'insert_article') {
              $insert_sql_data = array('articles_id' => $articles_id,
                                       'language_id' => $language_id,
									   );

              $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
			  
			  /*foreach($sql_data_array as $key=>$val)
			  {
			  	echo "$key=>$val";
				echo "<br />";
			  }*/

              tep_db_perform(TABLE_ARTICLES_DESCRIPTION, $sql_data_array);
            } elseif ($action == 'update_article') {
              tep_db_perform(TABLE_ARTICLES_DESCRIPTION, $sql_data_array, 'update', "articles_id = '" . (int)$articles_id . "' and language_id = '" . (int)$language_id . "'");
            }
          }
		  //exit;

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('topics');
          }

          tep_redirect(tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&aID=' . $articles_id));
        }
        break;
      case 'copy_to_confirm':
        if (isset($HTTP_POST_VARS['articles_id']) && isset($HTTP_POST_VARS['topics_id'])) {
          $articles_id = tep_db_prepare_input($HTTP_POST_VARS['articles_id']);
          $topics_id = tep_db_prepare_input($HTTP_POST_VARS['topics_id']);

          if ($HTTP_POST_VARS['copy_as'] == 'link') {
            if ($topics_id != $current_topic_id) {
              $check_query = tep_db_query("select count(*) as total from " . TABLE_ARTICLES_TO_TOPICS . " where articles_id = '" . (int)$articles_id . "' and topics_id = '" . (int)$topics_id . "'");
              $check = tep_db_fetch_array($check_query);
              if ($check['total'] < '1') {
                tep_db_query("insert into " . TABLE_ARTICLES_TO_TOPICS . " (articles_id, topics_id) values ('" . (int)$articles_id . "', '" . (int)$topics_id . "')");
              }
            } else {
              $messageStack->add_session('search', ERROR_CANNOT_LINK_TO_SAME_TOPIC, 'error');
            }
          } elseif ($HTTP_POST_VARS['copy_as'] == 'duplicate') {
            $article_query = tep_db_query("select articles_date_available, authors_id from " . TABLE_ARTICLES . " where articles_id = '" . (int)$articles_id . "'");
            $article = tep_db_fetch_array($article_query);

            tep_db_query("insert into " . TABLE_ARTICLES . " (articles_date_added, articles_date_available, articles_status, authors_id) values (now(), '" . tep_db_input($article['articles_date_available']) . "', '0', '" . (int)$article['authors_id'] . "')");
            $dup_articles_id = tep_db_insert_id();

            $description_query = tep_db_query("select language_id, articles_name, articles_description, articles_url, articles_head_title_tag, articles_head_desc_tag, articles_head_keywords_tag from " . TABLE_ARTICLES_DESCRIPTION . " where articles_id = '" . (int)$articles_id . "'");
            while ($description = tep_db_fetch_array($description_query)) {
              tep_db_query("insert into " . TABLE_ARTICLES_DESCRIPTION . " (articles_id, language_id, articles_name, articles_description, articles_url, articles_head_title_tag, articles_head_desc_tag, articles_head_keywords_tag, articles_viewed) values ('" . (int)$dup_articles_id . "', '" . (int)$description['language_id'] . "', '" . tep_db_input($description['articles_name']) . "', '" . tep_db_input($description['articles_description']) . "', '" . tep_db_input($description['articles_url']) . "', '" . tep_db_input($description['articles_head_title_tag']) . "', '" . tep_db_input($description['articles_head_desc_tag']) . "', '" . tep_db_input($description['articles_head_keywords_tag']) . "', '0')");
            }

            tep_db_query("insert into " . TABLE_ARTICLES_TO_TOPICS . " (articles_id, topics_id) values ('" . (int)$dup_articles_id . "', '" . (int)$topics_id . "')");
            $articles_id = $dup_articles_id;
          }

          if (USE_CACHE == 'true') {
            tep_reset_cache_block('topics');
          }
        }

        tep_redirect(tep_href_link(FILENAME_ARTICLES, 'tPath=' . $topics_id . '&aID=' . $articles_id));
        break;
    }
  }

// check if the catalog image directory exists
  if (is_dir(DIR_FS_CATALOG_IMAGES)) {
    if (!is_writeable(DIR_FS_CATALOG_IMAGES)) $messageStack->add('search', ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add('search', ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }

// check if the authors table is empty.  articles must be assigned an author to be shown in storefront.        
  $authors_query = tep_db_query("select * from " . TABLE_AUTHORS);
  if (tep_db_num_rows($authors_query) == 0){
    $add_str = '<a href="' . tep_href_link(FILENAME_AUTHORS, '&action=new') . '">' . tep_image_button('button_new_author.gif', IMAGE_NEW_AUTHOR) . '</a>';
    $messageStack->add('search', TEXT_WARNING_NO_AUTHORS, 'warning');
  }else{
    $add_str = '<a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&action=new_article') . '">' . tep_image_button('button_new_article.gif', IMAGE_NEW_ARTICLE) . '</a>';
  }
?>
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>" />
<title><?php echo HEADING_TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css" />
<script type="text/javascript" src="includes/javascript/general.js"></script>
<script type="text/javascript" src="includes/javascript/menu.js"></script>
<!-- Tabs code -->
<script type="text/javascript" src="includes/javascript/tabpane/local/webfxlayout.js"></script>
<link type="text/css" rel="stylesheet" href="includes/javascript/tabpane/tab.webfx.css">
<style type="text/css">
.dynamic-tab-pane-control h2 {
  text-align: center;
  width:    auto;
}

.dynamic-tab-pane-control h2 a {
  display:  inline;
  width:    auto;
}

.dynamic-tab-pane-control a:hover {
  background: transparent;
}
</style>
<script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="includes/javascript/tabpane/tabpane.js"></script>
<!-- End Tabs -->
<?php include('includes/javascript/editor.php');?>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<?php
//echo $login_id;
include DIR_FS_CLASSES . 'Remark.class.php';
$listrs = new Remark('articles');
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
   //----- new_topic / edit_topic  -----
  if ($HTTP_GET_VARS['action'] == 'new_topic_ACD' || $HTTP_GET_VARS['action'] == 'edit_topic_ACD') {
    if ( ($HTTP_GET_VARS['tID']) && (!$HTTP_POST_VARS) ) {
      $topics_query = tep_db_query("select t.topics_id, td.topics_name, td.topics_heading_title, td.topics_description, t.parent_id, t.sort_order, t.date_added, t.last_modified from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.topics_id = '" . $HTTP_GET_VARS['tID'] . "' and t.topics_id = td.topics_id and td.language_id = '" . $languages_id . "' order by t.sort_order, td.topics_name");
      $topic = tep_db_fetch_array($topics_query);

      $tInfo = new objectInfo($topic);
    } elseif ($HTTP_POST_VARS) {
      $tInfo = new objectInfo($HTTP_POST_VARS);
      $topics_name = $HTTP_POST_VARS['topics_name'];
      $topics_heading_title = $HTTP_POST_VARS['topics_heading_title'];
      $topics_description = $HTTP_POST_VARS['topics_description'];
      $topics_url = $HTTP_POST_VARS['topics_url'];
    } else {
      $tInfo = new objectInfo(array());
    }

    $languages = tep_get_languages(1);

    $text_new_or_edit = ($HTTP_GET_VARS['action']=='new_topic_ACD') ? TEXT_INFO_HEADING_NEW_TOPIC : TEXT_INFO_HEADING_EDIT_TOPIC;
?>
  <?php echo tep_draw_form('new_topic', FILENAME_ARTICLES, 'tPath=' . $tPath . '&tID=' . $HTTP_GET_VARS['tID'] . '&action=new_topic_preview', 'post', 'enctype="multipart/form-data"'); ?>

   <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf($text_new_or_edit, tep_output_generated_topic_path($current_topic_id)); ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
</table>
    <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_EDIT_SORT_ORDER; ?></td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_input_field('sort_order', $tInfo->sort_order, 'size="2"'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table>
<?php 
// Load Editor
echo tep_load_html_editor();
  for ($i=0; $i<sizeof($languages); $i++) {
      $topics_description_elements .= 'topics_description[' . $languages[$i]['id'] . '],'; 
    } 
echo tep_insert_html_editor($topics_description_elements);
?>
<div class="tab-pane" id="tabPane1">
<script type="text/javascript">tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
</script>
<?php
    for ($i=0; $i<sizeof($languages); $i++) {
?>
              <div class="tab-page" id="<?php echo $languages[$i]['name'];?>">
                <h2 class="tab"><nobr><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'],'align="absmiddle" style="height:16px; width:30px;"') . '&nbsp;' .$languages[$i]['name'];?></nobr></h2>
                <script type="text/javascript">tp1.addTabPage( document.getElementById( "<?php echo $languages[$i]['name'];?>" ) );</script>
                <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="tab table">
                  <tr>
                    <td valign="top">
<table border="0" cellspacing="0" cellpadding="2" width="100%">
<?php
//    for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
            <td class="main"><?php echo TEXT_EDIT_TOPICS_NAME; ?></td>
            <td class="main"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('topics_name[' . $languages[$i]['id'] . ']', (($topics_name[$languages[$i]['id']]) ? stripslashes($topics_name[$languages[$i]['id']]) : tep_get_topic_name($tInfo->topics_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
 //   }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
 // for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
            <td class="main"><?php echo TEXT_EDIT_TOPICS_HEADING_TITLE; ?></td>
            <td class="main"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('topics_heading_title[' . $languages[$i]['id'] . ']', (($topics_name[$languages[$i]['id']]) ? stripslashes($topics_name[$languages[$i]['id']]) : tep_get_topic_heading_title($tInfo->topics_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
//  }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
  //  for ($i=0; $i<sizeof($languages); $i++) {
?>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_EDIT_TOPICS_DESCRIPTION; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('topics_description[' . $languages[$i]['id'] . ']', 'soft', '70', '20', (($topics_description[$languages[$i]['id']]) ? stripslashes($topics_description[$languages[$i]['id']]) : tep_get_topic_description($tInfo->topics_id, $languages[$i]['id'])),' style="width: 100%" mce_editable="true"'); ?></td>
              </tr>
            </table></td>
          </tr>

<?php
//    }
?>
</table>
</td>
                  </tr>
                </table>
              </div>
<?php
    }
?>
            </div>
<script type="text/javascript">
//<![CDATA[
setupAllTabs();
//]]>
</script>
    </td>
      </tr>
</table>
  <table border="0" cellspacing="0" cellpadding="2" width="90%">
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo tep_draw_hidden_field('topics_date_added', (($tInfo->date_added) ? $tInfo->date_added : date('Y-m-d'))) . tep_draw_hidden_field('parent_id', $tInfo->parent_id) . tep_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&tID=' . $HTTP_GET_VARS['tID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </form></tr>
<?php
  //MaxiDVD Added WYSIWYG HTML Area Box + Admin Function v1.7 - 2.2 MS2 Articles Description HTML - </form>
  if (ARTICLE_WYSIWYG_ENABLE == 'Enable') {
?>

<?php
  }
  //----- new_topic_preview -----
  } elseif ($HTTP_GET_VARS['action'] == 'new_topic_preview') {
    if ($HTTP_POST_VARS) {
      $tInfo = new objectInfo($HTTP_POST_VARS);
      $topics_name = $HTTP_POST_VARS['topics_name'];
      $topics_heading_title = $HTTP_POST_VARS['topics_heading_title'];
      $topics_description = $HTTP_POST_VARS['topics_description'];
    } else {
      $topic_query = tep_db_query("select t.topics_id, td.language_id, td.topics_name, td.topics_heading_title, td.topics_description, t.sort_order, t.date_added, t.last_modified from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.topics_id = td.topics_id and t.topics_id = '" . $HTTP_GET_VARS['tID'] . "'");
      $topic = tep_db_fetch_array($topic_query);

      $tInfo = new objectInfo($topic);
    }

    $form_action = ($HTTP_GET_VARS['tID']) ? 'update_topic' : 'insert_topic';

    echo tep_draw_form($form_action, FILENAME_ARTICLES, 'tPath=' . $tPath . '&tID=' . $HTTP_GET_VARS['tID'] . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');

    $languages = tep_get_languages(true);
    for ($i=0; $i<sizeof($languages); $i++) {
      if ($HTTP_GET_VARS['read'] == 'only') {
        $tInfo->topics_name = tep_get_topic_name($tInfo->topics_id, $languages[$i]['id']);
        $tInfo->topics_heading_title = tep_get_topic_heading_title($tInfo->topics_id, $languages[$i]['id']);
        $tInfo->topics_description = tep_get_topic_description($tInfo->topics_id, $languages[$i]['id']);
      } else {
        $tInfo->topics_name = tep_db_prepare_input($topics_name[$languages[$i]['id']]);
        $tInfo->topics_heading_title = tep_db_prepare_input($topics_heading_title[$languages[$i]['id']]);
        $tInfo->topics_description = tep_db_prepare_input($topics_description[$languages[$i]['id']]);
      }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $tInfo->topics_heading_title; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo $tInfo->topics_description; ?></td>
      </tr>

<?php
    }
    if ($HTTP_GET_VARS['read'] == 'only') {
      if ($HTTP_GET_VARS['origin']) {
        $pos_params = strpos($HTTP_GET_VARS['origin'], '?', 0);
        if ($pos_params != false) {
          $back_url = substr($HTTP_GET_VARS['origin'], 0, $pos_params);
          $back_url_params = substr($HTTP_GET_VARS['origin'], $pos_params + 1);
        } else {
          $back_url = $HTTP_GET_VARS['origin'];
          $back_url_params = '';
        }
      } else {
        $back_url = FILENAME_ARTICLES;
        $back_url_params = 'tPath=' . $tPath . '&tID=' . $tInfo->topics_id;
      }
?>
      <tr>
        <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
    } else {
?>
      <tr>
        <td align="right" class="smallText">
<?php
/* Re-Post all POST'ed variables */
      reset($HTTP_POST_VARS);
      while (list($key, $value) = each($HTTP_POST_VARS)) {
        if (!is_array($HTTP_POST_VARS[$key])) {
          echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
        }
      }
      $languages = tep_get_languages(true);
      for ($i=0; $i<sizeof($languages); $i++) {
        echo tep_draw_hidden_field('topics_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($topics_name[$languages[$i]['id']])));
        echo tep_draw_hidden_field('topics_heading_title[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($topics_heading_title[$languages[$i]['id']])));
        echo tep_draw_hidden_field('topics_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($topics_description[$languages[$i]['id']])));
      }

      echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

      if ($HTTP_GET_VARS['tID']) {
        echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
      } else {
        echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
      }
      echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&tID=' . $HTTP_GET_VARS['tID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?></td>
      </form></tr>
<?php
    }
  } elseif ($action == 'new_article') {
  //============================================================== [new_article], 包括修改和新建 ======================================================
    $parameters = array('article_type' => '',
						'articles_name' => '',
                       'articles_description' => '',
                       'articles_url' => '',
                       'articles_head_title_tag' => '',
                       'articles_head_desc_tag' => '',
                       'articles_head_keywords' => '',
                       'articles_id' => '',
                       'articles_date_added' => '',
                       'articles_last_modified' => '',
                       'articles_date_available' => '',
                       'articles_status' => '',
                       'authors_id' => '',
					   'articles_seo_url' => '');

    $aInfo = new objectInfo($parameters);

    if (isset($HTTP_GET_VARS['aID']) && empty($HTTP_POST_VARS)) {	/*如果有aID,则是修改文章*/
	  $article_sql="select ad.articles_name, ad.articles_description, ad.articles_url, ad.articles_head_title_tag, ad.articles_head_desc_tag, ad.articles_head_keywords_tag, a.articles_id, a.article_type, a.articles_date_added, a.articles_last_modified, date_format(a.articles_date_available, '%Y-%m-%d') as articles_date_available, a.articles_status, a.authors_id,a.articles_image,a.articles_seo_url,a.article_type FROM " . TABLE_ARTICLES . " AS a, " . TABLE_ARTICLES_DESCRIPTION . " AS ad WHERE a.articles_id = '" . (int)$HTTP_GET_VARS['aID'] . "' and a.articles_id = ad.articles_id and ad.language_id = '" . (int)$languages_id . "'";
	  //echo $article_sql; exit();
      $article_query = tep_db_query($article_sql);	  
      $article = tep_db_fetch_array($article_query);	//print_r($article); exit();  

      $aInfo->objectInfo($article);	  //print_r($article); exit();
	  
    } elseif (tep_not_null($HTTP_POST_VARS)) {
      $aInfo->objectInfo($HTTP_POST_VARS);
	  $article_type = $HTTP_POST_VARS['article_type'];
      $articles_name = $HTTP_POST_VARS['articles_name'];
      $articles_description = $HTTP_POST_VARS['articles_description'];
      $articles_url = $HTTP_POST_VARS['articles_url'];
      $articles_head_title_tag = $HTTP_POST_VARS['articles_head_title_tag'];
      $articles_head_desc_tag = $HTTP_POST_VARS['articles_head_desc_tag'];
      $articles_head_keywords_tag = $HTTP_POST_VARS['articles_head_keywords_tag'];
    }

    $authors_array = array(array('id' => '', 'text' => TEXT_NONE));
    $authors_query = tep_db_query("SELECT authors_id, authors_name from " . TABLE_AUTHORS . " ORDER BY authors_name");
    while ($authors = tep_db_fetch_array($authors_query)) {
      $authors_array[] = array('id' => $authors['authors_id'],
                                     'text' => $authors['authors_name']);
    }

    $languages = tep_get_languages(true);

    if (!isset($aInfo->articles_status)) $aInfo->articles_status = '1';
    switch ($aInfo->articles_status) {
      case '0': $in_status = false; $out_status = true; break;
      case '1':
      default: $in_status = true; $out_status = false;
    }
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script type="text/javascript" src="includes/javascript/spiffyCal/spiffyCal_v2_1_2008_04_01-min.js"></script>
<script type="text/javascript" src="includes/javascript/calendar.js"></script>

<!--
================新增/编辑article=====================
//-->
    <?php echo tep_draw_form('new_article', FILENAME_ARTICLES, 'tPath=' . $tPath . (isset($HTTP_GET_VARS['aID']) ? '&aID=' . $HTTP_GET_VARS['aID'] : '') . '&action=article_preview', 'post', 'enctype="multipart/form-data"'); ?>
	
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf(TEXT_NEW_ARTICLE, tep_output_generated_topic_path($current_topic_id)); ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table>
		</td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
	  <tr>
	  	<td>
		<table>
			<tr><td>类别:</td>
				<td>
				<?php
				
				?>
				<label><?php
				if ($aInfo->article_type == 'article') { echo tep_draw_radio_field('article_type', 'article',true, '', '');}
				else { echo tep_draw_radio_field('article_type', 'article',false, '', '');}
				?>article(普通文章)</label>
				<label><?php
				if ($aInfo->article_type == 'announce' || !isset($HTTP_GET_VARS['aID'])) { echo tep_draw_radio_field('article_type', 'announce', true, '', '');}
				else{ echo tep_draw_radio_field('article_type', 'announce', false, '', '');}
				?>announce(公告)</label>
				</td>
			</tr>
		</table>
		</td>
	  </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_ARTICLES_STATUS; ?><br/>(文章状态)</td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_radio_field('articles_status', '0', $out_status) . '&nbsp;草稿' . TEXT_ARTICLE_NOT_AVAILABLE . '&nbsp;' . tep_draw_radio_field('articles_status', '1', $in_status) . '&nbsp;发布' . TEXT_ARTICLE_AVAILABLE; ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_ARTICLES_DATE_AVAILABLE; ?><br><small><!-- (YYYY-MM-DD) --><?php echo DATE_FORMAT?></small><br/>(计划生效日期)</td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?><?php echo tep_draw_input_field('articles_date_available', $aInfo->articles_date_available, ' class="textTime" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);"');?>(不填写就是立即生效)</td>
          </tr>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          
          <tr>
            <td class="main"><?php echo TEXT_ARTICLES_AUTHOR; ?><br/>(作者)</td>
            <td class="main"><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_pull_down_menu('authors_id', $authors_array, $aInfo->authors_id); ?></td>
          </tr>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
		  
		  

		  <tr>
            <td class="main"><?php echo TEXT_ARTICLES_SEO_URL; ?></td>
			<?php
				//echo $current_topic_id;
				$catname1 = tep_output_generated_topic_path($current_topic_id);
				if($catname1=='Top')
				{
					$catname_url = '';	
				}
				else
				{
					$catname = str_replace('&nbsp;&gt;&nbsp;','/',strtolower($catname1));
					$catname_url = str_replace(' ','-',$catname).'/';
				}
				echo tep_draw_hidden_field('current_topic_id', $current_topic_id);
				$edit_value_url = str_replace($catname_url,'',$aInfo->articles_seo_url);
			?>
            <td class="main"><?php echo HTTP_CATALOG_SERVER.'/articles/'.$catname_url. '&nbsp;' . tep_draw_input_field('articles_seo_url',$edit_value_url,'size="65"'); ?> <span class="smallText">(without extension)</span></td>
          </tr>
		  <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          
          <tr>
            <td class="main"><?php echo TEXT_ARTICLES_IMAGE; ?></td>
            <td class="main" valign="top">
				<?php 
					echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . tep_draw_file_field('articles_image');
					
				?>
			</td>
          </tr>
		  <tr>
		  <td></td>
		  	<td class="main" style="padding-left:30px;">
				<?php if(isset($aInfo->articles_image)&&$aInfo->articles_image!='') 
						{
							//echo 'here';
							echo tep_image(HTTP_CATALOG_SERVER.'/'.DIR_WS_IMAGES.$aInfo->articles_image,'',100,100);
						}
				?>
			</td>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
</table>
<?php 
// Load Editor
echo tep_load_html_editor();
  for ($i=0; $i<sizeof($languages); $i++) {
      //echo $languages[$i]['id'];
	  $articles_description_elements .= 'articles_description[' . $languages[$i]['id'] . '],'; 
    } 
echo tep_insert_html_editor($articles_description_elements);
?>
<div class="tab-pane" id="tabPane1">
<script type="text/javascript">tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
</script>
<?php
    for ($i=0; $i<sizeof($languages); $i++) {
	//echo 'here';
?>
              <div class="tab-page" id="<?php echo $languages[$i]['name'];?>">
                <h2 class="tab"><nobr><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name'],'align="absmiddle" style="height:16px; width:30px;"') . '&nbsp;' .$languages[$i]['name'];?></nobr></h2>
                <script type="text/javascript">tp1.addTabPage( document.getElementById( "<?php echo $languages[$i]['name'];?>" ) );</script>
                <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="tab table">
                  <tr>
                    <td valign="top">
<table border="0" cellspacing="0" cellpadding="2" width="100%">
<?php
  //  for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php echo TEXT_ARTICLES_NAME; ?><br/>(文章标题)</td>
            <td class="main"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('articles_name[' . $languages[$i]['id'] . ']', (isset($articles_name[$languages[$i]['id']]) ? $articles_name[$languages[$i]['id']] : tep_get_articles_name($aInfo->articles_id, $languages[$i]['id'])), 'size="35"'); ?></td>
          </tr>
<?php
  //  }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
  // for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php  echo TEXT_ARTICLES_HEAD_TITLE_TAG; ?><br/>(页面标题)</td>
            <td class="main"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('articles_head_title_tag[' . $languages[$i]['id'] . ']', (isset($articles_head_title_tag[$languages[$i]['id']]) ? $articles_head_title_tag[$languages[$i]['id']] : tep_get_articles_head_title_tag($aInfo->articles_id, $languages[$i]['id'])), 'size="35"'); ?></td>
          </tr>
<?php
//    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
 //   for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main" valign="top"><?php  echo sprintf(TEXT_ARTICLES_HEAD_DESC_TAG, MAX_ARTICLE_ABSTRACT_LENGTH); ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('articles_head_desc_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($articles_head_desc_tag[$languages[$i]['id']]) ? $articles_head_desc_tag[$languages[$i]['id']] : tep_get_articles_head_desc_tag($aInfo->articles_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
  //  }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
 //   for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_ARTICLES_HEAD_KEYWORDS_TAG; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('articles_head_keywords_tag[' . $languages[$i]['id'] . ']', 'soft', '70', '5', (isset($articles_head_keywords_tag[$languages[$i]['id']]) ? $articles_head_keywords_tag[$languages[$i]['id']] : tep_get_articles_head_keywords_tag($aInfo->articles_id, $languages[$i]['id']))); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
 //   }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

<?php
//    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_ARTICLES_DESCRIPTION; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main"><?php echo tep_draw_textarea_field('articles_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (isset($articles_description[$languages[$i]['id']]) ? $articles_description[$languages[$i]['id']] : tep_get_articles_description($aInfo->articles_id, $languages[$i]['id'])),' style="width: 100%" mce_editable="true"'); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
//    }
?>
          <tr>
            <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
//    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php  echo TEXT_ARTICLES_URL . '<br><small>' . TEXT_ARTICLES_URL_WITHOUT_HTTP . '</small>'; ?></td>
            <td class="main"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('articles_url[' . $languages[$i]['id'] . ']', (isset($articles_url[$languages[$i]['id']]) ? $articles_url[$languages[$i]['id']] : tep_get_articles_url($aInfo->articles_id, $languages[$i]['id'])), 'size="35"'); ?></td>
          </tr>
<?php
//    }
?>
        </table>
</td>
                  </tr>
                </table>
              </div>
<?php
    }
?>
            </div>
<script type="text/javascript">
//<![CDATA[
setupAllTabs();
//]]>
</script>
    </td>
      </tr>
</table><table border="0" cellspacing="0" cellpadding="0" width="80%">
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo tep_draw_hidden_field('articles_date_added', (tep_not_null($aInfo->articles_date_added) ? $aInfo->articles_date_added : date('Y-m-d'))) . tep_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . (isset($HTTP_GET_VARS['aID']) ? '&aID=' . $HTTP_GET_VARS['aID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr>
    </table></form>
<?php
  } elseif ($action == 'article_preview') {
  //================================================================= [article_preview] =====================================================================================
$articles_seo_url = str_replace(HTTP_CATALOG_SERVER,'',$HTTP_POST_VARS['articles_seo_url']);
 /*foreach($_POST as $key=>$val)
{
	echo "$key=>$val";
}
exit;*/
    if (tep_not_null($HTTP_POST_VARS)) {
      $aInfo = new objectInfo($HTTP_POST_VARS);
	  $article_type = $HTTP_POST_VARS['article_type'];//aben
      $articles_name = $HTTP_POST_VARS['articles_name'];	  
      $articles_description = $HTTP_POST_VARS['articles_description'];
      $articles_url = $HTTP_POST_VARS['articles_url'];
      $articles_head_title_tag = $HTTP_POST_VARS['articles_head_title_tag'];
      $articles_head_desc_tag = $HTTP_POST_VARS['articles_head_desc_tag'];
      $articles_head_keywords_tag = $HTTP_POST_VARS['articles_head_keywords_tag'];
    } else {
      $article_query = tep_db_query("select a.articles_id, ad.language_id, ad.articles_name, ad.articles_description, ad.articles_url, ad.articles_head_title_tag, ad.articles_head_desc_tag, ad.articles_head_keywords_tag, a.articles_date_added, a.articles_last_modified, a.articles_date_available, a.articles_status, a.authors_id  from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_DESCRIPTION . " ad where a.articles_id = ad.articles_id and a.articles_id = '" . (int)$HTTP_GET_VARS['aID'] . "'");
      $article = tep_db_fetch_array($article_query);

      $aInfo = new objectInfo($article);
    }

    $form_action = (isset($HTTP_GET_VARS['aID'])) ? 'update_article' : 'insert_article';

    echo tep_draw_form($form_action, FILENAME_ARTICLES, 'tPath=' . $tPath . (isset($HTTP_GET_VARS['aID']) ? '&aID=' . $HTTP_GET_VARS['aID'] : '') . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');

    
	if (tep_db_prepare_input($_FILES['articles_image']['name'])!='')
{
$articles_image = new upload('articles_image');
			  $articles_image->set_destination(DIR_FS_CATALOG_IMAGES);
			  if ($articles_image->parse() && $articles_image->save()) {
			  $articles_image_name = $articles_image->filename;
			  }
}
else
{
/*foreach($_POST as $key=>$val)
{
	echo "$key=>$val";
}*/
if(isset($_GET['aID']))
{
$articles_id = $_GET['aID'];
$article_image_query = tep_db_query("select articles_image from ".TABLE_ARTICLES." where articles_id=".$articles_id."");
$row_article_image = tep_db_fetch_array($article_image_query);

$articles_image_name = $row_article_image['articles_image'];
}
//exit;
}
	$languages = tep_get_languages(true);
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
      if (isset($HTTP_GET_VARS['read']) && ($HTTP_GET_VARS['read'] == 'only')) {
        $aInfo->articles_name = tep_get_articles_name($aInfo->articles_id, $languages[$i]['id']);
        $aInfo->articles_description = tep_get_articles_description($aInfo->articles_id, $languages[$i]['id']);
        $aInfo->articles_url = tep_get_articles_url($aInfo->articles_id, $languages[$i]['id']);
        $aInfo->articles_head_title_tag = tep_get_articles_head_title_tag($aInfo->articles_id, $languages[$i]['id']);
        $aInfo->articles_head_desc_tag = tep_get_articles_head_desc_tag($aInfo->articles_id, $languages[$i]['id']);
        $aInfo->articles_head_keywords_tag = tep_get_articles_head_keywords_tag($aInfo->articles_id, $languages[$i]['id']);
      } else {
        $aInfo->articles_name = tep_db_prepare_input($articles_name[$languages[$i]['id']]);
        $aInfo->articles_description = tep_db_prepare_input($articles_description[$languages[$i]['id']]);
        $aInfo->articles_url = tep_db_prepare_input($articles_url[$languages[$i]['id']]);
        $aInfo->articles_head_title_tag = tep_db_prepare_input($articles_head_title_tag[$languages[$i]['id']]);
        $aInfo->articles_head_desc_tag = tep_db_prepare_input($articles_head_desc_tag[$languages[$i]['id']]);
        $aInfo->articles_head_keywords_tag = tep_db_prepare_input($articles_head_keywords_tag[$languages[$i]['id']]);
      }
	  
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td>
		<?php 
		echo '文章类别:'.$article_type;
		?>
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" colspan="2"><?php echo tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . $aInfo->articles_name; ?></td>
			<td align="right">
				<?php
					//echo HTTP_CATALOG_SERVER.DIR_WS_IMAGES;
					if($articles_image_name!='')
					{
					echo tep_image(HTTP_CATALOG_SERVER.DIR_WS_IMAGES.$articles_image_name,'',120,120);
					}
				?>
			</td>
          </tr>
        </table>
		</td>
      </tr>
<?php
      if ($aInfo->articles_description) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo $aInfo->articles_description; ?></td>
      </tr>
<?php
      }
?>
<?php
      if ($aInfo->articles_url) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo sprintf(TEXT_ARTICLE_MORE_INFORMATION, $aInfo->articles_url); ?></td>
      </tr>
<?php
      }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
      if ($aInfo->articles_date_available > date('Y-m-d')) {
?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_ARTICLE_DATE_AVAILABLE, tep_date_long($aInfo->articles_date_available)); ?></td>
      </tr>
<?php
      } else {
?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_ARTICLE_DATE_ADDED, tep_date_long($aInfo->articles_date_added)); ?></td>
      </tr>
<?php
      }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
<?php
    }

    if (isset($HTTP_GET_VARS['read']) && ($HTTP_GET_VARS['read'] == 'only')) {
      if (isset($HTTP_GET_VARS['origin'])) {
        $pos_params = strpos($HTTP_GET_VARS['origin'], '?', 0);
        if ($pos_params != false) {
          $back_url = substr($HTTP_GET_VARS['origin'], 0, $pos_params);
          $back_url_params = substr($HTTP_GET_VARS['origin'], $pos_params + 1);
        } else {
          $back_url = $HTTP_GET_VARS['origin'];
          $back_url_params = '';
        }
      } else {
        $back_url = FILENAME_ARTICLES;
        $back_url_params = 'tPath=' . $tPath . '&aID=' . $aInfo->articles_id;
      }
?>
      <tr>
        <td align="right"><?php echo '<a href="' . tep_href_link($back_url, $back_url_params, 'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
    } else {
?>
      <tr>
        <td align="right" class="smallText">
<?php
/* Re-Post all POST'ed variables */
/*重新建立form,用来提交*/


      reset($HTTP_POST_VARS);
      while (list($key, $value) = each($HTTP_POST_VARS)) {
        if (!is_array($HTTP_POST_VARS[$key])) {
          //echo $key;
		  echo tep_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
        }
      }
	  echo tep_draw_hidden_field('articles_image', $articles_image_name);
      $languages = tep_get_languages(true);
	  
	  //echo tep_draw_hidden_field('article_type111', htmlspecialchars(stripslashes($article_type)));
	  
      for ($i=0, $n=sizeof($languages); $i<$n; $i++) {	  	
        echo tep_draw_hidden_field('articles_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($articles_name[$languages[$i]['id']])));
        echo tep_draw_hidden_field('articles_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($articles_description[$languages[$i]['id']])));
        echo tep_draw_hidden_field('articles_url[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($articles_url[$languages[$i]['id']])));
        echo tep_draw_hidden_field('articles_head_title_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($articles_head_title_tag[$languages[$i]['id']])));
        echo tep_draw_hidden_field('articles_head_desc_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($articles_head_desc_tag[$languages[$i]['id']])));
        echo tep_draw_hidden_field('articles_head_keywords_tag[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($articles_head_keywords_tag[$languages[$i]['id']])));
      }

      echo tep_image_submit('button_back.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

      if (isset($HTTP_GET_VARS['aID'])) {
        echo tep_image_submit('button_update.gif', IMAGE_UPDATE);
      } else {
        echo tep_image_submit('button_insert.gif', IMAGE_INSERT);
      }
      echo '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . (isset($HTTP_GET_VARS['aID']) ? '&aID=' . $HTTP_GET_VARS['aID'] : '')) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>';
?></td>
      </tr>
    </table></form>
<?php
    }
  } else {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="smallText" align="right">
<?php
    echo tep_draw_form('search', FILENAME_ARTICLES, '', 'get');
    echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search');
    if (isset($HTTP_GET_VARS[tep_session_name()])) {
      echo tep_draw_hidden_field(tep_session_name(), $HTTP_GET_VARS[tep_session_name()]);
    }
    echo '</form>';
?>
                </td>
              </tr>
              <tr>
                <td class="smallText" align="right">
<?php
    echo tep_draw_form('goto', FILENAME_ARTICLES, '', 'get');
    echo HEADING_TITLE_GOTO . ' ' . tep_draw_pull_down_menu('tPath', tep_get_topic_tree(), $current_topic_id, 'onChange="this.form.submit();"');
    if (isset($HTTP_GET_VARS[tep_session_name()])) {
      echo tep_draw_hidden_field(tep_session_name(), $HTTP_GET_VARS[tep_session_name()]);
    }
    echo '</form>';
?>
                </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TOPICS_ARTICLES; ?></td>
				<td>文章的类别</td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $topics_count = 0;
    $rows = 0;
    if (isset($HTTP_GET_VARS['search'])) {
      $search = tep_db_prepare_input($HTTP_GET_VARS['search']);
	  $topics_sql = "select t.topics_id,  td.topics_name, t.parent_id, t.sort_order, t.date_added, t.last_modified FROM " . TABLE_TOPICS . " AS t, " . TABLE_TOPICS_DESCRIPTION . " AS td where t.topics_id = td.topics_id and td.language_id = '" . (int)$languages_id . "' and td.topics_name like '%" . tep_db_input($search) . "%' ORDER BY t.sort_order, td.topics_name";
    } else {
      $topics_sql = "select t.topics_id,  td.topics_name, t.parent_id, t.sort_order, t.date_added, t.last_modified FROM " . TABLE_TOPICS . " AS t, " . TABLE_TOPICS_DESCRIPTION . " AS td where t.parent_id = '" . (int)$current_topic_id . "' and t.topics_id = td.topics_id and td.language_id = '" . (int)$languages_id . "' ORDER BY t.sort_order, td.topics_name";
    }
	$topics_query = tep_db_query($topics_sql);
	
	//在这里循环输出啦
    while ($topics = tep_db_fetch_array($topics_query)) {
      $topics_count++;
      $rows++;

// Get parent_id for subtopics if search
      if (isset($HTTP_GET_VARS['search'])) $tPath= $topics['parent_id'];

      if ((!isset($HTTP_GET_VARS['tID']) && !isset($HTTP_GET_VARS['aID']) || (isset($HTTP_GET_VARS['tID']) && ($HTTP_GET_VARS['tID'] == $topics['topics_id']))) && !isset($tInfo) && (substr($action, 0, 3) != 'new')) {
        $topic_childs = array('childs_count' => tep_childs_in_topic_count($topics['topics_id']));
        $topic_articles = array('articles_count' => tep_articles_in_topic_count($topics['topics_id']));

        $tInfo_array = array_merge($topics, $topic_childs, $topic_articles);
        $tInfo = new objectInfo($tInfo_array);
      }

      if (isset($tInfo) && is_object($tInfo) && ($topics['topics_id'] == $tInfo->topics_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ARTICLES, tep_get_topic_path($topics['topics_id'])) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&tID=' . $topics['topics_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_ARTICLES, tep_get_topic_path($topics['topics_id'])) . '">' . tep_image(DIR_WS_ICONS . 'folder.gif', ICON_FOLDER) . '</a>&nbsp;<b>' . $topics['topics_name'] . '</b>'; ?></td>
				<td> </td>
                <td class="dataTableContent" align="center">&nbsp;</td>
                <td class="dataTableContent" align="right"><?php if (isset($tInfo) && is_object($tInfo) && ($topics['topics_id'] == $tInfo->topics_id) ) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&tID=' . $topics['topics_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

    $articles_count = 0;
    if (isset($HTTP_GET_VARS['search'])) {
	  $articles_sql = "select a.articles_id, a.article_type, ad.articles_name, a.articles_date_added, a.articles_last_modified, a.articles_date_available, a.articles_status, a2t.topics_id from " . TABLE_ARTICLES . " AS a, " . TABLE_ARTICLES_DESCRIPTION . " AS ad, " . TABLE_ARTICLES_TO_TOPICS . " AS a2t where a.articles_id = ad.articles_id and ad.language_id = '" . (int)$languages_id . "' and a.articles_id = a2t.articles_id and ad.articles_name like '%" . tep_db_input($search) . "%' order by ad.articles_name";
    } else {
     $articles_sql = "select a.articles_id, a.article_type, ad.articles_name, a.articles_date_added, a.articles_last_modified, a.articles_date_available, a.articles_status from " . TABLE_ARTICLES . " AS a, " . TABLE_ARTICLES_DESCRIPTION . " AS ad, " . TABLE_ARTICLES_TO_TOPICS . " AS a2t where a.articles_id = ad.articles_id and ad.language_id = '" . (int)$languages_id . "' and a.articles_id = a2t.articles_id and a2t.topics_id = '" . (int)$current_topic_id . "' order by ad.articles_name";
    }
	$articles_query = tep_db_query($articles_sql);
    while ($articles = tep_db_fetch_array($articles_query)) {
      $articles_count++;
      $rows++;

// Get topics_id for article if search
      if (isset($HTTP_GET_VARS['search'])) $tPath = $articles['topics_id'];

      if ( (!isset($HTTP_GET_VARS['aID']) && !isset($HTTP_GET_VARS['tID']) || (isset($HTTP_GET_VARS['aID']) && ($HTTP_GET_VARS['aID'] == $articles['articles_id']))) && !isset($aInfo) && !isset($tInfo) && (substr($action, 0, 3) != 'new')) {
// find out the rating average from customer reviews
        $reviews_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_ARTICLE_REVIEWS . " where articles_id = '" . (int)$articles['articles_id'] . "'");
        $reviews = tep_db_fetch_array($reviews_query);
        $aInfo_array = array_merge($articles, $reviews);
        $aInfo = new objectInfo($aInfo_array);
      }

      if (isset($aInfo) && is_object($aInfo) && ($articles['articles_id'] == $aInfo->articles_id) ) {
        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&aID=' . $articles['articles_id'] . '&action=article_preview&read=only') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&aID=' . $articles['articles_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&aID=' . $articles['articles_id'] . '&action=article_preview&read=only') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $articles['articles_name']; ?></td>
				<td><?php if ($articles['article_type']=='article') {echo '一般文章';} else { echo '公告';}?></td>
                <td class="dataTableContent" align="center">
<?php
      if ($articles['articles_status'] == '1') {
        echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ARTICLES, 'action=setflag&flag=0&aID=' . $articles['articles_id'] . '&tPath=' . $tPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link(FILENAME_ARTICLES, 'action=setflag&flag=1&aID=' . $articles['articles_id'] . '&tPath=' . $tPath) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($aInfo) && is_object($aInfo) && ($articles['articles_id'] == $aInfo->articles_id)) { echo tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&aID=' . $articles['articles_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    }

    $tPath_back = '';
    if (sizeof($tPath_array) > 0) {
      for ($i=0, $n=sizeof($tPath_array)-1; $i<$n; $i++) {
        if (empty($tPath_back)) {
          $tPath_back .= $tPath_array[$i];
        } else {
          $tPath_back .= '_' . $tPath_array[$i];
        }
      }
    }

    $tPath_back = (tep_not_null($tPath_back)) ? 'tPath=' . $tPath_back . '&' : '';
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_TOPICS . '&nbsp;' . $topics_count . '<br>' . TEXT_ARTICLES . '&nbsp;' . $articles_count; ?></td>
                    <td align="right" class="smallText"><?php if (sizeof($tPath_array) > 0) echo '<a href="' . tep_href_link(FILENAME_ARTICLES, $tPath_back . 'tID=' . $current_topic_id) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>&nbsp;'; if (!isset($HTTP_GET_VARS['search'])) echo '<a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&action=new_topic') . '">' . tep_image_button('button_new_topic.gif', IMAGE_NEW_TOPIC) . '</a>&nbsp;' . $add_str; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();
    switch ($action) {
      case 'new_topic':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_TOPIC . '</b>');

        $contents = array('form' => tep_draw_form('newtopic', FILENAME_ARTICLES, 'action=insert_topic&tPath=' . $tPath, 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => TEXT_NEW_TOPIC_INTRO);

        $topic_inputs_string = '';
        $languages = tep_get_languages(true);
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $topic_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('topics_name[' . $languages[$i]['id'] . ']');
        }

        $contents[] = array('text' => '<br>' . TEXT_TOPICS_NAME . $topic_inputs_string);
        $contents[] = array('text' => '<br>' . TEXT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', '', 'size="2"'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'edit_topic':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_TOPIC . '</b>');

        $contents = array('form' => tep_draw_form('topics', FILENAME_ARTICLES, 'action=update_topic&tPath=' . $tPath, 'post', 'enctype="multipart/form-data"') . tep_draw_hidden_field('topics_id', $tInfo->topics_id));
        $contents[] = array('text' => TEXT_EDIT_INTRO);

        $topic_inputs_string = '';
        $languages = tep_get_languages(true);
        for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
          $topic_inputs_string .= '<br>' . tep_image(HTTP_SERVER . DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . tep_draw_input_field('topics_name[' . $languages[$i]['id'] . ']', tep_get_topic_name($tInfo->topics_id, $languages[$i]['id']));
        }

        $contents[] = array('text' => '<br>' . TEXT_EDIT_TOPICS_NAME . $topic_inputs_string);
        $contents[] = array('text' => '<br>' . TEXT_EDIT_SORT_ORDER . '<br>' . tep_draw_input_field('sort_order', $tInfo->sort_order, 'size="2"'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_save.gif', IMAGE_SAVE) . ' <a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&tID=' . $tInfo->topics_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_topic':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_TOPIC . '</b>');

        $contents = array('form' => tep_draw_form('topics', FILENAME_ARTICLES, 'action=delete_topic_confirm&tPath=' . $tPath) . tep_draw_hidden_field('topics_id', $tInfo->topics_id));
        $contents[] = array('text' => TEXT_DELETE_TOPIC_INTRO);
        $contents[] = array('text' => '<br><b>' . $tInfo->topics_name . '</b>');
        if ($tInfo->childs_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $tInfo->childs_count));
        if ($tInfo->articles_count > 0) $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_ARTICLES, $tInfo->articles_count));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&tID=' . $tInfo->topics_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_topic':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_TOPIC . '</b>');

        $contents = array('form' => tep_draw_form('topics', FILENAME_ARTICLES, 'action=move_topic_confirm&tPath=' . $tPath) . tep_draw_hidden_field('topics_id', $tInfo->topics_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_TOPICS_INTRO, $tInfo->topics_name));
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $tInfo->topics_name) . '<br>' . tep_draw_pull_down_menu('move_to_topic_id', tep_get_topic_tree(), $current_topic_id));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&tID=' . $tInfo->topics_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'delete_article':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ARTICLE . '</b>');

        $contents = array('form' => tep_draw_form('articles', FILENAME_ARTICLES, 'action=delete_article_confirm&tPath=' . $tPath) . tep_draw_hidden_field('articles_id', $aInfo->articles_id));
        $contents[] = array('text' => TEXT_DELETE_ARTICLE_INTRO);
        $contents[] = array('text' => '<br><b>' . $aInfo->articles_name . '</b>');

        $article_topics_string = '';
        $article_topics = tep_generate_topic_path($aInfo->articles_id, 'article');
        for ($i = 0, $n = sizeof($article_topics); $i < $n; $i++) {
          $topic_path = '';
          for ($j = 0, $k = sizeof($article_topics[$i]); $j < $k; $j++) {
            $topic_path .= $article_topics[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
          }
          $topic_path = substr($topic_path, 0, -16);
          $article_topics_string .= tep_draw_checkbox_field('article_topics[]', $article_topics[$i][sizeof($article_topics[$i])-1]['id'], true) . '&nbsp;' . $topic_path . '<br>';
        }
        $article_topics_string = substr($article_topics_string, 0, -4);

        $contents[] = array('text' => '<br>' . $article_topics_string);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&aID=' . $aInfo->articles_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'move_article':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_ARTICLE . '</b>');

        $contents = array('form' => tep_draw_form('articles', FILENAME_ARTICLES, 'action=move_article_confirm&tPath=' . $tPath) . tep_draw_hidden_field('articles_id', $aInfo->articles_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_ARTICLES_INTRO, $aInfo->articles_name));
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_TOPICS . '<br><b>' . tep_output_generated_topic_path($aInfo->articles_id, 'article') . '</b>');
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $aInfo->articles_name) . '<br>' . tep_draw_pull_down_menu('move_to_topic_id', tep_get_topic_tree(), $current_topic_id));
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_move.gif', IMAGE_MOVE) . ' <a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&aID=' . $aInfo->articles_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;
      case 'copy_to':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');

        $contents = array('form' => tep_draw_form('copy_to', FILENAME_ARTICLES, 'action=copy_to_confirm&tPath=' . $tPath) . tep_draw_hidden_field('articles_id', $aInfo->articles_id));
        $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_TOPICS . '<br><b>' . tep_output_generated_topic_path($aInfo->articles_id, 'article') . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_TOPICS . '<br>' . tep_draw_pull_down_menu('topics_id', tep_get_topic_tree(), $current_topic_id));
        $contents[] = array('text' => '<br>' . TEXT_HOW_TO_COPY . '<br>' . tep_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br>' . tep_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
        $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_copy.gif', IMAGE_COPY) . ' <a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&aID=' . $aInfo->articles_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
        break;

      default:
        if ($rows > 0) {
          if (isset($tInfo) && is_object($tInfo)) { // topic info box contents
            $heading[] = array('text' => '<b>' . $tInfo->topics_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&tID=' . $tInfo->topics_id . '&action=edit_topic') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&tID=' . $tInfo->topics_id . '&action=delete_topic') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&tID=' . $tInfo->topics_id . '&action=move_topic') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a>');
            $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($tInfo->date_added));
            if (tep_not_null($tInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($tInfo->last_modified));
            $contents[] = array('text' => '<br>' . TEXT_SUBTOPICS . ' ' . $tInfo->childs_count . '<br>' . TEXT_ARTICLES . ' ' . $tInfo->articles_count);
          } elseif (isset($aInfo) && is_object($aInfo)) { // article info box contents
            $heading[] = array('text' => '<b>' . tep_get_articles_name($aInfo->articles_id, $languages_id) . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&aID=' . $aInfo->articles_id . '&action=new_article') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&aID=' . $aInfo->articles_id . '&action=delete_article') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a> <a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&aID=' . $aInfo->articles_id . '&action=move_article') . '">' . tep_image_button('button_move.gif', IMAGE_MOVE) . '</a> <a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $tPath . '&aID=' . $aInfo->articles_id . '&action=copy_to') . '">' . tep_image_button('button_copy_to.gif', IMAGE_COPY_TO) . '</a>');            $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . tep_date_short($aInfo->articles_date_added));
            if (tep_not_null($aInfo->articles_last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . tep_date_short($aInfo->articles_last_modified));
            if (date('Y-m-d') < $aInfo->articles_date_available) $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . tep_date_short($aInfo->articles_date_available));
            $contents[] = array('text' => '<br>' . TEXT_ARTICLES_AVERAGE_RATING . ' ' . number_format($aInfo->average_rating, 2) . '%');
          }
        } else { // create topic/article info
          $heading[] = array('text' => '<b>' . EMPTY_TOPIC . '</b>');

          $contents[] = array('text' => TEXT_NO_CHILD_TOPICS_OR_ARTICLES);
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
    </table>
<?php
  }
?>
    </td>
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