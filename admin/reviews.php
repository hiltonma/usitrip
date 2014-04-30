<?php
/*
  $Id: reviews.php,v 1.1.1.1 2004/03/04 23:38:56 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */
function isdate($str, $format = "Y-m-d") {
	$strArr = explode("-", $str);
	if (empty($strArr)) {
		return false;
	}
	foreach ( $strArr as $val ) {
		if (strlen($val) < 2) {
			$val = "0" . $val;
		}
		$newArr[] = $val;
	}
	$str = implode("-", $newArr);
	$unixTime = strtotime($str);
	$checkDate = date($format, $unixTime);
	if ($checkDate == $str)
		return true;
	else
		return false;
}
function isdate2($str, $format = "Y-m-d H:i:s") {
	$strArr = explode(" ", $str);
	if (empty($strArr)) {
		return false;
	}

	if (! isdate($strArr[0]))
		return false;
	$strArr2 = explode(":", $strArr[1]);
	if (empty($strArr2)) {
		return false;
	}
	if (count($strArr2) == 3)
		return true;
	else
		false;
}

require('includes/application_top.php');

$action = (isset($_GET['action']) ? $_GET['action'] : '');

if (tep_not_null($action)) {
    switch ($action) {
        case 'submit_reply'://给评论回复
            $error = false;
            if (!(int) $_POST["parent_reviews_id"]) {
                $error = true;
                $error_msn .= db_to_html(general_to_ajax_string("无主帖ID "));
            }
            if (!tep_not_null($_POST["reviews_text"])) {
                $error = true;
                $error_msn .= db_to_html(general_to_ajax_string("请输入回复内容！"));
            }

            if ($error == true) {
                $js_str = '[JS]alert("' . $error_msn . '");[/JS]';
            } else {
                $customers_name = "usitrip";
                $sql_data_array = array('parent_reviews_id' => (int) $_POST["parent_reviews_id"], 'products_id' => (int) $_POST["products_id"], 'date_added' => date("Y-m-d H:i:s"), 'customers_name' => $customers_name, 'reviews_status' => "1", 'admin_id' => $login_id);
                tep_db_perform(TABLE_REVIEWS, $sql_data_array);
                $reviews_id = tep_db_insert_id();

                $reviews_text = ajax_to_general_string(tep_db_prepare_input($_POST["reviews_text"]));
                $sql_data_array = array('reviews_id' => $reviews_id, 'languages_id' => (int) $languages_id, 'reviews_text' => $reviews_text);
			
                tep_db_perform(TABLE_REVIEWS_DESCRIPTION, html_to_db($sql_data_array));

                if ($_POST["send_email_to_customers"] == "1" && tep_not_null($_POST["customers_email"])) {//给主帖人发邮件
                    $to_email_address = $to_name = tep_db_output($_POST["customers_email"]);
                    $email_subject = db_to_html("您在走四方网的评论已有回复啦！ ");
                    $from_email_name = "usitrip";
                    $from_email_address = "automail@usitrip.com";
                    $parent_reviews_text = ajax_to_general_string(tep_db_output($_POST['parent_reviews_text']));
                    $email_text = db_to_html("您的原帖：") . "\n" . $parent_reviews_text . "\n\n";
                    if ((int) $_POST["products_id"]) {
                        $links_str = tep_catalog_href_link("product_info.php?products_id=" . (int) $_POST["products_id"] . '&mnu=reviews');
                        $email_text .= db_to_html("参考网址：") . '<a href="' . $links_str . '">' . $links_str . '</a>' . "\n";
                    }
                    $email_text .= db_to_html("走四方回复：") . "\n" . html_to_db($reviews_text) . "\n\n";

                    tep_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address, 'true', CHARSET);
                }

                $js_str = '[JS]';
                $js_str .= 'jQuery("#reply_reviews_form").find("textarea").val("");';
                $js_str .= 'jQuery("#reply_reviews_form").find("input:checked").attr("checked", false);';
                $js_str .= 'jQuery("#reply_reviews_form").find(":submit").val("' . db_to_html(general_to_ajax_string("回复")) . '");';
                $js_str .= 'jQuery("#reply_reviews_form").find(":submit").attr("disabled", false);';
                $js_str .= 'alert("' . db_to_html(general_to_ajax_string("已成功回复此评论！")) . '");';
                $js_str .= 'location="'.tep_href_link('reviews.php').'";';
                $js_str .= '[/JS]';
            }
            $js_str = preg_replace('/[[:space:]]+/', ' ', $js_str);
            echo $js_str;
            exit;
            break;
        case 'setflag':
            if (($_GET['flag'] == '0') || ($_GET['flag'] == '1')) {

                if (isset($_GET['rID'])) {
                    $reviews_id = tep_db_prepare_input($_GET['rID']);
                    tep_db_query("update " . TABLE_REVIEWS . " set reviews_status = '" . $_GET['flag'] . "' where reviews_id = '" . $reviews_id . "'");

                    if ($_GET['flag'] == '1') {
                        $customer_query = tep_db_query("select customer_id, points_pending from " . TABLE_CUSTOMERS_POINTS_PENDING . " where points_status = 1 and points_type = 'RV' and orders_id = '" . (int) $reviews_id . "' limit 1");
                        $customer_points = tep_db_fetch_array($customer_query);
                        if (tep_db_num_rows($customer_query)) {

                            tep_db_query("update " . TABLE_CUSTOMERS_POINTS_PENDING . " set points_status = 2 where orders_id = '" . (int) $reviews_id . "' and points_type = 'RV' limit 1");
                            tep_auto_fix_customers_points((int)$customer_points['customer_id']);	//自动校正用户积分
							$sql = tep_db_query("optimize table " . TABLE_CUSTOMERS_POINTS_PENDING . "");

                            $messageStack->add_session(SUCCESS_POINTS_UPDATED, 'success');
                        }
                    }// end if
                }
            }
			if($_GET['ajax']=='true'){
                $js_str = '[JS]';
                $js_str .= 'show_this_child(' . (int) $_GET['parent_id'] . ',1); ';
                $js_str .= '[/JS]';
				$js_str = preg_replace('/[[:space:]]+/', ' ', $js_str);
				echo $js_str;
				exit;
			}else{
            	tep_redirect(tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $reviews_id . (isset($_GET['sortorder']) ? '&sortorder=' . $_GET['sortorder'] . '' : '')));
			}
			break;
        case 'update':
            $reviews_id = tep_db_prepare_input($_GET['rID']);
