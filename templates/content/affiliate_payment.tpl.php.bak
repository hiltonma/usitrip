<div class="userTitle"><ul><li><a href="<?= tep_href_link('affiliate_sales.php');?>"><?= NAVBAR_TITLE_2_1?></a></li><li class="cur"><?= NAVBAR_TITLE_2;?></li></ul></div>



<div class="mytoursTip" id="TopTip">
        <span><a id="hideId"  href="javascript:;" ><?= db_to_html("隐藏");?></a></span>
        <div id="tipbox">
        	<?php echo TEXT_PAGE_HEADING_SUB; ?>
        </div>
    </div>

<?php ob_start();?>
<script type="text/javascript">
    jQuery("#hideId").toggle(function(){
        jQuery("#tipbox").css({"height":"15px","overflow":"hidden"}); 
        jQuery(this).html("展开");
    },function(){
        jQuery("#tipbox").css("height", "auto");
        jQuery(this).html("隐藏");
    });
</script>

<div class="myPayment">
        <div class="salesTotal">
            <span>收款总笔数：<?= '<b>' . $affiliate_payment['totalRows'].'</b>'; ?></span>
			<span>佣金总额：</span>
            <span><strong><?= $currencies->display_price($affiliate_payment['total'], '');?></strong></span>
        </div>
        <div class="salesSearch" style="border-bottom: 1px dashed #DBDBDB;">
            <form action="" method="get" enctype="application/x-www-form-urlencoded" name="searchForm" target="_self">
			<?= tep_draw_input_num_en_field('orders_date_start', '', 'onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" class="textTime"');?>
			至
            <?= tep_draw_input_num_en_field('orders_date_end', '', 'onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" class="textTime"');?>
			<input type="submit" class="btnGrey" value="搜 索" />
			<a href="<?= tep_href_link('affiliate_payment.php',tep_get_all_get_params(array('orders_date_start','orders_date_end')));?>">清除搜索条件</a>
			<?php /* if(tep_not_null($orders_date_start) || tep_not_null($orders_date_end)){?>
			<span>收款总笔数：<?= '<b>' . $affiliate_payment_split->number_of_rows.'</b>'; ?></span>
			<span>获得佣金：<strong><?= $currencies->display_price($current_commission, '');?></strong></span>
			<?php } */
			echo tep_draw_hidden_field('status');
			?>
			</form>
        </div>
        <div class="userTitle" style="margin-top:8px">
        <span style="float:right;display:inline-block;line-height:25px;"><?php echo ($status == 'ispay') ? '已付款' : '未付款';?>佣金小计：<strong style="color:#F7860F"><?php echo $currencies->display_price($current_commission, '');?></strong></span>
        <ul>
        	<li <?php if ($status == 'needpay' || $status == '') { echo ' class="cur"';}?>>
        	<?php if ($status == 'needpay' || $status == '') {
        		echo '未付款(' . $affiliate_payment_split->number_of_rows . ')';
        	} else {
        	?>
        	<a href="<?php echo $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params(array('status')) . 'status=needpay'?>">未付款(<?php echo $affiliate_payment_pay['totalRows'];?>)</a>
        	<?php }?></li>
        	<li <?php if ($status == 'ispay') { echo ' class="cur"';}?>>
        	<?php if ($status == 'ispay') {
        		echo '已付款(' . $affiliate_payment_split->number_of_rows . ')';
        	} else {?>
        	<a href="<?php echo $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params(array('status')) . 'status=ispay'?>">已付款(<?php echo $affiliate_payment_pay['totalRows']?>)</a>
        	<?php }?>
        	</li>
        </ul>
        </div>
        <table class="com_table">
        	<thead>
                <tr>
                    <th width="138">付款编号</th>
                    <th width="126">付款日期</th>
                    <th width="138">金额</th>
                    <th width="132">付款状态</th>
                    <th>订单号</th>
                    <th>备注</th>
                </tr>
            </thead>
            <tbody>
            	<?php
					$affiliate_payment_values = tep_db_query($affiliate_payment_split->sql_query);
					while ($affiliate_payment = tep_db_fetch_array($affiliate_payment_values)) {
				?>
            	<tr>
                	<td><?= $affiliate_payment['affiliate_payment_id']; ?></td>
                    <td><?php if ($affiliate_payment['affiliate_payment_status'] == '1') {
                    	//tep_date_short($affiliate_payment['affiliate_payment_date']); 
                    	$sql = "select affiliate_date_added from affiliate_payment_status_history where affiliate_payment_id='" . $affiliate_payment['affiliate_payment_id'] . "' and affiliate_new_value=1 limit 1";
                    	$result = tep_db_query($sql);
                    	$row = tep_db_fetch_array($result);
                    	if ($row) {
							echo $row['affiliate_date_added'];
						}
                    }
						?></td>
                    <td><?= $currencies->display_price($affiliate_payment['affiliate_payment'], '');?></td>
                    <td><?= $affiliate_payment['affiliate_payment_status_name'];?></td>
                    <td><?php 
                    $sql = "select affiliate_orders_id from affiliate_sales where affiliate_payment_id='" . $affiliate_payment['affiliate_payment_id'] . "'";
                    $result = tep_db_query($sql);
                    while ($row = tep_db_fetch_array($result)) {
						echo $row['affiliate_orders_id'] . '<br/>';
					}
                    ?></td>
                    <td><?php echo $affiliate_payment['comment'];?>&nbsp;</td>
                </tr>
                <?php }?>
            </tbody>
        </table>
        <?php 
  if ($affiliate_payment_split->number_of_pages > 1) {
?> 
		<div class="salesList">       
            <div class="con">
            <div class="page" style="width:700px;">
   
          <?php echo $affiliate_payment_split->display_links_2011(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
			</div>
        </div>
    </div>
    <?php
  }
?>
</div>

<?php echo db_to_html(ob_get_clean());?>