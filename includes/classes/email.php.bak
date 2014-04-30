<?php
/*
  $Id: email.php,v 1.1.1.1 2004/03/04 23:40:43 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  mail.php - a class to assist in building mime-HTML eMails

  The original class was made by Richard Heyes <richard@phpguru.org>
  and can be found here: http://www.phpguru.org

  Renamed and Modified by Jan Wildeboer for osCommerce
*/

class email {
	var $html;
	var $text;
	var $output;
	var $html_text;
	var $html_images;
	var $image_types;
	var $build_params;
	var $attachments;
	var $headers;

	function email($headers = '') {
		if ($headers == '') $headers = array();

		$this->html_images = array();
		$this->headers = array();

		if (EMAIL_LINEFEED == 'CRLF') {
			$this->lf = "\r\n";
		} else {
			$this->lf = "\n";
		}

		/**
		 * If you want the auto load functionality
		 * to find other mime-image/file types, add the
		 * extension and content type here.
		 */

		$this->image_types = array('gif' => 'image/gif',
								   'jpg' => 'image/jpeg',
								   'jpeg' => 'image/jpeg',
								   'jpe' => 'image/jpeg',
								   'bmp' => 'image/bmp',
								   'png' => 'image/png',
								   'tif' => 'image/tiff',
								   'tiff' => 'image/tiff',
								   'swf' => 'application/x-shockwave-flash');

		//$this->build_params['html_encoding'] = 'quoted-printable';
		//$this->build_params['text_encoding'] = '7bit';
		$this->build_params['html_encoding'] = 'base64';
		$this->build_params['text_encoding'] = 'base64';
		$this->build_params['html_charset'] = 'gbk';
		$this->build_params['text_charset'] = 'gbk';
		$this->build_params['text_wrap'] = 68;//998;

		/**
	 	 * Make sure the MIME version header is first.
	 	 */

		$this->headers[] = 'MIME-Version: 1.0';
		//$this->headers[] = "Content-Type: text/html;";	//使用html发信
		//$this->headers[] = "Content-Transfer-Encoding: base64";	//邮件正文用base64_encode编码发送，注意后面的headers必须转成base64_encode

		reset($headers);
		while (list(,$value) = each($headers)) {
			if (tep_not_null($value)) {
				$this->headers[] = $value;
			}
		}
	}

	/**
 	 * 读取文件夹内容并返 回
 	 * This function will read a file in
	 * from a supplied filename and return
	 * it. This can then be given as the first
	 * argument of the the functions
	 * add_html_image() or add_attachment().
	 * @param string $filename 需要读取内容的文件[完整路径]
	 */

	function get_file($filename) {
		$return = '';

		if ($fp = fopen($filename, 'rb')) {
			while (!feof($fp)) {
				$return .= fread($fp, 1024);
			}
			fclose($fp);

			return $return;
		} else {
			return false;
		}
	}

	/**
	 * 从HTML中找出图片 并读取图片文件内容
	 * Function for extracting images from
	 * html source. This function will look
	 * through the html code supplied by add_html()
	 * and find any file that ends in one of the
	 * extensions defined in $obj->image_types.
	 * If the file exists it will read it in and
	 * embed it, (not an attachment).
	 *
	 * Function contributed by Dan Allen
	 * @param string $images_dir HTML中图片路径前部分，连接HTML中图片的地址读取图片
	 */
	function find_html_images($images_dir) {
		// Build the list of image extensions
		while (list($key, ) = each($this->image_types)) {
			$extensions[] = $key;
		}

		preg_match_all('/"([^"]+\.(' . implode('|', $extensions).'))"/Ui', $this->html, $images);

		for ($i=0; $i<count($images[1]); $i++) {
			if (file_exists($images_dir . $images[1][$i])) {
				$html_images[] = $images[1][$i];
				$this->html = str_replace($images[1][$i], basename($images[1][$i]), $this->html);
			}
		}

		if (tep_not_null($html_images)) {
			// If duplicate images are embedded, they may show up as attachments, so remove them.
			$html_images = array_unique($html_images);
			sort($html_images);

			for ($i=0; $i<count($html_images); $i++) {
		  		if ($image = $this->get_file($images_dir . $html_images[$i])) {
					$content_type = $this->image_types[substr($html_images[$i], strrpos($html_images[$i], '.') + 1)];
					$this->add_html_image($image, basename($html_images[$i]), $content_type);
		  		}
			}
	  	}
	}

