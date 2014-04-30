/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	config.toolbar_JianDan =[
		['Bold','Italic','Underline','Strike'],
		['NumberedList','BulletedList'],['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['TextColor','BGColor'],
		['Image','Flash','Link','Unlink','Anchor'],'/',
		['Styles','Format','Font','FontSize']
		];
	//config.language = 'zh';
	config.defaultLanguage = 'zh-cn';
	config.skin = 'v2';

	config.disableNativeSpellChecker = false;
	config.scayt_autoStartup = false;


	config.filebrowserBrowseUrl = '/includes/javascript/ckeditor/ckfinder/ckfinder.html';
 	config.filebrowserImageBrowseUrl = '/includes/javascript/ckeditor/ckfinder/ckfinder.html?Type=Images';
 	config.filebrowserFlashBrowseUrl = '/includes/javascript/ckeditor/ckfinder/ckfinder.html?Type=Flash';
 	config.filebrowserUploadUrl = '/includes/javascript/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
 	config.filebrowserImageUploadUrl = '/includes/javascript/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
 	config.filebrowserFlashUploadUrl = '/includes/javascript/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
	
};
