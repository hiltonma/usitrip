<?php

// Current EP Version
$curver = '';

/*
  $Id: easypopulate.php,v 1.18 2002/12/05 14:34:18 elarifr Exp $

 
//*******************************
//*******************************
// E D I T   H I S T O R Y
//*******************************
//*******************************
Easy Populate

The point of easy populate is to let someone use an excel file exported to
a tab delimited file to set up their entire store:
categories, products, manufacturers, quantities, and prices.

-----------------------------------
Modified by Tim Wasson - Wasson65 (wasson65@nc.rr.com) to:
 accept category/subcategory names
 allow reordering of columns in csv file
 accept manufacturer name or id
 some minor code simplification
 accept and set status of the product if desired
 changed all # to // comment markers so KDE's Kate will syntax highlight correctly
 added support for default images for products, categories, and categories.
 added support for exporting a csv file that can be modified and sent back in.
-----------------------------------
1.1 Changes
 Fixed a stupid bug, I didn't change the references to easypopulate.php from excel.php
 Added note in the docs that if the Excel import is already done, don't need to do the alter table
 Removed the extra semicolon on the end of the line in the csv download.  It prevented you from exporting and importing a file.
-----------------------------------
1.2 Fixes
More bugs fixed
___________________________________
1.3 Fixes
Added another link to put csv file in temp file for access via tools->files, for some windows machines that refuse to dl right...
-----------------------------------
1.4 Fixes
Switchted to tabs for delimiters
Strip cr's and tab's from strings before exporting
Added explicit end of row field
Added ability to split a big file into smaller files in the temp dir
Preserve double quotes, single quotes, and apostrophes and commas
Removed references to category_root, it's no longer required
------------------------------------
1.5 Fixes
Changed --EOR-- to EOREOR for better excel usability.
Made script accept "EOREOR" or EOREOR without the quotes.
If inserting a new product, delete any product_descriptions with that product_id to avoid an error if old data was still present.
------------------------------------
1.6 Fixes
Ooops, manufacturer_id variable misspellings meant that mfg wasn't getting set or updated
Whe I re-arranged the code, I left out the call to actually put the data into the products table.  Ooops again...
------------------------------------
1.61 Fixes
One more manufacturer id name fix.
------------------------------------
Skipped to 2.0 because of the big jump in functionality
------------------------------------
2.0
Made EP handle magic-quotes
Thanks to Joshua Dechant aka dreamscape, for this fix
Rewrote the categories part to handle any number of categories
------------------------------------
2.1
Fix split files not splitting.
Change from "file" to "fgets" to read the file to be split to avoid out of memory problems... hopefully
------------------------------------
2.2
Added multi-language support. - thanks to elari, who wrote all the code around handling all active langs in OSC
Added category names assumed to be in default language - thanks to elari again!  who wrote all that code as well
Fixed bug where files wouldn't split because the check for EOREOR was too specific.
Added separate file for functions tep_get_uploaded_file and friends so that older snapshots will have it and work.
Finally updated the docs since they sucked
Moved product_model field to the start of each row because sometimes, if the image name was empty, the parsing would get confused
------------------------------------
2.3
Thanks to these sponsors - their financial support made this release possible!
Support for more than one output file format with variable numbers of columns
	Sponsored by Ted Joffs

Support for Separate Price per Customer mod
	Sponsored by Alan Pace

Support for Linda's Header Controller v2.0
	Sponsored by Stewart MacKenzie

Removed quotes around all the fields on export.
Added configuration variable so you can turn off the qoutes -> control codes replacement
Merged Elari's changes to not hardcode language id's
------------------------------------
2.31
Bugfix for single language non-english name/desc not being put into the output file.
The code was still checking for product_name_1 instead of product_name_$langid.
------------------------------------
2.32 - never released into the wild
Added config var $zero_qty_inactive, defaulted to true.
This will make zero qty items inactive by default.
---- STILL NEED TO DEBUG THIS! ----
------------------------------------
2.4
Support for Froogle downloads to EP.
	Sponsored by Ted Joffs
Changed comments - it's not Multiple Price per Product, it's
Separate Price per Customer.
------------------------------------
2.41beta
Fixed bugs with Froogle:
1. Category not getting built right
2. Strip HTML from name/description
3. Handle SearchEngineFriendly URL's

Adding "Delete" capability via EP. -- NOT COMPLETE
Fixed bug - the Model/Category would give SQL errors
Fixed bug - Items with no manufacturer were getting a man of '' (empty string)
Fixed bug - When trying to import, all items gave a "Deleting product" message but no db changes
	This was because I'd tried inserting the delete functionality and didn't finish it.
	Commented it out for now.
Added Date_added, fixed Date_available
Fixed active/inactive status settings
Fixed bug with misnamed item for Linda's Header Controller support
Fixed bug with SQL syntax error with new products
These following 3 fixes thanks to Yassen Yotov
	Fixed bug where the default image name vars weren't declared global in function walk()
	Added set_time_limit call, it won't cover all cases, but hopefully many. commented out to avoid
		complaints with safe mode servers
	Fixed hardcoded catalog/temp/ in output string for splitting files
------------------------------------
2.5
DJZeon found a bug where product URL was getting lost because I always deleted and inserted the product description info - fixed
Same bug also was causing times viewed to be reset to zero because I always deleted and inserted the product descriiption.
Added the multi-image lines from Debbie and Nickie - Thanks!
Changed the output file name to make more sense, now it looks like EP2003Mar20-09:45.txt
------------------------------------
2.51
No code changes, bump version because I forgot to update the docs about the high-to-low category order
------------------------------------
2.53
Bug fixes?
------------------------------------
2.60
Fix froogle categories in reverse order bug
Comment out mimage lines that were causing problems for people in 2.53
Added separator configuration variable so you can pick the separator character to use.
Made Froogle download look for an applicable specials price
Froogle downloads have "froogle" at the start of the file name
You can now specify a file in the temp directory and it will upload that instead of uploading via the browser
------------------------------------
2.61-MS2
Bug fixes thanks to frozenlightning.com
Replaced array_merge with array_merge to bring up to MS2 Standards.
Modified by Deborah Carney, inspired by the Think Tank to be included in the CRE Loaded 6
New support will be found at http://phesis.co.uk in the forums, as well as at forums.oscommerce.com in the Contributions section.  This script was/is written by volunteers, please don't email or PM them, more answers are available in the forums if you search.  If you want EP to do something, someone else probably already asked....
Known issue:  html in the product description gets messed up, not sure how to fix it.  

2.62-MS2
Modified by Karlheinz Meier on 08.August.2003
As some people happen to have something like DIFFERENT_Prefix_TABLES in their databases,
normally there is a file called /admin/includes/database_tables.php
which defines nice varibales for them; you can for example make prefixed_databases, or live backups or so.
As some programmers did use DIRECT table names, this was changed.
And now if you use the "separte price..."-module and have an article where there is NO special price,
the import process won't die

Derived from the Excel Import 1.51 by:

ukrainianshop.net

  Copyright (c) 2002-2003 Tim Wasson
  Released under the GNU General Public License
*/
//*******************************
//*******************************
// E N D
// E D I T   H I S T O R Y
//*******************************
//*******************************

//
//*******************************
//*******************************
// C O N F I G U R A T I O N
// V A R I A B L E S
//*******************************
//*******************************

// **** Temp directory ****
// if you changed your directory structure from stock and do not have /catalog/temp/, then you'll need to change this accordingly.
//
//$tempdir = "catalog/temp/";
//$tempdir2 = "/catalog/temp/";

//BOF: Kevin Added: For root directory
//live sertver


$tempdir = "/temp/";  
$tempdir2 = "temp/";    


//local server 
/*    
$tempdir = "/samszone/temp/"; 
$tempdir2 = "samszone/temp/";   
  */
//EOF: Kevin Added: For root directory

//**** File Splitting Configuration ****
// we attempt to set the timeout limit longer for this script to avoid having to split the files
// NOTE:  If your server is running in safe mode, this setting cannot override the timeout set in php.ini
// uncomment this if you are not on a safe mode server and you are getting timeouts
//set_time_limit(330);

// if you are splitting files, this will set the maximum number of records to put in each file.
// if you set your php.ini to a long time, you can make this number bigger
global $maxrecs;
$maxrecs = 3000; // default, seems to work for most people.  Reduce if you hit timeouts
//$maxrecs = 4; // for testing

//**** Image Defaulting ****
global $default_images, $default_image_manufacturer, $default_image_product, $default_image_category;

// set them to your own default "We don't have any picture" gif
//$default_image_manufacturer = 'no_image_manufacturer.gif';
//$default_image_product = 'no_image_product.gif';
//$default_image_category = 'no_image_category.gif';

// or let them get set to nothing
$default_image_manufacturer = '';
$default_image_product = '';
$default_image_category = '';

//**** Status Field Setting ****
// Set the v_status field to "Inactive" if you want the status=0 in the system
// Set the v_status field to "Delete" if you want to remove the item from the system <- THIS IS NOT WORKING YET!
// If zero_qty_inactive is true, then items with zero qty will automatically be inactive in the store.
global $active, $inactive, $zero_qty_inactive, $deleteit;
$active = 'Active';
$inactive = 'Inactive';
//$deleteit = 'Delete'; // not functional yet
$zero_qty_inactive = true;

//**** Size of products_model in products table ****
// set this to the size of your model number field in the db.  We check to make sure all models are no longer than this value.
// this prevents the database from getting fubared.  Just making this number bigger won't help your database!  They must match!
global $modelsize;
$modelsize = 15;

//**** Price includes tax? ****
// Set the v_price_with_tax to
// 0 if you want the price without the tax included
// 1 if you want the price to be defined for import & export including tax.
global $price_with_tax;
$price_with_tax =false;

// **** Quote -> Escape character conversion ****
// If you have extensive html in your descriptions and it's getting mangled on upload, turn this off
// set to 1 = replace quotes with escape characters
// set to 0 = no quote replacement
global $replace_quotes;
$replace_quotes = true;

// **** Field Separator ****
// change this if you can't use the default of tabs
// Tab is the default, comma and semicolon are commonly supported by various progs
// Remember, if your descriptions contain this character, you will confuse EP!
global $separator;
$separator = "\t"; // tab is default
//$separator = "|"; // comma
//$separator = ";"; // semi-colon
//$separator = "~"; // tilde
//$separator = "-"; // dash
//$separator = "*"; // splat

// **** Max Category Levels ****
// change this if you need more or fewer categories
global $max_categories;
$max_categories = 3; // 7 is default




// ****************************************
// Froogle configuration variables
// -- YOU MUST CONFIGURE THIS!  IT WON'T WORK OUT OF THE BOX!
// ****************************************

// **** Froogle product info page path ****
// We can't use the tep functions to create the link, because the links will point to the admin, since that's where we're at.
// So put the entire path to your product_info.php page here
global $froogle_product_info_path;
$froogle_product_info_path = "http://www.your-domain/catalog/product_info.php";

// **** Froogle product image path ****
// Set this to the path to your images directory
global $froogle_image_path;
$froogle_image_path = "http://www.your-domain/catalog/images/";

// **** Froogle - search engine friendly setting
// if your store has SEARCH ENGINE FRIENDLY URLS set, then turn this to true
// I did it this way because I'm having trouble with the code seeing the constants
// that are defined in other places.
global $froogle_SEF_urls;
$froogle_SEF_urls = true;


// ****************************************
// End Froogle configuration variables
// ****************************************

//*******************************
//*******************************
// E N D
// C O N F I G U R A T I O N
// V A R I A B L E S
//*******************************
//*******************************


//*******************************
//*******************************
// S T A R T
// INITIALIZATION
//*******************************
//*******************************


require('includes/application_top.php');
require('includes/database_tables.php');
define('CURRENT_TIMESTAMP',date('Y-m-d H:m:s'));

     
//*******************************
// If you are running a pre-Nov1-2002 snapshot of OSC, then we need this include line to avoid
// errors like:
//   undefined function tep_get_uploaded_file
 if (!function_exists(tep_get_uploaded_file)){
	include ('easypopulate_functions.php');
 }
//*******************************

global $filelayout, $filelayout_count, $filelayout_sql, $langcode, $fileheaders;

// these are the fields that will be defaulted to the current values in the database if they are not found in the incoming file
global $default_these;
$default_these = array(
	'v_products_image',
	//'v_products_mimage',	
	'v_products_bimage',
	'v_products_subimage1',
	'v_products_bsubimage1',
	'v_products_subimage2',
	'v_products_bsubimage2',
	'v_products_subimage3',
	'v_products_bsubimage3',
	'v_products_subimage4',
	'v_products_bsubimage4',
	'v_products_subimage5',
	'v_products_bsubimage5',
	'v_products_subimage6',
	'v_products_bsubimage6',
	'v_categories_id',
	'v_products_price',
	//BOF: Kevin Added: product_list_price
	'v_products_price_list',
	//BOF: Kevin Added: product_list_price	
	'v_products_quantity',
	'v_products_weight',
	'v_date_avail',
	'v_instock',
	'v_tax_class_title',
	'v_manufacturers_name',
	'v_manufacturers_id',
	'v_products_dim_type',
	'v_products_length',
	'v_products_width',
	'v_products_height'
	);

//elari check default language_id from configuration table DEFAULT_LANGUAGE
$epdlanguage_query = tep_db_query("select languages_id, name from " . TABLE_LANGUAGES . " where code = '" . DEFAULT_LANGUAGE . "'");
if (tep_db_num_rows($epdlanguage_query)) {
	$epdlanguage = tep_db_fetch_array($epdlanguage_query);
	$epdlanguage_id   = $epdlanguage['languages_id'];
	$epdlanguage_name = $epdlanguage['name'];
} else {
	Echo 'Strange but there is no default language to work... That may not happen, just in case... ';
}

$langcode = ep_get_languages();

if ( $dltype != '' ){
	// if dltype is set, then create the filelayout.  Otherwise it gets read from the uploaded file
	ep_create_filelayout($dltype); // get the right filelayout for this download
}
//*******************************
//*******************************
// E N D
// INITIALIZATION
//*******************************
//*******************************


if ( $download == 'stream' or  $download == 'tempfile' ){
	//*******************************
	//*******************************
	// DOWNLOAD FILE
	//*******************************
	//*******************************
	$filestring = ""; // this holds the csv file we want to download
	
	if ( $dltype=='froogle' ){
		// set the things froogle wants at the top of the file
		$filestring .= "# html_escaped=YES\n";
		$filestring .= "# updates_only=NO\n";
		$filestring .= "# product_type=OTHER\n";
		$filestring .= "# quoted=YES\n";
	}
	
	/*
	if($dltype == "deals"){
	$filelayout_sql = $filelayout_sql." union ";

	$finddatatemp = tep_db_query("SELECT MAX(deals_id)+1 as deals_id,' ',' ',' ',' ', ' ',' ' FROM ".TABLE_STORES_DEALS."");
		
	if ($rslastdeal = tep_db_fetch_array($finddatatemp)) {
		$lastdeal = $rslastdeal['deals_id'];
	}

	$lastdeal= "(please start with $lastdeal)";
	$filelayout_sql = $filelayout_sql."SELECT \"$lastdeal\",' ',' ',' ',' ', ' ',' '";
	
	}
	
	if($dltype == "coupon"){
	$filelayout_sql = $filelayout_sql." union ";
	
	$finddatatemp = tep_db_query("SELECT MAX(coupon_id)+1 as coupon_id,' ',' ',' ',' ', ' ',' ',' ',' ',' ',' ' FROM ".TABLE_STORES_COUPONS."");
		
	if ($rslastdeal = tep_db_fetch_array($finddatatemp)) {
		$lastcoupon = $rslastdeal['coupon_id'];
	}

	$lastcoupon = "(please start with $lastcoupon)";

	$filelayout_sql = $filelayout_sql."SELECT \"$lastcoupon\",' ',' ',' ',' ', ' ',' ',' ',' ',' ',' '";
	
	}
	*/
	
	if($dltype == "rebates_transactions"){
	$filelayout_sql = $filelayout_sql." union ";

	 $customer_transaction_query_row = "select count(*) as ct from " . TABLE_REBATES_TRANSACTION . " where transaction_id like 'L%'";
	
				
	$finddatatemp = tep_db_query($customer_transaction_query_row);
		
	if ($rslastdeal = tep_db_fetch_array($finddatatemp)) {
		$lastdeal = (int)$rslastdeal['ct']+1;
	}

	$lastdeal= "(please start transaction id with $lastdeal)";
	$filelayout_sql = $filelayout_sql."SELECT '',\"$lastdeal\",' ',' ',' ', ' ',' ',' ',' ',' ',' ',' ' FROM ".TABLE_REBATES_TRANSACTION."";

	}
	
	
	if($dltype == "deals"){
	$filelayout_sql = $filelayout_sql." union ";

	$finddatatemp = tep_db_query("SELECT MAX(deals_id)+1 as dealsid,' ',' ',' ',' ', ' ',' ',' ' FROM ".TABLE_STORES_DEALS."");
		
	if ($rslastdeal = tep_db_fetch_array($finddatatemp)) {
		$lastdeal = $rslastdeal['dealsid'];
	}

	$lastdeal= "(please start deals id with $lastdeal)";
	$filelayout_sql = $filelayout_sql."SELECT MAX(deals_id)+1,\"$lastdeal\",' ',' ',' ', ' ',' ',' ' FROM ".TABLE_STORES_DEALS."";

	
	}
	
	if($dltype == "coupon"){
	$filelayout_sql = $filelayout_sql." union ";
	
	$finddatatemp = tep_db_query("SELECT MAX(coupon_id)+1 as couponid,' ',' ',' ',' ', ' ',' ',' ',' ',' ',' ',' ',' ' FROM ".TABLE_STORES_COUPONS."");
		
	if ($rslastdeal = tep_db_fetch_array($finddatatemp)) {
		$lastcoupon=$rslastdeal['couponid'];
	}

	$lastcoupon="(please start coupon id with $lastcoupon)";

	$filelayout_sql = $filelayout_sql."SELECT MAX(coupon_id)+1,\"$lastcoupon\",' ',' ',' ', ' ',' ',' ',' ',' ',' ',' ',' ' FROM ".TABLE_STORES_COUPONS."";
	
	}

	
	$result = tep_db_query($filelayout_sql);
	$row =  tep_db_fetch_array($result);
	
	

	// Here we need to allow for the mapping of internal field names to external field names
	// default to all headers named like the internal ones
	// the field mapping array only needs to cover those fields that need to have their name changed
	if ( count($fileheaders) != 0 ){
		//$filelayout_header = $fileheaders; // if they gave us fileheaders for the dl, then use them
		$filelayout_header = $filelayout; 	
	} else {
		$filelayout_header = $filelayout; // if no mapping was spec'd use the internal field names for header names
	}
	
	//We prepare the table heading with layout values
	foreach( $filelayout_header as $key => $value ){
		//echo  "$key => $value";
		$filestring .= $key . $separator;
	}
	
	// now lop off the trailing tab
	$filestring = substr($filestring, 0, strlen($filestring)-1);

	// set the type
	if ( $dltype == 'froogle' ){
		$endofrow = "\n";
	} else {
		// default to normal end of row
		$endofrow = $separator . 'EOREOR' . "\n";
	}
	$filestring .= $endofrow;

	$num_of_langs = count($langcode);
	while ($row){
	
		
		
		if((!(isset($_GET['cPath'])) && ($dltype == "category") || $_GET['cPath'] == '') ){
		
		//find parent cat and sub
//echo tep_db_prepare_input($row['categories_name']);
//echo tep_db_input($row['categories_name']);
		
		$row['categories_name'] = str_replace("\'","'",$row['categories_name']);
		$finddatacheck = tep_db_query("select categories_id from " . TABLE_CATEGORIES_DESCRIPTION . "  where categories_name = '".tep_db_input($row['categories_name'])."'");
		
		if ($checkparent = tep_db_fetch_array($finddatacheck)) {
				$check_categories_id = $checkparent['categories_id'];
		}
				
		//find parent id name
		
		
		
		$findparentcheck = tep_db_query("select parent_id from " . TABLE_CATEGORIES . "  where categories_id = '".$check_categories_id."'");
		
		if ($c_parent = tep_db_fetch_array($findparentcheck)) {
		$check_categories_id = (int)$c_parent['parent_id'];
			if ($check_categories_id == 0){
				$row['parent_categories_name'] = '';	
			}else{
					//find parent name start //////////
			    	$findparentcheck = tep_db_query('select cd.categories_name from ' . TABLE_CATEGORIES . ' AS c,' . TABLE_CATEGORIES_DESCRIPTION . ' AS cd where c.parent_id = cd.categories_id and c.parent_id = '.$check_categories_id.'');
					if ($finalparent = tep_db_fetch_array($findparentcheck)) {
						$row['parent_categories_name'] =  $finalparent['categories_name'];
					}
					//find parent name end ////////////
			}
		}
		
		}
			
		
		//////scs added:  find parent and sub category name BOF///////
		if(isset($_GET['cPath']) && ($dltype == "category" || $dltype == "products" ) && $_GET['cPath'] != '' ){
		$path = explode("_",$_GET['cPath']);
			if ( $path[1] == '' ){
				$finddata = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . "  where categories_id = '".(int)$path[0]."'");
				if ($parent = tep_db_fetch_array($finddata)) {
					$row['parent_categories_name'] =  $parent['categories_name'];
					$parent_cat_name = $parent['categories_name'];
				}
			}else{
			
				$finddata = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . "  where categories_id = '".(int)$path[0]."'");
				if ($parent = tep_db_fetch_array($finddata)) {
					$row['parent_categories_name'] =  $parent['categories_name'];
					$parent_cat_name = $parent['categories_name'];
				}
				
				$findsub = tep_db_query("select categories_name from " . TABLE_CATEGORIES_DESCRIPTION . "  where categories_id = '".(int)$path[1]."'");
				if ($sub = tep_db_fetch_array($findsub)) {
					$sub_cat_name = $sub['categories_name'];
				}
			
			
			}
		
		}
		
	
		//////scs added:  find parent category name of product BOF///////
		
		if( ($row['manufacturer'] == '' ) && $dltype == "products" ){
		//$row['manufacturer']  = "None";
		}
		
		if($dltype == "coupon"){
			if($row['coupon_type'] == 1 ){
			$row['coupon_type'] = 'Free Shipping';
			}else if($row['coupon_type'] == 2) {
			$row['coupon_type'] = 'Clearance Sales';
			}		
		}
		
		//////scs added:  find stores data BOF///////
		if((isset($_GET['storeid']) || isset($_GET['stores_id'])) && ($dltype == "coupon" || $dltype == "deals" || $dltype =="productstostores" )) {
		 		
				if(isset($_GET['storeid'])){
				$getstoresid = $_GET['storeid'];
				}else{
				$getstoresid = $_GET['stores_id'];
				}
		 		
		 		$findstore = tep_db_query("select stores_name from " . TABLE_STORES . "  where stores_id = '".(int)$getstoresid."'");
				if ($rs = tep_db_fetch_array($findstore)) {
					$rs_stores_name = tep_db_prepare_input($rs['stores_name']);
				}
		}
		//////scs added:  find stores data EOF///////
		
		//////scs added:  find parent category name of product BOF///////
		if($dltype == "coupon" || $dltype == "deals" || $dltype =="productstostores" ){
		
		$row['stores_name'] = strip_tags(str_replace("\'","'",$row['stores_name']));
		}
		 
		if($dltype =="productstostores" || $dltype =="products"){
		$row['products_head_title_tag'] = strip_tags(str_replace("\'","'",$row['products_head_title_tag']));
		$row['products_head_desc_tag'] = strip_tags(str_replace("\'","'",$row['products_head_desc_tag']));
		$row['products_head_keywords_tag'] = strip_tags(str_replace("\'","'",$row['products_head_keywords_tag']));
		}
		
		if( $dltype == "store" ){
		$row['v_stores_name']  = strip_tags(str_replace("\'","'",$row['v_stores_name']));
		$row['v_stores_description']  = strip_tags(str_replace("\'","'",$row['v_stores_description']));
		$row['v_stores_short_description']  = strip_tags(str_replace("\'","'",$row['v_stores_short_description']));
		
		}
		
		//////scs added:  find stores data BOF///////
		
		
		
		
		// if the filelayout says we need a products_name, get it
		// build the long full froogle image path
		$row['v_products_fullpath_image'] = $froogle_image_path . $row['v_products_image'];
		// Other froogle defaults go here for now
		$row['v_froogle_instock'] 		= 'Y';
		$row['v_froogle_shipping'] 		= '';
		$row['v_froogle_upc'] 			= '';
		$row['v_froogle_color']			= '';
		$row['v_froogle_size']			= '';
		$row['v_froogle_quantitylevel']		= '';
		$row['v_froogle_manufacturer_id']	= '';
		$row['v_froogle_exp_date']		= '';
		$row['v_froogle_product_type']		= 'OTHER';
		$row['v_froogle_delete']		= '';
		$row['v_froogle_currency']		= 'USD';
		$row['v_froogle_offer_id']		= $row['v_products_model'];
		$row['v_froogle_product_id']		= $row['v_products_model'];

		// names and descriptions require that we loop thru all languages that are turned on in the store
		foreach ($langcode as $key => $lang){
			$lid = $lang['id'];

			// for each language, get the description and set the vals
			$sql2 = "SELECT *
				FROM ".TABLE_PRODUCTS_DESCRIPTION."
				WHERE
					products_id = '" . $row['v_products_id'] . "' AND
					language_id = '" . $lid . "'";
			$result2 = tep_db_query($sql2);
			$row2 =  tep_db_fetch_array($result2);

			// I'm only doing this for the first language, since right now froogle is US only.. Fix later!
			// adding url for froogle, but it should be available no matter what
			if ($froogle_SEF_urls){
				// if only one language
				if ($num_of_langs == 1){
					$row['v_froogle_products_url_' . $lid] = $froogle_product_info_path . '/products_id/' . $row['v_products_id'];
				} else {
					$row['v_froogle_products_url_' . $lid] = $froogle_product_info_path . '/products_id/' . $row['v_products_id'] . '/language/' . $lid;
				}
			} else {
				if ($num_of_langs == 1){
					$row['v_froogle_products_url_' . $lid] = $froogle_product_info_path . '?products_id=' . $row['v_products_id'];
				} else {
					$row['v_froogle_products_url_' . $lid] = $froogle_product_info_path . '?products_id=' . $row['v_products_id'] . '&language=' . $lid;
				}
			}

			$row['v_products_name_' . $lid] 	= $row2['products_name'];
			$row['v_products_description_' . $lid] 	= $row2['products_description'];
			$row['v_products_url_' . $lid] 		= $row2['products_url'];

			// froogle advanced format needs the quotes around the name and desc
			$row['v_froogle_products_name_' . $lid] = '"' . strip_tags(str_replace('"','""',$row2['products_name'])) . '"';
			$row['v_froogle_products_description_' . $lid] = '"' . strip_tags(str_replace('"','""',$row2['products_description'])) . '"';

			// support for Linda's Header Controller 2.0 here
			if(isset($filelayout['v_products_head_title_tag_' . $lid])){
			
				$row['v_products_head_title_tag_' . $lid] 	= $row2['products_head_title_tag'];
				$row['v_products_head_desc_tag_' . $lid] 	= $row2['products_head_desc_tag'];
				$row['v_products_head_keywords_tag_' . $lid] 	= $row2['products_head_keywords_tag'];
				
			}
			// end support for Header Controller 2.0
		}

		// for the categories, we need to keep looping until we find the root category

		// start with v_categories_id
		// Get the category description
		// set the appropriate variable name
		// if parent_id is not null, then follow it up.
		// we'll populate an aray first, then decide where it goes in the
		$thecategory_id = $row['v_categories_id'];
		$fullcategory = ''; // this will have the entire category stack for froogle
		for( $categorylevel=1; $categorylevel<$max_categories+1; $categorylevel++){
			if ($thecategory_id){
				$sql2 = "SELECT categories_name
					FROM ".TABLE_CATEGORIES_DESCRIPTION."
					WHERE
						categories_id = " . $thecategory_id . " AND
						language_id = " . $epdlanguage_id ;

				$result2 = tep_db_query($sql2);
				$row2 =  tep_db_fetch_array($result2);
				// only set it if we found something
				$temprow['categories_name_' . $categorylevel] = $row2['categories_name'];
				// now get the parent ID if there was one
				$sql3 = "SELECT parent_id
					FROM ".TABLE_CATEGORIES."
					WHERE
						categories_id = " . $thecategory_id;
				$result3 = tep_db_query($sql3);
				$row3 =  tep_db_fetch_array($result3);
				$theparent_id = $row3['parent_id'];
				if ($theparent_id != ''){
					// there was a parent ID, lets set thecategoryid to get the next level
					$thecategory_id = $theparent_id;
				} else {
					// we have found the top level category for this item,
					$thecategory_id = false;
				}
				//$fullcategory .= " > " . $row2['categories_name'];
				$fullcategory = $row2['categories_name'] . " > " . $fullcategory;
			} else {
				$temprow['categories_name_' . $categorylevel] = '';
			}
		}
		// now trim off the last ">" from the category stack
		$row['v_category_fullpath'] = substr($fullcategory,0,strlen($fullcategory)-3);

		// temprow has the old style low to high level categories.
		$newlevel = 1;
		// let's turn them into high to low level categories
		for( $categorylevel=6; $categorylevel>0; $categorylevel--){
			if ($temprow['categories_name_' . $categorylevel] != ''){
				$row['categories_name_' . $newlevel++] = $temprow['categories_name_' . $categorylevel];
			}
		}
		// if the filelayout says we need a manufacturers name, get it
		if (isset($filelayout['v_manufacturers_name'])){
			if ($row['v_manufacturers_id'] != ''){
				$sql2 = "SELECT manufacturers_name
					FROM ".TABLE_MANUFACTURERS."
					WHERE
					manufacturers_id = " . $row['v_manufacturers_id']
					;
				$result2 = tep_db_query($sql2);
				$row2 =  tep_db_fetch_array($result2);
				$row['v_manufacturers_name'] = $row2['manufacturers_name'];
			}
		}


		// If you have other modules that need to be available, put them here


		// this is for the separate price per customer module
		if (isset($filelayout['v_customer_price_1'])){
			$sql2 = "SELECT
					customers_group_price,
					customers_group_id
				FROM
					".TABLE_PRODUCTS_GROUPS."
				WHERE
				products_id = " . $row['v_products_id'] . "
				ORDER BY
				customers_group_id"
				;
			$result2 = tep_db_query($sql2);
			$ll = 1;
			$row2 =  tep_db_fetch_array($result2);
			while( $row2 ){
				$row['v_customer_group_id_' . $ll] 	= $row2['customers_group_id'];
				$row['v_customer_price_' . $ll] 	= $row2['customers_group_price'];
				$row2 = tep_db_fetch_array($result2);
				$ll++;
			}
		}
		if ($dltype == 'froogle'){
			// For froogle, we check the specials prices for any applicable specials, and use that price
			// by grabbing the specials id descending, we always get the most recently added special price
			// I'm checking status because I think you can turn off specials
			$sql2 = "SELECT
					specials_new_products_price
				FROM
					".TABLE_SPECIALS."
				WHERE
				products_id = " . $row['v_products_id'] . " and
				status = 1 and
				expires_date < CURRENT_TIMESTAMP
				ORDER BY
					specials_id DESC"
				;
			$result2 = tep_db_query($sql2);
			$ll = 1;
			$row2 =  tep_db_fetch_array($result2);
			if( $row2 ){
				// reset the products price to our special price if there is one for this product
				$row['v_products_price'] 	= $row2['specials_new_products_price'];
			}
		}

		//elari -
		//We check the value of tax class and title instead of the id
		//Then we add the tax to price if $price_with_tax is set to 1
		$row_tax_multiplier 		= tep_get_tax_class_rate($row['v_tax_class_id']);
		$row['v_tax_class_title'] 	= tep_get_tax_class_title($row['v_tax_class_id']);
		$row['v_products_price'] 	= $row['v_products_price'] +
							($price_with_tax * round($row['v_products_price'] * $row_tax_multiplier / 100,2));

		/*
		// Now set the status to a word the user specd in the config vars
		if ( $row['v_status'] == '1' ){
			$row['v_status'] = $active;
		} else {
			$row['v_status'] = $inactive;
		}
		*/
		
	
		
		// remove any bad things in the texts that could confuse EasyPopulate
		$therow = '';
		foreach( $filelayout as $key => $value ){
			//echo "The field was $key<br>";

			$thetext = $row[$key];
			// kill the carriage returns and tabs in the descriptions, they're killing me!
			$thetext = str_replace("\r",' ',$thetext);
			$thetext = str_replace("\n",' ',$thetext);
			$thetext = str_replace("\t",' ',$thetext);
			// and put the text into the output separated by tabs
			$therow .= $thetext . $separator;
		}

		// lop off the trailing tab, then append the end of row indicator
		$therow = substr($therow,0,strlen($therow)-1) . $endofrow;

		$filestring .= $therow;
		// grab the next row from the db
		$row =  tep_db_fetch_array($result);
		
		
	}
	
	
	#$EXPORT_NAME=time();
	$EXPORT_NAME = strftime('%Y%b%d-%H%I');
	if ($dltype=="froogle"){
		$EXPORT_NAME = "FroogleEP" . $EXPORT_NAME;
	} else {
		$EXPORT_NAME = "EP" . $EXPORT_NAME;
	}
	//// scs ////////
		
	//////// change file name accourding to request//////////
	if($dltype == 'store'){
	$EXPORT_NAME = "store";
	}else if ($dltype == 'coupon'){
		if($rs_stores_name != '') {
		$EXPORT_NAME = "store_".$rs_stores_name."_coupons";
		}else {
		$EXPORT_NAME = "coupons";
		}
	}else if ($dltype == 'deals'){
			if($rs_stores_name != '') {
			$EXPORT_NAME = "store_".$rs_stores_name."_deals";
			}else {
			$EXPORT_NAME = "deals";
			}
	}else if ($dltype == 'category'){				
			if( isset($_GET['cPath'])  && $parent_cat_name != '')
			{
			$EXPORT_NAME = "category"."_".$parent_cat_name;
			}else{
			$EXPORT_NAME = "category";
			}
	}else if ($dltype == 'products'){ 	
			if(isset($_GET['cPath']) && $parent_cat_name != '')
			{
					//find sub cat end
					if($sub_cat_name != '' && $parent_cat_name != '') {
					$EXPORT_NAME = "products"."_".$parent_cat_name."_".$sub_cat_name;
					}else {
					$EXPORT_NAME = "products"."_".$parent_cat_name;					
					}
					
			}else{	
			$EXPORT_NAME = "products";
			}
	}else if ($dltype == 'productstostores'){
		if($rs_stores_name != ''){
			$EXPORT_NAME = "store_".$rs_stores_name."_products";			
		}else {
			$EXPORT_NAME = "stores_products";
		}
	}else if ($dltype == 'rebates_transactions'){
		if($_GET['customers_id'] != ''){
			$customers_id = $_GET['customers_id'];
			$EXPORT_NAME = $customers_id."_transactions";			
		}else {
			$EXPORT_NAME = "transactions";
	}
	
	
	}


	///////// End of file name assignment //////////
	////scs////////	
	// now either stream it to them or put it in the temp directory
	if ($download == 'stream'){
		//*******************************
		// STREAM FILE
		//*******************************		
		header("Content-type: application/vnd.ms-excel; charset=big5");
		header("Content-disposition: attachment; filename=$EXPORT_NAME.txt");
		header("Pragma: no-cache");
		header("Expires: 0");
	
		echo $filestring;
		die();
	} else {
		//*******************************
		// PUT FILE IN TEMP DIR
		//*******************************
		$tmpfname = DIR_FS_DOCUMENT_ROOT . $tempdir . "$EXPORT_NAME.txt";
		//unlink($tmpfname);
		$fp = fopen( $tmpfname, "w+");
		fwrite($fp, $filestring);
		fclose($fp);
		echo "You can get your file in the Tools/Files under " . $tempdir . $EXPORT_NAME . ".txt";
		die();
	}
}   // *** END *** download section
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php require(DIR_WS_INCLUDES . 'header.php');?>

