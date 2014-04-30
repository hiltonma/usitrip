<?php
/*
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2010 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * This is the File Manager Connector for PHP.
 */

function GetFolders( $resourceType, $currentFolder )
{
	// Map the virtual path to the local server path.
	$sServerDir = ServerMapFolder( $resourceType, $currentFolder, 'GetFolders' ) ;

	// Array that will hold the folders names.
	$aFolders	= array() ;

	$oCurrentFolder = @opendir( $sServerDir ) ;

	if ($oCurrentFolder !== false)
	{
		while ( $sFile = readdir( $oCurrentFolder ) )
		{
			if ( $sFile != '.' && $sFile != '..' && is_dir( $sServerDir . $sFile ) )
				$aFolders[] = '<Folder name="' . ConvertToXmlAttribute( $sFile ) . '" />' ;
		}
		closedir( $oCurrentFolder ) ;
	}

	// Open the "Folders" node.
	echo "<Folders>" ;

	natcasesort( $aFolders ) ;
	foreach ( $aFolders as $sFolder )
		echo $sFolder ;

	// Close the "Folders" node.
	echo "</Folders>" ;
}

function GetFoldersAndFiles( $resourceType, $currentFolder )
{
	// Map the virtual path to the local server path.
	$sServerDir = ServerMapFolder( $resourceType, $currentFolder, 'GetFoldersAndFiles' ) ;

	// Arrays that will hold the folders and files names.
	$aFolders	= array() ;
	$aFiles		= array() ;

	$oCurrentFolder = @opendir( $sServerDir ) ;

	if ($oCurrentFolder !== false)
	{
		while ( $sFile = readdir( $oCurrentFolder ) )
		{
			if ( $sFile != '.' && $sFile != '..' )
			{
				if ( is_dir( $sServerDir . $sFile ) )
					$aFolders[] = '<Folder name="' . ConvertToXmlAttribute( $sFile ) . '" />' ;
				else
				{
					$iFileSize = @filesize( $sServerDir . $sFile ) ;
					if ( !$iFileSize ) {
						$iFileSize = 0 ;
					}
					if ( $iFileSize > 0 )
					{
						$iFileSize = round( $iFileSize / 1024 ) ;
						if ( $iFileSize < 1 )
							$iFileSize = 1 ;
					}

					$aFiles[] = '<File name="' . ConvertToXmlAttribute( $sFile ) . '" size="' . $iFileSize . '" />' ;
				}
			}
		}
		closedir( $oCurrentFolder ) ;
	}

	// Send the folders
	natcasesort( $aFolders ) ;
	echo '<Folders>' ;

	foreach ( $aFolders as $sFolder )
		echo $sFolder ;

	echo '</Folders>' ;

	// Send the files
	natcasesort( $aFiles ) ;
	echo '<Files>' ;

	foreach ( $aFiles as $sFiles )
		echo $sFiles ;

	echo '</Files>' ;
}

function CreateFolder( $resourceType, $currentFolder )
{
	if (!isset($_GET)) {
		global $_GET;
	}
	$sErrorNumber	= '0' ;
	$sErrorMsg		= '' ;

	if ( isset( $_GET['NewFolderName'] ) )
	{
		$sNewFolderName = $_GET['NewFolderName'] ;
		$sNewFolderName = SanitizeFolderName( $sNewFolderName ) ;

		if ( strpos( $sNewFolderName, '..' ) !== FALSE )
			$sErrorNumber = '102' ;		// Invalid folder name.
		else
		{
			// Map the virtual path to the local server path of the current folder.
			$sServerDir = ServerMapFolder( $resourceType, $currentFolder, 'CreateFolder' ) ;

			if ( is_writable( $sServerDir ) )
			{
				$sServerDir .= $sNewFolderName ;

				$sErrorMsg = CreateServerFolder( $sServerDir ) ;

				switch ( $sErrorMsg )
				{
					case '' :
						$sErrorNumber = '0' ;
						break ;
					case 'Invalid argument' :
					case 'No such file or directory' :
						$sErrorNumber = '102' ;		// Path too long.
						break ;
					default :
						$sErrorNumber = '110' ;
						break ;
				}
			}
			else
				$sErrorNumber = '103' ;
		}
	}
	else
		$sErrorNumber = '102' ;

	// Create the "Error" node.
	echo '<Error number="' . $sErrorNumber . '" />' ;
}

