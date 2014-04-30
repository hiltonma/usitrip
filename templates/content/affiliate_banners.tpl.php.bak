<?php ob_start();?>
<script type="text/javascript" src="includes/javascript/hash.history.min.js"></script>
<div id="userTitle" class="userTitle">
<ul>
<li id="promocode_tag" onclick="setTab('promocode');">优惠码</li>
<li id="imagetext_tag" onclick="setTab('imagetext');">图文广告</li>
<li id="search_tag" onclick="setTab('search');">搜索框嵌入</li>
<li id="custom_tag" onclick="setTab('custom');">自定义链接</li>
</ul>
</div>

<form action="" method="post" enctype="multipart/form-data" name="formBanners" id="formBanners">
<div id="panelCon" class="mainbox">

<div class="promo_box">
<ol class="promo_step">
	<li>选择并设置合适的代码呈现方式点击"获取代码"获取代码。</li>
	<li>将获取的代码粘贴到您的网站，展示给您的用户。</li>
	<li class="nomargr">用户从您的网站上的推广链接来到走四方旅游网购物，从而您可以获取佣金。</li>
</ol>

<?php //优惠码{?>
		<div panel="yes" id="promocode_panel">
        	<div class="get_code_panel">
                <div class="promo_header">
                    <h2>Coupon Code(优惠码)</h2>
                    <h3>&mdash;&mdash;完美双赢!他拿折扣您获佣金!</h3>
                </div>
                <div class="promo_msg">
                    <i class="icons"></i>
                    通过Coupon Code，您在轻松赚取3%佣金的同时，您的朋友也将享受到2%的折扣优惠哦！完美双赢的推广方法！最便捷、最惊喜、最贴心的收益模式！
                </div>
                <div class="promo_user_code">
                    <p>您的Coupon Code(优惠码)：<input readonly="readonly" id="CouponCode" class="code" value="<?= $my_coupon_code;?>"><a href="javascript:void(0);" onclick="_copyClipboard('CouponCode')">[点击复制优惠码]</a></p>
                </div>
                <div style="color:#ff9600;">&nbsp;&nbsp;&nbsp;&nbsp;选择“优惠码”推广可能会出现佣金为零的情况。即当客户的订单带有“推广链”信息又使用了“优惠码”，系统将只对“推广链”信息提供者返还佣金，不对“优惠码”提供者返还佣金。为保证您的最大利益，请优先选择“推广链”进行推广。关于“推广链”和“优惠码”不能同时返佣的详细说明见：<a href="<?php echo tep_href_link('affiliate_faq.php')?>#list14"><?php  echo tep_href_link('affiliate_faq.php')?>#list14</a>
                </div>
                <div class="promo_cnt">
                    <p>每天我们都在为万千会员及新老客户带来最为贴心的实利！</p>
                    <p>您随时随地可通过电话、邮件、短信等任意方式，向您的家人及朋友推荐走四方旅游网，并将您的Coupon Code(优惠码)告诉他们。在您的家人及朋友购买我们的旅游产品时输入此Coupon Code(优惠码)，您的朋友即立刻获得2%优惠折扣！同时，通过此Coupon Code(优惠码)所产生3%的佣金则记录在您的帐户里。</p><br>
                    <p>金额越高，折扣越诱人！推广越多，佣金越丰厚！</p><br>
                    <p>还等什么？赶快推荐！为您的朋友带去实惠， 也让自己收获一份惊喜吧！</p>
                </div>
			</div>
            <div class="ui_notice_msg">
                <p><strong>您还需要注意以下几点Coupon Code(优惠码) 的规则：</strong></p>
                <p>1.订单金额小于<strong>700</strong>美金不能使用优惠码(Coupon Code)进行打折。</p>
                <p>2.您的Coupon Code(优惠码)不允许用在自己的订单里。</p>
                <p>3.使用了Coupon Code(优惠码)，将不再赠送积分。</p>
                <p>4.旧站会员已领用的Coupon Code(优惠码)，新站将仍然有效。</p>
            </div>
        </div> 
