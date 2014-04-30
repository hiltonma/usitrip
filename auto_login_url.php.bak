<?php
require('includes/application_top.php');
$url = '';
if($Admin->login_id && $_GET['order_id'] && $_SESSION['customer_id']){
	$url = $autoLogin->make_url(tep_href_link(FILENAME_ACCOUNT_HISTORY_INFO, 'order_id=' . (int)$_GET['order_id'], 'SSL'), $_SESSION['customer_id']);
	ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>无标题文档</title>
<style>
*{font-size:12px;}
</style>
<script type="text/javascript" src="jquery-1.3.2/merger/merger.min.js"></script>
</head>

<body>
<div>用户订单URL：
<textarea name="select_all" cols="100" rows="2" class="select_all" wrap="wrap"><?= $url;?></textarea>
</div>
</body>
</html>
<script type="text/javascript">
jQuery(function(x){
	x('.select_all').bind('click',function(){
		if(x.browser.msie) this.createTextRange().select();
		else {
			this.selectionStart = 0;
			this.selectionEnd = this.value.length;
		}
	});
});
</script>

<?php
echo db_to_html(ob_get_clean());
}
?>