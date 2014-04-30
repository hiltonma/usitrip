<?php
/*
  $Id: tools.php,v 1.1.1.2 2004/12/24 05:34:51 zip1 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- tools //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_TOOLS,
                     'link'  => tep_href_link(FILENAME_BACKUP_MYSQL, 'selected_box=tools'));

  if ($selected_box == 'tools' || $menu_dhtml == true) {
    $contents[] = array('text'  =>
//Admin begin
//                                   '<a href="' . tep_href_link(FILENAME_BACKUP) . '" class="menuBoxContentLink">' . BOX_TOOLS_BACKUP . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_BANNER_MANAGER) . '" class="menuBoxContentLink">' . BOX_TOOLS_BANNER_MANAGER . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_CACHE) . '" class="menuBoxContentLink">' . BOX_TOOLS_CACHE . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_DEFINE_LANGUAGE) . '" class="menuBoxContentLink">' . BOX_TOOLS_DEFINE_LANGUAGE . '</a><br>' .
                                   // MaxiDVD Added Line For WYSIWYG HTML Area: BOF
//                                   '<a href="' . tep_href_link(FILENAME_DEFINE_MAINPAGE, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_DEFINE_MAINPAGE . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_INFORMATION_MANAGER) . '" class="menuBoxContentLink">' . BOX_INFORMATION_MANAGER . '</a><br>' .
                                   // MaxiDVD Added Line For WYSIWYG HTML Area: EOF
//                                   '<a href="' . tep_href_link(FILENAME_FILE_MANAGER) . '" class="menuBoxContentLink">' . BOX_TOOLS_FILE_MANAGER . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_MAIL) . '" class="menuBoxContentLink">' . BOX_TOOLS_MAIL . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_NEWSLETTERS) . '" class="menuBoxContentLink">' . BOX_TOOLS_NEWSLETTER_MANAGER . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_SERVER_INFO) . '" class="menuBoxContentLink">' . BOX_TOOLS_SERVER_INFO . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_WHOS_ONLINE) . '" class="menuBoxContentLink">' . BOX_TOOLS_WHOS_ONLINE . '</a>');
			           			    
								   tep_admin_files_boxes(FILENAME_KEYWORDS, BOX_TOOLS_KEYWORDS) .
								   tep_admin_files_boxes(FILENAME_BACKUP_MYSQL, BOX_TOOLS_MYSQL_BACKUP) .
                                   tep_admin_files_boxes(FILENAME_BACKUP, BOX_TOOLS_BACKUP) .
                                   tep_admin_files_boxes(FILENAME_BANNER_MANAGER, BOX_TOOLS_BANNER_MANAGER) .
                                   tep_admin_files_boxes(FILENAME_CACHE, BOX_TOOLS_CACHE) .
                                   tep_admin_files_boxes(FILENAME_DEFINE_LANGUAGE, BOX_TOOLS_DEFINE_LANGUAGE) .
                               //    tep_admin_files_boxes(FILENAME_DEFINE_MAINPAGE, BOX_CATALOG_DEFINE_MAINPAGE) .
                                   tep_admin_files_boxes(FILENAME_INFORMATION_MANAGER, BOX_INFORMATION_MANAGER) .
                                   tep_admin_files_boxes(FILENAME_FILE_MANAGER, BOX_TOOLS_FILE_MANAGER) .
                                   tep_admin_files_boxes(FILENAME_MAIL, BOX_TOOLS_MAIL) .
                                   tep_admin_files_boxes(FILENAME_NEWSLETTERS, BOX_TOOLS_NEWSLETTER_MANAGER) .
                                   tep_admin_files_boxes(FILENAME_SERVER_INFO, BOX_TOOLS_SERVER_INFO) .
                                   tep_admin_files_boxes(FILENAME_WHOS_ONLINE, BOX_TOOLS_WHOS_ONLINE).
								   tep_admin_files_boxes('vote_system.php', TOURS_TOTE_SYSTEM).
								   tep_admin_files_boxes('email_track.php', EMAIL_TRACK_SYSTEM).
								   tep_admin_files_boxes('thesaurus.php', db_to_html('词库管理')).
								   tep_admin_files_boxes('seo_news.php', SEO_NEWS_SYSTEM).
								   tep_admin_files_boxes('update_data.php', DB_UPDATE_SYSTEM).
								   tep_admin_files_boxes('destination_guide.php', db_to_html('目的地指南管理')).
								   tep_admin_files_boxes('keywords_fault_tolerant.php', db_to_html('关键词容错表'))./*.
								   tep_admin_files_boxes('guestbook.php', db_to_html('留言本'))*/
    							   tep_admin_files_boxes('admin_login_logs.php',db_to_html('后台用户登录日志')).
								   tep_admin_files_boxes('raiders_type.php',db_to_html('攻略分类管理')).
								   tep_admin_files_boxes('raiders.php',db_to_html('攻略文章管理')).
								   tep_admin_files_boxes('raiders_tags.php',db_to_html('攻略标签管理'))
								   
								   
								   );
//Admin end
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- tools_eof //-->
