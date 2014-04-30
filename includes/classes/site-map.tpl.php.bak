<BR><BR><table border="0" align='center' width="100%" cellspacing="1" cellpadding="0">
			<TR>
				<TD class='tableHeading'><?php echo TEXT_SITE_MAP;?></TD>
			</TR>
			<TR>
				<TD class='tableHeading'><hr size='1'></TD>
			</TR>
			<TR>
				<TD class='smallText'><A HREF="<?=HTTP_SERVER.DIR_WS_HTTP_CATALOG;?>">首页&nbsp;&nbsp;&nbsp;&nbsp;</A>
				<?php 					echo(
					'<a href="about_us.php" class="foot">' . BOX_INFORMATION_ABOUT_US. '</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="privacy-policy.php" class="foot">隐私条例</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="payment-faq.php" class="foot">付款常见问题</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="copy-right.php" class="foot">版权</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="customer-agreement.php" class="foot">' . '客户协议' . '</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="links.php" class="foot"> 友情链接</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="' . tep_href_link('cancellation-and-refund-policy.php') . '" class="foot">' . '取消和退款条例' . '</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="site-map.php" class="foot">' . BOX_INFORMATION_SITE_MAP . '</a>&nbsp;&nbsp;&nbsp;&nbsp;' .
					'<a href="' . tep_href_link(FILENAME_CONTACT_US) . '" class="foot">' . BOX_INFORMATION_CONTACT . '</a>');
				?>
				</TD>
			</TR>
			<TR>
				<TD class='tableHeading'>&nbsp;</TD>
			</TR>
			<TR>
				<TD class='tableHeading'><?php echo TEXT_TORU_CATEGORIES;?></TD>
			</TR>
			<TR>
				<TD class='tableHeading'><hr size='1'></TD>
			</TR>
</table>

	<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
 <td class='main'><?require DIR_FS_CLASSES . 'category_tree.php'; $osC_CategoryTree = new osC_CategoryTree; echo $osC_CategoryTree->buildTree('');?></td>
  </tr>
   </table>

