<?php
if ( tep_not_null($topic['topics_heading_title']) ) {
	$titletagstr = $topic['topics_heading_title'];
} else {
	$titletagstr = HEADING_TITLE;
}
echo tep_get_design_body_header($titletagstr);  
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
<?php
if ($topic_depth == 'nested') 
{
	//echo 'here';
    $topic_sql = "select td.topics_name, td.topics_heading_title, td.topics_description from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.topics_id = '" . (int)$current_topic_id . "' and td.topics_id = '" . (int)$current_topic_id . "' and td.language_id = '" . (int)$languages_id . "'";
    $topic_query = tep_db_query($topic_sql);
    $topic = tep_db_fetch_array($topic_query);
?>
  <tr>
    <td>
		
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
        
        <?php if ( tep_not_null($topic['topics_description']) ) { ?>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
        <tr>
          <td align="left" colspan="2" class="main"><?php echo $topic['topics_description']; ?></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
        <?php } ?>
      </table>
	 </td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <?php
    if (isset($tPath) && strpos('_', $tPath)) {
// check to see if there are deeper topics within the current topic
      $topic_links = array_reverse($tPath_array);
      for($i=0, $n=sizeof($topic_links); $i<$n; $i++) {
        $topics_query = tep_db_query("select count(*) as total from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.parent_id = '" . (int)$topic_links[$i] . "' and t.topics_id = td.topics_id and td.language_id = '" . (int)$languages_id . "'");
        $topics = tep_db_fetch_array($topics_query);
        if ($topics['total'] < 1) {
          // do nothing, go through the loop
        } else {
          $topics_query = tep_db_query("select t.topics_id, td.topics_name, t.parent_id from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.parent_id = '" . (int)$topic_links[$i] . "' and t.topics_id = td.topics_id and td.language_id = '" . (int)$languages_id . "' order by sort_order, td.topics_name");
          break; // we've found the deepest topic the customer is in
        }
      }
    } else {
      $topics_query = tep_db_query("select t.topics_id, td.topics_name, t.parent_id from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.parent_id = '" . (int)$current_topic_id . "' and t.topics_id = td.topics_id and td.language_id = '" . (int)$languages_id . "' order by sort_order, td.topics_name");
    }

// needed for the new articles module shown below
    $new_articles_topic_id = $current_topic_id;
?>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
        </tr>
        <tr>
          <td><?php /*include(DIR_FS_MODULES . FILENAME_NEW_ARTICLES); */ ?></td>
        </tr>
      </table></td>
  </tr>
</table>
</td>
<?php
} 
elseif ($topic_depth == 'articles' || isset($HTTP_GET_VARS['authors_id'])) {
/* bof catdesc for bts1a */
// Get the topic name and description from the database
	$topic_sql = "select td.topics_name, td.topics_heading_title, td.topics_description from " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where t.topics_id = '" . (int)$current_topic_id . "' and td.topics_id = '" . (int)$current_topic_id . "' and td.language_id = '" . (int)$languages_id . "'";
	echo $topic_sql; exit();
    $topic_query = tep_db_query($topic_sql);
    $topic = tep_db_fetch_array($topic_query);
/* bof catdesc for bts1a */

// show the articles of a specified author
    if (isset($HTTP_GET_VARS['authors_id'])) {
      if (isset($HTTP_GET_VARS['filter_id']) && tep_not_null($HTTP_GET_VARS['filter_id'])) {
// We are asked to show only a specific topic
        $listing_sql = "select a.articles_id, a.authors_id, a.articles_date_added, a.articles_seo_url, ad.articles_name, ad.articles_head_desc_tag, au.authors_name, td.topics_name, a2t.topics_id
                        from " . TABLE_ARTICLES . " a,
                             " . TABLE_ARTICLES_DESCRIPTION . " ad,
                             " . TABLE_AUTHORS . " au,
                             " . TABLE_ARTICLES_TO_TOPICS . " a2t,
                             " . TABLE_TOPICS_DESCRIPTION . " td
                        where a.articles_status = '1'
                          and (a.articles_date_available IS NULL or to_days(a.articles_date_available) <= to_days(now()))
                          and au.authors_id = '" . (int)$HTTP_GET_VARS['authors_id'] . "'
                          and a.authors_id = '" . (int)$HTTP_GET_VARS['authors_id'] . "'
                          and a.articles_id = a2t.articles_id
                          and ad.articles_id = a2t.articles_id
                          and a2t.topics_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "'
                          and td.topics_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "'
                          and ad.language_id = '" . (int)$languages_id . "'
                          and td.language_id = '" . (int)$languages_id . "'
                        order by a.articles_date_added desc, ad.articles_name";
      } else {
// We show them all
        $listing_sql = "select a.articles_id, a.authors_id, a.articles_date_added,a.articles_seo_url, ad.articles_name, ad.articles_head_desc_tag, au.authors_name, td.topics_name, a2t.topics_id
                        from " . TABLE_ARTICLES . " a,
                             " . TABLE_ARTICLES_DESCRIPTION . " ad,
                             " . TABLE_AUTHORS . " au,
                             " . TABLE_ARTICLES_TO_TOPICS . " a2t,
                             " . TABLE_TOPICS_DESCRIPTION . " td
                        where a.articles_status = '1'
                          and (a.articles_date_available IS NULL or to_days(a.articles_date_available) <= to_days(now()))
                          and au.authors_id = '" . (int)$HTTP_GET_VARS['authors_id'] . "'
                          and a.authors_id = '" . (int)$HTTP_GET_VARS['authors_id'] . "'
                          and a.articles_id = a2t.articles_id
                          and ad.articles_id = a2t.articles_id
                          and a2t.topics_id = td.topics_id
                          and ad.language_id = '" . (int)$languages_id . "'
                          and td.language_id = '" . (int)$languages_id . "'
                        order by a.articles_date_added desc, ad.articles_name";
      }
    } else {
// show the articles in a given category
      if (isset($HTTP_GET_VARS['filter_id']) && tep_not_null($HTTP_GET_VARS['filter_id'])) {
// We are asked to show only specific catgeory
        $listing_sql = "select a.articles_id, a.authors_id, a.articles_date_added,a.articles_seo_url, ad.articles_name, ad.articles_head_desc_tag, au.authors_name, td.topics_name, a2t.topics_id
                        from " . TABLE_ARTICLES . " a,
                             " . TABLE_ARTICLES_DESCRIPTION . " ad,
                             " . TABLE_AUTHORS . " au,
                             " . TABLE_ARTICLES_TO_TOPICS . " a2t,
                             " . TABLE_TOPICS_DESCRIPTION . " td
                        where a.articles_status = '1'
                          and (a.articles_date_available IS NULL or to_days(a.articles_date_available) <= to_days(now()))
                          and au.authors_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "'
                          and a.authors_id = '" . (int)$HTTP_GET_VARS['filter_id'] . "'
                          and a.articles_id = a2t.articles_id
                          and ad.articles_id = a2t.articles_id
                          and a2t.topics_id = '" . (int)$current_topic_id . "'
                          and td.topics_id = '" . (int)$current_topic_id . "'
                          and ad.language_id = '" . (int)$languages_id . "'
                          and td.language_id = '" . (int)$languages_id . "'
                        order by a.articles_date_added desc, ad.articles_name";
      } else {
// We show them all
        $listing_sql = "select a.articles_id, a.authors_id, a.articles_date_added,a.articles_seo_url, ad.articles_name, ad.articles_head_desc_tag, au.authors_name, td.topics_name, a2t.topics_id
                        from " . TABLE_ARTICLES . " a,
                             " . TABLE_ARTICLES_DESCRIPTION . " ad,
                             " . TABLE_AUTHORS . " au,
                             " . TABLE_ARTICLES_TO_TOPICS . " a2t,
                             " . TABLE_TOPICS_DESCRIPTION . " td
                        where a.articles_status = '1'
                          and (a.articles_date_available IS NULL or to_days(a.articles_date_available) <= to_days(now()))
                          and a.authors_id = au.authors_id
                          and a.articles_id = a2t.articles_id
                          and ad.articles_id = a2t.articles_id
                          and a2t.topics_id = '" . (int)$current_topic_id . "'
                          and td.topics_id = '" . (int)$current_topic_id . "'
                          and ad.language_id = '" . (int)$languages_id . "'
                          and td.language_id = '" . (int)$languages_id . "'
                        order by a.articles_date_added desc, ad.articles_name";
      }
    }
?>
<?php 
			if ( tep_not_null($topic['topics_heading_title']) ) {
                 $_heading_title = $topic['topics_heading_title'];
			} else {
                 $_heading_title =  HEADING_TITLE;
			}
//echo tep_get_design_body_header($_heading_title); ?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
<tr><td class="main"><b><?php echo $_heading_title; ?></b>

</td></tr>
  <?php
           /* bof catdesc for bts1a, replacing "echo HEADING_TITLE;" by "topics_heading_title" */
               
//echo 'here';
             if (isset($HTTP_GET_VARS['authors_id'])) {
               //echo 'here';
			   $author_query = tep_db_query("select au.authors_name, aui.authors_description, aui.authors_url from " . TABLE_AUTHORS . " au, " . TABLE_AUTHORS_INFO . " aui where au.authors_id = '" . (int)$HTTP_GET_VARS['authors_id'] . "' and au.authors_id = aui.authors_id and aui.languages_id = '" . (int)$languages_id . "'");
               $authors = tep_db_fetch_array($author_query);
               $author_name = $authors['authors_name'];
               $authors_description = $authors['authors_description'];
               $authors_url = $authors['authors_url'];

               $_author_name =  TEXT_ARTICLES_BY . $author_name;
             }

           /* eof catdesc for bts1a */
                  ?>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top" align="left"></td>
          <?php
// optional Article List Filter
    if (ARTICLE_LIST_FILTER) {
      if (isset($HTTP_GET_VARS['authors_id'])) {
        $filterlist_sql = "select distinct t.topics_id as id, td.topics_name as name from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_TO_TOPICS . " a2t, " . TABLE_TOPICS . " t, " . TABLE_TOPICS_DESCRIPTION . " td where a.articles_status = '1' and a.articles_id = a2t.articles_id and a2t.topics_id = t.topics_id and a2t.topics_id = td.topics_id and td.language_id = '" . (int)$languages_id . "' and a.authors_id = '" . (int)$HTTP_GET_VARS['authors_id'] . "' order by td.topics_name";
      } else {
        $filterlist_sql= "select distinct au.authors_id as id, au.authors_name as name from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_TO_TOPICS . " a2t, " . TABLE_AUTHORS . " au where a.articles_status = '1' and a.authors_id = au.authors_id and a.articles_id = a2t.articles_id and a2t.topics_id = '" . (int)$current_topic_id . "' order by au.authors_name";
      }
      $filterlist_query = tep_db_query($filterlist_sql);
      if (tep_db_num_rows($filterlist_query) > 1) {
        echo '<td align="right" class="main"><' . tep_draw_form('filter', FILENAME_ARTICLES, 'get') . TEXT_SHOW . '&nbsp;';
        if (isset($HTTP_GET_VARS['authors_id'])) {
          echo tep_draw_hidden_field('authors_id', $HTTP_GET_VARS['authors_id']);
          $options = array(array('id' => '', 'text' => TEXT_ALL_TOPICS));
        } else {
          echo tep_draw_hidden_field('tPath', $tPath);
          $options = array(array('id' => '', 'text' => TEXT_ALL_AUTHORS));
        }
        echo tep_draw_hidden_field('sort', $HTTP_GET_VARS['sort']);
        while ($filterlist = tep_db_fetch_array($filterlist_query)) {
          $options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);
        }
        echo tep_draw_pull_down_menu('filter_id', $options, (isset($HTTP_GET_VARS['filter_id']) ? $HTTP_GET_VARS['filter_id'] : ''), 'onchange="this.form.submit()"');
        echo '</form></td>' . "\n";
      }
    }
    ?>
