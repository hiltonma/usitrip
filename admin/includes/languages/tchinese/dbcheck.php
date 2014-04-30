<?php
/*
_____________________________________________________________________

dbcheck.php Version 1.0.2 23/03/2002

osCommerce Database Maintenance Add-on
Copyright (c) 2002 James C. Logan

osCommerce, Open Source E-Commerce Solutions
Copyright (c) 2002 osCommerce
http://www.oscommerce.com

IMPORTANT NOTE:

This script is not part of the official osCommerce distribution
but an add-on contributed to the osCommerce community. Please
read the README and  INSTALL documents that are provided 
with this file for further information and installation notes.

LICENSE:

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
_____________________________________________________________________

*/

define('TEXT_HEADING_DBCHECK','Database Tables Check');
define('TEXT_HEADING_DBCHECK_RESULTS','Database Tables Check Results');
define('TEXT_DISPLAY_DBCHECK','This routine checks the selected table(s) for errors.<br><br>The Message Type returned should be one of status (normal), error, info, or warning. If a Message Type other than "status" and a Message of "OK" is returned, you may have to run a repair on the table. Read the Message carefully to determine if this is required.');
define('TEXT_DISPLAY_DBCHECK_SUBMIT','Check the Selected Tables');
define('TEXT_DISPLAY_DBCHECK_ALLTABLES','All osCommerce Tables');

?>
