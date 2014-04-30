<?php
/*
///////////////////////////////////////////////////
  file: visual_verify_code.php,v 1.0 26SEP03

Written for use with:
  osCommerce, Open Source E-Commerce Solutions http://www.oscommerce.com
Part of Contribution Named:
  Visual Verify Code (VVC) by William L. Peer, Jr. (wpeer@forgepower.com) for www.onlyvotives.com

[Modified By] [Date] [Mods Made]
-------------------------------------------


-------------------------------------------
*/

//////////////////////////////
/* This funtion has the responsibility of displaying the actual visual code with random results.
   It randomly picks an x and y position as well as font size for each character in the visual code
*/
  function vvcode_render_code($code) {
        if (!empty($code)) {
            $imwidth=100;
            $imheight=25;
            Header("Content-type: image/Jpeg");
            $im = @ImageCreate ($imwidth, $imheight) or die ("ERROR! Cannot create new GD image - see: verify_code_img_gen.php");

            $background_color = ImageColorAllocate ($im, 255, 255, 255);
            $text_color = ImageColorAllocate ($im, 0, 0, 0);
            $border_color = ImageColorAllocate ($im, 154, 154, 154);

            //strip any spaces that may have crept in
            //end-user wouldn't know to type the space! :)
            $code = str_replace(" ", "", $code);
            $x=0;

            $stringlength = strlen($code);
            for ($i = 0; $i< $stringlength; $i++) {
                 $x = $x + (rand (8, 15));
                 $y = rand (2, 10);
                 $font = rand (2,5);
                 $single_char = substr($code, $i, 1);
                 imagechar($im, $font, $x, $y, $single_char, $text_color);
                }

            imagerectangle ($im, 2, 2, $imwidth-2, $imheight-2, $border_color);

            ImageJpeg($im);
            ImageDestroy;
        }
  }
?>
