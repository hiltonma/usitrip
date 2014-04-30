<?php
/*
   This conversion program was written by support personel at chainreactionweb.com
	 for the purposes of upgrading  a regular OSCommerce store's database to a 
	 CRE Loaded database. 
	 
	 It transfers from OSCOMMERCE version MS2 to CRE Loaded 6.15
	 
   It will read the information in the configure.php file located on the OSCommerce site.
   Then generate a big database backup file that can then be uploaded to the CRE loaded site's database folder
   it can then be restored thus simulating a database conversion

	 It is designed for use on Linux or BSD based systems running the Apache Web
	 Server, but may function on other servers and plaforms.  
	 
	 Copyright 2005 &copy; Chain Reaction Works, Inc.
	 
	 For support :  email software@chainreactionworks.com
	 
	 Last Modified by : $Author$
	 Last Modified on : $Date$
	 Last Subversion Revision : $Revision$
	 
*/
// Settings to change to reflect your store, the place you want the backup file to be created
// The folder must be set to 777 using chmod or at least writable


// configuration variables (TODO: make user-selectable)
$compression = ''; // leave blank, to create a normal sql file  set to gzip to compress
$savetofile = false; // set to false, if you want on-the-fly downloading (desirable, if you run into file permission/ownership problems)
$eol = "\n"; // line-feed

require('includes/application_top.php');

function rem($value) {
	// This function will escape quotes in the field data
	return addslashes($value);
}

$action = '';
if (tep_not_null($_POST['action'])) {
  $action = tep_db_prepare_input($_POST['action']);
}

// if your database has an orderspaymethods table set this to true
$orderspaymethods = false;
// If you database has an ordersshipmethods table set this to true
$ordersshipmethods = false;

