<?php
/**
 * FCK编辑器的一些配置在用的时候直接引用此文件即可，所需要的源文件全部在网站根目录的/js/ckfinder和/js/ckeditor中
 */
//php文件的基本路径
define('CKEDITOR_DIR', DIR_FS_DOCUMENT_ROOT.'js/ckeditor/');
define('CKFINDER_DIR', DIR_FS_DOCUMENT_ROOT.'js/ckfinder/');
//编辑器的网址路径
define('CKEDITOR_WS_DIR', HTTP_SERVER.'/js/ckeditor/');
define('CKFINDER_WS_DIR', HTTP_SERVER.'/js/ckfinder/');

// Helper function for this sample file.
function printNotFound( $ver ){
	static $warned;

	if (!empty($warned))
		return;

	echo '<p><br><strong><span class="error">Error</span>: '.$ver.' not found</strong>. ' .
		'This sample assumes that '.$ver.' (not included with CKFinder) is installed in ' .
		'the "ckeditor" sibling folder of the CKFinder installation folder. If you have it installed in ' .
		'a different place, just edit this file, changing the wrong paths in the include ' .
		'(line 57) and the "basePath" values (line 70).</p>' ;
	$warned = true;
}

// This is a check for the CKEditor PHP integration file. If not found, the paths must be checked.
// Usually you'll not include it in your site and use correct path in line 57 and basePath in line 70 instead.
// Remove this code after correcting the include_once statement.
if ( !@file_exists( CKEDITOR_DIR.'ckeditor.php' ) )
{
	if ( @file_exists(CKEDITOR_DIR.'ckeditor.js') || @file_exists(CKEDITOR_DIR.'ckeditor_source.js') )
		printNotFound('CKEditor 3.1+');
	else
		printNotFound('CKEditor');
}

include_once CKEDITOR_DIR.'ckeditor.php' ;
require_once CKFINDER_DIR.'ckfinder.php' ;

// This is a check for the CKEditor class. If not defined, the paths in lines 57 and 70 must be checked.
if (!class_exists('CKEditor'))
{
	printNotFound('CKEditor');
}
else
{
	$initialValue = '<p>Just click the <b>Image</b> or <b>Link</b> button, and then <b>&quot;Browse Server&quot;</b>.</p>' ;

	$ckeditor = new CKEditor( ) ;
	$ckeditor->basePath	= CKEDITOR_WS_DIR; //'../../../ckeditor/' ;

	// Just call CKFinder::SetupCKEditor before calling editor(), replace() or replaceAll()
	// in CKEditor. The second parameter (optional), is the path for the
	// CKFinder installation (default = "/ckfinder/").
	CKFinder::SetupCKEditor( $ckeditor, '../../' ) ;
}
?>