function FileUpload( $resourceType, $currentFolder, $sCommand )
{
	if (!isset($_FILES)) {
		global $_FILES;
	}
	$sErrorNumber = '0' ;
	$sFileName = '' ;

	if ( isset( $_FILES['NewFile'] ) && !is_null( $_FILES['NewFile']['tmp_name'] ) )
	{
		global $Config ;

		$oFile = $_FILES['NewFile'] ;

		// Map the virtual path to the local server path.
		$sServerDir = ServerMapFolder( $resourceType, $currentFolder, $sCommand ) ;

		// Get the uploaded file name.
		$sFileName = $oFile['name'] ;
		$sFileName = SanitizeFileName( $sFileName ) ;

		$sOriginalFileName = $sFileName ;

		// Get the extension.
		$sExtension = substr( $sFileName, ( strrpos($sFileName, '.') + 1 ) ) ;
		$sExtension = strtolower( $sExtension ) ;

		if ( isset( $Config['SecureImageUploads'] ) )
		{
			if ( ( $isImageValid = IsImageValid( $oFile['tmp_name'], $sExtension ) ) === false )
			{
				$sErrorNumber = '202' ;
			}
		}

		if ( isset( $Config['HtmlExtensions'] ) )
		{
			if ( !IsHtmlExtension( $sExtension, $Config['HtmlExtensions'] ) &&
				( $detectHtml = DetectHtml( $oFile['tmp_name'] ) ) === true )
			{
				$sErrorNumber = '202' ;
			}
		}

		// Check if it is an allowed extension.
		if ( !$sErrorNumber && IsAllowedExt( $sExtension, $resourceType ) )
		{
			$iCounter = 0 ;

			while ( true )
			{
				$sFilePath = $sServerDir . $sFileName ;
                $NewFileName = my_setfilename() . '.jpg';
                $newFIlePath = $sServerDir . $NewFileName;
				if ( is_file( $sFilePath ) )
				{
					$iCounter++ ;
					$sFileName = RemoveExtension( $sOriginalFileName ) . '(' . $iCounter . ').' . $sExtension ;
					$sErrorNumber = '201' ;
				}
				else
				{
					move_uploaded_file( $oFile['tmp_name'], $sFilePath ) ;          
					if ( is_file( $sFilePath ) )
					{
                        
                       list($w_src, $h_src, $type) = getimagesize($sFilePath);     // create new dimensions, keeping aspect ratio
                       //file_put_contents('as.txt', print_r(getimagesize($sFilePath),1));
                       if ($w_src > 750){
                           
                           out_thumbnails($sFilePath, $newFIlePath);
                           @unlink($sFilePath);  //  clean up image storage
                           $sFilePath = $newFIlePath; 
                           $sFileName =  $NewFileName;
                        }
						if ( isset( $Config['ChmodOnUpload'] ) && !$Config['ChmodOnUpload'] )
						{
							break ;
						}

						$permissions = 0777;

						if ( isset( $Config['ChmodOnUpload'] ) && $Config['ChmodOnUpload'] )
						{
							$permissions = $Config['ChmodOnUpload'] ;
						}

						$oldumask = umask(0) ;
						chmod( $sFilePath, $permissions ) ;
						umask( $oldumask ) ;
					}

					break ;
				}
			}

			if ( file_exists( $sFilePath ) )
			{
				//previous checks failed, try once again
				if ( isset( $isImageValid ) && $isImageValid === -1 && IsImageValid( $sFilePath, $sExtension ) === false )
				{
					@unlink( $sFilePath ) ;
					$sErrorNumber = '202' ;
				}
				else if ( isset( $detectHtml ) && $detectHtml === -1 && DetectHtml( $sFilePath ) === true )
				{
					@unlink( $sFilePath ) ;
					$sErrorNumber = '202' ;
				}
			}
		}
		else
			$sErrorNumber = '202' ;
	}
	else
		$sErrorNumber = '202' ;


	$sFileUrl = CombinePaths( GetResourceTypePath( $resourceType, $sCommand ) , $currentFolder ) ;
    $sFileUrl = CombinePaths( $sFileUrl, $sFileName ) ;
    //$sFileUrl = CombinePaths( $sFileUrl, $NewFileName ) ;

	SendUploadResults( $sErrorNumber, $sFileUrl, $sFileName ) ;
    //SendUploadResults( $sErrorNumber, $sFileUrl, $NewFileName ) ;
	exit ;
}
#输出缩图文件，目前适用于jpeg和png图像，暂不支持gif图片
//$input_file = 'zhh04.jpg';
//$out_file = 'zhh04_sm.jpg';
//$max_width=750;
//$max_height=750;
function out_thumbnails($input_file, $out_file, $max_width=750, $max_height=750){	
	// File and new size
	$filename = $input_file;
	$out_file = $out_file;
	// Get image sizes and mime type 
	$image_array = getimagesize($filename);
	//如果图片是非png、gif和jpeg图片则停止继续
	if($image_array[2]!=1 && $image_array[2]!=2 && $image_array[2]!=3){
		return false;
	}
	
	list($width, $height) = $image_array;
	$newwidth = $width;
	$newheight = $height;
	//长度比的最大值
	$max_value = intval(($max_height/$max_width)*100)/100;
	//计算图像长宽比
	$ratio_value = intval(($height/$width)*100)/100;
	if($max_value >= $ratio_value){
		if($width > (int)$max_width){	//长比高大
			$newwidth = (int)$max_width;
			$newheight = (int)($newwidth * $ratio_value);
		}
	}else{
		if($height > (int)$max_height){
			$newheight = (int)$max_height;
			$newwidth = (int)($newheight/$ratio_value);
		}
	}
	
	// Load
	if(function_exists('imagecreatetruecolor')){
		$thumb=imagecreatetruecolor($newwidth, $newheight);//创建新图片并指定大小
	}else{
		$thumb = imagecreate($newwidth, $newheight);
	}
	switch ($image_array[2]) {	//取得图片类型
		case 1:   $source = @imagecreatefromgif($filename);  break;
		case 2:   $source = @imagecreatefromjpeg($filename);  break;
		case 3:   $source = @imagecreatefrompng($filename);  /*imagesavealpha($filename, true);*/  break;
	}

	if(function_exists('imagecopyresampled')){
		imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);	//不失真
	}else{
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height); 	//失真
	}
	
	switch ($image_array[2]) {	//输出图片到文件$dstFile
		case 1:   imagegif($thumb,$out_file);  break;
		case 2:   imagejpeg($thumb,$out_file,100);   break;
		case 3:   /*imagesavealpha($thumb, true);*/ imagepng($thumb,$out_file);  break;
	}
	@imagedestroy($thumb);
	@imagedestroy($source);
	return $out_file;
}
?>
