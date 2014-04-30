<?php
//预算层
$popupTip = "popDiv";
$popupConCompare = "popupConCompare";
$h4_contents = '<img src="image/yusuan_icon.gif" align="absmiddle" />&nbsp;'.TEXT_SHOW_DIV_BUDGET;
$buttons = tep_template_image_submit('book_now.gif', IMAGE_BUTTON_IN_CART,'onclick="javascript:closeDivPopSubmit();"');
//如果已经标记为已经售完则不能显示放入购物车和订购按钮了 start
if($product_info['products_stock_status']=='0'){
	$buttons = tep_template_image_button('book_now_out.gif', IMAGE_BUTTON_IN_CART,'onclick="javascript:alert(\''.db_to_html('该团已经卖完').'\');"');
}
$con_contents = '<div id="price_ajax_response"></div><div>'.$buttons.'</div><div style="font-size:12px; font-weight:normal; color:#707070; padding-left:28px;" class="margen_t">'.TEXT_SHOW_DIV_INFO.'</div>';
$PopupObj[] = tep_popup($popupTip, $popupConCompare, "460", $h4_contents, $con_contents );
?>