</table></td>
</tr>

<?php
if ( tep_not_null($topic['topics_description']) ) {
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>     <tr><td>
          <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="main"><?php echo $topic['topics_description']; ?></td>
            </tr>
            <?php if (tep_not_null($authors_description)) { ?>
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
            </tr>
            <tr>
              <td class="main" valign="top"><?php echo $authors_description; ?></td>
            <tr>
              <?php } ?>
              <?php if (tep_not_null($authors_url)) { ?>
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
              <td class="main" valign="top"><?php echo sprintf(TEXT_MORE_INFORMATION, $authors_url); ?></td>
            </tr>
            <?php } ?>
            <tr>
              <td>&nbsp;</td>
            </tr>
          </table> </td></tr>
          <?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD

}
?>
      
  </tr>
  <?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
  <tr>
    <td class="main"><?php include(DIR_FS_MODULES . FILENAME_ARTICLE_LISTING); ?></td>
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
<?php
  } else { // default page
  //echo "here";
?>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
// Kiran :: Upcoming articles query, moved from modules/articles_upcoming.php
  $expected_query = tep_db_query("select a.articles_id, a.articles_date_added,a.articles_seo_url, a.articles_date_available as date_expected, ad.articles_name, ad.articles_head_desc_tag, au.authors_id, au.authors_name, td.topics_id, td.topics_name from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_TO_TOPICS . " a2t, " . TABLE_TOPICS_DESCRIPTION . " td, " . TABLE_AUTHORS . " au, " . TABLE_ARTICLES_DESCRIPTION . " ad where to_days(a.articles_date_available) > to_days(now()) and a.articles_id = a2t.articles_id and a2t.topics_id = td.topics_id and a.authors_id = au.authors_id and a.articles_status = '1' and a.articles_id = ad.articles_id and ad.language_id = '" . (int)$languages_id . "' and td.language_id = '" . (int)$languages_id . "' order by date_expected limit " . MAX_DISPLAY_UPCOMING_ARTICLES);
  if (tep_db_num_rows($expected_query) > 0) {
  //echo 'here';
?>
<tr>
  <td><?php include(DIR_FS_MODULES . FILENAME_ARTICLES_UPCOMING); ?></td>
</tr>
<tr>
  <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
</tr>
<?php 
} 
// Kiran :: Upcoming articles query, moved from modules/articles_upcoming.php
?>
<tr>
  <td class=""><?php echo '<b>' . TEXT_CURRENT_ARTICLES . '</b>'; ?></td>
</tr>
<?php
  $articles_all_array = array();
  $articles_all_query_raw = "select a.articles_id, a.articles_date_added, a.articles_seo_url, ad.articles_name, ad.articles_head_desc_tag, au.authors_id, au.authors_name, td.topics_id, td.topics_name
                             from " . TABLE_ARTICLES . " a,
                                  " . TABLE_AUTHORS . " au,
                                  " . TABLE_ARTICLES_DESCRIPTION . " ad,
                                  " . TABLE_ARTICLES_TO_TOPICS . " a2t,
                                  " . TABLE_TOPICS_DESCRIPTION . " td
                             where a.articles_status = '1'
                               and a.authors_id = au.authors_id
                               and a.articles_id = a2t.articles_id
                               and a.articles_id = ad.articles_id
                               and a2t.topics_id = td.topics_id 
                               and ad.language_id = '" . (int)$languages_id . "'
                               and td.language_id = '" . (int)$languages_id . "'
                            order by a.articles_date_added desc, ad.articles_name";

  $articles_all_split = new splitPageResults($articles_all_query_raw, MAX_ARTICLES_PER_PAGE);
  if (($articles_all_split->number_of_rows > 0) && ((ARTICLE_PREV_NEXT_BAR_LOCATION == 'top') || (ARTICLE_PREV_NEXT_BAR_LOCATION == 'both'))) {
?>
<tr>
  <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="smallText"><?php echo $articles_all_split->display_count(TEXT_DISPLAY_NUMBER_OF_ARTICLES); ?></td>
        <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $articles_all_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
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
  <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <?php
  if ($articles_all_split->number_of_rows > 0) {
    $articles_all_query = tep_db_query($articles_all_split->sql_query);
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php
    while ($articles_all = tep_db_fetch_array($articles_all_query)) {
?>
      <tr>
        <td valign="top" class="main" width="75%"><?php
  echo '<a href="' . tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $articles_all['articles_id']) . '"><b>' . $articles_all['articles_name'] . '</b></a> ';
  //echo '<a href="articles/'.$articles_all['articles_seo_url'].'"><b>' . $articles_all['articles_name'] . '</b></a> ';
  if (DISPLAY_AUTHOR_ARTICLE_LISTING == 'true' && tep_not_null($articles_all['authors_name'])) {
   echo TEXT_BY . ' ' . '<a href="' . tep_href_link(FILENAME_ARTICLES, 'authors_id=' . $articles_all['authors_id']) . '"> ' . $articles_all['authors_name'] . '</a>';
  }
?>
        </td>
        <?php
      if (DISPLAY_TOPIC_ARTICLE_LISTING == 'true' && tep_not_null($articles_all['topics_name'])) {
?>
        <td valign="top" class="main" width="25%" nowrap><?php echo TEXT_TOPIC . '&nbsp;<a href="' . tep_href_link(FILENAME_ARTICLES, 'tPath=' . $articles_all['topics_id']) . '">' . $articles_all['topics_name'] . '</a>'; ?></td>
        <?php
      }
?>
      </tr>
      <?php
      if (DISPLAY_ABSTRACT_ARTICLE_LISTING == 'true') {
?>
      <tr>
        <td class="main" style="padding-left:15px"><?php echo clean_html_comments(substr($articles_all['articles_head_desc_tag'],0, MAX_ARTICLE_ABSTRACT_LENGTH)) . ((strlen($articles_all['articles_head_desc_tag']) >= MAX_ARTICLE_ABSTRACT_LENGTH) ? '...' : ''); ?></td>
      </tr>
      <?php
      }
      if (DISPLAY_DATE_ADDED_ARTICLE_LISTING == 'true') {
?>
      <tr>
        <td class="smalltext" style="padding-left:15px"><?php echo TEXT_DATE_ADDED . ' ' . tep_date_long($articles_all['articles_date_added']); ?></td>
      </tr>
      <?php
      }
      if (DISPLAY_ABSTRACT_ARTICLE_LISTING == 'true' || DISPLAY_DATE_ADDED_ARTICLE_LISTING) {
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php
 }
    } // End of listing loop
  } else {
?>
      <tr>
        <td class="main"><?php echo TEXT_NO_ARTICLES; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php
  }
?>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
    </table></td>
</tr>
<?php
  if (($articles_all_split->number_of_rows > 0) && ((ARTICLE_PREV_NEXT_BAR_LOCATION == 'bottom') || (ARTICLE_PREV_NEXT_BAR_LOCATION == 'both'))) {
?>
<tr>
  <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td class="smallText"><?php echo $articles_all_split->display_count(TEXT_DISPLAY_NUMBER_OF_ARTICLES); ?></td>
        <td align="right" class="smallText"><?php echo TEXT_RESULT_PAGE . ' ' . $articles_all_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
      </tr>
    </table></td>
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
</table>
<?php } ?>
<?php echo tep_get_design_body_footer();?>