if ($action == 'convert') {
	$savebuffer = '';

	// Converting the configuration table 
	$configuration_query = tep_db_query("select * from configuration");
	while ($configuration = tep_db_fetch_array($configuration_query)) {
		$savebuffer .= "update configuration set configuration_value = '".addslashes($configuration["configuration_value"])."' where configuration_key = '".addslashes($configuration["configuration_key"])."';" . $eol;
	}

	////////////////////////////////////////////////////
	////////////////////////////////////////////////////
	// Getting the auto increment value
	$auto_query = tep_db_query("select max(customers_id)+1 as newvalue from customers");
	$auto = tep_db_fetch_array($auto_query);
	// Now this section will create the customers table
	$savebuffer .= "DROP TABLE IF EXISTS customers;" . $eol;
	$savebuffer .= "CREATE TABLE customers (" . $eol;
	$savebuffer .= "customers_id int(11) NOT NULL auto_increment," . $eol;
	$savebuffer .= "purchased_without_account tinyint(1) unsigned NOT NULL default '0'," . $eol;
	$savebuffer .= "customers_gender char(1) NOT NULL default ''," . $eol;
	$savebuffer .= "customers_firstname varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "customers_lastname varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "customers_dob datetime NOT NULL default '0000-00-00 00:00:00'," . $eol;
	$savebuffer .= "customers_email_address varchar(96) NOT NULL default ''," . $eol;
	$savebuffer .= "customers_default_address_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "customers_telephone varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "customers_fax varchar(32) default NULL," . $eol;
	$savebuffer .= "customers_password varchar(40) NOT NULL default ''," . $eol;
	$savebuffer .= "customers_newsletter char(1) default NULL," . $eol;
	$savebuffer .= "customers_selected_template varchar(20) default NULL," . $eol;
	$savebuffer .= "PRIMARY KEY  (customers_id)," . $eol;
	$savebuffer .= "KEY purchased_without_account (purchased_without_account)" . $eol;
	$savebuffer .= ") TYPE=MyISAM AUTO_INCREMENT=".$auto["newvalue"].";" . $eol;
	$customers_query = tep_db_query("select * from customers");
	while ($customers = tep_db_fetch_array($customers_query)){
		//// echo "<br>in loop";
		$savebuffer .= "INSERT INTO customers (customers_id, purchased_without_account, customers_gender, customers_firstname,";
		$savebuffer .= " customers_lastname, customers_dob, customers_email_address, customers_default_address_id, customers_telephone,";
		$savebuffer .= " customers_fax, customers_password, customers_newsletter)";
		$savebuffer .= " VALUES (";
		$savebuffer .= $customers["customers_id"].",'".$customers["purchased_without_account"]."','".$customers["customers_gender"]."','".rem($customers["customers_firstname"])."','".rem($customers["customers_lastname"])."','";
		$savebuffer .= $customers["customers_dob"]."','".rem($customers["customers_email_address"])."',".$customers["customers_default_address_id"].",'".$customers["customers_telephone"]."','";
		$savebuffer .= $customers["customers_fax"]."','".$customers["customers_password"]."','".$customers["customers_newsletter"]."');" . $eol;
		//fflush($backupfile);
	}
	unset($auto);
	unset($auto_query);
	//fclose($backupfile);
	//die();
	// echo "<br>Converted the Customers Table...";
	// End of customer table creation as well as populating it
	///////////////////////////////////////////////////////////////////
	// Now this section where we create the address book
	$savebuffer .= "DROP TABLE IF EXISTS address_book;" . $eol;
	$savebuffer .= "CREATE TABLE address_book (" . $eol;
	$savebuffer .= "address_book_id int(11) NOT NULL auto_increment," . $eol;
	$savebuffer .= "customers_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "entry_gender char(1) NOT NULL default ''," . $eol;
	$savebuffer .= "entry_company varchar(32) default NULL," . $eol;
	$savebuffer .= "entry_firstname varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "entry_lastname varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "entry_street_address varchar(64) NOT NULL default ''," . $eol;
	$savebuffer .= "entry_suburb varchar(32) default NULL," . $eol;
	$savebuffer .= "entry_postcode varchar(10) NOT NULL default ''," . $eol;
	$savebuffer .= "entry_city varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "entry_state varchar(32) default NULL," . $eol;
	$savebuffer .= "entry_country_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "entry_zone_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "PRIMARY KEY  (address_book_id)," . $eol;
	$savebuffer .= "KEY idx_address_book_customers_id (customers_id)" . $eol;
	$savebuffer .= ") TYPE=MyISAM;" . $eol;
	$simcounter = 1;
	$address_query = tep_db_query("select * from address_book");
	while ($address = tep_db_fetch_array($address_query)) {
		$savebuffer .= "INSERT INTO address_book (customers_id, entry_gender, entry_company, entry_firstname,";
		$savebuffer .= " entry_lastname, entry_street_address, entry_suburb, entry_postcode, entry_city, entry_state, entry_country_id, entry_zone_id) VALUES (";
		$savebuffer .= $address["customers_id"].",'".$address["entry_gender"]."','".rem($address["entry_company"])."','".rem($address["entry_firstname"])."','".rem($address["entry_lastname"])."','".rem($address["entry_street_address"])."','".rem($address["entry_suburb"])."','".$address["entry_postcode"]."','".rem($address["entry_city"])."','".rem($address["entry_state"])."',".$address["entry_country_id"].",".$address["entry_zone_id"].");" . $eol;
		// Now that we have inserted the address_book ID we must update the customers record to show the correct
		// Default address ID
		$savebuffer .= "update customers set customers_default_address_id=".$simcounter." where customers_firstname ='".rem($address["entry_firstname"])."' and customers_lastname='".rem($address["entry_lastname"])."';" . $eol;
		// that line will update the customer record
		//fflush($backupfile);
		$simcounter ++;
	}
	// End of the adress book creation
	// echo "<br>Converted the Address Book...";
	///////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////
	////////// This section will convert the categories/////////
	$auto_query = tep_db_query("select max(categories_id)+1 as newvalue from categories");
	$auto = tep_db_fetch_array($auto_query);
	$savebuffer .= "DROP TABLE IF EXISTS categories;" . $eol;
	$savebuffer .= "CREATE TABLE categories (" . $eol;
	$savebuffer .= "categories_id int(11) NOT NULL auto_increment," . $eol;
	$savebuffer .= "categories_image varchar(64) default NULL," . $eol;
	$savebuffer .= "parent_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "sort_order int(3) default NULL," . $eol;
	$savebuffer .= "date_added datetime default NULL," . $eol;
	$savebuffer .= "last_modified datetime default NULL," . $eol;
	$savebuffer .= "PRIMARY KEY  (categories_id)," . $eol;
	$savebuffer .= "KEY idx_categories_parent_id (parent_id)" . $eol;
	$savebuffer .= ") TYPE=MyISAM AUTO_INCREMENT=".$auto["newvalue"].";" . $eol;
	$categories_query = tep_db_query("select * from categories");
	while ($cat = tep_db_fetch_array($categories_query)) {
		$savebuffer .= "INSERT INTO categories (categories_id, categories_image, parent_id, sort_order, date_added, last_modified)";
		$savebuffer .= "VALUES (".$cat["categories_id"].",'".rem($cat["categories_image"])."',".$cat["parent_id"].",".$cat["sort_order"].",'".$cat["date_added"]."','".$cat["last_modified"]."');" . $eol;
		//fflush($backupfile);
	}
	unset($auto);
	unset($auto_query);
	// echo "<br>Converted the Categories...";
	//// End of converting the categories /////////////////////
	/////////////////////////////////////////////////////////////////
	/////// Now this section will happily convert the categories description table ///
	$savebuffer .= "DROP TABLE IF EXISTS categories_description;" . $eol;
	$savebuffer .= "CREATE TABLE categories_description (" . $eol;
	$savebuffer .= "categories_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "language_id int(11) NOT NULL default '1'," . $eol;
	$savebuffer .= "categories_name varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "categories_heading_title varchar(64) default NULL," . $eol;
	$savebuffer .= "categories_description text," . $eol;
	$savebuffer .= "categories_head_title_tag varchar(80) default NULL," . $eol;
	$savebuffer .= "categories_head_desc_tag longtext NOT NULL," . $eol;
	$savebuffer .= "categories_head_keywords_tag longtext NOT NULL," . $eol;
	$savebuffer .= "PRIMARY KEY  (categories_id,language_id)," . $eol;
	$savebuffer .= "KEY idx_categories_name (categories_name)" . $eol;
	$savebuffer .= ") TYPE=MyISAM;" . $eol;
	$catdes_query = tep_db_query("select * from categories_description");
	while ($catdes = tep_db_fetch_array($catdes_query)) {
		$savebuffer .= "INSERT INTO categories_description (categories_id, language_id, categories_name, categories_heading_title, categories_description, categories_head_title_tag, categories_head_desc_tag, categories_head_keywords_tag) VALUES (";
		$savebuffer .= $catdes["categories_id"].",".$catdes["language_id"].",'".rem($catdes["categories_name"])."','".rem($catdes["categories_heading_title"])."','".rem($catdes["categories_description"])."','','','');" . $eol;
		//fflush($backupfile);
	}
	// echo "<br>Converted the Categories Description...";
	//////// End of categories description ////////////////////////////////
	/////////////////////////////////////////////////////////////////
	/// Converting the magical manufacturers ////////////////////////
	$auto_query = tep_db_query("select max(manufacturers_id)+1 as newvalue from manufacturers");
	$auto = tep_db_fetch_array($auto_query);
	if (isset($auto["newvalue"]) && $auto["newvalue"] == '') {
		$realautoincrement = $auto["newvalue"];
	}
	else {
		$realautoincrement = 0;
	}
	$savebuffer .= "DROP TABLE IF EXISTS manufacturers;" . $eol;
	$savebuffer .= "CREATE TABLE manufacturers (" . $eol;
	$savebuffer .= "manufacturers_id int(11) NOT NULL auto_increment," . $eol;
	$savebuffer .= "manufacturers_name varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "manufacturers_image varchar(64) default NULL," . $eol;
	$savebuffer .= "date_added datetime default NULL," . $eol;
	$savebuffer .= "last_modified datetime default NULL," . $eol;
	$savebuffer .= "PRIMARY KEY  (manufacturers_id)," . $eol;
	$savebuffer .= "KEY IDX_MANUFACTURERS_NAME (manufacturers_name)" . $eol;
	$savebuffer .= ") TYPE=MyISAM AUTO_INCREMENT=".$realautoincrement.";" . $eol;
	$manu_query = tep_db_query("select * from manufacturers");
	while ($manu = tep_db_fetch_array($manu_query)) {
		$savebuffer .= "INSERT INTO manufacturers (manufacturers_id, manufacturers_name, manufacturers_image, date_added, last_modified) VALUES (";
		$savebuffer .= $manu["manufacturers_id"].",'".rem($manu["manufacturers_name"])."','".rem($manu["manufacturers_image"])."','".$manu["date_added"]."','".$manu["last_modified"]."');" . $eol;
		//fflush($backupfile);
	}
	unset($auto);
	unset($auto_query);
	// echo "<br>Converted the Manufacturers...";
	//////////// End of manufacturers /////////////////////////////
	/////////////////////////////////////////////////////////////////
	//////////// Manufacturers info ////////////////////////
	$savebuffer .= "DROP TABLE IF EXISTS manufacturers_info;" . $eol;
	$savebuffer .= "CREATE TABLE manufacturers_info (" . $eol;
	$savebuffer .= "manufacturers_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "languages_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "manufacturers_url varchar(255) NOT NULL default ''," . $eol;
	$savebuffer .= "url_clicked int(5) NOT NULL default '0'," . $eol;
	$savebuffer .= "date_last_click datetime default NULL," . $eol;
	$savebuffer .= "PRIMARY KEY  (manufacturers_id,languages_id)" . $eol;
	$savebuffer .= ")TYPE=MyISAM;" . $eol;
	$manuinfo_query = tep_db_query("select * from manufacturers_info");
	while ($manuinfo = tep_db_fetch_array($manuinfo_query)) {
		$savebuffer .= "INSERT INTO manufacturers_info (manufacturers_id, languages_id, manufacturers_url, url_clicked, date_last_click) VALUES (";
		$savebuffer .= $manuinfo["manufacturers_id"].",".$manuinfo["languages_id"].",'".rem($manuinfo["manufacturers_url"])."',".$manuinfo["url_clicked"].",'".$manuinfo["date_last_click"]."');" . $eol;
		//fflush($backupfile);
	}
	// echo "<br>Converted the Manufacturers Info...";
	//////////// End of Manufacturers info ////////////////////////
	/////////////////////////////////////////////////////////////////
	//////////// Now we are converting the orders ///////////////////
	$auto_query = tep_db_query("select max(orders_id)+1 as newvalue from orders");
	$auto = tep_db_fetch_array($auto_query);
	$savebuffer .= "DROP TABLE IF EXISTS orders;" . $eol;
	$savebuffer .= "CREATE TABLE orders (" . $eol;
	$savebuffer .= "  orders_id int(11) NOT NULL auto_increment," . $eol;
	$savebuffer .= "  customers_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  customers_name varchar(64) NOT NULL default ''," . $eol;
	$savebuffer .= "  customers_company varchar(32) default NULL," . $eol;
	$savebuffer .= "  customers_street_address varchar(64) NOT NULL default ''," . $eol;
	$savebuffer .= "  customers_suburb varchar(32) default NULL," . $eol;
	$savebuffer .= "  customers_city varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "  customers_postcode varchar(10) NOT NULL default ''," . $eol;
	$savebuffer .= "  customers_state varchar(32) default NULL," . $eol;
	$savebuffer .= "  customers_country varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "  customers_telephone varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "  customers_email_address varchar(96) NOT NULL default ''," . $eol;
	$savebuffer .= "  customers_address_format_id int(5) NOT NULL default '0'," . $eol;
	$savebuffer .= "  delivery_name varchar(64) NOT NULL default ''," . $eol;
	$savebuffer .= "  delivery_company varchar(32) default NULL," . $eol;
	$savebuffer .= "  delivery_street_address varchar(64) NOT NULL default ''," . $eol;
	$savebuffer .= "  delivery_suburb varchar(32) default NULL," . $eol;
	$savebuffer .= "  delivery_city varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "  delivery_postcode varchar(10) NOT NULL default ''," . $eol;
	$savebuffer .= "  delivery_state varchar(32) default NULL," . $eol;
	$savebuffer .= "  delivery_country varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "  delivery_address_format_id int(5) NOT NULL default '0'," . $eol;
	$savebuffer .= "  billing_name varchar(64) NOT NULL default ''," . $eol;
	$savebuffer .= "  billing_company varchar(32) default NULL," . $eol;
	$savebuffer .= "  billing_street_address varchar(64) NOT NULL default ''," . $eol;
	$savebuffer .= "  billing_suburb varchar(32) default NULL," . $eol;
	$savebuffer .= "  billing_city varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "  billing_postcode varchar(10) NOT NULL default ''," . $eol;
	$savebuffer .= "  billing_state varchar(32) default NULL," . $eol;
	$savebuffer .= "  billing_country varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "  billing_address_format_id int(5) NOT NULL default '0'," . $eol;
	$savebuffer .= "  payment_method varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "  payment_info text," . $eol;
	$savebuffer .= "  payment_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  cc_type varchar(20) default NULL," . $eol;
	$savebuffer .= "  cc_owner varchar(64) default NULL," . $eol;
	$savebuffer .= "  cc_number varchar(32) default NULL," . $eol;
	$savebuffer .= "  cc_expires varchar(4) default NULL," . $eol;
	$savebuffer .= "  last_modified datetime default NULL," . $eol;
	$savebuffer .= "  date_purchased datetime default NULL," . $eol;
	$savebuffer .= "  orders_status int(5) NOT NULL default '0'," . $eol;
	$savebuffer .= "  orders_date_finished datetime default NULL," . $eol;
	$savebuffer .= "  currency char(3) default NULL," . $eol;
	$savebuffer .= "  currency_value decimal(14,6) default NULL," . $eol;
	$savebuffer .= "  account_name varchar(32) default NULL," . $eol;
	$savebuffer .= "  account_number varchar(20) default NULL," . $eol;
	$savebuffer .= "  po_number varchar(12) default NULL," . $eol;
	$savebuffer .= "  purchased_without_account tinyint(1) unsigned NOT NULL default '0'," . $eol;
	$savebuffer .= "  paypal_ipn_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  PRIMARY KEY  (orders_id)" . $eol;
	$savebuffer .= ") TYPE=MyISAM AUTO_INCREMENT=".$auto["newvalue"].";" . $eol;
	$orders_query = tep_db_query("select * from orders");
	while ($orders = tep_db_fetch_array($orders_query)) {
		$savebuffer .= "INSERT INTO orders (orders_id, customers_id, customers_name, customers_company, customers_street_address, customers_suburb, customers_city, customers_postcode, customers_state, customers_country, customers_telephone, customers_email_address, customers_address_format_id, delivery_name, delivery_company, delivery_street_address, delivery_suburb, delivery_city, delivery_postcode, delivery_state, delivery_country, delivery_address_format_id, billing_name, billing_company, billing_street_address, billing_suburb, billing_city, billing_postcode, billing_state, billing_country, billing_address_format_id, payment_method, payment_info, payment_id, cc_type, cc_owner, cc_number, cc_expires, last_modified, date_purchased, orders_status, orders_date_finished, currency, currency_value, account_name, account_number, po_number, purchased_without_account, paypal_ipn_id) VALUES (";
		$savebuffer .= $orders["orders_id"].",".$orders["customers_id"].",'".rem($orders["customers_name"])."','".rem($orders["customers_company"])."','".rem($orders["customers_street_address"])."','".rem($orders["customers_suburb"])."','".rem($orders["customers_city"])."','".rem($orders["customers_postcode"])."','".rem($orders["customers_state"])."','".rem($orders["customers_country"])."','".$orders["customers_telephone"]."','".rem($orders["customers_email_address"])."','".$orders["customers_address_format_id"]."','".rem($orders["delivery_name"])."','".rem($orders["delivery_company"])."','".rem($orders["delivery_street_address"])."','".rem($orders["delivery_suburb"])."','".rem($orders["delivery_city"])."','".rem($orders["delivery_postcode"])."','".rem($orders["delivery_state"])."','".rem($orders["delivery_country"])."','".$orders["delivery_address_format_id"]."','".rem($orders["billing_name"])."','".rem($orders["billing_company"])."','".rem($orders["billing_street_address"])."','".rem($orders["billing_suburb"])."','".rem($orders["billing_city"])."','".rem($orders["billing_postcode"])."','".rem($orders["billing_state"])."','".rem($orders["billing_country"])."','".$orders["billing_address_format_id"]."','".rem($orders["payment_method"])."','".rem($orders["payment_info"])."','".$orders["payment_id"]."','".$orders["cc_type"]."','".rem($orders["cc_owner"])."','".$orders["cc_number"]."','".$orders["cc_expires"]."','".$orders["last_modified"]."','".$orders["date_purchased"]."','".rem($orders["orders_status"])."','".$orders["orders_date_finished"]."','".rem($orders["currency"])."','".$orders["currency_value"]."','".rem($orders["account_name"])."','".rem($orders["account_number"])."','".rem($orders["po_number"])."','".$orders["purchased_without_account"]."','".$orders["paypal_ipn_id"]."');" . $eol;
		//fflush($backupfile);
	}
	unset($auto);
	unset($auto_query);
	// echo "<br>Converted the Order Table...";
	///////////// End of converting the orders ////////////////////
	/////////////////////////////////////////////////////////////////
	////////////  Converting the order pay methods //////////////
	if ($orderspaymethods) {
		$auto_query = tep_db_query("select max(pay_methods_id)+1 as newvalue from orders_pay_methods");
		$auto = tep_db_fetch_array($auto_query);
		$savebuffer .= "DROP TABLE IF EXISTS orders_pay_methods;" . $eol;
		$savebuffer .= "CREATE TABLE orders_pay_methods (" . $eol;
		$savebuffer .= "  pay_methods_id int(11) NOT NULL auto_increment," . $eol;
		$savebuffer .= "  pay_method varchar(255) NOT NULL default ''," . $eol;
		$savebuffer .= "  date_added datetime NOT NULL default '0000-00-00 00:00:00'," . $eol;
		$savebuffer .= "  PRIMARY KEY  (pay_methods_id)" . $eol;
		$savebuffer .= ") TYPE=MyISAM AUTO_INCREMENT=".$auto["newvalue"].";" . $eol;
		$paymethods_query = tep_db_query("select * from orders_pay_methods");
		while ($paymethods = tep_db_fetch_array($paymethods_query)) {
			$savebuffer .= "INSERT INTO orders_pay_methods (pay_methods_id, pay_method, date_added) VALUES (";
			$savebuffer .= $paymethods["pay_methods_id"].",'".rem($paymethods["pay_method"])."','".$paymethods["date_added"]."');" . $eol;
			//fflush($backupfile);
		}
		unset($auto);
		unset($auto_query);
		///////////// End of converting the order pay methods ////////
		// echo "<br>Converted the orders pay methods table...";
	}
	/////////////////////////////////////////////////////////////////
	///////// Converting Order products /////////////////////////
	$auto_query = tep_db_query("select max(orders_products_id)+1 as newvalue from orders_products");
	$auto = tep_db_fetch_array($auto_query);
	$savebuffer .= "DROP TABLE IF EXISTS orders_products;" . $eol;
	$savebuffer .= "CREATE TABLE orders_products (" . $eol;
	$savebuffer .= "  orders_products_id int(11) NOT NULL auto_increment," . $eol;
	$savebuffer .= "  orders_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  products_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  products_model varchar(12) default NULL," . $eol;
	$savebuffer .= "  products_name varchar(64) NOT NULL default ''," . $eol;
	$savebuffer .= "  products_price decimal(15,4) NOT NULL default '0.0000'," . $eol;
	$savebuffer .= "  final_price decimal(15,4) NOT NULL default '0.0000'," . $eol;
	$savebuffer .= "  products_tax decimal(7,4) NOT NULL default '0.0000'," . $eol;
	$savebuffer .= "  products_quantity int(2) NOT NULL default '0'," . $eol;
	$savebuffer .= "  PRIMARY KEY  (orders_products_id)" . $eol;
	$savebuffer .= ") TYPE=MyISAM AUTO_INCREMENT=".$auto["newvalue"].";" . $eol;
	$ordersproducts_query = tep_db_query("select * from orders_products");
	while ($ordersproducts = tep_db_fetch_array($ordersproducts_query)) {
		$savebuffer .= "INSERT INTO orders_products (orders_products_id, orders_id, products_id, products_model, products_name, products_price, final_price, products_tax, products_quantity) VALUES (";
		$savebuffer .= $orderproducts["orders_products_id"].",".$ordersproducts["orders_id"].",".$ordersproducts["products_id"].",'".rem($ordersproducts["products_model"])."','".rem($ordersproducts["products_name"])."','".$ordersproducts["products_price"]."','".$ordersproducts["final_price"]."','".$ordersproducts["products_tax"]."','".$ordersproducts["products_quantity"]."');" . $eol;
		//fflush($backupfile);
	}
	unset($auto);
	unset($auto_query);
	// echo "<br>Converted the orders products table...";
	////////// End of converting Order products /////////////////
	////////////////////////////////////////////////////////////////
	///////////////// Orders products attributes /////////////////
	$auto_query = tep_db_query("select max(orders_products_attributes_id)+1 as newvalue from orders_products_attributes");
	$auto = tep_db_fetch_array($auto_query);
	if (isset($auto["newvalue"]) && $auto["newvalue"] == '') {
		$realautoincrement = $auto["newvalue"];
	}
	else {
		$realautoincrement = 0;
	}
	$savebuffer .= "DROP TABLE IF EXISTS orders_products_attributes;" . $eol;
	$savebuffer .= "CREATE TABLE orders_products_attributes (" . $eol;
	$savebuffer .= "  orders_products_attributes_id int(11) NOT NULL auto_increment," . $eol;
	$savebuffer .= "  orders_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  orders_products_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  products_options varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "  products_options_values varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "  options_values_price decimal(15,4) NOT NULL default '0.0000'," . $eol;
	$savebuffer .= "  price_prefix char(1) NOT NULL default ''," . $eol;
	$savebuffer .= "  products_options_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  products_options_values_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  PRIMARY KEY  (orders_products_attributes_id)" . $eol;
	$savebuffer .= ") TYPE=MyISAM AUTO_INCREMENT=".$realautoincrement.";" . $eol;
	$ordprodatt_query = tep_db_query("select * from orders_products_attributes");
	while ($ordprodatt = tep_db_fetch_array($ordprodatt_query)) {
		$savebuffer .= "INSERT INTO orders_products_attributes (orders_products_attributes_id, orders_id, orders_products_id, products_options, products_options_values, options_values_price, price_prefix, products_options_id, products_options_values_id) VALUES (";
		$savebuffer .= $ordprodatt["orders_products_attributes_id"].",".$ordprodatt["orders_id"].",".$ordprodatt["orders_products_id"].",'".rem($ordprodatt["products_options"])."','".rem($ordprodatt["products_options_values"])."','".$ordprodatt["options_values_price"]."','".$ordprodatt["price_prefix"]."','".rem($ordprodatt["products_options_id"])."','".rem($ordprodatt["products_options_values_id"])."');" . $eol;
		//fflush($backupfile);
	}
	unset($auto);
	unset($auto_query);
	// echo "<br>Converted the orders products attributes table...";
	///////////////// END of orders products attributes ///////////
	////////////////////////////////////////////////////////////////

	if ($ordersshipmethods) {
		////////////// Beginning of Orders Ship method //////////////
		$auto_query = tep_db_query("select max(ship_methods_id)+1 as newvalue from orders_ship_methods");
		$auto = tep_db_fetch_array($auto_query);
		$savebuffer .= "DROP TABLE IF EXISTS orders_ship_methods;" . $eol;
		$savebuffer .= "CREATE TABLE orders_ship_methods (" . $eol;
		$savebuffer .= "  ship_methods_id int(11) NOT NULL auto_increment," . $eol;
		$savebuffer .= "  ship_method varchar(255) NOT NULL default ''," . $eol;
		$savebuffer .= "  date_added datetime NOT NULL default '0000-00-00 00:00:00'," . $eol;
		$savebuffer .= "  PRIMARY KEY  (ship_methods_id)" . $eol;
		$savebuffer .= ") TYPE=MyISAM AUTO_INCREMENT=".$auto["newvalue"].";" . $eol;
		$shipmethods_query = tep_db_query("select * from orders_ship_methods");
		while ($shipmethods = tep_db_fetch_array($shipmethods_query)) {
			$savebuffer .= "INSERT INTO orders_ship_methods (ship_methods_id, ship_method, date_added) VALUES (";
			$savebuffer .= $shipmethods["ship_methods_id"].",'".rem($shipmethods["ship_method"])."','".$shipmethods["date_added"]."');" . $eol;
			//fflush($backupfile);
		}
		unset($auto);
		unset($auto_query);
		////////////// End of orders ship method ///////////////////
		// echo "<br>Converted the orders ship methods table...";
	}
	//fclose($backupfile);
	//die();
	////////////////////////////////////////////////////////////////
	///////////// Converting the orders_status table /////////////
	$savebuffer .= "DROP TABLE IF EXISTS orders_status;" . $eol;
	$savebuffer .= "CREATE TABLE orders_status (" . $eol;
	$savebuffer .= "  orders_status_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  language_id int(11) NOT NULL default '1'," . $eol;
	$savebuffer .= "  orders_status_name varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "  PRIMARY KEY  (orders_status_id,language_id)," . $eol;
	$savebuffer .= "  KEY idx_orders_status_name (orders_status_name)" . $eol;
	$savebuffer .= ") TYPE=MyISAM;" . $eol;
	// Inserting the special paypal order status
	//$savebuffer .= "INSERT INTO orders_status (orders_status_id, language_id, orders_status_name) VALUES (99999, 1, 'Paypal Processing');";
	$orderstatus_query = tep_db_query("select * from orders_status");
	while($orderstatus = tep_db_fetch_array($orderstatus_query)) {
		$savebuffer .= "INSERT INTO orders_status (orders_status_id, language_id, orders_status_name) VALUES (";
		$savebuffer .= $orderstatus["orders_status_id"].",".$orderstatus["language_id"].",'".rem($orderstatus["orders_status_name"])."');" . $eol;
		//fflush($backupfile);
	}
	// echo "<br>Converted the orders status table...";
	///////////// End of converting the orders status table //////////
	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////
	///////////// Converting the orders_status_history table /////////////
	$auto_query = tep_db_query("select max(orders_status_history_id)+1 as newvalue from orders_status_history");
	$auto = tep_db_fetch_array($auto_query);
	$savebuffer .= "DROP TABLE IF EXISTS orders_status_history;" . $eol;
	$savebuffer .= "CREATE TABLE orders_status_history (" . $eol;
	$savebuffer .= "  orders_status_history_id int(11) NOT NULL auto_increment," . $eol;
	$savebuffer .= "  orders_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  orders_status_id int(5) NOT NULL default '0'," . $eol;
	$savebuffer .= "  date_added datetime NOT NULL default '0000-00-00 00:00:00'," . $eol;
	$savebuffer .= "  customer_notified int(1) default '0'," . $eol;
	$savebuffer .= "  comments text," . $eol;
	$savebuffer .= "  PRIMARY KEY  (orders_status_history_id)" . $eol;
	$savebuffer .= ") TYPE=MyISAM AUTO_INCREMENT=".$auto["newvalue"].";" . $eol;
	$orderstathis_query = tep_db_query("select * from orders_status_history");
	while ($orderstathis = tep_db_fetch_array($orderstathis_query)) {
		$savebuffer .= "INSERT INTO orders_status_history (orders_status_history_id, orders_id, orders_status_id, date_added, customer_notified, comments) VALUES (";
		$savebuffer .= $orderstathis["orders_status_history_id"].",".$orderstathis["orders_id"].",'".$orderstathis["orders_status_id"]."','".$orderstathis["date_added"]."','".rem($orderstathis["customer_notified"])."','".rem($orderstathis["comments"])."');" . $eol;
		//fflush($backupfile);
	}
	unset($auto);
	unset($auto_query);
	// echo "<br>Converted the orders status history table...";
	////////////////////////////////////////////////////////////////
	///////////// End of converting the orders status history table /////////////
	///////////////////////////////////////////////////////////////////
	//////////////////// Converting the orders total table ////////////////
	$auto_query = tep_db_query("select max(orders_total_id)+1 as newvalue from orders_total");
	$auto = tep_db_fetch_array($auto_query);
	$savebuffer .= "DROP TABLE IF EXISTS orders_total;" . $eol;
	$savebuffer .= "CREATE TABLE orders_total (" . $eol;
	$savebuffer .= "  orders_total_id int(10) unsigned NOT NULL auto_increment," . $eol;
	$savebuffer .= "  orders_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  title varchar(255) NOT NULL default ''," . $eol;
	$savebuffer .= "  text varchar(255) NOT NULL default ''," . $eol;
	$savebuffer .= "  value decimal(15,4) NOT NULL default '0.0000'," . $eol;
	$savebuffer .= "  class varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "  sort_order int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  PRIMARY KEY  (orders_total_id)," . $eol;
	$savebuffer .= "  KEY idx_orders_total_orders_id (orders_id)" . $eol;
	$savebuffer .= ") TYPE=MyISAM AUTO_INCREMENT=".$auto["newvalue"].";" . $eol;
	$orderstotal_query = tep_db_query("select * from orders_total");
	while ($orderstotal = tep_db_fetch_array($orderstotal_query)) {
		$savebuffer .= "INSERT INTO orders_total (orders_total_id, orders_id, title, text, value, class, sort_order) VALUES (";
		$savebuffer .= $orderstotal["orders_total_id"].",".$orderstotal["orders_id"].",'".rem($orderstotal["title"])."','".rem($orderstotal["text"])."','".$orderstotal["value"]."','".rem($orderstotal["class"])."','".$orderstotal["sort_order"]."');" . $eol;
		//fflush($backupfile);
	}
	unset($auto);
	unset($auto_query);
	// echo "<br>Converted the orders total table...";
	///////////////////// End of converting the orders total table //////////////
	///////////////////////////////////////////////////////////////////
	///////////////////// Now we are converting the products //////////////
	$auto_query = tep_db_query("select max(products_id)+1 as newvalue from products");
	$auto = tep_db_fetch_array($auto_query);
	$savebuffer .= "DROP TABLE IF EXISTS products;" . $eol;
	$savebuffer .= "CREATE TABLE products (" . $eol;
	$savebuffer .= "  products_id int(11) NOT NULL auto_increment," . $eol;
	$savebuffer .= "  products_quantity int(4) NOT NULL default '0'," . $eol;
	$savebuffer .= "  products_model varchar(25) default NULL," . $eol;
	$savebuffer .= "  products_image varchar(64) default NULL," . $eol;
	$savebuffer .= "  products_image_med varchar(64) default NULL," . $eol;
	$savebuffer .= "  products_image_lrg varchar(64) default NULL," . $eol;
/*comment by scs	$savebuffer .= "  products_image_sm_1 varchar(64) default NULL," . $eol;
	$savebuffer .= "  products_image_xl_1 varchar(64) default NULL," . $eol;
	$savebuffer .= "  products_image_sm_2 varchar(64) default NULL," . $eol;
	$savebuffer .= "  products_image_xl_2 varchar(64) default NULL," . $eol;
	$savebuffer .= "  products_image_sm_3 varchar(64) default NULL," . $eol;
	$savebuffer .= "  products_image_xl_3 varchar(64) default NULL," . $eol;
	$savebuffer .= "  products_image_sm_4 varchar(64) default NULL," . $eol;
	$savebuffer .= "  products_image_xl_4 varchar(64) default NULL," . $eol;
	$savebuffer .= "  products_image_sm_5 varchar(64) default NULL," . $eol;
	$savebuffer .= "  products_image_xl_5 varchar(64) default NULL," . $eol;
	$savebuffer .= "  products_image_sm_6 varchar(64) default NULL," . $eol;
	$savebuffer .= "  products_image_xl_6 varchar(64) default NULL," . $eol;	*/
	$savebuffer .= "  products_price decimal(15,4) NOT NULL default '0.0000'," . $eol;
	$savebuffer .= "  products_date_added datetime NOT NULL default '0000-00-00 00:00:00'," . $eol;
	$savebuffer .= "  products_last_modified datetime default NULL," . $eol;
	$savebuffer .= "  products_date_available datetime default NULL," . $eol;
	$savebuffer .= "  products_weight decimal(5,2) NOT NULL default '0.00'," . $eol;
	$savebuffer .= "  products_status tinyint(1) NOT NULL default '0'," . $eol;
	$savebuffer .= "  products_tax_class_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  manufacturers_id int(11) default NULL," . $eol;
	$savebuffer .= "  products_ordered int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  PRIMARY KEY  (products_id)," . $eol;
	$savebuffer .= "  KEY idx_products_date_added (products_date_added)" . $eol;
	$savebuffer .= ") TYPE=MyISAM AUTO_INCREMENT=".$auto["newvalue"].";" . $eol;
	$products_query = tep_db_query("select * from products");
	while ($products = tep_db_fetch_array($products_query)) {
		$savebuffer .= "INSERT INTO products (products_id, products_quantity, products_model, products_image, products_price, products_date_added, products_last_modified, products_date_available, products_weight, products_status, products_tax_class_id, manufacturers_id, products_ordered) VALUES (";
		$savebuffer .= "'".$products["products_id"]."','".$products["products_quantity"]."','".rem($products["products_model"])."','".rem($products["products_image"])."','".$products["products_price"]."','".$products["products_date_added"]."','".$products["products_last_modified"]."','".$products["products_date_available"]."','".$products["products_weight"]."','".rem($products["products_status"])."','".$products["products_tax_class_id"]."','".$products["manufacturers_id"]."','".$products["products_ordered"]."');" . $eol;
		//fflush($backupfile);
	}
	unset($auto);
	unset($auto_query);
	// echo "<br>Converted the products table...";
	/////////////////////// End of converting the products ///////////////

	///////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////
	///////////// Converting the products_attributes table ///////////

	$auto_query = tep_db_query("select max(products_attributes_id)+1 as newvalue from products_attributes");
	$auto = tep_db_fetch_array($auto_query);
	if (isset($auto["newvalue"]) && $auto["newvalue"] == '') {
		$realautoincrement = $auto["newvalue"];
	}
	else {
		$realautoincrement = 0;
	}
	$savebuffer .= "DROP TABLE IF EXISTS products_attributes;" . $eol;
	$savebuffer .= "CREATE TABLE products_attributes (" . $eol;
	$savebuffer .= "  products_attributes_id int(11) NOT NULL auto_increment," . $eol;
	$savebuffer .= "  products_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  options_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  options_values_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  options_values_price decimal(15,4) NOT NULL default '0.0000'," . $eol;
	$savebuffer .= "  price_prefix char(1) NOT NULL default ''," . $eol;
	$savebuffer .= "  products_options_sort_order int(6) NOT NULL default '0'," . $eol;
	$savebuffer .= "  PRIMARY KEY  (products_attributes_id)" . $eol;
	$savebuffer .= ") TYPE=MyISAM AUTO_INCREMENT=".$realautoincrement.";" . $eol;
	$prodatt_query = tep_db_query("select * from products_attributes");
	while ($prodatt = tep_db_fetch_array($prodatt_query)) {
		$savebuffer .= "INSERT INTO products_attributes (products_attributes_id, products_id, options_id, options_values_id, options_values_price, price_prefix, products_options_sort_order) VALUES (";
		$savebuffer .= $prodatt["products_attributes_id"].",".$prodatt["products_id"].",".rem($prodatt["options_id"]).",".$prodatt["options_values_id"].",'".$prodatt["options_values_price"]."','".$prodatt["price_prefix"]."','".$prodatt["products_options_sort_order"]."');" . $eol;
		//fflush($backupfile);
	}
	unset($auto);
	unset($auto_query);
	// echo "<br>Converted the products attributes table...";
	//////////// End of converting the products attributes table
	///////////////////////////////////////////////////////////////////
	//////////// Beginning of converting the products description table /////////////
	$auto_query = tep_db_query("select max(products_id)+1 as newvalue from products_description");
	$auto = tep_db_fetch_array($auto_query);
	$savebuffer .= "DROP TABLE IF EXISTS products_description;" . $eol;
	$savebuffer .= "CREATE TABLE products_description (" . $eol;
	$savebuffer .= "  products_id int(11) NOT NULL auto_increment," . $eol;
	$savebuffer .= "  language_id int(11) NOT NULL default '1'," . $eol;
	$savebuffer .= "  products_name varchar(64) NOT NULL default ''," . $eol;
	$savebuffer .= "  products_description text," . $eol;
	$savebuffer .= "  products_url varchar(255) default NULL," . $eol;
	$savebuffer .= "  products_viewed int(5) default '0'," . $eol;
	$savebuffer .= "  products_head_title_tag varchar(80) default NULL," . $eol;
	$savebuffer .= "  products_head_desc_tag longtext NOT NULL," . $eol;
	$savebuffer .= "  products_head_keywords_tag longtext NOT NULL," . $eol;
	$savebuffer .= "  PRIMARY KEY  (products_id,language_id)," . $eol;
	$savebuffer .= "  KEY products_name (products_name)" . $eol;
	$savebuffer .= ") TYPE=MyISAM AUTO_INCREMENT=".$auto["newvalue"].";" . $eol;
	$prodesc_query = tep_db_query("select * from products_description");
	while ($prodesc = tep_db_fetch_array($prodesc_query)) {
		$savebuffer .= "INSERT INTO products_description (products_id, language_id, products_name, products_description, products_url, products_viewed) VALUES (";
		$savebuffer .= $prodesc["products_id"].",".$prodesc["language_id"].",'".rem($prodesc["products_name"])."','".rem($prodesc["products_description"])."','".rem($prodesc["products_url"])."','".rem($prodesc["products_viewed"])."');" . $eol;
		//fflush($backupfile);
	}
	unset($auto);
	unset($auto_query);
	// echo "<br>Converted the products description table...";
	///////////// End of converting the products description table //////////////
	////////////// Converting the products notifications table ////////////////
	$savebuffer .= "DROP TABLE IF EXISTS products_notifications;" . $eol;
	$savebuffer .= "CREATE TABLE products_notifications (" . $eol;
	$savebuffer .= "  products_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  customers_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  date_added datetime NOT NULL default '0000-00-00 00:00:00'," . $eol;
	$savebuffer .= "  PRIMARY KEY  (products_id,customers_id)" . $eol;
	$savebuffer .= ") TYPE=MyISAM;" . $eol;
	$prodnoti_query = tep_db_query("select * from products_notifications");
	while ($prodnoti = tep_db_fetch_array($prodnoti_query)) {
		$savebuffer .= "INSERT INTO products_notifications (products_id, customers_id, date_added) VALUES (";
		$savebuffer .= $prodnoti["products_id"].",".$prodnoti["customers_id"].",'".$prodnoti["date_added"]."');" . $eol;
		//fflush($backupfile);
	}
	unset($auto);
	unset($auto_query);
	// echo "<br>Converted the products notifications table...";
	////////////// End of converting the products notifications table /////////////
	//////////////////////////products options////////////////////////////////////////
	$savebuffer .= "DROP TABLE IF EXISTS products_options;" . $eol;
	$savebuffer .= "CREATE TABLE products_options (" . $eol;
	$savebuffer .= "  products_options_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  language_id int(11) NOT NULL default '1'," . $eol;
	$savebuffer .= "  products_options_name varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "  products_options_sort_order int(4) NOT NULL default '0'," . $eol;
	$savebuffer .= "  PRIMARY KEY  (products_options_id,language_id)" . $eol;
	$savebuffer .= ") TYPE=MyISAM;" . $eol;
	$prodoptions_query = tep_db_query("select * from products_options");
	while ($prodoptions = tep_db_fetch_array($prodoptions_query)) {
		$savebuffer .= "INSERT INTO products_options (products_options_id, language_id, products_options_name) VALUES (";
		$savebuffer .= $prodoptions["products_options_id"].",".$prodoptions["language_id"].",'".rem($prodoptions["products_options_name"])."');" . $eol;
		//fflush($backupfile);
	}
	// echo "<br>Converted the products options table...";
	///////////////// End of products options ////////////////////////////
	///////////////////////////////////////////////////////////////////
	//////////////////// Converting products options values /////////////
	$savebuffer .= "DROP TABLE IF EXISTS products_options_values;" . $eol;
	$savebuffer .= "CREATE TABLE products_options_values (" . $eol;
	$savebuffer .= "  products_options_values_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  language_id int(11) NOT NULL default '1'," . $eol;
	$savebuffer .= "  products_options_values_name varchar(64) NOT NULL default ''," . $eol;
	$savebuffer .= "  PRIMARY KEY  (products_options_values_id,language_id)" . $eol;
	$savebuffer .= ") TYPE=MyISAM;" . $eol;
	$prodopval_query = tep_db_query("select * from products_options_values");
	while ($prodopval = tep_db_fetch_array($prodopval_query)) {
		$savebuffer .= "INSERT INTO products_options_values (products_options_values_id, language_id, products_options_values_name) VALUES (";
		$savebuffer .= $prodopval["products_options_values_id"].",".$prodopval["language_id"].",'".rem($prodopval["products_options_values_name"])."');" . $eol;
		//fflush($backupfile);
	}
	// echo "<br>Converted the products options values table...";
	/////////////////// End of converting products options values //////////
	///////////////////////////////////////////////////////////////////
	////////// Converting products to value /////////////////////////
	$auto_query = tep_db_query("select max(products_options_values_to_products_options_id)+1 as newvalue from products_options_values_to_products_options");
	$auto = tep_db_fetch_array($auto_query);
	if (isset($auto["newvalue"]) && $auto["newvalue"] == '') {
		$realautoincrement = $auto["newvalue"];
	}
	else {
		$realautoincrement = 0;
	}
	$savebuffer .= "DROP TABLE IF EXISTS products_options_values_to_products_options;" . $eol;
	$savebuffer .= "CREATE TABLE products_options_values_to_products_options (" . $eol;
	$savebuffer .= "  products_options_values_to_products_options_id int(11) NOT NULL auto_increment," . $eol;
	$savebuffer .= "  products_options_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  products_options_values_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  PRIMARY KEY  (products_options_values_to_products_options_id)" . $eol;
	$savebuffer .= ") TYPE=MyISAM AUTO_INCREMENT=".$realautoincrement.";" . $eol;
	$prodstuff_query = tep_db_query("select * from products_options_values_to_products_options");
	while ($prodstuff = tep_db_fetch_array($prodstuff_query)) {
		$savebuffer .= "INSERT INTO products_options_values_to_products_options (products_options_values_to_products_options_id, products_options_id, products_options_values_id) VALUES (";
		$savebuffer .= $prodstuff["products_options_values_to_products_options_id"].",".$prodstuff["products_options_id"].",".$prodstuff["products_options_values_id"].");" . $eol;
		//fflush($backupfile);
	}
	unset($auto);
	unset($auto_query);
	// echo "<br>Converted the products options values to products options table...";
	//////////////// End of converting products to values ///////////////
	///////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////
	///////// Converting products to categories /////////////
	$savebuffer .= "DROP TABLE IF EXISTS products_to_categories;" . $eol;
	$savebuffer .= "CREATE TABLE products_to_categories (" . $eol;
	$savebuffer .= "  products_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  categories_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  PRIMARY KEY  (products_id,categories_id)" . $eol;
	$savebuffer .= ") TYPE=MyISAM;" . $eol;
	$prodkitty_query = tep_db_query("select * from products_to_categories");
	while ($prodkitty = tep_db_fetch_array($prodkitty_query)) {
		$savebuffer .= "INSERT INTO products_to_categories (products_id, categories_id) VALUES (";
		$savebuffer .= $prodkitty["products_id"].",".$prodkitty["categories_id"].");" . $eol;
		//fflush($backupfile);
	}
	// echo "<br>Converted the products to categories table...";
	////////// End of converting products to categories ////////////
	///////////////////////////////////////////////////////////////////
	/////////// Converting the reviews table //////////////////////////
	$auto_query = tep_db_query("select max(reviews_id)+1 as newvalue from reviews");
	$auto = tep_db_fetch_array($auto_query);
	if (isset($auto["newvalue"]) && $auto["newvalue"] == '') {
		$realautoincrement = $auto["newvalue"];
	}
	else {
		$realautoincrement = 0;
	}
	$savebuffer .= "DROP TABLE IF EXISTS reviews;" . $eol;
	$savebuffer .= "CREATE TABLE reviews (" . $eol;
	$savebuffer .= "  reviews_id int(11) NOT NULL auto_increment," . $eol;
	$savebuffer .= "  products_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  customers_id int(11) default NULL," . $eol;
	$savebuffer .= "  customers_name varchar(64) NOT NULL default ''," . $eol;
	$savebuffer .= "  reviews_rating int(1) default NULL," . $eol;
	$savebuffer .= "  date_added datetime default NULL," . $eol;
	$savebuffer .= "  last_modified datetime default NULL," . $eol;
	$savebuffer .= "  reviews_read int(5) NOT NULL default '0'," . $eol;
	$savebuffer .= "  PRIMARY KEY  (reviews_id)" . $eol;
	$savebuffer .= ") TYPE=MyISAM AUTO_INCREMENT=".$realautoincrement.";" . $eol;
	$reviews_query = tep_db_query("select * from reviews");
	while ($reviews = tep_db_fetch_array($reviews_query)) {
		$savebuffer .= "INSERT INTO reviews (reviews_id, products_id, customers_id, customers_name, reviews_rating, date_added, last_modified, reviews_read) VALUES (";
		$savebuffer .= "'".$reviews["reviews_id"]."','".$reviews["products_id"]."','".$reviews["customers_id"]."','".rem($reviews["customers_name"])."','".rem($reviews["reviews_rating"])."','".$reviews["date_added"]."','".$reviews["last_modified"]."','".rem($reviews["reviews_read"])."');" . $eol;
		//fflush($backupfile);                                  
	}
	unset($auto);
	unset($auto_query);
	// echo "<br>Converted the reviews table...";
	////////////// End of converting the reviews table //////////////
	///////////////////////////////////////////////////////////////////
	///////////////// Converting the reviews description table ////////
	$savebuffer .= "DROP TABLE IF EXISTS reviews_description;" . $eol;
	$savebuffer .= "CREATE TABLE reviews_description (" . $eol;
	$savebuffer .= "  reviews_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  languages_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  reviews_text text NOT NULL," . $eol;
	$savebuffer .= "  PRIMARY KEY  (reviews_id,languages_id)" . $eol;
	$savebuffer .= ") TYPE=MyISAM;" . $eol;
	$reviewdes_query = tep_db_query("select * from reviews_description");
	while ($reviewdes = tep_db_fetch_array($reviewdes_query)) {
		$savebuffer .= "INSERT INTO reviews_description (reviews_id, languages_id, reviews_text) VALUES (";
		$savebuffer .= $reviewdes["reviews_id"].",".$reviewdes["languages_id"].",'".rem($reviewdes["reviews_text"])."');" . $eol;
		//fflush($backupfile); 
	}
	// echo "<br>Converted the reviews description table...";
	///////////////// End of converting the reviews description table /////////
	///////////////////////////////////////////////////////////////////
	///////////////// Converting the specials table ////////////////////
	$auto_query = tep_db_query("select max(specials_id)+1 as newvalue from specials");
	$auto = tep_db_fetch_array($auto_query);
	if (isset($auto["newvalue"]) && $auto["newvalue"] == '') {
		$realautoincrement = $auto["newvalue"];
	}
	else {
		$realautoincrement = 0;
	}
	$savebuffer .= "DROP TABLE IF EXISTS specials;" . $eol;
	$savebuffer .= "CREATE TABLE specials (" . $eol;
	$savebuffer .= "  specials_id int(11) NOT NULL auto_increment," . $eol;
	$savebuffer .= "  products_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  specials_new_products_price decimal(15,4) NOT NULL default '0.0000'," . $eol;
	$savebuffer .= "  specials_date_added datetime default NULL," . $eol;
	$savebuffer .= "  specials_last_modified datetime default NULL," . $eol;
	$savebuffer .= "  expires_date datetime default NULL," . $eol;
	$savebuffer .= "  date_status_change datetime default NULL," . $eol;
	$savebuffer .= "  status int(1) NOT NULL default '1'," . $eol;
	$savebuffer .= "  PRIMARY KEY  (specials_id)" . $eol;
	$savebuffer .= ") TYPE=MyISAM AUTO_INCREMENT=".$realautoincrement.";" . $eol;
	$specials_query = tep_db_query("select * from specials");
	while ($specials = tep_db_fetch_array($specials_query)) {
		$savebuffer .= "INSERT INTO specials (specials_id, products_id, specials_new_products_price, specials_date_added, specials_last_modified, expires_date, date_status_change, status) VALUES (";
		$savebuffer .= $specials["specials_id"].",".$specials["products_id"].",".$specials["specials_new_products_price"].",'".$specials["specials_date_added"]."','".$specials["specials_last_modified"]."','".$specials["expires_date"]."','".$specials["date_status_change"]."','".$specials["status"]."');" . $eol;
		//fflush($backupfile); 
	}
	unset($auto);
	unset($auto_query);
	// echo "<br>Converted the specials table...";
	///////////////// End of converting the specials table ////////////
	///////////////////////////////////////////////////////////////////
	///////////////////// Converting the tax class table /////////////
	$auto_query = tep_db_query("select max(tax_class_id)+1 as newvalue from tax_class");
	$auto = tep_db_fetch_array($auto_query);
	if (isset($auto["newvalue"]) && $auto["newvalue"] == '') {
		$realautoincrement = $auto["newvalue"];
	}
	else {
		$realautoincrement = 0;
	}
	$savebuffer .= "DROP TABLE IF EXISTS tax_class;" . $eol;
	$savebuffer .= "CREATE TABLE tax_class (" . $eol;
	$savebuffer .= "  tax_class_id int(11) NOT NULL auto_increment," . $eol;
	$savebuffer .= "  tax_class_title varchar(32) NOT NULL default ''," . $eol;
	$savebuffer .= "  tax_class_description varchar(255) NOT NULL default ''," . $eol;
	$savebuffer .= "  last_modified datetime default NULL," . $eol;
	$savebuffer .= "  date_added datetime NOT NULL default '0000-00-00 00:00:00'," . $eol;
	$savebuffer .= "  PRIMARY KEY  (tax_class_id)" . $eol;
	$savebuffer .= ") TYPE=MyISAM AUTO_INCREMENT=".$realautoincrement.";" . $eol;
	$taxclass_query = tep_db_query("select * from tax_class");
	while ($taxclass = tep_db_fetch_array($taxclass_query)) {
		$savebuffer .= "INSERT INTO tax_class (tax_class_id, tax_class_title, tax_class_description, last_modified, date_added) VALUES (";
		$savebuffer .= $taxclass["tax_class_id"].",'".rem($taxclass["tax_class_title"])."','".rem($taxclass["tax_class_description"])."','".$taxclass["last_modified"]."','".$taxclass["date_added"]."');" . $eol;
		//fflush($backupfile);
	}
	unset($auto);
	unset($auto_query);
	// echo "<br>Converted the tax class table...";
	////////////////////// End of converting the tax class table ////////////
	///////////////////////////////////////////////////////////////////
	////////////////////// Converting the tax_rates table ///////////////////
	$auto_query = tep_db_query("select max(tax_rates_id)+1 as newvalue from tax_rates");
	$auto = tep_db_fetch_array($auto_query);
	if (isset($auto["newvalue"]) && $auto["newvalue"] == '') {
		$realautoincrement = $auto["newvalue"];
	}
	else {
		$realautoincrement = 0;
	}
	$savebuffer .= "DROP TABLE IF EXISTS tax_rates;" . $eol;
	$savebuffer .= "CREATE TABLE tax_rates (" . $eol;
	$savebuffer .= "  tax_rates_id int(11) NOT NULL auto_increment," . $eol;
	$savebuffer .= "  tax_zone_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  tax_class_id int(11) NOT NULL default '0'," . $eol;
	$savebuffer .= "  tax_priority int(5) default '1'," . $eol;
	$savebuffer .= "  tax_rate decimal(7,4) NOT NULL default '0.0000'," . $eol;
	$savebuffer .= "  tax_description varchar(255) NOT NULL default ''," . $eol;
	$savebuffer .= "  last_modified datetime default NULL," . $eol;
	$savebuffer .= "  date_added datetime NOT NULL default '0000-00-00 00:00:00'," . $eol;
	$savebuffer .= "  PRIMARY KEY  (tax_rates_id)" . $eol;
	$savebuffer .= ") TYPE=MyISAM AUTO_INCREMENT=".$realautoincrement.";" . $eol;
	$taxrates_query = tep_db_query("select * from tax_rates");
	while ($tax_rates = tep_db_fetch_array($taxrates_query)) {
		$savebuffer .= "INSERT INTO tax_rates (tax_rates_id, tax_zone_id, tax_class_id, tax_priority, tax_rate, tax_description, last_modified, date_added) VALUES (";
		$savebuffer .= "'".$tax_rates["tax_rates_id"]."','".$tax_rates["tax_zone_id"]."','".$tax_rates["tax_class_id"]."','".rem($tax_rates["tax_priority"])."','".rem($tax_rates["tax_rate"])."','".rem($tax_rates["tax_description"])."','".$tax_rates["last_modified"]."','".$tax_rates["date_added"]."');" . $eol;
		//fflush($backupfile);
	}
	unset($auto);
	unset($auto_query);
	// echo "<br>Converted the tax rates table...";
	////////////////////// End of converting the tax rates table ///////////
	/////////////////////////////////////////////////////////////////

	$savefileextension = '.sql';
	if (isset($compression) && ($compression == 'gzip')) {
		// check if compression is available
		if (@function_exists('gzencode')) {
			$savefileextension = '.gz';

			$savebuffer = gzencode($savebuffer);
		} else {
			$error = true;
			$error_string = 'Compression not supported.';
		}
	}

	$savefilename = 'creloaded' . date("YmdHis") . $savefileextension;

	if ($savetofile) {
		$dbpath = tep_db_prepare_input($_POST['dbpath']);

		$savefilepath = DIR_FS_CATALOG . $dbpath . $savefilename;

		if (!$savehandle = fopen($savefilepath, 'w')) {
			$error = true;
			$error_string = 'Unable to write file.';
		} else {
			@fwrite($savehandle, $savebuffer);
			
			fclose($savehandle);
		}

		if ($error) {
			echo $error_string;
		} else {
			echo 'Database file created: ' . $savefilepath;
		}
	} else { // on-the-fly download
		$saveencoding = '';
		$savemimetype = '';

		switch ($compression) {
		case 'gzip':
			if (!@ini_get('zlib.output_compression')) {
				$saveencoding = 'x-gzip';
				$savemimetype = 'application/x-gzip';
			}

			break;
		default:
			$savemimetype = 'text/x-sql';
		}

		if (!empty($saveencoding)) {
			header('Content-Encoding: ' . $saveencoding);
		}
		header('Content-Type: ' . $savemimetype);
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');

		header('Content-Disposition: attachment; filename="' . $savefilename . '"');

		header('Pragma: no-cache');

		echo $savebuffer;

		exit();
	}
} else {
	?>

 <html>
		<body>
		<center>
		<h2>osCommerce 2.2 MS2 to CRE Loaded 6.x </h2>
		<h2>Database Converter</h2>

		<form name="convertdatabase" action="<?php echo HTTP_SERVER . $_SERVER['SCRIPT_NAME']; ?>" method="post" id="convertdatabase">
		<input type="hidden" name="action" id="action" value="convert" />
<!--		<label for="dbpath">File path: </label><input type="text" name="dbpath" id="dbpath" />
-->
												 <input type="submit" name="submit" value="Convert" />
												 </form>
											 
												 <p>
									Pressing the Convert Button will start the conversion process.  <br />
									Contact <a href="mailto:software@chainreactionworks.com">software@chainreactionworks.com</a> for support.
											 </p>

												 Copyright &copy; 2005 <a href="http://creloaded.com">Chain Reaction Works, Inc</a>.
																						</center>
																						</body>
																						</html>

																						<?php
																						}
?>