	/**
	 * 添加纯文本。不发送HTML电子邮件时使用此功能
	 * Adds plain text. Use this function
	 * when NOT sending html email
	 * @param string $text 纯文本文字
	 */
	function add_text($text = '') {
		$this->text = tep_convert_linefeeds(array("\r\n", "\n", "\r"), $this->lf, $text);
	}

	/**
	 * 添加一个HTML邮件的一部分。也取代的Content-ID的图像名称。并找出所有图片，并读取图片文件内容存储在$this->html_images中
	 * Adds a html part to the mail.
	 * Also replaces image names with
	 * content-id's.
	 *  @param string $html HTML
	 *  @param string $text
	 *  @param string $images_dir HTML中图片路径前缀
	 */

	function add_html($html, $text = NULL, $images_dir = NULL) {

		if(!preg_match('/\<html/i',$html) && !preg_match('/\<table/i',$html) && !preg_match('/\<div/i',$html) ){
			$this->html = tep_convert_linefeeds(array("\r\n", "\n", "\r"), '<br>', $html);
		}else{$this->html = $html;}

		$this->html_text = tep_convert_linefeeds(array("\r\n", "\n", "\r"), $this->lf, $text);

		if (isset($images_dir)) $this->find_html_images($images_dir);
	}

	/**
	 * 添加一个图片文件内容到$this->html_images[]中
	 * Adds an image to the list of embedded
	 * images.
	 * @param string $file 图片文件的内容
	 * @param string $name 图片文件名
	 * @param string $c_type 图片类型 [image/gif|image/jpeg等等]
	 */

	function add_html_image($file, $name = '', $c_type='application/octet-stream') {
		$this->html_images[] = array('body' => $file,
									 'name' => $name,
									 'c_type' => $c_type,
									 'cid' => md5(uniqid(time())));
	}

	/**
	 * 添加一个文件附件名单。
	 * Adds a file to the list of attachments.
	 * @param string $file 
	 * @param string $name
	 * @param string $c_type
	 * @param string $encoding
	 */

	function add_attachment($file, $name = '', $c_type='application/octet-stream', $encoding = 'base64') {
		$this->attachments[] = array('body' => $file,
									 'name' => $name,
									 'c_type' => $c_type,
									 'encoding' => $encoding);
	}

	/**
	 * Adds a text subpart to a mime_part object
	 */
	function add_text_part(&$obj, $text) {
		$params['content_type'] = 'text/html';
		$params['encoding'] = $this->build_params['text_encoding'];
		$params['charset'] = $this->build_params['text_charset'];

		if (is_object($obj)) {
			return $obj->addSubpart($text, $params);
		} else {
			return new mime($text, $params);
		}
	}

	/**
	 * Adds a html subpart to a mime_part object
	 */
	function add_html_part(&$obj) {
		$params['content_type'] = 'text/html';
		$params['encoding'] = $this->build_params['html_encoding'];
		$params['charset'] = $this->build_params['html_charset'];

		if (is_object($obj)) {
			return $obj->addSubpart($this->html, $params);
		} else {
			return new mime($this->html, $params);
		}
	}

	/**
	 * Starts a message with a mixed part
	 */
	function add_mixed_part() {
		$params['content_type'] = 'multipart/mixed';

		return new mime('', $params);
	}

	/**
	 * Adds an alternative part to a mime_part object
	 */	
	function add_alternative_part(&$obj) {
		$params['content_type'] = 'multipart/alternative';

		if (is_object($obj)) {
			return $obj->addSubpart('', $params);
		} else {
			return new mime('', $params);
		}
	}

