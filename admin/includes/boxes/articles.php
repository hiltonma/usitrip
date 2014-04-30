<?php
/*
  $Id: articles.php, v1.0 2003/12/04 12:00:00 ra Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce
  
  Chain Reaction Works, Inc
  Copyright &copy; 2005 - 2006 Chain Reaction Works, Inc.
  
  Last Modified by $Author$
  Last Modifed on : $Date$
  Latest Revision : $Revision: 4213 $

  Released under the GNU General Public License
*/

?>
<!-- articles //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_ARTICLES,
                     'link'  => tep_href_link(FILENAME_ARTICLES, 'selected_box=articles'));

  if ($selected_box == 'articles' || $menu_dhtml == true) {
      $contents[] = array('text' => tep_admin_files_boxes(FILENAME_ARTICLES,  BOX_TOPICS_ARTICLES)  .
                                    tep_admin_files_boxes(FILENAME_ARTICLES_CONFIG,  BOX_ARTICLES_CONFIG)  .
                                    tep_admin_files_boxes(FILENAME_AUTHORS, BOX_ARTICLES_AUTHORS)  .
                                    tep_admin_files_boxes(FILENAME_ARTICLE_REVIEWS,  BOX_ARTICLES_REVIEWS)  .
                                    tep_admin_files_boxes(FILENAME_ARTICLES_XSELL,  BOX_ARTICLES_XSELL));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- articles_eof //-->