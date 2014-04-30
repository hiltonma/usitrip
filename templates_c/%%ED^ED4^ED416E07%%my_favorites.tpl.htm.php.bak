<?php /* Smarty version 2.6.25-dev, created on 2013-12-27 03:56:00
         compiled from my_favorites.tpl.htm */ ?>
<table class="ui-collect-tb">
        <thead>
            <tr>
                <th width="70%;"><?php echo $this->_tpl_vars['shows']['title']['name']; ?>
</th>
                <th><?php echo $this->_tpl_vars['shows']['title']['price']; ?>
</th>
                <th><?php echo $this->_tpl_vars['shows']['title']['action']; ?>
</th>
            </tr>
        </thead>
        <tbody>
        <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['FavoritesRows']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
            <tr id="FavObj_<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['id']; ?>
">
                <td>
                    <div class="ui-collect-short-pic">
                        <a href="<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['href']; ?>
"><img src="/images/<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['p_image']; ?>
" width="<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['img_w']; ?>
" height="<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['img_h']; ?>
" /></a>
                    </div>
                    <div class="ui-line-info" id="proListCon_<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['p_id']; ?>
">
                        <h2><a href="<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['href']; ?>
"><?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['p_name']; ?>
</a></h2>
                        <p><span style="display:none">收藏人气：18050</span>&nbsp;&nbsp;<span><?php echo $this->_tpl_vars['shows']['reviews']; ?>
<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['satisfaction']; ?>
(<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['reviews']; ?>
<?php echo $this->_tpl_vars['shows']['comment']; ?>
)</span></p>
                    </div>
                </td>
                <td class="ui-price">
                    <em class="ui-start-price"><?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['p_price']; ?>
</em>
                </td>
                <td class="ui-handle">
                <?php if ($this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['p_is_transfer'] == '1' || $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['p_is_cruises'] == '1'): ?>
                	<p></p>
                <?php else: ?>
                    <p><a id="add_cart_a_link_<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['p_id']; ?>
" onclick="jQueryAddCart('<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['p_id']; ?>
','<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['have_room']; ?>
', '<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['guest_num']; ?>
');" class="ui-buy" href="javascript:void(0);"><?php echo $this->_tpl_vars['shows']['add_to_cart']; ?>
</a></p>
                <?php endif; ?>
                    <p><a href="javascript:void(0)" class="ui-delete" onclick="RemoveFavorites(<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['id']; ?>
)"><?php echo $this->_tpl_vars['shows']['del']; ?>
</a></p>
                </td>
            </tr>
        <?php endfor; endif; ?>     
            <!--<tr class="even">
                <td>
                    <div class="ui-collect-short-pic">
                        <a href="#"><img src="9565.jpg" width="95" height="65" /></a>
                    </div>
                    <div class="ui-line-info">
                        <p><a href="#">独家黄石公园-大峡谷-拉斯-旧金山-布莱斯峡谷10日豪华游</a></p>
                        <p><span>收藏人气：18050</span>&nbsp;&nbsp;<span>满意度：98%(58条评论)</span></p>
                    </div>
                </td>
                <td class="ui-price">
                    <em class="ui-start-price">$380起</em>
                </td>
                <td class="ui-handle">
                    <p><a href="#" class="ui-buy">立即购买</a></p>
                    <p><a href="#" class="ui-delete">删 除</a></p>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="ui-collect-short-pic">
                        <a href="#"><img src="9565.jpg" width="95" height="65" /></a>
                    </div>
                    <div class="ui-line-info">
                        <p><a href="#">独家黄石公园-大峡谷-拉斯-旧金山-布莱斯峡谷10日豪华游</a></p>
                        <p><span>收藏人气：18050</span>&nbsp;&nbsp;<span>满意度：98%(58条评论)</span></p>
                    </div>
                </td>
                <td class="ui-price">
                    <em class="ui-start-price">$380起</em>
                </td>
                <td class="ui-handle">
                    <p><a href="#" class="ui-buy">立即购买</a></p>
                    <p><a href="#" class="ui-delete">删 除</a></p>
                </td>
            </tr>
            <tr class="even">
                <td>
                    <div class="ui-collect-short-pic">
                        <a href="#"><img src="9565.jpg" width="95" height="65" /></a>
                    </div>
                    <div class="ui-line-info">
                        <p><a href="#">独家黄石公园-大峡谷-拉斯-旧金山-布莱斯峡谷10日豪华游</a></p>
                        <p><span>收藏人气：18050</span>&nbsp;&nbsp;<span>满意度：98%(58条评论)</span></p>
                    </div>
                </td>
                <td class="ui-price">
                    <em class="ui-start-price">$380起</em>
                </td>
                <td class="ui-handle">
                    <p><a href="#" class="ui-buy">立即购买</a></p>
                    <p><a href="#" class="ui-delete">删 除</a></p>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="ui-collect-short-pic">
                        <a href="#"><img src="9565.jpg" width="95" height="65" /></a>
                    </div>
                    <div class="ui-line-info">
                        <p><a href="#">独家黄石公园-大峡谷-拉斯-旧金山-布莱斯峡谷10日豪华游</a></p>
                        <p><span>收藏人气：18050</span>&nbsp;&nbsp;<span>满意度：98%(58条评论)</span></p>
                    </div>
                </td>
                <td class="ui-price">
                    <em class="ui-start-price">$380起</em>
                </td>
                <td class="ui-handle">
                    <p><a href="#" class="ui-buy">立即购买</a></p>
                    <p><a href="#" class="ui-delete">删 除</a></p>
                </td>
            </tr>
            <tr class="even">
                <td>
                    <div class="ui-collect-short-pic">
                        <a href="#"><img src="9565.jpg" width="95" height="65" /></a>
                    </div>
                    <div class="ui-line-info">
                        <p><a href="#">独家黄石公园-大峡谷-拉斯-旧金山-布莱斯峡谷10日豪华游</a></p>
                        <p><span>收藏人气：18050</span>&nbsp;&nbsp;<span>满意度：98%(58条评论)</span></p>
                    </div>
                </td>
                <td class="ui-price">
                    <em class="ui-start-price">$380起</em>
                </td>
                <td class="ui-handle">
                    <p><a href="#" class="ui-buy">立即购买</a></p>
                    <p><a href="#" class="ui-delete">删 除</a></p>
                </td>
            </tr>-->
        </tbody>
    </table>
    

