<div class="product_we">
<!--积分回馈小广告、客服聊天工具-->
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td><div class="product_1_0_tab">
		<?php
		//live chat
		$tool_chat = '53kf';
		if($tool_chat =='livechat'){
			$msn_code = '<a target="_blank" href=" http://www.chat4support.com.cn:8000/main.asp?sid=2675&sTag=ZENGHUA&style=0"><img src=" http://www.chat4support.com.cn:80/weboperator/BtnImage.aspx?sid=2675&sTag=ZENGHUA&style=0" border="0"></a>';
		}elseif($tool_chat =='53kf'){
			$msn_code = '<a target="_blank" href="http://chat.53kf.com/company.php?arg=usitrip&style=1" title="点击这里与走四方网客服沟通"><img src="http://chat.53kf.com/kfimg.php?arg=usitrip&style=1" /></a>';
		}
		echo db_to_html($msn_code);
		?>
		</div></td>
		<td width="10">&nbsp;</td>
	<td><div class="product_1_1_tab"><table width="100%"  border="0" cellpadding="0" cellspacing="0">
					 <tr>
					  <td width="35%" align="center" valign="middle"><img src="image/point_icon.gif" alt="<?php echo db_to_html('积分');?>" width="42" height="43" /></td>
					  <td width="65%"><p class="p_50ff_bai"><a href="<?php echo tep_href_link('points.php')?>" class="tell_f_a"><?php echo db_to_html('参与走四方积分回馈!')?></a><br />
	<?php echo db_to_html('赢取美元现金！通过发表评论、回答问题、上传照片等方式获取积分');?>
	</p></td>
					   </tr></table>
					   </div></td>
	  </tr>
	</table>

</div>