<?php /* Smarty version 2.6.22, created on 2013-12-27 17:27:46
         compiled from head.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!--[if ie]>
<html xmlns:wb="http://open.weibo.com/wb">
<![endif]-->
<head>
<base href="<?php echo $this->_tpl_vars['head_base_href']; ?>
" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['head_charset']; ?>
" />
<link href="<?php echo @DIR_WS_CSS; ?>
public.css" rel="stylesheet" type="text/css" />
<link href="<?php echo @DIR_WS_CSS; ?>
<?php echo $this->_tpl_vars['current_css_file']; ?>
" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo @DIR_WS_JS; ?>
lwk.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo @DIR_WS_JS; ?>
jquery.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo @DIR_WS_JS; ?>
public.js" charset="utf-8"></script>

<title><?php echo $this->_tpl_vars['page_title']; ?>
</title>
<meta name="Robots" content="index,follow,NOODP" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="cache-control" content="no-cache" />
<script type="text/javascript">
<?php if (@ENABLE_SSL): ?>
var is_ssl = true;
<?php else: ?>
var is_ssl = false;
<?php endif; ?>
var base_url = '<?php echo $this->_tpl_vars['head_base_href']; ?>
';
if (!lwkai) {
    var lwkai = {};
};
/**
 * 登录地址 
 * @var string
 */
lwkai.loginUrl = '<?php echo $this->_tpl_vars['js_login']; ?>
';
lwkai.logoutUrl = '<?php echo $this->_tpl_vars['js_logout']; ?>
';
lwkai.imageUrl = '<?php echo @DIR_WS_IMG; ?>
';
</script>
</head>
<body>
<div id="sitebg">
	<!--div class="lvtu_header" id="topbar"-->
		<div class="qn_nav">
			<div class="qn_header wrapper">
				<div class="fr">
					<ul class="fl uhmenu">
						<li id="loginOrLogin" class="freeregister">
						<?php if ($this->_tpl_vars['customer_id'] == ''): ?>
							<a href="<?php echo $this->_tpl_vars['login_href']; ?>
">会员登录</a>
							<a href="<?php echo $this->_tpl_vars['reg_href']; ?>
">免费注册</a>
						<?php else: ?>
							<a href="<?php echo $this->_tpl_vars['user_account']; ?>
">您好，<?php echo $this->_tpl_vars['username']; ?>
</a>|<a href="<?php echo $this->_tpl_vars['logout_href']; ?>
">退出</a>
						<?php endif; ?>
							<!--会员登录信息提示-->
						</li>
                    	<li class="userhome">
                    		<a href="<?php echo $this->_tpl_vars['user_account']; ?>
">用户中心</a>
                        	<div class="uhmenutree hide">
                        		<a href="<?php echo $this->_tpl_vars['my_orders']; ?>
">我的订单</a>
                            	<a href="<?php echo $this->_tpl_vars['my_favorites']; ?>
">我的收藏</a>
                        	</div>
                    	</li>
					</ul>
					<ul class="fl helpmenu">
                		<li class="helpcenter"><a href="<?php echo $this->_tpl_vars['help']; ?>
">帮助中心</a></li>
                    	<!--li><a href="" class="traditional">繁体</a></li>
                    	<li><a href="" class="english">English</a></li-->
                	</ul>
					<!--script type="text/javascript">G.writeLoginInfo();</script-->
					<input type="hidden"  id="userid" value="<?php echo $this->_tpl_vars['customer_id']; ?>
"/>
				</div>
				<span class="left"><wb:follow-button uid="3223551651" type="red_2" width="136" height="24" class="weibo"></wb:follow-button>USItrip走四方旅游网-Your Trip , Our Care !</span>
			</div>
		</div>
	<!--/div-->
	<div id="head">
    	<div class="hd">
        	<div class="fl logo"><a href="<?php echo $this->_tpl_vars['nav_index']; ?>
" title="欢迎来到走四方网">走四方网</a></div>
            <div class="fl grade">
            	<a href="<?php echo $this->_tpl_vars['a_jia_href']; ?>
" target="_blank">全球华人首选出国旅游网站<br>美国BBB认证最高商誉评级</a>
            </div>
            <div class="fl hdsearch">
            	<form method="get" action="<?php echo $this->_tpl_vars['top_search_action']; ?>
" id="TopSearchForm" name="TopSearchForm">
        			<div class="fl rel hdsearchIpt">
                    	<input type="text" name="w" class="hdsearchWords autocomplete_input" placeholder="请输入出发城市或想去的景点" dataurl="<?php echo $this->_tpl_vars['top_search_ajax_url']; ?>
"/>
                                            </div>
                    <input type="submit" class="fr hdsearchSubmit" value="搜索" />
                </form>
            </div>
            <div class="fr hdContact">
            	<ul>
                	<li>1-888-887-2816(美加)</li>
                    <li>4006-333-926(中国)</li>
                </ul>
            </div>
        </div>
        <div class="nav">
        	<ul class="navItems">
				<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['navlist']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
				<li <?php if ($this->_sections['i']['last']): ?>class="last"<?php endif; ?>><a href="<?php echo $this->_tpl_vars['navlist'][$this->_sections['i']['index']]['href']; ?>
" target="_blank" hidefocus="on"><span><?php echo $this->_tpl_vars['navlist'][$this->_sections['i']['index']]['text']; ?>
</span></a></li>
	 			<?php endfor; endif; ?>
            </ul>
        </div>
    </div>