<?php
//优惠码}
//图文广告{
?>

<div panel="yes" id="imagetext_panel">



<?= tep_draw_hidden_field('rProductsId','','id="rProductsId"');?>
<?= tep_draw_hidden_field('rCatId','','id="rCatId"');?>

<div class="get_code_panel">
<h2>选择推广目标</h2>

<div class="adLinks">
    
	<?php /* 被取消了的行程选择器?>
	<h2>选择要推荐的行程<a id="ChooseRoute1" class="blue" href="javascript:showPopup('popupRoute','popupConRoute','fixedTop','off','ChooseRoute1');">选择行程</a></h2>
    <div class="createCode">
        <div id="routeTitleProducts" class="routeTitle"><!--被推荐的产品名称Box-->&nbsp;</div>
    </div>
	<?php */ ?>
	
	<div class="createCode">
	<ul class="af_textpic">
	<li>
	<label class="textpic_label">选择链接目标页：</label>
	<select name="tips" class="select_links_page" id="tips" onchange="tips_fun(this)">
		<option value="selIndex">首页</option>
		<option value="selTheme">主题活动</option>
		<option value="selCategories">景点</option>
		<option value="selProducts" <?php if((int)$rProductsId){?> selected="selected" <?php }?>>线路行程</option>
	</select>	
	</li>
	<li>
	<label class="textpic_label">选择链接形式：</label> 
	<label><input name="links_type" id="links_type_0" type="radio" value="imageTextAdPr" onclick="links_type_fun(this)" checked="checked" /> 图片+文字</label>
	<label><input name="links_type" type="radio" value="imageAdPr" onclick="links_type_fun(this)" /> 图片</label>
	<label><input name="links_type" type="radio" value="textAdPr" onclick="links_type_fun(this)" /> 文字链接</label>
	</li>
	<li id="image_size_li">
	<label class="textpic_label">选择区域大小：</label>
	<label><input name="image_size" type="radio" value="760-90" checked="checked" onclick="clear_view();" /> 760*90</label>
	<label><input name="image_size" type="radio" value="468-60" onclick="clear_view();" /> 468*60</label>
	<label><input name="image_size" type="radio" value="120-600" onclick="clear_view();" /> 120*600</label>
	<label><input name="image_size" type="radio" value="300-300" onclick="clear_view();" /> 300*300</label>
	<a href="<?= HTTP_SERVER.'/download/ad_diy.2012.zip';?>">DIY素材下载</a>
	</li>
	<li id="selProducts" parent="tips" tips="线路行程">
	<h2>填写旅游团号：
	<?= tep_draw_input_num_en_field('products_model','','id="pModel" class="get_pro"');?>
	<span class="line_help" id="getHelp">如何找到旅游团号?
		<div class="help_tooltip" id="helpPanel">
			<i class="icons"></i>
			<div class="help_panel_title">
				<p>通过搜索或直接进入产品详情页后，可在基本信息首行查到<strong>“旅游团号”</strong>，如下：</p>
			</div>
			<img src="/image/affiliate/help_tuan_nub.jpg" width="287" height="99" align="旅游团号" />
		</div>
	</span>
    <a href="<?= tep_href_link('advanced_search_result.php');?>" class="choose_linexc" target="_blank">去挑选线路&gt;&gt;</a>
	</h2>
	
	<script type="text/javascript">
	window.onload = function(){
		function getId(id){
			return document.getElementById(id);
		}
		var getHelp = getId("getHelp");
		var helpPanel = getId("helpPanel");
		getHelp.onmousemove = function(){
			helpPanel.style.display = "block";
		}
		getHelp.onmouseout = function(){
			helpPanel.style.display = "none";
		}
	}
	</script>

	<div class="submit">
	<a href="javascript:;" class="btn btnOrange"><button id="createCodeButtonP" type="button" onclick="createCode('Products')">生成代码</button></a>
	</div>
	</li>
	<li id="selCategories" parent="tips" tips="景点">
		<h2>创建景点的广告链接：<a id="ChooseSort" class="blue" href="javascript:showPopup('popupSort','popupConSort','fixedTop','off','ChooseSort');">选择景点</a></h2>
		<div id="routeTitleCat" class="routeTitle" onclick="showPopup('popupSort','popupConSort','fixedTop','off','ChooseSort');"><!--被推荐的目录名称Box-->&nbsp;</div>
		<div class="submit"><a href="javascript:;" class="btn btnOrange"><button id="createCodeButtonC" type="button" onclick="createCode('Cat')">生成代码</button></a>
		</div>
	</li>
	<li id="selIndex" parent="tips" tips="首页">
	<div class="submit"><a href="javascript:;" class="btn btnOrange"><button id="createCodeButtonF" type="button" onclick="createCode('Index')">生成首页代码</button></a>
	</div>
	</li>
	
	<li id="selTheme" parent="tips" tips="主题活动">
	<h2>请选择主题活动：
		<select name="theme_name" onchange="createCode('Theme')" >
			<option value="googleapple">硅谷科技之旅游苹果谷歌</option>
			<option value="familyfun">亲子游-一睹名校风采</option>
			<option value="shopping">去美国购物吧</option>
			<option value="2012yellow_stone">黄石公园开团有大礼</option>
			<option value="yhuts">独家入住！黄石公园小木屋</option>
			</select>
	</h2>	
		<div class="submit"><a href="javascript:;" class="btn btnOrange"><button id="createCodeButtonG" type="button" onclick="createCode('Theme')">生成代码</button></a></div>
	</li>
	
	</ul>
	</div>
	</div>


