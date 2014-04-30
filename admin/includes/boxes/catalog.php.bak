<?php
/*
  $Id: catalog.php,v 1.1.1.1 2004/03/04 23:39:43 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- catalog //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_CATALOG,
                     'link'  => tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog'));

  if ($selected_box == 'catalog' || $menu_dhtml == true) {
    $contents[] = array('text'  =>
//Admin begin
//                                   '<a href="' . tep_href_link(FILENAME_CATEGORIES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_CATEGORIES_PRODUCTS . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_PRODUCTS_ATTRIBUTES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_MANUFACTURERS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_MANUFACTURERS . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_REVIEWS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_REVIEWS . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_SPECIALS, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_SPECIALS . '</a><br>' .
//                                   '<a href="' . tep_href_link(FILENAME_PRODUCTS_EXPECTED, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_PRODUCTS_EXPECTED . '</a><br>' .
                                   // MaxiDVD Added Line For WYSIWYG HTML Area: BOF
//                                     '<a href="' . tep_href_link(FILENAME_DEFINE_MAINPAGE, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_DEFINE_MAINPAGE . '</a>');
                                   // MaxiDVD Added Line For WYSIWYG HTML Area: EOF
								   
                                   tep_admin_files_boxes('picture_db.php', 'Picture图片管理') .
                                   tep_admin_files_boxes(FILENAME_CATEGORIES, BOX_CATALOG_CATEGORIES_PRODUCTS) .
                                   tep_admin_files_boxes(FILENAME_PRODUCTS_ATTRIBUTES, BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES) .
                                   tep_admin_files_boxes(FILENAME_MANUFACTURERS, BOX_CATALOG_MANUFACTURERS) .
								  
								   //tep_admin_files_boxes(FILENAME_STATE, BOX_CATALOG_STATES) .
								   tep_admin_files_boxes(FILENAME_TRAVEL_AGENCY, BOX_CATALOG_TRAVEL_AGENCY) .
								   tep_admin_files_boxes(FILENAME_TOUR_PROVIDER_REGIONS, BOX_CATALOG_TOUR_PROVIDER_REGIONS) .
								    
								   /* tep_admin_files_boxes(FILENAME_AGE_TYPE, BOX_CATALOG_AGE) .
                                   tep_admin_files_boxes(FILENAME_HOTELS, BOX_CATALOG_HOTELS) .
								   tep_admin_files_boxes(FILENAME_LODGING_TYPE, BOX_CATALOG_LODGING_TYPE) .
								   tep_admin_files_boxes(FILENAME_OTHER, BOX_CATALOG_OTHER) . 
								   tep_admin_files_boxes(FILENAME_REVIEWS, BOX_CATALOG_REVIEWS) .		
								   tep_admin_files_boxes(FILENAME_TRAVELER_PHOTOS, BOX_CATALOG_PHOTOS) . */
                      		   	   tep_admin_files_boxes(FILENAME_EASYPOPULATE, BOX_CATALOG_EASYPOPULATE) .
                                   tep_admin_files_boxes(FILENAME_EASYPOPULATE_BASIC, BOX_CATALOG_EASYPOPULATE_BASIC) .
                                   tep_admin_files_boxes(FILENAME_SPECIALS, BOX_CATALOG_SPECIALS) .
                                   tep_admin_files_boxes(FILENAME_SHOPBYPRICE, BOX_CATALOG_SHOP_BY_PRICE) .
		                   		   tep_admin_files_boxes(FILENAME_XSELL_PRODUCTS, BOX_CATALOG_XSELL_PRODUCTS) .
                                   tep_admin_files_boxes(FILENAME_SALEMAKER, BOX_CATALOG_SALEMAKER) .
                                   tep_admin_files_boxes(FILENAME_FEATURED, BOX_CATALOG_FEATURED) .
								   tep_admin_files_boxes(FILENAME_FAMOUS_ATTRACTION_SUBCATEGORIES, BOX_FAMOUS_ATTRACTION_SUBCATEGORIES) .
                                   tep_admin_files_boxes(FILENAME_FAMOUS_ATTRACTION_PRODUCTS, BOX_FAMOUS_ATTRACTION_PRODUCTS) .
								   tep_admin_files_boxes('products_fast_list.php', '产品快速查询') .		
								   tep_admin_files_boxes(FILENAME_TOUR_CODE_DECODE, '供应商产品代码查询') .		
								   tep_admin_files_boxes(FILENAME_PRODUCT_CATEGORY_TYPE, BOX_PRODUCT_CATEGORY_TYPE) .	
                                   tep_admin_files_boxes(FILENAME_PRODUCTS_EXPECTED, BOX_CATALOG_PRODUCTS_EXPECTED).
								   tep_admin_files_boxes(FILENAME_LANDING_PAGES, BOX_LANDING_PAGES).
								   tep_admin_files_boxes(FILENAME_HOTEL_ADMIN, BOX_HOTEL_ADMIN).
								   tep_admin_files_boxes(FILENAME_BOX_CRUISES_ADMIN, BOX_CRUISES_ADMIN).
								   tep_admin_files_boxes('travel_companion.php', '结伴同行').
								   tep_admin_files_boxes('double_room_preferences.php', '双人折扣团管理').
								   tep_admin_files_boxes('buy_two_get_one.php', '买二送一（二）管理').
								   tep_admin_files_boxes('categories_meta_tag.php', 'Categories Meta Tags 管理').
								   tep_admin_files_boxes('start_city_to_end_city.php', START_CITY_TO_END_CITY_HEADING).
    							   tep_admin_files_boxes('expert_group.php','旅程设计专家团').
								   tep_admin_files_boxes('edit_products_video.php','产品视频管理').
								   tep_admin_files_boxes('edit_products_exclusive_specials.php','独家特惠管理').
    							   tep_admin_files_boxes('products_show_order_by.php', '前台页面产品排序管理').
    							   tep_admin_files_boxes('products_meta_tag.php', '产品KDT管理').
    							   tep_admin_files_boxes('regular_price.php', '产品常规价格快速更新')
								   );
//Admin end
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- catalog_eof //-->