<table border="0" width="100%" cellspacing="2" cellpadding="2">
<tr>
<td width="<?php echo BOX_WIDTH; ?>" valign="top" height="27">
<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require(DIR_WS_INCLUDES . 'column_left.php');?>
</table></td>
<td class="pageHeading" valign="top"><?php
//echo "Easy Populate $curver - Default Language : " . $epdlanguage_name . '(' . $epdlanguage_id .')' ;
?>

<p class="smallText">

<?php



if ($localfile or (is_uploaded_file($usrfl) && $split==0)) {

	//*******************************
	//*******************************
	// UPLOAD AND INSERT FILE
	//*******************************
	//*******************************

	if ($usrfl){
		// move the file to where we can work with it
		$file = tep_get_uploaded_file('usrfl');
		if (is_uploaded_file($file['tmp_name'])) {
			tep_copy_uploaded_file($file, DIR_FS_DOCUMENT_ROOT . $tempdir);
		}

		echo "<p class=smallText>";
		echo "File uploaded. <br>";
		echo "Temporary filename: " . $usrfl . "<br>";
		echo "User filename: " . $usrfl_name . "<br>";
		echo "Size: " . $usrfl_size . "<br>";

		// get the entire file into an array
		$readed = file(DIR_FS_DOCUMENT_ROOT . $tempdir . $usrfl_name);
		
		// now we string the entire thing together in case there were carriage returns in the data
	$newreaded = "";
	foreach ($readed as $read){
		$newreaded .= $read;
	}

	// now newreaded has the entire file together without the carriage returns.
	// if for some reason excel put qoutes around our EOREOR, remove them then split into rows
	$newreaded = str_replace('"EOREOR"', 'EOREOR', $newreaded);
	$readed = explode( $separator . 'EOREOR',$newreaded);


	// Now we'll populate the filelayout based on the header row.
	$theheaders_array = explode( $separator, $readed[0] ); // explode the first row, it will be our filelayout
	$lll = 0;
	$filelayout = array();
	$checkfile = "false";
	$checkarray = array();
	foreach( $theheaders_array as $key => $header ){
		$cleanheader = str_replace( '"', '', $header);
		 $filelayout[ $cleanheader ] = $lll++; //
	
		// scs added: check store fields BOF
		if($importdata == "uploadstore"){
			if( $key == 0 ){
						 if (trim($header) != "v_stores_name" ){
							 $checkfile = "true" ;
						 }
			}
		}
		// scs added: check store fields BOF
		// scs added: check store coupon fields EOF
		if($importdata == "uploadstorecoupone"){
			if( $key == 0 ){
					 if (trim($header) != "coupon_id" ){
						 $checkfile = "true" ;
					 }
			 }
		}
		// scs added: check store coupon fields EOF
		// scs added: check store deals fields BOF
		if($importdata == "uploadstoredeals"){
			 if( $key == 0 ){
					 if (trim($header) != "deals_id" ){
						 $checkfile = "true" ;
					 }
			 }
		}
		//scs added: check stores deals EOF
		
		// scs added: check category fields BOF
		if($importdata == "uploadcategory"){
		 if( $key == 0 ){
					 if (trim($header) != "categories_name" ){
						 $checkfile = "true" ;
					 }
			 }
		}
		// scs added: check category fields EOF
		
		// scs added: check products fields BOF
		if($importdata == "uploadproductcat"){
			 if( $key == 0 ){
					 if (trim($header) != "products_model" ){
						 $checkfile = "true" ;
					 }
			 }
			 if( $key == 2 ){
					 if (trim($header) != "products_image" ){
						 $checkfile = "true" ;
					 }
			 }
		}
		// scs added: check products fields EOF
		// scs added: check store products fields BOF
		if($importdata == "uploadproductstores" ){
		
		 if( $key == 0 ){
					 if (trim($header) != "products_model" ){
						 $checkfile = "true" ;
					 }
			 }
		 if( $key == 2 ){
					 if (trim($header) != "stores_name" ){
						 $checkfile = "true" ;
					 }
			 }
		}
		// scs added: check store products fields BOF
		
		// scs added: check cj fields BOF
		if($importdata == "storerebatesorder" ){
		
		 if( $key == 0 ){
					 if (trim($header) != "Date" ){
						 $checkfile = "true" ;
					 }
			 }
		 if( $key == 3 ){
					 if (trim($header) != "ID" ){
						 $checkfile = "true" ;
					 }
			 }
		 if( $key == 7 ){
					 if (trim($header) != "Sale_Amount" ){
						 $checkfile = "true" ;
					 }
			 }	 
		 if( $key == 14 ){
					 if (trim($header) != "SID" ){
						 $checkfile = "true" ;
					 }
			 }	 	 
			 
			 
		}
		// scs added: check cj fields BOF 
		
		
		
		// scs added: check linkshare fields BOF
		if($importdata == "storerebateslinkshare" ){
		
		 if( $key == 0 ){
					 if (trim($header) != "Member ID" ){
						 $checkfile = "true" ;
					 }
			 }
		 if( $key == 1 ){
					 if (trim($header) != "Merchant ID" ){
						 $checkfile = "true" ;
					 }
			 }
		 if( $key == 2 ){
					 if (trim($header) != "Merchant Name" ){
						 $checkfile = "true" ;
					 }
			 }	 
		 if( $key == 7 ){
					 if (trim($header) != "Sales($)" ){
						 $checkfile = "true" ;
					 }
			 }
		 if( $key == 9 ){
					 if (trim($header) != "Commissions($)" ){
						 $checkfile = "true" ;
					 }
			 }
	    if( $key == 12 ){
					 if (trim($header) != "Transaction ID" ){
						 $checkfile = "true" ;
					 }
		 }	 	 	 	 
			 
			 
		}
		// scs added: check linkshare fields BOF 
	
		
		// scs added: check store deals fields BOF
		if($importdata == "uploadtourcity"){
			 
			 if( $key == 0 ){
					 if (trim($header) != "ID" ){
						 $checkfile = "true" ;
					 }
			 }
			  if( $key == 2 ){
					 if (trim($header) != "CITY_CODE"){
						 $checkfile = "true" ;
					 }
			 }
			 
			 if( $key == 6 ){
					 if (trim($header) != "IS_ATTRACTIONS" ){
						 $checkfile = "true" ;
					 }
			 }
			 
		}
		//scs added: check stores deals EOF
		
		
	
	}
	
	
	
	if($checkfile == "true"){
	
	echo "</br><font color=red><b>Please check your uploaded file. Fields are dosen't match.</b></font>";
	echo "<br><b>Data Import False.</b>";
	echo "</br></br>&nbsp;&nbsp;<a href='javascript:history.go(-1)'><img src='includes/languages/english/images/buttons/button_back.gif' border='0' alt='Back' title=' Back '></a>";
			
	exit;
	
	}
	
	unset($readed[0]); //  we don't want to process the headers with the data
	
/////////////  BOF:  Import Tour City data  ////////////////////////
		if($importdata == "uploadtourcity") {

			foreach($readed as $key => $val)
			{
			
				$categories_id = '';
				//$manufacturers_id = '';
				$rawdata = explode($separator, $val);
				
				$rawdata = str_replace("\'","'",$rawdata);
			//	$rawdata = str_replace("\\'","\'",$rawdata);
				$rawdata = str_replace('"','',$rawdata);
				//$rawdata = str_replace(",",'',$rawdata);
				if(trim($rawdata[0]) != '' || trim($rawdata[0]) != 0){
				
				$city_id = trim($rawdata[0]);
				$city_code = trim($rawdata[2]);
				$is_attractions = trim($rawdata[6]);
				
				
				$sql = "select * from " . TABLE_TOUR_CITY. " where city_id ='".$city_id."'";	
				$rs = tep_db_query($sql);
				
				
				while($rsdata = tep_db_fetch_array($rs))
				{
				//	echo "update <br>";
					echo "</br>";
					echo "<b>Update</b><br>";
					echo "City ID:-".$city_id."</br>";							
								
															
					if($city_id != ''){	
						$sql_data_array_city = array(		   
											'city_code' => $city_code,
											'is_attractions' => $is_attractions
											);
							
						//echo 	$city_id.'-->'.$city_code.'-->'. $is_attractions.'<br>';
											
						tep_db_perform(TABLE_TOUR_CITY, $sql_data_array_city, 'update', "city_id = '" .$city_id. "'");
					}
				}	//end of while
				
							
			  }
			}
			echo "<br><b>Data Import Successfully.</b>";
			echo "</br></br><a href='javascript:history.go(-1)'><img src='includes/languages/english/images/buttons/button_back.gif' border='0' alt='Back' title=' Back '></a>";
			exit;
			
			
			
			}
////////////EOF: SCS Added:  Import BOF:  Import Tour City data  /////////////////////
	
	
	////////BOF Import commision junction order data 
	
	if($importdata == "storerebatesorder") { 
		
		
			foreach($readed as $key => $val)
			{
				$rawdata = explode($separator, $val);
				$rawdata = str_replace('"','',$rawdata);		
				if(trim($rawdata[0]) != '' || trim($rawdata[0]) != 0){
				
				$event_date = tep_db_input(trim($rawdata[1])); 
			    $posting_date = tep_db_input(trim($rawdata[0]));   
				$transaction_id =  "C".trim($rawdata[3]); 
				//$corrected = str_replace('-','',trim($rawdata[6]));
				$corrected = number_format(trim($rawdata[6]),2,'.','');  
				$sale_amount = number_format(trim($rawdata[7]),2,'.','');  
				$commission = number_format(trim($rawdata[8]),2,'.','');						
				$advertiser_id  = "C".trim($rawdata[12]); 
				$advertiser_name = trim($rawdata[13]);
				$customers_id = (int)trim($rawdata[14]);
				$order_id = "C".trim($rawdata[15]);
				$associated_transaction_id  = trim($rawdata[18]); 
				$rebate_status = 'Pending';
				//assign type
				  //find  original transaction id form order id  bof
					$original_transaction_id = tep_get_original_transaction_id(trim($order_id));
					$original_transaciton_balance =  tep_get_rebates_amount_transaction_id($original_transaction_id);
					
				 //find original transaction id from order id  eof

				 if($corrected < 0 ) {
				   $transaction_type = 2;					
				  
					$rebate_amount =  $original_transaciton_balance - (str_replace('-','',($sale_amount*tep_get_rebate_balance_adveriser_id_persntage($advertiser_id))));
					
				      			  				   
				 }else{
				 //$associated_transaction_id  = trim($rawdata[18]); 
				  $transaction_type = 1; 
				  $rebate_amount =  number_format(($sale_amount*tep_get_rebate_balance_adveriser_id_persntage($advertiser_id)),2,'.','');				   
				 }
				
				/*				
				$stores_rebates = seo_get_stores_rebates_percent($advertiser_id);
				if( $stores_rebates > 0 ){ 		
				$stores_rebates_per = ($stores_rebates/100);  				
				$rebate_amount = number_format(($sale_amount * $stores_rebates_per),2,'.','');
				}else{
				*/
				
				/*
				}
				*/
				
			// date convert in mysql formate start	
				$event_date_array = explode(' ',$event_date); 
				$datestr = trim($event_date_array[0]);
				$event_date = date("Y-m-d", strtotime($datestr));
				
				$posting_date_array = explode(' ',$posting_date); 
				$postdatestr = trim($posting_date_array[0]);
				$posting_date = date("Y-m-d", strtotime($postdatestr));
				

			
			//date conver in mysql formate end
			
			
				$rs = tep_db_query("select * from " . TABLE_REBATES_TRANSACTION . " where transaction_id = '".$transaction_id."'");			
				

				if ($rsdata = tep_db_fetch_array($rs))
				{
									echo "<br><b>update </b><br>";		
									echo "</br>Transaction ID:-  " . $transaction_id."<br>";
									if($corrected < 0 ){
									//echo $corrected."<br>";
									//echo tep_get_currected_transaction_id($transaction_id);
									//exit;
									 if($corrected != tep_get_currected_transaction_id($transaction_id) ) {
									 //echo $original_transaciton_balance." + </br>";
									// echo (str_replace('-','',tep_get_rebate_balance_transaction_id($transaction_id)))." + </br>";
									// echo  number_format(($commission/2),2)."</br>";									 							 
									 $rebate_amount =  $original_transaciton_balance + (str_replace('-','',tep_get_rebate_balance_transaction_id($transaction_id))) - (str_replace('-','',($sale_amount*tep_get_rebate_balance_adveriser_id_persntage($advertiser_id))));
									 }else{
									 $rebate_amount = $original_transaciton_balance;
									 }
									 
									 }else{
									
									  	$rebate_amount = $original_transaciton_balance - tep_get_rebate_balance_transaction_id($transaction_id) + number_format(($sale_amount*tep_get_rebate_balance_adveriser_id_persntage($advertiser_id)),2,'.','');
									 }
									
													
									 $sql_data_array1 = array(
										 
												'advertiser_id' => $advertiser_id,
												
												'advertiser_name' => $advertiser_name,
												
												'customers_id ' => $customers_id,
												
												'sale_amount' => $sale_amount, 
												
												'corrected'  => $corrected,
												
												'commission' => $commission,
												
												'event_date' => $event_date,
												
												'posting_date' => $posting_date,
												
												'order_id' => $order_id,
												
												'transaction_type' => $transaction_type, 
												
												//'associated_transaction_id'=> $associated_transaction_id,   
																								
											  );							
									//update transaction start
											  tep_db_perform(TABLE_REBATES_TRANSACTION, $sql_data_array1, 'update', "transaction_id = '" .$transaction_id. "'");
									//update transaction start 				
									
										 if($corrected < 0) {
										// in case of return form customer start
											
											 $sql_rebate_history_array = array(
											 
													'rebate_amount' => $rebate_amount,
													
													'rebate_status' =>  $rebate_status, 
													
												  );  
											
											 tep_db_perform(TABLE_REBATES_HISTORY, $sql_rebate_history_array, 'update', "transaction_id = '" .$original_transaction_id. "'");		  
											
											// in case of return form customer end
											
										}else{
											//update rebate history start
																									
												 $sql_rebate_history_array = array(
											 
													'customers_id' => $customers_id,
													
													//'rebate_amount' => number_format(($commission/$stores_rebates_per),2),
													
													'rebate_amount' => number_format($rebate_amount,2,'.',''),
													
													'rebate_status' =>  $rebate_status,  
													
												  ); 
											tep_db_perform(TABLE_REBATES_HISTORY, $sql_rebate_history_array, 'update', "transaction_id = '" .$original_transaction_id. "'");		  
											//update rebate history end
										}
				}
				else 
				{
				
							
					echo "<br><b>Insert </b><br>"; 
					echo "Transaction id:- " . $transaction_id."</br><br>"; 
					
					
					//check for referal amout start 
						$referrals_email = tep_get_customers_email($customers_id);
						$check_customer_query = tep_db_query("select rri.customers_id from " . TABLE_REBATES_REFERRALS_INFO . " as rri, ".TABLE_CUSTOMERS." as c where rri.referrals_status ='1' and rri.referrals_email =  c.customers_email_address  and c.site_name ='Sams Rebates' and rri.referrals_email = '".$referrals_email."'");
    					if($customer_referrals = tep_db_fetch_array($check_customer_query)){
						$referred_cust_id = $customer_referrals['customers_id'];
							
							// insert free referral rebate transaction account start
							$sql_free_rebates_data_array = array( 'customers_id' => $referred_cust_id,
													'transaction_id' => 'SamsBonus_Referral_'.$customers_id,
													'advertiser_name' => 'Referral Bonus',
													'transaction_type' => '4',
													'event_date' => 'now()', 
													);
							  tep_db_perform(TABLE_REBATES_TRANSACTION, $sql_free_rebates_data_array);
							  $rebate_history_id  = tep_db_insert_id();
							 // insert free referral rebate transaction account end
							 // insert free referral rebate history account start
							 $sql_free_rebates_data_array = array( 'customers_id' => $referred_cust_id,
													'transaction_id' => 'SamsBonus_Referral_'.$customers_id, 
													'rebate_status' => 'Confirmed', 
													'rebate_amount' => SAMSREBATES_REFERAL_BONUS_REGISTRATION, 
													); 
							  tep_db_perform(TABLE_REBATES_HISTORY, $sql_free_rebates_data_array);
							  $rebate_history_id  = tep_db_insert_id();
							 // insert free rebate history account end
							 //update state one time paid start
							  $sql_update_rebates_info_status = array( 'referrals_status' => '2',
																	); 
							  tep_db_perform(TABLE_REBATES_REFERRALS_INFO, $sql_update_rebates_info_status, "update", "referrals_email= '".$referrals_email."' and customers_id = '".$referred_cust_id."'");
					
							 //update status one time paid end
						}
						//check for referal amoutn end
									
						 $sql_data_array1 = array(
						 
						 		'transaction_id' => $transaction_id,
						 
						 		'advertiser_id' => $advertiser_id,
								
								'advertiser_name' => $advertiser_name,
								
								'customers_id' => $customers_id,
								
								'sale_amount' => $sale_amount,
								
								'corrected'  => $corrected,
								
								'commission' => $commission,
								
								'event_date' => $event_date,
								
								'posting_date' => $posting_date,
								
								'order_id' => $order_id,
								
								'transaction_type' => $transaction_type, 
																								
								//'associated_transaction_id'=> $associated_transaction_id,   
							
		                      );							
					//inser a new transaction start
							  tep_db_perform(TABLE_REBATES_TRANSACTION, $sql_data_array1);
					//inser a new transaction start 				
					
							 if($corrected < 0) {
							 							 
							 
								// in case of return form customer start
								 $sql_rebate_history_array = array( 
								 
										'rebate_amount' => $rebate_amount,
										
										'rebate_status' =>  $rebate_status, 
										
									  ); 
								
									 tep_db_perform(TABLE_REBATES_HISTORY, $sql_rebate_history_array, 'update', "transaction_id = '" .$original_transaction_id. "'");		  
								
								// in case of return form customer end
								
								
							}else{
								//inset in rebate history start
														
									 $sql_rebate_history_array = array(
								 
										'transaction_id' => $transaction_id,
								 
										'customers_id' => $customers_id,
										
										//'rebate_amount' => number_format(($commission/$stores_rebates_per),2,'.',''), 
										
										'rebate_amount' => number_format($rebate_amount,2,'.',''),										
										
										'rebate_status' => 'Pending', 
										
									  );
								tep_db_perform(TABLE_REBATES_HISTORY, $sql_rebate_history_array);
								//inset in rebate history end
							}
					 		  				
				
				}
				
			}
			
			}
			echo "<br><b>Data Import Successfully.</b>";
			echo "</br></br><a href='javascript:history.go(-1)'><img src='includes/languages/english/images/buttons/button_back.gif' border='0' alt='Back' title=' Back '></a>";
			exit;
			
		 }
		////////////////EOF: SCS Added:  Import commision junction order Data  ///////////////////////////			
		

		
	////////BOF Import LinkShare order data  
	
	if($importdata == "storerebateslinkshare") { 
		
		
		
			foreach($readed as $key => $val)
			{
			
			
				$rawdata = explode($separator, $val);
				$rawdata = str_replace('"','',$rawdata);	
				if(trim($rawdata[0]) != '' || trim($rawdata[0]) != 0){				
				
				$order_id = "L".trim($rawdata[3]);							
				$advertiser_id  = "L".trim($rawdata[1]); 
				$advertiser_name = trim($rawdata[2]);
				$customers_id = trim($rawdata[0]);
				$sale_amount =  number_format(trim($rawdata[7]),2,'.','');  
				$commission =  number_format(trim($rawdata[9]),2,'.','');
				$event_date = tep_db_input(trim($rawdata[4])); 
				$event_date = date("Y-m-d", strtotime($event_date));
			    $posting_date = tep_db_input(trim($rawdata[10]));   
				$posting_date = date("Y-m-d", strtotime($posting_date));
				$posting_time = tep_db_input(trim($rawdata[11]));  
				$event_time = tep_db_input(trim($rawdata[5])); 
				$sku_number = tep_db_input(trim($rawdata[6])); 		
		    	$transaction_id = "L".trim($rawdata[12]);
				if($rawdata[13] != ''){
				$associated_transaction_id = "L".trim($rawdata[13]);
				}else{
				$associated_transaction_id = '';
				}
					
				/*
				if($sale_amount < 0 && 	$commission < 0){
				$associated_transaction_id = $advertiser_id.'_'.$order_id; 
				$transaction_id = "R_".$advertiser_id.'_'.$order_id; 
				//check to reupload agian start
				// $res_check_agian_transaction = tep_db_query("select  transaction_id  from " . TABLE_REBATES_TRANSACTION . " where posting_date = '".$posting_date."' and  sale_amount = '".$sale_amount."' and commission =  '".$commission."' and  order_id =  '".$order_id."' order by posting_date DESC");
				 $res_check_agian_transaction = tep_db_query("select  transaction_id  from " . TABLE_REBATES_TRANSACTION . " where posting_date = '".$posting_date."' and event_date= '".$event_date."' and  posting_time = '".$posting_time."' and event_time = '".$event_time."' and order_id =  '".$order_id."' order by posting_date DESC"); 
				 if($tran_array = tep_db_fetch_array($res_check_agian_transaction)) {   
				 
						$transaction_id = $tran_array['transaction_id']; 
						$updateflag = "uploadfalse";
				 }else{
				 
				 		//check for max return value 
						$res_check_agian_transaction_max = tep_db_query("select  transaction_id  from " . TABLE_REBATES_TRANSACTION . " where order_id = '".$order_id."' order by posting_date DESC"); 
				 		if($tran_id = tep_db_fetch_array($res_check_agian_transaction_max)) {  
						
						$transaction_id_array = explode('_',$tran_id['transaction_id']); 				 		
						
								if($transaction_id_array[0] == 'R'){
								$transaction_id = "R1_".$advertiser_id.'_'.$order_id; 
								}else if($transaction_id_array[0] == 'R1'){
								$transaction_id = "R2_".$advertiser_id.'_'.$order_id; 
								}else if($transaction_id_array[0] == 'R2'){
								$transaction_id = "R3_".$advertiser_id.'_'.$order_id; 
								}else if($transaction_id_array[0] == 'R3'){
								$transaction_id = "R4_".$advertiser_id.'_'.$order_id; 
								}
						
						}						
						//check for max return value end 				 
				 }				
				//check for reupload again end
				
				}else{
				$transaction_id = $advertiser_id.'_'.$order_id; 
				}
				*/
												
						 
				    //find  original transaction id form order id  bof
					
					if($associated_transaction_id != ''){
					$original_transaction_id = $associated_transaction_id;
					}else{
					$original_transaction_id = $transaction_id;
					}
					
					
					$original_transaciton_balance =  tep_get_rebates_amount_transaction_id($original_transaction_id);
					
					 //find original transaction id from order id  eof 
					
				 if($sale_amount < 0 ||  $commission < 0 ) {
				   $transaction_type = 2;					
				 
					$rebate_amount =  $original_transaciton_balance - (str_replace('-','',($sale_amount*tep_get_rebate_balance_adveriser_id_persntage($advertiser_id))));
					
					$corrected =  number_format($commission,2,'.','');
				      			  				   
				 }else{
				 //$associated_transaction_id  = trim($rawdata[18]); 
				  $transaction_type = 1; 
				  $rebate_amount =  number_format(($sale_amount*tep_get_rebate_balance_adveriser_id_persntage($advertiser_id)),2,'.','');
				  $corrected = 0;				   
				 }
				
					
					
				$rs = tep_db_query("select * from " . TABLE_REBATES_TRANSACTION . " where transaction_id = '".$transaction_id."'");			
				

				if ($rsdata = tep_db_fetch_array($rs))
				{
				
					echo "<br><b>Update </b><br>";	
					echo "Transaction ID:- &nbsp;&nbsp;" .$transaction_id."</br>";
					
					if($corrected < 0 ){
								 if($corrected != tep_get_currected_transaction_id($transaction_id) ) {
									 $rebate_amount =  $original_transaciton_balance + (str_replace('-','',tep_get_rebate_balance_transaction_id($transaction_id))) - (str_replace('-','',($sale_amount*tep_get_rebate_balance_adveriser_id_persntage($advertiser_id))));
								 }else{
									 $rebate_amount = $original_transaciton_balance;
								 }
					}else{
								
							  	$rebate_amount = $original_transaciton_balance - tep_get_rebate_balance_transaction_id($transaction_id) + number_format(($sale_amount*tep_get_rebate_balance_adveriser_id_persntage($advertiser_id)),2,'.','');
					}
					
					 $rebate_amount = number_format($rebate_amount,2,'.',''); 
					
					
					 $sql_data_array1 = array(
						 
						  		'advertiser_id' => $advertiser_id,
								
								'advertiser_name' => $advertiser_name,
								
								'customers_id' => $customers_id,
								
								'sale_amount' => $sale_amount,
								
								'commission' => $commission,
								
								'corrected'  => $corrected,
								
								'event_date' => $event_date,
								
								'posting_date' => $posting_date,
								
								'order_id' => $order_id,
								
								'transaction_type' => $transaction_type, 
								
								'posting_time' => $posting_time,
								
								'event_time' => $event_time,
								
								'sku_number' => $sku_number,
																								
								'associated_transaction_id'=> $associated_transaction_id,   
							
		                      );							
					tep_db_perform(TABLE_REBATES_TRANSACTION, $sql_data_array1, 'update', "transaction_id = '" .$transaction_id. "'");		  
									
					
					if($commission < 0 || $sale_amount < 0) { 
						
					 		$sql_rebate_history_array = array(
								 
								 //		'customers_id' => $customers_id,										
										//'rebate_amount' => $original_transaciton_balance,
										'rebate_amount' => $rebate_amount, 										
										//'rebate_status' => 'Corrected',  
										
									  ); 
								
								tep_db_perform(TABLE_REBATES_HISTORY, $sql_rebate_history_array, 'update', "transaction_id = '".$original_transaction_id. "'");		  
							
					//echo "Rebate Status Update to:-  Corrected</br>";					
					
							}else{					
								//updatein rebate history start
													
									 $sql_rebate_history_array = array(								 
																	 
										'customers_id' => $customers_id,
										
										'rebate_amount' => number_format($rebate_amount,2,'.',''),										
										
										'rebate_status' => 'Pending',  
										
									  );
									tep_db_perform(TABLE_REBATES_HISTORY, $sql_rebate_history_array, 'update', "transaction_id = '" .$original_transaction_id. "'");		  
						  		
								//updatein in rebate history end
						}
						
						
				}
				else 
				{
				
					echo "<br><b>Insert </b><br>"; 
					echo "Transaction id :- &nbsp;&nbsp;" . $transaction_id."</br><br>";
						
						//check for referal amout start 
						$referrals_email = tep_get_customers_email($customers_id);
						$check_customer_query = tep_db_query("select rri.customers_id from " . TABLE_REBATES_REFERRALS_INFO . " as rri, ".TABLE_CUSTOMERS." as c where rri.referrals_status ='1' and rri.referrals_email =  c.customers_email_address  and c.site_name ='Sams Rebates' and rri.referrals_email = '".$referrals_email."'");
    					if($customer_referrals = tep_db_fetch_array($check_customer_query)){
						$referred_cust_id = $customer_referrals['customers_id'];
							
							// insert free referral rebate transaction account start
							$sql_free_rebates_data_array = array( 'customers_id' => $referred_cust_id,
													'transaction_id' => 'SamsBonus_Referral_'.$customers_id,
													'advertiser_name' => 'Referral Bonus',
													'transaction_type' => '4',
													'event_date' => 'now()', 
													);
							  tep_db_perform(TABLE_REBATES_TRANSACTION, $sql_free_rebates_data_array);
							  $rebate_history_id  = tep_db_insert_id();
							 // insert free referral rebate transaction account end
							 // insert free referral rebate history account start
							 $sql_free_rebates_data_array = array( 'customers_id' => $referred_cust_id,
													'transaction_id' => 'SamsBonus_Referral_'.$customers_id, 
													'rebate_status' => 'Confirmed', 
													'rebate_amount' => SAMSREBATES_REFERAL_BONUS_REGISTRATION, 
													); 
							  tep_db_perform(TABLE_REBATES_HISTORY, $sql_free_rebates_data_array);
							  $rebate_history_id  = tep_db_insert_id();
							 // insert free rebate history account end
							 //update state one time paid start
							  $sql_update_rebates_info_status = array( 'referrals_status' => '2',
																	); 
							  tep_db_perform(TABLE_REBATES_REFERRALS_INFO, $sql_update_rebates_info_status, "update", "referrals_email= '".$referrals_email."' and customers_id = '".$referred_cust_id."'");
					
							 //update status one time paid end
						}
						//check for referal amoutn end
						
						 $sql_data_array1 = array(
						 
						 		'transaction_id' => $transaction_id,
						 
						 		'advertiser_id' => $advertiser_id,
								
								'advertiser_name' => $advertiser_name,
								
								'customers_id' => $customers_id,
								
								'sale_amount' => $sale_amount,
								
								'commission' => $commission, 
								
								'corrected'  => $corrected,
								
								'event_date' => $event_date,
								
								'posting_date' => $posting_date,
								
								'order_id' => $order_id,
								
								'transaction_type' => $transaction_type, 
								
								'posting_time' => $posting_time,
								
								'event_time' => $event_time,
																								
								'sku_number' => $sku_number,
																								
								'associated_transaction_id'=> $associated_transaction_id,   
							
		                      );							
							  tep_db_perform(TABLE_REBATES_TRANSACTION, $sql_data_array1);
							  
					
							if($commission < 0 || $sale_amount < 0 ) {
						
									$sql_rebate_history_array = array(
									
												'customers_id' => $customers_id,
										 
												'rebate_amount' => $rebate_amount,
												
												//'rebate_status' => 'Corrected', 
												
											  ); 
										
								tep_db_perform(TABLE_REBATES_HISTORY, $sql_rebate_history_array, 'update', "transaction_id = '" .$original_transaction_id. "'");		  
										
							 }else{
								//inset in rebate history start
														
									 $sql_rebate_history_array = array(
								 
										'transaction_id' => $transaction_id,
								 
										'customers_id' => $customers_id,
										
										'rebate_amount' => number_format($rebate_amount,2,'.',''),										
										
										'rebate_status' => 'Pending', 
										
									  );
								tep_db_perform(TABLE_REBATES_HISTORY, $sql_rebate_history_array);
								//inset in rebate history end
							}
						}
			
				
			}
			
			}
			
			echo "<br><b>Data Import Successfully.</b>";
			echo "</br></br><a href='javascript:history.go(-1)'><img src='includes/languages/english/images/buttons/button_back.gif' border='0' alt='Back' title=' Back '></a>";
			exit;
			
		 }
		////////////////EOF: SCS Added:  Import LinkShare order Data  ///////////////////////////			
	 
		

		/////////////  BOF:  Import Store data
		if($importdata == "uploadstore") { 
		
			foreach($readed as $key => $val)
			{
				$rawdata = explode($separator, $val);
				//$rawdata = strip_tags(str_replace("'","\'",$rawdata));
				$stores_name_temp = $rawdata[0];				
				$enterstoresname = tep_db_input(trim($rawdata[0])); 			
				$enterstoresname = str_replace("'","\\''",$enterstoresname);
				$rawdata = str_replace('"','',$rawdata);
							
				if(trim($rawdata[0]) != '' || trim($rawdata[0]) != 0){
				//echo "select * from " . TABLE_STORES . " where stores_name = '".$enterstoresname."'";		
				$rs = tep_db_query("select * from " . TABLE_STORES . " where stores_name = '".$enterstoresname."'");			
				$stores_name = tep_db_input(trim($rawdata[0])); 
				$category_name = str_replace("'","\'",$rawdata[1]);
				$stores_url = trim($rawdata[2]);
				$stores_urlname = trim($rawdata[3]);					
				$stores_image =  trim($rawdata[4]); 
				$stores_short_description =  tep_db_input(trim($rawdata[5]));
				$stores_description = trim($rawdata[6]);
				$stores_epc =  trim($rawdata[7]);
				$status =  trim($rawdata[8]);
				$stores_email_address = trim($rawdata[9]);
				$stores_telephone = trim($rawdata[10]);
				$stores_fax = trim($rawdata[11]);
				$stores_street_address = tep_db_input(trim($rawdata[12]));
				$stores_city = trim($rawdata[13]);
				$stores_post_code = trim($rawdata[14]);
				$stores_state = trim($rawdata[15]);
				$feature_status = trim($rawdata[16]);
			    $index_feature_status = trim($rawdata[17]);
				$stores_rebates = trim($rawdata[18]);
				$rebates_status = trim($rawdata[19]);
			    $stores_network = trim($rawdata[20]);
				$advertiser_id = trim($rawdata[21]);
				$samscoupons_title = trim($rawdata[22]);
				$samscoupons_meta_keywords = trim($rawdata[23]);
				$samscoupons_meta_description = trim($rawdata[24]);
				$samscoupons_seo_copy1 = trim($rawdata[25]);
				$samscoupons_seo_copy2 = trim($rawdata[26]);
				$samsrebates_title = trim($rawdata[27]);
				$samsrebates_meta_keywords = trim($rawdata[28]);
				$samsrebates_meta_description = trim($rawdata[29]);
				$samsrebates_seo_copy1 = trim($rawdata[30]);
				$samsrebates_seo_copy2 = trim($rawdata[31]); 
				$categories_id = '';
				///  scs added:  find stores_id  from ///
				
			
				/*			
				$finddata = tep_db_query("select  stores_id  from ".TABLE_STORES."  where stores_name ='".$rawdata[0]."'");
				if($stores = tep_db_fetch_array($finddata)) {
					
					$stores_id =  $stores['stores_id'];
				}
				*/
				
				///  scs added: stores_id from /// 
				
				///  scs added:  find categories_id  from ///
				$finddata = tep_db_query("select  categories_id  from `".TABLE_CATEGORIES_DESCRIPTION."`  where categories_name ='".$category_name."'");
				if($stores = tep_db_fetch_array($finddata)) {
				$categories_id = $stores['categories_id'];
				}
				///  scs added: categories_id from /// 

				if ($rsdata = tep_db_fetch_array($rs))
				{
					$stores_id = $rsdata['stores_id'];
					
					//$stores_name = str_replace("\\''","\'",$stores_name);
					
					
					$stores_name = tep_db_prepare_input($stores_name_temp);

					//$stores_name = ucfirst(substr($stores_name, 0, 1)).substr($stores_name, 1, strlen($stores_name));					
					
					$stores_name =addcslashes($stores_name,"\'");
					
					echo "</br>";
					echo "<b>Update</b><br>";
					echo "Stores Id:-".$stores_id."</br>";
					echo "Stores_name:-".tep_db_prepare_input($stores_name)."</br>";
					
					
					if ( $categories_id == ''){
					echo "<b><font color=red>category ". tep_db_prepare_input($category_name) ." not found, update failed.</b></font></br>";
					}else{
					$update_sql = '';
					// override the sql if we're using Linda's contrib
						
						//echo tep_db_input($stores_name);
						//exit;
							$update_sql = "UPDATE ".TABLE_STORES." SET
									stores_id = '".(int)$stores_id."',
									category_id = '".(int)$categories_id."',
									stores_name  = '".tep_db_input($stores_name)."',
									stores_url = '" .$stores_url."',
									stores_urlname = '" .$stores_urlname."',
									stores_image  = '" .$stores_image."',
									stores_short_description  = '".$stores_short_description."',
									stores_description = '".tep_db_input($stores_description)."', 
									stores_email_address  ='".$stores_email_address."',
									stores_telephone = '".$stores_telephone."',
									stores_fax = '".$stores_fax."',
									stores_street_address = '".$stores_street_address."',
									stores_city = '".$stores_city."',
									stores_post_code = '". $stores_post_code."',
									stores_state = '".$stores_state."',
									stores_epc = '".$stores_epc ."',
									status = '".$status."', 
									feature_status =  '".$feature_status."', 
									index_feature_status =  '".$index_feature_status."', 
									stores_rebates =  '".$stores_rebates."', 
									rebates_status =  '".$rebates_status."', 
									stores_network =  '".$stores_network."', 
									advertiser_id =  '".$advertiser_id."', 
									samscoupons_title =	'".tep_db_input($samscoupons_title)."', 
									samscoupons_meta_keywords = '".tep_db_input($samscoupons_meta_keywords)."', 
									samscoupons_meta_description = '".tep_db_input($samscoupons_meta_description)."', 
									samscoupons_seo_copy1 = '".tep_db_input($samscoupons_seo_copy1)."', 
									samscoupons_seo_copy2 = '".tep_db_input($samscoupons_seo_copy2)."', 
									samsrebates_title = '".tep_db_input($samsrebates_title)."', 
									samsrebates_meta_keywords = '".tep_db_input($samsrebates_meta_keywords)."', 
									samsrebates_meta_description = '".tep_db_input($samsrebates_meta_description)."', 
									samsrebates_seo_copy1 = '".tep_db_input($samsrebates_seo_copy1)."', 
									samsrebates_seo_copy2 = '".tep_db_input($samsrebates_seo_copy2)."', 
									last_modified = '".CURRENT_TIMESTAMP."'
									WHERE 
									stores_id = '$stores_id'";
									
					//echo $update_sql;																	
					
					$result = tep_db_query($update_sql);
					// echo "</br>";
					}
				}
				else 
				{
									
					$stores_name = tep_db_prepare_input($stores_name_temp);

					//$stores_name = ucfirst(substr($stores_name, 0, 1)).substr($stores_name, 1, strlen($stores_name));					
					
					$stores_name =addcslashes($stores_name,"\'");
					
					/// scs added: find max store id////////
					$max_sql = "SELECT MAX(stores_id) max FROM ".TABLE_STORES;
					$max_result = tep_db_query($max_sql);
					$max_row =  tep_db_fetch_array($max_result);
					$stores_id = $max_row['max']+1;
					/// scs added: find max store id ////
					echo "</br>";
					echo "<b>Insert</b><br>";
					echo "Stores Id:-".$stores_id."</br>";
					echo "Stores_name:-".tep_db_prepare_input($stores_name)."</br>";
					
					if ( $categories_id == ''){
					echo "<b><font color=red>category ". tep_db_prepare_input($category_name) ." not found, insert failed.</b></font></br>";
					}else{						
					$insert_sql = "INSERT INTO ".TABLE_STORES."
									(stores_id,
									category_id,
									stores_name,
									stores_url,
									stores_urlname,
									stores_image,
									stores_short_description,
									stores_description, 
									stores_email_address,
									stores_telephone,
									stores_fax,
									stores_street_address,
									stores_city,
									stores_post_code,
									stores_state,
									stores_epc,
									status, 
									feature_status,
									index_feature_status,
									stores_rebates, 
									rebates_status, 
									stores_network, 
									advertiser_id, 
									samscoupons_title, 
									samscoupons_meta_keywords, 
									samscoupons_meta_description, 
									samscoupons_seo_copy1, 
									samscoupons_seo_copy2, 
									samsrebates_title, 
									samsrebates_meta_keywords, 
									samsrebates_meta_description, 
									samsrebates_seo_copy1, 
									samsrebates_seo_copy2, 
									date_added,
									last_modified)
									VALUES (
											'".(int)$stores_id."',
											'".(int)$categories_id."',
											'".tep_db_input($stores_name)."',
											'".$stores_url."',
											'".$stores_urlname."',
											'".$stores_image."',
											'".$stores_short_description."',
											'".tep_db_input($stores_description)."', 
											'".$stores_email_address."',
											'".$stores_telephone."',
											'".$stores_fax."',
											'".$stores_street_address."',
											'".$stores_city."',
											'".$stores_post_code."',
											'".$stores_state."',
											'".$stores_epc ."',
											'".$status."', 
											'".$feature_status."', 
											'".$index_feature_status."', 
											'".$stores_rebates."', 
											'".$rebates_status."', 
											'".$stores_network."', 
											'".$advertiser_id."',
											'".tep_db_input($samscoupons_title)."', 
											'".tep_db_input($samscoupons_meta_keywords)."', 
											'".tep_db_input($samscoupons_meta_description)."', 
											'".tep_db_input($samscoupons_seo_copy1)."', 
											'".tep_db_input($samscoupons_seo_copy2)."', 
											'".tep_db_input($samsrebates_title)."', 
											'".tep_db_input($samsrebates_meta_keywords)."', 
											'".tep_db_input($samsrebates_meta_description)."', 
											'".tep_db_input($samsrebates_seo_copy1)."', 
											'".tep_db_input($samsrebates_seo_copy2)."', 
											'".CURRENT_TIMESTAMP."',
											'".CURRENT_TIMESTAMP."')";
						
						
						//echo $insert_sql;
						
						$result = tep_db_query($insert_sql);
						//echo "</br>";
					}
				}
				
			}
			
			}
			echo "<br><b>Data Import Successfully.</b>";
			echo "</br></br><a href='javascript:history.go(-1)'><img src='includes/languages/english/images/buttons/button_back.gif' border='0' alt='Back' title=' Back '></a>";
			exit;
			
		 }
		////////////////EOF: SCS Added:  Import Store Data  ///////////////////////////			
		
		
		/////////////  BOF:  Import Store Coupon data /////////////////////////////////
		if($importdata == "uploadstorecoupone") {

			foreach($readed as $key => $val)
			{
				
				$rawdata = explode($separator, $val);
				//$rawdata = str_replace("'",'`',$rawdata);
				$rawdata = str_replace('"','',$rawdata);

				if(trim($rawdata[1]) != ''){				
				$sql = "select * from " . TABLE_STORES_COUPONS . " where coupon_id= '" . (int)$rawdata[0]. "'"	;
				$rs = tep_db_query($sql);
				
				$coupon_id = trim((int)$rawdata[0]);
				$stores_name = tep_db_input(trim($rawdata[1])); 
				$store_display =  tep_db_prepare_input($stores_name);			
				$stores_name = str_replace("'","\\''",$stores_name);
				$categories_name = str_replace("'","\'",$rawdata[2]);
				$coupon_name = trim($rawdata[3]);	
				$coupon_url =  trim($rawdata[4]);
				$coupon_code =  trim($rawdata[5]);
				$coupon_description =  trim($rawdata[6]);
				$coupon_type =  trim($rawdata[7]);
				$coupon_restriction =  trim($rawdata[8]);
				$coupon_expire_date =  trim($rawdata[9]);
				$status = trim($rawdata[10]);
				$feature_status = trim($rawdata[11]);
				$index_feature_status = trim($rawdata[12]);
				$categories_id = '';
				$stores_id = '';
				
				
				
				if(strtolower($coupon_type) == 'free shipping' ){
				$coupon_type = '1';
				}else if(strtolower($coupon_type) == 'clearance sales') {
				$coupon_type = '2';
				}
				
				/*			
					///  scs added:  find store id  and categories_id  from coupon_id ///
					$finddata = tep_db_query('select s.stores_id, d.categories_id ,s.stores_name, d.categories_name from ' . TABLE_STORES_COUPONS . ' AS sc,' . TABLE_STORES . ' AS s,'.TABLE_CATEGORIES_DESCRIPTION.' as d  where sc.coupon_id = '.$coupon_id.' and sc.categories_id = d.categories_id and s.stores_id = sc.stores_id');
    				while ($stores = tep_db_fetch_array($finddata)) {
					$stores_id =  $stores['stores_id'];
					$categories_id =  $stores['categories_id'];
					}
					///  scs added:  find store id and categories_id from coupon_id /// 
					*/
				$findcatid = tep_db_query("select categories_id from " . TABLE_CATEGORIES_DESCRIPTION . "  where categories_name = '$categories_name'");
   				while ($strcat = tep_db_fetch_array($findcatid)) {					
					$categories_id =  $strcat['categories_id'];					
				}
				
				$findstoreid = tep_db_query("select stores_id from " . TABLE_STORES . " where stores_name = '$stores_name'");
    				if ($stores = tep_db_fetch_array($findstoreid)) {					
					$stores_id =  $stores['stores_id'];					
					}
													
					$stores_name = tep_db_prepare_input($stores_name);

					//$stores_name = ucfirst(substr($stores_name, 0, 1)).substr($stores_name, 1, strlen($stores_name));					
					
					$stores_name =addcslashes($stores_name,"\'");
					
				
				if ($rsdata = tep_db_fetch_array($rs))
				{
					echo "</br>";
					echo "<b>Update</b><br>";
					echo "Coupon Id :-".$coupon_id."</br>";
					echo "Coupon Name:-".$coupon_name."</br>";
					
					
					if ( $categories_id == '' || $stores_id == '' ){
					echo "<b><font color=red>Please check category ". tep_db_prepare_input($categories_name) ." or stores name $store_display not found, update failed.</b></font></br></br>";
					}else{					
					///  scs added: update store coupon  table EOF  ////
					$update_store_coupon = "UPDATE ".TABLE_STORES_COUPONS." SET
									coupon_name  = '".tep_db_input($coupon_name). "',
									categories_id ='".$categories_id ."',
									stores_id = '".$stores_id."',
									coupon_url  = '" . $coupon_url."',
									coupon_code = '".$coupon_code."',
									coupon_description  = '".tep_db_input($coupon_description)."',
									coupon_type = '".(int)$coupon_type."',
									coupon_restriction  = '".tep_db_input($coupon_restriction)."',
									coupon_expire_date = '". $coupon_expire_date ."',
									status = '". $status ."',
									feature_status = '".$feature_status ."',
									index_feature_status = '".$index_feature_status ."',
									date_modified = '".CURRENT_TIMESTAMP."'
									WHERE 
									coupon_id = '$coupon_id'";
									
					//	echo $update_store_coupon;																	
					$result = tep_db_query($update_store_coupon);					
					//echo "</br>";					
					///  scs added: update store coupon  table EOF  ////
					/*
					///  scs added: update store table EOF  ////
					
					$update_store = "UPDATE ".TABLE_STORES." SET
									stores_name  = '".tep_db_input($stores_name)."',
									category_id ='".$categories_id."',
									stores_id = '".$stores_id."'
									WHERE 
									stores_id = '$stores_id'";
									
					//	echo $update_store;																	
					$result = tep_db_query($update_store);					
					//echo "</br>";
					*/					
										
					///  scs added: update storetable EOF  ////
					}
					
				}
				else
				{
					
						/// scs added: find max coupon_id////////
					$max_sql = "SELECT MAX(coupon_id) max FROM ".TABLE_STORES_COUPONS;
					$max_result = tep_db_query($max_sql);
					$max_row =  tep_db_fetch_array($max_result);
					$coupon_id = $max_row['max']+1;
					/// scs added: find max coupon_id////
					
					if($coupon_name != '') {
					echo "</br>";
					echo "<b>Insert</b><br>";
					echo "Coupon Id :-".$coupon_id."</br>";
					echo "Coupon Name:-".$coupon_name."</br>";
					
					if($categories_id == '' || $stores_id == '' ){
					echo "<b><font color=red>Please check category  ". tep_db_prepare_input($categories_name) ." or stores name $store_display not found, insert failed.</b></font></br>";
					}else{	
					$insert_coupon_sql = "INSERT INTO ".TABLE_STORES_COUPONS."
									(coupon_id,
									stores_id,
									categories_id,
									coupon_code,
									coupon_name,
									coupon_url,
									coupon_description,
									coupon_type,
									coupon_restriction,
									coupon_start_date, 
									coupon_expire_date,									
									status,
									feature_status,
									index_feature_status,
									date_created)
									VALUES (
											'".(int)$coupon_id ."',
											'".$stores_id."',
											'".$categories_id."',
											'".$coupon_code . "',
											'" .tep_db_input($coupon_name)."',
											'" . $coupon_url  ."',
											'" .tep_db_input($coupon_description)."',
											'".(int)$coupon_type."',
											'".tep_db_input($coupon_restriction)."',
											'".CURRENT_TIMESTAMP."',
											'".$coupon_expire_date."',											
											'".$status."',
											'".$feature_status."',
											'".$index_feature_status."',											
											'".CURRENT_TIMESTAMP."')";
						
						//echo $insert_coupon_sql;						
						$result = tep_db_query($insert_coupon_sql);
						//echo "</br>";
					}
				  }	
				}
			  }				
			}
			echo "<br><b>Data Import Successfully.</b>";
			if(isset($backpath)) {
			echo "</br></br><a href='stores_product_coupons.php?s_id=$backpath'><img src='includes/languages/english/images/buttons/button_back.gif' border='0' alt='Back' title=' Back '></a>";
			}else {
			echo "</br></br><a href='javascript:history.go(-1)'><img src='includes/languages/english/images/buttons/button_back.gif' border='0' alt='Back' title=' Back '></a>";
			}
			exit;
			
			}
			////////////EOF: SCS Added:  Import Store Coupon Data  /////////////////////			


/////////////  BOF:  Import Store deals data /////////////////////////////////
		if($importdata == "uploadstoredeals") {
		
			foreach($readed as $key => $val)
			{
				
				$rawdata = explode($separator, $val);
				//$rawdata = str_replace("'",'`',$rawdata);
				$rawdata = str_replace('"','',$rawdata);
				if(trim($rawdata[1]) != ''){				
				$sql = "select * from " . TABLE_STORES_DEALS . " where deals_id= '" . (int)$rawdata[0]. "'"	;
				$rs = tep_db_query($sql);				
				$deals_id = trim($rawdata[0]);			
				$stores_name = tep_db_input(trim($rawdata[1])); 
				$store_display =  tep_db_prepare_input($stores_name);			
				$stores_name = str_replace("'","\\''",$stores_name);
				$categories_name = str_replace("'","\'",$rawdata[2]);
				$deals_headline = trim($rawdata[3]);	
				$deals_url =  trim($rawdata[4]);
				$deals_description =  trim($rawdata[5]);
				$deals_status =  trim($rawdata[6]);
				$deals_expire_date =  trim($rawdata[7]);
				$categories_id = '';
				$stores_id = '';
				/*
				///  scs added:  find categories_id  from ///
					$finddata = tep_db_query("select s.stores_id, cd.categories_id from " . TABLE_CATEGORIES_DESCRIPTION . " AS cd," . TABLE_STORES . " AS s  where s.stores_name  = '$stores_name' and cd.categories_name = '$categories_name' and s.category_id = cd.categories_id");

    				while ($stores = tep_db_fetch_array($finddata)) {
					
					$categories_id =  $stores['categories_id'];
					$stores_id =  $stores['stores_id'];
					
					}
					///  scs added: categories_id from /// 
			*/
					
				
				$findcatid = tep_db_query("select categories_id from " . TABLE_CATEGORIES_DESCRIPTION . "  where categories_name = '$categories_name'");
					if ($strcat = tep_db_fetch_array($findcatid)) {
					
					$categories_id =  $strcat['categories_id'];					
					}
					
				$findstoreid = tep_db_query("select stores_id from " . TABLE_STORES . " where stores_name = '".$stores_name."'");

    				if ($stores = tep_db_fetch_array($findstoreid)) {
					
					$stores_id =  $stores['stores_id'];					
					}
					
				
					$stores_name = tep_db_prepare_input($stores_name);

					//$stores_name = ucfirst(substr($stores_name, 0, 1)).substr($stores_name, 1, strlen($stores_name));					
					
				//	$stores_name =addcslashes($stores_name,"\'");
						
													
				if ($rsdata = tep_db_fetch_array($rs))
				{
					//echo "update <br>";
					echo "</br>";
					echo "<b>Update</b><br>";
					echo "Deals Id :-".$deals_id."</br>";
					echo "Stores Name:-".$store_display."</br>"; 
					
					
					
					if ( $categories_id == '' || $stores_id == '' ){
					echo "<b><font color=red>Please check category ". tep_db_prepare_input($categories_name) ." or stores name  $store_display not found, update failed.</b></font></br>";
					}else{
					///  scs added: update store coupon  table EOF  ////
					$update_store_deals = "UPDATE ".TABLE_STORES_DEALS." SET
									deals_headline  = '".tep_db_input($deals_headline)."',
									categories_id  = '".$categories_id."',
									stores_id = '".$stores_id."',
									deals_url  = '". $deals_url ."',
									deals_description  = '".tep_db_input($deals_description) ."',
									deals_status = '". $deals_status ."',
									deals_expire_date = '".$deals_expire_date ."',
									deals_date_last_modified = '".CURRENT_TIMESTAMP."'
									WHERE 
									deals_id= '".$deals_id."'";
									
					//echo $update_store_deals;																	
					$result = tep_db_query($update_store_deals);					
					//echo "</br>";
					
					/*					
					///  scs added: update store table EOF  ////
					$update_store = "UPDATE ".TABLE_STORES." SET
									stores_name  = '".tep_db_input($stores_name)."',
									category_id  = '".$categories_id."',
									stores_id = '".$stores_id."',
									category_id  ='".$categories_id."'
									WHERE 
									stores_id = '$stores_id'";
									
					//echo $update_store;																	
					$result = tep_db_query($update_store);					
					//echo "</br>";
					*/					
				
					}
				}
				else
				{
				//	echo "insert <br>";
						/// scs added: find max deals_id////////
					$max_sql = "SELECT MAX(deals_id) max FROM ".TABLE_STORES_DEALS;
					$max_result = tep_db_query($max_sql);
					$max_row =  tep_db_fetch_array($max_result);
					$deals_id = $max_row['max']+1;
					/// scs added: find max coupon_id////
					if($deals_headline != '') {
					
					echo "</br>";
					echo "<b>Insert</b><br>";
					echo "Deals Id :-".$deals_id."</br>";
					echo "Stores Name:-".$store_display."</br>";
					
					if($categories_id == '' ||  $categories_id == 0 || $stores_id == '' ){
					echo "<b><font color=red>category ". tep_db_prepare_input($categories_name) ." or stores name  $store_display not found, insert failed.</b></font></br>";
					}else{	
					$insert_deals_sql =
								"INSERT INTO ".TABLE_STORES_DEALS."
									(deals_id,
									deals_headline,
									categories_id,
									stores_id,
									deals_url,
									deals_description,
									deals_date_added,
									deals_expire_date
									)
									VALUES (
											'".(int)$deals_id ."',
											'".tep_db_input($deals_headline). "',
											'".$categories_id."',
											'".$stores_id."',
											'" . $deals_url ."',
											'" .tep_db_input($deals_description)."',
											'".CURRENT_TIMESTAMP."',
											'".$deals_expire_date ."' 
											)";
						
						//echo $insert_deals_sql;
						$result = tep_db_query($insert_deals_sql);
						//echo "</br>";
					}
					}	
				}
				
				}
			}
			echo "<br><b>Data Import Successfully.</b>";
			if(isset($backpath)) {
			echo "</br></br><a href='stores_product_deals.php?s_id=$backpath'><img src='includes/languages/english/images/buttons/button_back.gif' border='0' alt='Back' title=' Back '></a>";
			}else {
			echo "</br></br><a href='javascript:history.go(-1)'><img src='includes/languages/english/images/buttons/button_back.gif' border='0' alt='Back' title=' Back '></a>";
			}
		
			exit;
			
			}
			////////////EOF: SCS Added:  Import Store Deals Data  /////////////////////			
		





		
		/////////////  BOF:  Import Category data  ////////////////////////
		if($importdata == "uploadcategory") {	
			
			foreach($readed as $key => $val)
			{
				
				$rawdata = explode($separator, $val);
				$rawdata = str_replace('"','',$rawdata);
				//$rawdata = tep_db_input($rawdata);
				$rawdata = str_replace("'","\'",$rawdata);
				
				if(trim($rawdata[0]) != '' || trim($rawdata[0]) != 0){
				$sql = "select * from " . TABLE_CATEGORIES_DESCRIPTION. " where categories_name ='".trim($rawdata[0])."'";	
				$rs = tep_db_query($sql);
							
				
				$categories_name = trim($rawdata[0]);
				$parent_categories_name = trim($rawdata[1]);
				$categories_heading_title =  trim($rawdata[2]);
				$categories_urlname =  trim($rawdata[3]);
			    $categories_description  = trim($rawdata[4]);				
				$categories_seo_description = trim($rawdata[5]);				
				$categories_logo_alt_tag = trim($rawdata[6]);
				$categories_first_sentence = trim($rawdata[7]);
				$sort_order = trim($rawdata[8]);
				$categories_head_title_tag = trim($rawdata[9]);
			   	$categories_head_desc_tag = trim($rawdata[10]);
				$categories_head_keywords_tag = trim($rawdata[11]);
				$categories_status = trim($rawdata[12]);
				
				$par_error = "flase";			
				// find requiered id's/////////
				
			
	
			   $finddata = tep_db_query("select categories_id  from " . TABLE_CATEGORIES_DESCRIPTION . "  where categories_name = '".trim($categories_name)."'");
				if($category = tep_db_fetch_array($finddata)) {
					$categories_id =  $category['categories_id'];
				}
				
				
				/// end of needed data
				
				//find parent id start
				if($parent_categories_name != ''){
				$findparentid = tep_db_query("select categories_id  from " . TABLE_CATEGORIES_DESCRIPTION . "  where categories_name = '".$parent_categories_name."'");
				if($parent = tep_db_fetch_array($findparentid)) {
					$parent_id =  $parent['categories_id'];
				}else {
					$par_error = "true";
				}
				}
				
												
				// find parent id end 
				
								
				if ($rsdata = tep_db_fetch_array($rs))
				{
					$categories_name = ucfirst(substr($categories_name, 0, 1)).substr($categories_name, 1, strlen($categories_name));
					echo "</br>";					
					echo "<b>Update</b><br>"; 
					echo "Category Id:-".$categories_id."</br>";
					echo "Category Name:-".tep_db_prepare_input($categories_name)."</br>";
					
					
					if($par_error == "true" &&  $parent_categories_name != '' ){
					echo "<b><font color=red>Parent Category  Name  ". tep_db_prepare_input($parent_categories_name) ." not found, update failed.</b></font></br>";
					}else {
					
					if($parent_categories_name == '' ){
						$update_cat_table = "UPDATE ".TABLE_CATEGORIES." SET
									categories_urlname   = '".$categories_urlname."',
									parent_id = '0',
									sort_order  = '" .$sort_order."',
									last_modified  = '".CURRENT_TIMESTAMP."'
									WHERE 
									categories_id = '".$categories_id."'";
					}else{
					
					$update_cat_table = "UPDATE ".TABLE_CATEGORIES." SET
									categories_urlname   = '".$categories_urlname."',
									parent_id = '".$parent_id."',
									sort_order  = '" .$sort_order."',
									categories_status = '".$categories_status."',
									last_modified  = '".CURRENT_TIMESTAMP."'
									WHERE categories_id = '".$categories_id."'";
					
					}
									
				//	echo $update_cat_table;
					$result = tep_db_query($update_cat_table);
				//	echo "</br>";
					
					
					$update_cat_dis_table = '';
					// override the sql if we're using Linda's contrib
					
						$update_cat_dis_table  = "UPDATE ".TABLE_CATEGORIES_DESCRIPTION." SET
									categories_heading_title = '".tep_db_input($categories_heading_title)."',
									categories_description  = '".tep_db_input($categories_description)."',
									categories_seo_description  = '".tep_db_input($categories_seo_description)."',
									categories_logo_alt_tag   ='".tep_db_input($categories_logo_alt_tag)."',
									categories_first_sentence  = '".tep_db_input($categories_first_sentence)."',
									categories_head_title_tag = '".tep_db_input($categories_head_title_tag)."',
									categories_head_desc_tag = '".tep_db_input($categories_head_desc_tag)."',
									categories_head_keywords_tag = '".tep_db_input($categories_head_keywords_tag)."'									
									WHERE categories_id = '".$categories_id."'";
									
				//	echo $update_cat_dis_table;																	
					$result = tep_db_query($update_cat_dis_table);
				//	echo "</br>";
					
					/*
					$update_cat_meta  = "UPDATE ".TABLE_METATAGS." SET
									title   = '".$category_title_tag. "',
									keywords = '" .$meta_keyword_tag."',
									description  = '" .$meta_description_tag."'
									WHERE 
									categories_id = '".$categories_id."'";
									
				//	echo $update_cat_meta;
					$result = tep_db_query($update_cat_meta);
					
				//	echo "</br>";
					*/
				  }	
				}
				else
				{
									
					$categories_name = ucfirst(substr($categories_name, 0, 1)).substr($categories_name, 1, strlen($categories_name));

					/// scs added: find max categories id////////
					$max_sql = "SELECT MAX(categories_id) max FROM ".TABLE_CATEGORIES;
					$max_result = tep_db_query($max_sql);
					$max_row =  tep_db_fetch_array($max_result);
					$categories_id = $max_row['max']+1;
					/// scs added: find max categories id////
					echo "</br>";
					echo "<b>Insert</b><br>";
					echo "Category Id:-".$categories_id."</br>";
					echo "Category Name:-".tep_db_prepare_input($categories_name)."</br>";
					
					////////insert in category  table BOF: scs added: //////////
					
					if($par_error == "true" &&  $parent_categories_name != '' ){
					echo "<b><font color=red>Parent Category  Name ".tep_db_prepare_input($parent_categories_name) ." not found, insert failed.</b></font></br>";
					}else {
					if($parent_categories_name == '' ){
					$insert_cat_sql =
								"INSERT INTO ".TABLE_CATEGORIES."
									(categories_id ,
									categories_urlname,
									parent_id,																		
									sort_order,
									categories_status,
									date_added)
									VALUES (
											'".(int)$categories_id."',
											'".$categories_urlname. "',
											'0',
											'" . $sort_order ."',
											'".$categories_status."',
											'".CURRENT_TIMESTAMP."'
											)";
					
					
					}else {				
					$insert_cat_sql =
								"INSERT INTO ".TABLE_CATEGORIES."
									(categories_id ,
									categories_urlname,
									parent_id,																		
									sort_order,
									categories_status,
									date_added)
									VALUES (
											'".(int)$categories_id."',
											'".$categories_urlname. "',
											'".(int)$parent_id."',
											'" . $sort_order ."',
											'".$categories_status."',
											'".CURRENT_TIMESTAMP."'
											)";
						
					}	
					//echo $insert_cat_sql;
					$result = tep_db_query($insert_cat_sql);
					//echo "</br>";
					
					////////insert in category  table EOF: scs added: //////////
													
				////////insert in category desc table BOF: scs added: //////////
									
					$insert_cat_det_sql =
								"INSERT INTO ".TABLE_CATEGORIES_DESCRIPTION."
									(categories_id ,
									categories_name,
									categories_heading_title,
									categories_description,
									categories_seo_description,
									categories_logo_alt_tag,
									categories_first_sentence,
									categories_head_title_tag,
									categories_head_desc_tag,
									categories_head_keywords_tag)
									VALUES (
											'".(int)$categories_id."',
											'".$categories_name."',
											'" .tep_db_input($categories_heading_title)."',
											'".tep_db_input($categories_description)."',
											'" .tep_db_input($categories_seo_description)."',
											'" .tep_db_input($categories_logo_alt_tag)."',
											'" .tep_db_input($categories_first_sentence)."',
											'".tep_db_input($categories_head_title_tag)."',
											'".tep_db_input($categories_head_desc_tag)."',
											'".tep_db_input($categories_head_keywords_tag)."')";
											
					//echo $insert_cat_det_sql;									
					$result = tep_db_query($insert_cat_det_sql);					
					//echo "</br>";
					////////insert in category  desc  table EOF: scs added: //////////
				
					////////insert in meta table BOF: scs added: //////////
					/*				
					$insert_meta_sql =
								"INSERT INTO ".TABLE_METATAGS."
									(categories_id ,
									title,
									keywords,
									description
									)
									VALUES (
											'".(int)$categories_id."',
											'".$category_title_tag. "',
											'" .$meta_keyword_tag."',
											'" .$meta_description_tag."'
											)";
					//echo $insert_meta_sql;
					$result = tep_db_query($insert_meta_sql);
					//echo "</br>";
					*/
					////////insert in meta table BOF: scs added: //////////
					} 
				  }	
				}
			}
			
			echo "<br><b>Data Import Successfully.</b>";
			echo "</br></br><a href='javascript:history.go(-1)'><img src='includes/languages/english/images/buttons/button_back.gif' border='0' alt='Back' title=' Back '></a>";
			exit;
			
			}
			////////////EOF: SCS Added:  Import BOF:  Import Category data  /////////////////////			
	
			
			/////////////  BOF:  Import product data  ////////////////////////
		if($importdata == "uploadproductcat") {

			foreach($readed as $key => $val)
			{
			
				$categories_id = '';
				//$manufacturers_id = '';
				$rawdata = explode($separator, $val);
				
				$rawdata = str_replace("\'","'",$rawdata);
			//	$rawdata = str_replace("\\'","\'",$rawdata);
				$rawdata = str_replace('"','',$rawdata);
				//$rawdata = str_replace(",",'',$rawdata);
				if(trim($rawdata[0]) != '' || trim($rawdata[0]) != 0){
				$sql = "select * from " . TABLE_PRODUCTS. " where products_model ='".trim($rawdata[0])."'";	
				$rs = tep_db_query($sql);
				
				$products_model = trim($rawdata[0]);
				$products_name = trim($rawdata[1]);
				$products_image  = trim($rawdata[2]);
				$category_name =  str_replace("'","\'",trim($rawdata[3]));
				$products_description = stripslashes(trim($rawdata[4]));
				$products_urlname = trim($rawdata[5]);
				$products_url = trim($rawdata[6]);
				$products_is_regular_tour = trim($rawdata[7]);
				$products_durations = trim($rawdata[8]);
				$agency_id  = tep_get_agencyid_from_name(trim($rawdata[9]));
				$regions_id = tep_get_regionid_from_name(trim($rawdata[10]));
				$departure_city_id = tep_get_departureid_from_name(trim($rawdata[11]));
				$products_price = trim($rawdata[12]);				
				$products_head_title_tag = trim($rawdata[13]);
				$products_head_desc_tag = trim($rawdata[14]);
				$products_head_keywords_tag = trim($rawdata[15]);
				$products_status = trim($rawdata[16]);
				$featured_products = trim($rawdata[17]);		
				
				/*
				///find manufacture and category id from data bof//////////////
					$finddata = tep_db_query("select c.categories_id, m.manufacturers_id from " . TABLE_CATEGORIES_DESCRIPTION . " as c, " . TABLE_MANUFACTURERS . " as where c.categories_name = '".$categories_name."' AND m.manufacturers_name = '".$manufacturers_name."'");

    				while ($catemanu = tep_db_fetch_array($finddata)) {
										
					$categories_id = $catemanu['categories_id'];
					$manufacturers_id  = $catemanu['manufacturers_id'];
					
					} 
					///find manufactire and category id from data  end////////					
				*/
				///finde product id from product model /////////
				$findprod = tep_db_query("select products_id  from " . TABLE_PRODUCTS . "  where products_model = '".$products_model."'");
				if ($prodid = tep_db_fetch_array($findprod)) {
					$products_id =  $prodid['products_id'];
					
				}
										
				/////////////  end  ////////////////////////
				//find cat id and manufacture id ////
				
				$findcat = tep_db_query("select categories_id  from " . TABLE_CATEGORIES_DESCRIPTION . "  where categories_name = '".$category_name."'");
				if($cat = tep_db_fetch_array($findcat))
				{
					 $categories_id = $cat['categories_id'];					
				}
				
				/*  --amit commented becouse of no need to manufatures				
				if($manufacturer_name == 'None' || $manufacturer_name == ''){				
				 	$manufacturers_id = 0;
					$manufacturer_name = '';
				}
				
				
				
				if($manufacturer_name != ''){
				$manufacturer_name = str_replace("'","\'",$manufacturer_name);								
				$findmid= tep_db_query("select manufacturers_id  from " . TABLE_MANUFACTURERS . "  where manufacturers_name = '".$manufacturer_name."'");
				if ($manu = tep_db_fetch_array($findmid)) {
					$manufacturers_id =  $manu['manufacturers_id'];
				}
				}
				
				
				
				if ( $manufacturers_id == '' && $manufacturer_name != ''  ){
			
				
				/// scs added: find max  manufacturers_id  BOF///////
					$max_sql = "SELECT MAX( manufacturers_id) max FROM ".TABLE_MANUFACTURERS;
					$max_result = tep_db_query($max_sql);
					$max_row =  tep_db_fetch_array($max_result);
					$manufacturers_id  = $max_row['max']+1;
					/// scs added: find max  manufacturers_id  EOF////
					
					//scs added: insert new record of manufature BOF ////
					
					$insert_manu_sql = "INSERT INTO ".TABLE_MANUFACTURERS."
									(manufacturers_id,
									manufacturers_name,
									date_added 
									)
									VALUES (
											'".(int)$manufacturers_id."',
											'".tep_db_input($manufacturer_name)."',
											'".CURRENT_TIMESTAMP."'											
											)";
															
					$result = tep_db_query($insert_manu_sql);
				//	echo "</br>";
					//scs added: insert new record of manufature EOF ////
					//scs added: insert new record of manufature info  BOF ////
					
					$insert_manu_info_sql = "INSERT INTO ".TABLE_MANUFACTURERS_INFO."
									(manufacturers_id,								
									date_last_click 
									)
									VALUES (
											'".(int)$manufacturers_id."',										
											'".CURRENT_TIMESTAMP."'										
											)";
															
					$result = tep_db_query($insert_manu_info_sql);
					//scs added: insert new record of manufature info  EOF ////
					echo "<b>insert</b><br>";
					echo "manufacturers_id:-".$manufacturers_id."</br>";
					echo "manufacturers Name:-".$manufacturer_name."</br>";
					
					//scs added : insert new record of EOF BOF ////
			
				}
				*/
				
				
				//find cat id & manufacture id ////
				if ($rsdata = tep_db_fetch_array($rs))
				{
				//	echo "update <br>";
					echo "</br>";
					echo "<b>Update</b><br>";
					echo "Products Model:-".$products_model."</br>";
					echo "Products Name:-".$products_name."</br>";
					
															
					if($categories_id != ''){
						////////scs  update  product table BOF //////////////
						$update_product_table  = "UPDATE ".TABLE_PRODUCTS." SET
									products_image  = '" .$products_image."',
									products_model  = '" .$products_model."',
									products_urlname  = '" .$products_urlname."',
									products_status = '" .$products_status."',									
									products_last_modified = '".CURRENT_TIMESTAMP."',
									featured_products= '" .$featured_products."',
									agency_id  = '".(int)$agency_id."',
									regions_id =  '".(int)$regions_id."',
									departure_city_id = '".(int)$departure_city_id."',
									products_durations ='".$products_durations."',
									products_price = '".number_format($products_price,2,'.','')."',
									products_is_regular_tour = '".$products_is_regular_tour."'
									WHERE 
									products_id = '$products_id'";
						//echo $update_product_table."</br>"; 																			
					 	$result = tep_db_query($update_product_table);
						//echo "</br>";
						
						////////scs  update  product description table BOF //////////////
						$update_product_dis_table  = "UPDATE ".TABLE_PRODUCTS_DESCRIPTION." SET
									products_description = '".tep_db_input($products_description)."',
									products_name = '".tep_db_input($products_name)."',
									products_url = '".tep_db_input($products_url)."',									
									products_head_title_tag = '".tep_db_input($products_head_title_tag)."',
									products_head_desc_tag = '".tep_db_input($products_head_desc_tag)."',
									products_head_keywords_tag = '".tep_db_input($products_head_keywords_tag)."'									
									WHERE 
									products_id = '$products_id'";
						//echo $update_product_dis_table ;
						$result = tep_db_query($update_product_dis_table);
						MCache::update_product($products_id);//MCache update
						//echo "</br>";

						////////scs  update  product to categories table BOF //////////////
						$check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$categories_id . "'");
						$check = tep_db_fetch_array($check_query);
						if ($check['total'] < '1'){
							$update_ptc_table = "UPDATE ".TABLE_PRODUCTS_TO_CATEGORIES." SET 
									categories_id   = '".$categories_id."' 
									WHERE 
									products_id = '".$products_id."'";
							//echo 	$update_ptc_table;
							$result = tep_db_query($update_ptc_table);
						}
						//echo "</br>";
					}else{
						echo "<b><font color='red'>category ". tep_db_prepare_input($category_name) ." not found, update failed.</b></font></br>";
					}
				}
				else
				{
					//echo "insert";
					/// scs added: find max categories id////////
					$max_sql = "SELECT MAX(products_id) max FROM ".TABLE_PRODUCTS;
					$max_result = tep_db_query($max_sql);
					$max_row =  tep_db_fetch_array($max_result);
					$products_id = $max_row['max']+1;
					/// scs added: find max categories id////
					echo "</br>";
					echo "<b>Insert</b><br>";
					echo "Products Model:-".$products_model."</br>";
					echo "Products Name:-".$products_name."</br>";
												
				
					if($categories_id !=''){											
						////////scs  insert  product table BOF //////////////			
						$insert_product_sql = "INSERT INTO ".TABLE_PRODUCTS."
										(products_id,
										products_image,
										products_model,
										products_urlname,
										products_status,
										featured_products,
										agency_id,
										regions_id,
										departure_city_id,
										products_durations,
										products_price,
										products_is_regular_tour,
										products_date_added )
										VALUES ( '".(int)$products_id."',
												'" .$products_image."',
												'" .$products_model."',
												'" .$products_urlname."',									
												'" .$products_status."',
												'" .$featured_products."',
												'".(int)$agency_id."',
												'".(int)$regions_id."',
												'".(int)$departure_city_id."',
												'".$products_durations."', 
												'".number_format($products_price,2,'.','')."',
												'".$products_is_regular_tour."',
												'".CURRENT_TIMESTAMP."')";
						//echo $insert_product_sql;
						$result = tep_db_query($insert_product_sql);
						//echo "</br>";
						////////insert in product  table EOF: scs added: //////////
					
						////////insert in products desc table BOF: scs added: //////////
						$insert_product_dis = "INSERT INTO ".TABLE_PRODUCTS_DESCRIPTION."
										(products_id,
										products_description,
										products_name,
										products_url,																	
										products_head_title_tag,
										products_head_desc_tag,
										products_head_keywords_tag
										)
										VALUES (
												'".(int)$products_id."',
												'".tep_db_input($products_description)."',
												'".tep_db_input($products_name)."',
												 '".tep_db_input($products_url)."',
												'".tep_db_input($products_head_title_tag). "',
												'" .tep_db_input($products_head_desc_tag)."',
												'".tep_db_input($products_head_keywords_tag)."'
											)";
						//echo $insert_product_dis;
						$result = tep_db_query($insert_product_dis);					
						//echo "</br>";
						////////insert in products  desc  table EOF: scs//////////
						MCache::update_product($products_id);//MCache update
						
						////////insert in procduct to category table BOF: scs added: //////////
						$check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$categories_id . "'");
						$check = tep_db_fetch_array($check_query);
						if ($check['total'] < '1'){
							$insert_ptc_sql = "INSERT INTO ".TABLE_PRODUCTS_TO_CATEGORIES."
										(categories_id ,
										products_id 
										)
										VALUES (
												'".(int)$categories_id."',
												'".(int)$products_id."'											
												)";
							//echo $insert_ptc_sql;											
							$result = tep_db_query($insert_ptc_sql); 
						}
						//echo "</br>";
						////////insert in product to category table BOF: scs added: //////////
					}else{
						echo "<b><font color=red>category ". tep_db_prepare_input($category_name) ." not found, insert failed.</b></font></br>";
				  	}	
				} 
			  }
			}
			echo "<br><b>Data Import Successfully.</b>";
			echo "</br></br><a href='javascript:history.go(-1)'><img src='includes/languages/english/images/buttons/button_back.gif' border='0' alt='Back' title=' Back '></a>";
			exit;
			
			
			
			}
			////////////EOF: SCS Added:  Import BOF:  Import Product data  /////////////////////			
			
			
			/////////////  BOF:  Import product to store data  ////////////////////////
		if($importdata == "uploadproductstores") {

			foreach($readed as $key => $val)
			{
				$categories_id = '';
				$manufacturers_id = '';
				$rawdata = explode($separator, $val);
				//$rawdata = str_replace("'",'`',$rawdata);
				$rawdata = str_replace("\\'","\'",$rawdata);
				$rawdata = str_replace('"','',$rawdata);

				if(trim($rawdata[0]) != '' || trim($rawdata[0]) != 0){
				$sql = "select * from " . TABLE_PRODUCTS. " where products_model='".trim($rawdata[0])."'";	
				$rs = tep_db_query($sql);
				
				$products_model = trim($rawdata[0]);
				$products_name = trim($rawdata[1]);
				$stores_name = tep_db_input(trim($rawdata[2])); 
				$stores_display = tep_db_prepare_input(trim($rawdata[2])); 			
				$stores_name = str_replace("'","\\''",$stores_name);
				$products_image  = trim($rawdata[3]);
				$category_name = str_replace("'","\'",$rawdata[4]);
				$products_brief = trim($rawdata[5]);
				$products_description = trim($rawdata[6]);
				$products_url = trim($rawdata[7]);
				$products_price = trim($rawdata[8]);
				$products_sale_price = trim($rawdata[9]);
				$products_status = trim($rawdata[10]);				
				$manufacturer_name = trim($rawdata[11]);
				$products_urlname = trim($rawdata[12]);
				$products_head_title_tag = trim($rawdata[13]);
				$products_head_desc_tag = trim($rawdata[14]);
				$products_head_keywords_tag = trim($rawdata[15]);
				$stores_id = '';
				$categories_id = '';
				/*
				///find manufacture and category id from data bof//////////////
					$finddata = tep_db_query("select c.categories_id, m.manufacturers_id from " . TABLE_CATEGORIES_DESCRIPTION . " as c, " . TABLE_MANUFACTURERS . " as where c.categories_name = '$categories_name' AND m.manufacturers_name = '$manufacturers_name'");

    				while ($catemanu = tep_db_fetch_array($finddata)) {
										
					$categories_id = $catemanu['categories_id'];
					$manufacturers_id  = $catemanu['manufacturers_id'];
					
					} 
					///find store id from data  end////////
				*/
				
				///finde product id from product model /////////
				$findprod = tep_db_query("select products_id  from " . TABLE_PRODUCTS . "  where products_model = '".$products_model."'");
				if ($prodid = tep_db_fetch_array($findprod)) {
					$products_id =  $prodid['products_id'];
					
				}						
				//////////end ////////////////////////
			//find store id ////
			
				$findstore = tep_db_query("select stores_id  from " . TABLE_STORES . "  where stores_name = '".trim($stores_name)."'");
				if ($store = tep_db_fetch_array($findstore)) {
					$stores_id =  $store['stores_id'];
				}
				
			//find store id ////
				$finddata = tep_db_query("select categories_id  from " . TABLE_CATEGORIES_DESCRIPTION . "  where categories_name = '".$category_name."'");
				if ($category = tep_db_fetch_array($finddata)) {
					$categories_id =  $category['categories_id'];
					
				}
				
				if($manufacturer_name == 'None' || $manufacturer_name == ''){				
				 	$manufacturers_id = 0;
					$manufacturer_name = '';
				}
				
				
				
				if($manufacturer_name != ''){
				$manufacturer_name = str_replace("'","\'",$manufacturer_name);								
				$findmid= tep_db_query("select manufacturers_id  from " . TABLE_MANUFACTURERS . "  where manufacturers_name = '".$manufacturer_name."'");
				if ($manu = tep_db_fetch_array($findmid)) {
					$manufacturers_id =  $manu['manufacturers_id'];
				}
				}
				
				
				
				if ( $manufacturers_id == '' && $manufacturer_name != ''  ){
			
				
				/// scs added: find max  manufacturers_id  BOF///////
					$max_sql = "SELECT MAX( manufacturers_id) max FROM ".TABLE_MANUFACTURERS;
					$max_result = tep_db_query($max_sql);
					$max_row =  tep_db_fetch_array($max_result);
					$manufacturers_id  = $max_row['max']+1;
					/// scs added: find max  manufacturers_id  EOF////
					
					//scs added: insert new record of manufature BOF ////
					
					$insert_manu_sql = "INSERT INTO ".TABLE_MANUFACTURERS."
									(manufacturers_id,
									manufacturers_name,
									date_added 
									)
									VALUES (
											'".(int)$manufacturers_id."',
											'".$manufacturer_name."',
											'".CURRENT_TIMESTAMP."'											
											)";
															
					$result = tep_db_query($insert_manu_sql);
				//	echo "</br>";
					//scs added: insert new record of manufature EOF ////
					//scs added: insert new record of manufature info  BOF ////
					
					$insert_manu_info_sql = "INSERT INTO ".TABLE_MANUFACTURERS_INFO."
									(manufacturers_id,								
									date_last_click 
									)
									VALUES (
											'".(int)$manufacturers_id."',										
											'".CURRENT_TIMESTAMP."'										
											)";
															
					$result = tep_db_query($insert_manu_info_sql);
					//scs added: insert new record of manufature info  EOF ////
					echo "<b>insert</b><br>";
					echo "manufacturers_id:-".$manufacturers_id."</br>";
					echo "manufacturers Name:-".tep_db_input($manufacturer_name)."</br>";
					
					//scs added : insert new record of EOF BOF ////
			
				}
				
			
				
					
				if ($rsdata = tep_db_fetch_array($rs))
				{
				//	echo "update <br>";
				echo "</br>";
				echo "<b>Update</b><br>";
				echo "Products Model:-".$products_model."</br>";
				echo "Products Name:-".$products_name."</br>";
				echo "Store Name:-".$stores_display."</br>";
				
				if($categories_id == '' ||  $stores_id == '' ){
					echo "<b><font color=red>Please check category ". tep_db_prepare_input($category_name) ." or stores name $stores_display not found, update failed.</b></font></br>";
				}else{			
				$count = 0;
					/// scs BOF check product exit in two stoe//////
				$findproducts = tep_db_query("select products_id  from " . TABLE_PRODUCTS_TO_STORES . "  where products_id = '".$products_id."'");
				while ($store_prod = tep_db_fetch_array($findproducts)) {
					$count=$count+1;
				}
					
					////scs EOF check product exit in two store//////
					//scs update stores 
					if ( $count > 1) {					
					$findstoreckeck = tep_db_query("select *  from " . TABLE_PRODUCTS_TO_STORES . "  where products_id = '".$products_id."' AND stores_id  = '" .$stores_id."'");
					if ($store_check = tep_db_fetch_array($findstoreckeck)) {
					
					$update_pts_table  = "UPDATE ".TABLE_PRODUCTS_TO_STORES." SET
									products_url  = '" . $products_url."',
									products_price  = '" . $products_price."',
									products_sale_price   = '" . $products_sale_price."',
									products_status = '".$products_status ."'
									WHERE 
									products_id = '$products_id' AND stores_id  = '" .$stores_id."'";
									
					//echo $update_pts_table;																	
					$result = tep_db_query($update_pts_table);
					//echo "</br>";
					}else{
					echo "Products Model $products_model Added to Store $stores_display "."</br>";
					$insert_store_to_product = "INSERT INTO ".TABLE_PRODUCTS_TO_STORES."
									(stores_id,
									products_id,
									products_url,
									products_price,
									products_sale_price,
									products_status )
									VALUES ( '".(int)$stores_id."',
											'".(int)$products_id."',
											'" .tep_db_input($products_url)."',
											'" .$products_price."',
											'" .$products_sale_price."',											
											'".$products_status."')";
						
					// echo $insert_store_to_product;
					$result = tep_db_query($insert_store_to_product);
					// echo "</br>";
					
					}
					}else {				
					////////scs  update  product table BOF //////////////
					$update_product_table  = "UPDATE ".TABLE_PRODUCTS." SET
									products_image  = '" .$products_image."',
									products_model  = '" . $products_model."',
									products_urlname  = '" .tep_db_input($products_urlname)."',
									manufacturers_id   = '" . $manufacturers_id."',
									products_last_modified = '".CURRENT_TIMESTAMP."'
									WHERE 
									products_id = '$products_id'";
									
					// echo $update_product_table;
																	
					$result = tep_db_query($update_product_table);
					// echo "</br>";
					
					////////scs  update  product table EOF//////////////
					
					////////scs  update  product description table BOF //////////////
					$update_product_dis_table  = "UPDATE ".TABLE_PRODUCTS_DESCRIPTION." SET
									products_brief = '".tep_db_input($products_brief)."',
									products_description  = '".tep_db_input($products_description)."',
									products_name = '".tep_db_input($products_name)."',
									products_head_title_tag = '" .tep_db_input($products_head_title_tag)."',
									products_head_desc_tag = '" .tep_db_input($products_head_desc_tag)."',
									products_head_keywords_tag = '" .tep_db_input($products_head_keywords_tag)."'									
									WHERE 
									products_id = '$products_id'";
									
					// echo $update_product_dis_table ;
					$result = tep_db_query($update_product_dis_table);
					// echo "</br>";
					MCache::update_product($products_id);//MCache update
					
					
					////////scs  update  category table EOF//////////////
					$check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$categories_id . "'");
					$check = tep_db_fetch_array($check_query);
					if ($check['total'] < '1'){
						$update_ptc_table = "UPDATE ".TABLE_PRODUCTS_TO_CATEGORIES." SET
									categories_id   = '".$categories_id."'
									WHERE 
									products_id = '".$products_id."'";
						// echo $update_ptc_table ;
						$result = tep_db_query($update_ptc_table);
					}
					// echo "</br>";
			
					
					$findstoreckeck = tep_db_query("select *  from " . TABLE_PRODUCTS_TO_STORES . "  where products_id = '".$products_id."' AND stores_id  = '" .$stores_id."'");
					if ($store_check = tep_db_fetch_array($findstoreckeck)) {
					
					$update_pts_table  = "UPDATE ".TABLE_PRODUCTS_TO_STORES." SET
									products_url  = '" . tep_db_input($products_url)."',
									products_price  = '" . $products_price."',
									products_sale_price   = '" . $products_sale_price."',
									products_status = '".$products_status ."'
									WHERE 
									products_id = '$products_id' AND stores_id  = '" .$stores_id."'";
									
					//echo $update_pts_table;
					//echo $update_pts_table;																	
					$result = tep_db_query($update_pts_table);
					//echo "</br>";
					} else {
					echo "Products Model $products_model Added to Store $stores_display "."</br>";
					$insert_store_to_product = "INSERT INTO ".TABLE_PRODUCTS_TO_STORES."
									(stores_id,
									products_id,
									products_url,
									products_price,
									products_sale_price,
									products_status )
									VALUES ( '".(int)$stores_id."',
											'".(int)$products_id."',
											'" .tep_db_input($products_url)."',
											'" .$products_price."',
											'" .$products_sale_price."',											
											'".$products_status."')";
						
					//echo $insert_store_to_product;
					$result = tep_db_query($insert_store_to_product);
					//echo "</br>";
					
					}
					
					
					}
				  }
				}
				else
				{
					//echo "insert";
					
					/// scs added: find max categories id////////
					$max_sql = "SELECT MAX(products_id) max FROM ".TABLE_PRODUCTS;
					$max_result = tep_db_query($max_sql);
					$max_row =  tep_db_fetch_array($max_result);
					$products_id = $max_row['max']+1;
					/// scs added: find max categories id////
				echo "</br>";	
				echo "<b>Insert</b><br>";
				echo "Products Model:-".$products_model."</br>";
				echo "Products Name:-".$products_name."</br>";
				echo "Store Name:-".$stores_display."</br>";
				
				if($categories_id == '' ||  $stores_id == '' ){
					echo "<b><font color=red>category ". tep_db_prepare_input($category_name) ." or stores name $stores_display not found, insert failed.</b></font></br>";
				}else{
					////////scs  insert  product table BOF //////////////			
					$insert_product_sql = "INSERT INTO ".TABLE_PRODUCTS."
									(products_id,
									products_image,
									products_model,
									products_urlname,
									manufacturers_id,
									products_date_added )
									VALUES ( '".(int)$products_id."',
											'" .$products_image."',
											'" .tep_db_input($products_model)."',
											'" .tep_db_input($products_urlname)."',
											'" . $manufacturers_id."',
											'".CURRENT_TIMESTAMP."')";
						
					//echo $insert_product_sql;
					$result = tep_db_query($insert_product_sql);
				//	echo "</br>";
					////////insert in product  table EOF: scs added: //////////
				
				////////insert in products desc table BOF: scs added: //////////
				$insert_product_dis = "INSERT INTO ".TABLE_PRODUCTS_DESCRIPTION."
									(products_id,
									products_brief,
									products_description,
									products_name, 								
									products_head_title_tag,
									products_head_desc_tag,
									products_head_keywords_tag
									)
									VALUES (
											'".(int)$products_id."',
											'".tep_db_input($products_brief)."',
											'".tep_db_input($products_description)."',
											'".tep_db_input($products_name)."',	
											'".tep_db_input($products_head_title_tag). "',
											'" .tep_db_input($products_head_desc_tag)."',
											'".tep_db_input($products_head_keywords_tag)."'
										)";
											
				//	echo $insert_product_dis;
					$result = tep_db_query($insert_product_dis);					
				//	echo "</br>";
					////////insert in products  desc  table EOF: scs//////////
					MCache::update_product($products_id);//MCache update
					////////insert in procduct to category table BOF: scs added: //////////
					$check_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$categories_id . "'");
					$check = tep_db_fetch_array($check_query);
					if ($check['total'] < '1'){
						$insert_ptc_sql = "INSERT INTO ".TABLE_PRODUCTS_TO_CATEGORIES."
									(categories_id ,
									products_id
									)
									VALUES (
											'".(int)$categories_id."',
											'".(int)$products_id."'											
											)";
						//echo $insert_ptc_sql;					
						$result = tep_db_query($insert_ptc_sql);
					}
					//echo "</br>";
					
					////////insert in product to category table BOF: scs added: //////////
					
					$insert_store_to_product = "INSERT INTO ".TABLE_PRODUCTS_TO_STORES."
									(stores_id,
									products_id,
									products_url,
									products_price,
									products_sale_price,
									products_status )
									VALUES ( '".(int)$stores_id."',
											'".(int)$products_id."',
											'" .tep_db_input($products_url)."',
											'" .$products_price."',
											'" .$products_sale_price."',											
											'".$products_status."')";
						
				//	echo $insert_store_to_product;
					$result = tep_db_query($insert_store_to_product);
				//	echo "</br>";


					
						
				}	
				} 
			  }
			}
			echo "<br><b>Data Import Successfully.</b>";
			
			echo "</br></br><a href='products.php?cPath=$backpath'><img src='includes/languages/english/images/buttons/button_back.gif' border='0' alt='Back' title=' Back '></a>";
			exit;
			
			}
			////////////EOF: SCS Added:  Import BOF:  Import  product to store data /////////////////////			
		
		
		
		
			
	}
	if ($localfile){
		// move the file to where we can work with it
		$file = tep_get_uploaded_file('usrfl');
		if (is_uploaded_file($file['tmp_name'])) {
			tep_copy_uploaded_file($file, DIR_FS_DOCUMENT_ROOT . $tempdir);
		}

		echo "<p class=smallText>";
		echo "Filename: " . $localfile . "<br>";

		// get the entire file into an array
		$readed = file(DIR_FS_DOCUMENT_ROOT . $tempdir . $localfile);
	}

	// now we string the entire thing together in case there were carriage returns in the data
	$newreaded = "";
	foreach ($readed as $read){
		$newreaded .= $read;
	}

	// now newreaded has the entire file together without the carriage returns.
	// if for some reason excel put qoutes around our EOREOR, remove them then split into rows
	$newreaded = str_replace('"EOREOR"', 'EOREOR', $newreaded);
	$readed = explode( $separator . 'EOREOR',$newreaded);


	// Now we'll populate the filelayout based on the header row.
	$theheaders_array = explode( $separator, $readed[0] ); // explode the first row, it will be our filelayout
	$lll = 0;
	$filelayout = array();
	foreach( $theheaders_array as $header ){
		$cleanheader = str_replace( '"', '', $header);
	//	echo "Fileheader was $header<br><br><br>";
		$filelayout[ $cleanheader ] = $lll++; //
	}
	unset($readed[0]); //  we don't want to process the headers with the data

	// now we've got the array broken into parts by the expicit end-of-row marker.
	array_walk($readed, 'walk');

}