</div>
	
	<div class="get_code_view">
	<h2>获取广告代码</h2>
	<div class="get_code_panel">
	<div class="adLinks">
	<div id="textAdPr" parent="links_type">
    <h4>文字广告<span>将以下代码放到您网站或博客、论坛网页源码中。</span></h4>
	<textarea isCodeBox="yes" id="codeProductsText" class="code" ><?= $bLink2?></textarea>
    <div id="codeProductsHtml"></div>
	<div class="copyCode">
	<a href="javascript:;" class="get_code_btn" onclick="_copyClipboard('codeProductsText')">获取代码</a>
	<a href="javascript:;" class="btn btnGrey"><button type="button" onclick="_preview('codeProductsText')">预览效果</button></a>
	</div>
    </div>
	
	<div id="imageAdPr" parent="links_type">
	<h4>图片广告<span>将以下代码放到您网站或博客、论坛网页源码中。</span></h4>
    <textarea isCodeBox="yes" id="codeProductsImages" class="code" ><?= $bLink?></textarea>
	<div id="codeProductsImagesHtml"></div>
	<div class="copyCode">
	<a href="javascript:;" class="get_code_btn" onclick="_copyClipboard('codeProductsImages')">获取代码</a>
	<a href="javascript:;" class="btn btnGrey"><button type="button" onclick="_preview('codeProductsImages')">预览效果</button></a>
	</div>
	</div>
	
	<div id="imageTextAdPr" parent="links_type">
	<h4>图文广告<span>将以下代码放到您网站或博客、论坛网页源码中。</span></h4>
    <textarea isCodeBox="yes" id="codeProductsImagesText" class="code" ><?= $bLink?></textarea>
	<div id="codeProductsImagesTextHtml"></div>	
	<div class="copyCode"><a href="javascript:;" class="get_code_btn" onclick="_copyClipboard('codeProductsImagesText')">获取代码</a>
	<a href="javascript:;" class="btn btnGrey"><button type="button" onclick="_preview('codeProductsImagesText')">预览效果</button></a>
	</div>
	</div>
	
</div>
</div>
	</div>

