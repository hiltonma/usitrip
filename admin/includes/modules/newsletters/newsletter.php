<?php
/*
  $Id: newsletter.php,v 1.1.1.1 2004/03/04 23:40:24 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class newsletter {
    var $nID, $show_choose_audience, $title, $content, $send_range;
    /**
     * 邮件发件人名称
     * @var string
     */
    public $from_email_name = '走四方旅游网usitrip.com';
    var $contentId = 0;

    function newsletter($title, $content ,$contentId = 0, $send_range = 1 ) {
		$this->show_choose_audience = false;
		$this->title = trim($title).' ';
		$this->contentId = intval($contentId);
        //$this->email_str = '';
		/* 添加跟踪参数 */
		$ex_parameter = 'utm_source=newsletter&utm_medium=email&utm_term='.rawurlencode($title).' {EMAIL}&utm_campaign='.date('Ymd');
		$part = array();
		$trans = array();
		if(preg_match_all('/(href="[^ \<"]+")/i', $content, $m, PREG_SET_ORDER)){
			for($i=0, $n = sizeof($m); $i<$n; $i++){
				$part[] = $m[$i][1];
			}
			rsort($part);
			foreach($part as $v){
				$_symbol = '&';
				$v = trim($v);
				if(strpos($v,'?')===false){
					$_symbol = '?';
				}
				$trans[$v] = substr($v,0,-1).$_symbol.$ex_parameter.'"';
			}
			$content = strtr($content, $trans);
		}
        /*  增加退订调查 panda */
		$content .= '<div style="padding-left:15px;">如果您不需要走四方网的相关旅游资讯，请<a href="{HTTP_SERVER}/confirmation_newslleter_email.php?confirmation=false">点击这里</a>进行退订。</div>';
        //$content .= '<div style="padding-left:15px;">如果您不需要走四方网的相关旅游资讯，请<a href="{HTTP_SERVER}/email_unsubscribe.php?email=@EMAIL@&contentId='.$this->contentId.'">点击这里</a>进行退订。</div>';
		$this->content = preg_replace('/[[:space:]]+/',' ',$content);
		$this->send_range = $send_range;
    }
	//取得可发newsletter的Gmail邮箱
	function get_mail_address(){
		$smtp_mail_sql = tep_db_query('SELECT mail_id, mail_address FROM `smtp_mail` WHERE mail_address Like "newsletter%@usitrip.com" and mail_max_send_num > mail_max_sent_num and action_status ="true" limit 1 ');
		$smtp_mail = tep_db_fetch_array($smtp_mail_sql);
		if(tep_not_null($smtp_mail['mail_address'])){
			/*
			以下代码已经在phpmailer/send_mail_funtoin.php有了，所以不用添加
			tep_db_query('UPDATE `smtp_mail` SET `mail_max_sent_num` =  `mail_max_sent_num`+1 WHERE `mail_id` = "'.$smtp_mail['mail_id'].'" ');
			*/
			return trim($smtp_mail['mail_address']);
		}
		return false;
	}
	//邮箱已发送次数清零
	function clear_zero_sent_mail(){
		tep_db_query('UPDATE `smtp_mail` SET `mail_max_sent_num` = 0 WHERE mail_address Like "newsletter%@usitrip.com" ');
	}

    function choose_audience() {
      return false;
    }

    function confirm() {
      global $HTTP_GET_VARS;
      $mail_count = 0;
	  if($this->contentId == 0){
	      $_e_where = ' AND customers_newsletter = "1" ';
		  if($this->send_range=="2") $_e_where = ' ';
		  $mail_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS . " where 1 ".$_e_where." AND customers_newsletter_sent = '0' ");
	      $mail = tep_db_fetch_array($mail_query);
		  $mail_count += $mail['count'];
	  }
	  //zhh added 
	  $mail_sql = tep_db_query("select count(*) as count from newsletters_email  where newsletter_sent = '0' AND agree_newsletter = 1 AND content_id = ".$this->contentId);
	  $mail_news = tep_db_fetch_array($mail_sql);
	  $mail_count += $mail_news['count'];

      $confirm_string = '<table border="0" cellspacing="0" cellpadding="2">' . "\n" .
                        '  <tr>' . "\n" .
                        //'    <td class="main"><font color="#ff0000"><b>' . sprintf(TEXT_COUNT_CUSTOMERS, $mail['count']) . '</b></font></td>' . "\n" .
                        '    <td class="main"><font color="#ff0000"><b>' . sprintf(TEXT_COUNT_CUSTOMERS, $mail_count) . '</b></font></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '    <td><b>Title</b>:<font color="#ff0000">' . $this->title . '</font></td>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><b>' . $this->title . '</b></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><tt>' . nl2br($this->content) . '</tt></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td align="right"><a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID'] . '&action=confirm_send') . '">' . tep_image_button('button_send.gif', IMAGE_SEND) . '</a> <a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $HTTP_GET_VARS['page'] . '&nID=' . $HTTP_GET_VARS['nID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '</table>';

      return $confirm_string;
    }

    function send($newsletter_id) {
    	require(DIR_WS_FUNCTIONS . 'banner.php');
    	$mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));
    	if($this->contentId == 0) {
    		//当content_id 为0 会需要发送默认的newsletter
	    	$_e_where = ' AND customers_newsletter = "1" ';
			if($this->send_range=="2") $_e_where = ' ';
			
			$mail_query = tep_db_query("select customers_id, customers_firstname, customers_lastname, customers_email_address, customers_char_set from " . TABLE_CUSTOMERS . " where 1 ".$_e_where." AND customers_newsletter_sent = '0' ");
			
			$to_email_address = '';
			$to_email_addresscn = '';
			$to_email_addresstw= '';
			
			if(isset($_GET['Whetherismass']) && $_GET['Whetherismass']=="1"){
				$Whetherismass = $_GET['Whetherismass'];//开关
			}else{
				$Whetherismass = 0;
			}
			
			while ($mail = tep_db_fetch_array($mail_query)) {			
				$mail['customers_char_set'] = strtolower(trim($mail['customers_char_set']));
				if($mail['customers_char_set']!='big5' && $mail['customers_char_set']!='gb2312'){
					$mail['customers_char_set'] = 'gb2312';
				}
				//howard added new eamil tpl
				$patterns = array();
				$patterns[0] = '{CustomerName}';
				$patterns[1] = '{images}';
				$patterns[2] = '{HTTP_SERVER}';
				$patterns[3] = '{EMAIL}';
				$patterns[4] = '{_IMAGES_}';
				$replacements = array();
				//$replacements[0] = $mail['customers_firstname'] . ' ' . $mail['customers_lastname'];	
	      	
				//是否群发 jason
				if($Whetherismass == 1){
					$replacements[0] = iconv(CHARSET,$mail['customers_char_set'].'//IGNORE','亲爱的走四方网客户');
						$_SESSION['Whetherismass'] = 1;//send_mail_funtoin.php用
					    if($mail['customers_char_set']!='gb2312'){
							if($to_email_addresstw == ''){
							    $to_email_addresstw.= $mail['customers_email_address'];
							}else{
								$to_email_addresstw.= ','.$mail['customers_email_address'];	
							}
						}else{
							if($to_email_addresscn == ''){
							    $to_email_addresscn.= $mail['customers_email_address'];
							}else{
								$to_email_addresscn.= ','.$mail['customers_email_address'];	
							}				
						}
				}else{
					$_SESSION['Whetherismass'] = 0;
					$to_email_address = $mail['customers_email_address'];
					$to_name = iconv(CHARSET,$mail['customers_char_set'].'//IGNORE',$mail['customers_firstname'] . ' ' . $mail['customers_lastname']).' ';
				    $replacements[0] = iconv(CHARSET,$mail['customers_char_set'].'//IGNORE',$mail['customers_firstname']);
		            $HTTP_SERVER = HTTP_SERVER;
		            if(ENABLE_SSL=='true'){ $HTTP_SERVER = HTTPS_SERVER; }
		            $replacements[1] = $HTTP_SERVER.'/email_tpl/images';
		            if($mail['customers_char_set']!='gb2312'){
		                $replacements[1] = $HTTP_SERVER.'/email_tpl/images_ft';
		            }
		            $replacements[2] = HTTP_SERVER;
		            $replacements[3] = $mail['customers_email_address'];
		            $replacements[4] = "images";
		            if($mail['customers_char_set']!='gb2312'){
		                $replacements[4] = "images_ft";
		            }
		            //howard added new eamil tpl end
				}
		        //$to_name = iconv(CHARSET,$mail['customers_char_set'].'//IGNORE',$mail['customers_firstname'] . ' ' . $mail['customers_lastname']).' ';
				//$to_email_address = $mail['customers_email_address'];
				//$from_email_name = iconv(CHARSET,$mail['customers_char_set'].'//IGNORE',EMAIL_FROM);
				$from_email_name = iconv('gb2312',$mail['customers_char_set'].'//IGNORE',$this->from_email_name);
				$email_subject = iconv(CHARSET,$mail['customers_char_set'].'//IGNORE',$this->title);
				$email_charset = tep_not_null($mail['customers_char_set']) ? $mail['customers_char_set'] : CHARSET;
		        /* 退订优化 start panda*/
		        $to_email = base64_encode($mail['customers_email_address']);
		        $content = str_replace('@EMAIL@', $to_email ,$this->content);        
				$email_text = str_replace($patterns,$replacements,iconv(CHARSET,$mail['customers_char_set'].'//IGNORE',$content));
				/* 退订优化 end panda*/
		        
		        //加入newsletter广告
				$banner_language_code_name = (strtolower($email_charset)=='gb2312') ? 'schinese':'tchinese';
				$banners = get_banners("NewsLetter Email", $banner_language_code_name);
				if(tep_not_null($banners)){
					for($i=0; $i<count($banners); $i++){
						if(tep_not_null($banners[$i]['FinalCode'])){
							$email_text .= iconv('GB2312',$email_charset.'//IGNORE',$banners[$i]['FinalCode']);
						}else{
							$email_text .= '<div><a title="'.iconv('GB2312',$email_charset.'//IGNORE',$banners[$i]['alt']).'" href="'.$banners[$i]['links'].'" target="_blank"><img border="0" src="'.$banners[$i]['src'].'" alt="'.iconv('GB2312',$email_charset.'//IGNORE',$banners[$i]['alt']).'" /></a></div>';
						}
		            }
		        }
				if($Whetherismass !=1){
					if (EMAIL_TRANSPORT != 'smtp') {
						
						// MaxiDVD Added Line For WYSIWYG HTML Area: EOF (Send TEXT Newsletter v1.7 when WYSIWYG Disabled)
						if (HTML_AREA_WYSIWYG_DISABLE_NEWSLETTER == 'Disable') {
						  $mimemessage->add_text(str_replace($patterns,$replacements,iconv(CHARSET,$mail['customers_char_set'].'//IGNORE',$this->content)));
						} else {
						  $mimemessage->add_html(str_replace($patterns,$replacements,iconv(CHARSET,$mail['customers_char_set'].'//IGNORE',$this->content.email_track_code('newsletter', $to_email_address, (int)$_GET['nID'], 'newsletters_id'))));
						  // MaxiDVD Added Line For WYSIWYG HTML Area: EOF (Send HTML Newsletter v1.7 when WYSIWYG Enabled)
						}
		            	$mimemessage->build_message();  
						$mimemessage->send($to_name, $to_email_address, '', 'UsiTrip <newsletter@usitrip.com>', $email_subject, '',$email_charset);
						
					}else{
						//$from_email_name = 'usitrip';
						//$from_email_name = iconv('gb2312',$mail['customers_char_set'].'//IGNORE',$this->from_email_name);
						$from_email_address = $this->get_mail_address();
						if(!tep_not_null($from_email_address)){
							echo '本次发送的邮箱次数已经达到极限！请明天再发。';
							exit;
						}
				
						 $track_code = email_track_code('newsletter', $to_email_address, (int)$_GET['nID'], 'newsletters_id');
						 tep_mail($to_name, $to_email_address, $email_subject, $email_text.$track_code, $from_email_name, $from_email_address, EMAIL_USE_HTML , $email_charset);
						
					}
				}
				tep_db_query("UPDATE `customers` SET `customers_newsletter_sent` = '1' WHERE `customers_id` = '".(int)$mail['customers_id']."' LIMIT 1 ; ");
			}
    	}
      //zhh added snd mail from newsletters_email
	  //$mail_sql = tep_db_query("select * from newsletters_email WHERE newsletter_sent = '0'");
      // 增加未注册用户邮件退订功能
      
      $mail_sql = tep_db_query("select * from newsletters_email WHERE newsletter_sent = '0'  AND agree_newsletter=1 AND content_id = ".$this->contentId);
      while ($mail_news = tep_db_fetch_array($mail_sql)) {
		//check_customers
		$check_cus_sql = tep_db_query('select customers_id, customers_char_set  from ' . TABLE_CUSTOMERS . ' where customers_email_address="'.$mail_news['newsletters_email_address'].'" limit 1');
		$check_cus_row = tep_db_fetch_array($check_cus_sql);
		
		//只发不存在的客户邮件
		$needSend = $this->contentId == 0 ?!(int)$check_cus_row['customers_id']:true;
		if($needSend){
			$patterns = array();
			$patterns[0] = '{CustomerName}';
			$patterns[1] = '{images}';
			$patterns[2] = '{HTTP_SERVER}';
			$patterns[3] = '{EMAIL}';
			$patterns[4] = '{_IMAGES_}';
			$replacements = array();
			$replacements[0] = 'friend';
			$HTTP_SERVER = HTTP_SERVER;
			if(ENABLE_SSL=='true'){ $HTTP_SERVER = HTTPS_SERVER; }
			$replacements[1] = $HTTP_SERVER.'/email_tpl/images';
			$replacements[2] = HTTP_SERVER;
			$replacements[3] = $mail_news['newsletters_email_address'];
			$replacements[4] = 'images';
			
			$to_name = "friend";
			$to_email_address = $mail_news['newsletters_email_address'];
			
			$email_subject = $this->title;
            /* 邮件退订优化 */
            $to_email = base64_encode($mail_news['newsletters_email_address']);
            $email_text = str_replace($patterns,$replacements,$this->content);
            $email_text = str_replace('@EMAIL@', $to_email ,$email_text);    
            /* 邮件退订优化 */
			$email_charset = CHARSET;
			//$from_email_name = EMAIL_FROM;
			$from_email_name = iconv('GB2312',$email_charset.'//IGNORE',$this->from_email_name);
			//加入newsletter广告
			$banner_language_code_name = (strtolower($email_charset)=='gb2312') ? 'schinese':'tchinese';
			$banners = get_banners("NewsLetter Email", $banner_language_code_name);
			if(tep_not_null($banners)){
				for($i=0; $i<count($banners); $i++){
					if(tep_not_null($banners[$i]['FinalCode'])){
						$email_text .= iconv('GB2312',$email_charset.'//IGNORE',$banners[$i]['FinalCode']);
					}else{
						$email_text .= '<div><a title="'.iconv('GB2312',$email_charset.'//IGNORE',$banners[$i]['alt']).'" href="'.$banners[$i]['links'].'" target="_blank"><img border="0" src="'.$banners[$i]['src'].'" alt="'.iconv('GB2312',$email_charset.'//IGNORE',$banners[$i]['alt']).'" /></a></div>';
					}
				}
			}
			
			if (EMAIL_TRANSPORT != 'smtp') {
				// MaxiDVD Added Line For WYSIWYG HTML Area: EOF (Send TEXT Newsletter v1.7 when WYSIWYG Disabled)
				if (HTML_AREA_WYSIWYG_DISABLE_NEWSLETTER == 'Disable') {
					$mimemessage->add_text(str_replace($patterns,$replacements,$this->content));
				} else {
					$mimemessage->add_html(str_replace($patterns,$replacements,$this->content.email_track_code('newsletter', $to_email_address, (int)$_GET['nID'], 'newsletters_id')));
				// MaxiDVD Added Line For WYSIWYG HTML Area: EOF (Send HTML Newsletter v1.7 when WYSIWYG Enabled)
				}
				$mimemessage->build_message();
				$mimemessage->send($to_name, $to_email_address , '', 'UsiTrip <newsletter@usitrip.com>', $email_subject);
			}else{
				//$from_email_name = 'usitrip';
				$from_email_address = $this->get_mail_address();
				if(!tep_not_null($from_email_address)){
					echo '本次发送的邮箱次数已经达到极限！请明天再发。';
					exit;
				}
				$track_code = email_track_code('newsletter', $to_email_address, (int)$_GET['nID'], 'newsletters_id');
				tep_mail($to_name, $to_email_address, $email_subject, $email_text.$track_code, $from_email_name, $from_email_address, EMAIL_USE_HTML , $email_charset);
			}
			tep_db_query("UPDATE `newsletters_email` SET `newsletter_sent` = '1' WHERE `newsletter_sent` = '".(int)$mail_news['newsletters_email_id']."' AND  content_id = ".$this->contentId." LIMIT 1 ; ");
		}
      }

      $newsletter_id = tep_db_prepare_input($newsletter_id);
      tep_db_query("update " . TABLE_NEWSLETTERS . " set date_sent = now(), status = '1' where newsletters_id = '" . tep_db_input($newsletter_id) . "'");
	  
	  tep_db_query("UPDATE `customers` SET `customers_newsletter_sent` = '0' ");
	  tep_db_query("UPDATE `newsletters_email` SET `newsletter_sent` = '0' ");
	  $this->clear_zero_sent_mail();
    }
  }
?>
