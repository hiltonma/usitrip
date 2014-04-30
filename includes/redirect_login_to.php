<?php
/*
  /includes/redirect_login_to.php

  Shoppe Enhancement Controller - Copyright (c) 2003 WebMakers.com
  Linda McGrath - osCommerce@WebMakers.com

*/

// WebMakers.com Added: Login redirect to last page
  if (sizeof($navigation->snapshot) > 0) {
    $origin_href = tep_href_link($navigation->snapshot['page'], tep_array_to_string($navigation->snapshot['get'], array(tep_session_name())), $navigation->snapshot['mode']);
    $navigation->clear_snapshot();
    $link = $origin_href;
  } else {
    $link = tep_href_link(FILENAME_DEFAULT);
  }
?>
