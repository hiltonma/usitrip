<script type="text/javascript" src="includes/javascript/group_scroll.js"></script>
<script type="text/javascript" src="includes/javascript/pop.js"></script>

<?php ob_start();?>
<?php
$numTclass = 'num numOn';
$timeTclass = 'time';
if($_GET['gb_type']=="2"){
	$numTclass = 'num';
	$timeTclass = 'time timeOn';
}
?>
<div class="group">
            <div class="groupTab">
                <span id="GroupTabNum" class="<?= $numTclass?>">限量团</span>
                <span id="GroupTabTime" class="<?= $timeTclass?>">限时团</span>
            </div>
			<script type="text/javascript">
                jQuery("#GroupTabNum").click(function(){
                        jQuery(this).addClass("numOn");
                        jQuery("#GroupTabTime").removeClass("timeOn");
                        jQuery("#GroupNum").show();
                        jQuery("#GroupTime").hide();
                });
                jQuery("#GroupTabTime").click(function(){
                        jQuery(this).addClass("timeOn");
                        jQuery("#GroupTabNum").removeClass("numOn");
                        jQuery("#GroupTime").show();
                        jQuery("#GroupNum").hide();
                });
            </script>
			
			<div class="groupLeft">


<?php // 倒数团{?>			
			<div id="GroupNum"  class="groupNum">
<?php
$datas = $datas0;
$datas_count = sizeof($datas0);
$loop = 0;
for($i=0; $i<$datas_count; $i++){
	$loop++;
	if($datas[$i]['specials_type']!=1){ break; } /* 如果不是倒数团则离开到限量团 */
	if(isset($datas[$i]['balanceNum']) && $datas[$i]['balanceNum']==0){	//如果名额已经没了就不显示该团了。
		continue;
	}
	
	if($datas[$i]['specials_type']==1){	//2为限时团、1为倒数团
		$con_class = "con conNum";
		$start_days_class = "";
		if($loop==1) $con_class = "con conNum conIndex";
	}elseif($datas[$i]['specials_type']==2){
		$con_class = "con";
		$start_days_class = "";
		if($loop==1) $con_class = "con conIndex";
	}
	
	$pic_ids = 'Scroll_'.$datas[$i]['products_id'];	//图片区ID
	$products_info_links = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$datas[$i]['products_id']);
	
