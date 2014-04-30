<?php
require('includes/application_top.php');

require(DIR_FS_LANGUAGES . $language . '/vote_system.php');

/*
if (!tep_session_is_registered('customer_id')) {
    
	$messageStack->add_session('login', NEXT_NEED_SIGN); 
	$navigation->set_snapshot();
	if(tep_not_null($_COOKIE['LoginDate'])){
		$messageStack->add_session('login', LOGIN_OVERTIME);
		setcookie('LoginDate', '');
	}
    tep_redirect(tep_href_link(FILENAME_LOGIN, '', 'SSL'));
	
}
*/
if (isset($_GET['email']) && ($_GET['email'] != '')){
    $unsubscribe_email = trim($_GET['email']);
    $unsubscribeHtml = display_vote_for_unsubscribe(11 , $table_width ='100%' , $form_name='vote_form', $form_method='post', $form_action='email_unsubscribe.php',$form_target='', $submit_name='submit', $charset=CHARSET, $orders_id='', $submit_image =' ',$unsubscribe_email);
}else{
    $unsubscribeHtml =  returnHtmlCode('您的邮件地址错误。');
}


function display_vote_for_unsubscribe($v_s_id , $table_width ='100%' , $form_name='', $form_method='post', $form_action='',$form_target='', $submit_name='', $charset=CHARSET, $orders_id='', $submit_image ='', $email = ''){
	global $error_string;
	
	if(!(int)$v_s_id){ return false;}
	$string = '';
	$string_foot = '';
	if(tep_not_null($form_name)){
		$string .='<form id="'.$form_name.'" name="'.$form_name.'" method="'.$form_method.'" action="'.$form_action.'" style="margin:0px;" target="'.$form_target.'"> <div class="newsletterfromCon">
    
    <div class="newsletterfromConTab">';
		if(tep_not_null($charset)){
			$string .=tep_draw_hidden_field('vote_code',$charset);
		}
        $string .=tep_draw_hidden_field('email',$email);
        $string .=tep_draw_hidden_field('contentId',$_GET['contentId']);
		$string_foot = ' </div>
  </div></form>';
	}
	
	if((int)$orders_id){
		$string .=tep_draw_hidden_field('orders_id',$orders_id);
	}
	$ToDay = date('Y-m-d');
	$Whiere_Ex = ' AND ( v_s_end_date >="'.$ToDay.'" || v_s_end_date="" || v_s_end_date="0000-00-00" )';
	$vote_sql = tep_db_query('SELECT * FROM `vote_system` WHERE v_s_id="'.(int)$v_s_id.'" AND v_s_state ="1" AND  v_s_start_date <="'.$ToDay.'" '.$Whiere_Ex.' limit 1');
	if($_GET['action']=='preview'){
		$vote_sql = tep_db_query('SELECT * FROM `vote_system` WHERE v_s_id="'.(int)$v_s_id.'" limit 1');
	}
	//取得vote_system
	while($vote_rows = tep_db_fetch_array($vote_sql)){
		$string.= '<table  width="483" border="0" cellpadding="0" cellspacing="0"">';
		
		if(tep_not_null($error_string)) {
			$string.= '<tr><td  class="mailfrom">'.$error_string.'</td></tr>';
		}
		
		$string.= '<tr><td  class="mailfrom"><b><h3>'.iconv('gb2312',$charset.'//IGNORE',$vote_rows['v_s_description']).'</h3></b></td></tr><tr><td  class="mailfrom">';
		
		
		//取得vote_system_item
		$item_sql = tep_db_query('SELECT * FROM `vote_system_item` WHERE v_s_id="'.(int)$vote_rows['v_s_id'].'" Order By v_s_i_sort ASC, v_s_i_id ASC ');
		$item_num = 0;
		while($item_rows = tep_db_fetch_array($item_sql)){
			$item_num ++;
			$background_color = '#FFFFFF';
			if($item_num % 2==0){ $background_color='#FFFBEB';}
			$string.= '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:100%;"><tr><td" >&nbsp;<b>'.$item_num.'. '.iconv('gb2312',$charset.'//IGNORE',$item_rows['v_s_i_title']).'</b></td></tr></table>';
			//取得答案选项
			$string.= '<table border="0" cellspacing="0" cellpadding="0" style="width:100%; background-color:'.$background_color.'"><tr>';
			if((int)$item_rows['v_s_i_type']!='2'){
				//单选复选
				$options_sql = tep_db_query('SELECT * FROM `vote_system_item_options` WHERE v_s_i_id="'.$item_rows['v_s_i_id'].'" Order By v_s_i_o_id ');
				$check_box_i = 0;
				while($options_rows = tep_db_fetch_array($options_sql)){
					
					if((int)$item_rows['v_s_i_type'] =='0'){
						$checked = false;
						$tmp_var = $_POST['v_s_i_o_id'][$vote_rows['v_s_id']][(int)$item_rows['v_s_i_id']];
						if($tmp_var == (int)$options_rows['v_s_i_o_id']){
							$checked = true;
						}
						$input_box = tep_draw_radio_field('v_s_i_o_id['.$vote_rows['v_s_id'].']['.(int)$item_rows['v_s_i_id'].']', (int)$options_rows['v_s_i_o_id'] , $checked );
					}elseif((int)$item_rows['v_s_i_type'] =='1'){
						$checked = false;
						$tmp_var = $_POST['v_s_i_o_id'][$vote_rows['v_s_id']][(int)$item_rows['v_s_i_id']][$check_box_i];
						if($tmp_var == (int)$options_rows['v_s_i_o_id']){
							$checked = true;
						}
						$input_box = tep_draw_checkbox_field('v_s_i_o_id['.$vote_rows['v_s_id'].']['.(int)$item_rows['v_s_i_id'].']['.$check_box_i.']', (int)$options_rows['v_s_i_o_id'] , $checked );
						$check_box_i++;
					}
					 
					$string.= '<td width="1%" nowrap="nowrap" style="font-size:12px">&nbsp;'.$input_box.'&nbsp;</td><td style="font-size:12px" >'.iconv('gb2312',$charset.'//IGNORE',$options_rows['v_s_i_o_title']).'</td>';
				}
			}else{
				//文本
				$tmp_var = $_POST['text_vote'][$vote_rows['v_s_id']][(int)$item_rows['v_s_i_id']];
				if(tep_not_null($_POST['vote_code'])){
					$tmp_var = iconv($_POST['vote_code'],CHARSET.'//IGNORE',$tmp_var);
				}
				//$string.= '<td style="font-size:12px">&nbsp;'.tep_draw_input_field('text_vote['.$vote_rows['v_s_id'].']['.(int)$item_rows['v_s_i_id'].']', $tmp_var ,' style="width:400px; font-size:12px" ').'</td>';
				//$string.= '<td style="font-size:12px">&nbsp;'.tep_draw_textarea_field('text_vote['.$vote_rows['v_s_id'].']['.(int)$item_rows['v_s_i_id'].']', 'virtual', '50', '5', $tmp_var).'</td>';
                $string .= '<td style="font-size:12px">&nbsp;<textarea class="mailfromanother" wrap="virtual" rows="5" cols="50" name="'.'text_vote['.$vote_rows['v_s_id'].']['.(int)$item_rows['v_s_i_id'].']'.'"';
                if (CHARSET == 'gb2312'){
                    $string .= 'onblur="this.value = simplized(this.value);"';
                }else{
                    $string .= 'onblur="this.value = traditionalized(this.value);"';
                }
                $string .= ' onclick="this.value=\'\'"';
                $string .= '>'.db_to_html('请提供您宝贵的建议和意见以便改进我们的服务，谢谢！').'</textarea></td>';
                
			}
			$string.= '</tr><tr><td>&nbsp;</td></tr></table>';
		}
		
		$string.= '</td></tr>';
		
		if(tep_not_null($submit_name)){
			if(!tep_not_null($submit_image)){
                $href= "window.location.href='index.php'";
				$string .= '<tr><td bgcolor="#FFF5CC"  align="center">
                
                <a class="btn btnOrange">
                <button type="submit" onclick="return checkOption();">'.iconv('gb2312',$charset.'//IGNORE','确认退订').'</button>
            </a>
               &nbsp;&nbsp;&nbsp;&nbsp;
               <a class="btn btnOrange"  onclick="javascript:location.href=\'index.php\'">
            <button type="button" onclick="'.$href.'">'.iconv('gb2312',$charset.'//IGNORE','取消退订').'</button>
            </a>               
               '.tep_draw_hidden_field('ation_vote','true').'</td></tr>';
			}else{
				$string .= '<tr><td  align="center"><table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td ><a class="btn btnOrange">
                <button  type="submit"  onclick="return checkOption();" >'.iconv('gb2312',$charset.'//IGNORE','确认退订').'</button>
            </a></td>
    <td>&nbsp;&nbsp;&nbsp;&nbsp;'.tep_draw_hidden_field('ation_vote','true').'</td>
    <td><a class="btn btnOrange"  onclick="javascript:location.href=\'index.php\'">
            <button type="button" onclick="'.$href.'">'.iconv('gb2312',$charset.'//IGNORE','取消退订').'</button>
            </a></td>
  </tr>
</table></td></tr>';
			}
		}       
		$string.= '</table>';
	}
	
	return $string.$string_foot;
}