//$reviews_rating = tep_db_prepare_input($_POST['reviews_rating']);
            $reviews_text = tep_db_prepare_input($_POST['reviews_text']);
            $reviews_status = tep_db_prepare_input($_POST['reviews_status']);
            $reviews_title = tep_db_prepare_input($_POST['reviews_title']);
            $is_top = tep_db_prepare_input($_POST['is_top']);
            $is_essence = tep_db_prepare_input($_POST['is_essence']);
            $from_order_stauts = tep_db_prepare_input($_POST['from_order_stauts']);
			$has_purchased = (int)$_POST['has_purchased'];

            $rating_0 = $_POST['rating_0'];
            $rating_1 = $_POST['rating_1'];
            $rating_2 = $_POST['rating_2'];
            $rating_3 = $_POST['rating_3'];
            $rating_4 = $_POST['rating_4'];
            $rating_5 = $_POST['rating_5'];
            $rating = $rating_0 + $rating_1 + $rating_2 + $rating_3 + $rating_4 + $rating_5;

            $booking_rating = max((int) $_POST['starsResult1'], 1) * 20;
            $travel_rating = max((int) $_POST['starsResult2'], 1) * 20;
            $rating = min(100, ((int) ($booking_rating + $travel_rating) / 2));

//tep_db_query("update " . TABLE_REVIEWS . " set rating_total = '" . tep_db_input($rating) . "', reviews_status='" .$reviews_status. "', rating_0='".(int)$rating_0."', rating_1='".(int)$rating_1."', rating_2='".(int)$rating_2."', rating_3='".(int)$rating_3."', rating_4='".(int)$rating_4."', rating_5='".(int)$rating_5."', last_modified = now(), is_top='".(int)$is_top."', is_essence='".(int)$is_essence."' where reviews_id = '" . (int)$reviews_id . "'");
$str_sql="update " . TABLE_REVIEWS . " set rating_total = '" . tep_db_input($rating) . "', reviews_status='" . $reviews_status . "', booking_rating='" . (int) $booking_rating . "', travel_rating='" . (int) $travel_rating . "', last_modified = now(), is_top='" . (int) $is_top . "', is_essence='" . (int) $is_essence . "', from_order_stauts='1', admin_id='{$login_id}', has_purchased='".$has_purchased."'";
if(isset($_POST[add_time])&&isdate2($_POST[add_time])){
	$str_sql.=",date_added='$_POST[add_time]'";
}
$str_sql.=" where reviews_id = '" . (int) $reviews_id . "'";
            tep_db_query($str_sql);
            tep_db_query("update " . TABLE_REVIEWS_DESCRIPTION . " set reviews_title = '" . tep_db_input($reviews_title) . "', reviews_text = '" . tep_db_input($reviews_text) . "' where reviews_id = '" . (int) $reviews_id . "'");

//points

            if ($reviews_status == 1) {
                $customer_query = tep_db_query("select customer_id, points_pending from " . TABLE_CUSTOMERS_POINTS_PENDING . " where points_status = 1 and points_type = 'RV' and orders_id = '" . (int) $reviews_id . "' limit 1");
                $customer_points = tep_db_fetch_array($customer_query);

                if (tep_db_num_rows($customer_query)) {

                    tep_db_query("update " . TABLE_CUSTOMERS_POINTS_PENDING . " set points_status = 2 where orders_id = '" . (int) $reviews_id . "' and points_type = 'RV' limit 1");
                    tep_auto_fix_customers_points((int)$customer_points['customer_id']);	//自动校正用户积分
					$sql = tep_db_query("optimize table " . TABLE_CUSTOMERS_POINTS_PENDING . "");

                    $messageStack->add_session(SUCCESS_POINTS_UPDATED, 'success');
                }
            } //end if
