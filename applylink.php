<?php
/**
 * 申请友情链接页面
 */
require('includes/application_top.php');
require_once(DIR_FS_INCLUDES . 'ajax_encoding_control.php');
if (isset($_GET['action']) && $_GET['action'] == 'applylink'){
    
     $link_url = trim(tep_db_prepare_input($_POST['link_url']));    
     $link_desc = trim(html_to_db(ajax_to_general_string(tep_db_prepare_input($_POST['link_desc']))));
     $link_contact_name = trim(html_to_db(ajax_to_general_string(tep_db_prepare_input($_POST['link_contact_name']))));
     $link_contact_email = trim(tep_db_prepare_input($_POST['link_contact_email']));    
     $link_name = trim(html_to_db(ajax_to_general_string(tep_db_prepare_input($_POST['link_name']))));    
     $link_reciprocal_url = trim(tep_db_prepare_input($_POST['link_reciprocal_url']));
    
    $data_array = array();
    
    $data_array['links_reciprocal_url'] = $link_reciprocal_url;
    $data_array['links_contact_name'] = $link_contact_name;
    $data_array['links_contact_email'] = $link_contact_email;
    $data_array['links_url'] = $link_url;
    $data_array['links_status'] = '1';    
    $data_array['links_date_added'] = date('Y-m-d H:i:s', time());    
    tep_db_perform(TABLE_LINKS, $data_array, 'insert');
    $last_insert_id = tep_db_insert_id();
    if ($last_insert_id){
        $data_array = array();
        $data_array['links_id'] = $last_insert_id;
        $data_array['links_title'] = $link_name;
        $data_array['links_description'] = $link_desc;        
        tep_db_perform(TABLE_LINKS_DESCRIPTION, $data_array, 'insert');
        
        $data_array = array();
        $data_array['links_id'] = $last_insert_id;
        $data_array['link_categories_id'] = '0';
        
        tep_db_perform(TABLE_LINKS_TO_LINK_CATEGORIES, $data_array, 'insert');
        
        $email_text = "您好，".$link_contact_name."：<br/>感谢您申请走四方网友情链接合作！您提交的网站信息如下：<br/> 网站名：".$link_name ." <br/>网址：".$link_url." <br/>互惠页：".$link_reciprocal_url." <br/>我们将在1-3个工作日审核您提交的网站信息并邮件通知您审核结果。<br/>走四方网敬上";        
        /*tep_mail(db_to_html($link_contact_name), $link_contact_email, '', db_to_html($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
        */
        $_SESSION['need_send_email'] = array();
        $_SESSION['need_send_email'][0]['to_name'] = db_to_html($link_contact_name);
        $_SESSION['need_send_email'][0]['to_email_address'] = $link_contact_email;
        $_SESSION['need_send_email'][0]['email_subject'] = '';
        $_SESSION['need_send_email'][0]['email_text'] = db_to_html($email_text);
        $_SESSION['need_send_email'][0]['from_email_name'] = STORE_OWNER;
        $_SESSION['need_send_email'][0]['from_email_address'] = STORE_OWNER_EMAIL_ADDRESS;
        $_SESSION['need_send_email'][0]['action_type'] = 'true';
        
    }    
    $js = "[JS]showPopup('popupTip','popupConTip');";
    $js .= "jQuery('#passButton').click(function (){location.reload();});[/JS]";    
    echo $js;
    exit;
}
$content = 'applylink';
unset($breadcrumb);
require(DIR_FS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_FS_INCLUDES . 'application_bottom.php');

?>
