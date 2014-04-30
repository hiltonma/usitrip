<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link rel="stylesheet" type="text/css" href="css2/global.css" />
<script type="text/javascript" src="js2/jquery.js"></script>
<script type="text/javascript" src="js2/global.js"></script>
<title>left</title>
<style type="text/css">
*{font-size:12px;}
</style>
</head>

<body>
<div class="leftIframe">
    <div class="title"><span class="tl"></span><span class="tr"></span>
       <h3><?php echo db_to_html('系统导航');?></h3>
    </div>

    <div class="leftCon" id="LeftCon">
        <div class="leftConBox" id="LeftConBox">
            <ul>
                <li><a href="orders_pointcards.php" target="main"><?php echo db_to_html('查询会员积分卡消费情况');?></a></li>   
				<li><a href="orders_pointcards.php?action=status" target="main"><?php echo db_to_html('查询会员积分卡激活情况');?></a></li>   
				<li><a href="stats_pageview.php" target="main"><?php echo db_to_html('PageView统计');?></a></li>  
            </ul>
        </div>
    </div>        
</div>
</body>
</html>
