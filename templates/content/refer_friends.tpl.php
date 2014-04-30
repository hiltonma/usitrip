<?php 
require('includes/javascript/common.js.php'); 
require('includes/javascript/refer_friends.js.php'); 

if($sendmsg == "sendture" && $_GET['rewards4fun']!='true'){
//网站联盟邮件发送成功提示{

ob_start();
?>
<div style="border:1px solid #AED5FF;overflow:hidden;zoom:1;">
        <div style="text-align:center;padding-top:10px;">
            <h2 style="display:inline-block;*display:inline;*zoom:1;margin:0 auto;"><span  class="successTip">恭喜您，邮件推荐成功！</span></h2>
        </div>
        
        <div class="transed" style="text-align:center;">您为朋友 <?= html_to_db($sentIinfo['fnames']);?> 推荐了以下行程，只要他们下单支付后，您将得到<b><?= $sentIinfo['percent']?></b>的佣金。<a href="<?= tep_href_link('affiliate_payment.php');?>">我的佣金明细</a></div>
        
        <ul class="transedList" style="overflow:hidden;">
            <?php
			if((int)$_POST['products1']){
				$products_name = tep_db_output(tep_get_products_name((int)$_POST['products1']));
				$products_name1=strstr($products_name, '**');
				if($products_name1!='' && $products_name1!==false) $products_name=str_replace($products_name1,'',$products_name);
			?>
			<li>
                <div class="left">
                    <div><a href="<?= tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.(int)$_POST['products1']);?>" target="_blank"><?= $products_name?></a><p><?= $products_name1?></p></div>
                    <span><?= html_to_db($sentIinfo['msg'])?></span>
                    <b>推荐理由：</b>
                </div>
                <div class="right"><p><a href="<?= tep_href_link('refer_friends.php', 'products1=' . (int)$_POST['products1']);?>" class="blue">邮件推荐给更多朋友</a></p><p><a href="<?= tep_href_link('affiliate_banners.php', 'individual_banner_id=' . (int)$_POST['products1']);?>" class="blue">去投放广告</a></p></div>
            </li>
            <?php }?>
			<?php if((int)$_POST['cPath1']){?>
			<li>
                <div class="left">
                    <div><a href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath='.(int)$_POST['cPath1']);?>" target="_blank"><?= tep_get_category_name((int)$_POST['cPath1'])?></a></div>
                    <span><?= html_to_db($sentIinfo['msg'])?></span>
                    <b>推荐理由：</b>
                </div>
                <div class="right"><p><a href="<?= tep_href_link('refer_friends.php', 'cPath1=' . (int)$_POST['cPath1']);?>" class="blue">邮件推荐给更多朋友</a></p><p><a href="<?= tep_href_link('affiliate_banners.php', 'individual_banner_id=' . (int)$_POST['cPath1']);?>" class="blue">去投放广告</a></p></div>
            </li>
			<?php }?>
        </ul>

</div>
<?php
	echo db_to_html(ob_get_clean());
//网站联盟邮件发送成功提示}
}else{

if(isset($_GET['rewards4fun']) && $_GET['rewards4fun']=='true') { 
	echo tep_draw_form('theForm', tep_href_link(FILENAME_REFER_A_FRIEND, 'rewards4fun=true', 'SSL'), 'post', ' id="theForm" onsubmit="return false"') . tep_draw_hidden_field('action', 'process'); //'onsubmit="return register_refral_validator(this);"' 
	}else{
	echo tep_draw_form('theForm', tep_href_link(FILENAME_REFER_A_FRIEND, '', 'SSL'), 'post', ' id="theForm" onsubmit="return false"') . tep_draw_hidden_field('action', 'process');
	}
	
?>																											

        <div style="border:1px solid #FFC75F" class="mytoursTip" id="TopTip">
            <span id="hideId"><?= db_to_html('隐藏')?></span>
            <div id="mytoursTipCont">
            <?php if(isset($_GET['rewards4fun']) && $_GET['rewards4fun']=='true') { 
						if ((USE_POINTS_SYSTEM == 'true') && (tep_not_null(USE_REFERRAL_SYSTEM))) {
							echo sprintf(REFER_FRIEND_HELP_LINK, USE_REFERRAL_SYSTEM, '<a href="' . tep_href_link(FILENAME_MY_POINTS_HELP,'faq_item=12', 'NONSSL') . '" class="sp3" title="' . BOX_INFORMATION_MY_POINTS_HELP . '">' . BOX_INFORMATION_MY_POINTS_HELP . '</a>');	
						}
					}else{
						echo TEXT_PAGE_HEADING1."<br />".TEXT_PAGE_HEADING2."<br /><br />";
						echo TEXT_PAGE_HEADING3;
					} 
			?>
            </div>					
 
        </div>

<?php 
if($sendmsg == "sendture"){ ?>
<table border="0" cellspacing="1" cellpadding="0" width="100%" align="center">

  <tr>
		<td><table border="0" width="100%" cellspacing="0" cellpadding="2">
			  <tr class="messageStackSuccess">
				<td class="messageStackSuccess"><img src="image/icons/success.gif" border="0" alt="Success" title=" Success " width="10" height="10">&nbsp;
				<?php
				$strMsn = sprintf(TEXT_MESSAGE_SENT_SUCCESS,$effective_points);
				if(!(int)$effective_points){
					$strMsn = db_to_html("您的消息已经成功地发给您的朋友。");
				}
				echo $strMsn;
				?>
				</td>
			  </tr>
			</table>
	</td>
  </tr>
   <tr>
  <td height="15"></td>
  </tr>
</table>
<?php }?>


<?php ob_start();?>
<script type="text/javascript">
    jQuery("#hideId").toggle(function(){
        jQuery("#mytoursTipCont").css({"height":"5px","overflow":"hidden"}); 
        jQuery(this).html("展开");
    },function(){
        jQuery("#mytoursTipCont").css("height", "auto");
        jQuery(this).html("隐藏");
    });
</script>


<?= tep_draw_hidden_field("email_address", tep_get_customers_email($customer_id), ' id="email_address" ');?>
<?= tep_draw_hidden_field("fname", tep_customers_name($customer_id), ' id="fname" ');?>
<?= tep_draw_hidden_field("cPath1");?>
<?= tep_draw_hidden_field("products1");?>



        <div class="trans">
            <h2>选择要推荐给朋友的行程</h2>
            <div class="route">
                <h4>给朋友推荐一个行程：<a id="ChooseRoute1" href="javascript:showPopup('popupRoute','popupConRoute','fixedTop','off','ChooseRoute1');">选择行程</a></h4>
                <div id="routeTitleProducts" class="routeTitle"><!--被推荐的产品名称Box-->&nbsp;</div>
            </div>
            <div class="popup" id="popupRoute">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
<tr><td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td><td class="con">

  <div class="popupCon" id="popupConRoute" style="width:650px;">
    <div class="popupConTop" id="dragRoute">
      <h3><b>请选择你要推荐的行程</b></h3><span onclick="closePopup('popupRoute')"><img src="<?= DIR_WS_ICONS;?>icon_x.gif" alt="关闭" title="关闭" /></span>
    </div>
    
    <ul class="chooseRoute" id="ChooseRoute">
        <?php
		$li_page = 1;
		$li_class = "";
		for($i=0, $n=sizeof($referProducts); $i<$n; $i++ ){
			if((int)$i && $i%10==0){
				$li_page++;
				$li_class = 'displayNone';
			}
		?>
		<li page="<?= $li_page?>" class="<?= $li_class?>"><input type="radio" name="route" value="<?= $referProducts[$i]['products_id']?>" /><a target="_blank" href="<?= tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$referProducts[$i]['products_id']);?>"><?= tep_db_prepare_input($referProducts[$i]['products_name'])?></a></li>
        <?php }?>
    </ul>
    
	<?php if($li_page>1){?>
    <div class="page routePage">
        <a class="go pre" style="display:none" href="javascript:_goto(-1,'ChooseRoute','popupConRoute')">上一页</a>
        <a class="go next" href="javascript:_goto(1,'ChooseRoute','popupConRoute')">下一页</a>
    </div>
    <?php }?>
	
    <div class="popupBtn">
        <a class="btn btnOrange" href="javascript:;"><button onclick="_selProducts()" type="button">确 定</button></a>
    </div>
    
<script type="text/javascript">

//选择推荐的行程
jQuery("#ChooseRoute li").click(function(){
	jQuery("#ChooseRoute li").removeClass("over");
	jQuery(this).find("input[type='radio']").attr('checked',true);
	jQuery(this).addClass("over");
	jQuery('#theForm input[name="products1"]').val(jQuery(this).find("input[type='radio']").val());
	jQuery('#routeTitleProducts').html(jQuery(this).html());
	jQuery('#routeTitleProducts input').remove();
	prodcut_call();
});
//确定
function _selProducts(){
	var _pID = jQuery('#theForm input[name="products1"]').val();
	if(_pID=="" || parseInt(_pID)==0){
		alert("请选择一个行程！");
	}else{
		closePopup('popupRoute');
	}
}
</script>
    
    
  </div>
  
</td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
</table>
</div>



<div class="popup" id="popupSort">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
<tr><td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td><td class="con">

  <div class="popupCon" id="popupConSort" style="width:650px;">
    <div class="popupConTop" id="dragSort">
      <h3><b>请选择你要推荐的类别</b></h3><span onclick="closePopup('popupSort')"><img src="<?= DIR_WS_ICONS;?>icon_x.gif" alt="关闭" title="关闭" /></span>
    </div>
    
    <div class="chooseSort">
        <dl>
            <?php
			for($i=0, $n=sizeof($rfCats); $i < $n; $i++){
			?>
			<dt id="rfCats_<?=$rfCats[$i]['id']?>"><label><input name="route" type="radio" value="<?=$rfCats[$i]['id']?>" onclick="_showChild('rfCats_<?=$rfCats[$i]['id']?>')" link="<?=tep_href_link(FILENAME_DEFAULT, 'cPath='.$rfCats[$i]['id']);?>" _alt="<?= $rfCats[$i]['name']?>" /><?= $rfCats[$i]['name']?></label></dt>
            	<?php foreach((array)$rfCats[$i]['child'] as $key => $val){?>
				<dd parent="rfCats_<?=$rfCats[$i]['id']?>"><label><input name="route" type="radio" value="<?= $rfCats[$i]['child'][$key]['id']?>" link="<?=tep_href_link(FILENAME_DEFAULT, 'cPath='.$rfCats[$i]['child'][$key]['id']);?>" _alt="<?= $rfCats[$i]['child'][$key]['name']?>" /><?= $rfCats[$i]['child'][$key]['name']?></label></dd>
				<?php }?>
			<?php
			}
			?>
        </dl>
    </div>
    
    <div class="popupBtn">
        <a class="btn btnOrange" href="javascript:;"><button onclick="_selCat()" type="button">确 定</button></a>
    </div>
    
<script type="text/javascript">
jQuery('#popupConSort dd').hide();
function _showChild(id){
	jQuery('#popupConSort dd').hide();
	jQuery('dd[parent="'+ id +'"]').fadeIn(300);
}
//选择推荐的目录
jQuery('#popupSort input[type="radio"]').click(function(){
	jQuery('#theForm input[name="cPath1"]').val(jQuery(this).val());
	var _boxHtml = '<a href="'+ jQuery(this).attr('link') +'" target="_blank">' + jQuery(this).attr('_alt') + '</a>';
	jQuery('#routeTitleCat').html(_boxHtml);
	cat_call();
});
//确定
function _selCat(){
	var _cID = jQuery('#theForm input[name="cPath1"]').val();
	if(_cID=="" || parseInt(_cID)==0){
		alert("请选择一个景点！");
	}else{
		closePopup('popupSort');
	}
}
</script>
    
    
  </div>
  
</td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
</table>
</div>

<div id="popupBg" class="popupBg"></div>
<script type="text/javascript">
//设置弹出层顶部拖曳 
new divDrag([GetIdObj('dragRoute'),GetIdObj('popupRoute')]); 
new divDrag([GetIdObj('dragSort'),GetIdObj('popupSort')]); 
</script>
            
            <div class="route">
                <h4>或给朋友推荐景点：<a id="ChooseSort" href="javascript:showPopup('popupSort','popupConSort','fixedTop','off','ChooseSort');">选择景点</a></h4>
                <div id="routeTitleCat" class="routeTitle"><!--被推荐的目录名称Box-->&nbsp;</div>
                <h4>推荐理由：</h4>
                <div><?= tep_draw_textarea_field("msg_to_friends", '', '13', '10', '', 'class="required textarea" title="请填写推荐理由！"')?></div>
            </div>
        </div>
        
        <div class="trans">
            <h2>推荐给一下朋友：<span>推荐给<input id="referfrdEmailMax" onblur="setReferfrdEmail();" type="text" style="width:20px; border: 1px solid #D5D5D5; color: #111111; font-size: 12px; height: 16px; line-height: 16px; padding: 2px 3px;" value="3" maxlength="1" />个朋友</span></h2>
            <!--
			<div class="getEmail">
                <span><a href="">获取QQ好友Email地址</a></span>
                <span><a href="">获取QQ好友Email地址</a></span>
            </div>
            -->
			
            <ul id="referfrdEmail" class="emailList">
				<li>
                    <div class="col1">&nbsp;</div>
                    <div class="col2"><label>您朋友的姓名</label></div>
                    <div class="col3"><label>Email地址</label></div>
                </li>
                <li>
                    <div class="col1">1.</div>
                    <div class="col2"><input name="refer_frd_name_1" type="text" class="required text" title="请输入朋友的姓名"/></div>
                    <div class="col3"><input name="refer_frd_email_1" type="text" class="required validate-email text email" title="请输入朋友的电子邮箱地址"/></div>
                </li>
                
            </ul>
			
			<script type="text/javascript">
			function setReferfrdEmail(){
				var _num = parseInt(jQuery('#referfrdEmailMax').val());
				if(_num>0){
				}else{
					_num = 3;
					jQuery('#referfrdEmailMax').val(_num);
				}
				var li0 = '<li>';
                    li0 +=' <div class="col1">&nbsp;</div>';
                    li0 +='<div class="col2"><label>您朋友的姓名</label></div>';
                    li0 +='<div class="col3"><label>Email地址</label></div>';
                	li0 +='</li>';
				/*
				var li0 = jQuery('#referfrdEmail').html();
				*/
				var li1 = '';
				var _onb = 'this.value = simplized(this.value); ';
				<?php if(strtolower(CHARSET)=='big5'){ echo " _onb = ' this.value = this.value = traditionalized(this.value); '; "; }?>
				
				for(i=1; i<=_num; i++){
					var _rx = '<a href="javascript:_reMoveEmailBox('+i+')">[x]</a>';
					if(i==1){ _rx = ""; }
					li1+= '<li id="emailBox_'+ i +'">';
                    li1+= '<div class="col1">'+ i +'.</div>';
                    li1+= '<div class="col2"><input name="refer_frd_name_'+ i +'" type="text" onBlur="'+_onb+'" class="required text" title="请输入朋友的姓名"/></div>';
                    li1+= '<div class="col3"><input name="refer_frd_email_'+ i +'" type="text" class="required validate-email text email" title="请输入朋友的电子邮箱地址"/></div><div  class="cancel">'+ _rx +'</div>';
                	li1+= '</li>';
				}
				jQuery('#referfrdEmail').html((li0+li1));
				atuo_Validation();
			}
			
			setReferfrdEmail();
			
			function _reMoveEmailBox(n){
				jQuery('#emailBox_'+n).remove();
				jQuery('#referfrdEmailMax').val(jQuery("#referfrdEmail li[id^='emailBox_']").length);
			}
			
			</script>
            
            <div class="submit"><label class="btn btnOrange"><button type="submit">推荐给朋友</button></label></div>
 
        </div>
</form>

<script type="text/javascript">
function _goto(num,ulID,popupID){
	var oldPage = jQuery('#'+ulID+' li[class=""]').attr('page');
	jQuery('#'+ulID+' li[class=""]').attr('class','displayNone');
	var nowPage = parseInt(oldPage)+parseInt(num);
	var preNextPage = nowPage+parseInt(num);
	var nowPageObj = jQuery("#"+ulID+" li[page='"+nowPage+"']");
	var preNextPageObj = jQuery("#"+ulID+" li[page='"+preNextPage+"']");
	if(nowPageObj.length>0){
		jQuery(nowPageObj).attr('class','');
	}
	
	jQuery('#'+popupID+' a[class="go pre"]').show();
	jQuery('#'+popupID+' a[class="go next"]').show();
	if(preNextPageObj.length<1){
		if(num=="-1"){ jQuery('#'+popupID+' a[class="go pre"]').hide(); }
		if(num=="1"){ jQuery('#'+popupID+' a[class="go next"]').hide(); }
	}
	
}

var TF = document.theForm;
function formCallback(result, form) {
	if(result==true){
		if((parseInt(TF.products1.value)+parseInt(TF.cPath1.value))>0){
			jQuery('#theForm button[type="submit"]').attr('disabled',true);
			jQuery('#theForm button[type="submit"]').html("邮件发送中……");
			TF.submit();
		}else{
			alert("请选择行程或景点！");
			return false;
		}
	}
	window.status = "valiation callback for form '" + form.id + "': result = " + result;
}
</script>	
<?php echo db_to_html(ob_get_clean());?>

<script type="text/javascript">
var valid = "";						
var extraval = "";
function atuo_Validation(){
	valid = new Validation('theForm', {immediate : true,useTitles:true, onFormValidate : formCallback});						
	extraval = "";
}
atuo_Validation();

var txtval = "<?php echo EMAIL_TEXT_ONE_HI;?>\n\n<?php echo EMAIL_TEXT_ONE;?>";	
var signname = "\n\n";

//	TF.msg_to_friends.value = txtval + extraval;
TF.msg_to_friends.value = txtval + extraval + signname;

//	alert(txtval);
function cat_call(){
	var cName = jQuery('#routeTitleCat').text();
	if(TF.cPath1.value == "0"){
		TF.msg_to_friends.value = txtval + signname;	
	}else {
		TF.msg_to_friends.value = txtval + " <?php echo TEXT_AND_THIS;?> " + cName + " <?php echo TEXT_INTEREST_TO_ME;?> "+ signname;
	}
	TF.products1.value = '0';
	jQuery('#routeTitleProducts').html("");
}

function prodcut_call(){
	var pName = jQuery('#routeTitleProducts').text();
	if(TF.products1.value == "0") {
		TF.msg_to_friends.value = txtval + signname;
	}else{
	  TF.msg_to_friends.value = txtval + " <?php echo TEXT_AND_THIS;?> " +  pName + " <?php echo TEXT_INTEREST_TO_ME;?> "+ signname;
	}
	TF.cPath1.value = '0';
	jQuery('#routeTitleCat').html("");
}

</script>

<?php
}
?>