//points
            if (isset($_POST['daction']) && $_POST['daction'] == 'return_visit') {
                $rv_isend = isset($_POST['rv_isend']) ? intval($_POST['rv_isend']) : 0;
                if ($rv_isend != '1') {//未完成回复
                    tep_redirect(tep_href_link(FILENAME_REVIEWS, 'action=add&oID=' . $_POST['oID']));
                } else {//已完成回复
                    tep_db_query("update `orders` set is_ret='1' WHERE `orders_id`='{$_POST['oID']}'");
                    tep_redirect(tep_href_link(FILENAME_ORDERS, 'agret=1&action=edit&oID=' . $_POST['oID']));
                }
            } else {
                tep_redirect(tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $reviews_id));
            }
            break;
        case 'deleteconfirm':
            $reviews_id = tep_db_prepare_input($_GET['rID']);

            tep_db_query("delete from " . TABLE_REVIEWS . " where reviews_id = '" . (int) $reviews_id . "'");
            tep_db_query("delete from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . (int) $reviews_id . "'");
            tep_db_query("optimize table " . TABLE_REVIEWS );
			if ($_GET['ajax'] == 'true') {
                $js_str = '[JS]';
                $js_str .= 'show_this_child(' . (int) $_GET['parent_reviews_id'] . ',1)';
                $js_str .= '[/JS]';
                $js_str = preg_replace('/[[:space:]]+/', ' ', $js_str);
                echo $js_str;
                exit;
            } else {
                tep_redirect(tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page']));
            }
            break;
    }
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
        <title><?php echo TITLE; ?></title>
        <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
        <script language="javascript" src="includes/menu.js"></script>
        <script language="javascript" src="includes/big5_gb-min.js"></script>
        <script language="javascript" src="includes/general.js"></script>
        <script type="text/javascript" src="includes/jquery-1.3.2/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="includes/javascript/calendar.js"></script>
        <script type="text/javascript">
            //提交回复内容
<?php
$p = array('/&amp;/', '/&quot;/');
$r = array('&', '"');
?>
            function submit_reply(form_id){
                var from = document.getElementById(form_id);
                var error = false;
                if(from.elements["reviews_text"].value.length<1){
                    error = true;
                    error_msn = "<?php echo db_to_html("请输入回复内容！"); ?>";
                }
                if(error == true){ alert(error_msn); return false;}
                jQuery("#"+form_id).find(":submit").val("<?php echo db_to_html("正在提交回复……"); ?>");
                jQuery("#"+form_id).find(":submit").attr("disabled",true);
                var url = "<?php echo preg_replace($p, $r, tep_href_link('reviews.php', 'ajax=true&action=submit_reply')) ?>";
                var success_msm="";
                var success_go_to="";
                var replace_id="";
                ajax_post_submit(url,form_id,success_msm,success_go_to, replace_id);
                return true;
            }
            //快速删除子评论
            function fast_del_reviews(parent_reviews_id, reviews_id){
                if(reviews_id>0 && parent_reviews_id>0){
                    var url = "<?php echo preg_replace($p, $r, tep_href_link_noseo('reviews.php', 'ajax=true&action=deleteconfirm')) ?>";
                    url+= '&rID='+reviews_id;
                    url+= '&parent_reviews_id='+parent_reviews_id;
                    var success_msm ="";
                    var success_go_to ="";
                    var replace_id ="";
                    ajax_get_submit(url,success_msm,success_go_to,replace_id);
                }
            }
            //显示某个评论的所有子评论
            function show_this_child(reviews_id, Refresh){
				var child_tr = document.getElementById('child_tr_'+reviews_id);
                var child_div = document.getElementById('child_div_'+reviews_id);
                if(child_tr==null || child_div==null){ return false;}
                if(child_tr.style.display!="none" && Refresh!=1 ){
                    child_tr.style.display="none";
                }else{
                    child_tr.style.display="";
					var tmp=/\s+/g;  
					var newstr=child_div.innerHTML.replace(tmp,"");
					if(newstr.length<15 || Refresh==1){	//需要查询数据库
var url = "<?php echo preg_replace($p, $r, tep_href_link_noseo('reviews_ajax.php', 'ajax=true')) ?>";
                        url+= '&reviews_id='+reviews_id;
                        var success_msm ="";
                        var success_go_to ="";
                        var replace_id ="";
                        ajax_get_submit(url,success_msm,success_go_to,replace_id);
                    }
                }
            }
            //快速设置评论的状态
            function fast_set_status(reviews_id, parent_id, set_val){
                if(reviews_id>0){
                    var url = "<?php echo preg_replace($p, $r, tep_href_link_noseo('reviews.php', 'ajax=true&action=setflag')) ?>";
                    url+= '&flag='+set_val;
                    url+= '&rID='+reviews_id;
                    url+= '&parent_id='+parent_id;
                    var success_msm ="";
                    var success_go_to ="";
                    var replace_id ="";
                    ajax_get_submit(url,success_msm,success_go_to,replace_id);
                }
            }
        </script>
    </head>
    <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
        <!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
        <!-- header_eof //-->

        <!-- body //-->
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
if ($action == 'edit' || $action == 'add') {
    $rID = tep_db_prepare_input($_GET['rID']);
///=========dleno add=====================
    if ($action == 'add') {
        $daction = 'return_visit';
        $oID = tep_db_prepare_input($_GET['oID']);
        $order_data = tep_db_fetch_array(tep_db_query("SELECT o.`customers_id`,o.`customers_name`,o.`customers_email_address`,orv.`prodcuts_ids` FROM `orders` o,`orders_return_visit` orv WHERE o.`orders_id`=orv.`orders_id` and o.`orders_id`='{$oID}'"));
        $prodcuts_ids = explode(',', $order_data['prodcuts_ids']);
        $customers_id = $order_data['customers_id'];
        $customers_name = $order_data['customers_name'];
        $customers_email = $order_data['customers_email_address'];
        $count_orders_prodcut = count($prodcuts_ids);
        $this_orders_prodcut = 0;
        foreach ($prodcuts_ids as $prodcuts_id) {
            $this_orders_prodcut++;
            $order_pro_reviews = tep_db_fetch_array(tep_db_query("select r.reviews_id,r.from_order_stauts from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.products_id = '{$prodcuts_id}' and r.orders_id='{$oID}' and r.reviews_id = rd.reviews_id"));
            if ($order_pro_reviews) {
                $rID = $order_pro_reviews['reviews_id'];
                if (!$order_pro_reviews['from_order_stauts']) {
                    break;
                }
            } else {
                tep_db_query("insert into " . TABLE_REVIEWS . " set `parent_reviews_id`='0', `products_id`='{$prodcuts_id}', `customers_id`='{$customers_id}', `customers_name`='{$customers_name}', `date_added`=NOW(), `last_modified`=NOW(), `reviews_status`='0', `customers_email`='{$customers_email}', `orders_id`='{$oID}',`from_order_stauts`='0'");
                $rID = tep_db_insert_id();
                $retExtText = "(#Order {$oID} and #Prodcuts {$prodcuts_id}) Return Visit ";
                tep_db_query("insert into " . TABLE_REVIEWS_DESCRIPTION . " set `reviews_id`='{$rID}',`languages_id`='" . (int) $languages_id . "',`reviews_text`='{$retExtText}Content',`reviews_title`='{$retExtText}Title'");
                break;
            }
        }
    }
    $action = 'edit';
///=========dleno add end=================
    $reviews_query = tep_db_query("select r.*, rd.reviews_text, rd.reviews_title from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . (int) $rID . "' and r.reviews_id = rd.reviews_id");
    $reviews = tep_db_fetch_array($reviews_query);

    $products_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int) $reviews['products_id'] . "'");
    $products = tep_db_fetch_array($products_query);

    $products_name_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int) $reviews['products_id'] . "' and language_id = '" . (int) $languages_id . "'");
    $products_name = tep_db_fetch_array($products_name_query);


    $rInfo_array = array_merge($reviews, $products, $products_name);
    $rInfo = new objectInfo($rInfo_array);
    if (!isset($rInfo->reviews_status))
        $rInfo->reviews_status = '1';
    switch ($rInfo->reviews_status) {
        case '0': $in_status = false;
            $out_status = true;
            break;
        case '1':
        default: $in_status = true;
            $out_status = false;
    }