if($_POST['ation_vote']){
    
    $submit_vote = submit_vote($error_string, $customer_id);        
    $email = base64_decode($_POST['email']);       
    $contentId = intval($_POST['contentId']);
    $customers_sql = tep_db_query('SELECT customers_id, customers_firstname FROM `customers` WHERE `customers_email_address` = "'.trim($email).'"  limit 1;');
	$customers_row2 = tep_db_fetch_array($customers_sql);
    
    if (count($customers_row2) > 1 && $contentId == 0){
        
        //print_r($_SESSION);
        tep_db_query('UPDATE `customers` SET `customers_newsletter` = "0" WHERE `customers_id` = "'.$customers_row2['customers_id'].'" LIMIT 1 ;'); 
        $unsubscribeHtml = returnHtmlCode('您已经成功取消了走四方信息邮件的订阅。');
    }else{
        
        $customers_sql2 = tep_db_query('SELECT newsletters_email_id, newsletters_email_address FROM `newsletters_email` WHERE `newsletters_email_address` = "'.trim($email).'" AND `content_id`='.$contentId.' limit 1;');        
        $customers_row = tep_db_fetch_array($customers_sql2);        
        if (count($customers_row) > 1){                    
            tep_db_query('UPDATE `newsletters_email` SET `agree_newsletter` = "0" WHERE `newsletters_email_id` = "'.$customers_row['newsletters_email_id'].'"  LIMIT 1 ;'); 
            $unsubscribeHtml = returnHtmlCode('您已经成功取消了走四方信息邮件的订阅。');
        }else{
            $unsubscribeHtml = returnHtmlCode('您要退订的邮件地址不存在。');
        }
    }
    
 
    
	if($_GET['v_s_id']=='1' && !(int)$_POST['orders_id']){
		$js_code = '<script type="text/javascript">alert("'.db_to_html("此项调查您必须有适合条件的订单才能提交，谢谢你的支持！").'"); document.location="'.tep_href_link('vote_system_list.php', '').'"; </script>';
		echo $js_code;
		exit;
	}
    
}

function returnHtmlCode($msg){
    return $unsubscribeHtml = '<div class="newsletterfromCon"><div class="newsletterfromConTab">
            <h3>'.iconv('gb2312',$charset,'订阅走四方信息邮件').'</h3>
            <table width="99%" cellspacing="0" cellpadding="0" border="0">
  <tbody><tr>
    <td width="100%" class="pageHeading"></td><td align="right" nowrap="nowrap" class="pageHeading">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="mainbodybackground">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tbody><tr><td height="15"></td></tr>
			     <tr>
					 <td width="10%"></td>
					 <td width="80%" class="main"><table width="100%" cellspacing="0" cellpadding="0" border="0" align="center">
	<tbody><tr>
		<td>'.db_to_html($msg) .'
		</td>
	</tr>
</tbody></table>
</td><td width="10%"></td></tr><tr><td height="30"></td></tr></tbody></table></td></tr></tbody></table></div></div>';
}
//tep_session_unregister('unsubscribe_email');
$smarty->assign('votes_items', $vote_item);
$smarty->assign('unsubscribeHtml', $unsubscribeHtml);
$smarty->display('email_unsubscribe.tpl.html');


