<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
        <title><?php echo '转账支付管理系统'; ?></title>
        <link rel="stylesheet" type="text/css" href="domestic/css/ordergl.css">
        <link rel="stylesheet" type="text/css" href="domestic/css/new_sys_main.css">
        <link rel="stylesheet" type="text/css" href="includes/jquery-1.3.2/blue/style.css">
        <link rel="stylesheet" type="text/css" href="includes/jquery-1.3.2/datepicker/jquery.ui.all.css">
        <link rel="stylesheet" type="text/css" href="includes/jquery-1.3.2/nyroModal.css" media="screen">
        <script type="text/javascript" src="includes/javascript/usitrip-tabs-2009-06-19.js"></script>
        <script type="text/javascript" src="includes/javascript/menujs-2008-04-15-min.js"></script>
        <script type="text/javascript" src="includes/javascript/add_global.js"></script>
         <script type="text/javascript" src="includes/javascript/calendar.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

        <script type="text/javascript" src="includes/jquery-1.3.2/chili-1.7.pack.js"></script>
        <script type="text/javascript" src="includes/jquery-1.3.2/jquery.easing.js"></script>
        <script type="text/javascript" src="includes/jquery-1.3.2/jquery.dimensions.js"></script>
        <script type="text/javascript" src="includes/jquery-1.3.2/jquery.accordion.js"></script>
        <script type="text/javascript" src="includes/jquery-1.3.2/jquery.nyroModal-1.6.2.js"></script>
        <script type="text/javascript" src="includes/jquery-1.3.2/datepicker/jquery.ui.core.js"></script>
        <script type="text/javascript" src="includes/jquery-1.3.2/datepicker/jquery.ui.datepicker.js"></script>
        <script type="text/javascript" src="includes/jquery-1.3.2/datepicker/jquery.ui.widget.js"></script>


        <script type="text/javascript" src="includes/ajx.js"></script>
        <script language="javascript" src="includes/menu.js"></script>
        <script language="javascript" src="includes/big5_gb-min.js"></script>
        <script language="javascript" src="includes/general.js"></script>
        <script type="text/javascript">
		if(document.all){
			//alert("由于兼容性的问题，本系统目前只能运行在火狐浏览下面！请使用火狐浏览器，待兼容性问题解决后，此警告将自动取消！");
		}
		//载入href的文件并写到taret中
		function LoadToDom(target,href){
			showPopup("popup","popupConCompare",1);
			$("#"+target).html('<img src="<?= HTTP_SERVER;?>/admin/images/loading.gif" />');
			$("#"+target).load(href);
		}
   
            $(function() {
                $.nyroModalSettings({height: 600, width: 900});
                
                $("#a_2").click(function(){
                    $("#li_1").addClass('Tab_2_myjb');
                    $("#li_2").removeClass('Tab_2_myjb');
                   $("#li_2").addClass('Tab_1_myjb');
                

                });
            });
	
        </script>
        <script language="javascript"><!--
   
                                                         
 
