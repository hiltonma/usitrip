<?php
/*
_____________________________________________________________________

dboptimize.php Version 1.0.2 23/03/2002

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

define('TEXT_HEADING_DBOPTIMIZE','Database Tables Optimization');
define('TEXT_HEADING_DBOPTIMIZE_RESULTS','Database Tables Optimization Results');
define('TEXT_DISPLAY_DBOPTIMIZE','During the course of normal use, records can and will be deleted. Deleted records are maintained in a linked list and subsequent INSERT operations reuse old record positions. Running the Optimize Tables routine reclaims the unused space. It is recommended you run this procedure regularily to ensure the best possible performance.<br><br>While the Optimize Tables routine is executing, the original table is readable by other clients. Updates and writes to the table are stalled until the new table is ready. This is done in such a way that all updates are automatically redirected to the new table without any failed updates.<br><br>The Message Type returned should be one of status (normal), error, info, or warning. If a Message Type other than "status" and a Message of "OK" is returned, you may have to run a repair on the table. Read the Message carefully to determine if this is required.');
define('TEXT_DISPLAY_DBOPTIMIZE_SUBMIT','Optimize the Selected Tables');
define('TEXT_DISPLAY_DBOPTIMIZE_ALLTABLES','All osCommerce Tables');

?>
