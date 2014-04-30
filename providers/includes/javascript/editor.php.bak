<?php
// configurations
define('HTML_EDITOR_ENABLE','Enable'); //Automatically use the WYSIWYG interface Enable/Disable
define('HTML_EDITOR_HEIGHT','300'); //Height, in pixels, of the WYSIWYG interface
define('HTML_EDITOR_WIDTH','800'); //Height, in pixels, of the WYSIWYG interface
define('HTML_EDITOR_LANG','en');//Force editor to use english language, if none, will use default admin language
define('HTML_EDITOR_INTERFACE','tiny_mce'); // The WYSIWYG interface to use when editing pages, we may add more in future

// TinyMCE Specific
define('HTML_EDITOR_TOOLBAR_SET','advanced');//toolbar set options, simple, advanced, creloaded(default), for mailing scripts, it uses basic.
define('HTML_EDITOR_TOOLBAR_LOCATION','top');// top or bottom
define('HTML_EDITOR_PATH_LOCATION','top'); // top or bottom
define('HTML_EDITOR_TOOLBAR_ALIGN','left'); // left / right / center
define('HTML_EDITOR_COMPRESS','true');// Use gzip-compressed editor
define('HTML_EDITOR_IMAGE_MANAGER','true');// show hide image manager in html editor, false will return default advimage plugin
define('HTML_EDITOR_PLUGINS','emotions,paste,advimage,advlink,advhr,searchreplace,spellcheck');// Under development
define('HTML_EDITOR_LOAD_CSS','true'); // load default template CSS
define('HTML_EDITOR_CONVERT_NEW_LINE','false');// will allow "Enter" to <br>
// end configurations


//Main Functions for html editor calls
if ((HTML_EDITOR_ENABLE == 'Enable') && (file_exists(DIR_FS_INCLUDES . 'javascript/' . HTML_EDITOR_INTERFACE . '.php'))){ 

require(DIR_FS_INCLUDES . 'javascript/' . HTML_EDITOR_INTERFACE . '.php');

} else {

function tep_load_html_editor() {
  echo '<!-- No editor loaded -->' . "\n";
}
function tep_insert_html_editor ( $textarea, $tool_bar_set = '', $editor_height = '' ) {  
  echo '<!-- No editors to load -->' . "\n";
}
}
?>