<?php
$p = array('/&amp;/', '/&quot;/');
$r = array('&', '"');
?>
            function orders_edit_submit(id,os_name){
               
                    var form_id = 'orders_edit_'+id;
                     var notic_id = 'notic_status_'+id;
                    var form_obj = document.getElementById(form_id);
                    //var from = from;

                    var error = false;
                    var error_sms = '';
                   // if(form_obj.elements['dransfer_total'].value==''||form_obj.elements['dransfer_total'].value<'1'){
                        //error = true;
                      //  error_sms += "* " + '<?php echo db_to_html('美元转账金额不能为空！'); ?>'+"\n\n";
                   // }
                  var status_type_value = form_obj.elements['status_type'].value;
				  if(status_type_value=="<?php echo db_to_html('Payment Received')?>" || status_type_value=="<?php echo db_to_html('Partial Payment Received')?>" || status_type_value=='Need Check Bank Account'){
                            if((form_obj.elements['select_collection_time'].value==''||form_obj.elements['select_collection_time'].value<'1'||form_obj.elements['select_collection_time'].value=='0000-00-00')&&status_type_value!='Need Check Bank Account'){
                                error = true;
                                error_sms += "* " + '<?php echo db_to_html('转账时间不能为空！'); ?>'+"\n\n";
                            }
                            if(form_obj.elements['actual_collection']!=null&&(form_obj.elements['actual_collection'].value==''||form_obj.elements['actual_collection'].value=='0')&&status_type_value!='Need Check Bank Account'){
                                error = true;
                                error_sms += "* " + '<?php echo db_to_html('实际收款不能为空！'); ?>'+"\n\n";
                            }
                             
                             if(form_obj.elements['bank_type'].value==''||form_obj.elements['bank_type'].value=='nobank'){
                                error = true;
                                error_sms += "* " + '<?php echo db_to_html('请选择银行'); ?>'+"\n\n";
                                form_obj.elements['status_type'].focus();
                              }
							  if(form_obj.elements['payer_name'].value==''&&status_type_value=='Need Check Bank Account'){
                                error = true;
                                error_sms += "* " + '<?php echo db_to_html('请输入 付款人'); ?>'+"\n\n";
                                form_obj.elements['payer_name'].focus();
                              }
                            }
                           if((form_obj.elements['dransfer_total_rmb'].value==''||form_obj.elements['dransfer_total_rmb'].value=='0'||form_obj.elements['dransfer_total_rmb'].value<'0')&&status_type_value!='Need Check Bank Account'){
                                error = true;
                                error_sms += "* " + '<?php echo db_to_html('应收人民币不能为空或小于0！'); ?>'+"\n\n";
                                form_obj.elements['dransfer_total_rmb'].focus();
                            }
                            if(form_obj.elements['status_type'].value==''||form_obj.elements['status_type'].value=='noselect'){
                                error = true;
                                error_sms += "* " + '<?php echo db_to_html('支付状态不能为空！'); ?>'+"\n\n";
                                form_obj.elements['status_type'].focus();
                            }
                            if(form_obj.elements['update_status'].value=='0'){
                                error = true;
                                error_sms += "* " + '<?php echo db_to_html('请确认支付状态'); ?>'+"\n\n";

                                $("#notic_status_"+id).show();
                            }
                      
                    if(error == true){
                        alert(error_sms);
                        return false;
                    }
           
                 if(confirm("<?php echo db_to_html("此操作将把订单状态更改为:"); ?>"+form_obj.elements['status_type'].value+"<?php echo db_to_html('，请确认！')?>")){
                    var orders_edit_submit_id_obj = document.getElementById("orders_edit_submit_id_"+id);
					if(orders_edit_submit_id_obj!=null){
						orders_edit_submit_id_obj.disabled = true;
						orders_edit_submit_id_obj.value = "<?php echo db_to_html('正在提交数据信息，请耐心等候...'); ?>";
						orders_edit_submit_id_obj.className = "AllbuttonHui";
					}
					var url = url_ssl("<?php echo preg_replace($p, $r, tep_href_link_noseo('ajax_domestic_orders.php', 'action=submit_orders_edit')) ?>");
                    //var form_id = from.id;
                    var success_msm = "";
                    var success_go_to = "";
                    ajax_post_submit(url,form_id,success_msm,success_go_to);
           
                }
            }
            function Submit_Update(){
                if(window.confirm("<?php echo db_to_html('确实要发送邮件？...') ?>\t")==true){
                    var upboton = document.getElementById("Update_Button");
					upboton.value = "<?php echo db_to_html('正在发送邮件，请耐心等候...'); ?>";
                    upboton.disabled=true;
					
					var form_id = 'notice_form';
                    // var form_id = document.getElementById(from_id);
                    //var from = from;
                    var error = false;
                    var error_sms = '';

                    var url = url_ssl("<?php echo preg_replace($p, $r, tep_href_link_noseo('ajax_domestic_orders.php', 'action=update_edit')) ?>");
                    //var form_id = from.id;
                    var success_msm = "";
                    var success_go_to = "";
                    ajax_post_submit(url,form_id,success_msm,success_go_to);
					
                }

            }

            function unset_permit(admin_id){
                if(confirm("<?php echo db_to_html("更改该管理员权限未不允许？"); ?>")){
                    var check_db = document.getElementById('check_db_'+admin_id).value;
                    var url =url_ssl("<?php echo preg_replace($p, $r, tep_href_link_noseo('ajax_domestic_orders.php', 'action=unset_permit')) ?>");
                    url += "&admin_id="+admin_id;
                    url += "&check_db="+check_db;
                    var success_msm = "";
                    var success_go_to = "";
                    ajax_get_submit(url,success_msm,success_go_to);
                    //alert(check_db);

                }

            }
            function set_permit(admin_id){
                if(confirm("<?php echo db_to_html("更改该管理员权限为许可？"); ?>")){
                    var check_db = document.getElementById('check_db_'+admin_id).value;
                    var url =url_ssl("<?php echo preg_replace($p, $r, tep_href_link_noseo('ajax_domestic_orders.php', 'action=set_permit')) ?>");
                    url += "&admin_id="+admin_id;
                    url += "&check_db="+check_db;
                    var success_msm = "";
                    var success_go_to = "";
                    ajax_get_submit(url,success_msm,success_go_to);

                }

            }
            function change_group(admin_id){
                if(confirm("<?php echo db_to_html("更改该管理员的权限等级？"); ?>")){
                    var set_group = document.getElementById('active_type_'+admin_id);
                    var set_group_num = set_group.value;
                    var url =url_ssl("<?php echo preg_replace($p, $r, tep_href_link_noseo('ajax_domestic_orders.php', 'action=set_group')) ?>");
                    url += "&admin_id="+admin_id;
                    url += "&group_num="+set_group_num;
                    var success_msm = "";
                    var success_go_to = "";
                    ajax_get_submit(url,success_msm,success_go_to);
          

                }
            }
            function changesubjectemail_new(od){

                var select_obj = document.getElementById('select_nemu');
     
                var url =url_ssl("<?php echo preg_replace($p, $r, tep_href_link_noseo('ajax_domestic_orders.php', 'action=change_email')) ?>");
                url += "&orders_status="+select_obj.value;
                url += "&orders_id="+od;
                var success_msm = "";
                var success_go_to = "";
                ajax_get_submit(url,success_msm,success_go_to);
                /* var text_1='';

               if(select_obj.value=='100071'){
                                text_1 = '<?php echo $text_1 ?>';
               }
               var sms=select_obj.value;

               comment_obj.innerHTML=text_1;*/


            }
			
			//当订单支付方式更改为其他支付方式时，将订单从转账支付系统中移出去 start
			function auto_remove_changed_payment_method_orders(){
                var url =url_ssl("<?php echo preg_replace($p, $r, tep_href_link_noseo('ajax_domestic_orders.php', 'orders_payment_method_changed_check=1')) ?>");
                var success_msm = "";
                var success_go_to = "";
                ajax_get_submit(url,success_msm,success_go_to);
			}
			auto_remove_changed_payment_method_orders();
			//当订单支付方式更改为其他支付方式时，将订单从转账支付系统中移出去 end
            //--></script>
        <script type="text/javascript">

            jQuery().ready(function(){
                // simple accordion

                // first simple accordion with special markup
                jQuery('#tab').accordion({
                    header: 'tr.Tabselect',
                    active: false,
                    alwaysOpen: false,
                    animated: false,
                    autoheight: false
                });
                             

            });
        </script>
        <script type="text/javascript">
            function hasClass(ele,cls) {
                return ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
            }
            function addClass(ele,cls) {
                if (!this.hasClass(ele,cls)) ele.className += " "+cls;
            }
            function removeClass(ele,cls) {
                if (hasClass(ele,cls)) {
                    var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
                    ele.className=ele.className.replace(reg,' ');
                }
            }
            function toggleClass(ele,cls) {
                if(hasClass(ele,cls)){
                    removeClass(ele,cls);
                }
                else
                    addClass(ele,cls);
            }
            function getStyle(elem,styleName){
                if(elem.style[styleName]){
                    return elem.style[styleName];
                }
                else if(elem.currentStyle){//IE
                    return elem.currentStyle[styleName];
                }
                else if(document.defaultView && document.defaultView.getComputedStyle){
                    styleName = styleName.replace(/([A-Z])/g,'-$1').toLowerCase();
                    var s = document.defaultView.getComputedStyle(elem,'');
                    return s&&s.getPropertyValue(styleName);
                }
                else{
                    return null;
                }
            }
            function showHideLyer(tit,con,cls){
                toggleClass(tit,cls);
                var t = document.getElementById(con);
                if(t){t.style.display = getStyle(t,'display') == 'none' ? '' : 'none';}
            }
            function ddd(obj,obj2, sType) {
var oDiv = document.getElementById(obj);
var oDiv2 = document.getElementById(obj2);
if (sType == 'show') { oDiv.style.display = 'block';oDiv2.className = 'changeXtSelect';}
if (sType == 'hide') { oDiv.style.display = 'none';oDiv2.className = 'changeXt';}
} 

        </script>