	/**
	 * Adds a html subpart to a mime_part object
	 */
	function add_related_part(&$obj) {
		$params['content_type'] = 'multipart/related';

		if (is_object($obj)) {
			return $obj->addSubpart('', $params);
		} else {
			return new mime('', $params);
		}
	}

	/**
	 * Adds an html image subpart to a mime_part object
	 */
	function add_html_image_part(&$obj, $value) {
		$params['content_type'] = $value['c_type'];
		$params['encoding'] = 'base64';
		$params['disposition'] = 'inline';
		$params['dfilename'] = $value['name'];
		$params['cid'] = $value['cid'];

		$obj->addSubpart($value['body'], $params);
	}

	/**
	 * Adds an attachment subpart to a mime_part object
	 */
	function add_attachment_part(&$obj, $value) {
		$params['content_type'] = $value['c_type'];
		$params['encoding'] = $value['encoding'];
		$params['disposition'] = 'attachment';
		$params['dfilename'] = $value['name'];

		$obj->addSubpart($value['body'], $params);
	}

	/**
	 * Builds the multipart message from the
	 * list ($this->_parts). $params is an
	 * array of parameters that shape the building
	 * of the message. Currently supported are:
	 *
	 * $params['html_encoding'] - The type of encoding to use on html. Valid options are
	 *							"7bit", "quoted-printable" or "base64" (all without quotes).
	 *							7bit is EXPRESSLY NOT RECOMMENDED. Default is quoted-printable
	 * $params['text_encoding'] - The type of encoding to use on plain text Valid options are
	 *							"7bit", "quoted-printable" or "base64" (all without quotes).
	 *							Default is 7bit
	 * $params['text_wrap']	 - The character count at which to wrap 7bit encoded data.
	 *							Default this is 998.
	 * $params['html_charset']  - The character set to use for a html section.
	 *							Default is iso-8859-1
	 * $params['text_charset']  - The character set to use for a text section.
	 *						  - Default is iso-8859-1
	 */
	function build_message($params = '') {	
	/* HPDL PHP3 */
	//	function build_message($params = array()) {

		if ($params == '') $params = array();

		if (count($params) > 0) {
			reset($params);
			while(list($key, $value) = each($params)) {
				$this->build_params[$key] = $value;
			}
		}

		if (tep_not_null($this->html_images)) {
			reset($this->html_images);
			while (list(,$value) = each($this->html_images)) {
				$this->html = str_replace($value['name'], 'cid:' . $value['cid'], $this->html);
			}
		}

		$null = NULL;
		$attachments = ((tep_not_null($this->attachments)) ? true : false);
		$html_images = ((tep_not_null($this->html_images)) ? true : false);
		$html = ((tep_not_null($this->html)) ? true : false);
		$text = ((tep_not_null($this->text)) ? true : false);

		switch (true) {
			case (($text == true) && ($attachments == false)):
				/* HPDL PHP3 */
				//			$message =& $this->add_text_part($null, $this->text);
				$message = $this->add_text_part($null, $this->text);
				break;
			case (($text == false) && ($attachments == true) && ($html == false)):
				/* HPDL PHP3 */
				//			$message =& $this->add_mixed_part();
				$message = $this->add_mixed_part();
	
				for ($i=0; $i<count($this->attachments); $i++) {
				$this->add_attachment_part($message, $this->attachments[$i]);
				}
				break;
			case (($text == true) && ($attachments == true)):
				/* HPDL PHP3 */
				//			$message =& $this->add_mixed_part();
				$message = $this->add_mixed_part();
				$this->add_text_part($message, $this->text);
	
				for ($i=0; $i<count($this->attachments); $i++) {
				$this->add_attachment_part($message, $this->attachments[$i]);
				}
				break;
			case (($html == true) && ($attachments == false) && ($html_images == false)):
				if (tep_not_null($this->html_text)) {
					/* HPDL PHP3 */
					//			$message =& $this->add_alternative_part($null);
					$message = $this->add_alternative_part($null);
					$this->add_text_part($message, $this->html_text);
					$this->add_html_part($message);
				} else {
					/* HPDL PHP3 */
					//			$message =& $this->add_html_part($null);
				$message = $this->add_html_part($null);
				}
				break;
			case (($html == true) && ($attachments == false) && ($html_images == true)):
				if (tep_not_null($this->html_text)) {
					/* HPDL PHP3 */
					//			$message =& $this->add_alternative_part($null);
					$message = $this->add_alternative_part($null);
					$this->add_text_part($message, $this->html_text);
					/* HPDL PHP3 */
					//			$related =& $this->add_related_part($message);
					$related = $this->add_related_part($message);
				} else {
					/* HPDL PHP3 */
					//			$message =& $this->add_related_part($null);
					//			$related =& $message;
					$message = $this->add_related_part($null);
					$related = $message;
				}
				$this->add_html_part($related);

				for ($i=0; $i<count($this->html_images); $i++) {
					$this->add_html_image_part($related, $this->html_images[$i]);
				}
				break;
			case (($html == true) && ($attachments == true) && ($html_images == false)):
				/* HPDL PHP3 */
				//			$message =& $this->add_mixed_part();
				$message = $this->add_mixed_part();
				if (tep_not_null($this->html_text)) {
					/* HPDL PHP3 */
					//			$alt =& $this->add_alternative_part($message);
					$alt = $this->add_alternative_part($message);
					$this->add_text_part($alt, $this->html_text);
					$this->add_html_part($alt);
				} else {
					$this->add_html_part($message);
				}

				for ($i=0; $i<count($this->attachments); $i++) {
					$this->add_attachment_part($message, $this->attachments[$i]);
				}
				break;
			case (($html == true) && ($attachments == true) && ($html_images == true)):
				/* HPDL PHP3 */
				//			$message =& $this->add_mixed_part();
				$message = $this->add_mixed_part();

				if (tep_not_null($this->html_text)) {
					/* HPDL PHP3 */
					//			$alt =& $this->add_alternative_part($message);
					$alt = $this->add_alternative_part($message);
					$this->add_text_part($alt, $this->html_text);
					/* HPDL PHP3 */
					//			$rel =& $this->add_related_part($alt);
					$rel = $this->add_related_part($alt);
				} else {
					/* HPDL PHP3 */
					//			$rel =& $this->add_related_part($message);
					$rel = $this->add_related_part($message);
				}
				$this->add_html_part($rel);

				for ($i=0; $i<count($this->html_images); $i++) {
					$this->add_html_image_part($rel, $this->html_images[$i]);
				}

				for ($i=0; $i<count($this->attachments); $i++) {
					$this->add_attachment_part($message, $this->attachments[$i]);
				}
				break;
		}

		if ( (isset($message)) && (is_object($message)) ) {
			$output = $message->encode();
			$this->output = $output['body'];

			reset($output['headers']);
			while (list($key, $value) = each($output['headers'])) {
				$headers[] = $key . ': ' . $value;
			}
	
			$this->headers = array_merge((array)$this->headers, (array)$headers);
	
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Sends the mail.
	 */

	function send($to_name, $to_addr, $from_name, $from_addr, $subject = '', $headers = '', $email_charset = CHARSET) {
	
		//echo $email_charset;
		
		if($email_charset!=""){
			$old_charset = $email_charset;
		}else{
			$old_charset = constant('CHARSET');
		}
		
		if ((strstr($to_name, "\n") != false) || (strstr($to_name, "\r") != false)) {
			return false;
		}
		if ((strstr($to_addr, "\n") != false) || (strstr($to_addr, "\r") != false)) {
			return false;
		}
		if ((strstr($subject, "\n") != false) || (strstr($subject, "\r") != false)) {
			return false;
		}
		if ((strstr($from_name, "\n") != false) || (strstr($from_name, "\r") != false)) {
			return false;
		}
		if ((strstr($from_addr, "\n") != false) || (strstr($from_addr, "\r") != false)) {
			return false;
		}

		$to = (($to_name != '') ? '"=?'.'gbk'.'?B?' . base64_encode(iconv($old_charset,'gbk'.'//IGNORE',$to_name)) . '?=" <' . $to_addr . '>' : $to_addr);
		$from = (($from_name != '') ? '"=?'.'gbk'.'?B?' . base64_encode(iconv($old_charset,'gbk'.'//IGNORE',$from_name)) . '?=" <' . $from_addr . '>' : $from_addr);

		if (is_string($headers)) {
			$headers = explode($this->lf, trim($headers));
		}

		for ($i=0; $i<count($headers); $i++) {
			if (is_array($headers[$i])) {
				for ($j=0; $j<count($headers[$i]); $j++) {
					if ($headers[$i][$j] != '') {
						$xtra_headers[] = $headers[$i][$j];
					}
				}
			}

			if ($headers[$i] != '') {
				$xtra_headers[] = $headers[$i];
			}
		}

		if (!isset($xtra_headers)) {
			$xtra_headers = array();
		}

		//zhh added
		$subject	= '=?'.'gbk'.'?B?'.base64_encode(iconv($old_charset,'gbk'.'//IGNORE',$subject)).'?=';
		$msn=$this->output;
 		$msn = base64_decode($msn);
		$msn = iconv($old_charset,'gbk'.'//IGNORE',$msn);

		if(!preg_match('/\<html/i',$msn)){
			if(!preg_match('/\<div/i',$msn) && !preg_match('/\<table/i',$msn)){
				$msn=nl2br($msn);################
			}
			$html_top='<html '.HTML_PARAMS.'>
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset='.'gbk'.'" />
				<title></title>
				</head>
				<body>';
			//$html_top=base64_encode($html_top);
			$html_bottom='</body></html>'.' ';
			//$html_bottom=base64_encode($html_bottom);
		}else{ 
			$html_top=""; $html_bottom="";
		}

		$msn=wordwrap(base64_encode($html_top.$msn.$html_bottom),70,"\n",true);
		//zhh added end
		
		if (EMAIL_TRANSPORT == 'smtp') {
			return mail($to_addr, $subject, $msn, 'From: ' . $from . $this->lf . 'To: ' . $to . $this->lf . implode($this->lf, $this->headers) . $this->lf . implode($this->lf, $xtra_headers));
		} else {
			return mail($to, $subject, $msn, 'From: '.$from.$this->lf.implode($this->lf, $this->headers).$this->lf.implode($this->lf, $xtra_headers));
			//echo $this->output;
			//exit;
		}
	}

	/**
	 * Use this method to return the email
	 * in message/rfc822 format. Useful for
	 * adding an email to another email as
	 * an attachment. there's a commented
	 * out example in example.php.
	 *
	 * string get_rfc822(string To name,
	 *	   string To email,
	 *	   string From name,
	 *	   string From email,
	 *	   [string Subject,
	 *		string Extra headers])
	 */

	function get_rfc822($to_name, $to_addr, $from_name, $from_addr, $subject = '', $headers = '') {
		// Make up the date header as according to RFC822
		$date = 'Date: ' . date('D, d M y H:i:s');
		$to = (($to_name != '') ? 'To: "' . $to_name . '" <' . $to_addr . '>' : 'To: ' . $to_addr);
		$from = (($from_name != '') ? 'From: "' . $from_name . '" <' . $from_addr . '>' : 'From: ' . $from_addr);

		if (is_string($subject)) {
			$subject = 'Subject: ' . $subject;
		}

		if (is_string($headers)) {
			$headers = explode($this->lf, trim($headers));
		}

		for ($i=0; $i<count($headers); $i++) {
			if (is_array($headers[$i])) {
				for ($j=0; $j<count($headers[$i]); $j++) {
					if ($headers[$i][$j] != '') {
						$xtra_headers[] = $headers[$i][$j];
					}
				}
			}

			if ($headers[$i] != '') {
				$xtra_headers[] = $headers[$i];
			}
		}

		if (!isset($xtra_headers)) {
			$xtra_headers = array();
		}

		$headers = array_merge((array)$this->headers, (array)$xtra_headers);

		return $date . $this->lf . $from . $this->lf . $to . $this->lf . $subject . $this->lf . implode($this->lf, $headers) . $this->lf . $this->lf . $this->output;
	}
}
?>