?>                
				<div id="ProductsObj_<?=$datas[$i]['products_id']?>" class="<?= $con_class?>">
                    <div id="ProductsTitle_<?=$datas[$i]['products_id']?>" class="top">
                        <h2><a href="<?= $products_info_links?>"><?= $datas[$i]['products_name'];?></a></h2>
                        <h3><?= $datas[$i]['products_name1'];?></h3>
                    </div>
                    
                    <div class="info">
                        <h4>行程介绍：</h4>
                        <p><?= $datas[$i]['products_small_description'];?></p>
                    </div>
                    
					<div class="main">
                    <div class="mainLeft">
						<div class="priceTag">
							<div class="priceTagCon">
								<?= $datas[$i]['priceTag'];?>                            
								<a href="<?= $products_info_links?>" class="buyBtn">马上团</a>
							</div>
                        </div>
                        
                        <div class="priceDetail">
                            <div class="priceTop">
                                <div class="col1">市场价</div><div class="col2">折扣</div><div class="col3">节省</div>
                            </div>
                            <div class="priceCon">
                                <div id="oldPrice_<?= $datas[$i]['products_id']?>" class="col1"><?= $datas[$i]['oldPrice']?></div><div id="Discount_<?= $datas[$i]['products_id']?>" class="col2"><?= $datas[$i]['Discount']?></div><div id="Save_<?= $datas[$i]['products_id']?>" class="col3"><?= $datas[$i]['Save']?></div>
                            </div>
                        </div>
                        
						<div class="countdown startTime">
                            <h3>出团时间:</h3>
                            <p><?= str_replace(array(' ',"\n",'<br>','<br />',"\t"),'',$datas[$i]['display_start_days'])?></p>
                        </div>
						
                        <div class="countdown">
                            <h3>距离本次团购结束还有:</h3>
                            <p id="CountDown<?= $datas[$i]['products_id']?>"></p>
							<?php //下面的JS代码不可以移动到其它地方，否则计时偏差太大?>
							<script type="text/javascript">
							GruopBuyCountdown(<?= $datas[$i]['products_id']?>, <?= $datas[$i]['CountdownEndTime']?>,'CountDown<?= $datas[$i]['products_id']?>','ProductsObj_<?=$datas[$i]['products_id']?>');
							</script>
                        </div>
                        
                        <div class="countdown orderNum">
                            <h3>数量有限，立刻抢购！</h3>
							<p><?= $datas[$i]['orderNumInfo']?></p>
                        </div>
                        
                        <div class="invite">
                            <p>
							<?php
							if(tep_not_null($datas[$i]['invite_info'])){
								echo $datas[$i]['invite_info'];
							}else{?>
								该团定于<?= trim($datas[$i]['display_start_days'])?>出团，完美行程，超低优惠价，数量有限，售完即止！走四方网强烈推荐！
							<?php
							}
							?>
							下单前请详细阅读<a href="<?= tep_href_link('group_buys.php','do=note');?>" target="_blank">团购须知</a></p>
                            <a href="javascript:;" onclick="showPopEmail(<?= $datas[$i]['products_id']?>)" class="inviteBtn">邀请朋友也来参团</a>
                            <p class="inviteTip">成功邀请朋友前来参团，您将获赠1000积分奖励！</p>
                        </div>
                        
                        
                    </div><div class="mainRight">
                        <div class="slider">
                            <div id="<?= $pic_ids?>" class="scroll">
                                <div class="preBtn"></div>
                                <ul>
								<?php for($j=0; $j<sizeof($datas[$i]['products_pics_src']); $j++){ //产品图片?>
                                    <li><a href="<?= $products_info_links?>"><img src="<?= $datas[$i]['products_pics_src'][$j]?>" /></a></li>
								<?php }?>	
                                </ul>
                                <div class="nextBtn"></div>
                            </div>
                        </div>
                        
                        <div class="basicInfo">
                            <ul>
                                <li><label>产品编号：</label><?= $datas[$i]['products_model']?></li>
                                <li><label>出发地点：</label><?= trim($datas[$i]['display_str_departure_city'])?></li>
                                <li><label>持续时间：</label><?= trim($datas[$i]['display_products_durations'])?></li>
                                <li><label>结束地点：</label><?= trim($datas[$i]['display_str_end_city'])?></li>
                                <li><label>出团时间：</label><?= str_replace(array(' ',"\n",'<br>','<br />',"\t"),'',$datas[$i]['display_start_days'])?></li>
                                <li><label>积分信息：</label><?= strip_tags($datas[$i]['products_points_info'])?></li>
                            </ul>
                        </div>
                        
                    <?php if((int)sizeof($datas[$i]['reviews'])){ //第一条评论?>    
						<div class="des"><?= $datas[$i]['reviews']['reviews_text']?><span>”</span></div>
                        <div class="comment">
                            <span><label>订购 </label><?= $datas[$i]['reviews']['booking_ratings'][0];?></span>
                            <span><label>出行 </label><?= $datas[$i]['reviews']['travel_ratings'][0];?></span>
                            <div class="signature"><?= $datas[$i]['reviews']['modified_date']?><a href="<?= tep_href_link('individual_space.php','customers_id='.$datas[$i]['reviews']['customers_id']);?>"><?= $datas[$i]['reviews']['customers_name']?></a></div>
                        </div>
					<?php }?>
						
                    </div>
                    </div>
