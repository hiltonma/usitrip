<?php
function tep_load_html_editor() {
if (( HTML_EDITOR_COMPRESS  == 'true' ) && (!extension_loaded('zlib')) )  {
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Gecko') ){ 
				 define('HTML_EDITOR_LOADER','tiny_mce_gzip.php'); 
		} else if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ) {
				 define('HTML_EDITOR_LOADER','tiny_mce.js'); 
		} else {
				 define('HTML_EDITOR_LOADER','tiny_mce.js');
				 }
		} else { 
				define('HTML_EDITOR_LOADER','tiny_mce.js');
				}
		echo "\n" . '<script type="text/javascript" src="' . DIR_WS_INCLUDES . 'javascript/tiny_mce/' . HTML_EDITOR_LOADER . '"></script>' . "\n";
}// end tep_insert_html_editor

	function tep_insert_html_editor ( $textarea, $tool_bar_set = HTML_EDITOR_TOOLBAR_SET, $editor_height = HTML_EDITOR_HEIGHT ) {	
// Current stylesheet to be loaded into editor for enhanced editing.
if (HTML_EDITOR_LOAD_CSS == 'true') {
$template_query = tep_db_query("select configuration_id, configuration_title, configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_TEMPLATE'");
$template = tep_db_fetch_array($template_query);
$template_default_style_css = 'content_css : "' . HTTP_SERVER . DIR_WS_TEMPLATES . $template['configuration_value'] . '/stylesheet.css",';
}
// End CSS Code
// Tell editor valid elements
$editor_valid_lements =    'extended_valid_elements : ""
                +"a[href|title|target|onclick|id|name|style|class],"
                +"img[id|class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|"
                +"onmouseout|name|style|usemap],"
                +"div[class|style|id|name|align],"
                +"span[class|style|id|name],"
                +"font[face|size|color|style],table[bgcolor|border=0|cellspacing|cellpadding|width|height|class|align],tr[bgcolor|class|rowspan|width|height|align|valign],td[bgcolor|class|colspan|rowspan|width|height|align|valign],th[bgcolor|class|colspan|rowspan|width|height|align|valign],thead[bgcolor|class|colspan|rowspan|width|height|align|valign],tbody[bgcolor|class|colspan|rowspan|width|height|align|valign],script[src|type|language],iframe[src|width|height|class|border|align|scrolling],"';
// end valid elements

		switch ($tool_bar_set) {
		case "simple":
		echo '<script language="javascript" type="text/javascript">
		tinyMCE.init({
		theme : "advanced",
		mode : "exact",
		//browsers : "msie,safari,gecko,opera",
		elements : "'.$textarea.'",
		width : "'.HTML_EDITOR_WIDTH.'",
		height : "'.$editor_height.'",
		convert_urls : "false",
		convert_newlines_to_brs : "'.HTML_EDITOR_CONVERT_NEW_LINE.'",
		language : "'.DEFAULT_LANGUAGE.'",
		lang_list : "en,de,fr,pt_br,nl,pl,se",
		theme_advanced_toolbar_location : "'.HTML_EDITOR_TOOLBAR_LOCATION.'",
		theme_advanced_toolbar_align : "'.HTML_EDITOR_TOOLBAR_ALIGN.'",
		theme_advanced_path_location : "'.HTML_EDITOR_PATH_LOCATION.'",
		plugin_preview_pageurl : "../../plugins/preview/preview.php",
		plugins : "preview,contextmenu,advlink,paste,fullscreen,directionality,searchreplace",
		theme_advanced_buttons1 : "newdocument,separator,cut,copy,paste,pasteword,undo,redo,separator,bold,italic,underline,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,outdent,indent,separator,search,replace,separator,cleanup,removeformat",
		theme_advanced_buttons2 : "formatselect,fontselect,fontsizeselect,sub,sup,forecolor,backcolor,charmap,visualaid,separator,link,unlink,anchor",
		theme_advanced_buttons3 : "fullscreen,preview,separator,ltr,rtl,separator,code,help",
		'.$editor_valid_lements.'
	});
	</script>';
	break;
		case "advanced":
		echo '<script type="text/javascript">
       	tinyMCE.init({
		theme : "advanced",
		mode : "exact",
		//browsers : "msie,safari,gecko,opera",
		elements : "'.$textarea.'",
		width : "'.HTML_EDITOR_WIDTH.'",
		height : "'.$editor_height.'",
		convert_urls : "false",
		convert_newlines_to_brs : "'.HTML_EDITOR_CONVERT_NEW_LINE.'",
		language : "'.DEFAULT_LANGUAGE.'",
		lang_list : "en,de,fr,pt_br,nl,pl,se",
		theme_advanced_toolbar_location : "'.HTML_EDITOR_TOOLBAR_LOCATION.'",
		theme_advanced_toolbar_align : "'.HTML_EDITOR_TOOLBAR_ALIGN.'",
		theme_advanced_path_location : "'.HTML_EDITOR_PATH_LOCATION.'",
		'.$template_default_style_css.'
		plugin_preview_pageurl : "../../plugins/preview/preview.php",
		plugins : "table,preview,contextmenu,advlink,paste,fullscreen,directionality,searchreplace,ibrowser,filemanager",
		theme_advanced_buttons1 : "newdocument,separator,cut,copy,paste,pasteword,undo,redo,separator,bold,italic,underline,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,outdent,indent,separator,search,replace,separator,cleanup,removeformat",
		theme_advanced_buttons2 : "formatselect,fontselect,fontsizeselect,styleselect,sub,sup,forecolor,backcolor,charmap,visualaid,separator,link,unlink,anchor",
		theme_advanced_buttons3 : "fullscreen,preview,ibrowser,filemanager,separator,tablecontrols,separator,ltr,rtl,separator,code,help",
		'.$editor_valid_lements.'
	});
	</script>';
		break;
		case "customcomment":
		echo '<script language="javascript" type="text/javascript">
		tinyMCE.init({
		theme : "advanced",
		mode : "exact",
		//browsers : "msie,safari,gecko,opera",
		elements : "'.$textarea.'",
		width : "'.HTML_EDITOR_WIDTH.'",
		height : "'.$editor_height.'",
		convert_urls : "false",
		convert_newlines_to_brs : "'.HTML_EDITOR_CONVERT_NEW_LINE.'",
		language : "en",
		lang_list : "en,de,fr,pt_br,nl,pl,se",
		theme_advanced_toolbar_location : "'.HTML_EDITOR_TOOLBAR_LOCATION.'",
		theme_advanced_toolbar_align : "'.HTML_EDITOR_TOOLBAR_ALIGN.'",
		theme_advanced_path_location : "",
		plugin_preview_pageurl : "../../plugins/preview/preview.php",
		plugins : "preview,contextmenu,advlink,paste,fullscreen,directionality,ibrowser,searchreplace",
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,forecolor,backcolor,formatselect,fontselect,fontsizeselect,link,unlink,code",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		'.$editor_valid_lements.'
	});
	</script>';
		break;
	}
}	
?>