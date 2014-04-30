<?php echo tep_get_design_body_header(TEXT_SITE_MAP); ?>
<table border="0" align='center' width="100%" cellspacing="1" cellpadding="0">
			<tr>
				<td class='tableHeading'><?php echo TEXT_SITE_MAP;?></td>
			</tr>
			<tr>
				<td class='tableHeading'><hr size='1' /></td>
			</tr>
			<tr>
				<td class='smallText'><a href="<?=HTTP_SERVER.DIR_WS_HTTP_CATALOG;?>"><?php echo db_to_html('首页'); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;

				<?php 					echo(
					'<a href="about_us.php" class="foot">' . BOX_INFORMATION_ABOUT_US. '</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="privacy-policy.php" class="foot">'.db_to_html("隐私条例").'</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="payment-faq.php" class="foot">'.db_to_html("付款常见问题").'</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="copy-right.php" class="foot">'.db_to_html("版权").'</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="customer-agreement.php" class="foot">' . db_to_html("客户协议") . '</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="links.php" class="foot"> '.db_to_html("友情链接").'</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="' . tep_href_link('cancellation-and-refund-policy.php') . '" class="foot">'.db_to_html("取消和退款条例").'</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="site-map.php" class="foot">' . BOX_INFORMATION_SITE_MAP . '</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="' . tep_href_link(FILENAME_CONTACT_US) . '" class="foot">' . BOX_INFORMATION_CONTACT . '</a>');
				?>
				</td>
			</tr>
			<tr>
				<td class='tableHeading'>&nbsp;</td>
			</tr>
			<tr>
				<td class='tableHeading'><?php echo TEXT_TORU_CATEGORIES;?></td>
			</tr>
			<tr>
				<td class='tableHeading'><hr size='1' /></td>
			</tr>
</table>

	<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
 <td class='main'><?php require DIR_FS_CLASSES . 'category_tree.php'; $osC_CategoryTree = new osC_CategoryTree; echo db_to_html($osC_CategoryTree->buildTree(''));?></td>
  </tr>
   </table>

<?php echo tep_get_design_body_footer();?>