?>
                        <tr><?php echo tep_draw_form('review', FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $_GET['rID'] . '&action=preview'); ?>
                            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="main" valign="top"><b><?php echo ENTRY_PRODUCT; ?></b> <?php echo $rInfo->products_name; ?><br><b><?php echo ENTRY_FROM; ?></b> <?php echo $rInfo->customers_name; ?><br><br>
										<b><?php echo ENTRY_DATE; ?></b><?php if(!$can_update_question_date){ echo tep_date_short($rInfo->date_added);}else{?>
										<input type="text" value="<?php echo $rInfo->date_added;?>" name="add_time" /> 格式 2011-11-11 11:11:11
										<?php } ?></td>
                                        <td class="main" align="right" valign="top"><?php echo tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $rInfo->products_image, $rInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"'); ?></td>
                                    </tr>
                                </table></td>
                        </tr>
                        <tr>
                            <td><table witdh="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td class="main"><b><?php echo ENTRY_REVIEW_STATUS; ?></b>
                                            <?php
                                            if ($daction != 'return_visit'
                                                )echo tep_draw_radio_field('reviews_status', '1', $in_status) . '&nbsp;On&nbsp;';
                                            echo tep_draw_radio_field('reviews_status', '0', $out_status) . 'Off';
                                            if ($daction == 'return_visit') {
                                                echo tep_draw_hidden_field('oID', $oID);
                                                echo tep_draw_hidden_field('daction', $daction);
                                                if ($this_orders_prodcut >= $count_orders_prodcut) {
                                                    echo tep_draw_hidden_field('rv_isend', '1');
                                                }
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                                    </tr>


                                    <tr>
                                        <td class="main" valign="top"><b><?php echo ENTRY_REVIEW_TITLE; ?></b><br><?php echo tep_draw_input_field('reviews_title', $rInfo->reviews_title, 'size=60'); ?></td>
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
                                    <tr>
                                        <td class="main"><b><?php echo HAS_PURCHASED;?></b>
                                            <?php
                                            $has_purchased = $rInfo->has_purchased;
											((int)$has_purchased) ? $zero_c = false : $zero_c = true;
											echo tep_draw_radio_field('has_purchased', '0', $zero_c) . SET_NULL.'&nbsp;';
                                            echo tep_draw_radio_field('has_purchased', '1') . WILL_TO.'&nbsp;';
                                            echo tep_draw_radio_field('has_purchased', '2') . HAS_TO.'&nbsp;';
											echo HAS_PURCHASED_TIP;
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="main" valign="top"><b>Set Top</b> 
                                            <?php
                                            $is_top_1_ch = $is_top_0_ch = false;
                                            (int) $rInfo->is_top ? $is_top_1_ch = true : $is_top_0_ch = true;
                                            echo tep_draw_radio_field('is_top', '1', $is_top_1_ch) . '&nbsp;Yes&nbsp;' . tep_draw_radio_field('is_top', '0', $is_top_0_ch) . 'No';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="main" valign="top"><b>Set Essence</b> 
                                            <?php
                                            $is_essence_1_ch = $is_essence_0_ch = false;
                                            (int) $rInfo->is_essence ? $is_essence_1_ch = true : $is_essence_0_ch = true;
                                            echo tep_draw_radio_field('is_essence', '1', $is_essence_1_ch) . '&nbsp;Yes&nbsp;' . tep_draw_radio_field('is_essence', '0', $is_essence_0_ch) . 'No';
                                            ?>
                                        </td>
                                    </tr>
                                </table></td>
                        </tr>
                        <tr>
                            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                        </tr>
                        <tr>
                            <td><b>评分</b></td>
                        </tr>
                        <tr>
                            <td class="main"><b>订购</b>&nbsp;<?php echo TEXT_BAD; ?>&nbsp;
                                <?php
                                            $starsResult1 = ($rInfo->booking_rating / 20);
                                            if (!(int) $rInfo->booking_rating) {
                                                $starsResult1 = 5;
                                            }
                                            for ($i = 1; $i <= 5; $i++)
                                                echo tep_draw_radio_field('starsResult1', $i) . '&nbsp;'; echo TEXT_GOOD;
                                ?>
                                        </td>            
                                    </tr>            
                                    <tr>            
                                        <td class="main"><b>出行</b>&nbsp;<?php echo TEXT_BAD; ?>&nbsp;
                                <?php
                                            $starsResult2 = ($rInfo->travel_rating / 20);
                                            if (!(int) $rInfo->travel_rating) {
                                                $starsResult2 = 5;
                                            }
                                            for ($i = 1; $i <= 5; $i++)
                                                echo tep_draw_radio_field('starsResult2', $i) . '&nbsp;'; echo TEXT_GOOD;
                                ?>
                                                </td>                    
                                            </tr>                    
                        <?php
                                            /* 取消旧的评级信息
                                              $reviews_array = get_reviews_array();
                                              for($i=0; $i<count($reviews_array); $i++){
                                              ?>
                                              <tr>
                                              <td class="main"><b><?php echo db_to_html($reviews_array[$i]['title'])?>[Old Version]</b>&nbsp;

                                              <?php
                                              foreach($reviews_array[$i]['opction'] as $key_val => $text){
                                              $rating_checked = '';
                                              $rating_var = 'rating_'.$i;
                                              $$rating_var = $rInfo->$rating_var;
                                              if($$rating_var == $key_val){
                                              $rating_checked = ' checked ';
                                              }
                                              echo '<label><input name="rating_'.$i.'" value="'.$key_val.'" type="radio" class="required" title="'.db_to_html('请为'.$reviews_array[$i]['title'].'选择一个评级').'" '.$rating_checked.'> '.db_to_html($text).'</label> ';
                                              }
                                              ?>
                                              </td>
                                              </tr>
                                              <?
                                              } */
                        ?>
                                            <tr>                    
                                                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                                            </tr>                    
                                            <tr>                    
                                                <td align="right" class="main"><?php echo tep_draw_hidden_field('reviews_id', $rInfo->reviews_id) . tep_draw_hidden_field('products_id', $rInfo->products_id) . tep_draw_hidden_field('customers_name', $rInfo->customers_name) . tep_draw_hidden_field('products_name', $rInfo->products_name) . tep_draw_hidden_field('products_image', $rInfo->products_image) . tep_draw_hidden_field('date_added', $rInfo->date_added) . tep_image_submit('button_preview.gif', IMAGE_PREVIEW) . ' <a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $_GET['rID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
                                                </form></tr>                    
                    
<?php
//回复
                                            if ($rInfo->parent_reviews_id == "0" && $rInfo->reviews_status == "1") {
?>
                                <tr>        
                                    <td>        
                                        <div><form id="reply_reviews_form" method="post" action="" name="reply_reviews_form" onSubmit="submit_reply(this.id); return false;">您可以针对此帖进行回复(只是回复，不对主帖进行更新)：<br><?php echo tep_draw_textarea_field('reviews_text', 'soft', '60', '5') ?><br><label><input name="send_email_to_customers" type="checkbox" value="1">同时给对方发电子邮件</label>
                                                <input name="parent_reviews_id" type="hidden" value="<?= $rID ?>">
                                                <input name="products_id" type="hidden" value="<?= $rInfo->products_id ?>">
                                        <?php
                                                $customers_email = $rInfo->customers_email;
                                                if (!tep_not_null($customers_email)) {
                                                    $customers_email = tep_get_customers_email($rInfo->customers_id);
                                                }
                                        ?>
                                                                <input type="hidden" name="customers_email" value="<?= $rInfo->customers_email ?>">
                        <?php echo tep_draw_hidden_field('parent_reviews_text', $rInfo->reviews_text); ?>
                                                                <br><input type="submit" value="回复" /></form></div>                        
                                                    </td>                        
                                                </tr>                        
                        <?php
                                            }
                        ?>
                        <?php
                                        } elseif ($action == 'preview') {
                                            if (tep_not_null($_POST)) {
                                                $rInfo = new objectInfo($_POST);
                                            } else {
                                                $rID = tep_db_prepare_input($_GET['rID']);

                                                $reviews_query = tep_db_query("select r.*, rd.reviews_text, rd.reviews_title from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . (int) $rID . "' and r.reviews_id = rd.reviews_id");
                                                $reviews = tep_db_fetch_array($reviews_query);

                                                $products_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int) $reviews['products_id'] . "'");
                                                $products = tep_db_fetch_array($products_query);

                                                $products_name_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int) $reviews['products_id'] . "' and language_id = '" . (int) $languages_id . "'");
                                                $products_name = tep_db_fetch_array($products_name_query);

                                                $rInfo_array = array_merge($reviews, $products, $products_name);
                                                $rInfo = new objectInfo($rInfo_array);
                                            }
                        ?>
                                            <tr><?php echo tep_draw_form('update', FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $_GET['rID'] . '&action=update', 'post', 'enctype="multipart/form-data"'); ?>
                                                <td><table border="0" width="100%" cellspacing="0" cellpadding="0">                    
                                                        <tr>                    
                                                            <td class="main" valign="top"><b><?php echo ENTRY_PRODUCT; ?></b> <?php echo $rInfo->products_name; ?><br><b><?php echo ENTRY_FROM; ?></b> <?php echo $rInfo->customers_name; ?><br><br><b><?php echo ENTRY_DATE; ?></b> <?php if(isset($rInfo->add_time)){echo tep_date_short($rInfo->add_time);}else{echo tep_date_short($rInfo->date_added);} ?></td>
                                                            <td class="main" align="right" valign="top"><?php echo tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . $rInfo->products_image, $rInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"'); ?></td>
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
                                                            <td valign="top" class="main"><b><?php echo ENTRY_REVIEW; ?></b><br><br><?php echo nl2br(tep_db_output($rInfo->reviews_text)); ?></td>
                                                        </tr>                    
                                                    </table></td>                    
                                            </tr>                    
                    
                                            <tr>                    
                                                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                                    </tr>            
                                <?php
                                            if (tep_not_null($_POST)) {
                                ?>
                                        <tr>                
                                            <td align="right" class="smallText">                
                        <?php
                                                /* Re-Post all POST'ed variables */
                                                reset($_POST);
                                                while (list($key, $value) = each($_POST))
                                                    echo tep_draw_hidden_field($key, $value);
                        ?>
                        <?php echo '<a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=edit') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> ' . tep_image_submit('button_update.gif', IMAGE_UPDATE) . ' <a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
                                                    </form></tr>                        
                        <?php
                                            } else {
                                                if (isset($_GET['origin'])) {
                                                    $back_url = $_GET['origin'];
                                                    $back_url_params = '';
                                                } else {
                                                    $back_url = FILENAME_REVIEWS;
                                                    $back_url_params = 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id;
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
                                                <td>
												
<fieldset>
                        <legend align="left"> Search Module </legend>
<?php echo tep_draw_form('form_search', 'reviews.php', tep_get_all_get_params(array('page', 'y', 'x', 'action')), 'get'); ?>

                        <table border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td align="right" nowrap class="main">显示方式：</td>
                              <td colspan="7" align="left" class="main"><?php echo tep_draw_radio_field('search_show_type','0').db_to_html('显示所有帖&nbsp;&nbsp;').tep_draw_radio_field('search_show_type','1').db_to_html('只显示主帖&nbsp;&nbsp;').tep_draw_radio_field('search_show_type','2').db_to_html('只显示子帖&nbsp;&nbsp;');?>&nbsp;</td>
                            </tr>
                            <tr>
                            	<td align="right" nowrap class="main"><?php echo HAS_PURCHASED?></td>
                            	<td colspan="7" align="left" class="main">
								<?php
								(!isset($_GET['search_has_purchased']) || $_GET['search_has_purchased']=='all') ? $all_c = true : $all_c = false;
								
								echo tep_draw_radio_field('search_has_purchased', 'all', $all_c) . 'All&nbsp;';
								echo tep_draw_radio_field('search_has_purchased', 'null') . SET_NULL.'&nbsp;';
								echo tep_draw_radio_field('search_has_purchased', 'willto') . WILL_TO.'&nbsp;';
								echo tep_draw_radio_field('search_has_purchased', 'hasto') . HAS_TO.'&nbsp;';
								
								?>
								
								&nbsp;</td>
                            	</tr>
                            <tr>
                                
								<td align="right" nowrap class="main">团ID号：</td>
                                <td align="left" class="main"><?php echo tep_draw_input_field('search_products_id');?>&nbsp;</td>
                                <td align="left" class="main">&nbsp;</td>
								<td align="right" nowrap class="main">客人邮箱：</td>
                                <td align="left" class="main"><?php echo tep_draw_input_field('search_customers_email_address');?>&nbsp;</td>
                                <td align="left" class="main">&nbsp;</td>
								
								<td align="right" nowrap class="main">评论主题：</td>
                                <td align="left" class="main"><?php echo tep_draw_input_field('search_reviews_title');?>&nbsp;</td>
                                </tr>
								<tr>
                                <td align="right" nowrap class="main">置顶：</td>
                                <td align="left" class="main">
								<?php
								$is_top_options = array();
								$is_top_options[] = array('id'=>'0','text'=>'不限');
								$is_top_options[] = array('id'=>'1','text'=>'置顶帖');
								$is_top_options[] = array('id'=>'2','text'=>'非置顶帖');
								echo tep_draw_pull_down_menu('search_is_top', $is_top_options);
								?>								</td>
                                <td align="left" class="main">&nbsp;</td>
                                <td align="right" nowrap class="main">精华：</td>
                                <td align="left" class="main">
								<?php
								$is_essence_options = array();
								$is_essence_options[] = array('id'=>'0','text'=>'不限');
								$is_essence_options[] = array('id'=>'1','text'=>'精华帖');
								$is_essence_options[] = array('id'=>'2','text'=>'非精华帖');
								echo tep_draw_pull_down_menu('search_is_essence', $is_essence_options);
								?>								</td>
                                <td align="left" class="main">&nbsp;</td>
                                <td align="right" nowrap class="main">状态：</td>
                                <td align="left" class="main">
								<?php
								$reviews_status_options = array();
								$reviews_status_options[] = array('id'=>'0','text'=>'不限');
								$reviews_status_options[] = array('id'=>'1','text'=>'已审核');
								$reviews_status_options[] = array('id'=>'2','text'=>'未通过审核');
								echo tep_draw_pull_down_menu('search_reviews_status', $reviews_status_options);
								?>								</td>
                            </tr>
								<tr>
								  <td align="right" nowrap class="main">评论时间：</td>
								  <td colspan="7" align="left" class="main"><?php echo tep_draw_input_field('search_date_added_start','',' onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" ')?>&nbsp;至&nbsp;<?php echo tep_draw_input_field('search_date_added_end','',' onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" ')?></td>
						  </tr>
								<tr>
								  <td align="left" nowrap class="main"><input name="search" type="hidden" value="1"><button type="submit">确定</button></td>
								  <td align="left" class="main">&nbsp;</td>
								  <td align="left" class="main">&nbsp;</td>
								  <td align="left" nowrap class="main">&nbsp;</td>
								  <td align="left" class="main">&nbsp;</td>
								  <td align="left" class="main">&nbsp;</td>
								  <td align="left" nowrap class="main">&nbsp;</td>
								  <td align="left" class="main">&nbsp;</td>
						  </tr>
                        </table>

<?php echo '</form>'; ?>
                    </fieldset>												<table border="0" width="100%" cellspacing="0" cellpadding="0">                    
                                                        <tr>                    
                                                            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">                    
                                                                    <tr class="dataTableHeadingRow">                    
                                                                        <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                                                                        <td class="dataTableHeadingContent"><?php echo db_to_html('置顶') ?></td>
                                                                        <td class="dataTableHeadingContent"><?php echo db_to_html('精华') ?></td>
                                                                        <td class="dataTableHeadingContent"><?php echo db_to_html('回复') ?></td>
                                                    <td class="dataTableHeadingContent"><?php echo db_to_html('评论') ?></td>
                                                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS_EMAIL; ?></td>
                                                    <td class="dataTableHeadingContent" align="right"><?php echo db_to_html('满意度'); ?></td>
                                                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_DATE_ADDED; ?></td>
                                                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_REVIEW_STATUS; ?></td>              
                                                    <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
                                                </tr>
                                                <?php
												$select = ' r.*, rd.reviews_title, rd.reviews_text ';
												$table = TABLE_REVIEWS.' r, '.TABLE_REVIEWS_DESCRIPTION.' rd ';
												$where = ' rd.reviews_id = r.reviews_id AND r.from_order_stauts="1" and rd.languages_id ="1" ';
												$order_by = ' r.date_added DESC ';
												$group_by = ' r.reviews_id ';
												//--------搜索 start-------
												if((int)$_GET['search_show_type']=='1'){
													$where .= ' AND r.parent_reviews_id="0" ';
												}elseif((int)$_GET['search_show_type']=='2'){
													$where .= ' AND r.parent_reviews_id >0 ';
												}
												if((int)$_GET['search_is_top']=='1'){
													$where .= ' AND r.is_top ="1" ';
												}elseif((int)$_GET['search_is_top']=='2'){
													$where .= ' AND r.is_top ="0" ';
												}
												if((int)$_GET['search_is_essence']=='1'){
													$where .= ' AND r.is_essence ="1" ';
												}elseif((int)$_GET['search_is_essence']=='2'){
													$where .= ' AND r.is_essence ="0" ';
												}
												if((int)$_GET['search_reviews_status']=='1'){
													$where .= ' AND r.reviews_status ="1" ';
												}elseif((int)$_GET['search_reviews_status']=='2'){
													$where .= ' AND r.reviews_status ="0" ';
												}
												if((int)$_GET['search_products_id']){
													$where .= ' AND r.products_id ="'.(int)$_GET['search_products_id'].'" ';
												}
												if(tep_not_null($_GET['search_customers_email_address'])){
													$table .= ', '.TABLE_CUSTOMERS.' c ';
													$where .= ' AND c.customers_id = r.customers_id AND c.customers_email_address Like Binary "%'.$_GET['search_customers_email_address'].'%" ';
												}
												if(tep_not_null($_GET['search_reviews_title'])){
													$where .= ' AND rd.reviews_title Like Binary "%'.$_GET['search_reviews_title'].'%" ';
												}
												if(tep_not_null($_GET['search_date_added_start'])){
													$where .= ' AND r.date_added >= "'.$_GET['search_date_added_start'].' 00:00:00" ';
												}
												if(tep_not_null($_GET['search_date_added_end'])){
													$where .= ' AND r.date_added <= "'.$_GET['search_date_added_end'].' 23:59:59" ';
												}
												if(tep_not_null($_GET['search_has_purchased']) && $_GET['search_has_purchased']!="all"){
													switch($_GET['search_has_purchased']){
														case 'null': $where .= ' AND r.has_purchased ="0" '; break;
														case 'willto': $where .= ' AND r.has_purchased ="1" '; break;
														case 'hasto': $where .= ' AND r.has_purchased ="2" '; break;
														default: break;
													}
												}
												//--------搜索 end-------
												
												$reviews_query_raw = "select {$select} from {$table} where {$where} group by {$group_by} order by {$order_by}";
												//echo $reviews_query_raw;
                                                $reviews_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $reviews_query_raw, $reviews_query_numrows);
                                                $reviews_query = tep_db_query($reviews_query_raw);
                                                while ($reviews = tep_db_fetch_array($reviews_query)) {
                                                    $child_sql = tep_db_query('select count(*) as total from ' . TABLE_REVIEWS . ' where parent_reviews_id="' . (int) $reviews['reviews_id'] . '" ');
                                                    $child_row = tep_db_fetch_array($child_sql);
                                                    $reviews['child_reviews_num'] = $child_row['total'];

                                                    if ((!isset($_GET['rID']) || (isset($_GET['rID']) && ($_GET['rID'] == $reviews['reviews_id']))) && !isset($rInfo)) {
                                                        $reviews_text_query = tep_db_query("select r.reviews_read, r.customers_name, length(rd.reviews_text) as reviews_text_size from " . TABLE_REVIEWS . " r, " . TABLE_REVIEWS_DESCRIPTION . " rd where r.reviews_id = '" . (int) $reviews['reviews_id'] . "' and r.reviews_id = rd.reviews_id");
                                                        $reviews_text = tep_db_fetch_array($reviews_text_query);

                                                        $products_image_query = tep_db_query("select products_image from " . TABLE_PRODUCTS . " where products_id = '" . (int) $reviews['products_id'] . "'");
                                                        $products_image = tep_db_fetch_array($products_image_query);

                                                        $products_name_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int) $reviews['products_id'] . "' and language_id = '" . (int) $languages_id . "'");
                                                        $products_name = tep_db_fetch_array($products_name_query);

//$reviews_average_query = tep_db_query("select (avg(reviews_rating) / 5 * 100) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int)$reviews['products_id'] . "'");
                                                        $reviews_average_query = tep_db_query("select avg(rating_total) as average_rating from " . TABLE_REVIEWS . " where products_id = '" . (int) $reviews['products_id'] . "'");
                                                        $reviews_average = tep_db_fetch_array($reviews_average_query);

                                                        $review_info = array_merge($reviews_text, $reviews_average, $products_name);
                                                        $rInfo_array = array_merge($reviews, $review_info, $products_image);
                                                        $rInfo = new objectInfo($rInfo_array);
                                                    }

                                                    if (isset($rInfo) && is_object($rInfo) && ($reviews['reviews_id'] == $rInfo->reviews_id)) {
                                                        echo '              <tr id="defaultSelected" class="dataTableRowSelected" onclick="show_this_child(' . $reviews['reviews_id'] . ')" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" >' . "\n";
                                                        $use_long_tr = true;
                                                    } else {
                                                        echo '              <tr class="dataTableRow" onclick="show_this_child(' . $reviews['reviews_id'] . ')" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" >' . "\n";
                                                        $use_long_tr = true;
                                                    }
                                                ?>