<script type="text/javascript">
//查看详情按钮
function open_detail_table_content(OrdersId){
	var id=$("#rows_bk_id").val();
	var style_bk =$("#style_bk_"+id).val();
	$("#show_orders_details_"+id).attr({'style':'background:'+style_bk});
	//alert($("#orders_detail_tr_"+OrdersId).attr('style'));
	$("td a:contains(查看详情)").removeClass('XiangxiSelect');
	if($("#orders_detail_tr_"+OrdersId).attr('style').indexOf("none")>-1){
		$("#show_orders_details_"+OrdersId).attr({'style':'background:url(domestic/image/tabhover_bg.jpg) repeat-x;'});
		$("#orders_detail_"+OrdersId).addClass('XiangxiSelect');
	}else{
		$("#orders_detail_"+OrdersId).removeClass('XiangxiSelect');
	}

	
	var url =url_ssl("<?php echo preg_replace($p, $r, tep_href_link_noseo('ajax_domestic_orders.php', 'action=reload_orders_details')) ?>");
	url += "&orders_id="+OrdersId;
	var success_msm = "";
	var success_go_to = "";
	ajax_get_submit(url,success_msm,success_go_to);
}

//更新支付状态的按钮动作
function orders_status_submit_id_up_action(OrdersId){
	$("#tom_status_"+OrdersId).val("1");
	var status_text_obj = document.getElementById("status_text_"+OrdersId);
	if(status_text_obj!=null){
		status_text_obj.innerHTML = "<font color='green'>已更新</font>";
		$("#status_text_"+OrdersId).fadeIn(100);
	}else{
		$("#orders_status_submit_id_"+OrdersId).after("<span id='status_text_"+OrdersId+"'><font color='green'>已更新</font></span>");
	}
	
	$("#notic_status_"+OrdersId).hide();
	window.setTimeout('$("#status_text_'+OrdersId+'").fadeOut(1000);',3000);
}	

