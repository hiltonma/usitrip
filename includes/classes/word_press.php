<?php
/*
本类是自动登录、注册Word Press博客
*/
class word_press{
	var $word_press_url;
	var $auth_user;
	var $auth_pass;
	function word_press(){
		$this->word_press_url = HTTP_SERVER.'/blog';
		if(IS_DEV_SITES==true){
			$this->auth_user = 'dev';
			$this->auth_pass = 'dev007';
		}elseif(IS_QA_SITES==true){
			$this->auth_user = 'qa';
			$this->auth_pass = 'qa007';
		}
	}
	
	//自动注册博客
	function auto_reg_user($user_name, $user_email, $user_pass){
/*		$data = array('user_login'=>$user_name, 'user_email'=>$user_email, 'user_pass'=>$user_pass);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->word_press_url.'/wp-login.php?action=register&source=curl');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		if(tep_not_null($this->auth_user) && tep_not_null($this->auth_pass)){
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);  
			curl_setopt($ch, CURLOPT_USERPWD,$this->auth_user.':'.$this->auth_pass);
		}
		$result = curl_exec($ch);
		curl_close ($ch);
*/	}
	
	//自动登录博客
	function auto_login_user($user_name, $user_pass){
/*		if(!(int)$this->check_user($user_name)){
			$this->auto_reg_user($user_name, $user_name, $user_pass);
		}
		$url = $this->word_press_url.'/wp-login.php?ajax=true&source=curl';
		$js_code = '<script type="text/javascript">'."\n";
		$js_code .= 'function auto_login_wordpress(){';
		$js_code .= ' var url=url_ssl("'.$url.'");';
		$js_code .= ' var data = {
			"log": "'.$user_name.'",
			"pwd": "'.$user_pass.'",
			"testcookie": "1"
		}; ';
		$js_code .= ' jQuery.post(url, data);';

		$js_code .= '} auto_login_wordpress();'."\n";	
		$js_code .= '</script>'."\n";
		
		return $js_code;
*/		
	}
	
	//检查账号是否存在
	function check_user($user_email){
		/*$sql = tep_db_query('select ID FROM `tffblog_users` WHERE user_email = "'.$user_email.'" limit 1');
		$row = tep_db_fetch_array($sql);
		return (int)$row['ID'];*/
	}
}
?>