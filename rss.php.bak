<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce
  Copyright (c) 2003 Rodolphe Quiedeville <rodolphe@quiedeville.org>

  Author : Rodolphe Quiedeville <rodolphe@quiedeville.org>

  Released under the GNU General Public License

  Usage : call /catalog/rss.php?box=BOX_NAME
  If no BOX_NAME specified we use whats_new by default

  Remember to define in configure.php :

  define('DIR_WS_RSS', DIR_WS_INCLUDES . 'rss/');


  TODO : set channel description correctly
*/

//header("Content-type: text/plain");

//require('includes/application_top.php');

print '<?xml version="1.0" encoding="BIG5"?>' . "\n";
print '<rss version="0.91">' . "\n";
print '  <channel>' . "\n";
print "    <title>".$latest_news['newsdesk_article_name'] ."</title>\n";
print "    <source></source>\n";
print "    <link>".'<a href="http://www.yourdomain.com/">RSS Newsfeeds</a>'."</link>\n";
print "    <language>en-uk</language>\n\n";


require(DIR_FS_LANGUAGES . $language . '/' . FILENAME_DEFAULT);

if (!strlen($box))
{
  $box = "newsdesk_latest";
}

$file = DIR_WS_RSS . $box . '.php';

if (file_exists($file))
{
  require($file); 
}
print "  </channel>\n";
print "</rss>\n";
?>