<?php if ($use_long_tr != true) { ?>
                                                    <tr>    
<?php } ?>

                                                    <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $reviews['reviews_id'] . '&action=preview') . '">' . tep_image(DIR_WS_ICONS . 'preview.gif', ICON_PREVIEW) . '</a>&nbsp;'; ?><?php echo "<a target='_blank' href=" . HTTP_CATALOG_SERVER . "/" . seo_get_products_path($reviews['products_id'], true) . ">" . tep_get_products_model($reviews['products_id']) . "</a>"; ?></td>
                                                    <td class="dataTableContent" ><?php echo (int) $reviews['is_essence'] ? "精华" : "&nbsp;"; ?></td>
                                                    <td class="dataTableContent" ><?php echo (int) $reviews['is_top'] ? "置顶" : "&nbsp;"; ?></td>
                                                    <td class="dataTableContent" ><?php echo (int) $reviews['child_reviews_num']; ?></td>
                                                    <td class="dataTableContent" ><p style="padding:5px;"><b><?php echo tep_db_output($reviews['reviews_title']); ?></b><br><?php echo tep_db_output($reviews['reviews_text']); ?></p></td>
                                                    <td class="dataTableContent" ><?php echo $reviews['customers_email']; ?></td>
                                                    <td class="dataTableContent" align="right">
                                                        <?php //echo tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . 'stars_' . $reviews['reviews_rating'] . '.gif'); ?>
                                                        <?php echo $reviews['rating_total'] . '%'; ?>				</td>



                                                    <td class="dataTableContent" align="right"><?php echo tep_date_short($reviews['date_added']); ?></td>
                                                    <td class="dataTableContent" align="center">
