<?php
/*
  $Id: feed.php,v 1.00 2004/09/07

  Store Data Feed base class

  Contribution based on:

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
  
  Contribution created by: Chemo
*/

class feed {

	/***************************/
	/**     File / Format     **/
	/***************************/
	var $downloadname = 'download.txt'; //download text file name and extension
	var $fields; //this will be populated later
	var $format = array('delimiter' => "\t", 'newline' => "\n"); //standard format is tab delimited
	var $connection = 'ftp'; //FTP or just save it to disk
	var $data = array(); //this will be populated later
	var $html; //used to store the HTML output
	
	/***************************/
	/**       FTP INFO        **/
	/***************************/
	var $ftp_user; //FTP username
	var $ftp_pass; //FTP password
	var $ftp_server; //FTP server

	/***************************/
	/**     Filename Info     **/
	/***************************/
	var $savefilename; //local file name
	var $targetfilename; //remote file name
	
	/***************************/
	/**   Paths and Images    **/
	/***************************/
	var $autoenlarge = true; //autoenlarge on by default, 500 px / 500 px
	var $imageurl = 'http://yourdomain.com/images/'; //trailing slash on the address
	var $imagepath = '/home/username/public_html/images/'; //trailing slash on the path
	var $feedimageurl = 'http://yourdomain.com/feeds/'; //trailing slash on the address
	var $feedimagepath = '/home/username/public_html/feeds/'; //trailing slash on the path
	var $producturl = 'http://yourdomain.com/product_info.php/id/';//make sure you use the URL version of your store. This example is for search engine safe URL's ON.

	/***************************/
	/**      Main Query       **/
	/***************************/
	// Don't touch this query unless you know what you are doing.  Instead, create a derived class and define it there.
	var $query = 'SELECT * FROM products p 
				  LEFT JOIN products_description pd ON p.products_id = pd.products_id 
				  LEFT JOIN specials sp ON p.products_id = sp.products_id 
				  LEFT JOIN products_to_categories p2c ON p.products_id = p2c.products_id 
				  LEFT JOIN categories_description cd ON p2c.categories_id = cd.categories_id 
				  WHERE p.products_status = 1 GROUP BY p.products_id';

	
	/******************************************************/
	/**                  Class Methods                   **/
	/******************************************************/
	
	function upload ($targetfilename, $savefilename) {
		$conn = ftp_connect($this->ftp_server);		
		$login = ftp_login($conn, $this->ftp_user, $this->ftp_pass);		
		if ((!$conn) || (!$login)) { echo "The FTP connection to $this->ftp_server as user $this->ftp_user <b>FAILED</b>.<BR>"; exit; } 
		else { echo "Connected to $this->ftp_server as user $this->ftp_user ready to upload...<BR>"; }		
		$upload = ftp_put($conn, $this->targetfilename, $this->savefilename, FTP_BINARY);		
		if (!$upload) { echo "The FTP upload has <b>FAILED!</b><br>Local file: $this->savefilename<br>Remote file: $this->targetfilename<BR>"; } 
		else { echo "Uploaded $this->savefilename to $this->ftp_server as $this->targetfilename successfully.<BR>";	}		
		ftp_close($conn);
	}
	
	function savetofile ($data, $savefilename) {
		if ( file_exists( $savefilename ) )
		unlink( $savefilename );		
		$fp = fopen( $savefilename , "w" );
		$fout = fwrite( $fp , $data );
		fclose( $fp );		
	}
	
	function strip ($content) {
		$search = array("![\t ]+$|^[\t ]+!m", '%[\r\n]+%m');
		$replace = array('','');
		return preg_replace($search, $replace, strip_tags( str_replace(">", "> ", $content) ) ) ;				
	}
	
	function file_download($file, $filename, $filetype = 'text/plain')
	{	
		$filetype = 'application/octet-stream';		
		if (file_exists( $file ))
		{
			$fp = fopen( $file , "r" );
			$filecontents = fread( $fp , filesize($file));
			fclose( $fp );
		} else { $filecontents = $file; }
		header('Content-Type: ' . $filetype);
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Content-Length: ' . filesize($file));
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		echo $filecontents;	
		exit;
	}
	
	function viewfile ($file, $filename, $filetype = 'text/plain') {
		header('Content-Type: ' . $filetype);
		header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
		header('Content-Disposition: inline; filename="' . $filename . '"');
		header('Content-Length: ' . strlen($file));
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		echo $file;
		exit; 

	}
	
	function viewfileHTML () {
		return false;
	}
	
	function imageresize($image, $newimage, $newwidth = 500, $newheight = 500) {
	 if (is_file($image)) {	 		
		   $size = getimagesize($image);
		   $width  = $size[0];
		   $height = $size[1];
		   $type   = $size[2];
	 	   switch ($type) {
			  case 2 :				 
					 $im = imagecreatefromjpeg($image);
				 break;
			  case 3 :				 
					 $im = imagecreatefrompng($image);
				 break;
			  case 1 :
			  default:
			  	$fd = @fopen($image,"r");
			    $image_string = fread($fd,filesize($image));
			    fclose($fd);
			    	$im = imagecreatefromstring($image_string);					 			 
				break;				
		  	 } //End switch
		//check proportions
		if ($newheight && ($width < $height)) {
			$newwidth = ($newheight / $height) * $width;
		 } else {
			 $newheight = ($newwidth / $width) * $height;
		 }
		//make the new image with proportions
		$newim = imagecreatetruecolor ($newwidth, $newheight);
		//copy it over
		imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$width,$height);
		//save it to file
	 	   switch ($type) {
			  case 3 :
					 imagepng($newim, $newimage);
					 break;
			  case 1 :
			  		$newimage .= '.JPG';
			  case 2 :
			  default:
					imagejpeg($newim, $newimage, 80);
					break;
		  	 } //End switch
		//kill the temps
		imagedestroy($im);
		imagedestroy($newim);		
		return true; //good to go
	 } else {  
	  			return false; //image wasn't a file
	 		}
	}
	
	function checkimage ($imagename) {		
		if (!is_dir($this->feedimagepath)) { @mkdir($this->feedimagepath,0777); @chmod($this->feedimagepath,0777); }
		$image = $this->imagepath . $imagename; $newimage = $this->feedimagepath . $imagename;  
		$size = getimagesize($image);
		   $width  = $size[0];
		   $height = $size[1];
		   $type   = $size[2];
		   switch ($type) {
		   case 1: 
		   	$feedimagename = $imagename . '.JPG';
			break;
		   case 2;
		   case 3;
		   	$feedimagename = $imagename;
		   	 break;
		   }		   
		   if ($this->autoenlarge){
				 if (!is_file($this->feedimagepath . $feedimagename)) {
					$this->imageresize($this->imagepath . $imagename, $this->feedimagepath . $imagename); 
					return $this->feedimageurl . $feedimagename;			
				 }
				 else {
						return $this->feedimageurl . $feedimagename;
					   }
			} //autoenlarge 
			else {
					return $this->imageurl . $imagename;
				}				
	}
}
?>