<script type="text/javascript">

    jQuery("#<?= $pic_ids?>").Scroll({
        scroll           : "#<?= $pic_ids?>",
        nextBtn          : "#<?= $pic_ids?> .nextBtn",
        preBtn           : "#<?= $pic_ids?> .preBtn",
        scrollCon        : "#<?= $pic_ids?>>ul",
        scrollConLi      : "#<?= $pic_ids?>>ul>li"
    });


</script>
     
                </div>
			
				<?php	//默认的邮件内容{?>
				<div style="display:none">
					<div id="emailTitle_<?= $datas[$i]['products_id']?>"><?= $datas[$i]['emailTitle']?></div>
					<div id="emailContent_<?= $datas[$i]['products_id']?>"><?= $datas[$i]['emailContent']?></div>
				</div>
				<?php //默认的邮件内容}?>
<?php
}

//无产品倒数团{
if($datas_count<1){
?>

	<div class="groupNull"><img src="image/<?= $noGroups['group_null_image'];?>" /></div>
	<div class="groupNullHistory">
		<h2>往期成功团购</h2>
		<ul>
			<?php
			foreach((array)$noGroups['expired_products'] as $expireds){
			?>
			<li><a href="<?= $expireds['link']?>" target="<?= $expireds['link_target']?>"><?= $expireds['name']?></a></li>
			<?php
			}
			?>
		</ul>
	</div>

<?php
}
//无产品倒数团}
?>
			
			<?php if($group_split0->number_of_pages > 1) {?>
				<div class="pageWrap">
					<div class="page">
					<?php echo TEXT_RESULT_PAGE . ' ' . html_to_db($group_split0->display_links_2011(5, 'gb_type=1&'.tep_get_all_get_params(array('page', 'info', 'gb_type'))));?>
					</div>
				</div>
			<?php }?>
                
            </div>
<?php // 倒数团}?>

<?php // 限时团{?>
			<div id="GroupTime" class="groupTime" >
