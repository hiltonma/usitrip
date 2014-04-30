<?php
/*
  $Id: application_bottom.php,v 1.1.1.1 2004/03/04 23:39:39 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

// close session (store variables)
  
  tep_session_close();

  if (STORE_PAGE_PARSE_TIME == 'true') {
    if (!is_object($logger)) $logger = new logger;
    echo $logger->timer_stop(DISPLAY_PAGE_PARSE_TIME);
  }
?>
<script type="text/javascript">
/*把提示内容以弹出框的形式告诉使用者*/
if(jQuery('td[class="messageStackError"]').text().length > 1){
	alert(jQuery('td[class="messageStackError"]').text());
}
</script>
