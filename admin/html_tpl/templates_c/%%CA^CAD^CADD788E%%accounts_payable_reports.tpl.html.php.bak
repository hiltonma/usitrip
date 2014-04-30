<?php /* Smarty version 2.6.25-dev, created on 2013-12-27 00:58:24
         compiled from accounts_payable_reports.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'accounts_payable_reports.tpl.html', 78, false),array('function', 'html_input', 'accounts_payable_reports.tpl.html', 83, false),array('modifier', 'escape', 'accounts_payable_reports.tpl.html', 83, false),array('modifier', 'date_format', 'accounts_payable_reports.tpl.html', 155, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main_header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
jQuery().ready(function() {
	var Random=(Math.random()*10+1);
	$("#RandomNumber").val(Random);
	$("#form_search").find(':radio').click(function(){
		$("#form_search").submit();
	});
});

function DownloadCVS(){
	var Random=(Math.random()*10+1);
	$("#RandomNumber").val(Random);
	$("#download").val("1");
	$("#form_search").attr("target","_blank");
	$("#form_search").submit();
	$("#form_search").attr("target","_self");
	$("#download").val("0");
}

/* 提交确认成本和invoice */
function confirm_invoice_cost(FormObj){
	var Form = FormObj;
	jQuery("#"+Form.id).find("input[type='submit']").val('提交中……');
	var url = "accounts_payable_reports.php?action=confirm";
	var form_id = Form.id;
	var success_msm = "";
	var success_go_to = "";
	ajax_post_submit(url,form_id,success_msm,success_go_to);
	return false;
}

/* 提交备注信息 */
function update_admin_comment(FormObj){
	var Form = FormObj;
	jQuery("#"+Form.id).find("input[type='submit']").val('提交中……');
	var url = "accounts_payable_reports.php?action=update_admin_comment";
	var form_id = Form.id;
	ajax_post_submit(url,form_id);
	return false;
}

/* 提交中国会计填写的成本和美国会计填写的成本或实收价 */
function submit_cost_and_final_price_china_or_usa(inputBox, ordersProductsId, chinaOrUsa){
	if(jQuery(inputBox).attr('oldvalue') != inputBox.value){
		//alert(inputBox.value+':'+ordersProductsId+':'+chinaOrUsa);
		if(inputBox.value !="" && inputBox.value.search(/[^0-9_\.]/g)!=-1 ){ alert("对不起，只接受数字格式输入！"); return false; }
		var url = "accounts_payable_reports.php?action=submit_cost_and_final_price_china_or_usa";
		url += "&orders_products_id=" + ordersProductsId;
		if(chinaOrUsa=='china'){
			url += "&china_bookkeeper_final_price_cost=" + inputBox.value;
		}else if(chinaOrUsa=='usa'){
			url += "&usa_bookkeeper_final_price_cost=" + inputBox.value;
		}else if(chinaOrUsa=='usafinalprice'){
			url += "&usa_bookkeeper_final_price=" + inputBox.value;
		}else if(chinaOrUsa=='beforedeparturecost'){
			url += "&before_departure_cost=" + inputBox.value;
		}
		ajax_get_submit(url);
		jQuery(inputBox).attr('oldvalue', inputBox.value);
	}
}
</script>
<div class="main">
  <form action="" method="get" id="form_search" target="_self" name="form_search">
  	<div class="ItemsTj">
  		<h1 class="ItemsH1"  id="tit" onclick="showHideLyer(this,'CI_content','ItemsH1Select')"><?php echo @SEARCH_CRITERIA; ?>
</h1>
  		<div class="ItemsTjContent" id="CI_content" style="width:auto;">
  			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td nowrap="nowrap"><?php echo @DEPARTURE_DATE; ?>
：</td>
					<td nowrap="nowrap">
					
  			<input type="text" class="textTime" name="S_start_date" value="<?php echo $this->_tpl_vars['S_start_date']; ?>
" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" style="ime-mode: disabled;" /><?php echo @SEARCH_DATE_TO; ?>
<input type="text" class="textTime" name="S_end_date" value="<?php echo $this->_tpl_vars['S_end_date']; ?>
" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" />
<span style="color:#FF0000">为了不占用太多系统资源请选择时间段搜索！</span>  					
					</td>
					<td align="right" nowrap="nowrap"><?php echo @SEARCH_FILTER; ?>
</td><td nowrap="nowrap"><label style="display:inline; margin:5px;"><?php echo smarty_function_html_radios(array('name' => 'Filter','values' => '0','output' => @ALL_DEAL_WITH,'selected' => $this->_tpl_vars['Filter'],'labels' => ''), $this);?>
</label>&nbsp;<label style="display:inline; margin:5px;"><?php echo smarty_function_html_radios(array('name' => 'Filter','values' => '1','output' => @THE_TAX_CURRENTLY_PAYABLE,'selected' => $this->_tpl_vars['Filter'],'labels' => ''), $this);?>
</label>&nbsp;<label style="display:inline; margin:5px;"><?php echo smarty_function_html_radios(array('name' => 'Filter','values' => '2','output' => @FUTURE_PAYMENT,'selected' => $this->_tpl_vars['Filter'],'labels' => ''), $this);?>
</label>&nbsp;<label style="display:inline; margin:5px;"><?php echo smarty_function_html_radios(array('name' => 'Filter','values' => '3','output' => @PURCHASED_DATE_PAYMENT,'selected' => $this->_tpl_vars['Filter'],'labels' => ''), $this);?>
</label>&nbsp;<label style="display:inline; margin:5px;"><?php echo smarty_function_html_radios(array('name' => 'Filter','values' => '4','output' => @COST_NOT_MATCH_AMOUNT,'selected' => $this->_tpl_vars['Filter'],'labels' => ''), $this);?>
</label>&nbsp;<label style="display:inline; margin:5px;"><?php echo smarty_function_html_radios(array('name' => 'Filter','values' => '5','output' => @COST_MATCH_AMOUNT,'selected' => $this->_tpl_vars['Filter'],'labels' => ''), $this);?>
</label>
					<!--<label style="display:inline; margin:5px;"><?php echo smarty_function_html_radios(array('name' => 'Filter','values' => '3','output' => '待确定支付','selected' => $this->_tpl_vars['Filter'],'labels' => ''), $this);?>
</label>-->
					</td>
  					</tr>
				<tr>
					<td>供应商：</td><td colspan="3"><?php echo $this->_tpl_vars['S_ProviderSelectMenu']; ?>
