<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link rel="stylesheet" type="text/css" href="css2/global.css" />
<script type="text/javascript" src="js2/jquery.js"></script>
<script type="text/javascript" src="js2/global.js"></script>
<title><?php echo $DOC_TITLE ?></title>
</head>
<body>
<table width="100%"  border="0" height="100%" cellpadding="0" cellspacing="0" class="centerTab">
    <tr id="Header">
        <td colspan="3" height="55">
            <div class="centerTopFrame">
                <div class="head">
                    <p class="logo"><img src="images2/orderlogo.jpg" class="logoimg" /><span><?php echo $DOC_TITLE ?></span></p>
                    
                    
                    <div class="changeSys" id="ChangeSys">
                        <div class="changeSysTitle"><?php echo db_to_html('切换系统')?></div>
                        <div class="changeSysCon" id="ChangeSysCon">
                            <ul>
								<li> <?php  echo '<a target="_blank" href="' . tep_catalog_href_link() . '" class="headerLink">';?><?php echo db_to_html('usitrip.com')?></a> </li>
                                <li><a href="<?php echo tep_href_link(FILENAME_DEFAULT, '', 'NONSSL') ?>" class="headerLink"><?php echo db_to_html('usitrip.com 后台')?></a></li>     <li><?php  echo '<a href="' . tep_href_link('zhh_system_index.php', '', 'NONSSL') . '" class="headerLink">';?><?php echo db_to_html('知识库系统')?></a></li>                      
                            </ul>
                        </div>
                     </div>  
                </div>
                
                <div class="welcome">
				<?php echo db_to_html(sprintf('%s 欢迎您登录，<a href="%s">退出</a>',$_SESSION['login_firstname'],tep_href_link(FILENAME_LOGOFF, '', 'NONSSL')))?></div>
            </div>
        </td>
    </tr>
    
    <tr>
        <td colspan="3" height="7" valign="top" align="center" bgcolor="#EBEFF2">
            <div class="topSwitch" id="HeaderSwitch"></div>
        </td>
    </tr>
  
    <tr>
        <td class="centerLeftFrame " width="200" id="Left"><iframe name="I2" height="100%"  width="200px" src="<?php echo $DOC_LEFT?>" border="0" frameborder="0" scrolling="no"  ><?php db_to_html('浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。')?> </iframe></td>

        <td class="leftSwitchBg " width="20" height="100%" ><div class="leftSwitch"  id="LeftSwitch" ></div></td>

        <td class="rightIframe" height="100%;" valign="top"><iframe width="100%" height="100%" name="main" id="main" src="<?php echo $DOC_MAIN?>" border="0" frameborder="0" scrolling=""><?php db_to_html('浏览器不支持嵌入式框架，或被配置为不显示嵌入式框架。')?></iframe>   </td>
    </tr>

    <tr>
        <td colspan="3" height="35"><div class="footer">版权 <span>&copy;</span>2006-2011 usitrip.com</div></td>
    </tr>
</table>
</body>
</html>
