<?php /* Smarty version 2.6.25-dev, created on 2013-12-27 00:54:26
         compiled from zhh_system_words_list.tpl.html */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script type="text/javascript">
function del_words(words_id,botton_obj){
    var obj = botton_obj;
    if(confirm('确认要删除这个文章？')){
        //alert('OK');
        var urladdress = "<?php echo $this->_tpl_vars['js_urladdress']; ?>
";
        urladdress+= "&words_id="+words_id;
        $.ajax({
               type: "GET",
               url: urladdress,
               success: function(){
                   //obj.parentNode.parentNode.parentNode.removeChild(obj.parentNode.parentNode);
                   $("#tr_"+words_id).slideUp(500);
                }
        });
    }
}

</script>
<div class="main">
<?php if ($this->_tpl_vars['have_everyone']): ?>
    <div class="prompt">
        <span class="unread">未读信息<b><?php echo $this->_tpl_vars['unread']; ?>
</b>条</span>
        <span class="red"><a href="?dir_id=<?php echo $this->_tpl_vars['dir_id']; ?>
&adjective=1&read=0">紧急信息<b><?php echo $this->_tpl_vars['adjective']; ?>
</b>条</a></span>
        <span class="orange"><a href="?dir_id=<?php echo $this->_tpl_vars['dir_id']; ?>
&adjective=0&read=0">非紧急信息<b><?php echo $this->_tpl_vars['unadjective']; ?>
</b>条</a></span>
        <span class="green"><a target="_blank" href="orders.php">紧急订单处理</a></span>
    </div>
<?php endif; ?>
  <form action="" method="get" id="form_search" target="_self" name="form_search">
    <div class="ItemsTj">
        <h1 class="ItemsH1"  id="tit" onclick="showHideLyer(this,'CI_content','ItemsH1Select')">搜索条件</h1>
        <div class="ItemsTjContent" id="CI_content">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>关键字：</td><td><?php echo $this->_tpl_vars['input_field_keyword']; ?>
</td>
                    <td><label style="float:left"><input type="checkbox" name="is_serch_content" checked="checked" value="1"/>同时搜索文章内容</label></td>
                    <?php if ($this->_tpl_vars['have_everyone']): ?>
                    <td align="right" nowrap="nowrap">&nbsp;&nbsp;provider：</td><td nowrap="nowrap"><?php echo $this->_tpl_vars['input_field_provider']; ?>
<input type="hidden" name="dir_id" id="dir_id" value="<?php echo $this->_tpl_vars['dir_id']; ?>
" /></td>
                    <?php else: ?>
                    <td align="right" nowrap="nowrap">&nbsp;&nbsp;所属目录：</td><td nowrap="nowrap"><?php echo $this->_tpl_vars['pull_down_menu_dir_id']; ?>
</td>					
                    <?php endif; ?>
                </tr>
                <tr>
                    
                    <td nowrap="nowrap">发布日期：</td>
                    <td colspan="2" nowrap="nowrap">					
            <input type="text" class="textTime" name="added_start_date" value="<?php echo $this->_tpl_vars['added_start_date']; ?>
" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" style="ime-mode: disabled;" />至<input type="text" class="textTime" name="added_end_date" value="<?php echo $this->_tpl_vars['added_end_date']; ?>
" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" />					
            </td>
            <?php if ($this->_tpl_vars['have_everyone']): ?>
            <td align="right" nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;发布人员：</td>
            <td nowrap="nowrap"><?php echo $this->_tpl_vars['input_field_serch_admin_name']; ?>
</td>
            <?php else: ?>
            <td align="right" nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;更新日期：</td>
            <td nowrap="nowrap">
            <input type="text" class="textTime" name="updated_start_date" value="<?php echo $this->_tpl_vars['updated_start_date']; ?>
" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" style="ime-mode: disabled;" />至<input type="text" class="textTime" name="updated_end_date" value="<?php echo $this->_tpl_vars['updated_end_date']; ?>
" style="ime-mode: disabled;" onclick="GeCalendar.SetUnlimited(1); GeCalendar.SetDate(this);" />					
              </td>
            <?php endif; ?>  
              </tr>
                <tr><td colspan="9" class="ButtonAligh">
                    <button type="submit" class="Allbutton">搜索</button> <button class="AllbuttonHui" type="button" onclick="javascript: location='<?php echo $this->_tpl_vars['href_link_zhh_system_words_list']; ?>