<?php /* 行程弹出框，已经被取消?>
<div class="popup" id="popupRoute">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
<tr><td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td><td class="con">

  <div class="popupCon" id="popupConRoute" style="width:650px;">
    <div class="popupConTop" id="dragRoute">
      <h3><b>请选择你要推荐的行程</b></h3><span onclick="closePopup('popupRoute')"><img src="image/icons/icon_x.gif" alt="关闭" title="关闭" /></span>
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
	jQuery('#rProductsId').val(jQuery(this).find("input[type='radio']").val());
	jQuery('#routeTitleProducts').html(jQuery(this).html());
	jQuery('#routeTitleProducts input').remove();
});
//确定
function _selProducts(){
	var _pID = jQuery('#rProductsId').val();
	if(_pID=="" || parseInt(_pID)==0){
		alert("请选择一个行程！");
	}else{
		closePopup('popupRoute');
	}
}
//翻页
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

</script>
    
    
  </div>
  
</td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
</table>
</div>
<?php */?>

<div class="popup" id="popupSort">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
<tr><td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td><td class="con">

  <div class="popupCon" id="popupConSort" style="width:650px;">
    <div class="popupConTop" id="dragSort">
      <h3><b>请选择您要推荐的景点</b></h3><span onclick="closePopup('popupSort')"><img src="<?= DIR_WS_ICONS;?>icon_x.gif" alt="关闭" title="关闭" /></span>
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
	jQuery('#rCatId').val(jQuery(this).val());
	var _boxHtml = '<a href="'+ jQuery(this).attr('link') +'" target="_blank">' + jQuery(this).attr('_alt') + '</a>';
	jQuery('#routeTitleCat').html(_boxHtml);
});
//确定
function _selCat(){
	var _cID = jQuery('#rCatId').val();
	if(_cID=="" || parseInt(_cID)==0){
		alert("请选择一个景点！");
	}else{
		createCode('Cat');
		closePopup('popupSort');
	}
}
</script>
    
    
  </div>
  
</td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
</table>
</div>

<div id="popupBg" class="popupBg"></div>



</div>
<?php 
//图文广告}
//搜索框嵌入{
?>
<div panel="yes" id="search_panel">
<div class="get_code_panel">
	<table>
		<tbody>
			<tr>
				<td align="right" style="width:180px;">选择搜索框类型：</td>
				<td>
					<span><input type="radio" checked="checked" id="sideSearch" name="searchType" value="side" onclick="jQuery('#search_panel_all').hide(); clear_view();"><label for="sideSearch">边侧搜索框</label></span>
					<span><input type="radio" id="allSearch" name="searchType" value="all" onclick="jQuery('#search_panel_all').show(); clear_view();"><label for="allSearch">通栏搜索框</label></span>
				</td>
			</tr>
			<tr id="search_panel_all" style="display:none">
				<td  align="right">设置结构：</td>
				<td>
					<span><input type="checkbox" checked="checked" name="search_logo" id="search_logo" value="1" onclick="clear_view();"><label for="search_logo">走四方logo</label></span>
					<span><input type="checkbox" checked="checked" name="search_keywords" id="search_keywords" value="1" onclick="clear_view();"><label for="search_keywords">热门关键词</label></span>
				</td>
			</tr>
			<tr>
				<td  align="right">获取代码：</td>
				<td>
					<textarea isCodeBox="yes" id="codeSearchHtml" class="code_view" readonly="readonly"></textarea>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
				<a href="javascript:;" class="btn btnOrange"><button id="createCodeButtonE" type="button" onclick="createCode('Search')">生成代码</button></a>
				<a href="javascript:;" class="get_code_btn" onclick="_copyClipboard('codeSearchHtml')">获取代码</a>
				</td>
			</tr>
		</tbody>
	</table>
