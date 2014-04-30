	<?php if(basename($PHP_SELF)=='tours-faq.php'){//旅美常识?>  
	<div class="chanpin_xg5"><table width="97%" border="0" cellspacing="0" cellpadding="0" style="border-bottom:2px #FFAD39 solid; margin-left:4px;">
		  <tr>
			<td width="2%">&nbsp;</td>
			<td width="6%"><img src="image/changshi_liebiao.gif" /></td>
			<td width="1%">&nbsp;</td>
			<td width="94%"><h3><?php echo db_to_html('走四方FAQ分类')?></h3></td>
		  </tr>
		</table>
		
		<?php
		$faq_class14 = 'text cu'; 
		$faq_class15 = 'text'; 
		$faq_class16 = 'text';
		switch((int)$_GET['information_id']){
			case '14': 	$faq_class14 = 'text cu'; 
						$faq_class15 = 'text'; 
						$faq_class16 = 'text';
						break;
			case '15': 	$faq_class14 = 'text'; 
						$faq_class15 = 'text cu'; 
						$faq_class16 = 'text';
						break;
			case '16': 	$faq_class14 = 'text'; 
						$faq_class15 = 'text'; 
						$faq_class16 = 'text cu';
						break;
		} 
		?>   
		<ul>
		<li><a href="<?php echo tep_href_link('tours-faq.php','information_id=14')?>" class="<?php echo $faq_class14 ?>"><?php echo db_to_html('付费咨询')?></a> </li>
		<li><a href="<?php echo tep_href_link('tours-faq.php','information_id=15')?>" class="<?php echo $faq_class15 ?>"><?php echo db_to_html('常见问题')?></a>  </li>
		<li><a href="<?php echo tep_href_link('tours-faq.php','information_id=16')?>" class="<?php echo $faq_class16 ?>"><?php echo db_to_html('旅美须知')?></a>  </li>
		</ul>
    </div>
	<?php }//旅美常识end?>
