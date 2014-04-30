<?php if(!$is_my_account){ //在我的走四方里面不显示这个affiliate导航栏 howard fixed on 2011-08-16?>
<tr>
        <td>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
   <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
       </tr>
	     <tr>
		 <td class="main">
		 <?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_SUMMARY, '', 'SSL') . '">' . BOX_AFFILIATE_SUMMARY . '</a>&nbsp;|&nbsp;';?>
<?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_ACCOUNT, '', 'SSL'). '">' . BOX_AFFILIATE_ACCOUNT . '</a>&nbsp;|&nbsp;';?>
<?php echo '<a href="' . tep_href_link(FILENAME_REFER_A_FRIEND, '', 'SSL'). '">' . BOX_AFFILIATE_REFER_FRIENDS . '</a>&nbsp;|&nbsp;';?>
<?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_BANNERS, '', 'NONSSL'). '">' . BOX_AFFILIATE_BANNERS . '</a>&nbsp;|&nbsp;';?>
<?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_SALES, '', 'SSL'). '">' . BOX_AFFILIATE_SALES . '</a>&nbsp;|&nbsp;';?>
<?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_PAYMENT, '', 'SSL'). '">' . BOX_AFFILIATE_PAYMENT . '</a>&nbsp;|&nbsp;';?>
<?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_CONTACT, '', 'SSL'). '">' . BOX_AFFILIATE_CONTACT . '</a>&nbsp;|&nbsp;';?>
<?php echo '<a href="' . tep_href_link(FILENAME_AFFILIATE_FAQ, '', 'NONSSL'). '">' . BOX_AFFILIATE_FAQ . '</a>&nbsp;|';?>
<?php echo '<a href="' . tep_href_link('affiliate_terms.php'). '">' . db_to_html('网站联盟协议') . '</a>';?>
		</td>
		</tr>   
        <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '3'); ?></td>
   </tr>  
	<tr>
        <td><hr style="color:#108BCE;" size="1" /></td>
   </tr>    
</table>
</td>
   </tr>     
     <tr>
	<td height="15"></td>
  </tr>
<?php } ?>