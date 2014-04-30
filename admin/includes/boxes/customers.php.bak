<?php
/*
  $Id: customers.php,v 1.1.1.1 2004/03/04 23:39:43 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- customers //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_CUSTOMERS,
                     'link'  => tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers'));

  if ($selected_box == 'customers' || $menu_dhtml == true) {
    $contents[] = array('text'  =>
//Admin begin

                                   tep_admin_files_boxes(FILENAME_CUSTOMERS, BOX_CUSTOMERS_CUSTOMERS) .
                                   tep_admin_files_boxes(FILENAE_INDIVIDUAL_SPACE, BOX_CUSTOMERS_INDIVIDUAL_SPACE).
								   tep_admin_files_boxes(FILENAME_DOMESTIC, BOX_CUSTOMERS_DOMESTIC).
								    tep_admin_files_boxes(FILENAME_NEWUI_FRAME, BOX_CUSTOMERS_POINTCARDS).
                                   tep_admin_files_boxes(FILENAME_TOUR_LEAD_QUESTION, BOX_CUSTOMERS_LEADS) .
				                   tep_admin_files_boxes(FILENAME_QUESTION_ANSWERS, BOX_CATALOG_QUESTION_ANSWERS) .
				                   tep_admin_files_boxes(FILENAME_ORDERS_CHECK, BOX_CUSTOMERS_ORDERS_CHECK) .
                                   tep_admin_files_boxes(FILENAME_ORDERS, BOX_CUSTOMERS_ORDERS) .
								   //E-ticket Log Start
								   tep_admin_files_boxes(FILENAME_ETICKET_LOG, BOX_HEADING_FILENAME_ETICKET_LOG) .
								   //E-ticket Log End
								   tep_admin_files_boxes(FILENAME_ORDERS_AGREE_RETURN, BOX_AGREE_RETURN_VISIT) .
                                   //begin PayPal_Shopping_Cart_IPN
                                   tep_admin_files_boxes(FILENAME_PAYPAL, BOX_CUSTOMERS_PAYPAL) .
                                   //end PayPal_Shopping_Cart_IPN
                                   tep_admin_files_boxes(FILENAME_CREATE_ACCOUNT, BOX_MANUAL_ORDER_CREATE_ACCOUNT) .
                                   tep_admin_files_boxes(FILENAME_CREATE_ORDER, BOX_MANUAL_ORDER_CREATE_ORDER) .
                                   tep_admin_files_boxes(FILENAME_CREATE_ORDERS_ADMIN, BOX_CREATE_ORDERS_ADMIN).
								   tep_admin_files_boxes(FILENAME_WAITLIST, TEXT_FILENAME_WAITLIST).
                                   tep_admin_files_boxes(FILENAME_OFFERS_SMS_NOTIFICATION, BOX_OFFERS_SMS_NOTIFICATION).
                                   tep_admin_files_boxes(FILENAME_OFFERS_SMS_CONTACT_GUEST, BOX_OFFERS_SMS_CONTACT_GUEST).
								   tep_admin_files_boxes(FILENAME_PHONE_BOOKING, BOX_PHONE_BOOKING).
								   tep_admin_files_boxes('customer_service_request.php', db_to_html('客户自定义服务需求')).
								   tep_admin_files_boxes('op_special_list.php', db_to_html('操作员需审核的订单列表')).
								   tep_admin_files_boxes('op_special_list.php?item=op_think_it_problem', db_to_html('操作员认为有问题的订单')).
								   tep_admin_files_boxes('visa.php?action=updatelistfromlujia', db_to_html('VISA_从路嘉网站更新订单列表')).
								   tep_admin_files_boxes('visa.php?action=search', db_to_html('VISA_签证订单管理')).
								   tep_admin_files_boxes('problem.php', BOX_HEADING_QUESTION).
								   tep_admin_files_boxes('other_order_get_by.php',db_to_html('自主订单归属人管理')).
								   tep_admin_files_boxes('pick_up_sms.php', db_to_html('上传接机导游信息'))
								   
								   );

//Admin end
  }
  $heading[] = array('text'  => BOX_HEADING_QUESTION,
                     'link'  => tep_href_link('question.php'));
					 
  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- customers_eof //-->
