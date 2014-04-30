<?php

$one_1_class = $one_2_class = $one_3_class = $one_4_class = $one_5_class = $one_6_class = $one_7_class = $one_8_class = $one_9_class =
$two_1_class = $two_2_class = $two_3_class = $two_4_class = '';
$con_one_1_display = $con_one_2_display = $con_one_3_display = $con_one_4_display = $con_one_5_display = $con_one_6_display = $con_one_7_display = $con_one_8_display = $con_one_9_display =
$con_two_1_display = $con_two_2_display = $con_two_3_display  = $con_two_4_display = ' style="display:block" ';	//Howard updated by 2013-07-08 默认开放所有的Tab内容

$mnu = isset($_GET['mnu']) ? $_GET['mnu'] : $HTTP_GET_VARS['mnu'];
if(!tep_not_null($mnu)){ $mnu = $_GET['mnu'] = $HTTP_GET_VARS['mnu'] = "tours"; }
switch($mnu){
	case "tours": $con_one_1_display=''; $one_1_class = 'class="selected"'; break;
	case "prices": $con_one_2_display=''; $one_2_class = 'class="selected"'; break;
	case "departure": $con_one_3_display=''; $one_3_class = 'class="selected"'; break;
	case "notes": $con_one_4_display=''; $one_4_class = 'class="selected"'; break;
	case "frequentlyqa": $con_one_5_display=''; $one_5_class = 'class="selected"'; break;
	case "cruisesIntroduction": $con_one_6_display=''; $one_6_class = 'class="selected"'; break;
	case "payment" :$con_one_7_display = ''; $one_7_class = 'class="selected"';break;
	
	case "reviews": $con_two_1_display=''; $two_1_class = 'class="selected"'; break;
	case "qanda": $con_two_2_display=''; $two_2_class = 'class="selected"'; break;
	case "photos": $con_two_3_display=''; $two_3_class = 'class="selected"'; break;
	case "video": $con_two_4_display=''; $two_4_class ='class="selected"'; break;

}


//行程介绍和游客评论在以下情况也是显示状态 start
//（1）行程介绍：游客评论、问题咨询、照片分享为当前页面时显示；
//（2）游客评论：问题咨询和照片分享均不是当前页面时显示；
//（3）视频资料：在没有视频的情况有均不显示
$chooseTab1style = ''; // 常见问题等tab选项的隐藏控制
if($mnu == 'qanda' || $mnu == 'reviews' || $mnu == 'photos' || $mnu == 'video'){
	$one_1_class = 'class="selected"';
	$con_one_1_display = '';
}
if($mnu == 'photos' ){
	echo "<script  type=\"text/javascript\">jQuery(function(){lazyload({defObj: '#reviews_photos_ul'});});</script>";
}
/*
if($mnu != 'qanda' && $mnu != 'photos' && $mnu != 'video'){
	$two_1_class = 'class="selected"';
	$con_two_1_display = '';
}*/

// 默认 点评显示
/*if($mnu != 'review'&&$mnu != 'reviews' && $mnu != 'photos' && $mnu != 'video' && $mnu != 'qanda'){
	$two_1_class = 'class="selected"';
	$con_two_1_display = '';
}*/

if(!tep_not_null($product_info['products_video'])){
	$con_two_4_display = $two_4_class = ' style="display:none" ';
}
if(!tep_not_null($final_departure_array_result)){
	$con_one_3_display = $one_3_class = ' style="display:none" ';
}

//行程介绍和游客评论在以下情况也是显示状态 end

//浏览全部问题、评论、图片的时候 隐藏行程相关信息 - vincent
if(tep_not_null($_GET['seeAll'])){
	$view_all_style = ' style="display:none"';
	
	$two_2_class = 'class="selected"';
	$con_two_2_display = '';
	
	$two_1_class = '';
	$con_two_1_display = 'style="display:none"';
}
//----------------------------------------------------

//判断常见问题是否隐藏
$frequently_question_query_raw = "select customers_name,que_id,question,DATE_FORMAT(date,'%Y-%m-%d %H:%i') as add_date from " . TABLE_QUESTION ." where products_id = '" . (int)$_GET['products_id'] . "' and set_hit='1' and languages_id = '" . (int)$languages_id . "' order by que_id desc limit 5 ";

