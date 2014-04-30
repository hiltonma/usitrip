<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty truncate modifier plugin
 *
 * Type:     modifier<br>
 * Name:     truncate<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *           optionally splitting in the middle of a word, and
 *           appending the $etc string or inserting $etc into the middle.
 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php
 *          truncate (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param integer
 * @param string
 * @param boolean
 * @param boolean
 * @return string
 */
function smarty_modifier_cut($str, $len = 80, $etc = ' ...')
{
	$return_str = '';
    if($len>0){
		$sLen=strlen($str);
		if($len>=$sLen){
			$return_str = $str;
		}else{
			for($i=0;$i<($len-1);$i++){
				if(ord(substr($str,$i,1))>0xa0){
					$i++;
				}
			}
			
			if($i>=$len)
				$return_str = substr($str,0,$len);
			elseif(ord(substr($str,$i,1))>0xa0)
				$return_str = substr($str,0,$len-1);
			else
				$return_str = substr($str,0,$len);
				
			$return_str .= $etc;
		}
	}
	return $return_str;
}

/* vim: set expandtab: */

?>