<!--<div class="routeFav">
  <div class="title titleSmall">
    <b></b><span></span>
    <h3><?php echo $this->_tpl_vars['shows']['heading']; ?>
</h3>
  </div>
  <div class="routeFavCon">
    <div class="routeFavTop">
      <div class="row1"><?php echo $this->_tpl_vars['shows']['title']['name']; ?>
</div>
      <div class="row2"><?php echo $this->_tpl_vars['shows']['title']['price']; ?>
</div>
      <div class="row3"><?php echo $this->_tpl_vars['shows']['title']['action']; ?>
</div>
    </div>
    <table cellpadding="0" cellspacing="0" class="routeFavList">
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['FavoritesRows']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
      <tr id="FavObj_<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['id']; ?>
">
        <td id="proListCon_<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['p_id']; ?>
" class="left">
          <h2><a href="<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['href']; ?>
" target="_blank"><?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['p_name']; ?>
</a></h2>
        </td>
        <td><?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['p_price']; ?>
</td>
        <td class="right" style="display:table-cell; vertical-align:middle; ">
          <?php if ($this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['p_is_transfer'] == '1' || $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['p_is_cruises'] == '1'): ?>
          <div style="width:90px;float:left">&nbsp;</div>
          <?php else: ?>
          <a id="add_cart_a_link_<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['p_id']; ?>
" onclick="jQueryAddCart('<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['p_id']; ?>
','<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['have_room']; ?>
', '<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['guest_num']; ?>
');" class="btn btnOrange" href="javascript:;"><button type="button"><?php echo $this->_tpl_vars['shows']['add_to_cart']; ?>
</button></a>
          <?php endif; ?>
          <a href="javascript:;" class="btn btnGrey" onclick="RemoveFavorites(<?php echo $this->_tpl_vars['FavoritesRows'][$this->_sections['i']['index']]['id']; ?>
)"><button type="button"><?php echo $this->_tpl_vars['shows']['del']; ?>
</button></a>
        </td>
      </tr>
<?php endfor; endif; ?> 
<tr>
<td colspan="3" style="text-align:right;"><?php echo $this->_tpl_vars['shows']['pages_num']; ?>
&nbsp;&nbsp;<?php echo $this->_tpl_vars['shows']['page_links']; ?>
</td>
</tr>
    </table>
  </div>
</div>-->


<div class="popup" id="addToCart">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" class="popupTable">
    <tr>
      <td class="topLeft"></td><td class="side"></td><td class="topRight"></td></tr><tr><td class="side"></td>
        <td class="con">
          <div class="popupCon addSuccess" id="addToCartPanel" style="width:400px; ">
          	<div class="successTip" style="background-image:none;">
				<div class="img favorite_dialog"><img src="image/success.jpg"></div>
				<div class="words">
                <p><?php echo $this->_tpl_vars['shows']['cart_0']; ?>
<a href="" id="Cart_Pname"></a><?php echo $this->_tpl_vars['shows']['cart_1']; ?>
</p>
                <p><?php echo $this->_tpl_vars['shows']['cart_2']; ?>
<font id='Cart_Sum'></font><?php echo $this->_tpl_vars['shows']['cart_3']; ?>
<span><b id='CartTotal'></b></span></p>
				</div>
            </div>
            <div class="errorTip"></div>
            <div class="popupBtn"><a href="<?php echo $this->_tpl_vars['shows']['cart_4']; ?>
" class="btn btnOrange" target="mycart" style="color:#FFFFFF;"><?php echo $this->_tpl_vars['shows']['cart_5']; ?>
</a>
			<a href="javascript:void(0);" class="btn btnGrey" onclick="closePopup('addToCart');"><button type="button"><?php echo $this->_tpl_vars['shows']['cart_6']; ?>
</button></a>
			</div>
          </div>
      </td><td class="side"></td></tr><tr><td class="botLeft"></td><td class="side"></td><td class="botRight"></td></tr>
  </table>
</div>