<?php
                                                        if ($reviews['reviews_status'] == '1') {
                                                            echo tep_image(DIR_WS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_REVIEWS, 'action=setflag&flag=0&rID=' . $reviews['reviews_id'] . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['sortorder']) ? '&sortorder=' . $_GET['sortorder'] . '' : '')) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
                                                        } else {
                                                            echo '<a href="' . tep_href_link(FILENAME_REVIEWS, 'action=setflag&flag=1&rID=' . $reviews['reviews_id'] . (isset($_GET['page']) ? '&page=' . $_GET['page'] . '' : '') . (isset($_GET['sortorder']) ? '&sortorder=' . $_GET['sortorder'] . '' : '')) . '">' . tep_image(DIR_WS_IMAGES . 'icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image(DIR_WS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
                                                        }
?>				</td>
                                                            <td class="dataTableContent" align="right"><?php if ((is_object($rInfo)) && ($reviews['reviews_id'] == $rInfo->reviews_id)) {
                                                            echo '<a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=preview') . '">' . tep_image(DIR_WS_IMAGES . 'icon_arrow_right.gif') . '</a>';
                                                        } else {
                                                            echo '<a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $reviews['reviews_id']) . '">' . tep_image(DIR_WS_IMAGES . 'icon_info.gif', IMAGE_ICON_INFO) . '</a>';
                                                        } ?>&nbsp;</td>
                                                </tr>
                                                            <?php
