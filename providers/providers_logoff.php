<?php
/*
  $Id: providers_logoff.php,v 1.1.1.1 2004/03/04 23:38:43 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top_providers.php');

session_unregister('providers_id');
session_unregister('providers_agency_id');
session_unregister('providers_email_address');

tep_redirect(FILENAME_PROVIDERS_LOGIN);
?>