// 弹出层
function GetIdObj(id){
  return document.getElementById(id);
}
function bodySize(){
  var a=new Array();
  a.st = document.body.scrollTop?document.body.scrollTop:document.documentElement.scrollTop;
  a.sl = document.body.scrollLeft?document.body.scrollLeft:document.documentElement.scrollLeft;
  a.sw = document.documentElement.clientWidth;
  a.sh = document.documentElement.clientHeight;
  return a;
}
function centerElement(obj){
  var s = bodySize();
  var w = GetIdObj(obj).offsetWidth;
  var h = GetIdObj(obj).offsetHeight;
  GetIdObj(obj).style.left = parseInt((s.sw - w)/2) + s.sl + "px";
  
  GetIdObj(obj).style.top = parseInt((s.sh - h)/2) + s.st + "px";
}


//显示弹出窗 2010-09-14
function popupBg(){
  var _scrollWidth = document.body.scrollWidth;
  var _scrollHeight = document.body.scrollHeight;
  GetIdObj("popupBg").style.width = _scrollWidth + "px";  //设置弹出窗的背景宽度和屏幕的大小一致
  GetIdObj("popupBg").style.height = _scrollHeight + "px";
  GetIdObj("popupBgIframe").style.width = _scrollWidth + "px";  //设置弹出窗的背景不能遮盖select的bug,加了个iframe的宽度和高度和屏幕的大小一致
  GetIdObj("popupBgIframe").style.height = _scrollHeight + "px";
 
}
function showPopup(popupId,popupCon,ScrollOff){
//popupId是弹出层的id,popupCon是弹出层内容部分，popupCon的宽度必须设定，高度可不设定
  GetIdObj(popupId).style.display="block";  //显示弹出窗
  GetIdObj(popupId).style.width = (GetIdObj(popupCon).offsetWidth + 12) + "px";   //设置内容的宽度
  GetIdObj(popupId).style.height = (GetIdObj(popupCon).offsetHeight + 12) + "px";  //设置内容的高度
  centerElement(popupId);
  GetIdObj("popupBg").style.display="block";  //设置弹出窗的背景
  popupBg();
  
  window.onresize = function() {centerElement(popupId); popupBg();}//屏幕改变的时候重新设定悬浮框
  if(ScrollOff!="" && ScrollOff!=null){}else{
  	window.onscroll = function() {centerElement(popupId); popupBg();}//上下滚动时悬浮框位置重新设定
  }
  //window.setTimeout("showPopup('popupTip','popupConCompare')",1000);

}