if (is_uploaded_file($usrfl) && $split==1) {
	//*******************************
	//*******************************
	// UPLOAD AND SPLIT FILE
	//*******************************
	//*******************************
	// move the file to where we can work with it
	$file = tep_get_uploaded_file('usrfl');
	//echo "Trying to move file...";
	if (is_uploaded_file($file['tmp_name'])) {
		tep_copy_uploaded_file($file, DIR_FS_DOCUMENT_ROOT . $tempdir);
	}

	$infp = fopen(DIR_FS_DOCUMENT_ROOT . $tempdir . $usrfl_name, "r");

	//toprow has the field headers
	$toprow = fgets($infp,32768);

	$filecount = 1;

	echo "Creating file EP_Split" . $filecount . ".txt ...  ";
	$tmpfname = DIR_FS_DOCUMENT_ROOT . $tempdir . "EP_Split" . $filecount . ".txt";
	$fp = fopen( $tmpfname, "w+");
	fwrite($fp, $toprow);

	$linecount = 0;
	$line = fgets($infp,32768);
	while ($line){
		// walking the entire file one row at a time
		// but a line is not necessarily a complete row, we need to split on rows that have "EOREOR" at the end
		$line = str_replace('"EOREOR"', 'EOREOR', $line);
		fwrite($fp, $line);
		if (strpos($line, 'EOREOR')){
			// we found the end of a line of data, store it
			$linecount++; // increment our line counter
			if ($linecount >= $maxrecs){
				echo "Added $linecount records and closing file... <Br>";
				$linecount = 0; // reset our line counter
				// close the existing file and open another;
				fclose($fp);
				// increment filecount
				$filecount++;
				echo "Creating file EP_Split" . $filecount . ".txt ...  ";
				$tmpfname = DIR_FS_DOCUMENT_ROOT . $tempdir . "EP_Split" . $filecount . ".txt";
				//Open next file name
				$fp = fopen( $tmpfname, "w+");
				fwrite($fp, $toprow);
			}
		}
		$line=fgets($infp,32768);
	}
	echo "Added $linecount records and closing file...<br><br> ";
	fclose($fp);
	fclose($infp);

	echo "You can download your split files in the Tools/Files under /catalog/temp/";

}

