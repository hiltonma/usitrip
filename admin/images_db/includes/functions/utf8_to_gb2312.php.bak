<?php
 function utf_to_gb($instr) {
 $fp = fopen('unicode-gb.tab', 'r' );
 $len = strlen($instr);
 $outstr = '';
 for( $i = $x = 0 ; $i < $len ; $i++ ) {
  $b1 = ord($instr[$i]);
  if( $b1 < 0x80 ) {
   $outstr[$x++] = chr($b1);
#   printf( "[%02X]", $b1);
  }
  elseif( $b1 >= 224 ) { # 3 bytes UTF-8
   $b1 -= 224;
   $b2 = ord($instr[$i+1]) - 128;
   $b3 = ord($instr[$i+2]) - 128;
   $i += 2;
   $uc = $b1 * 4096 + $b2 * 64 + $b3 ;
   fseek( $fp, $uc * 2 );
   $gb = fread( $fp, 2 );
   $outstr[$x++] = $gb[0];
   $outstr[$x++] = $gb[1];
#   printf( "[%02X%02X]", ord($gb[0]), ord($gb[1]));
  }
  elseif( $b1 >= 192 ) { # 2 bytes UTF-8
   printf( "[%02X%02X]", $b1, ord($instr[$i+1]) );
   $b1 -= 192;
   $b2 = ord($instr[$i]) - 128;
   $i++;
   $uc = $b1 * 64 + $b2 ;
   fseek( $fp, $uc * 2 );
   $gb = fread( $fp, 2 );
   $outstr[$x++] = $gb[0];
   $outstr[$x++] = $gb[1];
#   printf( "[%02X%02X]", ord($gb[0]), ord($gb[1]));
  }
 }
 fclose($fp);
 if( $instr != '' ) {
#  echo '##' . $instr . " becomes " . join( '', $outstr) . "<br>n";
  return join( '', $outstr);
 }
}
?>