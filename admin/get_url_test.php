<?php
  // É­¡õ¹Ò¡õ¡õôÀ 2002 ÀÃÇ»¨Û
  // ¡õ¡õí¼¡õ¡õÙç¡õôÀ 2003 ÀÃ
  $text = "<div><div>aaa</div><div>bbb</div><div>ccc</div></div>";
  $text = "0<div>1<div>2<div>dd</div>0aaa</div>1 3<div>bbb</div>2 4<div>ccc</div>3</div>4";

  //À£ñöè÷¡õ¡õ´á¡õ¡õ¡õ÷Ð
  $text_h_array = preg_split('/<div>/',$text);
  $text_f_array = preg_split('/<\/div>/',$text);
  if(count($text_h_array)!=count($text_f_array)){ echo 'è÷¡õÏé¡õ÷Ð'; }
  $out ='';
  $e = '';
  for($i=0; $i<count($text_h_array); $i++){
  	if($i>0){ $e='<howard_'.$i.'>'.'<div>';}
	$out .= $e.$text_h_array[$i];
  }
  
  echo $out;
  //print_r($text_array);


?>
