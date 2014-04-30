<?php
/*
  $Id: password_funcs.php,v 1.1.1.1 2004/03/04 23:39:56 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

////
// This funstion validates a plain text password with an
// encrpyted password
  function tep_validate_password($plain, $encrypted) {
    if (tep_not_null($plain) && tep_not_null($encrypted)) {
// split apart the hash / salt
      $stack = explode(':', $encrypted);

      if (sizeof($stack) != 2) return false;

      if (md5($stack[1] . $plain) == $stack[0]) {
        return true;
      }
    }

    return false;
  }

////
// This function makes a new password from a plaintext password.
  function tep_encrypt_password($plain) {
    $password = '';

    for ($i=0; $i<10; $i++) {
      $password .= tep_rand();
    }

    $salt = substr(md5($password), 0, 2);

    $password = md5($salt . $plain) . ':' . $salt;

    return $password;
  }
  
 function scs_cc_encrypt($text) {   
   $key = CC_ENC_KEY_SECURE_KEY;
   $key = md5($key);
   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
   $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);
   
   if($text!=''){
  	$return_enc_text = base64_encode($crypttext);
   }else{
    $return_enc_text = '';
   }
   
   return $return_enc_text;   
}
   
function scs_cc_decrypt($enc) {
	$key = CC_ENC_KEY_SECURE_KEY;
	$enc =base64_decode($enc);
	$key = md5($key);
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);	
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $enc, MCRYPT_MODE_ECB, $iv);
	
	if($enc!=''){
	$return_dec_text = trim($decrypttext);
	}else{
	$return_dec_text = '';
	} 
	  
	return ($return_dec_text);
} 
?>