</div>
</div>
<?php 
//搜索框嵌入}
//自定义链接{
?>
<div panel="yes" id="custom_panel">
        <div class="links_form">
        	<p><label for="custom_links_text">自定义文字：</label><input type="text" name="custom_links_text" id="custom_links_text" value="走四方" /></p>
            <p><label for="custom_links_url">链接的网址：</label><input type="text" name="custom_links_url" id="custom_links_url" value="<?= HTTP_SERVER;?>/" /><span>请输入含有<?= HTTP_SERVER;?>的网址</span></p>
        <div class="submit"><a href="javascript:;" class="btn btnOrange"><button id="createCodeButtonD" type="button" onclick="createCode('Custom')">生成代码</button></a>
		</div>
		</div>
        <h3 class="get_code_head">获取自定义的HTML代码</h3>
        <div class="get_code_panel">
        	<p class="get_code_tips"><em>注意：</em>您的联盟ID在“生成代码”时被嵌入</p>
            <textarea isCodeBox="yes" id="codeCustomHtml" name="codeCustomHtml" class="get_code_textarea"></textarea>
            <p>
			<a href="javascript:;" onclick="_copyClipboard('codeCustomHtml')" class="get_code_btn">获取HTML代码</a>
			
			<!--<a href="javascript:;" class="btn btnGrey"><button type="button" onclick="_preview('codeCustomHtml')">预览效果</button></a>-->
			</p>
        </div>
        <h3 class="get_code_head">获取自定义的URL代码</h3>
        <div class="get_code_panel">
        	<p class="get_code_tips"><em>注意：</em>您的联盟ID在"生成代码"时被嵌入</p>
            <textarea isCodeBox="yes" id="codeCustomUrl" name="codeCustomUrl" class="get_code_textarea"></textarea>
            <p>
			<a href="javascript:;" onclick="_copyClipboard('codeCustomUrl')" class="get_code_btn">获取URL代码</a>
			<!--<a href="javascript:;" class="btn btnGrey"><button type="button" onclick="_preview('codeCustomUrl')">预览效果</button></a>-->
			</p>
        </div>

</div>
<?php 
//自定义链接}
?>

<div id="pview" class="get_code_view">
	<h2>预览效果</h2>
	<div class="get_code_view_panel" id="get_code_view_panel">
	</div>
</div>
</div>
</div>
</form>

<script type="text/javascript">
/* 清空预览框和代码框内容*/
function clear_view(){
	jQuery('#get_code_view_panel').html('');
	jQuery('textarea[isCodeBox="yes"]').val('');
}

/*切换Tags*/
function setTab(TabStr){
	clear_view();
	jQuery('#userTitle ul li').removeClass('cur');
	jQuery('#panelCon div[panel="yes"]').hide();
	jQuery('#pview').hide();
	
	if(TabStr!=''){
		var li = '#'+ TabStr +'_tag';
		var panel = '#'+ TabStr +'_panel';
		jQuery(li).addClass('cur');
		jQuery(panel).show();
		if(TabStr!='promocode' && TabStr!='custom'){
			jQuery('#pview').show();
		}
		if(document.all){			
			//IE
			var hashString = 'tag=' + TabStr +'_tag';
			if(hashString!=''){
				unFocus.History.addHistory(hashString);
			}		
		}else{
			//FF
			window.location.hash = '#tag=' + TabStr +'_tag';
		}
	}
}

<?php
//如果有产品ID则应显示图文广告标签
$TabStr = '';
if((int)$rProductsId || !isset($_GET['tag'])){
	$TabStr = 'imagetext';
}elseif(tep_not_null($_GET['tag'])){
	$TabStr = str_replace('_tag','',$_GET['tag']);
}
?>
var TabStr = '<?= $TabStr?>';
setTab(TabStr);
/*定时执行setTab*/
var _tmp_num = 0;
setInterval(function(){
	var oldTabStr = TabStr;
	hash = window.location.hash.substring(1);
	_tab = hash.split('=');
	if(_tab.constructor == Array){
		if(_tab[0]=='tag'){
			TabStr = _tab[1].replace('_tag','');
		}
	}
	if(TabStr != oldTabStr ||  _tmp_num == 0){
		_tmp_num = 1;
		setTab(TabStr);
	}
} , 700 );


//设置弹出层顶部拖曳 
//new divDrag([GetIdObj('dragRoute'),GetIdObj('popupRoute')]); 
new divDrag([GetIdObj('dragSort'),GetIdObj('popupSort')]); 

