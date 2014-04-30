<?php
//<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
//簡繁體不能互轉的文字
$sheng_pi_zhi_big5 = array();

$txet_big5 = 
iconv('utf-8','big5'.'//IGNORE',  '鎔,玥,珪,毳,掱,垚,煐,焺,煒,烓,燚,焜,瑛,璟,琀,瑋,瑢,瑱,琤,玭,瑒,瑒,琨,嫚,婻,嬛,翀,翽,翯,珝,翾,昫,昉,昍,晢,暘,暔,眚,凊,湜,汧,沄,湦,沕,禕,屾,奡,劼,駸,郬,虓,蓧,筱,鶤,靘,颺,臸,棽,燊,嶢');
$txet_big5_array = explode(',',$txet_big5);

//只有字符中不存在以下文字才執行替換，否則不可替換，絕對不可以。
$txet_can_not_replace_big = 
iconv('utf-8','gb2312'.'//IGNORE','妈,月,痎,或,贡,蚕,亏,赿,辞,衬,当,礝,冯,害,谤,扔,馕,抚,言,抵,数,数,珑,酷,犬,尔,触,蚓,球,曳,虫,误,棠,烫,债,穤,萜,抑,嗅,贏,葵,蔷,赥,莶,恻,少,后,苏,颹,尊,肿,镭,育,鸟,渚,肯,促,愤,关,术');
/*
iconv('utf-8','gb2312'.'//IGNORE','妈,玥,痎,馘,賁,蝅,迃,諹,赿,辪,襨,闣,礝,冯,縖,謗,蕹,馕,庑,讠,蚳,薮,薮,礲,醦,厣,镾,觘,蚓,赇,曳,蚩,误,棠,烫,债,穤,萜,抑,嗅,贏,薒,薔,赥,薟,恻,少,卮,苏,颹,尊,臃,鐂,逳,鮵,渚,肎,踀,偾,關,錰');
*/
$txet_can_not_replace_big_array=explode(',',$txet_can_not_replace_big);

$txet_code_big5 = '&#37780;,&#29605;,&#29674;,&#27635;,&#25521;,&#22426;,&#29008;,&#28986;,&#29010;,&#28883;,&#29146;,&#28956;,&#29787;,&#29855;,&#29696;,&#29771;,&#29794;,&#29809;,&#29732;,&#29613;,&#29778;,&#29778;,&#29736;,&#23258;,&#23163;,&#23323;,&#32704;,&#32765;,&#32751;,&#29661;,&#32766;,&#26155;,&#26121;,&#26125;,&#26210;,&#26264;,&#26260;,&#30490;,&#20938;,&#28252;,&#27751;,&#27780;,&#28262;,&#27797;,&#31125;,&#23678;,&#22881;,&#21180;,&#39416;,&#37100;,&#34387;,&#34023;,&#31601;,&#40356;,&#38744;,&#39098;,&#33272;,&#26877;,&#29130;,&#23970;';
$txet_code_big5_array = explode(',',$txet_code_big5);

//取消替换
$txet_big5_array = array();
$txet_code_big5_array = array();

if(count($txet_big5_array) != count($txet_code_big5_array)){ echo 'string doce array error!'; exit; }

for($i=0; $i<count($txet_big5_array); $i++){
	$sheng_pi_zhi_big5[$i] = array($txet_big5_array[$i],$txet_code_big5_array[$i]);
}

function special_string_replace_for_big5($str='',$action='0'){
	global $sheng_pi_zhi_big5,$txet_can_not_replace_big_array;
	if($str==''){
		return $str;
	}
	$p = array();
	$repl = array();
	$replace_action = false;
	for($i=0; $i<count($sheng_pi_zhi_big5); $i++){
		$p = $sheng_pi_zhi_big5[$i][0];
		$repl = $sheng_pi_zhi_big5[$i][1];
		if(!preg_match('/'.$txet_can_not_replace_big_array[$i].'/',$str)){
			$str = str_replace($p, $repl, $str);
		}
	}

	return $str;
}
?>