<?php /* Smarty version 2.6.22, created on 2013-12-27 19:42:09
         compiled from album.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'album.html', 173, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "head.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div id="body">
	<div class="crumbs"></div>
	<h1><a href="javascript:void(0)" class="J-publish publish"></a><a href="<?php echo $this->_tpl_vars['nav_index']; ?>
" class="myhome myfind"></a><a href="<?php echo $this->_tpl_vars['nav_index']; ?>
" class="a_all"><span class="title"><i></i>全部游记</span></a></h1>
	<p class="place uifix"><?php unset($this->_sections['key']);
$this->_sections['key']['name'] = 'key';
$this->_sections['key']['loop'] = is_array($_loop=$this->_tpl_vars['list']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['key']['show'] = true;
$this->_sections['key']['max'] = $this->_sections['key']['loop'];
$this->_sections['key']['step'] = 1;
$this->_sections['key']['start'] = $this->_sections['key']['step'] > 0 ? 0 : $this->_sections['key']['loop']-1;
if ($this->_sections['key']['show']) {
    $this->_sections['key']['total'] = $this->_sections['key']['loop'];
    if ($this->_sections['key']['total'] == 0)
        $this->_sections['key']['show'] = false;
} else
    $this->_sections['key']['total'] = 0;
if ($this->_sections['key']['show']):

            for ($this->_sections['key']['index'] = $this->_sections['key']['start'], $this->_sections['key']['iteration'] = 1;
                 $this->_sections['key']['iteration'] <= $this->_sections['key']['total'];
                 $this->_sections['key']['index'] += $this->_sections['key']['step'], $this->_sections['key']['iteration']++):
$this->_sections['key']['rownum'] = $this->_sections['key']['iteration'];
$this->_sections['key']['index_prev'] = $this->_sections['key']['index'] - $this->_sections['key']['step'];
$this->_sections['key']['index_next'] = $this->_sections['key']['index'] + $this->_sections['key']['step'];
$this->_sections['key']['first']      = ($this->_sections['key']['iteration'] == 1);
$this->_sections['key']['last']       = ($this->_sections['key']['iteration'] == $this->_sections['key']['total']);
?>
	<a href="<?php echo $this->_tpl_vars['list'][$this->_sections['key']['index']]['href']; ?>
"><?php echo $this->_tpl_vars['list'][$this->_sections['key']['index']]['name']; ?>
</a>
	<?php endfor; endif; ?></p>
		<input type="hidden" id="metaDesc" value=""/>
	<input type="hidden" value="<?php echo $this->_tpl_vars['customer_id']; ?>
" id="visitorId"/>
	<input type="hidden" value="<?php echo $this->_tpl_vars['customer_id']; ?>
" id="userId"/>
	<input type="hidden" value="<?php echo $this->_tpl_vars['piclist'][0]['image_id']; ?>
" id="firstPicId"/>
	<input type="hidden" value="<?php echo $this->_tpl_vars['albumid']; ?>
" id="albumIdInfo"/>	<input type="hidden" value="<?php echo $this->_tpl_vars['current_day']; ?>
" id="albumCurrentDayTime"/>
	<input type="hidden" value="<?php echo $this->_tpl_vars['piclist'][0]['time_taken']; ?>
" oid='2013-02-26' id="firstDay"/>
	<input type="hidden" value="<?php echo $this->_tpl_vars['albumid']; ?>
" id="albumId" />
    <input type="hidden" value="<?php echo $this->_tpl_vars['productid']; ?>
" id="productId" />
	<input type="hidden" value="1" id="opType" />
	<input type="hidden" value="<?php if ($this->_tpl_vars['showmap'] == true): ?>true<?php else: ?>false<?php endif; ?>" id="map"  />
	<input type="hidden" value="<?php echo $this->_tpl_vars['is_like']; ?>
" id="albumIsPraised" /> 	<input type="hidden" value="<?php echo $this->_tpl_vars['travel_notes_title']; ?>
" id="albumName" />
		<input type="hidden" value="11110" id="newhandler" />
	<div class="container">
		<div class="travels_title pt20 fn-pr"> 
			<!--标题编辑--> 
			<?php if ($this->_tpl_vars['is_self'] == true): ?>
			<span class="travels_title_upload upload fn-pa png24"></span> 
			<span class="travels_title_delete delete fn-pa png24"></span>
			<?php endif; ?>
			<div class="travels_title_na fn-pr z-index100 fn-clearfix">
				<h1 class="J-album-name font-yahei darkgray333 ml30 mr30 fn-fl"><?php echo $this->_tpl_vars['travel_notes_title']; ?>
<?php if ($this->_tpl_vars['is_self'] == true): ?><span class="J-edit travels_title_edit edit png24"></span><?php endif; ?></h1>
				<span id="lsAlbumHas" class="textarea_error_album darkgray666 fn-pa fn-none">此游记名已有，请重新命名</span> 
				<span id="lsAlbumNameHas" class="textarea_error_album darkgray666 fn-pa fn-none">游记名不能为空</span> 
			</div>
			<div class="font-tahoma f12 darkgray666 ml30 mt5 fn-clearfix z-index99">
				<div class="userName mr10 fn-fl textarea_lineH">
					<em class="darkgray999">by</em> 
					<a href="<?php echo $this->_tpl_vars['my_home']; ?>
"><?php echo $this->_tpl_vars['album_username']; ?>
</a>
				</div>
				<div class="f12 fn-fl textarea_lineH mr10">共<?php echo $this->_tpl_vars['image_number']; ?>
张</div>
				<div class="album_textarea_is fn-pr fn-fl">
					<div id="maddressName" class="fn-clearfix"><span class="textarea_lineH fn-fl">目的地：</span><span class="J-destination textarea_lineH fn-fl"><?php echo $this->_tpl_vars['to_address']; ?>
</span><span class="textarea_lineH fn-fl">&nbsp;&nbsp;旅游线路：</span><span class="textarea_lineH line_H fn-fl"><a href="<?php echo $this->_tpl_vars['to_line_href']; ?>
" target="_blank"><?php echo $this->_tpl_vars['to_line_name']; ?>
</a></span></div>
					<span id="albumDescError" class="textarea_error_maddressName darkgray666 fn-pa fn-none"> 请选择专辑关联的目的地</span> </div>
			</div>
			<!--总 浏览数 赞数 -->
						
			<!--取消/确定-->
			<div class="J-input travels_title_buttom fn-pa fn-none">
				<input class="J-cancel cancel mr5 png24" name="" type="button" value="">
				<input id="albumSave" class="J-confirm confirm png24" name="" type="button" value="">
			</div>
			<!--取消/确定 end -->
			
			<div class="travels_line png24"></div>
		</div>
		<!--主体-->
		<div class="travels_main fn-clearfix fn-pr">
			<div id="shortcut" class="travels_left mt15 fn-fl ml30">
				<div class="travels_days mb10 f16 fb white png24">共<?php echo $this->_tpl_vars['day_count']; ?>
天旅程</div>
				<span class="shorcut_prev png24 fn-none">上一页</span> 
				
				<!--随页面滚动的天数list-->
				<div class="shorcut-nav-main fn-pr">
					<div class="shortcut-nav fn-pa">
						<ul>
							<input type="hidden" id="dayIds"  value="1=com.qunar.wireless.ugc.module.web.DayPoi@6ca9616e"/>
							<input type="hidden" id="dayId"  value="<?php echo $this->_tpl_vars['nextid']; ?>
"/>
							<?php unset($this->_sections['key']);
$this->_sections['key']['name'] = 'key';
$this->_sections['key']['loop'] = is_array($_loop=$this->_tpl_vars['daylist']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['key']['show'] = true;
$this->_sections['key']['max'] = $this->_sections['key']['loop'];
$this->_sections['key']['step'] = 1;
$this->_sections['key']['start'] = $this->_sections['key']['step'] > 0 ? 0 : $this->_sections['key']['loop']-1;
if ($this->_sections['key']['show']) {
    $this->_sections['key']['total'] = $this->_sections['key']['loop'];
    if ($this->_sections['key']['total'] == 0)
        $this->_sections['key']['show'] = false;
} else
    $this->_sections['key']['total'] = 0;
if ($this->_sections['key']['show']):

            for ($this->_sections['key']['index'] = $this->_sections['key']['start'], $this->_sections['key']['iteration'] = 1;
                 $this->_sections['key']['iteration'] <= $this->_sections['key']['total'];
                 $this->_sections['key']['index'] += $this->_sections['key']['step'], $this->_sections['key']['iteration']++):
$this->_sections['key']['rownum'] = $this->_sections['key']['iteration'];
$this->_sections['key']['index_prev'] = $this->_sections['key']['index'] - $this->_sections['key']['step'];
$this->_sections['key']['index_next'] = $this->_sections['key']['index'] + $this->_sections['key']['step'];
$this->_sections['key']['first']      = ($this->_sections['key']['iteration'] == 1);
$this->_sections['key']['last']       = ($this->_sections['key']['iteration'] == $this->_sections['key']['total']);
?>
							<li><a class="<?php if ($this->_tpl_vars['daylist'][$this->_sections['key']['index']]['current'] == 'true'): ?>line_red<?php else: ?>line_gray<?php endif; ?>" href="<?php echo $this->_tpl_vars['daylist'][$this->_sections['key']['index']]['href']; ?>
" title ="<?php echo $this->_tpl_vars['daylist'][$this->_sections['key']['index']]['to_address']; ?>
"><?php echo $this->_tpl_vars['daylist'][$this->_sections['key']['index']]['day']; ?>
</a></li>
							<?php endfor; endif; ?>
						</ul>
					</div>
				</div>
				<span class="shorcut_next png24 fn-none">下一页</span> </div>
			<!--右侧主体内容-->
			<div class="travels_right fn-fr mr30 mt15 fn-pr"> 
				<!--游记模式/幻灯模式-->
				
				<div class="travels_mode fn-pa png24"><span class="travels_mode_album fn-fl"><a href="javascript:void(0);"></a></span> <span class="travels_mode_pic fn-fl"><a href="javascript:void(0);"></a></span> 
					<!-- 不是国外的节点，并且地图中每个节点都有坐标 --> 
					
					<span class="travels_mode_map fn-fl" style="display:<?php if ($this->_tpl_vars['showmap'] == true): ?>block<?php else: ?>none<?php endif; ?>" ><a href="javascript:void(0);"></a></span> </div>
				<div class="travels_right_day fn-pa png24"></div>
				<!--第几天标题-->
				<div class="travels_right_day_t fn-pa font-yahei darkgray666"><?php echo $this->_tpl_vars['current_day_str']; ?>
 : <?php echo $this->_tpl_vars['current_day']; ?>
</div>
				<div style="height:37px;border-bottom:1px solid #dcdcdc"></div>
				<?php if ($this->_tpl_vars['is_self'] == true): ?>
				<!--第一条 插入文字/插入图片 不参与循环-->
				<div class="J-hidden">
					<div class="travels_op_add fn-none fn-tc">
						<div class="travels_op fn-bc"> 
							<!--   <input type="text" id="" /> --> 
							<span class="J-opText travels_op_text fn-fl png24"></span> <span class="J-opPic travels_op_pic fn-fr png24"></span>
							<input type="hidden" value="1361846662342" name="before"/>
						</div>
					</div>
				</div>
				<!-- 插入文字/插入图片 end -->
				<?php endif; ?>
				 
				<?php unset($this->_sections['key']);
$this->_sections['key']['name'] = 'key';
$this->_sections['key']['loop'] = is_array($_loop=$this->_tpl_vars['beforelist']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['key']['show'] = true;
$this->_sections['key']['max'] = $this->_sections['key']['loop'];
$this->_sections['key']['step'] = 1;
$this->_sections['key']['start'] = $this->_sections['key']['step'] > 0 ? 0 : $this->_sections['key']['loop']-1;
if ($this->_sections['key']['show']) {
    $this->_sections['key']['total'] = $this->_sections['key']['loop'];
    if ($this->_sections['key']['total'] == 0)
        $this->_sections['key']['show'] = false;
} else
    $this->_sections['key']['total'] = 0;
if ($this->_sections['key']['show']):

            for ($this->_sections['key']['index'] = $this->_sections['key']['start'], $this->_sections['key']['iteration'] = 1;
                 $this->_sections['key']['iteration'] <= $this->_sections['key']['total'];
                 $this->_sections['key']['index'] += $this->_sections['key']['step'], $this->_sections['key']['iteration']++):
$this->_sections['key']['rownum'] = $this->_sections['key']['iteration'];
$this->_sections['key']['index_prev'] = $this->_sections['key']['index'] - $this->_sections['key']['step'];
$this->_sections['key']['index_next'] = $this->_sections['key']['index'] + $this->_sections['key']['step'];
$this->_sections['key']['first']      = ($this->_sections['key']['iteration'] == 1);
$this->_sections['key']['last']       = ($this->_sections['key']['iteration'] == $this->_sections['key']['total']);
?>
				<div class="travels_mood_add fn-tc fn-pr" id="<?php echo $this->_tpl_vars['beforelist'][$this->_sections['key']['index']]['day_id']; ?>
">
	                <input type="hidden" name="picId" value="<?php echo $this->_tpl_vars['beforelist'][$this->_sections['key']['index']]['day_id']; ?>
">
	                <input type="hidden" value="<?php echo $this->_tpl_vars['beforelist'][$this->_sections['key']['index']]['is_like']; ?>
" name="currIsPraised">	                <input type="hidden" value="2" name="targeType">
	                <?php if ($this->_tpl_vars['is_self'] == true): ?>
	            	   <span class="travels_mood_add_delete fn-pa delete fn-none png24" style="display: none;"></span>
	            	<?php endif; ?>
	            	<div class="travels_describe fn-pr">
<!-- 	            	<span id="albumDescError" class="textarea_error_mood darkgray666 fn-pa fn-tl"> 效验文字展示</span>
 -->					<p class="J-mood-content travels_describe_nm fn-tl mb20 f14 fn-pr fn-enter"><span><?php echo $this->_tpl_vars['beforelist'][$this->_sections['key']['index']]['description']; ?>
</span><?php if ($this->_tpl_vars['is_self'] == true): ?><span class="J-mood-edit travels_mood_add_edit edit fn-none png24 lh30" style="display: none;"></span><?php endif; ?></p>
			
	            	<p class=" darkgray666 fn-clearfix">
	                    	
 							<span class="J-address fn-fr">
                        	
                            	
                            	
                        		<em title="<?php if ($this->_tpl_vars['beforelist'][$this->_sections['key']['index']]['is_like'] == '2'): ?>我很喜欢，支持一下<?php else: ?>已喜欢<?php endif; ?>" class="J-approve <?php if ($this->_tpl_vars['beforelist'][$this->_sections['key']['index']]['is_like'] == '2'): ?>travels_approve<?php else: ?>travels_approve_hover<?php endif; ?> font-tahoma darkgray  mr10 "><span class="likemood"><?php if ($this->_tpl_vars['beforelist'][$this->_sections['key']['index']]['is_like'] == '2'): ?>喜欢<?php else: ?>已喜欢<?php endif; ?></span>(<span class="mood_prai"><?php echo $this->_tpl_vars['beforelist'][$this->_sections['key']['index']]['like_number']; ?>
</span>)</em>
                        		
                        	
                        	<em title="我有话想说" class="J-comment travels_comment font-tahoma darkgray  mr10 ">评论(<span class="mood_commentnum"><?php echo $this->_tpl_vars['beforelist'][$this->_sections['key']['index']]['replay_number']; ?>
</span>)</em>
                        	<em class="travels_blowse font-tahoma darkgray  mr10">浏览(<?php echo $this->_tpl_vars['beforelist'][$this->_sections['key']['index']]['read_number']; ?>
)</em>
                      		</span>
	              	</p>
	              	</div>
	            	<div class="travels_mood_buttom fn-tr fn-none">
					<input type="button" value="" name="" class="moodcancel cancel mr5">
					<input type="button" value="" name="" class="moodSave confirm">
					</div>
	            </div>
								<?php endfor; endif; ?>
								<?php unset($this->_sections['key']);
$this->_sections['key']['name'] = 'key';
$this->_sections['key']['loop'] = is_array($_loop=$this->_tpl_vars['piclist']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['key']['show'] = true;
$this->_sections['key']['max'] = $this->_sections['key']['loop'];
$this->_sections['key']['step'] = 1;
$this->_sections['key']['start'] = $this->_sections['key']['step'] > 0 ? 0 : $this->_sections['key']['loop']-1;
if ($this->_sections['key']['show']) {
    $this->_sections['key']['total'] = $this->_sections['key']['loop'];
    if ($this->_sections['key']['total'] == 0)
        $this->_sections['key']['show'] = false;
} else
    $this->_sections['key']['total'] = 0;
if ($this->_sections['key']['show']):

            for ($this->_sections['key']['index'] = $this->_sections['key']['start'], $this->_sections['key']['iteration'] = 1;
                 $this->_sections['key']['iteration'] <= $this->_sections['key']['total'];
                 $this->_sections['key']['index'] += $this->_sections['key']['step'], $this->_sections['key']['iteration']++):
$this->_sections['key']['rownum'] = $this->_sections['key']['iteration'];
$this->_sections['key']['index_prev'] = $this->_sections['key']['index'] - $this->_sections['key']['step'];
$this->_sections['key']['index_next'] = $this->_sections['key']['index'] + $this->_sections['key']['step'];
$this->_sections['key']['first']      = ($this->_sections['key']['iteration'] == 1);
$this->_sections['key']['last']       = ($this->_sections['key']['iteration'] == $this->_sections['key']['total']);
?>
				<!--游记图片和文字描述-->
				<div id="<?php echo $this->_tpl_vars['piclist'][$this->_sections['key']['index']]['image_id']; ?>
" class="travels_right_cont fn-pr fn-tc">
					<input type="hidden" value="<?php echo $this->_tpl_vars['piclist'][$this->_sections['key']['index']]['image_id']; ?>
" name="picId"/>
					<input type="hidden" value="<?php echo $this->_tpl_vars['piclist'][$this->_sections['key']['index']]['location_id']; ?>
" name="addressId"/>
					<input type="hidden" value="<?php echo $this->_tpl_vars['piclist'][$this->_sections['key']['index']]['addressName']; ?>
" name="addressName"/>
					<input name="currIsPraised" type="hidden" value="<?php echo $this->_tpl_vars['piclist'][$this->_sections['key']['index']]['is_like']; ?>
"/>					<input name="targeType" type="hidden" value="1"/>
					<?php if ($this->_tpl_vars['is_self'] == true): ?><span class="travels_right_cont_delete fn-pa deleteimg fn-none png24"></span><?php endif; ?> <a href="<?php echo $this->_tpl_vars['piclist'][$this->_sections['key']['index']]['image_href']; ?>
" class="travels_right_img  fn-pr"> <span class="travels_arrows fn-pa" style="width:<?php echo $this->_tpl_vars['piclist'][$this->_sections['key']['index']]['width']; ?>
px"></span> <img class="qpic" src="<?php echo $this->_tpl_vars['piclist'][$this->_sections['key']['index']]['image_src']; ?>
" original="<?php echo $this->_tpl_vars['piclist'][$this->_sections['key']['index']]['image_src']; ?>
" onerror="javascript:this.src='<?php echo @DIR_WS_IMG; ?>
erroimgs.jpg'" <?php if ($this->_tpl_vars['piclist'][$this->_sections['key']['index']]['width'] != ""): ?>width="<?php echo $this->_tpl_vars['piclist'][$this->_sections['key']['index']]['width']; ?>
"<?php endif; ?> <?php if ($this->_tpl_vars['piclist'][$this->_sections['key']['index']]['height'] != ''): ?>height="<?php echo $this->_tpl_vars['piclist'][$this->_sections['key']['index']]['height']; ?>
"<?php endif; ?> alt="<?php echo $this->_tpl_vars['piclist'][$this->_sections['key']['index']]['addressName']; ?>
"/> </a> 
					<!--游记图片描述-->
					<div class="travels_describe fn-pr"> 
						<!--                 	<span id="albumDescError" class="textarea_error_pic darkgray666 fn-pa fn-tl">效验文字展示地方</span>
 -->
						
						<p id="fdesc" class="J-pic-content f14 darkgray333 fn-tl fn-enter fn-pr"><?php echo ((is_array($_tmp=@$this->_tpl_vars['piclist'][$this->_sections['key']['index']]['image_desc'])) ? $this->_run_mod_handler('default', true, $_tmp, "您啥都没有写，写点吧亲") : smarty_modifier_default($_tmp, "您啥都没有写，写点吧亲")); ?>
<?php if ($this->_tpl_vars['is_self'] == true): ?><span class="J-pic-edit travels_describe_edit edit fn-none png24"></span><?php endif; ?></p>
						<div class="travels_scenery_fiy darkgray666 fn-clearfix png24">
							<div id="faddressName" class="travels_scenery fn-fl fn-enter fn-omit png24"><span class="J-places"><a href="<?php echo ((is_array($_tmp=@$this->_tpl_vars['piclist'][$this->_sections['key']['index']]['alibumPicAddressNameHref'])) ? $this->_run_mod_handler('default', true, $_tmp, "javascript:void(0)") : smarty_modifier_default($_tmp, "javascript:void(0)")); ?>
" title="<?php echo $this->_tpl_vars['piclist'][$this->_sections['key']['index']]['addressName']; ?>
"><?php echo $this->_tpl_vars['piclist'][$this->_sections['key']['index']]['addressName']; ?>
</a></span></div>
							<span class="J-address fn-fr"> <em class="J-approve <?php if ($this->_tpl_vars['piclist'][$this->_sections['key']['index']]['is_like'] == 2): ?>travels_approve<?php else: ?>travels_approve_hover<?php endif; ?> font-tahoma darkgray  mr10 " title="<?php if ($this->_tpl_vars['piclist'][$this->_sections['key']['index']]['is_like'] == 2): ?>我很喜欢，支持一下<?php else: ?>已喜欢<?php endif; ?>"><span class="like"><?php if ($this->_tpl_vars['piclist'][$this->_sections['key']['index']]['is_like'] == 2): ?>喜欢<?php else: ?>已喜欢<?php endif; ?></span>(<span class="pic_prai"><?php echo $this->_tpl_vars['piclist'][$this->_sections['key']['index']]['like_number']; ?>
</span>)</em> <em class="J-comment travels_comment font-tahoma darkgray  mr10 " title="我有话想说">评论(<span class="pic_commentnum"><?php echo $this->_tpl_vars['piclist'][$this->_sections['key']['index']]['replay_number']; ?>
</span>)</em> <em class="travels_blowse font-tahoma darkgray  mr10">浏览(<?php echo $this->_tpl_vars['piclist'][$this->_sections['key']['index']]['read_number']; ?>
)</em> </span> </div>
					</div>
					
					<!--          		取消/确定	取消/确定 end -->
					<div class="travels_describe_buttom fn-tr fn-none">
						<input class="piccancel cancel mr5 png24" name="" type="button" value=""/>
						<input class="picSave confirm png24" name="" type="button" value=""/>
					</div>
				</div>
				<!--游记图片和文字描述 end --> 
				<?php endfor; endif; ?>
 
				<!--心情描述 心情显示方式-->
				 
				<?php unset($this->_sections['key']);
$this->_sections['key']['name'] = 'key';
$this->_sections['key']['loop'] = is_array($_loop=$this->_tpl_vars['afterlist']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['key']['show'] = true;
$this->_sections['key']['max'] = $this->_sections['key']['loop'];
$this->_sections['key']['step'] = 1;
$this->_sections['key']['start'] = $this->_sections['key']['step'] > 0 ? 0 : $this->_sections['key']['loop']-1;
if ($this->_sections['key']['show']) {
    $this->_sections['key']['total'] = $this->_sections['key']['loop'];
    if ($this->_sections['key']['total'] == 0)
        $this->_sections['key']['show'] = false;
} else
    $this->_sections['key']['total'] = 0;
if ($this->_sections['key']['show']):

            for ($this->_sections['key']['index'] = $this->_sections['key']['start'], $this->_sections['key']['iteration'] = 1;
                 $this->_sections['key']['iteration'] <= $this->_sections['key']['total'];
                 $this->_sections['key']['index'] += $this->_sections['key']['step'], $this->_sections['key']['iteration']++):
$this->_sections['key']['rownum'] = $this->_sections['key']['iteration'];
$this->_sections['key']['index_prev'] = $this->_sections['key']['index'] - $this->_sections['key']['step'];
$this->_sections['key']['index_next'] = $this->_sections['key']['index'] + $this->_sections['key']['step'];
$this->_sections['key']['first']      = ($this->_sections['key']['iteration'] == 1);
$this->_sections['key']['last']       = ($this->_sections['key']['iteration'] == $this->_sections['key']['total']);
?>
				<div class="travels_mood_add fn-tc fn-pr" id="<?php echo $this->_tpl_vars['afterlist'][$this->_sections['key']['index']]['day_id']; ?>
">
	                <input type="hidden" name="picId" value="<?php echo $this->_tpl_vars['afterlist'][$this->_sections['key']['index']]['day_id']; ?>
">
	                <input type="hidden" value="<?php echo $this->_tpl_vars['afterlist'][$this->_sections['key']['index']]['is_like']; ?>
" name="currIsPraised">	                <input type="hidden" value="2" name="targeType">
	                <?php if ($this->_tpl_vars['is_self'] == true): ?>
	            	   <span class="travels_mood_add_delete fn-pa delete fn-none png24" style="display: none;"></span>
	            	<?php endif; ?>
	            	<div class="travels_describe fn-pr">
<!-- 	            	<span id="albumDescError" class="textarea_error_mood darkgray666 fn-pa fn-tl"> 效验文字展示</span>
 -->					<p class="J-mood-content travels_describe_nm fn-tl mb20 f14 fn-pr fn-enter"><span><?php echo $this->_tpl_vars['afterlist'][$this->_sections['key']['index']]['description']; ?>
</span><?php if ($this->_tpl_vars['is_self'] == true): ?><span class="J-mood-edit travels_mood_add_edit edit fn-none png24 lh30" style="display: none;"></span><?php endif; ?></p>
			
	            	<p class=" darkgray666 fn-clearfix">
	                    	
 							<span class="J-address fn-fr">
                        	
                            	
                            	
                        		<em title="<?php if ($this->_tpl_vars['afterlist'][$this->_sections['key']['index']]['is_like'] == '2'): ?>我很喜欢，支持一下<?php else: ?>已喜欢<?php endif; ?>" class="J-approve <?php if ($this->_tpl_vars['afterlist'][$this->_sections['key']['index']]['is_like'] == '2'): ?>travels_approve<?php else: ?>travels_approve_hover<?php endif; ?> font-tahoma darkgray  mr10 "><span class="likemood"><?php if ($this->_tpl_vars['afterlist'][$this->_sections['key']['index']]['is_like'] == '2'): ?>喜欢<?php else: ?>已喜欢<?php endif; ?></span>(<span class="mood_prai"><?php echo $this->_tpl_vars['afterlist'][$this->_sections['key']['index']]['like_number']; ?>
</span>)</em>
                        		
                        	
                        	<em title="我有话想说" class="J-comment travels_comment font-tahoma darkgray  mr10 ">评论(<span class="mood_commentnum"><?php echo $this->_tpl_vars['afterlist'][$this->_sections['key']['index']]['replay_number']; ?>
</span>)</em>
                        	<em class="travels_blowse font-tahoma darkgray  mr10">浏览(<?php echo $this->_tpl_vars['afterlist'][$this->_sections['key']['index']]['read_number']; ?>
)</em>
                      		</span>
	              	</p>
	              	</div>
	            	<div class="travels_mood_buttom fn-tr fn-none">
					<input type="button" value="" name="" class="moodcancel cancel mr5">
					<input type="button" value="" name="" class="moodSave confirm">
					</div>
	            </div>
								<?php endfor; endif; ?>
				 
				<?php if ($this->_tpl_vars['is_self'] == true): ?>
				<!-- 插入文字/插入图片 go -->
				<div class="J-hidden">
					<div class="travels_op_add fn-none fn-tc">
						<div class="travels_op fn-bc"> <span class="J-opText travels_op_text fn-fl png24"></span> <span class="J-opPic travels_op_pic fn-fr png24"></span>
							<input type="hidden" value="1361846662342" name="after"/>
						</div>
					</div>
				</div>
				<?php endif; ?>
				<!--插入文字/插入图片 end -->
				<?php if ($this->_tpl_vars['last_day'] == $this->_tpl_vars['current_day']): ?>
				<div class="travels_next_end fn-tc mb20"><img src="<?php echo @DIR_WS_IMG; ?>
next_end.jpg" width="323" height="73" /></div>
				<?php endif; ?>
				<div class="travels_pageTurning mb20">
				<?php if ($this->_tpl_vars['next'] > 1): ?>
				<?php if ($this->_tpl_vars['current_day'] != $this->_tpl_vars['first_day']): ?>
				<div class="travels_prveDay fn-fl png24"></div>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['last_day'] == $this->_tpl_vars['current_day']): ?>
          		<div class="travels_again fn-fr png24"></div>
				<?php else: ?>
          		<div class="travels_nextDay fn-fr png24"></div>
				<?php endif; ?>
				<?php endif; ?>
				 </div>
			</div>
			<!--travels_right end--> 
			
		</div>
		<!--travels_main end--> 
		
	</div>
	<!--container end--> 
	
	<!--弹出评论框-->
	<div class="bg_mask"></div>
	<div class="pop_status">
		<div class="close fn-pa png24"></div>
		<div class="content">
			<div class="travels_comment_list darkgray fn-bc">
				<ul id="feedback">
				</ul>
			</div>
			<!-- 评论模块 翻页 -->
			<div id="pagination" class="pagination darkgray mt10"></div>
			<!--     翻页
    <div id="pagination" class="pagination darkgray mt10 mb10 fn-tc"><a href="#1" class="first png24">首页</a><a href="#1" class="prev png24">前一页</a><span class="current">1</span><a href="#1" class="next png24">下一页</a><a href="#1" class="last png24">末页</a></div>
  --> 
			<!--评论框-->
			<div class="travels_comment_box fn-bc mt10">
				<div class="fn-fl">
					<textarea name="textarea" id="textlimit" cols="45" rows="1" title="图片很美，说点神马吧" style="background-color: rgb(255, 255, 255); background-position: initial initial; background-repeat: no-repeat no-repeat; " class="focus"></textarea>
				</div>
				<div class="ml5 fn-tr fn-fr "> <span id="prompt" class="travels_comment_restrict mb10 f12 darkgray">还可以输入<em class="deongaree">140</em>个字</span> <span class="J-comment-btn confirm fn-block" onClick="javascript:comment();"></span> </div>
			</div>
		</div>
	</div>
	<!-- <div id="testIE6"></div> --> 
	<!--firfox虚影解决-->
	<div class="wrap_shadow"></div>
	<input id="newHandler" value="11110" type="hidden"/>

</div>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "foot.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<!-- 隐藏地址列表  -->
<div id="albumOrPicDesList" style="display:none">
	<ul id="albumOrPicDesListUl">
		<li> <span id="albumOrPicDesId"><?php echo $this->_tpl_vars['to_address_id']; ?>
</span><!-- 地址Id --> 
			<span id="albumOrPicDesName"><?php echo $this->_tpl_vars['to_address']; ?>
</span><!-- 地址名称--> 
		</li>
	</ul>
</div>
<script type="text/javascript">		
var pathUpload = "<?php echo $this->_tpl_vars['nav_index']; ?>
";
</script> 

</body>
</html><script src="<?php echo @DIR_WS_JS; ?>
albumDay-201302261120.js" charset="utf-8"></script>