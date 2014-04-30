<?php
$error_string = '很抱歉！没有找到符合您查询条件的行程。';
if(tep_not_null($error_not_3_day_trour)){
	$error_string = $error_not_3_day_trour;
}elseif(tep_not_null($error_not_trour_pack)){
	$error_string = $error_not_trour_pack;
}
require(DIR_FS_LANGUAGES . $language . '/tour_lead_question.php');
?>
<div class="notFound">
    <div class="topTip"><?php echo db_to_html($error_string);?></div>
    <div class="suggest">
       <h3><?php echo db_to_html('走四方网建议您');?></h3>
       <p><?php echo db_to_html('1.请重新设定筛选条件。  <a href="'.$resetSearchOptionUrl.'">重置筛选条件</a><br>2.请检查关键字是否错误，请尝试输入正确的城市或景点名称。<br>3.请拨打7x24热线或点击右边的在线客户咨询。');?></p>
       <ul class="phone">
        <?php
		$contact_phone = tep_get_us_contact_phone();
		foreach($contact_phone as $key => $val){
		?>
		<li class="<?php echo $val['class']?>"><?php echo db_to_html($val['name'].$val['phone']);?></li>
		<?php
		}
		?>
        <li class="service"><?php echo STORE_OWNER_EMAIL_ADDRESS;?></li>
		
       </ul>
	   <p>4.<?php echo db_to_html('<a href="' . tep_href_link('tour_question.php') . '">提交疑问或留言</a>,走四方网客服会及时答复您。')?></p>
       <?php /* 
	   // by lwkai add 去掉找不到的留言框 2012-05-28
	   <p>4.<?php echo db_to_html('提交疑问或留言，走四方网客服会及时答复您。');?></p>
       <?php echo tep_draw_form('product_queston_write', tep_href_link('tour_question.php', 'action=process'), 'post', 'id="frm_product_queston_write"'); //onSubmit="return checkForm();" ?>
       <ul class="message">
          <li>
          <label><?php echo TEXT_YOUR_FNAME;?></label>
          <?php echo tep_draw_input_field('customers_name',db_to_html(ucfirst($customer_first_name)),'  class="text required" id="customers_name" title="'.TEXT_YOUR_FNAME_ERROR.'"'); ?>
          </li>
          <li>
          <label><?php echo TEXT_YOUR_EMAIL;?></label>
          <?php echo tep_draw_input_field('customers_email',tep_get_customers_email($customer_id),'class="text required validate-email" id="customers_email" title="'.TEXT_YOUR_EMAIL_ERROR.'"'); ?>
          </li>
          <li>
          <label><?php echo TEXT_YOUR_EMAIL_CONFIRM;?></label>
          <?php echo tep_draw_input_field('c_customers_email',tep_get_customers_email($customer_id),' class="text required validate-email-confirm-que" title="'.TEXT_YOUR_EMAIL_CONFIRM_ERROR.'" id="c_customers_email"'); ?>
          </li>
          <li>
          <label><?php echo TEXT_YOUR_QUESTION;?></label>
          <?php echo tep_draw_textarea_field('question', 'soft', '', '','',' class="textarea required " id="question" title="'.TEXT_YOUR_COMMENT_ERROR.'"'); ?>
          </li>
          <li><label>
          &nbsp;<input type="hidden" name="products_id" value="<?php echo $HTTP_GET_VARS['products_id'];?>" />
        <input type="hidden" name="cPath" value="<?php echo $cPath;?>" />
          </label><a href="javascript:;" class="btn btnGrey"><button type="submit"><?php echo db_to_html('提 交');?></button></a><span>(<?php echo db_to_html('请填写所有信息后再提交');?>)</span></li>
       </ul>
       <?php echo '</form>';?>
	   // 注释 留言框结束
	   */ ?>
	   
    </div>
</div>
<?php
//取得属於精品推荐的产品 
// by lwkai 去掉条件 AND ptc.categories_id="139" 2012-05-08
$rec_prod_sql = tep_db_query('SELECT pd.products_name,pd.products_id, p.products_model, p.products_price, p.products_tax_class_id FROM `products` p,  `products_description` pd,`products_to_categories` ptc WHERE p.products_id = ptc.products_id AND p.products_id = pd.products_id  AND p.products_status="1" ORDER BY rand() limit 10;');//AND ptc.categories_id="139"
?>
<div class="suggestRoute">
    <div class="title titleSmall">
        <b></b><span></span>
        <h3><?php echo db_to_html('您可能喜欢')?></h3>
    </div>
    <ul>
    <?php
		while($rec_prod_rows = tep_db_fetch_array($rec_prod_sql)){
			$rec_prod_rows['products_name1']=strstr($rec_prod_rows['products_name'], '**');
			if($rec_prod_rows['products_name1']!='' && $rec_prod_rows['products_name1']!==false)$rec_prod_rows['products_name']=str_replace($rec_prod_rows['products_name1'],'',$rec_prod_rows['products_name']);
	?>
        <li>
            <div class="left">
                <a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $rec_prod_rows['products_id']);?>" title="<?php echo db_to_html(tep_db_output($rec_prod_rows['products_name'].$rec_prod_rows['products_name1']));?>"><?php echo db_to_html(tep_db_output($rec_prod_rows['products_name']));?> [<?php echo $rec_prod_rows['products_model'];?>]</a>
                <p><?php echo db_to_html(tep_db_output($rec_prod_rows['products_name1']));?></p>
            </div>
            <b><?php 
			$tax_rate_val_get = tep_get_tax_rate($rec_prod_rows['products_tax_class_id']);
			if ($new_price = tep_get_products_special_price($rec_prod_rows['products_id'])) {
				$products_price = $currencies->display_price($new_price, $tax_rate_val_get);
			} else {
				$products_price = $currencies->display_price($rec_prod_rows['products_price'], $tax_rate_val_get);
			}
			echo $products_price;
			?></b>
        </li>
    <?php
		}
	?>
    </ul>
</div>