function closePopup(popupId){
  GetIdObj(popupId).style.display='none';
  GetIdObj("popupBg").style.display='none';
}

</script>


    </head>

    <body>
       
        <?php
        include(domestic . '/' . DIR_WS_CLASSES . 'banking_statistics.php');
        $login_admin_id = $_SESSION['login_id'];
        $login_admin_group = $_SESSION['login_groups_id'];
        $admin_type = admin_check($login_admin_id);
        ?>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr id="TopShow_content">
    <td height="55px">
      <div class="centerTopFrame">
        <div class="head">
          <p class="logo"><a href="{:$smarty.const.HTTP_SERVER:}" target="_blank"><img src="images/orderlogo.jpg" class="logoimg" /></a><span>转帐支付管理系统</span></p>
          <div class="changeXt"  id="changeXt" onMouseOver="document.getElementById('child').style.display='block';document.getElementById('changeXtTop').className='changeXtTopSelect'" onMouseOut="document.getElementById('child').style.display='none';document.getElementById('changeXtTop').className='changeXtTop'">
            <div id="changeXtTop" class="changeXtTop">切换系统</div>
            <div class="TopShowBg" id="child" style="display:none" >
              <div class="changeOther">
                <ul>
                  <li><a href="index.php">走四方网后台</a></li>
                  <li><a href="zhh_system_index.php">职员培训系统</a></li>
                  <li><a href="orders.php">我的订单</a></li>
                </ul>
              </div>
            </div>
          </div>
       </div>
       <div class="Welcome">
	   <?= get_login_publicity_name() ?> 欢迎您登录
<?php if ((int)$login_admin_group=='1') { ?>
                    <a href="ajax_domestic_orders.php#main" class="nyroModal" style=" color:#93B5E0; text-decoration:none; padding-left:30px; padding-right:30px;">系统设置</a>
<?php } ?>
	   </div>
</div>
</td></tr>
  <tr><td height="7" valign="top"><div class="TopShow" id="TopShowtit" onClick="showHideLyer(this,'TopShow_content','TopShowselect')"></div></td></tr></table>

<!--弹出层的iframe-->
<div id="popupBg" class="popupBg">
<iframe scrolling="no" height="100%" width="100%" marginwidth="0" marginheight="0" frameborder="0" style="filter:alpha(opacity=0); -moz-opacity:0; opacity:0;" id="popupBgIframe"></iframe>
</div>
	<!--<a href="javascript:;" onclick="showPopup(&quot;popup&quot;,&quot;popupConCompare&quot;,1);">Open</a>-->
	<div class="popup" id="popup">
	  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
		<tr>
		  <td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td  class="side"></td>
			<td class="con">
			  <div class="popupCon" id="popupConCompare" style="width:960px; ">
				<div class="popupConTop">
				  <h4>发送邮件</h4><span><a id="closeBut" href="javascript:closePopup(&quot;popup&quot;)"><img src="images/popup/icon_x.gif" /></a></span>
				</div>
				<?php echo tep_draw_form('status', 'ajax_domestic_orders.php', tep_get_all_get_params(array('action')) . 'action=update_order', 'get', ' id="notice_form" ');?>
				<div id="pop_obj"></div>
				<?php echo '</form>';?>
			  </div>
		  </td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
	  </table>
	</div>
      