<?php
$datas = $datas1;
$datas_count = sizeof($datas1);
$loop = 0;
for($ii=0; $ii<$datas_count; $ii++){
	$loop++;
	if($datas[$ii]['specials_type']!=2){ break; }
	if(isset($datas[$ii]['balanceNum']) && $datas[$ii]['balanceNum']==0){	//如果名额已经没了就不显示该团了。
		continue;
	}
	
	if($datas[$ii]['specials_type']==1){	//2为限时团、1为倒数团
		$con_class = "con conNum";
		$start_days_class = "";
		if($loop==1) $con_class = "con conNum conIndex";
	}elseif($datas[$ii]['specials_type']==2){
		$con_class = "con";
		$start_days_class = "";
		if($loop==1) $con_class = "con conIndex";
	}
	
	$pic_ids = 'Scroll_'.$datas[$ii]['products_id'];	//图片区ID
	$products_info_links = tep_href_link(FILENAME_PRODUCT_INFO, 'products_id='.$datas[$ii]['products_id']);
	
?>                
				<div id="ProductsObj_<?=$datas[$ii]['products_id']?>" class="<?= $con_class?>">
                    <div id="ProductsTitle_<?=$datas[$ii]['products_id']?>" class="top">
                        <h2><a href="<?= $products_info_links?>"><?= $datas[$ii]['products_name'];?></a></h2>
                        <h3><?= $datas[$ii]['products_name1'];?></h3>
                    </div>
                    
                    <div class="info">
                        <h4>行程介绍：</h4>
                        <p><?= $datas[$ii]['products_small_description'];?></p>
                    </div>
                    
					<div class="main">
                    <div class="mainLeft">
						<div class="priceTag">
							<div class="priceTagCon">
								<?= $datas[$ii]['priceTag'];?>                            
								<a href="<?= $products_info_links?>" class="buyBtn">马上团</a>
							</div>
                        </div>
                        
                        <div class="priceDetail">
                            <div class="priceTop">
                                <div class="col1">市场价</div><div class="col2">折扣</div><div class="col3">节省</div>
                            </div>
                            <div class="priceCon">
                                <div id="oldPrice_<?= $datas[$ii]['products_id']?>" class="col1"><?= $datas[$ii]['oldPrice']?></div><div id="Discount_<?= $datas[$ii]['products_id']?>" class="col2"><?= $datas[$ii]['Discount']?></div><div id="Save_<?= $datas[$ii]['products_id']?>" class="col3"><?= $datas[$ii]['Save']?></div>
                            </div>
                        </div>
                        
                        <div class="countdown">
                            <h3>距离本次团购结束还有:</h3>
                            <p id="CountDown<?= $datas[$ii]['products_id']?>"></p>
							<?php //下面的JS代码不可以移动到其它地方，否则计时偏差太大?>
							<script type="text/javascript">
							GruopBuyCountdown(<?= $datas[$ii]['products_id']?>, <?= $datas[$ii]['CountdownEndTime']?>,'CountDown<?= $datas[$ii]['products_id']?>','ProductsObj_<?=$datas[$ii]['products_id']?>');
							</script>
							
                        </div>
                        
                        <div class="countdown orderNum">
                            <h3>优惠多多，立刻抢购！</h3>
							<p><?= $datas[$ii]['orderNumInfo']?></p>
                        </div>
                        
                        <div class="invite">
                            <p>
							<?php
							if(tep_not_null($datas[$ii]['invite_info'])){
								echo $datas[$ii]['invite_info'];
							}else{?>
								该团定于<?= trim($datas[$ii]['display_start_days'])?>出团，完美行程，超低优惠价，超长有效期，特价购买，闲时出游！走四方网强烈推荐！
							<?php
							}
							?>
							下单前请详细阅读<a href="<?= tep_href_link('group_buys.php','do=note');?>" target="_blank">团购须知</a></p>
                            <a href="javascript:;" onclick="showPopEmail(<?= $datas[$ii]['products_id']?>)" class="inviteBtn">邀请朋友也来参团</a>
                            <p class="inviteTip">成功邀请朋友前来参团，您将获赠1000积分奖励！</p>
                        </div>
                        
                        
                    </div><div class="mainRight">
                        <div class="slider">
                            <div id="<?= $pic_ids?>" class="scroll">
                                <div class="preBtn"></div>
                                <ul>
								<?php for($j=0; $j<sizeof($datas[$ii]['products_pics_src']); $j++){ //产品图片?>
                                    <li><a href="<?= $products_info_links?>"><img src="<?= $datas[$ii]['products_pics_src'][$j]?>" /></a></li>
								<?php }?>	
                                </ul>
                                <div class="nextBtn"></div>
                            </div>
                        </div>
                        
                        <div class="basicInfo">
                            <ul>
                                <li><label>产品编号：</label><?= $datas[$ii]['products_model']?></li>
                                <li><label>出发地点：</label><?= trim($datas[$ii]['display_str_departure_city'])?></li>
                                <li><label>持续时间：</label><?= trim($datas[$ii]['display_products_durations'])?></li>
                                <li><label>结束地点：</label><?= trim($datas[$ii]['display_str_end_city'])?></li>
                                <li><label>出团时间：</label>截止到<?= $datas[$ii]['last_departure_date']?></li>
                                <li><label>积分信息：</label><?= strip_tags($datas[$ii]['products_points_info'])?></li>
                            </ul>
                        </div>
                        
                    <?php if((int)sizeof($datas[$ii]['reviews'])){ //第一条评论?>    
						<div class="des"><?= $datas[$ii]['reviews']['reviews_text']?><span>”</span></div>
                        <div class="comment">
                            <span><label>订购 </label><?= $datas[$ii]['reviews']['booking_ratings'][0];?></span>
                            <span><label>出行 </label><?= $datas[$ii]['reviews']['travel_ratings'][0];?></span>
                            <div class="signature"><?= $datas[$ii]['reviews']['modified_date']?><a href="<?= tep_href_link('individual_space.php','customers_id='.$datas[$ii]['reviews']['customers_id']);?>"><?= $datas[$ii]['reviews']['customers_name']?></a></div>
                        </div>
					<?php }?>
						
                    </div>
                    </div>
<script type="text/javascript">

    jQuery("#<?= $pic_ids?>").Scroll({
        scroll           : "#<?= $pic_ids?>",
        nextBtn          : "#<?= $pic_ids?> .nextBtn",
        preBtn           : "#<?= $pic_ids?> .preBtn",
        scrollCon        : "#<?= $pic_ids?>>ul",
        scrollConLi      : "#<?= $pic_ids?>>ul>li"
    });


</script>
     
                </div>
				<?php	//默认的邮件内容{?>
				<div style="display:none">
					<div id="emailTitle_<?= $datas[$ii]['products_id']?>"><?= $datas[$ii]['emailTitle']?></div>
					<div id="emailContent_<?= $datas[$ii]['products_id']?>"><?= $datas[$ii]['emailContent']?></div>
				</div>
				<?php //默认的邮件内容}?>
<?php
}

