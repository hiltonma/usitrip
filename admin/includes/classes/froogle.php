<?php
/*
  $Id: froogle.php,v 1.00 2004/09/07

  Froogle Data Feed extended class

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
  
  Contribution created by: Chemo
*/

require (DIR_WS_CLASSES . 'feed.php');
class froogle extends feed {
	var $links; //optional list of links to be displayed above the content
	function froogle (){ //class contructor

	/****************************/
	/**     FTP and fields     **/
	/****************************/
	//
	/// ! DON'T change these !
	$this->fields = "product_url\tname\tdescription\tprice\timage_url\tcategory\n"; //basic feed
	$this->connection = 'ftp'; //FTP the data
	//
	/// ! Change these settings !
	$this->ftp_user = 'username'; //FTP username
	$this->ftp_pass = 'password'; //FTP password
	$this->ftp_server = 'hedwig.google.com'; //FTP server
	$this->targetfilename = 'yourfilename.txt'; //this is the name of the file once uploaded
	
	/*****************************/
	/**     Paths and URL's     **/
	/*****************************/
	
	//Where do you want to save the generated TXT file?  Be sure to include the FULL path and title.
	// ! REQUIRED !
	/// Make sure this is your FULL path to the save directory and name of file
	$this->savefilename = '/var/www/html/usitrip/feeds/love_scent.txt';
	
	//Do you want to enable auto enlarging for images?
	//Default is true and dimensions are proportional 500px by 500px
	$this->autoenlarge = true;
	
	//Where do you want to save the enlarged image? Be sure to include the FULL path and title.
	// ! REQUIRED !
	/// Make sure this is the FULL path to your feed image directory
	$this->feedimagepath = '/var/www/html/usitrip/feeds/'; //Trailing slash required!
	
	//What is the URL for the feed (enlarged) image folder?
	// ! REQUIRED !
	/// This is the URL to the feed images directory
	$this->feedimageurl = 'http://yourdomain.com/feeds/'; //Trailing slashes required
	
	//What is the path to your images folder?
	// ! REQUIRED !
	/// This is the FULL path to your normal images directory
	$this->imagepath = '/var/www/html/usitrip/images/'; //Trailing slash required!
	
	//What is the URL for the images folder?
	// ! REQUIRED !
	/// This is the URL to your images directory
	$this->imageurl = 'http://yourdomain.com/images/'; //Trailing slashes required!
	
	//Use this to output links above the content
	// ! OPTIONAL !
	///Put your own links that are displayed above the content
	$this->links = '';
	}
	
	/******************************************************/
	/**                  Class Methods                   **/
	/******************************************************/

	function makedata() {	
	$data_query = tep_db_query($this->query);	
		while ( $data = tep_db_fetch_array($data_query) ) {
			if ($data['specials_new_products_price']) { //if it's on special
				$data['products_price'] = $data['specials_new_products_price']; //show the special price 
				}
			 $data['products_image'] = $this->checkimage($data['products_image']); //check to see if the image is already enlarged

			$this->data[$data['products_id']] = array ( 'product_url' => $this->producturl . $data['products_id'],
														'name' => $this->strip($data['products_name']),
														'description' => $this->strip($data['products_description']),
														'price' => number_format($data['products_price'], 2, '.', ''),
														'image_url' => $data['products_image'],
														'category' => $data['categories_name'] );
			 foreach ($this->data[$data['products_id']] as $column => $value) {
			 	$this->fields .= $value . $this->format['delimiter'];
			 	}			 
			 $this->fields = rtrim($this->fields, $this->format['delimiter']) . $this->format['newline'];			 			 
		}		
	}
	
	function viewfileHTML ($links='') {
	$this->html = $links.'<style type="text/css">
					<!--
					td, a {
						font-family: Arial, Helvetica, sans-serif;
						font-size:12px;
						}
					.highlight {
					background-color: #CCCCCC;
					}
					-->
					</style>
					<table width="100%">
					<tr>
						<td>URL</td>
						<td>Name</td>
						<td>Description</td>
						<td>Price</td>
						<td>Image</td>
						<td>Category</td>
					</tr>';
		$data_query = tep_db_query($this->query);	
		$on=false;
		while ( $data = tep_db_fetch_array($data_query) ) {
			if($on) {$class=' class="highlight"';$on=false; } else { $class='';$on=true; }			 		
			if ($data['specials_new_products_price']) {
				$data['products_price'] = $data['specials_new_products_price'];				
				$b = '<b>'; $bend = '</b>';
				} else {$b = ''; $bend = '';}
			$this->html .= '<tr>';
			$this->data[$data['products_id']] = array ( 'product_url' => '<a href="'.$this->producturl . $data['products_id'].'">'.$data['products_id'].'</a>',
														'name' => '<a href="'.$this->producturl . $data['products_id'].'">'.$this->strip($data['products_name']).'</a>',
														'description' => substr($this->strip($data['products_description']), 0, 100).' <b>...</b>',
														'price' => number_format($data['products_price'], 2, '.', ''),
														'image_url' => '<a href="'.$this->checkimage($data['products_image']).'">'.$data['products_image'].'</a>',
														'category' => $data['categories_name'] );
			 foreach ($this->data[$data['products_id']] as $column => $value) {
			 	$this->html .= '<td valign="top" align="left"'.$class.'>'.$b .$value . $bend .'</td>';
			 	}			 
			 $this->html .= '</tr>';			 			 
		}
		$this->html .= '</table>';
		echo $this->html;
	}	
}
?>
