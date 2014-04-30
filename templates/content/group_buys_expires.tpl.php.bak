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
				<span id="groupHistoryNumTop" class="groupHistoryNum" style="display: inline;">第<b><?= $datas1[0]['issue_num'];?></b>期团购</span>
            </div>
			<script type="text/javascript">
                jQuery("#GroupTabNum").click(function(){
                        jQuery(this).addClass("numOn");
                        jQuery("#GroupTabTime").removeClass("timeOn");
                        jQuery("#GroupNum").show();
						jQuery(".groupHistoryNum").hide();
                        jQuery("#GroupTime").hide();
                });
                jQuery("#GroupTabTime").click(function(){
                        jQuery(this).addClass("timeOn");
                        jQuery("#GroupTabNum").removeClass("numOn");
                        jQuery("#GroupTime").show();
						jQuery(".groupHistoryNum").show();
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
	$showIssueNum = false;	//是否显示期数
	if($datas[$i]['issue_num']!=$datas[($i-1)]['issue_num'] && $datas[($i-1)]['issue_num']>0){
		$showIssueNum = true;
	}
?>                
				<div id="ProductsObj_<?=$datas[$i]['products_id']?>" class="<?= $con_class?>">
                    <?php if($showIssueNum==true){?>
					<?php
					if(!tep_not_null($showGroupHistoryTag0)){
						$showGroupHistoryTag0 = '<a id="GroupHistoryTag"></a>';
						echo $showGroupHistoryTag0;
					}
					?>
					
					<div id="GroupHistory<?= $datas[$i]['issue_num']?>" class="groupHistoryNum" style="display: block;">第<b><?= $datas[$i]['issue_num']?></b>期团购</div>
					<?php }?>
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
								<a class="buyBtn buyBtnEnd">已结束</a>
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
                            <p>
                                <b>0</b>天<b>0</b>小时<b>0</b>分<b>0</b>秒
                            </p>
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
				
			<?php if($group_split0->number_of_pages > 1) {?>
				<div class="pageWrap">
					<div class="page">
					<?php echo TEXT_RESULT_PAGE . ' ' . html_to_db($group_split0->display_links_2011(5, 'gb_type=1&'.tep_get_all_get_params(array('page', 'info', 'gb_type'))));?>
					</div>
				</div>
			<?php }?>
							
<?php
}
?>
                
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
	$showIssueNum = false;	//是否显示期数
	if($datas[$ii]['issue_num']!=$datas[($ii-1)]['issue_num'] && $datas[($ii-1)]['issue_num']>0){
		$showIssueNum = true;
	}
?>                
				<div id="ProductsObj_<?=$datas[$ii]['products_id']?>" class="<?= $con_class?>">
                    <?php if($showIssueNum==true){?>
					<?php
					if(!tep_not_null($showGroupHistoryTag1)){
						$showGroupHistoryTag1 = '<a id="GroupHistoryTag"></a>';
						echo $showGroupHistoryTag1;
					}
					?>
					
					<div id="GroupHistory<?= $datas[$ii]['issue_num']?>" class="groupHistoryNum" style="display: block;">第<b><?= $datas[$ii]['issue_num']?></b>期团购</div>
					<?php }?>
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
								<a class="buyBtn buyBtnEnd">已结束</a>
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
							<p>
                                <b>0</b>天<b>0</b>小时<b>0</b>分<b>0</b>秒
                            </p>
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
<?php
}

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

<?php //快速链接{?>
<div class="quickNav" id="QuickNav">
	<div class="con">
		<h2>快速链接</h2>
		<ul>
			<?php
			//快速连接
			$sqlStr = 'SELECT count(*) as total, sgbh.issue_num FROM `products` p, specials_group_buy_history sgbh WHERE sgbh.products_id = p.products_id AND p.products_status = "1" AND p.products_stock_status="1" AND sgbh.specials_type=2 AND sgbh.expires_date <"'.$Today_date.'" Group By sgbh.issue_num Order By sgbh.issue_num ASC';
			$sqlQuery = tep_db_query($sqlStr);
			$Loop = 1;
			$toPage = 1;	//求每期的第1页在哪里？
			$nMaxRows = $max_rows_page;
			while($rows = tep_db_fetch_array($sqlQuery)){
				if($Loop>$nMaxRows){
					$nMaxRows+=$max_rows_page;
					$toPage++;
				}
				$Loop+=$rows['total'];
				
				$classLi = '';
				if($rows['issue_num']=="1"){
					$classLi = 'noborder';
				}
				
				$liGeneral = '<li class="'.$classLi.'"><a href="'.tep_href_link('group_buys.php','gb_type=2&do=expires&page='.$toPage).'#GroupHistory'.$rows['issue_num'].'">第'.$rows['issue_num'].'期团购</a></li>';
				$liTop = '<li class="'.$classLi.'"><a href="javascript:;" onclick="jQuery(\'html,body\').animate({scrollTop: jQuery(\'.groupTab\').offset().top}, 1000);">第'.$rows['issue_num'].'期团购</a></li>';
				$liOther = '<li class="'.$classLi.'"><a href="javascript:;" onclick="jQuery(\'html,body\').animate({scrollTop: jQuery(\'#GroupHistory'.$rows['issue_num'].'\').offset().top}, 1000);">第'.$rows['issue_num'].'期团购</a></li>';
				
				$liShow = $liGeneral;
				if($datas[0]['issue_num']==$rows['issue_num']) $liShow = $liTop;
				for($I=0; $I<$datas_count; $I++){
					if($rows['issue_num']==$datas[$I]['issue_num'] && $datas[$I]['issue_num']!=$datas[($I-1)]['issue_num'] && $datas[($I-1)]['issue_num']>0){
						$liShow = $liOther;
					}
				}
				echo $liShow;
			}
			
			/*
			?>
			<li class="noborder"><a href="javascript:;" onclick="jQuery('html,body').animate({scrollTop: jQuery('.groupTab').offset().top}, 1000);">第<?= $datas[0]['issue_num']?>期团购</a></li>
			<?php 
			for($I=0; $I<$datas_count; $I++){
				if($datas[$I]['issue_num']!=$datas[($I-1)]['issue_num'] && $datas[($I-1)]['issue_num']>0){
			?>
			<li><a href="javascript:;" onclick="jQuery('html,body').animate({scrollTop: jQuery('#GroupHistory<?= $datas[$I]['issue_num']?>').offset().top}, 1000);">第<?= $datas[$I]['issue_num']?>期团购</a></li>
			<?php
				}
			}
			*/
			?>
		</ul>
	</div>
</div>

<script type="text/javascript">
jQuery(function(){
    jQuery(window).scroll(function(){
        //if(jQuery("#GroupHistoryTag").offset()==null){ return false; }
		if(jQuery("#GroupTime").css("display") != "none" && jQuery("#GroupHistoryTag").offset().top < Math.max(document.documentElement.scrollTop, document.body.scrollTop)+20){
            jQuery("#QuickNav").show();
        }else{
            jQuery("#QuickNav").hide();
        }
    });
});
</script>
<?php //快速链接}?>

<script type="text/javascript">
<?php if($_GET['gb_type']==2){	//根据要求隐藏相关Tab?>
jQuery("#GroupNum").hide();
<?php }else{?>
jQuery("#GroupTime").hide();
jQuery(".groupHistoryNum").hide();
<?php }?>

</script>

<?php echo db_to_html(ob_get_clean());?>