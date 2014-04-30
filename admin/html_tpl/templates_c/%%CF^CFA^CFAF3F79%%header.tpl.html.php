<?php /* Smarty version 2.6.25-dev, created on 2013-12-27 00:54:22
         compiled from header.tpl.html */ ?>
<?php echo $this->_tpl_vars['DOCTYPE']; ?>

<html <?php echo @HTML_PARAMS; ?>
>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo @CHARSET; ?>
">
<title><?php echo $this->_tpl_vars['title']; ?>
</title>
<!--Load Files for Css-->
<?php unset($this->_sections['n']);
$this->_sections['n']['name'] = 'n';
$this->_sections['n']['loop'] = is_array($_loop=$this->_tpl_vars['CssArray']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['n']['show'] = true;
$this->_sections['n']['max'] = $this->_sections['n']['loop'];
$this->_sections['n']['step'] = 1;
$this->_sections['n']['start'] = $this->_sections['n']['step'] > 0 ? 0 : $this->_sections['n']['loop']-1;
if ($this->_sections['n']['show']) {
    $this->_sections['n']['total'] = $this->_sections['n']['loop'];
    if ($this->_sections['n']['total'] == 0)
        $this->_sections['n']['show'] = false;
} else
    $this->_sections['n']['total'] = 0;
if ($this->_sections['n']['show']):

            for ($this->_sections['n']['index'] = $this->_sections['n']['start'], $this->_sections['n']['iteration'] = 1;
                 $this->_sections['n']['iteration'] <= $this->_sections['n']['total'];
                 $this->_sections['n']['index'] += $this->_sections['n']['step'], $this->_sections['n']['iteration']++):
$this->_sections['n']['rownum'] = $this->_sections['n']['iteration'];
$this->_sections['n']['index_prev'] = $this->_sections['n']['index'] - $this->_sections['n']['step'];
$this->_sections['n']['index_next'] = $this->_sections['n']['index'] + $this->_sections['n']['step'];
$this->_sections['n']['first']      = ($this->_sections['n']['iteration'] == 1);
$this->_sections['n']['last']       = ($this->_sections['n']['iteration'] == $this->_sections['n']['total']);
?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['CssArray'][$this->_sections['n']['index']]; ?>
">
<?php endfor; endif; ?>

<!--Load Files for JS-->
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['JavaScriptSrc']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<script language="JavaScript" type="text/javascript" src="<?php echo $this->_tpl_vars['JavaScriptSrc'][$this->_sections['i']['index']]; ?>
"></script>
<?php endfor; endif; ?>

<?php if ($this->_tpl_vars['open_dir_id'] != ""): ?>
<script type="text/javascript">
//var I2_iframe = parent.document.getElementById("I2");
var I2_iframe = window.parent.frames['I2'];
if(I2_iframe!=null){
	try {  
		if(typeof(eval("I2_iframe.auto_open_to_target_ul"))=="function"){
			I2_iframe.auto_open_to_target_ul(<?php echo $this->_tpl_vars['open_dir_id']; ?>
,"show");
		}else{
			//alert("not function: auto_open_to_target_ul"); 
		}
	}catch(e){
	}  
}
</script> 
<?php endif; ?>
</head>

<body <?php echo $this->_tpl_vars['body_style']; ?>
 oncontextmenu="return false;" onselectstart="return false;" oncopy="return false;" oncut="return false;" ondragstart="return false;">
<div id="notice_div"></div>
<?php if ($this->_tpl_vars['Bread'] != ""): ?>
<div class="Bread">
<?php echo $this->_tpl_vars['Bread']; ?>

</div>
<?php endif; ?>

<!--
</body>
</html>
-->