?>
      </p>

      <table width="75%" border="2">
        <tr>
          <td width="75%" class="smallText">
           <FORM ENCTYPE="multipart/form-data" ACTION="easypopulate.php?split=0" METHOD=POST>
              <p>
              <div align = "left">
                <p><b>Upload EP File</b></p>
                <p>
                  <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="100000000">
                <p></p>
                  <input name="usrfl" type="file" size="50">
                  <input type="submit" name="buttoninsert" value="Insert into db"><br>
                </p>
              </div>

            </form>

           <FORM ENCTYPE="multipart/form-data" ACTION="easypopulate.php?split=1" METHOD=POST>
              <p>
              <div align = "left">
                <p><b>Split EP File</b></p>
                <p>
                  <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="1000000000">
                <p></p>
                  <input name="usrfl" type="file" size="50">
                  <input type="submit" name="buttonsplit" value="Split file"><br>
                </p>
              </div>

            </form>

           <FORM ENCTYPE="multipart/form-data" ACTION="easypopulate.php" METHOD=POST>
              <p>
              <div align = "left">
                <p><b>Import from Temp Dir (<? echo $tempdir; ?>)</b></p>
		<p class="smallText">
		<INPUT TYPE="text" name="localfile" size="50">
                  <input type="submit" name="buttoninsert" value="Insert into db"><br>
                </p>
              </div>

            </form>




		<p><b>Download EP and Froogle Files</b></p>

	      <!-- Download file links -  Add your custom fields here -->
	  <a href="exportcsv.php?download=stream&dltype=products">Download <b>Complete</b> tab-delimited .txt file to edit</a><br>
	  <a href="exportcsv.php?download=stream&dltype=priceqty">Download <b>Model/Price/Qty</b> tab-delimited .txt file to edit</a><br>
	  <a href="exportcsv.php?download=stream&dltype=category">Download <b>Model/Category</b> tab-delimited .txt file to edit</a><br>
	  <a href="exportcsv.php?download=stream&dltype=store">Download <b>Store</b> tab-delimited .txtfile to edit</a><br>
	  <a href="exportcsv.php?download=stream&dltype=coupon">Download <b>Store Coupon</b> tab-delimited .txt file to edit</a><br>
	  <a href="exportcsv.php?download=stream&dltype=deals">Download <b>Store Deals</b> tab-delimited .txt file to edit</a><br>
        <a href="exportcsv.php?download=stream&dltype=froogle">Download <b>Froogle</b> tab-delimited .txt file</a><br>

		<p><b>Create EP and Froogle Files in Temp Dir (<? echo $tempdir; ?>)</b></p>
	  <a href="exportcsv.php?download=tempfile&dltype=full">Create Complete tab-delimited .txtfile in temp dir</a><br>
          <a href="exportcsv.php?download=tempfile&dltype=priceqty">Create Model/Price/Qty tab-delimited .txt file in temp dir</a><br>
          <a href="exportcsv.php?download=tempfile&dltype=category">Create Model/Category tab-delimited .txt file in temp dir</a><br>
	    <a href="exportcsv.php?download=tempfile&dltype=store">Download <b>Store</b> tab-delimited .txt file to edit</a><br>
	  <a href="exportcsv.php?download=tempfile&dltype=coupon">Download <b>Store Coupon</b> tab-delimited .txt file to edit</a><br>
	  <a href="exportcsv.php?download=tempfile&dltype=deals">Download <b>Store Deals</b> tab-delimited .txt file to edit</a><br>
      <a href="exportcsv.php?download=tempfile&dltype=froogle">Create Froogle tab-delimited .txt file in temp dir</a><br>
	  </td>
	</tr>
      </table>
    </td>
 </tr>
