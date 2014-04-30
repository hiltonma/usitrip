<?php
//从2009-07-23开始，所有有landingpage页都在这里处理

if(tep_not_null($_GET['landingpagename']) && strtolower(CHARSET)=='gb2312'){
	$tpl_content = file_get_contents(DIR_FS_CATALOG.'landing-page/'.$_GET['landingpagename'].'/las-sfo-pro.tpl.html');

}elseif(tep_not_null($_GET['landingpagename']) && strtolower(CHARSET)=='big5'){
	$tpl_content = file_get_contents(DIR_FS_CATALOG.'landing-page/'.$_GET['landingpagename'].'/las-sfo-pro_ft.tpl.html');
}

$email_text = str_replace( $patterns ,$replacements, $tpl_content);
echo $email_text;
?>
<?php
//自动更新产品列表的价格
$p=array('/&amp;/','/&quot;/');
$r=array('&','"');
?>
<script type="text/javascript"><!--//
function auto_update_langding_page_price(){
	var span = document.getElementsByTagName("span");
	var s = document.getElementsByTagName("s");
	var p_ids = '';
	for(i=0; i<span.length; i++){
		if(span[i].id.indexOf('new_price_')>-1){
			p_ids += (span[i].id.replace(/new_price_/,'')) + ',';
		}
	}
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('ajax_landing_page.php','action=process')) ?>")+"&p_ids="+p_ids;
	var success_msm = "";
	var success_go_to = "";
	ajax_get_submit(url,success_msm,success_go_to);
}
auto_update_langding_page_price();
//--></script>