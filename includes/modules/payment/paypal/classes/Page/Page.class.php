<?php
/*
  $Id: Page.class.php,v 2.8 2004/09/11 devosc Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  DevosC, Developing open source Code
  http://www.devosc.com

  Copyright (c) 2003 osCommerce
  Copyright (c) 2004 DevosC.com

  Released under the GNU General Public License
*/

class PayPal_Page {

  var $baseDirectory;

  var $metaTitle = 'osCommerce: PayPal_Shopping_Cart_IPN';

  // class constructor
  function PayPal_Page() {
  }

  function template() {
    return $this->baseDirectory . 'templates/' . $this->templateName . '.tpl.php';
  }

  function setTemplate($template) {
    $this->templateName = $template;
  }

  function setContentFile($contentFile = '') {
    $this->contentFile = $contentFile;
  }

  function setContentLangaugeFile($base_dir, $lng_dir, $lng_file) {
    if(file_exists($base_dir .'/'. $lng_dir . '/' . $lng_file)) {
      $this->contentFile = $base_dir .'/'. $lng_dir . '/' . $lng_file;
    } elseif (file_exists($base_dir . '/english/' . $lng_file)) {
      $this->contentFile =  $base_dir . '/english/' . $lng_file;
    }
  }

  function setTitle($title = '') {
    $this->pageTitle = $title;
  }

  function setOnLoad($javascript) {
    $this->onLoad = $javascript;
  }

  function setMetaTitle($title) {
    $this->metaTitle = $title;
  }

  function includeLanguageFile($paypal_dir, $lng_dir, $lng_file) {
    $base_dir = $this->baseDirectory . $paypal_dir . '/';
    if(file_exists($base_dir . $lng_dir . '/' . $lng_file)) {
      include_once($base_dir . $lng_dir . '/' . $lng_file);
    } elseif (file_exists($base_dir . 'english/' . $lng_file)) {
      include_once($base_dir . 'english/' . $lng_file);
    }
  }

  function setBaseDirectory($dir) {
    $this->baseDirectory = $dir;
  }

  function setBaseURL($location) {
    $this->baseURL = $location;
  }

  function imagePath($image) {
    return $this->baseURL. 'images/'.$image;
  }

  function image($img,$alt='') {
    return tep_image($this->baseURL.'images/'.$img,$alt);
  }

  function draw_href_link($ppURLText, $ppURLParams = '', $ppURL = FILENAME_PAYPAL, $js = true) {
    //$ppURL = tep_href_link(FILENAME_PAYPAL,'action=details&info='.$ppTxnID);
    $ppURL = tep_href_link($ppURL,$ppURLParams);
    if ($js === true) {
      $ppScriptLink = '<script language="JavaScript"><!--
      document.write("<a style=\"color: #0033cc; text-decoration: none;\" href=\"javascript:openWindow(\''.$ppURL.'\');\" tabindex=\"-1\">'.$ppURLText.'</a>");
      --></script><noscript><a style="color: #0033cc; text-decoration: none;" href="'.$ppURL.'" target="PayPal">'.$ppURLText.'</a></noscript>';
    } else {
      $ppScriptLink = '<a style="color: #0033cc; text-decoration: none;" href="'.$ppURL.'" target="PayPal">'.$ppURLText.'</a>';
    }
    return $ppScriptLink;
  }

  function addJavaScript($filename) {
    $this->javascript[] = tep_db_input($filename);
  }

  function importJavaScript() {
    if(is_array($this->javascript)) {
      $javascript = '';
      $javascriptCount = count($this->javascript);
      for($i=0; $i<$javascriptCount; $i++) {
        $javascript .= '<script language="javascript" src="' . $this->javascript[$i] . '"></script>'."\n";
      }
      return $javascript;
    }
  }

  function addCSS($filename) {
    $this->css[] = tep_db_input($filename);
  }

  function getCSS($filename) {
    $css = '';
    if(function_exists(file_get_contents)) {
      $css = file_get_contents($this->baseDirectory.'templates/css/'.stripslashes($filename).'.css');
    } else {
      $fh = @fopen($filename,'rb');
      while (!feof($fh))
        $css .= @fread($fh, 8192);
      @fclose($fh);
    }
    return $css;
  }

  function importCSS() {
    $css = "<style type='text/css' media=\"all\">\n";
    if(is_array($this->css)) {
      $cssCount = count($this->css);
      for($i=0; $i<$cssCount; $i++) {
        $css .= "@import url(" . $this->css[$i] . ");\n";
      }
    }
    return $css."</style>\n";
  }

  function logo() {
    return base64_decode("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");
  }

  function copyright() {
  return "<table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"2\">
      <tr><td><br class=\"h10\"/></td></tr>
      <tr><td align=\"center\" class=\"ppfooter\">E-Commerce Engine Copyright &copy; 2000-2004 <a href=\"http://www.oscommerce.com\" class=\"copyright\" target=\"_blank\">osCommerce</a><br/>osCommerce provides no warranty and is redistributable under the <a href=\"http://www.fsf.org/licenses/gpl.txt\" class=\"copyright\" target=\"_blank\">GNU General Public License</a></td></tr>
      <tr><td><br class=\"h10\"/></td></tr><tr><td align=\"center\" class=\"ppfooter\"><a href=\"http://www.oscommerce.com\" target=\"_blank\" class=\"poweredByButton\"><span class=\"poweredBy\">Powered By</span><span class=\"osCommerce\">" . PROJECT_VERSION . "</span></a></td></tr><tr><td><br class=\"h10\"/></td></tr></table>";
  }
}//end class
?>