//主题回复列表 start
                                                            $need_include = false;
                                                            $child_tr_display = 'none';
                                                            ?>
                                                <tr id="child_tr_<?= $reviews['reviews_id'] ?>" style="display:<?= $child_tr_display; ?>;">
                                                                <td colspan="10">            
                                                                    <div id="child_div_<?= $reviews['reviews_id'] ?>" style="padding-left:30px; font-size:12px; ">
                                                <?php
                                                            if ($need_include == true) {
                                                                include('reviews_ajax.php');
                                                            }
                                                ?>
                                                                    </div>            
                                                                </td>            
                                                            </tr>            
<?php
//主题回复列表 end
?>
<?php
                                                        }
?>
                                                                <tr>                
                                                                    <td colspan="10"><table border="0" width="100%" cellspacing="0" cellpadding="2">                
                                                                            <tr>                
                                                                                <td class="smallText" valign="top"><?php echo $reviews_split->display_count($reviews_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></td>
                                                                                <td class="smallText" align="right"><?php echo $reviews_split->display_links($reviews_query_numrows, MAX_DISPLAY_SEARCH_RESULTS_ADMIN, MAX_DISPLAY_PAGE_LINKS, $_GET['page'],tep_get_all_get_params(array('page','y','x', 'action'))); ?></td>
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

                                                                $contents = array('form' => tep_draw_form('reviews', FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=deleteconfirm'));
                                                                $contents[] = array('text' => TEXT_INFO_DELETE_REVIEW_INTRO);
                                                                $contents[] = array('text' => '<br><b>' . $rInfo->products_name . '</b>');
                                                                $contents[] = array('align' => 'center', 'text' => '<br>' . tep_image_submit('button_delete.gif', IMAGE_DELETE) . ' <a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>');
                                                                break;
                                                            default:
                                                                if (isset($rInfo) && is_object($rInfo)) {
                                                                    $heading[] = array('text' => '<b>' . $rInfo->products_name . '</b>');

                                                                    $contents[] = array('align' => 'center', 'text' => '<a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=edit') . '">' . tep_image_button('button_edit.gif', IMAGE_EDIT) . '</a> <a href="' . tep_href_link(FILENAME_REVIEWS, 'page=' . $_GET['page'] . '&rID=' . $rInfo->reviews_id . '&action=delete') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a>');
                                                                    $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($rInfo->date_added));

                                                                    if (tep_not_null($rInfo->last_modified))
                                                                        $contents[] = array('text' => TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($rInfo->last_modified));
                                                                    $contents[] = array('text' => '<br>' . tep_info_image($rInfo->products_image, $rInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT));
                                                                    $contents[] = array('text' => '<br>' . TEXT_INFO_REVIEW_AUTHOR . ' ' . $rInfo->customers_name);
                                                                    if (tep_not_null($rInfo->customers_email)) {
                                                                        $contents[] = array('text' => TEXT_INFO_REVIEW_AUTHOR_EMAIL . ' ' . $rInfo->customers_email);
                                                                    }
//$contents[] = array('text' => TEXT_INFO_REVIEW_RATING . ' ' . tep_image(HTTP_CATALOG_SERVER . DIR_WS_CATALOG_IMAGES . 'stars_' . $rInfo->reviews_rating . '.gif'));
                                                                    $contents[] = array('text' => TEXT_INFO_REVIEW_READ . ' ' . $rInfo->reviews_read);
                                                                    $contents[] = array('text' => '<br>' . TEXT_INFO_REVIEW_SIZE . ' ' . $rInfo->reviews_text_size . ' bytes');
                                                                    $contents[] = array('text' => '<br>' . TEXT_INFO_PRODUCTS_AVERAGE_RATING . ' ' . number_format($rInfo->average_rating, 2) . '%');
                                                                }
                                                                break;
                                                        }

                                                        if ((tep_not_null($heading)) && (tep_not_null($contents))) {
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