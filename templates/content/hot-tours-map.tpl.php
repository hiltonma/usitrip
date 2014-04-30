<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
<div class="leftside_11"> 
	  
      <div class="left_bg2" style="width:647px;"><h1><?php echo db_to_html('热门景点地图')?></h1>
        <img src="image/map_big_usa.gif" border="0" usemap="#Map" />
<map name="Map" id="Map"><area shape="rect" coords="40,29,83,51" href="<?= tep_href_link('advanced_search_result.php', 'departure_city_id=279'); ?>" /><area shape="rect" coords="140,82,219,104" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=35');?>" /><area shape="rect" coords="116,131,159,149" href="<?= tep_href_link('advanced_search_result.php', 'departure_city_id=10'); ?>" /><area shape="rect" coords="13,134,53,151" href="<?= tep_href_link('advanced_search_result.php', 'departure_city_id=2'); ?>" /><area shape="rect" coords="52,159,118,178" href="<?= tep_href_link('advanced_search_result.php', 'departure_city_id=3'); ?>" /><area shape="rect" coords="29,199,68,222" href="<?= tep_href_link('advanced_search_result.php', 'departure_city_id=1'); ?>" /><area shape="rect" coords="94,185,183,203" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=31');?>" /><area shape="rect" coords="177,136,220,154" href="<?= tep_href_link('advanced_search_result.php', 'departure_city_id=37'); ?>" /><area shape="rect" coords="427,95,513,114" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=57');?>" /><area shape="rect" coords="476,119,501,132" href="<?= tep_href_link('advanced_search_result.php', 'departure_city_id=66'); ?>" /><area shape="rect" coords="468,137,490,146" href="<?= tep_href_link('advanced_search_result.php', 'departure_city_id=67'); ?>" /><area shape="rect" coords="476,119,501,132" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=56');?>" /><area shape="rect" coords="451,158,489,170" href="<?= tep_href_link('advanced_search_result.php', 'departure_city_id=65'); ?>" /><area shape="rect" coords="388,362,428,378" href="<?=tep_href_link(FILENAME_DEFAULT, 'cPath=33');?>" /></map>

<p><?php echo db_to_html('点击地名，到达相应旅游地点')?></p>

<?php
/*取消顶和收藏 
<div class="foot_tag_link"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" class="chengse_font"><table width="34%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="40%" height="28" align="center"><table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#FFDCAB">
          <tr>
            <td height="25" align="center" bgcolor="#FFDCAB"><a href="#" ><img src="image/ding.gif" width="21" height="20" style="margin:0px; border:0px;" /></a></td>
            <td width="65%" align="center" bgcolor="#FFFFFF"><span class="ding_shu">500</span></td>
          </tr>
          
        </table></td>
        <td width="6%">&nbsp;</td>
        <td width="26%" align="center" valign="middle"><div class="fenxiang"><a href="javascript:showDiv()" class="fenxiang_zi">分享</a></div></td>
        <td width="6%">&nbsp;</td>
        <td width="27%" align="center" valign="middle"><div  class="fenxiang"><a href="#" class="fenxiang_zi">收藏</a></div></td>
      </tr>
      
    </table>
      </td>
    </tr>
</table>
</div>
*/
?>
</div>
   </div>	
	</td>
    <td valign="top">
<?php require(DIR_FS_TEMPLATES . TEMPLATE_NAME .'/column_right.php'); ?>	</td>
  </tr>
</table>