&nbsp;&nbsp;  						<!--<?php echo smarty_function_html_input(array('name' => 'S_Provider','enterkey' => 'true','value' => ((is_array($_tmp=$this->_tpl_vars['S_Provider'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')),'parameters' => ' class="textAll" '), $this);?>
-->
						订单ID：<?php echo smarty_function_html_input(array('name' => 'S_orders_id','enterkey' => 'true','value' => ((is_array($_tmp=$this->_tpl_vars['S_orders_id'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')),'parameters' => ' class="textAll" '), $this);?>
&nbsp;&nbsp;&nbsp;&nbsp;
						工号：<?php echo smarty_function_html_input(array('name' => 'S_orders_owners','enterkey' => 'true','value' => ((is_array($_tmp=$this->_tpl_vars['S_orders_owners'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')),'parameters' => ' class="textAll" '), $this);?>
&nbsp;&nbsp;&nbsp;&nbsp;
						地接团号：<?php echo smarty_function_html_input(array('name' => 'S_provider_tour_code','enterkey' => 'true','value' => ((is_array($_tmp=$this->_tpl_vars['S_provider_tour_code'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')),'parameters' => ' class="textAll" '), $this);?>
&nbsp;&nbsp;&nbsp;&nbsp;
						发票号：<?php echo smarty_function_html_input(array('name' => 'S_customer_invoice_no','enterkey' => 'true','value' => ((is_array($_tmp=$this->_tpl_vars['S_customer_invoice_no'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')),'parameters' => ' class="textAll" '), $this);?>
&nbsp;&nbsp;&nbsp;&nbsp;
						订单下单类型： <?php echo $this->_tpl_vars['S_is_other_ownerSelectMenu']; ?>

						 </td>
				</tr>
				<tr><td colspan="8" class="ButtonAligh">
					<button type="submit" class="Allbutton"><?php echo @SEARCH; ?>
</button> <button class="AllbuttonHui" type="button" onclick="javascript: location='<?php echo $this->_tpl_vars['href_accounts_payable_reports']; ?>
'"><?php echo @CLEAR_SEARCH_DATA; ?>
</button>
					<?php if ($this->_tpl_vars['rows_total'] < 4000 && $this->_tpl_vars['rows_total'] > 0): ?>
					<button class="Allbutton" type="button" onclick="javascript: DownloadCVS();"><?php echo @DOWNLOAD_DATA_TO_THE_LOCAL; ?>
</button>
					<?php endif; ?>
					<input id="download" name="download" type="hidden" value="0" />
					<input name="sort_type" type="hidden" value="<?php echo $this->_tpl_vars['sort_type']; ?>
" />
					<input name="sort" type="hidden" value="<?php echo $this->_tpl_vars['sort']; ?>
" />
					<input id="RandomNumber" name="RandomNumber" type="hidden" value="" />
					<table width="200" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td align="right"><?php echo @INVOICE_NUMBER_SUM; ?>
</td>
							<td align="right"><?php echo $this->_tpl_vars['invoice_total']; ?>
</td>
							</tr>
						<tr>
							<td align="right"><?php echo @COST_SUM; ?>
</td>
							<td align="right"><?php echo $this->_tpl_vars['cost_total']; ?>
</td>
							</tr>
						<tr>
							<td align="right">实收总计:</td>
							<td align="right"><?php echo $this->_tpl_vars['_final_price_total']; ?>
</td>
							</tr>
						</table>
					
				</td></tr>
  				</table>
  			</div>
  		</div>
  	</form>

 <div class="ItemsLb">
   <h1 class="ItemsH1"  id="Lb" onclick="showHideLyer(this,'Lb_content','ItemsH1Select')"><?php echo @SEARCH_RESULTS; ?>
</h1>
   <div class="ItemsLbContent" id="Lb_content">
<table class="DdTab">
	<tr>
		<th nowrap="nowrap" class="header">订单ID<?php echo $this->_tpl_vars['orders_sort_up_link']; ?>
<?php echo $this->_tpl_vars['orders_sort_down_link']; ?>
</th>
		<th nowrap="nowrap">工号</th>
		<th nowrap="nowrap" class="header"><?php echo @PURCHASED_DATE; ?>
<?php echo $this->_tpl_vars['purchased_sort_up_link']; ?>
<?php echo $this->_tpl_vars['purchased_sort_down_link']; ?>
</th>
		<th nowrap="nowrap"class="header" title="不含退款日期">
		客人付款时间
		</th>
		<th nowrap="nowrap"class="header">
		<?php echo @DEPARTURE_DATE; ?>
<?php echo $this->_tpl_vars['departure_sort_up_link']; ?>
<?php echo $this->_tpl_vars['departure_sort_down_link']; ?>
</th>
		<!--<th nowrap="nowrap">供应商</th>-->
		<th nowrap="nowrap">地接团号</th>
		<th nowrap="nowrap">参团人数</th>
		<th nowrap="nowrap">是否已支付</th>
		<th nowrap="nowrap" class="header">发票号<?php echo $this->_tpl_vars['invoice_number_sort_up_link']; ?>
<?php echo $this->_tpl_vars['invoice_number_sort_down_link']; ?>
</th>
		<th nowrap="nowrap" class="header">发票金额<?php echo $this->_tpl_vars['invoice_amount_sort_up_link']; ?>
<?php echo $this->_tpl_vars['invoice_amount_sort_down_link']; ?>
</th>
		<th nowrap="nowrap" class="header">底价<?php echo $this->_tpl_vars['cost_sort_up_link']; ?>
<?php echo $this->_tpl_vars['cost_sort_down_link']; ?>
</th>
		<th nowrap="nowrap" class="header">出团前底价</th>
		<th nowrap="nowrap" class="header">出团后底价</th>
		<th nowrap="nowrap" class="header">USA财务底价</th>
		<th nowrap="nowrap">实收价<?php echo $this->_tpl_vars['sales_sort_up_link']; ?>
<?php echo $this->_tpl_vars['sales_sort_down_link']; ?>
</th>
		<th nowrap="nowrap">USA财务实收</th>
		<th nowrap="nowrap">GP(%)<?php echo $this->_tpl_vars['gp_sort_up_link']; ?>
<?php echo $this->_tpl_vars['gp_sort_down_link']; ?>
</th>
		<th nowrap="nowrap">订单状态</th>
		<th nowrap="nowrap">财务备注</th>
		<th nowrap="nowrap"><?php echo @HAS_CONFIRM; ?>
</th>
		</tr>
	<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['datas']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
	<tr id="tr_<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['orders_products_id']; ?>
" class="indexDdanyjXxBorb" style="background-color:<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['_tr_background_color']; ?>
">
		<td class="tab_line1"><a href="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['orders_links']; ?>
" target="_blank"><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['orders_id']; ?>
</a></td>
		<td class="tab_line1" nowrap="nowrap" style="line-height:15px; padding-top:5px; padding-bottom:5px;"><?php if ($this->_tpl_vars['datas'][$this->_sections['i']['index']]['job_19']): ?><span style="color:#F00"><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['job_19']; ?>
</span>, <?php endif; ?> <?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['orders_owners']; ?>
</td>
		<td class="tab_line1"><?php echo ((is_array($_tmp=$this->_tpl_vars['datas'][$this->_sections['i']['index']]['date_purchased'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
		<td class="tab_line1">
		<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['date_paid']; ?>

		</td>
		<td class="tab_line1">
		<?php echo ((is_array($_tmp=$this->_tpl_vars['datas'][$this->_sections['i']['index']]['products_departure_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d") : smarty_modifier_date_format($_tmp, "%Y-%m-%d")); ?>
</td>
		<!--<td class="tab_line1" nowrap="nowrap" style="line-height:15px;"><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['agency_name']; ?>
</td>-->
		<td class="tab_line1" nowrap="nowrap" style="line-height:15px; padding-top:5px; padding-bottom:5px;"><a href="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['products_links']; ?>
" target="_blank" title="[<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['products_model']; ?>
]<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['products_name']; ?>
"><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['provider_tour_code']; ?>
</a></td>
		<td class="tab_line1"><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['travelPeopleNumber']; ?>
</td>
		<td class="tab_line1"><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['paymentPaidStr']; ?>
</td>
		<td class="tab_line1"><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['customer_invoice_no']; ?>
</td>
		<td class="tab_line1" align="right" style="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['cost_color']; ?>
"><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['_customer_invoice_total']; ?>
</td>
		<td class="tab_line1" align="right" style="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['cost_color']; ?>
"><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['_final_price_cost']; ?>
</td>
		<td class="tab_line1" align="right" nowrap="nowrap">$<input name="" class="none_border" type="text" value="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['before_departure_cost']; ?>
" size="8" oldvalue="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['before_departure_cost']; ?>
" onfocus="this.className='border';" onblur="submit_cost_and_final_price_china_or_usa(this, <?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['orders_products_id']; ?>
, 'beforedeparturecost'); this.className='none_border';" style="ime-mode: disabled; " /></td>
		<td class="tab_line1" align="right" nowrap="nowrap">$<input name="" class="none_border" type="text" value="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['china_bookkeeper_final_price_cost']; ?>
" size="8" oldvalue="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['china_bookkeeper_final_price_cost']; ?>
" onfocus="this.className='border';" onblur="submit_cost_and_final_price_china_or_usa(this, <?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['orders_products_id']; ?>
, 'china'); this.className='none_border';" style="ime-mode: disabled; " /></td>
		<td class="tab_line1" align="right" nowrap="nowrap">$<input name="" class="none_border" type="text" value="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['usa_bookkeeper_final_price_cost']; ?>
" size="8" oldvalue="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['usa_bookkeeper_final_price_cost']; ?>
" onfocus="this.className='border';" onblur="submit_cost_and_final_price_china_or_usa(this, <?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['orders_products_id']; ?>
, 'usa'); this.className='none_border';" style="ime-mode: disabled; " /></td>
		<td class="tab_line1" ><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['_final_price']; ?>
</td>
		<td class="tab_line1" >
		<input name="" class="none_border" type="text" value="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['usa_bookkeeper_final_price']; ?>
" size="8" oldvalue="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['usa_bookkeeper_final_price']; ?>
" onfocus="this.className='border';" onblur="submit_cost_and_final_price_china_or_usa(this, <?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['orders_products_id']; ?>
, 'usafinalprice'); this.className='none_border';" style="ime-mode: disabled; " />
		</td>
		<td class="tab_line1" ><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['GrossProfit']; ?>
</td>
		<td class="tab_line1" ><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['orderStatus']; ?>
</td>
		<td class="tab_line1" >
		<form id="CommentForm_<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['orders_products_id']; ?>
" name="CommentForm_<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['orders_products_id']; ?>
" action="" method="post" enctype="multipart/form-data" onsubmit="update_admin_comment(this); return false;">
		<textarea name="admin_comment" cols="30" rows="2" wrap="virtual" class="border"><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['admin_comment']; ?>
</textarea><!--update_admin_comment(this.form);-->
		<input name="orders_products_id" type="hidden" value="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['orders_products_id']; ?>
" />
		<br />
		<input type="submit" value="提交备注" />
		<!--<input name="admin_comment_old" type="hidden" value="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['admin_comment']; ?>
" />
		[<a href="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['orders_links']; ?>
#OrderStatusHistoryList" target="_blank">查看订单备注</a>]-->
		</form>
		</td>
		<td id="confirm_td_<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['orders_products_id']; ?>
" class="tab_line1" style="text-align:center" title="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['MatchMsn']; ?>
" >
			<?php if ($this->_tpl_vars['datas'][$this->_sections['i']['index']]['admin_confirm'] != 'Y'): ?>
			<form id="ConfirmForm_<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['orders_products_id']; ?>
" name="ConfirmForm_<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['orders_products_id']; ?>
" action="" method="post" enctype="multipart/form-data" onsubmit="confirm_invoice_cost(this); return false;">
			<input name="orders_products_id" type="hidden" value="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['orders_products_id']; ?>
" />
			<input name="admin_confirm_final_price_cost" type="hidden" value="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['final_price_cost']; ?>
" />
			<input name="admin_confirm_customer_invoice_no" type="hidden" value="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['customer_invoice_no']; ?>
" />
			<input name="admin_confirm_customer_invoice_total" type="hidden" value="<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['customer_invoice_total']; ?>
" />
			<input title="如果发票金额与底价不符也能确认！" type="submit" name="button" <?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['disabled']; ?>
 value="<?php echo @CONFIRM_BUTTON; ?>
" />
			</form>
			<?php else: ?>
				<font color="#00CC00" >match</font>
			<?php endif; ?>
			</td>
		</tr>
	<?php endfor; endif; ?>
</table>
  
  
  <div style="margin-top:10px; clear:both;">
  	<!--分页-->
  	<table width="98%" border="0" cellspacing="0" cellpadding="0">
  		<tr>
  			<td align="right">
  			<div class="pageBot"><?php echo $this->_tpl_vars['split_right_content']; ?>
</div>
  			<div class="pageBot"><?php echo $this->_tpl_vars['split_left_content']; ?>
</div>
  				</td>
  			</tr>
  		</table>
  	
  	</div>

 </div>
</div>

</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "main_footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>