</table>

<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<p>?</p>
<p>?</p><p><br>
</p></body>
</html>

<?php

function ep_get_languages() {
	$languages_query = tep_db_query("select languages_id, code from " . TABLE_LANGUAGES . " order by sort_order");
	// start array at one, the rest of the code expects it that way
	$ll =1;
	while ($ep_languages = tep_db_fetch_array($languages_query)) {
		//will be used to return language_id en language code to report in product_name_code instead of product_name_id
		$ep_languages_array[$ll++] = array(
					'id' => $ep_languages['languages_id'],
					'code' => $ep_languages['code']
					);
	}
	return $ep_languages_array;
};

function tep_get_tax_class_rate($tax_class_id) {
	$tax_multiplier = 0;
	$tax_query = tep_db_query("select SUM(tax_rate) as tax_rate from " . TABLE_TAX_RATES . " WHERE  tax_class_id = '" . $tax_class_id . "' GROUP BY tax_priority");
	if (tep_db_num_rows($tax_query)) {
		while ($tax = tep_db_fetch_array($tax_query)) {
			$tax_multiplier += $tax['tax_rate'];
		}
	}
	return $tax_multiplier;
};

function tep_get_tax_title_class_id($tax_class_title) {
	$classes_query = tep_db_query("select tax_class_id from " . TABLE_TAX_CLASS . " WHERE tax_class_title = '" . $tax_class_title . "'" );
	$tax_class_array = tep_db_fetch_array($classes_query);
	$tax_class_id = $tax_class_array['tax_class_id'];
	return $tax_class_id ;
}

function print_el( $item2 ) { 
	echo " | " . substr(strip_tags($item2), 0, 10);
};

