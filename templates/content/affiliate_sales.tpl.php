<div class="userTitle"><ul><li class="cur"><?= NAVBAR_TITLE_2;?></li><li><a href="<?= tep_href_link('affiliate_payment.php');?>"><?= NAVBAR_TITLE_2_1?></a></li></ul></div>



<?php //请Afie处理以下模板程序{?>
      <div class="mySales">
        <div class="salesTotal" style="display:none">
            <span><?php echo TEXT_AFFILIATE_HEADER . ' <b>' . $AffiliateInfo['affiliate_transactions'].'</b>'; ?></span>
            <span><?php echo TEXT_INFORMATION_SALES_TOTAL . ' <strong>' . $currencies->display_price($AffiliateInfo['affiliate_commission'], '') .'</strong>' ;//. TEXT_INFORMATION_SALES_TOTAL2; ?></span>
        </div>
        
        <div class="salesSearch" style="border-bottom: 1px dashed #DBDBDB;">
            <form action="" method="get" enctype="application/x-www-form-urlencoded" name="searchForm" target="_self">
			<?= db_to_html("生成日期：")?>
			<?= tep_draw_input_num_en_field('orders_date_start', '', 'onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" class="textTime"');?>
			<?= db_to_html("至")?>
            <?= tep_draw_input_num_en_field('orders_date_end', '', 'onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" class="textTime"');?>
			<label class="btn btnGrey"><button type="submit"><?= db_to_html("搜 索")?></button></label>
			<a href="<?= tep_href_link('affiliate_sales.php',tep_get_all_get_params(array('orders_date_start','orders_date_end')));?>"><?= db_to_html("清除搜索条件")?></a>
			<?php
			/* if(tep_not_null($orders_date_start) || tep_not_null($orders_date_end)){?>
			<span><?= db_to_html("订单数：").$affiliate_sales_split->number_of_rows;?></span><span><?= db_to_html("获得佣金：")?><strong><?= $currencies->display_price($current_commission['current'], '');?></strong></span>
			<?php }*/

			echo tep_draw_hidden_field('status');
			?>
			</form>
        </div>
        <?php ob_start()?>
        <div class="userTitle" style="margin-top:8px;">
        	<span style="float:right;display:inline-block;"><?php echo $title_text . "佣金小计："?><strong style="color:#F7860F"><?= $currencies->display_price($current_commission['current'], '');?></strong></span>
        	<ul>
        		<li<?php if ($status == 'points' || $status == '') {echo ' class="cur" ';}?>><?php 
        		if ($status == 'points' || $status == '') {
					echo '已出团(' . $affiliate_sales_split->number_of_rows . ')';
				} else {
	        		echo '<a href="' . $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params(array('status')) . 'status=points">已出团(' . $current_commission['points'] . ')</a>';
      			}?></li>
        		<li<?php if ($status == 'ticket'){ echo ' class="cur"';}?>>
        		<?php if ($status == 'ticket') {
        			echo '已发电子参团凭证-未出团(' . $affiliate_sales_split->number_of_rows . ')';
        		} else {?>
        		<a href="<?php echo $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params(array('status')) . 'status=ticket'?>">已发电子参团凭证-未出团(<?php echo $current_commission['ticket'];?>)</a>
        		<?php }?></li>
        		<li class="tooltip <?= ($status == 'other' ? 'cur' : '');?>" tooltip="此处存放的是客人未付款、未出团、未发电子参团凭证或已被取消的订单">
        		<?php if ($status == 'other') {
        			echo '其它(' . $affiliate_sales_split->number_of_rows .')';
        		} else {
        		?><a href="<?php echo $_SERVER['PHP_SELF'] . '?' . tep_get_all_get_params() . 'status=other'?>">其它(<?php echo $current_commission['other']?>)</a>
        		<?php }?></li>
        	</ul>
        </div>
        <?php echo db_to_html(ob_get_clean());?>
        <table class="report_table">
        	<thead>
                <tr>
                    <th><?= db_to_html("订单号")?></th>
                    <th><?= db_to_html("生成日期");?></th>
                    <th><?= db_to_html("出团日期");?></th>
                    <th><?= db_to_html("下单人");?></th>
                    <th><?php echo TABLE_HEADING_TOURNAME; ?></th>
                    <th><?php echo TABLE_HEADING_VALUE; ?></th>
                    <th><?php echo TABLE_HEADING_PERCENTAGE; ?></th>
                    <th><?php echo TABLE_HEADING_SALES; ?></th>
                    <th><?php echo TABLE_HEADING_STATUS; ?></th>
                </tr>
            </thead>
            <tbody>
            	<?php
				if ($affiliate_sales_split->number_of_rows > 0) {
					$affiliate_sales_values = tep_db_query($affiliate_sales_split->sql_query);
					while ($affiliate_sales = tep_db_fetch_array($affiliate_sales_values)) {
				?>
                <tr>
                    <td><?php echo $affiliate_sales['affiliate_orders_id']; ?></td>
                    <td><?php echo tep_date_short($affiliate_sales['affiliate_date']); ?></td>
                    <td><?php echo tep_get_date_of_departure($affiliate_sales['affiliate_orders_id']);?></td>
                    <td>
                    <?php
					if(tep_not_null($affiliate_sales['customers_name'])){
						echo db_to_html(tep_db_output($affiliate_sales['customers_name']));
					}else{
						echo db_to_html(tep_customers_name($affiliate_sales['customers_id']));
					}
					?>
                    </td>
                    <td width="160"><p><?php echo tep_get_product_name_by_order_id($affiliate_sales['affiliate_orders_id']); ?></p></td>
                    <td><?php echo $currencies->display_price($affiliate_sales['affiliate_value'], ''); ?></td>
                    <td><?php echo $affiliate_sales['affiliate_percent'] . " %"; ?></td>
                    <td><?php echo $currencies->display_price($affiliate_sales['affiliate_payment'], ''); ?></td>
                    <td><?php if ($affiliate_sales['orders_status']) echo db_to_html($affiliate_sales['orders_status']); else echo TEXT_DELETED_ORDER_BY_ADMIN; ?></td>
                </tr>
                <?php
					}
				}else{ 
					//echo TEXT_NO_SALES; 
				}
				?>
            </tbody>
        </table><?php 
  if ($affiliate_sales_split->number_of_pages > 1) {
?> 
        <div class="salesList">
            <div class="con">
            <div class="page" style="width:700px;">
   
          <?php echo $affiliate_sales_split->display_links_2011(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
			</div>
        </div>
    </div><?php
  }
?>
</div>

<?php //}?>


<script type="text/javascript" src="/includes/javascript/tips.js"></script>