//无产品限时团{
if($datas_count<1){
?>

	<div class="groupNull"><img src="image/<?= $noGroups['group_null_image'];?>" /></div>
	<div class="groupNullHistory">
		<h2>往期成功团购</h2>
		<ul>
			<?php
			foreach((array)$noGroups['expired_products'] as $expireds){
			?>
			<li><a href="<?= $expireds['link']?>" target="<?= $expireds['link_target']?>"><?= $expireds['name']?></a></li>
			<?php
			}
			?>
		</ul>
	</div>

<?php
}
//无产品限时团}
?>
			<?php if($group_split1->number_of_pages > 1) {?>
				<div class="pageWrap">
					<div class="page">
					<?php echo TEXT_RESULT_PAGE . ' ' . html_to_db($group_split1->display_links_2011(5, 'gb_type=2&'.tep_get_all_get_params(array('page', 'info', 'gb_type'))));?>
					</div>
				</div>
			<?php }?>

			</div>
<?php // 限时团}?>
			
			</div><div class="groupRight">
                <div class="title titleSmall">
                    <b></b><span></span>
                    <h3>关于团购</h3>
                </div>
                
                <div class="con">
                    <h4 class="yellow">限量团</h4>
                    <p>背景为橙色的团即为“限量团”，已经为您安排好了出团时间，届时我们将准时出团，请您确认出团时间是否和您的行程安排相符。</p>
                    
                    <h4 class="blue">限时团</h4>
                    <p>背景为蓝色的团即为“限时团”，没有固定的出团时间。您可以选择在有效期内任意有效出团日出行。</p>
                    
                    <h4>团购须知</h4>
                    <p>走四方网精心挑选旅游行程：您可以选择定期出团的限量团，也可以选择限定时间内有效的限时团，低价不代表低质，走四方网一如既往的带给您最满意最快乐的旅游行程。</p>
                    
                    <h4>团购优势</h4>
                    <p>1.超低低价<br/>2.超高品质<br/>
                    </p>
                    
                </div>
                
          
            </div>
            
            
        </div>