function print_el1( $item2 ) {
	echo sprintf("| %'.4s ", substr(strip_tags($item2), 0, 80));
};
function ep_create_filelayout($dltype){
	global $filelayout, $filelayout_count, $filelayout_sql, $langcode, $fileheaders, $max_categories;
	// depending on the type of the download the user wanted, create a file layout for it.
	$fieldmap = array(); // default to no mapping to change internal field names to external.
	switch( $dltype ){
	case 'full':
		// The file layout is dynamically made depending on the number of languages
		$iii = 0;
		$filelayout = array(
			'v_products_model'		=> $iii++,
			'v_products_image'		=> $iii++,
			);

		foreach ($langcode as $key => $lang){
			$l_id = $lang['id'];
			// uncomment the head_title, head_desc, and head_keywords to use
			// Linda's Header Tag Controller 2.0
			//echo $langcode['id'] . $langcode['code'];
			$filelayout  = array_merge($filelayout , array(
					'v_products_name_' . $l_id		=> $iii++,
					'v_products_description_' . $l_id	=> $iii++,
					'v_products_url_' . $l_id	=> $iii++,
					'v_products_head_title_tag_'.$l_id	=> $iii++,
					'v_products_head_desc_tag_'.$l_id	=> $iii++,
					'v_products_head_keywords_tag_'.$l_id	=> $iii++,
					));
		}


		// uncomment the customer_price and customer_group to support multi-price per product contrib

		$filelayout  = array_merge($filelayout , array(
			'v_products_price'		=> $iii++,
			
			//EOF:Kevin Added: products_price_list
			'v_products_price_list' => $iii++,  
			//EOF:Kevin Added: products_price_list
			
			'v_products_weight'		=> $iii++,
			'v_date_avail'			=> $iii++,
			'v_date_added'			=> $iii++,
			'v_products_quantity'		=> $iii++,
			#'v_customer_price_1'		=> $iii++,
			#'v_customer_group_id_1'		=> $iii++,
			#'v_customer_price_2'		=> $iii++,
			#'v_customer_group_id_2'		=> $iii++,
			#'v_customer_price_3'		=> $iii++,
			#'v_customer_group_id_3'		=> $iii++,
			#'v_customer_price_4'		=> $iii++,
			#'v_customer_group_id_4'		=> $iii++,
			'v_manufacturers_name'		=> $iii++,			
			));

		// build the categories name section of the array based on the number of categores the user wants to have
		for($i=1;$i<$max_categories+1;$i++){
			$filelayout = array_merge($filelayout, array('categories_name_' . $i => $iii++));
		}

		$filelayout = array_merge($filelayout, array(
			'v_tax_class_title'		=> $iii++,
			'v_status'			=> $iii++,
			));

		$filelayout_sql = "SELECT
			p.products_id as v_products_id,
			p.products_model as v_products_model,
			p.products_image as v_products_image,
			p.products_price as v_products_price,			
			p.products_price_list as v_products_price_list,			
			p.products_weight as v_products_weight,
			p.products_date_available as v_date_avail,
			p.products_date_added as v_date_added,
			p.products_tax_class_id as v_tax_class_id,
			p.products_quantity as v_products_quantity,
			p.manufacturers_id as v_manufacturers_id,
			subc.categories_id as v_categories_id,
			p.products_status as v_status
			FROM
			".TABLE_PRODUCTS." as p,
			".TABLE_CATEGORIES." as subc,
			".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
			WHERE
			p.products_id = ptoc.products_id AND
			ptoc.categories_id = subc.categories_id
			";

		break;
	case 'priceqty':
		$iii = 0;
		// uncomment the customer_price and customer_group to support multi-price per product contrib
		$filelayout = array(
			'v_products_model'		=> $iii++,
			'v_products_price'		=> $iii++,
			//BOF: Kevin Added: product_list_price
			'v_products_price_list'     => $iii++,
			//BOF: Kevin Added: product_list_price
			'v_products_quantity'		=> $iii++,
			#'v_customer_price_1'		=> $iii++,
			#'v_customer_group_id_1'		=> $iii++,
			#'v_customer_price_2'		=> $iii++,
			#'v_customer_group_id_2'		=> $iii++,
			#'v_customer_price_3'		=> $iii++,
			#'v_customer_group_id_3'		=> $iii++,
			#'v_customer_price_4'		=> $iii++,
			#'v_customer_group_id_4'		=> $iii++,
				);
		$filelayout_sql = "SELECT
			p.products_id as v_products_id,
			p.products_model as v_products_model,
			p.products_price as v_products_price,
			p.products_price_list as v_products_price_list,
			p.products_quantity as v_products_quantity
			FROM
			".TABLE_PRODUCTS." as p
			";

		break;
		
		case 'rebates_transactions':
		$iii = 0;
		$filelayout = array(
			'customers_id'	=> $iii++,
			'transaction_id'	=> $iii++,
			'posting_date'     => $iii++,
			'corrected'	=> $iii++,
			'sale_amount'	=> $iii++,
			'commission' 	=> $iii++,
			'advertiser_id'	=> $iii++,
			'advertiser_name'	=> $iii++, 
			'order_id'	=> $iii++,
			'associated_transaction_id' => $iii++,
			'rebate_status' => $iii++,
			'rebate_balance' => $iii++,
			);
					
			$transaction_sql_raw = "SELECT
			rt.customers_id as customers_id,
			rt.transaction_id as transaction_id,
			rt.order_id as order_id,
			rt.transaction_type as transaction_type,
			rt.associated_transaction_id as associated_transaction_id
			FROM
			".TABLE_REBATES_TRANSACTION." as rt 
			WHERE rt.customers_id = ".$_GET['customers_id']; 
			
			$transaction_sql = tep_db_query($transaction_sql_raw);
			while ($transdata  = tep_db_fetch_array($transaction_sql )) {
			
				 if($transdata['order_id'] != '') {
				 
				 $fcheck = substr($transdata['transaction_id'],0,1);
				 
				  if($fcheck != 'L'){
				  $original_transaction_id = tep_get_original_transaction_id($transdata['order_id']);
				  }else{
						  if($transdata['transaction_type'] == 1){
						  $original_transaction_id = $transdata['transaction_id'];
						}else{
						  $original_transaction_id = $transdata['associated_transaction_id'];
						}
				  }	
				 $transdata['rebate_amount'] =  number_format(tep_get_rebates_amount_transaction_id($original_transaction_id),2,'.','');
				 $transdata['rebate_status'] =  tep_get_rebates_status_transaction_id($original_transaction_id);
				 
				}else{
				 $transdata['rebate_amount'] = number_format(tep_get_rebates_amount_transaction_id($transdata['transaction_id']),2,'.','');
				 $transdata['rebate_status'] =  tep_get_rebates_status_transaction_id($transdata['transaction_id']);
				
				}
						
				//make single query start
				$filelayout_sql .= "SELECT
				rt.customers_id as customers_id,
				rt.transaction_id as transaction_id,
				rt.posting_date as posting_date,
				rt.corrected as corrected,
				rt.sale_amount as sale_amount,
				rt.commission as commission,
				rt.advertiser_id as advertiser_id,
				rt.advertiser_name as advertiser_name,
				rt.order_id as order_id,
				rt.associated_transaction_id as associated_transaction_id,
				'".$transdata['rebate_status']."' as rebate_status,
				'".$transdata['rebate_amount']."' as rebate_balance
				FROM
				".TABLE_REBATES_TRANSACTION." as rt			
				WHERE
				rt.transaction_id = '".$transdata['transaction_id']."' AND 
				rt.customers_id = ".$_GET['customers_id']; 
				
				$filelayout_sql = $filelayout_sql." union ";			
				//make single query end			
			}
			
			$filelayout_sql = substr($filelayout_sql, 0, -6);
			
			
			
			
		break;

		case 'products':
		//scs added: product download BOF/////////////
		$iii = 0;
			$filelayout = array(
			'products_model'	=> $iii++,
			'products_name'		=> $iii++,
			'products_image'	=> $iii++,
			'products_category_name'	=> $iii++,
			'products_description'	=> $iii++,
			'products_urlname'	=> $iii++,
			'products_url'	=> $iii++,
			'is_regular_tour' => $iii++,
			'products_durations' => $iii++,
			'agency_name' => $iii++,
			'regions_name' => $iii++,
			'city' => $iii++,
			'products_price' => $iii++,
			'products_head_title_tag'	=> $iii++, 
			'products_head_desc_tag'	=> $iii++,
			'products_head_keywords_tag'	=> $iii++,
			'products_status'	=> $iii++,
			'featured_products'	=> $iii++,
			);
			
		if(isset($_GET['cPath']) && $_GET['cPath'] != '' && $_GET['cPath'] != 0)
		{
			
			
			$path = explode("_",$_GET['cPath']); 
			
			if($path[2] != ""){
			$path[1] = $path[2];
			//$path[0] = $path[1]
			}

			if ($path[1] == '')
					{
						$filelayout_sql = "";
						
						$finddata = tep_db_query("select categories_id  from  ".TABLE_CATEGORIES." where categories_id ='".$path[0]."' or parent_id ='".$path[0]."'");
						while ($parent = tep_db_fetch_array($finddata))
						
						 {
							
							if ($filelayout_sql != "")
							{					
								$filelayout_sql = $filelayout_sql." union ";
							}
								

								$filelayout_sql = $filelayout_sql." SELECT
								p.products_model  as products_model,
								pd.products_name as products_name, 
								p.products_image as  products_image,
								subc.categories_name as products_category_name,
								pd.products_description  as products_description,
								p.products_urlname as products_urlname,
								pd.products_url as products_url,
								p.products_is_regular_tour  as is_regular_tour,
								p.products_durations as products_durations,
								ta.agency_name as agency_name,
								rdis.regions_name as regions_name,
								tc.city as city,
								p.products_price as products_price,
								pd.products_head_title_tag as products_head_title_tag, 
								pd.products_head_desc_tag as products_head_desc_tag, 
								pd.products_head_keywords_tag as products_head_keywords_tag,
								p.products_status  as products_status,
								p.featured_products as featured_products			
								FROM
								".TABLE_PRODUCTS." as p,
								".TABLE_PRODUCTS_DESCRIPTION." as pd,
								".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc,
								".TABLE_CATEGORIES_DESCRIPTION." as subc,
								".TABLE_TRAVEL_AGENCY." as ta,
								".TABLE_REGIONS_DESCRIPTION." as  rdis,
								".TABLE_TOUR_CITY." as  tc
								WHERE
								p.products_id = pd.products_id AND								
								ptoc.categories_id = subc.categories_id AND
								ptoc.products_id = p.products_id AND
								ta.agency_id = p.agency_id AND
								rdis.regions_id =  p.regions_id AND
								tc.city_id = p.departure_city_id AND
								ptoc.categories_id = ".$parent['categories_id'];							
						}
							
					}
				else
					{
						$filelayout_sql = "SELECT
						p.products_model  as products_model,
						pd.products_name as products_name, 
						p.products_image as  products_image,
						subc.categories_name as products_category_name,						
						pd.products_description  as products_description,
						p.products_urlname as products_urlname,
						pd.products_url as products_url,
						p.products_is_regular_tour  as is_regular_tour,
						p.products_durations as products_durations,
						ta.agency_name as agency_name,
						rdis.regions_name as regions_name,
						tc.city as city,
						p.products_price as products_price,
						pd.products_head_title_tag as products_head_title_tag, 
						pd.products_head_desc_tag as products_head_desc_tag, 
						pd.products_head_keywords_tag as products_head_keywords_tag,
						p.products_status  as products_status,
						p.featured_products as featured_products				
						FROM
						".TABLE_PRODUCTS." as p,
						".TABLE_PRODUCTS_DESCRIPTION." as pd,						
						".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc,
						".TABLE_CATEGORIES_DESCRIPTION." as subc,
						".TABLE_TRAVEL_AGENCY." as ta,
						".TABLE_REGIONS_DESCRIPTION." as  rdis,
						".TABLE_TOUR_CITY." as  tc	
						WHERE
						p.products_id = pd.products_id AND						
						ptoc.categories_id = subc.categories_id AND
						ptoc.products_id = p.products_id AND
						ta.agency_id = p.agency_id AND
						rdis.regions_id =  p.regions_id AND
						tc.city_id = p.departure_city_id AND
						ptoc.categories_id = '".$path[1]."' order by pd.products_name
					";
				}
			
			}
			
			else {
			
			$filelayout_sql = "SELECT
			p.products_model  as products_model,
			pd.products_name as products_name, 
			p.products_image as  products_image,
			subc.categories_name as products_category_name,
			pd.products_description  as products_description,
			p.products_urlname as products_urlname,
			pd.products_url as products_url,
			p.products_is_regular_tour  as is_regular_tour,
			p.products_durations as products_durations,
			ta.agency_name as agency_name,
			rdis.regions_name as regions_name,
			tc.city as city,
			p.products_price as products_price,
			pd.products_head_title_tag as products_head_title_tag, 
			pd.products_head_desc_tag as products_head_desc_tag, 
			pd.products_head_keywords_tag as products_head_keywords_tag,
			p.products_status  as products_status,
			p.featured_products as featured_products		 									
			FROM
			".TABLE_PRODUCTS." as p,
			".TABLE_PRODUCTS_DESCRIPTION." as pd,			
			".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc,
			".TABLE_CATEGORIES_DESCRIPTION." as subc,
			".TABLE_TRAVEL_AGENCY." as ta,
			".TABLE_REGIONS_DESCRIPTION." as  rdis,
			".TABLE_TOUR_CITY." as  tc
			WHERE
			p.products_id = pd.products_id AND			
			ptoc.categories_id = subc.categories_id AND
			ta.agency_id = p.agency_id AND
			rdis.regions_id =  p.regions_id AND
			tc.city_id = p.departure_city_id AND
			ptoc.products_id = p.products_id order by pd.products_name
			";			
			}
			
		//scs added: procudtdownload   EOF//////////
		break;
		case 'city':
		//scs added: product download BOF/////////////
		$iii = 0;
			$filelayout = array(
			'ID'	=> $iii++,
			'CITY_NAME'		=> $iii++,
			'STATE_CODE'		=> $iii++,
			'STATE_NAME'		=> $iii++,
			'COUNTRY_NAME'		=> $iii++,
			'IS_ATTRACTIONS'	=> $iii++,
			);
			
			$filelayout_sql = "SELECT c.city_id as ID, c.city as CITY_NAME, c.is_attractions as IS_ATTRACTIONS, s.zone_code as STATE_CODE, s.zone_name as STATE_NAME, co.countries_name as COUNTRY_NAME FROM	".TABLE_TOUR_CITY." as c, " . TABLE_ZONES . " as s, " . TABLE_COUNTRIES . " as co where c.state_id = s.zone_id and s.zone_country_id = co.countries_id and c.city !='' order by city";			
		//scs added: procudtdownload   EOF//////////
		break;
		case 'productstostores':
		//scs added: procudt_to_store BOF/////////////
		
		$iii = 0;
			$filelayout = array(
			'products_model'		=> $iii++,
			'products_name'		=> $iii++,
			'stores_name'		=> $iii++,	
			'products_image'	=> $iii++,
			'products_category_name'	=> $iii++,
			'products_brief'	=> $iii++,
			'products_description'	=> $iii++,
			'products_url'	=> $iii++,
			'products_price' => $i++,
			'products_sale_price' => $i++,
			'products_status' => $i++,
			'products_model'	=> $iii++,
			'manufacturer'	=> $iii++,
			'products_urlname'	=> $iii++,
			'products_head_title_tag'	=> $iii++, 
			'products_head_desc_tag'	=> $iii++,
			'products_head_keywords_tag'	=> $iii++,
			);
			
			if ( isset($_GET['stores_id']) ) {
			
				$stores_id=$_GET['stores_id'];
				
				if (isset($_GET['storeprodcat']) && $_GET['storeprodcat'] != '' && $_GET['storeprodcat'] != 0 ){		
				$storeprodcat = $_GET['storeprodcat'];
				$filelayout_sql = "SELECT
						p.products_model as products_model,
						pd.products_name as products_name, 
						s.stores_name as stores_name,
						p.products_image as  products_image,
						subc.categories_name as products_category_name,
						pd.products_brief  as products_brief,
						pd.products_description  as products_description,
						ptos.products_url as products_url,
						ptos.products_price as products_price,
						ptos.products_sale_price as products_sale_price,
						ptos.products_status as products_status,
						p.products_model  as products_model ,
						m.manufacturers_name as manufacturer,
						p.products_urlname as products_urlname,
						pd.products_head_title_tag as products_head_title_tag, 
						pd.products_head_desc_tag as products_head_desc_tag, 
						pd.products_head_keywords_tag as products_head_keywords_tag 			
						FROM
						".TABLE_PRODUCTS." as p,
						".TABLE_STORES." as s,
						".TABLE_PRODUCTS_DESCRIPTION." as pd,
						".TABLE_MANUFACTURERS." as m,
						".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc,
						".TABLE_PRODUCTS_TO_STORES." as ptos,
						".TABLE_CATEGORIES_DESCRIPTION." as subc
						WHERE
						p.products_id = pd.products_id AND
						p.manufacturers_id =  m.manufacturers_id AND
						ptoc.categories_id = subc.categories_id AND
						ptoc.products_id = p.products_id AND
						ptos.products_id = p.products_id AND
						s.stores_id = ptos.stores_id AND
						ptos.stores_id = '".$stores_id."' AND ptoc.categories_id='".$storeprodcat."' order by pd.products_name
						";
				}else{	
						$filelayout_sql = "SELECT
						p.products_model as products_model,
						pd.products_name as products_name, 
						s.stores_name as stores_name,
						p.products_image as  products_image,
						subc.categories_name as products_category_name,
						pd.products_brief  as products_brief,
						pd.products_description  as products_description,
						ptos.products_url as products_url,
						ptos.products_price as products_price,
						ptos.products_sale_price as products_sale_price,
						ptos.products_status as products_status,
						p.products_model  as products_model ,
						m.manufacturers_name as manufacturer,
						p.products_urlname as products_urlname,
						pd.products_head_title_tag as products_head_title_tag, 
						pd.products_head_desc_tag as products_head_desc_tag, 
						pd.products_head_keywords_tag as products_head_keywords_tag 			
						FROM
						".TABLE_PRODUCTS." as p,
						".TABLE_STORES." as s,
						".TABLE_PRODUCTS_DESCRIPTION." as pd,
						".TABLE_MANUFACTURERS." as m,
						".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc,
						".TABLE_PRODUCTS_TO_STORES." as ptos,
						".TABLE_CATEGORIES_DESCRIPTION." as subc
						WHERE
						p.products_id = pd.products_id AND
						p.manufacturers_id =  m.manufacturers_id AND
						ptoc.categories_id = subc.categories_id AND
						ptoc.products_id = p.products_id AND
						ptos.products_id = p.products_id AND
						s.stores_id = ptos.stores_id AND
						ptos.stores_id = '".$stores_id."' order by pd.products_name
						";
					}	
			}else {
			
			if (isset($_GET['storeprodcat']) && $_GET['storeprodcat'] != '' && $_GET['storeprodcat'] != 0 ){		
				$storeprodcat = $_GET['storeprodcat'];
				
				$filelayout_sql = "SELECT
				p.products_model as products_model,
				pd.products_name as products_name, 
				s.stores_name as stores_name,
				p.products_image as  products_image,
				subc.categories_name as products_category_name,
				pd.products_brief  as products_brief,
				pd.products_description  as products_description,
				ptos.products_url as products_url,
				ptos.products_price as products_price,
				ptos.products_sale_price as products_sale_price,
				ptos.products_status as products_status,
				p.products_model  as products_model ,
				m.manufacturers_name as manufacturer,
				p.products_urlname as products_urlname,
				pd.products_head_title_tag as products_head_title_tag, 
				pd.products_head_desc_tag as products_head_desc_tag, 
				pd.products_head_keywords_tag as products_head_keywords_tag 			
				FROM
				".TABLE_PRODUCTS." as p,
				".TABLE_STORES." as s,
				".TABLE_PRODUCTS_DESCRIPTION." as pd,
				".TABLE_MANUFACTURERS." as m,
				".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc,
				".TABLE_PRODUCTS_TO_STORES." as ptos,
				".TABLE_CATEGORIES_DESCRIPTION." as subc
				WHERE
				p.products_id = pd.products_id AND
				p.manufacturers_id =  m.manufacturers_id AND
				ptoc.categories_id = subc.categories_id AND
				ptoc.products_id = p.products_id AND
				ptos.products_id = p.products_id AND
				s.stores_id = ptos.stores_id AND ptoc.categories_id='".$storeprodcat."' order by pd.products_name
				";

			}else{
			$filelayout_sql = "SELECT
				p.products_model as products_model,
				pd.products_name as products_name, 
				stores_name as stores_name,
				p.products_image as  products_image,
				subc.categories_name as products_category_name,
				pd.products_brief  as products_brief,
				pd.products_description  as products_description,
				ptos.products_url as products_url,
				ptos.products_price as products_price,
				ptos.products_sale_price as products_sale_price,
				ptos.products_status as products_status,
				p.products_model  as products_model ,
				m.manufacturers_name as manufacturer,
				p.products_urlname as products_urlname,
				pd.products_head_title_tag as products_head_title_tag, 
				pd.products_head_desc_tag as products_head_desc_tag, 
				pd.products_head_keywords_tag as products_head_keywords_tag 			
				FROM
				".TABLE_PRODUCTS." as p,
				".TABLE_STORES." as s,
				".TABLE_PRODUCTS_DESCRIPTION." as pd,
				".TABLE_MANUFACTURERS." as m,
				".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc,
				".TABLE_PRODUCTS_TO_STORES." as ptos,
				".TABLE_CATEGORIES_DESCRIPTION." as subc
				WHERE
				p.products_id = pd.products_id AND
				p.manufacturers_id =  m.manufacturers_id AND
				ptoc.categories_id = subc.categories_id AND
				ptoc.products_id = p.products_id AND
				ptos.products_id = p.products_id AND
				s.stores_id = ptos.stores_id order by pd.products_name
				";
			  }	
			}
		//scs added: procudt_to_store download   EOF//////////
		break;

		
	/*case 'store':
		/////////////////      scs  ///////////////////////
		$iii = 0;
		// uncomment the customer_price and customer_group to support multi-price per product contrib
		$filelayout = array(
			'v_stores_id'		=> $iii++,
			'v_stores_name'		=> $iii++,
			'v_stores_url'		=> $iii++,
			'v_stores_image'		=> $iii++,
			'v_stores_short_description'		=> $iii++,
			'v_stores_description'		=> $iii++,
			'v_stores_email_address'  	=> $iii++,
			'v_stores_telephone'	=> $iii++,
			'v_stores_fax' 		=> $iii++,
			'v_stores_street_address'  	=> $iii++,
			'v_stores_city' 	=> $iii++,
			'v_stores_post_code'	=> $iii++,
			'v_stores_state' 	=> $iii++,
			'v_languages_id'	=> $iii++,
			'v_status' 		=> $iii++,
			'v_feature_status' 	=> $iii++,
			 'v_index_feature_status' 	=> $iii++,			
			'v_stores_date_added'		=> $iii++,
			'v_stores_date_last_modified'		=> $iii++,			
			#'v_customer_group_id_2'		=> $iii++,
			#'v_customer_price_3'		=> $iii++,
			#'v_customer_group_id_3'		=> $iii++,
			#'v_customer_price_4'		=> $iii++,
			#'v_customer_group_id_4'		=> $iii++,
		);

		$filelayout_sql = "SELECT
			s.stores_id as v_stores_id,
			s.stores_name as v_stores_name,
			s.stores_image as v_stores_image,
			s.stores_url as v_stores_url,
			s.stores_short_description as v_stores_short_description,
			s.stores_description as v_stores_description,
			s.stores_email_address as v_stores_email_address,
			s.stores_telephone as v_stores_telephone,
			s.stores_fax as v_stores_fax,
			s.stores_street_address as v_stores_street_address,
			s.stores_city as v_stores_city,
			s.stores_post_code as v_stores_post_code,
			s.stores_state as v_stores_state,
			s.languages_id as v_languages_id,
			s.status as v_status,
			s.feature_status as v_feature_status,
			s.index_feature_status as v_index_feature_status,
			si.stores_date_added as v_stores_date_added ,
			si.stores_date_last_modified as v_stores_date_last_modified
			FROM
			".TABLE_STORES." as s,
			".TABLE_STORES_STATISTIC." as si
			WHERE
			s.stores_id = si.stores_id  
			";
		//// scs //////////
		break;*/
	
	case 'store':
		/////////////////      scs  ///////////////////////
		$iii = 0;
		// uncomment the customer_price and customer_group to support multi-price per product contrib
		$filelayout = array(
			'v_stores_name'		=> $iii++,
			'v_category_name'		=> $iii++,			
			'v_stores_url'		=> $iii++,
			'v_stores_urlname'		=> $iii++,
			'v_stores_image'		=> $iii++,
			'v_stores_short_description'		=> $iii++,
			'v_stores_description'		=> $iii++,
			'v_stores_epc'	=> $iii++,
			'v_status' 	=> $iii++,
			'v_stores_email_address'  	=> $iii++,
			'v_stores_telephone'	=> $iii++,
			'v_stores_fax' 		=> $iii++,
			'v_stores_street_address'  	=> $iii++,
			'v_stores_city' 	=> $iii++,
			'v_stores_post_code'	=> $iii++,
			'v_stores_state' 	=> $iii++,
			'v_feature_status' 	=> $iii++,
			'v_index_feature_status' 	=> $iii++,
			'v_stores_rebates' 	=> $iii++,
			'v_rebates_status' 	=> $iii++,
			'v_stores_network' 	=> $iii++,
			'v_stores_advertiser_id' 	=> $iii++,			
			'samscoupons_title' 	=> $iii++,
			'samscoupons_meta_keywords' 	=> $iii++,
			'samscoupons_meta_description' 	=> $iii++,
			'samscoupons_seo_copy1' 	=> $iii++,
			'samscoupons_seo_copy2' 	=> $iii++,
			'samsrebates_title' 	=> $iii++,
			'samsrebates_meta_keywords' 	=> $iii++,
			'samsrebates_meta_description' 	=> $iii++,
			'samsrebates_seo_copy1' 	=> $iii++,
			'samsrebates_seo_copy2' 	=> $iii++,						
		);
		
			/* with category name*/
		if(isset($_GET['page']))
		{
			$pageno=($_GET['page']-1)*MAX_DISPLAY_STORES_LIST;
			
			
			if (isset($_GET['storecat']) && $_GET['storecat'] != '' ){		
			$storecat = $_GET['storecat'];
			$filelayout_sql = "SELECT
				s.stores_id as v_stores_id,
				s.stores_name as v_stores_name,
				c.categories_name as v_category_name,
				s.stores_image as v_stores_image,
				s.stores_url as v_stores_url,
				s.stores_urlname as v_stores_urlname,
				s.stores_short_description as v_stores_short_description,
				s.stores_description as v_stores_description,
				s.stores_epc as v_stores_epc,
				s.status as v_status,
				s.stores_email_address as v_stores_email_address,
				s.stores_telephone as v_stores_telephone,
				s.stores_fax as v_stores_fax,
				s.stores_street_address as v_stores_street_address,
				s.stores_city as v_stores_city,
				s.stores_post_code as v_stores_post_code,
				s.stores_state as v_stores_state,
				s.feature_status as v_feature_status,
				s.index_feature_status as v_index_feature_status,
				s.stores_rebates as v_stores_rebates,
				s.rebates_status as v_rebates_status,
				s.stores_network as v_stores_network,
				s.advertiser_id as v_stores_advertiser_id,
				s.samscoupons_title as samscoupons_title,
				s.samscoupons_meta_keywords as samscoupons_meta_keywords,
				s.samscoupons_meta_description as samscoupons_meta_description,
				s.samscoupons_seo_copy1 as samscoupons_seo_copy1,
				s.samscoupons_seo_copy2 as samscoupons_seo_copy2,
				s.samsrebates_title as samsrebates_title,
				s.samsrebates_meta_keywords as samsrebates_meta_keywords,
				s.samsrebates_meta_description as samsrebates_meta_description,
				s.samsrebates_seo_copy1 as samsrebates_seo_copy1,
				s.samsrebates_seo_copy2 as samsrebates_seo_copy2
				FROM
				".TABLE_STORES." as s,
				".TABLE_CATEGORIES_DESCRIPTION." as c where s.category_id = c.categories_id AND s.category_id = $storecat order by s.stores_name		
				limit ".$pageno.",".MAX_DISPLAY_STORES_LIST."
				";
			
			}else {
			
			$filelayout_sql = "SELECT
				s.stores_id as v_stores_id,
				s.stores_name as v_stores_name,
				c.categories_name as v_category_name,
				s.stores_image as v_stores_image,
				s.stores_url as v_stores_url,
				s.stores_urlname as v_stores_urlname,
				s.stores_short_description as v_stores_short_description,
				s.stores_description as v_stores_description,
				s.stores_epc as v_stores_epc,
				s.status as v_status,
				s.stores_email_address as v_stores_email_address,
				s.stores_telephone as v_stores_telephone,
				s.stores_fax as v_stores_fax,
				s.stores_street_address as v_stores_street_address,
				s.stores_city as v_stores_city,
				s.stores_post_code as v_stores_post_code,
				s.stores_state as v_stores_state,
				s.feature_status as v_feature_status,
				s.index_feature_status as v_index_feature_status,
				s.stores_rebates as v_stores_rebates,
				s.rebates_status as v_rebates_status,
				s.stores_network as v_stores_network,
				s.advertiser_id as v_stores_advertiser_id,
				s.samscoupons_title as samscoupons_title,
				s.samscoupons_meta_keywords as samscoupons_meta_keywords,
				s.samscoupons_meta_description as samscoupons_meta_description,
				s.samscoupons_seo_copy1 as samscoupons_seo_copy1,
				s.samscoupons_seo_copy2 as samscoupons_seo_copy2,
				s.samsrebates_title as samsrebates_title,
				s.samsrebates_meta_keywords as samsrebates_meta_keywords,
				s.samsrebates_meta_description as samsrebates_meta_description,
				s.samsrebates_seo_copy1 as samsrebates_seo_copy1,
				s.samsrebates_seo_copy2 as samsrebates_seo_copy2				
				FROM
				".TABLE_STORES." as s,
				".TABLE_CATEGORIES_DESCRIPTION." as c where s.category_id = c.categories_id	order by s.stores_name		
				limit ".$pageno.",".MAX_DISPLAY_STORES_LIST."
				";
				}
		}
		else
		{
		
			if (isset($_GET['storecat']) && $_GET['storecat'] != '' ){
			$storecat = $_GET['storecat'];
			$filelayout_sql = "SELECT
			s.stores_id as v_stores_id,
			c.categories_name as v_category_name,
			s.stores_name as v_stores_name,
			s.stores_image as v_stores_image,
			s.stores_url as v_stores_url,
			s.stores_urlname as v_stores_urlname,
			s.stores_short_description as v_stores_short_description,
			s.stores_description as v_stores_description,
			s.stores_epc as v_stores_epc,
			s.status as v_status,
			s.stores_email_address as v_stores_email_address,
			s.stores_telephone as v_stores_telephone,
			s.stores_fax as v_stores_fax,
			s.stores_street_address as v_stores_street_address,
			s.stores_city as v_stores_city,
			s.stores_post_code as v_stores_post_code,
			s.stores_state as v_stores_state,
			s.feature_status as v_feature_status,
			s.index_feature_status as v_index_feature_status,
			s.stores_rebates as v_stores_rebates,
			s.rebates_status as v_rebates_status,
			s.stores_network as v_stores_network,
			s.advertiser_id as v_stores_advertiser_id,
			s.samscoupons_title as samscoupons_title,
			s.samscoupons_meta_keywords as samscoupons_meta_keywords,
			s.samscoupons_meta_description as samscoupons_meta_description,
			s.samscoupons_seo_copy1 as samscoupons_seo_copy1,
			s.samscoupons_seo_copy2 as samscoupons_seo_copy2,
			s.samsrebates_title as samsrebates_title,
			s.samsrebates_meta_keywords as samsrebates_meta_keywords,
			s.samsrebates_meta_description as samsrebates_meta_description,
			s.samsrebates_seo_copy1 as samsrebates_seo_copy1,
			s.samsrebates_seo_copy2 as samsrebates_seo_copy2
			FROM
			".TABLE_STORES." as s,
			".TABLE_CATEGORIES_DESCRIPTION." as c
			WHERE
			s.category_id = c.categories_id AND s.category_id = $storecat order by s.stores_name
			";
			}else{
			$filelayout_sql = "SELECT
			s.stores_id as v_stores_id,
			c.categories_name as v_category_name,
			s.stores_name as v_stores_name,
			s.stores_image as v_stores_image,
			s.stores_url as v_stores_url,
			s.stores_urlname as v_stores_urlname,
			s.stores_short_description as v_stores_short_description,
			s.stores_description as v_stores_description,
			s.stores_epc as v_stores_epc,
			s.status as v_status,
			s.stores_email_address as v_stores_email_address,
			s.stores_telephone as v_stores_telephone,
			s.stores_fax as v_stores_fax,
			s.stores_street_address as v_stores_street_address,
			s.stores_city as v_stores_city,
			s.stores_post_code as v_stores_post_code,
			s.stores_state as v_stores_state,
			s.feature_status as v_feature_status,
			s.index_feature_status as v_index_feature_status,
			s.stores_rebates as v_stores_rebates,
			s.rebates_status as v_rebates_status,
			s.stores_network as v_stores_network,
			s.advertiser_id as v_stores_advertiser_id,
			s.samscoupons_title as samscoupons_title,
			s.samscoupons_meta_keywords as samscoupons_meta_keywords,
			s.samscoupons_meta_description as samscoupons_meta_description,
			s.samscoupons_seo_copy1 as samscoupons_seo_copy1,
			s.samscoupons_seo_copy2 as samscoupons_seo_copy2,
			s.samsrebates_title as samsrebates_title,
			s.samsrebates_meta_keywords as samsrebates_meta_keywords,
			s.samsrebates_meta_description as samsrebates_meta_description,
			s.samsrebates_seo_copy1 as samsrebates_seo_copy1,
			s.samsrebates_seo_copy2 as samsrebates_seo_copy2
			FROM
			".TABLE_STORES." as s,
			".TABLE_CATEGORIES_DESCRIPTION." as c
			WHERE
			s.category_id = c.categories_id order by s.stores_name
			";
			
			}
		}
	
		
		////////////// scs //////////
		
		
		break;
	
		case 'coupon':
		/////////////////  scs  ///////////////////////
		//import export store coupon start////////
		$iii = 0;
		// uncomment the customer_price and customer_group to support multi-price per product contrib
		$filelayout = array(
			'coupon_id'		=> $iii++,
			'stores_name'		=> $iii++,
			'category_name'		=> $iii++,
			'coupon_title'		=> $iii++,
			'coupon_url'		=> $iii++,
			'coupon_code'		=> $iii++,
			'coupon_description'		=> $iii++,
			'coupon_type'		=> $iii++,
			'coupon_restriction'		=> $iii++,
			'coupon_expiration_date'=>$iii++,
			'coupon_status' 		=> $iii++,
			'feature_status' 		=> $iii++,
			'index_feature_status' 		=> $iii++,			
		);
		
	if(isset($_GET['storeid']))
	{
	
			if (isset($_GET['couponcat']) && $_GET['couponcat'] != '' ){
			$couponcat = $_GET['couponcat'];
			 $filelayout_sql = "SELECT
			c.coupon_id as coupon_id,
			s.stores_name as stores_name,
			d.categories_name as category_name,
			c.coupon_name as coupon_title,
			c.coupon_url as coupon_url,
			c.coupon_code as coupon_code,
			c.coupon_description as coupon_description,
			c.coupon_type as coupon_type,
			c.coupon_restriction as coupon_restriction,
			c.coupon_expire_date as coupon_expiration_date,
			c.status as coupon_status,
			c.feature_status as feature_status,
			c.index_feature_status as index_feature_status
			FROM
			".TABLE_STORES_COUPONS." as c,
			".TABLE_STORES." as s,
			".TABLE_CATEGORIES_DESCRIPTION." as d
			WHERE
			c.stores_id = '".$_GET['storeid']."' AND
			c.categories_id = d.categories_id  AND
			c.stores_id = s.stores_id  AND c.categories_id = $couponcat"; 
			}else{
			$filelayout_sql = "SELECT
			c.coupon_id as coupon_id,
			s.stores_name as stores_name,
			d.categories_name as category_name,
			c.coupon_name as coupon_title,
			c.coupon_url as coupon_url,
			c.coupon_code as coupon_code,
			c.coupon_description as coupon_description,
			c.coupon_type as coupon_type,
			c.coupon_restriction as coupon_restriction,
			c.coupon_expire_date as coupon_expiration_date,
			c.status as coupon_status,
			c.feature_status as feature_status,
			c.index_feature_status as index_feature_status
			FROM
			".TABLE_STORES_COUPONS." as c,
			".TABLE_STORES." as s,
			".TABLE_CATEGORIES_DESCRIPTION." as d
			WHERE
			c.stores_id = '".$_GET['storeid']."' AND
			c.categories_id = d.categories_id  AND
			c.stores_id = s.stores_id 			
			"; 
			}
		}
		else
		{
		
			if (isset($_GET['couponcat']) && $_GET['couponcat'] != '' ){
			$couponcat = $_GET['couponcat'];
			$filelayout_sql = "SELECT
			c.coupon_id as coupon_id,
			s.stores_name as stores_name,
			d.categories_name as category_name,
			c.coupon_name as coupon_title,
			c.coupon_url as coupon_url,
			c.coupon_code as coupon_code,
			c.coupon_description as coupon_description,
			c.coupon_type as coupon_type,
			c.coupon_restriction as coupon_restriction,
			c.coupon_expire_date as coupon_expiration_date,
			c.status as coupon_status,
			c.feature_status as feature_status,
			c.index_feature_status as index_feature_status
			FROM
			".TABLE_STORES_COUPONS." as c,
			".TABLE_STORES." as s,
			".TABLE_CATEGORIES_DESCRIPTION." as d
			WHERE
			c.categories_id = d.categories_id AND
			s.stores_id = c.stores_id AND c.categories_id = $couponcat
			";
			
			}else{			
			$filelayout_sql = "SELECT
			c.coupon_id as coupon_id,
			s.stores_name as stores_name,
			d.categories_name as category_name,
			c.coupon_name as coupon_title,
			c.coupon_url as coupon_url,
			c.coupon_code as coupon_code,
			c.coupon_description as coupon_description,
			c.coupon_type as coupon_type,
			c.coupon_restriction as coupon_restriction,
			c.coupon_expire_date as coupon_expiration_date,
			c.status as coupon_status,
			c.feature_status as feature_status,
			c.index_feature_status as index_feature_status
			FROM
			".TABLE_STORES_COUPONS." as c,
			".TABLE_STORES." as s,
			".TABLE_CATEGORIES_DESCRIPTION." as d
			WHERE
			c.categories_id = d.categories_id AND
			s.stores_id = c.stores_id 
			";
			}
		}
		//import export store coupon end////////
		//// scs //////////
		
		break;
	

	case 'deals':
	
	/*
	$sql_product = "select p.products_id,p.stores_id, pd.products_url,p.products_price,p.products_status,p.products_price_list from products 

as p, products_description as pd where p.products_id=pd.products_id  AND p.stores_id !=''";

$result = tep_db_query($sql_product);

while($row1 =tep_db_fetch_array($result)){

$products_id = $row1['products_id'];
$products_url = $row1['products_url'];
$products_price = $row1['products_price'];
$products_status = $row1['products_status']; 
$products_price_list = $row1['products_price_list'];
$stores_id = $row1['stores_id'];
	
	$sql_data_array = array('stores_id' => $stores_id,
								
							'products_id' => $products_id,
								
								'products_url'    => $products_url,

		                        'products_price' => $products_price,

								'products_sale_price'   => $products_price_list,
								
								
								'products_status'   => $products_status,
								
								);
								
		tep_db_perform(TABLE_PRODUCTS_TO_STORES, $sql_data_array);
					
	//$result = mysql_query($insert_sql);
	
					

}
echo "done";
exit;

*/

	/*temp	
			$sql_product = 'select products_id,products_url from products_description';
$result = tep_db_query($sql_product);

while($row1 =tep_db_fetch_array($result)){

$products_id = $row1['products_id'];
$products_url = $row1['products_url'];
	
	echo $products_id."</br>";
	echo $products_url."</br>";  
	$sql_data_array = array(	'products_url'  => $products_url,
  					);
	tep_db_perform(TABLE_PRODUCTS_TO_STORES, $sql_data_array, "update", "products_id = '" . (int)$products_id . "'");
	
	//$result = mysql_query($insert_sql);
}
echo "done";
exit;
temp dump end*/
			
		/////////////////  scs  ///////////////////////
		//import export store deals start////////
		$iii = 0;
		// uncomment the customer_price and customer_group to support multi-price per product contrib
		$filelayout = array(
			'deals_id' => $iii++,
			'stores_name'		=> $iii++,
			'category_name'		=> $iii++,
			'deals_headline'		=> $iii++,
			'deals_url'		=> $iii++,
			'deals_description'		=> $iii++,
			'deals_status'		=> $iii++,
			'deals_expire_date'		=> $iii++,
			);
 	if(isset($_GET['storeid']))
	{
	
			if (isset($_GET['dealscat']) && $_GET['dealscat'] != '' ){
				$dealscat = $_GET['dealscat'];
				$filelayout_sql = "SELECT
				d.deals_id as deals_id,
				s.stores_name as stores_name,
				cd.categories_name as category_name,
				d.deals_headline as deals_headline,
				d.deals_url as deals_url,
				d.deals_description as deals_description,
				d.deals_status as deals_status,
				d.deals_expire_date as deals_expire_date
				FROM
				".TABLE_STORES." as s,
				".TABLE_CATEGORIES_DESCRIPTION." as cd,
				".TABLE_STORES_DEALS." as d
				WHERE
				d.stores_id ='".$_GET['storeid']."'  AND
				cd.categories_id = d.categories_id  AND 
				s.stores_id = d.stores_id  AND d.categories_id = $dealscat			
				"; 

			}else {
				$filelayout_sql = "SELECT
				d.deals_id as deals_id,
				s.stores_name as stores_name,
				cd.categories_name as category_name,
				d.deals_headline as deals_headline,
				d.deals_url as deals_url,
				d.deals_description as deals_description,
				d.deals_status as deals_status,
				d.deals_expire_date as deals_expire_date
				FROM
				".TABLE_STORES." as s,
				".TABLE_CATEGORIES_DESCRIPTION." as cd,
				".TABLE_STORES_DEALS." as d
				WHERE
				d.stores_id ='".$_GET['storeid']."'  AND
				cd.categories_id = d.categories_id  AND 
				s.stores_id = d.stores_id 			
				"; 
			}
	}
	else
	{
			if (isset($_GET['dealscat']) && $_GET['dealscat'] != '' ){
			$dealscat = $_GET['dealscat'];
			$filelayout_sql = "SELECT
			d.deals_id as deals_id,
			s.stores_name as stores_name,
			cd.categories_name as category_name,
			d.deals_headline as deals_headline,
			d.deals_url as deals_url,
			d.deals_description as deals_description,
			d.deals_status as deals_status,
			d.deals_expire_date as deals_expire_date
			FROM
			".TABLE_STORES." as s,
			".TABLE_CATEGORIES_DESCRIPTION." as cd,
			".TABLE_STORES_DEALS." as d
			WHERE
			d.stores_id = s.stores_id AND
			cd.categories_id = d.categories_id AND d.categories_id = $dealscat
			";
			
			}else {
			$filelayout_sql = "SELECT
			d.deals_id as deals_id,
			s.stores_name as stores_name,
			cd.categories_name as category_name,
			d.deals_headline as deals_headline,
			d.deals_url as deals_url,
			d.deals_description as deals_description,
			d.deals_status as deals_status,
			d.deals_expire_date as deals_expire_date
			FROM
			".TABLE_STORES." as s,
			".TABLE_CATEGORIES_DESCRIPTION." as cd,
			".TABLE_STORES_DEALS." as d
			WHERE
			d.stores_id = s.stores_id AND
			cd.categories_id = d.categories_id 
			";
			}
		}	
		
					
			//import export store deals end////////
		//// scs //////////
		break;
	
		/*
	case 'category':
	
		// The file layout is dynamically made depending on the number of languages
		$iii = 0;
		$filelayout = array(
			'v_products_model'		=> $iii++,
		);

		// build the categories name section of the array based on the number of categores the user wants to have
		for($i=1;$i<$max_categories+1;$i++){
			$filelayout = array_merge($filelayout, array('categories_name_' . $i => $iii++));
		}


		$filelayout_sql = "SELECT
			p.products_id as v_products_id,
			p.products_model as v_products_model,
			subc.categories_id as v_categories_id
			FROM
			".TABLE_PRODUCTS." as p,
			".TABLE_CATEGORIES." as subc,
			".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc			
			WHERE
			p.products_id = ptoc.products_id AND
			ptoc.categories_id = subc.categories_id
			";
		break;
		*/
		
		case 'category':
		// The file layout is dynamically made depending on the number of languages
		
		/*
		$finddatacheck = tep_db_query("select categories_id from " . TABLE_CATEGORIES_DESCRIPTION ."");
		
		while ($checkparent = tep_db_fetch_array($finddatacheck)) {
				$check_categories_id = $checkparent['categories_id'];
		
		//check metatag entry to related category BOF
		
		 $meta_query = tep_db_query ("select title, keywords, description from " . TABLE_METATAGS . " where categories_id = '" . (int)$check_categories_id . "'");

         if (!tep_db_num_rows($meta_query)) {
		      $sql_data_array = array('categories_id' => $check_categories_id);
			  tep_db_perform(TABLE_METATAGS, $sql_data_array);
		 }
		
		// check metatag entry to related category EOF
		
		}
		*/
		
		$iii = 0;
		$filelayout = array(
			'categories_name' => $iii++,
			'parent_categories_name' => $iii++,
			'categories_heading_title' => $iii++, 
			'categories_urlname'	=> $iii++,
			'categories_description'	=> $iii++, 
			'categories_seo_description'	=> $iii++, 
			'categories_logo_alt_tag'	=> $iii++, 
			'categories_first_sentence' 	=> $iii++, 
			'sort_order'	=> $iii++, 
			'categories_head_title_tag'	=> $iii++, 
			'categories_head_desc_tag'	=> $iii++, 
			'categories_head_keywords_tag'	=> $iii++, 
			'categories_status'  => $iii++			
		);
		/*
		if(isset($_GET['cID']) && $_GET['cID'] != '' && isset($_GET['cPath']) && $_GET['cPath'] != ''){
			
			$findcat = tep_db_query("select categories_id, parent_id  from " . TABLE_CATEGORIES . "  where categories_id = '".$_GET['cID']."'");
				while ($cat = tep_db_fetch_array($findcat))
				{
					 $parent_id = $cat['parent_id'];					
				}
			if($parent_id == 0 || $parent_id == NULL){
			
			$_GET['cPath'] = $_GET['cID'];
			}else{
			
			$_GET['cPath'] = $parent_id."_".$_GET['cID'];
			}
			
						
		}
		
		exit;
		*/
		if(isset($_GET['cPath']) && $_GET['cPath'] != '' && $_GET['cPath'] != 0)
		{
		$temppath=$_GET['cPath'];
		$path = explode("_",$_GET['cPath']); 
		
		if($path[2] != ""){
			$path[1] = $path[2];
			//$path[0] = $path[1]
		}
		
	
		if ($path[1] ==  "")  {
		$filelayout_sql = "SELECT
			cdis.categories_name as categories_name, 
			cdis.categories_name as parent_categories_name,
			cdis.categories_heading_title as categories_heading_title,  
			subc.categories_urlname as categories_urlname,
			cdis.categories_description as categories_description,  
			cdis.categories_seo_description as categories_seo_description,  
			cdis.categories_logo_alt_tag  as categories_logo_alt_tag,
			cdis.categories_first_sentence as categories_first_sentence,  
			subc.sort_order as sort_order,
			cdis.categories_head_title_tag as categories_head_title_tag, 
			cdis.categories_head_desc_tag as categories_head_desc_tag, 
			cdis.categories_head_keywords_tag as categories_head_keywords_tag, 
			subc.categories_status as categories_status			
			FROM
			".TABLE_CATEGORIES." as subc,
			".TABLE_CATEGORIES_DESCRIPTION." as cdis		
			WHERE
			cdis.categories_id = subc.categories_id AND
			subc.parent_id=".$temppath." order by subc.sort_order, cdis.categories_name
			";
			}else{
			
			$filelayout_sql = "SELECT
			cdis.categories_name as categories_name, 
			cdis.categories_name as parent_categories_name,
			cdis.categories_heading_title as categories_heading_title,  
			subc.categories_urlname as categories_urlname,
			cdis.categories_description as categories_description,  
			cdis.categories_seo_description as categories_seo_description,  
			cdis.categories_logo_alt_tag  as categories_logo_alt_tag,
			cdis.categories_first_sentence as categories_first_sentence,  
			subc.sort_order as sort_order,
			cdis.categories_head_title_tag as categories_head_title_tag, 
			cdis.categories_head_desc_tag as categories_head_desc_tag, 
			cdis.categories_head_keywords_tag as categories_head_keywords_tag
			subc.categories_status as categories_status
			FROM
			".TABLE_CATEGORIES." as subc,
			".TABLE_CATEGORIES_DESCRIPTION." as cdis						
			WHERE
			cdis.categories_id = subc.categories_id AND
			subc.parent_id=".$path[1]." order by subc.sort_order, cdis.categories_name
			";
			
			
			}
			
			}
			else 
			{
			
			$filelayout_sql = "SELECT
			cdis.categories_name as categories_name, 
			cdis.categories_name as parent_categories_name,
			cdis.categories_heading_title as categories_heading_title,  
			subc.categories_urlname as categories_urlname,
			cdis.categories_description as categories_description,  
			cdis.categories_seo_description as categories_seo_description,  
			cdis.categories_logo_alt_tag  as categories_logo_alt_tag,
			cdis.categories_first_sentence as categories_first_sentence,  
			subc.sort_order as sort_order,
			cdis.categories_head_title_tag as categories_head_title_tag, 
			cdis.categories_head_desc_tag as categories_head_desc_tag, 
			cdis.categories_head_keywords_tag as categories_head_keywords_tag, 		
			subc.categories_status as categories_status
			FROM
			".TABLE_CATEGORIES." as subc,
			".TABLE_CATEGORIES_DESCRIPTION." as cdis			
			WHERE
			cdis.categories_id = subc.categories_id order by subc.parent_id, subc.sort_order, cdis.categories_name
			";
			}
			
				
						
				/*
		
		$filelayout_sql = "SELECT
			subc.categories_urlname as v_categories_urlname,
			cdis.categories_heading_title as v_categories_heading_title,  
			cdis.shopping_keywords as v_shopping_keywords,  
			cdis.categories_seo_desc as v_categories_seo_desc,  
			cdis.frist_sentence as v_frist_sentence,  
			subc.sort_order as v_sort_order,
			m.title as v_category_title, 
			m.description as v_category_description,
			m.keywords as v_category_keywords, 
			subc.categories_id as v_categories_id
			FROM
			".TABLE_CATEGORIES." as subc,
			".TABLE_CATEGORIES_DESCRIPTION." as cdis,
			".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc,
			".TABLE_METATAGS." as m			
			WHERE
			ptoc.categories_id = subc.categories_id AND
			cdis.categories_id = subc.categories_id AND
			m.categories_id = subc.categories_id
			";		
		*/	
		
		break;

	case 'froogle':
		// this is going to be a little interesting because we need
		// a way to map from internal names to external names
		//
		// Before it didn't matter, but with froogle needing particular headers,
		// The file layout is dynamically made depending on the number of languages
		$iii = 0;
		$filelayout = array(
			'v_froogle_products_url_1'			=> $iii++,
			);
		//
		// here we need to get the default language and put
		$l_id = 1; // dummy it in for now.
//		foreach ($langcode as $key => $lang){
//			$l_id = $lang['id'];
			$filelayout  = array_merge($filelayout , array(
					'v_froogle_products_name_' . $l_id		=> $iii++,
					'v_froogle_products_description_' . $l_id	=> $iii++,
					));
//		}
		$filelayout  = array_merge($filelayout , array(
			'v_products_price'		=> $iii++,
			'v_products_fullpath_image'	=> $iii++,
			'v_category_fullpath'		=> $iii++,
			'v_froogle_offer_id'		=> $iii++,
			'v_froogle_instock'		=> $iii++,
			'v_froogle_ shipping'		=> $iii++,
			'v_manufacturers_name'		=> $iii++,
			'v_froogle_ upc'		=> $iii++,
			'v_froogle_color'		=> $iii++,
			'v_froogle_size'		=> $iii++,
			'v_froogle_quantitylevel'	=> $iii++,
			'v_froogle_product_id'		=> $iii++,
			'v_froogle_manufacturer_id'	=> $iii++,
			'v_froogle_exp_date'		=> $iii++,
			'v_froogle_product_type'	=> $iii++,
			'v_froogle_delete'		=> $iii++,
			'v_froogle_currency'		=> $iii++,
				));
		$iii=0;
		$fileheaders = array(
			'product_url'		=> $iii++,
			'name'			=> $iii++,
			'description'		=> $iii++,
			'price'			=> $iii++,
			'image_url'		=> $iii++,
			'category'		=> $iii++,
			'offer_id'		=> $iii++,
			'instock'		=> $iii++,
			'shipping'		=> $iii++,
			'brand'			=> $iii++,
			'upc'			=> $iii++,
			'color'			=> $iii++,
			'size'			=> $iii++,
			'quantity'		=> $iii++,
			'product_id'		=> $iii++,
			'manufacturer_id'	=> $iii++,
			'exp_date'		=> $iii++,
			'product_type'		=> $iii++,
			'delete'		=> $iii++,
			'currency'		=> $iii++,
			);
		$filelayout_sql = "SELECT
			p.products_id as v_products_id,
			p.products_model as v_products_model,
			p.products_image as v_products_image,
			p.products_price as v_products_price,			
			
			p.products_price_list as v_products_price_list,
			
			p.products_weight as v_products_weight,
			p.products_date_added as v_date_avail,
			p.products_tax_class_id as v_tax_class_id,
			p.products_quantity as v_products_quantity,
			p.manufacturers_id as v_manufacturers_id,
			subc.categories_id as v_categories_id
			FROM
			".TABLE_PRODUCTS." as p,
			".TABLE_CATEGORIES." as subc,
			".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
			WHERE
			p.products_id = ptoc.products_id AND
			ptoc.categories_id = subc.categories_id
			";
		break;

	}
	$filelayout_count = count($filelayout);

}