function _copyClipboard(id){
	var _text = jQuery('#'+id).val();
	if(document.all){
		var e=_text;
		window.clipboardData.setData('text', e);
		if(e.length>2){
			alert("恭喜，已经成功复制至粘贴板！");
		}else{ /*alert("空的");*/ }
	}else{
		alert("您的浏览器不支持剪贴板操作，请自行复制。");
	};
}

//预览效果
function _preview(htmlIpnutID){
	var ID = '#'+htmlIpnutID;
	jQuery('#get_code_view_panel').hide();
	jQuery('#get_code_view_panel').html(jQuery(ID).val());
	jQuery('#get_code_view_panel').fadeIn();
	//alert(jQuery(ID).val());
}

//生成代码
function createCode(action){
	clear_view();
	var error = false;
	var Form = document.getElementById("formBanners");
	switch(action){
		case 'Products':
			var _pID = jQuery('#rProductsId').val();
			var _pModel = jQuery('#pModel').val();
			if((_pID=="" || parseInt(_pID)==0) && _pModel == "" ){
				error = true;
				alert("请先填写一个旅游团号！");
			}else{
				disabledBtn = jQuery('#createCodeButtonP');
			}			
		break;
		case 'Cat':
			var _cID = jQuery('#rCatId').val();
			if(_cID=="" || parseInt(_cID)==0){
				error = true;
				alert("请先选择景点！");
			}else{
				disabledBtn = jQuery('#createCodeButtonC');
			}
		break;
		case 'Custom':
			var val = jQuery('#custom_links_url').val();
			var textVal = jQuery('#custom_links_text').val();
			if(val.length<1 || textVal.length<1){
				error = true;
				alert("请输入链接的网址和自定义文字！");
			}else if(val.search(/^<?= preg_quote(HTTP_SERVER,'/');?>/)==-1){
				error = true;
				alert("链接的网址！必须以<?= HTTP_SERVER;?>/开头");
			}else{
				disabledBtn = jQuery('#createCodeButtonD');
			}
		break;
		case 'Search':	
			disabledBtn = jQuery('#createCodeButtonE');
		break;
		case 'Index':
			disabledBtn = jQuery('#createCodeButtonF');
		break;
		case 'Theme':
			disabledBtn = jQuery('#createCodeButtonG');
		break;
	}
	
	if(error == true){
		return false;
	}
	
	jQuery(disabledBtn).attr('disabled',true);
	jQuery(disabledBtn).html('生成中……');
	
	var url = url_ssl("<?php echo preg_replace($p,$r,tep_href_link_noseo('affiliate_banners.php','ajax=true')) ?>");
	url+='&action='+action;
	ajax_post_submit(url,Form.id);
}

<?php
if((int)$rProductsId){	//当有产品传过来时自动执行生成代码
?>
createCode('Products');
<?php 
}
?>

/* 选择链接目标页时的功能*/
function tips_fun(obj){
	clear_view();
	jQuery('#imagetext_panel li[parent="tips"]').hide();
	jQuery('#'+obj.value).fadeIn();
	switch(obj.value){
		case 'selCategories':
			showPopup('popupSort','popupConSort','fixedTop','off','ChooseSort');
		break;
		case 'selProducts':		/* 行程不显示区域大小 */
			jQuery('#image_size_li').hide();
		break;		
		default: jQuery('#image_size_li').fadeIn();		
	}
};

tips_fun(document.getElementById('tips'));

/* 选择链接形式时的功能 */
function links_type_fun(obj){
	 clear_view();
	jQuery('#imagetext_panel div[parent="links_type"]').hide();
	jQuery('#image_size_li').hide();
	jQuery('#'+obj.value).fadeIn();
	if((obj.value == 'imageTextAdPr' || obj.value == 'imageAdPr') && jQuery('#tips').val()!='selProducts'){	/* 行程不显示区域大小 */
		jQuery('#image_size_li').fadeIn();
	}
};

links_type_fun(document.getElementById('links_type_0'));

</script>
<?php echo db_to_html(ob_get_clean());?>