'">清除搜索数据</button>
                    </td></tr>
          </table>
      </div>
    </div>
  </form>

 <div class="ItemsLb">
   <h1 class="ItemsH1"  id="Lb" onclick="showHideLyer(this,'Lb_content','ItemsH1Select')">搜索结果</h1>
   <div class="ItemsLbContent" id="Lb_content">
  <form action="" method="get">
  <div align="left" style="width:100%; padding-top:10px; padding-bottom:10px;">
    <?php if ($this->_tpl_vars['href_link_create_words'] != ""): ?>
    <a href="<?php echo $this->_tpl_vars['href_link_create_words']; ?>
" class="dosome">新增文章</a>
    <?php endif; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php if ($this->_tpl_vars['href_link_admin_dir'] != ""): ?>
    <a href="<?php echo $this->_tpl_vars['href_link_admin_dir']; ?>
" class="nyroModal dosome" target="_blank">管理目录</a>
    <?php endif; ?>
    </div>
<table class="indexDdanyjXx">
    <!--省略标题
    <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent" nowrap="nowrap">ID</td>
        <td class="dataTableHeadingContent" nowrap="nowrap">标题</td>
        <td class="dataTableHeadingContent" nowrap="nowrap">发布人</td>
        <td class="dataTableHeadingContent" nowrap="nowrap">发布日期</td>
        <td class="dataTableHeadingContent" nowrap="nowrap">最后更新</td>
        <td class="dataTableHeadingContent" nowrap="nowrap">更新日期</td>
        <td class="dataTableHeadingContent" nowrap="nowrap">所属目录</td>
        <td class="dataTableHeadingContent" nowrap="nowrap">附件</td>
        <td class="dataTableHeadingContent" nowrap="nowrap">操作</td>
        </tr>
    -->
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
        
        <tr id="tr_<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['words_id']; ?>
" class="indexDdanyjXxBorb">
        <!--<td><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['words_id']; ?>
</td>-->
        <td class="urgent">       
        <?php if ($this->_tpl_vars['have_everyone']): ?>      
               
                    <?php if ($this->_tpl_vars['datas'][$this->_sections['i']['index']]['is_read'] == 1 || $this->_tpl_vars['datas'][$this->_sections['i']['index']]['is_my_work'] == 1): ?>
                    【已读】
                    <?php else: ?>
                    【未读】
                    <?php endif; ?>
               
                      
        <?php endif; ?>
        <?php if ($this->_tpl_vars['have_everyone']): ?>
            <?php if ($this->_tpl_vars['datas'][$this->_sections['i']['index']]['is_read'] == 1): ?>
                <?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['words_title']; ?>

                <?php if ($this->_tpl_vars['datas'][$this->_sections['i']['index']]['is_adjective'] == 1): ?>
                    <span class="urgentIcon">&nbsp;</span>
                <?php endif; ?>
            <?php else: ?>
                <?php if ($this->_tpl_vars['datas'][$this->_sections['i']['index']]['is_my_work'] == 1): ?>
                    <?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['words_title']; ?>

                <?php else: ?>
                    <b><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['words_title']; ?>
</b>
                <?php endif; ?>
                
                <?php if ($this->_tpl_vars['datas'][$this->_sections['i']['index']]['is_adjective'] == 1): ?>
                    <span class="urgentIcon">&nbsp;</span>
                <?php endif; ?>
            <?php endif; ?>
        <?php else: ?>
            <?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['words_title']; ?>

        <?php endif; ?>
        </td>
        <td class="spanContentp"><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['sent_name']; ?>
</td>
        <td class="spanContent"><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['added_time']; ?>
</td>
        <!--<td><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['up_per_name']; ?>
</td>
        <td><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['updated_time']; ?>
</td>
        <td><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['this_dirs_string']; ?>
</td>
        <td><?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['annexs']; ?>
</td>-->
        <td class="caozuoContent">
            <!--<?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['view_button']; ?>
-->
            <?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['edit_button']; ?>

            </td>
            <td class="caozuoContent">
            <?php echo $this->_tpl_vars['datas'][$this->_sections['i']['index']]['del_button']; ?>

            </td>
        </tr>
        
    <?php endfor; endif; ?>
    
</table>
    </form>
  
  
  <div style="margin-top:10px;">
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
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>