<?php // 邀请朋友也来参团弹出层 start { ?>
<div class="pop" id="GroupBuyRecommendEmail">
<form method="post" enctype="multipart/form-data" id="formGroupBuyRecommendEmail" onSubmit="return false;">
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="popTable">
    <tr>
      <td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td>
        <td class="con">
          <div class="popCon" id="GroupBuyRecommendEmailCon" style="width:510px;" >

            <div class="popTitle" id="drag">
                <div class="popTitleCon"><b>邀请朋友也来参团</b></div>
                <div class="popClose" id="GroupBuyRecommendEmailClose"></div>
            </div>
            <ul id="emailCon" class="emailCon">
                <?php if(!(int)$customer_id){?>
				<li><label>您的账号:</label><?= tep_draw_input_field('FromAddress',$customer_email_address,'style="ime-mode: disabled;" class="required validate-email text" title="请输入您在走四方网的账号（电子邮箱），成功邀请朋友前来参团，您将获赠1000积分奖励！" ')?></li>
				<?php }?>
				<li><label>收件人邮箱:</label><input name="to_email_address" type="text" class="required validate-email text" onblur="if(this.value==''){this.value='多个邮箱请用“,”隔开。';this.style.color='#777';}" onfocus="if(this.value=='多个邮箱请用“,”隔开。'){this.value='';this.style.color='#333';}" value="多个邮箱请用“,”隔开。" style="ime-mode: disabled; color:#777;" /></li>
				
                <li><label>邮件标题:</label><?= tep_draw_input_field('mail_subject','','class="required text" title="请输入邮件标题" ')?></li>
                <li><label>邮件内容:</label><?= tep_draw_textarea_field('mail_text','','','','',' class="textarea" title="请输入邮件内容" ')?></li>
				<input name="prod_id" type="hidden" />
				<input name="ProdName" type="hidden" />
				
            </ul>
            <div id="emailBtnCenter" class="btnCenter">
                <a href="javascript:;" class="btn btnOrange"><button type="submit">发 送</button></a>
            </div>
			<div id="emailConSuccess" class="emailConSuccess" style="display:none">
			邮件发送成功！<b id="emailConSuccessTime"></b> 秒后关闭此窗口！
			</div>
	     </div>
 </td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
  </table>
</form>
</div>

<script type="text/javascript">
function showPopEmail(prod_id){
    var popEmails = new Pop('GroupBuyRecommendEmail','GroupBuyRecommendEmailCon','GroupBuyRecommendEmailClose',{dragId:"drag"});
	var formObj = document.getElementById('formGroupBuyRecommendEmail');
	formObj.elements['mail_subject'].value = jQuery("#emailTitle_"+prod_id).text() +' - 走四方网';
	formObj.elements['mail_text'].value = jQuery("#emailContent_"+prod_id).text() +"\n";
	formObj.elements['prod_id'].value = prod_id;
	formObj.elements['ProdName'].value = jQuery("#ProductsTitle_"+prod_id).find("h2").text() + jQuery("#ProductsTitle_"+prod_id).find("h3").text();
	
}

var ShareEmailFormValid = new Validation('formGroupBuyRecommendEmail', {immediate : true,useTitles:true, onFormValidate : formGroupBuyRecommendEmailCallback});

function formGroupBuyRecommendEmailCallback(result, form) {
	window.status = "valiation callback for form '" + form.id + "': result = " + result;
	if(result==true){
		//发送电子邮件给朋友
		var url = url_ssl("group_buys.php?action=SendGroupBuyRecommendEmailToFriend");
		ajax_post_submit(url,form.id);
	}
	return false;
}

function SendEmaiSuccessAction(){
	Num = 6;
	TimeObj = document.getElementById('emailConSuccessTime');
	if(TimeObj==null){
		alert("No id=emailConSuccessTime"); return false; 
	}else if(TimeObj!=null && TimeObj.innerHTML!=""){
		Num = TimeObj.innerHTML;
	}
	
	if(Num <= 1 ){
		jQuery("#emailCon").show();
		jQuery("#emailBtnCenter").show();
		jQuery("#emailConSuccess").hide();
		jQuery("#GroupBuyRecommendEmailClose").click();
		
		TimeObj.innerHTML = 6;
	}else{
		TimeObj.innerHTML = (Num-1);
		window.setTimeout("SendEmaiSuccessAction()",1000);
	}
}

<?php if($_GET['gb_type']==2){	//根据要求隐藏相关Tab?>
jQuery("#GroupNum").hide();
<?php }else{?>
jQuery("#GroupTime").hide();
<?php }?>

</script>
<?php // 邀请朋友也来参团弹出层 end } ?>

<?php //echo preg_replace('/[[:space:]]+/',' ',db_to_html(ob_get_clean()));?>
<?php echo db_to_html(ob_get_clean());?>