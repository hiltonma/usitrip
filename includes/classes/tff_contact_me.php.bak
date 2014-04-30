<?php
/*让走四方联系我*/
class tff_contact_me{
	var $products_id;
	function tff_contact_me(){
		global $products_id;
		$this->products_id = $products_id;
	}
	function right_html(){
		return '<div class="contentMe"><h3 onclick="showPopup(\'popupContactMe\',\'popupConContactMe\',\'off\',\'\',\'\',\'200\');">让走四方网主动联系我</h3><p>如您对走四方网行程或其他产品<br />有任何疑问，请留下您的联系<br />方式。我们会在您方便的时间<br />及时与您联系。</p></div>';
	}
	/**
	 * 显示到右侧边栏的ContactMe图片按钮 用户点选后弹出页内窗口，滚动条滚至顶部
	 * 2011.3.21
	 * @author vincent
	 */
	function right_html_type2011($language){
			$tr = $language == 'tchinese'?'_tr':'';
			return '<div class="widget lazyLoad"><a id="ContactMe" href="javascript:showPopup(\'popupContactMe\',\'popupConContactMe\',\'off\',\'\',\'\',\'fixedTop\',\'ContactMe\');"><img src="image/blank.gif" src2="image/contact_me'.$tr.'.jpg" style="cousor:pointer" alt="'.db_to_html('有事儿您说话!0086-4006333926或留个您的电话、邮箱让我们联系您?').'"  title="'.db_to_html('有事儿您说话!0086-4006333926或留个您的电话、邮箱让我们联系您?').'" border="0"></a></div>';
	}
	function form_html(){
		return 
		'<div class="popup" id="popupContactMe">
			<form id="popupContactMeForm" name="popupContactMeForm" action="" method="post" enctype="multipart/form-data" onsubmit="return false;">
			<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
			<tr><td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td><td class="con">
			
			  <div class="popupCon" id="popupConContactMe" style="width:500px;">
				<div class="popupConTop" id="dragContactMe">
				  <h3><b>让走四方网主动联系我</b><label>(带*号必须填写)</label></h3><span><a href="javascript:closePopup(\'popupContactMe\')"><img src="'.DIR_WS_ICONS.'icon_x.gif"></a></span>
				</div>
				
				<div class="contactMeCon">
					<div class="tip">请根据提示填入联系方式，约定联系时间，我们将为您进行专业细致的咨询服务。</div>
					<h4>请填写联系方式:</h4>
					<ul>
						<li>
							<label>您的姓名:</label>
							'.tep_draw_input_field('name','',' title="请输入您的姓名" class="required text country" ','text',false).'
							<span>*</span>
						</li>
						<li>
							<label>国家/地区:</label>
							'.tep_draw_input_field('state','',' title="请输入您所在的国家/地区" class="required text country" ').'
							<span>*</span>
						</li>
						<li>
							<label>城市:</label>
							'.tep_draw_input_field('city','',' title="请输入您所在的城市" class="required text city" ').'
							<span>*</span>
						</li>
						<li><label>手机/座机:</label>
						'.tep_draw_input_num_en_field('mobile','',' title="请输入您的手机/座机" class="required text" ').'
						<span>*</span></li>
						<li><label>&nbsp;</label>建议输入您的手机号码！
						</li>
					</ul>
					
					<h4>请选择方便与您联系的时间:</h4>
					<div class="time">
						<h5>周一到周五</h5>
						<span><input name="con_time[]" value="08:00-10:00" type="checkbox" />08:00-10:00</span>
						<span><input name="con_time[]" value="10:00-12:00" type="checkbox" />10:00-12:00</span>
						<span><input name="con_time[]" value="12:00-14:00" type="checkbox" />12:00-14:00</span>
						<span><input name="con_time[]" value="14:00-16:00" type="checkbox" />14:00-16:00</span>
						<span><input name="con_time[]" value="16:00-18:00" type="checkbox" />16:00-18:00</span>
						<span><input name="con_time[]" value="18:00-20:00" type="checkbox" />18:00-20:00</span>
						<span><input name="con_time[]" value="20:00-22:00" type="checkbox" />20:00-22:00</span>
						<span><input name="con_time[]" value="22:00-24:00" type="checkbox" />22:00-24:00</span>
					</div>
					<div class="time">
						<h5>周末</h5>
						<span><input name="weekend[]" value="上午" type="checkbox" />上午</span>
						<span><input name="weekend[]" value="下午" type="checkbox" />下午</span>
						<span><input name="weekend[]" value="晚上" type="checkbox" />晚上</span>
					</div>
					
					
					<h4>请填写您的邮箱地址，在必要时我们将提供更加详细的信息:</h4>
					<ul>
						<li><label>Email:</label><input name="email" type="text" class="text"></li>
						<li><label>留言:</label>'.tep_draw_textarea_field('message', '','40','5','', ' class="textarea" ').'</li>
					</ul>
					<p>1.我们会在您约定的时间向您提供专业咨询服务。</p>
					<p>2.您的联系信息加密存储于走四方网后台数据库，不会被第三方获取。</p>
					<p>3.未经您的允许,走四方网不会发送广告促销短信。</p>
				</div>
				<div class="popupBtn"><a href="javascript:;" class="btn btnOrange"><button type="submit">提 交</button>
					<input name="products_id" type="hidden" id="products_id" value="'.(int)$this->products_id.'" />
				</a></div>
				
				
			  </div>
			  
			</td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
			</table>
			</form>
		</div>';
	}
	function javascript(){
		$p=array('/&amp;/','/&quot;/');
		$r=array('&','"');
		
		$js = '<script type="text/javascript">';
		$js .= "new divDrag([GetIdObj('dragContactMe'),GetIdObj('popupContactMe')]);";
		$js .= ' function ContactMeFormCallback(result, form) {
			if(result==true){
				var url="'.preg_replace($p,$r,tep_href_link_noseo('product_info.php','products_id='.(int)$this->products_id.'&action=submit_contact_me_form')).'";
				ajax_post_submit(url,"popupContactMeForm","","","");				
			}
		}
		var ContactMeFormValid = new Validation(\'popupContactMeForm\', {immediate : true,useTitles:true, onFormValidate : ContactMeFormCallback});';
		$js .= '</script>';
		return $js;
		
	}
	function post(){
		global $_GET, $_POST;
		$p=array('/&amp;/','/&quot;/');
		$r=array('&','"');
		if($_GET['action']=="submit_contact_me_form"){			
			$name = trim(html_to_db(ajax_to_general_string($_POST['name'])));
			$state = trim(html_to_db(ajax_to_general_string($_POST['state'])));
			$city = trim(html_to_db(ajax_to_general_string($_POST['city'])));
			$mobile = trim($_POST['mobile']);
			
			if($name == '' || $state=='' || $city== '' || $mobile == ''){
				$js_str = '[JS]';
				$js_str .= 'alert("请填写您完整的联系方式！");';
				$js_str .= '[/JS]';
				echo db_to_html($js_str);
				exit;
			}
			
			$goto_url = preg_replace($p,$r,tep_href_link('product_info.php','products_id='.(int)$this->products_id));
			$to_email_address = "service@usitrip.com";
			$to_name = "usitrip";
			$email_subject = db_to_html("让走四方联系我 ").db_to_html(tep_db_output($name))." ";
			$email_text = '<b>客人的联系方式</b>'."\n";
			$email_text .= '<span style="color:#777777">姓名:</span>'.tep_db_output($name)."\n";
			$email_text .= '<span style="color:#777777">地区:</span>'.tep_db_output($state).' '.tep_db_output($city)."\n";
			$email_text .= '<span style="color:#777777">手机/座机:</span>'.tep_db_output($mobile)."\n";
			//$email_text .= '<span style="color:#777777">座机:</span>'.tep_db_output($_POST['phone'])."\n";
			$email_text .= '<b>客人选择的方便联系的时间</b>'."\n";
			$email_text .= '<b style="margin:0; padding:0; line-height:20px; color:#777; font-size:12px;">周一到周五</b>'."\n";
			$email_text .= @implode('&nbsp;&nbsp;',$_POST['con_time'])."\n";
			$email_text .= '<b style="margin:0; padding:0; line-height:20px; color:#777; font-size:12px;">周末</b>'."\n";
			$email_text .= html_to_db(ajax_to_general_string(@implode('&nbsp;&nbsp;',$_POST['weekend'])))."\n";
			$email_text .= '<b>客人的邮箱和留言</b>'."\n";
			$email_text .= '<span style="color:#777777">Email:</span>'.tep_db_output($_POST['email'])."\n";
			$email_text .= '<span style="color:#777777">留言:</span>'.tep_db_output(html_to_db(ajax_to_general_string($_POST['message'])))."\n";
			$email_text .= '<a href="'.$goto_url.'">'.$goto_url."</a>\n";
			$email_text = db_to_html($email_text);
			
			$from_email_name = 'automail@usitrip.com';
			$from_email_name = 'automail@usitrip.com';

			
			$s_count = count($_SESSION['need_send_email']);
			$_SESSION['need_send_email'][$s_count]['to_name'] = $to_name;
			$_SESSION['need_send_email'][$s_count]['to_email_address'] = $to_email_address;
			$_SESSION['need_send_email'][$s_count]['email_subject'] = $email_subject;
			$_SESSION['need_send_email'][$s_count]['email_text'] = $email_text;
			$_SESSION['need_send_email'][$s_count]['from_email_name'] = $from_email_name;
			$_SESSION['need_send_email'][$s_count]['from_email_address'] = $from_email_address;
			$_SESSION['need_send_email'][$s_count]['action_type'] = 'true';
			
			$js_str = '[JS]';
			$js_str .= 'alert("信息提交成功！");';
			$js_str .= 'location="'.$goto_url.'";';
			$js_str .= '[/JS]';
			echo db_to_html($js_str);
			exit;
		}
	}
}
?>