function walk( $item1 ) {
	global $filelayout, $filelayout_count, $modelsize;
	global $active, $inactive, $langcode, $default_these, $deleteit, $zero_qty_inactive;
    global $epdlanguage_id, $price_with_tax, $replace_quotes;
	global $default_images, $default_image_manufacturer, $default_image_product, $default_image_category;
	global $separator, $max_categories;
	// first we clean up the row of data

	// chop blanks from each end
	$item1 = ltrim(rtrim($item1));

	// blow it into an array, splitting on the tabs
	$items = explode($separator, $item1);

	// make sure all non-set things are set to '';
	// and strip the quotes from the start and end of the stings.
	// escape any special chars for the database.
	foreach( $filelayout as $key=> $value){
		$i = $filelayout[$key];
		if (isset($items[$i]) == false) {
			$items[$i]='';
		} else {
			// Check to see if either of the magic_quotes are turned on or off;
			// And apply filtering accordingly.
			if (function_exists('ini_get')) {
				//echo "Getting ready to check magic quotes<br>";
				if (ini_get('magic_quotes_runtime') == 1){
					// The magic_quotes_runtime are on, so lets account for them
					// check if the last character is a quote;
					// if it is, chop off the quotes.
					if (substr($items[$i],-1) == '"'){
						$items[$i] = substr($items[$i],2,strlen($items[$i])-4);
					}
					// now any remaining doubled double quotes should be converted to one doublequote
					$items[$i] = str_replace('\"\"',"&#34",$items[$i]);
					if ($replace_quotes){
						$items[$i] = str_replace('\"',"&#34",$items[$i]);
						$items[$i] = str_replace("\'","&#39",$items[$i]);
					}
				} else { // no magic_quotes are on
					// check if the last character is a quote;
					// if it is, chop off the 1st and last character of the string.
					if (substr($items[$i],-1) == '"'){
						$items[$i] = substr($items[$i],1,strlen($items[$i])-2);
					}
					// now any remaining doubled double quotes should be converted to one doublequote
					$items[$i] = str_replace('""',"&#34",$items[$i]);
					if ($replace_quotes){
						$items[$i] = str_replace('"',"&#34",$items[$i]);
						$items[$i] = str_replace("'","&#39",$items[$i]);
					}
				}
			}
		}
	}
/*
	if ( $items['v_status'] == $deleteit ){
		// they want to delete this product.
		echo "Deleting product " . $items['v_products_model'] . " from the database<br>";
		// Get the ID

		// kill in the products_to_categories

		// Kill in the products table

		return; // we're done deleteing!
	}
*/
	// now do a query to get the record's current contents
	$sql = "SELECT
		p.products_id as v_products_id,
		p.products_model as v_products_model,
		p.products_image as v_products_image,
		p.products_price as v_products_price,		
		p.products_price_list as v_products_price_list,
		p.products_weight as v_products_weight,
		p.products_date_added as v_date_avail,
		p.products_tax_class_id as v_tax_class_id,
		p.products_quantity as v_products_quantity,
		p.manufacturers_id as v_manufacturers_id,
		subc.categories_id as v_categories_id
		FROM
		".TABLE_PRODUCTS." as p,
		".TABLE_CATEGORIES." as subc,
		".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
		WHERE
		p.products_id = ptoc.products_id AND
		p.products_model = '" . $items[$filelayout['v_products_model']] . "' AND
		ptoc.categories_id = subc.categories_id
		";

	$result = tep_db_query($sql);
	$row =  tep_db_fetch_array($result);


	while ($row){
		// OK, since we got a row, the item already exists.
		// Let's get all the data we need and fill in all the fields that need to be defaulted to the current values
		// for each language, get the description and set the vals
		foreach ($langcode as $key => $lang){
			//echo "Inside defaulting loop";
			//echo "key is $key<br>";
			//echo "langid is " . $lang['id'] . "<br>";
//			$sql2 = "SELECT products_name, products_description 
//				FROM ".TABLE_PRODUCTS_DESCRIPTION."
//				WHERE
//					products_id = " . $row['v_products_id'] . " AND
//					language_id = '" . $lang['id'] . "'
//				";
			$sql2 = "SELECT *
				FROM ".TABLE_PRODUCTS_DESCRIPTION."
				WHERE
					products_id = " . $row['v_products_id'] . " AND
					language_id = '" . $lang['id'] . "'
				";
			$result2 = tep_db_query($sql2);
			$row2 =  tep_db_fetch_array($result2);
                        // Need to report from ......_name_1 not ..._name_0
			$row['v_products_name_' . $lang['id']] 		= $row2['products_name'];
			$row['v_products_description_' . $lang['id']] 	= $row2['products_description'];
			$row['v_products_url_' . $lang['id']] 		= $row2['products_url'];

			// support for Linda's Header Controller 2.0 here
			if(isset($filelayout['v_products_head_title_tag_' . $lang['id'] ])){
				$row['v_products_head_title_tag_' . $lang['id']] 	= $row2['products_head_title_tag'];
				$row['v_products_head_desc_tag_' . $lang['id']] 	= $row2['products_head_desc_tag'];
				$row['v_products_head_keywords_tag_' . $lang['id']] 	= $row2['products_head_keywords_tag'];
			}
			// end support for Header Controller 2.0
		}

		// start with v_categories_id
		// Get the category description
		// set the appropriate variable name
		// if parent_id is not null, then follow it up.
		$thecategory_id = $row['v_categories_id'];

		for( $categorylevel=1; $categorylevel<$max_categories+1; $categorylevel++){
			if ($thecategory_id){
				$sql2 = "SELECT categories_name
					FROM ".TABLE_CATEGORIES_DESCRIPTION."
					WHERE
						categories_id = " . $thecategory_id . " AND
						language_id = " . $epdlanguage_id ;

				$result2 = tep_db_query($sql2);
				$row2 =  tep_db_fetch_array($result2);
				// only set it if we found something
				$temprow['categories_name_' . $categorylevel] = $row2['categories_name'];
				// now get the parent ID if there was one
				$sql3 = "SELECT parent_id
					FROM ".TABLE_CATEGORIES."
					WHERE
						categories_id = " . $thecategory_id;
				$result3 = tep_db_query($sql3);
				$row3 =  tep_db_fetch_array($result3);
				$theparent_id = $row3['parent_id'];
				if ($theparent_id != ''){
					// there was a parent ID, lets set thecategoryid to get the next level
					$thecategory_id = $theparent_id;
				} else {
					// we have found the top level category for this item,
					$thecategory_id = false;
				}
			} else {
					$temprow['categories_name_' . $categorylevel] = '';
			}
		}
		// temprow has the old style low to high level categories.
		$newlevel = 1;
		// let's turn them into high to low level categories
		for( $categorylevel=$max_categories+1; $categorylevel>0; $categorylevel--){
			if ($temprow['categories_name_' . $categorylevel] != ''){
				$row['categories_name_' . $newlevel++] = $temprow['categories_name_' . $categorylevel];
			}
		}

		if ($row['v_manufacturers_id'] != ''){
			$sql2 = "SELECT manufacturers_name
				FROM ".TABLE_MANUFACTURERS."
				WHERE
				manufacturers_id = " . $row['v_manufacturers_id']
				;
			$result2 = tep_db_query($sql2);
			$row2 =  tep_db_fetch_array($result2);
			$row['v_manufacturers_name'] = $row2['manufacturers_name'];
		}

		//elari -
		//We check the value of tax class and title instead of the id
		//Then we add the tax to price if $price_with_tax is set to true
		$row_tax_multiplier = tep_get_tax_class_rate($row['v_tax_class_id']);
		$row['v_tax_class_title'] = tep_get_tax_class_title($row['v_tax_class_id']);
		if ($price_with_tax){
			$row['v_products_price'] = $row['v_products_price'] + round($row['v_products_price']* $row_tax_multiplier / 100,2);
		}

		// now create the internal variables that will be used
		// the $$thisvar is on purpose: it creates a variable named what ever was in $thisvar and sets the value
		foreach ($default_these as $thisvar){
			$$thisvar	= $row[$thisvar];
		}

		$row =  tep_db_fetch_array($result);
	}

	// this is an important loop.  What it does is go thru all the fields in the incoming file and set the internal vars.
	// Internal vars not set here are either set in the loop above for existing records, or not set at all (null values)
	// the array values are handled separatly, although they will set variables in this loop, we won't use them.
	foreach( $filelayout as $key => $value ){
		$$key = $items[ $value ];
	}

        // so how to handle these?  we shouldn't built the array unless it's been giving to us.
	// The assumption is that if you give us names and descriptions, then you give us name and description for all applicable languages
	foreach ($langcode as $lang){
		//echo "Langid is " . $lang['id'] . "<br>";
		$l_id = $lang['id'];
		if (isset($filelayout['v_products_name_' . $l_id ])){
			//we set dynamically the language values
			$v_products_name[$l_id] 	= $items[$filelayout['v_products_name_' . $l_id]];
			$v_products_description[$l_id] 	= $items[$filelayout['v_products_description_' . $l_id ]];
			$v_products_url[$l_id] 		= $items[$filelayout['v_products_url_' . $l_id ]];
			// support for Linda's Header Controller 2.0 here
			if(isset($filelayout['v_products_head_title_tag_' . $l_id])){
				$v_products_head_title_tag[$l_id] 	= $items[$filelayout['v_products_head_title_tag_' . $l_id]];
				$v_products_head_desc_tag[$l_id] 	= $items[$filelayout['v_products_head_desc_tag_' . $l_id]];
				$v_products_head_keywords_tag[$l_id] 	= $items[$filelayout['v_products_head_keywords_tag_' . $l_id]];
			}
			// end support for Header Controller 2.0
		}
	}
	//elari... we get the tax_clas_id from the tax_title
	//on screen will still be displayed the tax_class_title instead of the id....
	if ( isset( $v_tax_class_title) ){
		$v_tax_class_id          = tep_get_tax_title_class_id($v_tax_class_title);
	}
	//we check the tax rate of this tax_class_id
        $row_tax_multiplier = tep_get_tax_class_rate($v_tax_class_id);

	//And we recalculate price without the included tax...
	//Since it seems display is made before, the displayed price will still include tax
	//This is same problem for the tax_clas_id that display tax_class_title
	if ($price_with_tax){
		$v_products_price        = round( $v_products_price / (1 + ( $row_tax_multiplier * $price_with_tax/100) ), 2);
	}

	// if they give us one category, they give us all 6 categories
	unset ($categories_name); // default to not set.
	if ( isset( $filelayout['categories_name_1'] ) ){
		$newlevel = 1;
		for( $categorylevel=6; $categorylevel>0; $categorylevel--){
			if ( $items[$filelayout['categories_name_' . $categorylevel]] != ''){
				$categories_name[$newlevel++] = $items[$filelayout['categories_name_' . $categorylevel]];
			}
		}
		while( $newlevel < $max_categories+1){
			$categories_name[$newlevel++] = ''; // default the remaining items to nothing
		}
	}

	if (ltrim(rtrim($v_products_quantity)) == '') {
		$v_products_quantity = 1;
	}
	if ($v_date_avail == '') {
		$v_date_avail = "CURRENT_TIMESTAMP";
	} else {
		// we put the quotes around it here because we can't put them into the query, because sometimes
		//   we will use the "current_timestamp", which can't have quotes around it.
		$v_date_avail = '"' . $v_date_avail . '"';
	}

	if ($v_date_added == '') {
		$v_date_added = "CURRENT_TIMESTAMP";
	} else {
		// we put the quotes around it here because we can't put them into the query, because sometimes
		//   we will use the "current_timestamp", which can't have quotes around it.
		$v_date_added = '"' . $v_date_added . '"';
	}


	// default the stock if they spec'd it or if it's blank
	$v_db_status = '1'; // default to active
	if ($v_status == $inactive){
		// they told us to deactivate this item
		$v_db_status = '0';
	}
	if ($zero_qty_inactive && $v_products_quantity == 0) {
		// if they said that zero qty products should be deactivated, let's deactivate if the qty is zero
		$v_db_status = '0';
	}

	if ($v_manufacturer_id==''){
		$v_manufacturer_id="NULL";
	}

	if (trim($v_products_image)==''){
		$v_products_image = $default_image_product;
	}

	if (strlen($v_products_model) > $modelsize ){
		echo "<font color='red'>" . strlen($v_products_model) . $v_products_model . "... ERROR! - Too many characters in the model number.<br>
			12 is the maximum on a standard OSC install.<br>
			Your maximum product_model length is set to $modelsize<br>
			You can either shorten your model numbers or increase the size of the field in the database.</font>";
		die();
	}

	// OK, we need to convert the manufacturer's name into id's for the database
	if ( isset($v_manufacturers_name) && $v_manufacturers_name != '' ){
		$sql = "SELECT man.manufacturers_id
			FROM ".TABLE_MANUFACTURERS." as man
			WHERE
				man.manufacturers_name = '" . $v_manufacturers_name . "'";
		$result = tep_db_query($sql);
		$row =  tep_db_fetch_array($result);
		if ( $row != '' ){
			foreach( $row as $item ){
				$v_manufacturer_id = $item;
			}
		} else {
			// to add, we need to put stuff in categories and categories_description
			$sql = "SELECT MAX(manufacturers_id) max FROM ".TABLE_MANUFACTURERS;
			$result = tep_db_query($sql);
			$row =  tep_db_fetch_array($result);
			$max_mfg_id = $row['max']+1;
			// default the id if there are no manufacturers yet
			if (!is_numeric($max_mfg_id) ){
				$max_mfg_id=1;
			}

			// Uncomment this query if you have an older 2.2 codebase
			/*
			$sql = "INSERT INTO ".TABLE_MANUFACTURERS."(
				manufacturers_id,
				manufacturers_name,
				manufacturers_image
				) VALUES (
				$max_mfg_id,
				'$v_manufacturers_name',
				'$default_image_manufacturer'
				)";
			*/

			// Comment this query out if you have an older 2.2 codebase
			$sql = "INSERT INTO ".TABLE_MANUFACTURERS."(
				manufacturers_id,
				manufacturers_name,
				manufacturers_image,
				date_added,
				last_modified
				) VALUES (
				$max_mfg_id,
				'$v_manufacturers_name',
				'$default_image_manufacturer',
				CURRENT_TIMESTAMP,
				CURRENT_TIMESTAMP
				)";
			$result = tep_db_query($sql);
			$v_manufacturer_id = $max_mfg_id;
		}
	}
	// if the categories names are set then try to update them
	if ( isset($categories_name_1)){
		// start from the highest possible category and work our way down from the parent
		$v_categories_id = 0;
		$theparent_id = 0;
		for ( $categorylevel=$max_categories+1; $categorylevel>0; $categorylevel-- ){
			$thiscategoryname = $categories_name[$categorylevel];
			if ( $thiscategoryname != ''){
				// we found a category name in this field

				// now the subcategory
				$sql = "SELECT cat.categories_id
					FROM ".TABLE_CATEGORIES." as cat, 
					     ".TABLE_CATEGORIES_DESCRIPTION." as des
					WHERE
						cat.categories_id = des.categories_id AND
						des.language_id = $epdlanguage_id AND
						cat.parent_id = " . $theparent_id . " AND
						des.categories_name = '" . $thiscategoryname . "'";
				$result = tep_db_query($sql);
				$row =  tep_db_fetch_array($result);
				if ( $row != '' ){
					foreach( $row as $item ){
						$thiscategoryid = $item;
					}
				} else {
					// to add, we need to put stuff in categories and categories_description
					$sql = "SELECT MAX( categories_id) max FROM ".TABLE_CATEGORIES;
					$result = tep_db_query($sql);
					$row =  tep_db_fetch_array($result);
					$max_category_id = $row['max']+1;
					if (!is_numeric($max_category_id) ){
						$max_category_id=1;
					}
					$sql = "INSERT INTO ".TABLE_CATEGORIES."(
						categories_id,
						categories_image,
						parent_id,
						sort_order,
						date_added,
						last_modified
						) VALUES (
						$max_category_id,
						'$default_image_category',
						$theparent_id,
						0,
						CURRENT_TIMESTAMP
						,CURRENT_TIMESTAMP
						)";
					$result = tep_db_query($sql);
					$sql = "INSERT INTO ".TABLE_CATEGORIES_DESCRIPTION."(
							categories_id,
							language_id,
							categories_name
						) VALUES (
							$max_category_id,
							'$epdlanguage_id',
							'$thiscategoryname'
						)";
					$result = tep_db_query($sql);
					$thiscategoryid = $max_category_id;
					if(USE_MCACHE) MCache::update_categories(array('method'=>'insert','categories_id'=>$categories_id)); //¸üÐÂ»º´æ-vincent-2011-4-22
				}
				// the current catid is the next level's parent
				$theparent_id = $thiscategoryid;
				$v_categories_id = $thiscategoryid; // keep setting this, we need the lowest level category ID later
			}
		}
	}

	if ($v_products_model != "") {
		//   products_model exists!
		array_walk($items, 'print_el');

		// First we check to see if this is a product in the current db.
		$result = tep_db_query("SELECT products_id FROM ".TABLE_PRODUCTS." WHERE (products_model = '". $v_products_model . "')");

		if (tep_db_num_rows($result) == 0)  {
			//   insert into products

			$sql = "SELECT MAX( products_id) max FROM ".TABLE_PRODUCTS;
			$result = tep_db_query($sql);
			$row =  tep_db_fetch_array($result);
			$max_product_id = $row['max']+1;
			if (!is_numeric($max_product_id) ){
				$max_product_id=1;
			}
			$v_products_id = $max_product_id;
			echo "<font color='green'> !New Product!</font><br>";
			
//EOF: Kevin Added: more image and list price
			$query = "INSERT INTO ".TABLE_PRODUCTS." (
					products_image,
					products_bimage,
					products_subimage1,
					products_bsubimage1,
					products_subimage2,
					products_bsubimage2,
					products_subimage3,
					products_bsubimage3,
					products_subimage4,
					products_bsubimage4,
					products_subimage5,
					products_bsubimage5,
					products_subimage6,
					products_bsubimage6,
					products_model,
					products_price,					
					products_price_list,					
					products_status,
					products_last_modified,
					products_date_added,
					products_date_available,
					products_tax_class_id,
					products_weight,
					products_quantity,
					manufacturers_id)
						VALUES (
							'$v_products_image',";

			// unmcomment these lines if you are running the image mods
						
				$query .=	//  $v_products_mimage . '", "'
							"'". $v_products_bimage . "',"."'"
							. $v_products_subimage1 . "',"."'"
							. $v_products_bsubimage1 . "',"."'"
							. $v_products_subimage2 . "',"."'"
							. $v_products_bsubimage2 ."',"."'"
							. $v_products_subimage3 . "',"."'"
							. $v_products_bsubimage3 . "',"."'"
							. $v_products_subimage4 . "',"."'"
							. $v_products_bsubimage4 . "',"."'"
							. $v_products_subimage5 . "',"."'"
							. $v_products_bsubimage5 . "',"."'"
							. $v_products_subimage6 . "',"."'"							
							. $v_products_bsubimage6 . "',";
			
			// end of more images
			
			$query .="				'$v_products_model',
								'$v_products_price',							
								'$v_products_price_list',								
								'$v_db_status',
								CURRENT_TIMESTAMP,
								$v_date_added,
								$v_date_avail,
								'$v_tax_class_id',
								'$v_products_weight',
								'$v_products_quantity',
								'$v_manufacturer_id')
							";
				$result = tep_db_query($query);
				MCache::update_product(tep_db_insert_id());//MCache update
		} else {
			// existing product, get the id from the query
			// and update the product data
			$row =  tep_db_fetch_array($result);
			$v_products_id = $row['products_id'];
			echo "<font color='black'> Updated</font><br>";
			$row =  tep_db_fetch_array($result);
			$query = 'UPDATE '.TABLE_PRODUCTS.'
					SET
					products_price="'.$v_products_price.
					
					//BOF: Kevin Added: product_list_price
					'" ,products_price_list="'.$v_products_price_list.
					//BOF: Kevin Added: product_list_price
					
					'" ,products_image="'.$v_products_image;

			// uncomment these lines if you are running the image mods

				$query .=
					//'" ,products_mimage="'.$v_products_mimage.
					'" ,products_bimage="'.$v_products_bimage.
					'" ,products_subimage1="'.$v_products_subimage1.
					'" ,products_bsubimage1="'.$v_products_bsubimage1.
					'" ,products_subimage2="'.$v_products_subimage2.
					'" ,products_bsubimage2="'.$v_products_bsubimage2.
					'" ,products_subimage3="'.$v_products_subimage3.
					'" ,products_bsubimage3="'.$v_products_bsubimage3.
					'" ,products_subimage3="'.$v_products_subimage4.
					'" ,products_bsubimage3="'.$v_products_bsubimage4.
					'" ,products_subimage3="'.$v_products_subimage5.
					'" ,products_bsubimage3="'.$v_products_bsubimage5.
					'" ,products_subimage3="'.$v_products_subimage6.
					'" ,products_bsubimage3="'.$v_products_bsubimage6;

			// end of more images
			$query .= '", products_weight="'.$v_products_weight .
					'", products_tax_class_id="'.$v_tax_class_id . 
					'", products_date_available= ' . $v_date_avail .
					', products_date_added= ' . $v_date_added .
					', products_last_modified=CURRENT_TIMESTAMP
					, products_quantity="' . $v_products_quantity .  
					'" ,manufacturers_id=' . $v_manufacturer_id . 
					' , products_status=' . $v_db_status . '
					WHERE
						(products_id = "'. $v_products_id . '")';

			$result = tep_db_query($query);
			MCache::update_product($v_products_id);//MCache update
		}

		// the following is common in both the updating an existing product and creating a new product
                if ( isset($v_products_name)){
			foreach( $v_products_name as $key => $name){
							if ($name!=''){
					$sql = "SELECT * FROM ".TABLE_PRODUCTS_DESCRIPTION." WHERE
							products_id = $v_products_id AND
							language_id = " . $key;
					$result = tep_db_query($sql);
					if (tep_db_num_rows($result) == 0) {
						// nope, this is a new product description
						$result = tep_db_query($sql);
						$sql =
							"INSERT INTO ".TABLE_PRODUCTS_DESCRIPTION."
								(products_id,
								language_id,
								products_name,
								products_description,
								products_url)
								VALUES (
									'" . $v_products_id . "',
									" . $key . ",
									'" . $name . "',
									'". $v_products_description[$key] . "',
									'". $v_products_url[$key] . "'
									)";
						// support for Linda's Header Controller 2.0
						if (isset($v_products_head_title_tag)){
							// override the sql if we're using Linda's contrib
							$sql =
								"INSERT INTO ".TABLE_PRODUCTS_DESCRIPTION."
									(products_id,
									language_id,
									products_name,
									products_description,
									products_url,
									products_head_title_tag,
									products_head_desc_tag,
									products_head_keywords_tag)
									VALUES (
										'" . $v_products_id . "',
										" . $key . ",
										'" . $name . "',
										'". $v_products_description[$key] . "',
										'". $v_products_url[$key] . "',
										'". $v_products_head_title_tag[$key] . "',
										'". $v_products_head_desc_tag[$key] . "',
										'". $v_products_head_keywords_tag[$key] . "')";
						}
						// end support for Linda's Header Controller 2.0
						$result = tep_db_query($sql);
						MCache::update_product($v_products_id);//MCache update
					} else {
						// already in the description, let's just update it
						$sql =
							"UPDATE ".TABLE_PRODUCTS_DESCRIPTION." SET
								products_name='$name',
								products_description='".$v_products_description[$key] . "',
								products_url='" . $v_products_url[$key] . "'
							WHERE
								products_id = '$v_products_id' AND
								language_id = '$key'";
						// support for Lindas Header Controller 2.0
						if (isset($v_products_head_title_tag)){
							// override the sql if we're using Linda's contrib
							$sql =
								"UPDATE ".TABLE_PRODUCTS_DESCRIPTION." SET
									products_name = '$name',
									products_description = '".$v_products_description[$key] . "',
									products_url = '" . $v_products_url[$key] ."',
									products_head_title_tag = '" . $v_products_head_title_tag[$key] ."',
									products_head_desc_tag = '" . $v_products_head_desc_tag[$key] ."',
									products_head_keywords_tag = '" . $v_products_head_keywords_tag[$key] ."'
								WHERE
									products_id = '$v_products_id' AND
									language_id = '$key'";
						}
						// end support for Linda's Header Controller 2.0
						$result = tep_db_query($sql);
						MCache::update_product($v_products_id);//MCache update
					}
				}
			}
		}
		if (isset($v_categories_id)){
			//find out if this product is listed in the category given
			$result_incategory = tep_db_query('SELECT
						'.TABLE_PRODUCTS_TO_CATEGORIES.'.products_id,
						'.TABLE_PRODUCTS_TO_CATEGORIES.'.categories_id
						FROM
							'.TABLE_PRODUCTS_TO_CATEGORIES.'
						WHERE
						'.TABLE_PRODUCTS_TO_CATEGORIES.'.products_id='.$v_products_id.' AND
						'.TABLE_PRODUCTS_TO_CATEGORIES.'.categories_id='.$v_categories_id);
			if (tep_db_num_rows($result_incategory) == 0) {
				// nope, this is a new category for this product
				$res1 = tep_db_query('INSERT INTO '.TABLE_PRODUCTS_TO_CATEGORIES.' (products_id, categories_id)
							VALUES ("' . $v_products_id . '", "' . $v_categories_id . '")');
			} else {
				// already in this category, nothing to do!
			}
		}
		// for the separate prices per customer module
		$ll=1;

		if (isset($v_customer_price_1)){
			
			if (($v_customer_group_id_1 == '') AND ($v_customer_price_1 != ''))  {
				echo "<font color=red>ERROR - v_customer_group_id and v_customer_price must occur in pairs</font>";
				die();
			}
			// they spec'd some prices, so clear all existing entries
			$result = tep_db_query('
						DELETE
						FROM
							'.TABLE_PRODUCTS_GROUPS.'
						WHERE
							products_id = ' . $v_products_id
						);
			// and insert the new record
			if ($v_customer_price_1 != ''){
				$result = tep_db_query('
							INSERT INTO
								'.TABLE_PRODUCTS_GROUPS.'
							VALUES
							(
								' . $v_customer_group_id_1 . ',
								' . $v_customer_price_1 . ',
								' . $v_products_id . ',
								' . $v_products_price .'
								)'
							);
			}
			if ($v_customer_price_2 != ''){
				$result = tep_db_query('
							INSERT INTO
								'.TABLE_PRODUCTS_GROUPS.'
							VALUES
							(
								' . $v_customer_group_id_2 . ',
								' . $v_customer_price_2 . ',
								' . $v_products_id . ',
								' . $v_products_price . '
								)'
							);
			}
			if ($v_customer_price_3 != ''){
				$result = tep_db_query('
							INSERT INTO
								'.TABLE_PRODUCTS_GROUPS.'
							VALUES
							(
								' . $v_customer_group_id_3 . ',
								' . $v_customer_price_3 . ',
								' . $v_products_id . ',
								' . $v_products_price . '
								)'
							);
			}
			if ($v_customer_price_4 != ''){
				$result = tep_db_query('
							INSERT INTO
								'.TABLE_PRODUCTS_GROUPS.'
							VALUES
							(
								' . $v_customer_group_id_4 . ',
								' . $v_customer_price_4 . ',
								' . $v_products_id . ',
								' . $v_products_price . '
								)'
							);
			}

		}

	} else {
		// this record was missing the product_model
		array_walk($items, 'print_el');
		echo "<p class=smallText>No products_model field in record. This line was not imported <br>";
		echo "<br>";
	}
// end of row insertion code
}


require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