$frequently_question_total = (int)tep_db_num_rows(tep_db_query($frequently_question_query_raw));
if(!$frequently_question_total){
	$one_5_class = ' style="display:none" ';
}
$expert_group_show = 'hide';
//判断专家有，并且要可用
if (tep_not_null($product_info['expert_ids'])) {
	$sql = "select count(expert_id) as t from expert_group where expert_id in (" . $product_info['expert_ids'] . ") and expert_disabled=0";
	$result = tep_db_query($sql);
	$row = tep_db_fetch_array($result);
	if ($row['t'] > 0) {
		$expert_group_show = 'show';
	}
}
?>

<script type="text/javascript">
function stop_goto(obj){
	obj.href = 'JavaScript:void(0);';
}
//设置或隐藏某些产品Tab
function setProductTab(name,cursel,n){
	var tit = document.getElementById("tit_"+name+"_"+cursel);
	jQuery("html,body").animate({scrollTop:jQuery(tit).position().top-0});
/*  for(var i=1;i<=n;i++){
    var menu=document.getElementById(name+i);
    if(menu!=null){
		var con=document.getElementById("con_"+name+"_"+i);
    	menu.className=i==cursel?"selected":"";
    	con.style.display=i==cursel?"block":"none";
	}
  }
*/
}
</script>
<div class="proInfo" <?php echo $view_all_style?> >
	<?php if($product_info['only_our_free']){//独家特惠?>
	<ul class="exclusiveSpecialsTab" style="overflow:visible;" ><li><a><?= db_to_html("独家特惠");?></a><span></span></li></ul>
	<div class="exclusiveSpecialsCon">
	<p><?=nl2br(db_to_html($product_info['only_our_free']))?></p>
	
	</div>
	<?php }?>
	
	<div id="tit_one_1" >
  <ul id="SuspendedLocation" class="chooseTab" style="overflow:visible;" >
	<?php
	//if($content=="product_info_vegas_show"){
	
	$itineraryStr = "行程内容";
	if($isHotels){
		$itineraryStr = "酒店介绍";
	}
	if ($product_info['products_type'] == 7){
		$departure_title = "演出时间/地点";
	}else{
		$departure_title = "出发时间/地点";
	}
	//$display_fast=false;
	if($display_fast!=true){
		//传统菜单已经被Howard 删除于2013-07-08
	}elseif($display_fast==true){	//JS快速切换菜单
	?>
		<li id="one1" toid="tit_one_1" <?php echo $one_1_class;?> suspension="true"><a id="anchor1" onclick="stop_goto(this); setProductTab('one',1,9);"><?php echo db_to_html($itineraryStr);?></a><span></span></li>
		<?php
		if($content=="product_info_vegas_show"){
			$one_2_class = $con_one_2_display; 
		}
		?>
		<li id="one2" toid="tit_one_2" <?php echo $one_2_class;?> suspension="true"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=prices&'.tep_get_all_get_params(array('info','mnu','page')));?>" onclick="stop_goto(this); setProductTab('one',2,9);"><?php echo db_to_html('价格明细');?></a><span></span></li>
		<?php if($expert_group_show == 'show'){ //旅程设计专家团?>
		<li id="one8" toid="tit_one_8" <?php echo $one_8_class;?> suspension="true"><a onclick="setProductTab('one',8,9);"><?php echo db_to_html('旅程设计专家团');?></a><span></span></li>
		<?php }
		if($product_info['with_air_transfer']=="1" && in_array($product_info['agency_id'], array(209,219,235,201))){?>
		<li id="one9" toid="tit_one_9" <?php echo $one_9_class;?> suspension="true"><a onclick="setProductTab('one',9,9);"><?php echo db_to_html('接机事项');?></a><span></span></li>
		<?php 
		}
		// lwkai 添加 如何预订 start { ?>
		<li id="one7" toid="tit_one_7" <?php echo $one_7_class;?> suspension="true"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=payment&' . tep_get_all_get_params(array('info','mnu','page')));?>" onclick="stop_goto(this);setProductTab('one',7,9);"><?php echo db_to_html('如何预订');?></a><span></span></li>
		<?php 
		// } lwkai 如何预订 end 
		
		if($isCruises){?>
		<li id="one6" toid="tit_one_6" <?php echo $one_6_class;?> suspension="true"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=cruisesIntroduction&'.tep_get_all_get_params(array('info','mnu','page')));?>" onclick="stop_goto(this); setProductTab('one',6,9);"><?php echo db_to_html($cruisesData['cruises_name'].'介绍');?></a><span></span></li>
		<?php
		}
		?>
		
		<li id="one3" toid="tit_one_3" <?php echo $one_3_class;?> suspension="true"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=departure&'.tep_get_all_get_params(array('info','mnu','page','rn')));?>" onclick="stop_goto(this); setProductTab('one',3,9);"><?php echo db_to_html($departure_title);?></a><span></span></li>
		
		<li id="one4" toid="tit_one_4" <?php echo $one_4_class;?> suspension="true"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=notes&'.tep_get_all_get_params(array('info','mnu','page','rn')));?>" onclick="stop_goto(this); setProductTab('one',4,9);"><?php echo db_to_html('注意事项');?></a><span></span><em class="pro-tab-note"></em></li>
		
		<li id="one5" toid="tit_one_5" <?php echo $one_5_class;?> suspension="true"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=frequentlyqa&'.tep_get_all_get_params(array('info','mnu','page','rn')));?>" onclick="stop_goto(this); setProductTab('one',5,9);"><?php echo db_to_html('常见问题');?></a><span></span></li>
		
		<li id="two1" toid="tit_two_1" <?php echo $two_1_class;?> suspension="true"><a id="anchor2" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=reviews&'.tep_get_all_get_params(array('info','mnu','rn')));?>" onclick="stop_goto(this); setProductTab('two',1,4);updateTitle('review');" ><?php echo db_to_html("用户点评");?></a><span></span></li>
		
		<li id="two2" toid="tit_two_2" <?php echo $two_2_class;?> suspension="true"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=qanda&'.tep_get_all_get_params(array('info','mnu','rn')));?>" onclick="stop_goto(this); setProductTab('two',2,4);updateTitle('question');" ><?php echo db_to_html("用户咨询");?></a><span></span></li>
		<li id="two3" toid="tit_two_3" <?php echo $two_3_class;?> suspension="true"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=photos&'.tep_get_all_get_params(array('info','mnu','rn')));?>" onclick="stop_goto(this); setProductTab('two',3,4); lazyload({defObj: '#reviews_photos_ul'});updateTitle('photo');"><?php echo db_to_html("照片分享"); ?></a><span></span></li>
		<li id="two4" toid="tit_two_4" <?php echo $two_4_class;?> suspension="true"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=video&'.tep_get_all_get_params(array('info','mnu','rn')));?>" onclick="stop_goto(this); setProductTab('two',4,4);"><?php echo db_to_html("视频资料"); ?></a><span></span></li>
		
	<?php
	}
	?>
  </ul>
	</div>
	
	<div id="con_one_1" class="chooseCon"  <?php echo $con_one_1_display;?>>
		<?php
		//行程详细信息
		if($mnu == 'tours' || $con_one_1_display=="" || $display_fast==true){ //start of tour section tab 
			include('product_info_module2_description_2011.php');//行程内容	
		} //end of tour section tab 
		?>
		<?php
			//选择乐园 Howard added by 2012-10-13 start {
			if(is_array($manualRelatedProductsInfo)){
			?>
			<div class="manualRelated">
				<div class="num0"></div>
				<div class="con">
				<div class="conTitle">
					<h2 style="color:#2D4DA2;"><?= db_to_html($manualRelatedProductsInfo['title']);?></h2>
				</div>
				<ul id="con_nav">			  
					<?php
					foreach((array)$manualRelatedProductsInfo['content'] as $val){
						if($val['id']==$product_info['products_id']){
					?>
						<li class="cur"><a href="javascript:void(0)"><?= db_to_html($val['text']);?></a></li>
					<?php
						}else{
					?>
						<li><a href="<?= $val['href'];?>"><?= db_to_html($val['text']);?></a></li>
					<?php
						}
					}
					?>
					</ul>
					<div style="clear:both"></div>
				</div>
			</div>
			<?php
			}
			//选择乐园 Howard added by 2012-10-13 end }
			?>
	</div>
	  
		<?php //价格明细?>
		<ul id="tit_one_2" class="chooseTab" style="overflow:visible;" ><li class="selected"><a><?= db_to_html("价格明细");?></a><span></span></li></ul>
	  <div id="con_one_2" class="chooseCon"  <?php echo $con_one_2_display;?>>
		<?php
		if($mnu == 'prices' || $display_fast==true){ //start of prices section tab 
		?> 
		<div id="review_desc_body" class="priceDetail">
			<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td>
				<?php
				if($isCruises){
					include('product_info_module_cruises_prices_detail.php');
				}else{
					include('product_info_module_right_3.php');
				}
				?>
				<?php include('product_info_module_right_includes.php');//费用包括?>
				<?php include('product_info_module_right_excludes.php');//费用不包括?>
				
				<?php
					//预订程序及电子参团凭证 start {
					echo '<h3>'.TEXT_HEADING_TOURS_DETAILS_RESERVATION_PROCCESS_ETICKET.'</h3>';
					$TOURS_DEFAULT_RESERVATION_PROCCESS_ETICKET = stripslashes2(TOURS_DEFAULT_RESERVATION_PROCCESS_ETICKET);
					if($isHotels){
						$TOURS_DEFAULT_RESERVATION_PROCCESS_ETICKET = str_replace(array('出团','导游'), array('入住酒店','酒店工作人员'),$TOURS_DEFAULT_RESERVATION_PROCCESS_ETICKET);
					}
					echo '<div class="description">'.db_to_html($TOURS_DEFAULT_RESERVATION_PROCCESS_ETICKET).'</div>';
					
					//预订程序及电子参团凭证 end }
					//订购条例 start {
					echo '<h3>'.TEXT_HEADING_TOURS_DETAILS_TERMS_AND_CONDITIONS.'</h3>';
					echo '<div class="description">'.stripslashes2(db_to_html(TOURS_DEFAULT_TERMS_AND_CONDITIONS)).'</div>';
					//订购条例 end }
				?>

				</td>
				</tr>
				</table>
			</div>
		<?php } //end of prices section tab ?>   		
		</div>
		<?php //价格明细end
		//旅程设计专家团 start{
		if($expert_group_show == 'show'){ //旅程设计专家团
		?>
		<ul id="tit_one_8" style="overflow:visible;" class="chooseTab"><li class="selected"><a><?= db_to_html('旅程设计专家团');?></a><span></span></li></ul>
		<div id="con_one_8" class="chooseCon" <?php echo $con_one_8_display;?>>
			<ul class="train_design cfix">
			<?php $sql = "select * from expert_group where expert_id in (" . $product_info['expert_ids'] . ") and expert_disabled=0";
			$result = tep_db_query($sql);
			$i = 1;
			while ($row = tep_db_fetch_array($result)) {?>
				<li class="cfix <?php if ($i==3){echo 'show';}?>">
					<div class="t_designer">
						<div class="t_thumb">
							<img src="<?php echo $row['expert_img']?>" alt="<?php echo db_to_html($row['expert_name'])?>" />
							<p><?php echo db_to_html($row['expert_job_title'])?></p>
						</div>
						<div class="t_thumb_info">
							<p class="t_thumb_name"><?php echo db_to_html($row['expert_name'])?></p>
							<p><?php echo db_to_html($row['expert_title'])?></p>
						</div>
					</div>
					<div class="t_designer_intro"><?php 
					echo db_to_html($row['expert_detail'])?>
						<?php /*<b>资深经历：<i class="color_y">（超过11年从业经验）</i></b>
						<p>出生于中国新疆塔里木盆地，啊手机好地方空间撒粉红色空间东方航空双方会计师的花费可节省刷卡缴费hd声卡见货付款将第三方和空间的收费空间第三方汉江的水发货肯定是放空间的收费空间划分空间的收费空间双方将看到合肥将收到货接口换行符但是</p>
						<b>尊享服务:</b>
						<p>出生于中国新疆塔里木盆地，啊手机好地方空间撒粉红色空间东方航空双方会计师的花费可节省刷卡缴费hd声卡见货付款将第三方和空间的收费空间第三方汉江的水发货肯定是放空间的收费空间划分空间的收费空间双方将看到合肥将收到货接口换行符但是手机好地方空间撒粉红色空间东方航空双方会计师的花费可节省刷卡缴费hd声卡见货付款将第三方和空间的收费空间第三方汉江的水发货肯定是放空间的收费空间划分空间的收费空间双方将看到合肥将收到货接口换行符但是手机好地方空间撒粉红色空间东方航空双方会计师的花费可节省刷卡缴费hd声卡见货付款将第三方和空间的收费空间第三方汉江的水发货肯定是放空间的收费空间划分空间的收费空间双方将看到合肥将收到货接口换行符但是</p>
						*/?>
					</div>
				</li>
				<?php 
				if ($i == 3) {
					break;
				}
				$i++;
			} /*?>
				<li class="show cfix">
					<div class="t_designer">
						<div class="t_thumb">
							<img src="/image/nav/abouts_img15.jpg" alt="" />
							<p>资深驴友设计师</p>
						</div>
						<div class="t_thumb_info">
							<p class="t_thumb_name">Lily Ren</p>
							<p>大家跟着我的热忱服务玩美西！</p>
						</div>
					</div>
					<div class="t_designer_intro">
						<b>资深经历：<i class="color_y">（超过11年从业经验）</i></b>
						<p>出生于中国新疆塔里木盆地，啊手机好地方空间撒粉红色空间东方航空双方会计师的花费可节省刷卡缴费hd声卡见货付款将第三方和空间的收费空间第三方汉江的水发货肯定是放空间的收费空间划分空间的收费空间双方将看到合肥将收到货接口换行符但是</p>
						<b>尊享服务:</b>
						<p>出生于中国新疆塔里木盆地，啊手机好地方空间撒粉红色空间东方航空双方会计师的花费可节省刷卡缴费hd声卡见货付款将第三方和空间的收费空间第三方汉江的水发货肯定是放空间的收费空间划分空间的收费空间双方将看到合肥将收到货接口换行符但是</p>
					</div>
				</li>
				<li class="cfix">
					<div class="t_designer">
						<div class="t_thumb">
							<img src="/image/nav/abouts_img15.jpg" alt="" />
							<p>资深驴友设计师</p>
						</div>
						<div class="t_thumb_info">
							<p class="t_thumb_name">Lily Ren</p>
							<p>大家跟着我的热忱服务玩美西！</p>
						</div>
					</div>
					<div class="t_designer_intro">
						<b>资深经历：<i class="color_y">（超过11年从业经验）</i></b>
						<p>出生于中国新疆塔里木盆地，啊手机好地方空间撒粉红色空间东方航空双方会计师的花费可节省刷卡缴费hd声卡见货付款将第三方和空间的收费空间第三方汉江的水发货肯定是放空间的收费空间划分空间的收费空间双方将看到合肥将收到货接口换行符但是</p>
						<b>尊享服务:</b>
						<p>出生于中国新疆塔里木盆地，啊手机好地方空间撒粉红色空间东方航空双方会计师的花费可节省刷卡缴费hd声卡见货付款将第三方和空间的收费空间第三方汉江的水发货肯定是放空间的收费空间划分空间的收费空间双方将看到合肥将收到货接口换行符但是</p>
					</div>
				</li>*/ ?>
			</ul>
		</div>
		<?php
		}
		//旅程设计专家团 end}
		//接机事项 start {
		if($product_info['with_air_transfer']=="1" && in_array($product_info['agency_id'], array(209,219,235,201))){
			ob_start();
		?>
		<ul id="tit_one_9" style="overflow:visible;" class="chooseTab"><li class="selected"><a>接机事项</a><span></span></li></ul>
		<div id="con_one_9" class="chooseCon train_meet_air cfix" <?php echo $con_one_9_display;?>>
			<div class="note_list">
				<dt>当您的航班抵达机场的时候，我们热情的导游将手持带有走四方旅游网标识和贵宾姓名的接机牌，等候您的到来。<i>（接机牌如右图所示）</i></dt>
				<strong><!--注意要点：--></strong>
				<dl>
					<dd></dd>
					<dd></dd>
					<dd></dd>
				</dl>
			</div>
			<div class="note_thumb"><img src="<?= DIR_WS_TEMPLATE_IMAGES;?>meet_air.gif" alt="接机牌" /></div>
			<div style="clear:both"></div>
		</div>
		<?php
			echo db_to_html(ob_get_clean());
		}
		//接机事项 end }
		//如何预订 内容 start {
		?>
		<ul id="tit_one_7" style="overflow:visible;" class="chooseTab"><li class="selected"><a><?= db_to_html('如何预订');?></a><span></span></li></ul>
		<div id="con_one_7" class="chooseCon" <?php echo $con_one_7_display;?>>
        <?php ob_start();?>
		<h3>在您预订行程前，请仔细阅读<a target="_blank" href="<?= tep_href_link('order_agreement.php');?>">订购协议</a>和<a target="_blank" href="<?= tep_href_link('change_plan.php');?>">变更取消条例</a>！</h3>
        <h3>订购步骤详解：</h3>
        <ul>
        	<li>一、您可以通过各种方式搜寻到您需要的行程，具体请点击<a href="<?= tep_href_link('order_process.php')?>" target="_blank">预订流程</a>了解详情，或可直接联系我们的客服咨询了解。</li>
            <li>二、找到心仪的行程后，请认真填写各类订单信息，并点击按钮“立即预订”，还可直接联系客服协助电话预订。</li>
            <li>三、在您预订成功后，系统会发一份自动生成的预订单到您所填写的Email邮箱里。</li>
            <li>四、您可以选择信用卡、Paypal、支付宝等方式进行在线支付，或者现金、支票、银行转账等其他方式线下支付，订单确认收到款项后我司方可安排确认具体的行程位置。</li>
            <li>五、在您支付成功后的1-4个工作日内，我们会将相应的行程确认信或者电子参团凭证发到您的邮箱。收到后请您仔细核对信息，若有异议，请48小时内联系走四方，若无异议，则视同为信息正确无误。</li>
            <li>六、旅行前请务必打印并携带好您的参团凭证及各类有效证件，上有参团的详细内容及地接公司紧急联络电话等重要信息。</li>
            <li>七、结束您的愉悦旅程后，您可以对行程进行点评，分享旅途中的点滴，更可获取相应的<a href="<?= tep_href_link('points.php');?>" target="_blank">积分奖励</a>，将来还可享受最高100%的优惠折扣。</li>
         </ul>
         <h3>支付方式：<span>(<a href="<?php echo tep_href_link('payment.php','','NONSSL',false)?>" target="_blank">查看全部支付方式</a>)</span></h3>
         <div class="zifufanshi">
         	<ul>
            <li>
            	<div class="left img1"></div>
                <div class="right">我们接受Visa、MasterCard、American Express、Discover及Debit卡，支持币种为美元。实时到帐，无任何手续费。另外，本网站已安装SSL证书，并通过了安全认证，您可以放心使用。</div>
            </li>
            <li>
            	<div class="left img2"></div>
                <div class="right">我们还为您提供了支付宝支付方式，没有支付宝帐户也可以通过支付宝平台合作的网银进行支付。</div>
            </li>
            <li>
            	<div class="left img3"></div>
                <div class="right">除了以上方式，我们还支持支票支付、美国银行转账和电汇、中国银行转账。<span><a href="<?php echo tep_href_link('payment.php','','NONSSL',false)?>" target="_blank">查看所有银行>></a></span></div>
            </li>
            </ul>
            <div class="del_float"></div>
         </div>   
        <?php echo db_to_html(ob_get_clean());?>
        </div>
		<?php 
		// } 如何预订 内容 end 
		
		if($isCruises){ //邮轮介绍?>
		  <ul id="tit_one_6" style="overflow:visible;" class="chooseTab"><li class="selected"><a><?= db_to_html('邮轮介绍');?></a><span></span></li></ul>
		  <div id="con_one_6" class="chooseCon chooseCon1"  <?php echo $con_one_6_display;?>>
			 <?php
			 if($mnu == 'cruisesIntroduction' || $display_fast==true){
			 	include('product_info_module_cruises_introduction.php');
			 }
			 ?> 
			</div>
		<?php } //邮轮介绍?>

	  <?php if($final_departure_array_result){	//接送时间和地点?>
	  <ul id="tit_one_3" style="overflow:visible;" class="chooseTab"><li class="selected"><a><?= db_to_html($departure_title);?></a><span></span></li></ul>
	  <div id="con_one_3" class="chooseCon chooseCon1"  <?php echo $con_one_3_display;?>> 
		<?php
		//接送时间和地点
		if($mnu == 'departure' || $display_fast==true){
			include('product_info_module_right_4.php');
		}
		?>
		</div>
	  <?php }?>
	  
	  <ul id="tit_one_4" style="overflow:visible;" class="chooseTab"><li class="selected"><a><?= db_to_html('注意事项');?></a><span></span></li></ul>
	  <div id="con_one_4" class="chooseCon"  <?php echo $con_one_4_display;?>> 
		<?php
		//注意事项
		if($mnu == 'notes' || $display_fast==true){
			include('product_info_module_notes.php');
		}
		?>
		</div>
	  
	  <?php if($frequently_question_total){?>
	  <ul style="overflow:visible;" class="chooseTab"><li class="selected"><a><?= db_to_html('常见问题');?></a><span></span></li></ul>
	  <div id="con_one_5" class="chooseCon chooseCon1"  <?php echo $con_one_5_display;?>> 
		<?php
		//常见问题
		if($mnu == 'frequentlyqa' || $display_fast==true){
			include('product_info_module_frequentlyqa.php');
		}
		?>
		</div>
	<?php }?>
	
</div>
<div class="proInfo" >
  <ul id="tit_two_1" class="chooseTab">
	<?php
	if($display_fast!=true){
		//传统菜单已经被Howard 删除于2013-07-08
	}elseif($display_fast==true){	//JS快速切换菜单
	?>
	<li class="selected"><a><?= db_to_html("用户点评");?></a><span></span></li>
    <?php
	/*
	<li id="two1" <?php echo $two_1_class;?>><a id="anchor2" href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=reviews&'.tep_get_all_get_params(array('info','mnu','rn')));?>" onclick="stop_goto(this); setProductTab('two',1,4);updateTitle('review');" ><?php echo db_to_html("用户点评");?></a><span></span></li>
	<li id="two2" <?php echo $two_2_class;?>><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=qanda&'.tep_get_all_get_params(array('info','mnu','rn')));?>" onclick="stop_goto(this); setProductTab('two',2,4);updateTitle('question');" ><?php echo db_to_html("用户咨询");?></a><span></span></li>
	
	<li id="two3" style="display:none" <?php echo $two_3_class;?>><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=photos&'.tep_get_all_get_params(array('info','mnu','rn')));?>" onclick="stop_goto(this); setProductTab('two',3,4); lazyload({defObj: '#reviews_photos_ul'});updateTitle('photo');"><?php echo db_to_html("照片分享"); ?></a><span></span></li>
	<li id="two4" <?php echo $two_4_class;?>><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'mnu=video&'.tep_get_all_get_params(array('info','mnu','rn')));?>" onclick="stop_goto(this); setProductTab('two',4,4);"><?php echo db_to_html("视频资料"); ?></a><span></span></li>
	*/?>
	<?php
	}
	?>
  </ul>
	<div id="con_two_1" class="chooseCon chooseCon1" <?=$con_two_1_display?>> 
		<?php
		if($mnu == 'reviews' || $con_two_1_display == "" || $display_fast==true ){
			include('product_reviews_tabs_ajax.php');
		}
		?>
		
		
	</div>
	  
	  <ul id="tit_two_2" style="overflow:visible;" class="chooseTab"><li class="selected"><a><?= db_to_html('用户咨询');?></a><span></span></li></ul>
	  <div id="con_two_2" class="chooseCon chooseCon1" <?= $con_two_2_display?>> 
		<?php
		//问题咨询
		if($mnu == 'qanda' || $display_fast==true){
			//question_info.php
			include(FILENAME_QUESTION_INFO);
		}
		?>
	</div> 

	  <ul id="tit_two_3" style="overflow:visible;" class="chooseTab"><li class="selected"><a><?= db_to_html('照片分享');?></a><span></span></li></ul>
	  <div id="con_two_3" class="chooseCon chooseCon1" <?= $con_two_3_display?> style="display:none"> 
        
		<div class="photoTop">
          <div class="left">
			<?php
			echo db_to_html("您可以将旅途中的靓照与走四方旅游网分享哦，晒晒你的旅途照片，可以结识更多的旅友哦！赶快上传照片吧！");
			/*if ((USE_POINTS_SYSTEM == 'true') && (tep_not_null(USE_POINTS_FOR_REVIEWS))) {
				if((int)$customer_id){
					echo sprintf(PHOTOS_HELP_LINK, USE_POINTS_FOR_PHOTOS, '<a href="' . tep_href_link('my_points.php','', 'SSL') . '" class="sp3" title="' . MY_POINTS_VIEW . '">' . MY_POINTS_VIEW . '</a>');
				
				}else{
					echo sprintf(PHOTOS_HELP_LINK, USE_POINTS_FOR_PHOTOS, '<a href="' . tep_href_link('points.php') . '" class="sp3" title="' . TEXT_MENU_JOIN_REWARDS4FUN . '">' . TEXT_MENU_JOIN_REWARDS4FUN . '</a>');
				}
			}*/
			?>
		  </div>
          <div class="right">
		  <script type="text/javascript">
		  jQuery(document).ready(function(){
		  	  jQuery.get('/lvtu/album/getProductsImages/pid--<?php echo $product_info['products_id']?>.html',function(r){
			  	//console.log(r);
				//if(r.length == 0) {
					//jQuery('#tit_two_3,#con_two_3,#two3').hide();
				//}	
				var html = '';
				jQuery('#review_result_photo').css('width','auto');
				jQuery.each(r,function(i,n){
					html += '<li><a href="' + n.href + '" target="_blank"><img src="' + n.pic + '" alt="' + n.username + '"/><p>' + n.username + '</p></a></li>'; 
				});
				jQuery('#review_result_photo').html(html);
			  },'json');
		  });
		  </script>
		  <p><?php #echo db_to_html("您可以同时选择多张照片上传");?><input id="uploaded_photos_box" type="hidden" value="" /></p>
		  <?php
		  $ALinkAddPhotoDisplay="none";
		  $ALinkAddPhotoNoLoginDisplay="";
		  if((int)$customer_id){
			  $ALinkAddPhotoDisplay = "";
			  $ALinkAddPhotoNoLoginDisplay = "none";
		  }
		  ?>
		  <?php /*<a id="ALinkAddPhoto" style="display:<?= $ALinkAddPhotoDisplay;?>" class="btn btnGrey" href="javascript:void(0)"><div id="divAddPhoto"><?php echo db_to_html("加载中…");?></div></a>
		   <a id="ALinkAddPhotoNoLogin" style="display:<?= $ALinkAddPhotoNoLoginDisplay;?>" class="btn btnGrey" href="javascript:void(0)" onclick="showPopupForm('<?php echo preg_replace($p,$r,tep_href_link_noseo('review_photos.php','action=FastLoginProcess'))?>','CommonFastLoginPopup', 'CommonFastLoginPopupConCompare' ,'off')"><div style="width:75px; height:20px;"><?php echo db_to_html("分享照片");?></div></a>*/?>
		   <a href="/lvtu/album/add.html" target="_blank" class="btn btnGrey" style="background:url(/image/nav/upimg.gif);width:97px;height:29px;"><div style="width:98px; height:29px;color:#fff;line-height:29px;font-size:14px;font-weight:bold;"><?php echo db_to_html("分享照片");?></div></a>
		  </div>
        </div>
		<ul id="review_result_photo" class="photoList" style="width:auto">
		
		</ul>
		<?php
		//照片分享
		if($mnu == 'photos' || $display_fast==true){
			//include(FILENAME_REVIEWS_PHOTOS);
		}
		?>
	</div>

	<?php if(tep_not_null($product_info['products_video'])){?>
	  <ul id="tit_two_4" style="overflow:visible;" class="chooseTab"><li class="selected"><a title="<?= $product_info['products_video'];?>"><?= db_to_html('视频资料');?></a><span></span></li></ul>
	  <div id="con_two_4" class="chooseCon" <?= $con_two_4_display?>> 
		<?php
		if($product_info['products_video'] != '' && ($mnu == 'video' || $display_fast==true) ){
			include('tours_video.php');
		}
		?>
	</div>
	<?php }?>
	
 </div>