<?php
/*
 * id#404 edit 404.php 2011/3/4 jason
 * */

$sql = "select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price,(
p.products_price - s.specials_new_products_price) AS cz from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by s.specials_date_added DESC limit 0,5";
?>
<script language="javascript">  
var i=6;  
function clock(){     
        //document.title="本窗口将在" i "秒后自动关闭!";  
        jQuery("#time").html(i);
        if(i>0) {
                setTimeout("clock();",1000);  
        } else {   
                //self.close();  
                window.location.href="/";  // 跳转到其他页面  
        }  
        i--;  
}  
 clock();


 </script>  
    <div class="noPage">
    <?php ob_start()?>
    <div id="Error404">
       <ul>
       		<li><h2>抱歉！找不到你要访问的页面......</h2></li>
            <li><p>您正在搜索的页面可能已经删除、更名或暂时不可用。<br />
确保浏览器的地址栏中显示的网站地址的拼写和格式正确无误。</p></li>
            <li><span>我们将在<em id="time">6</em>秒之后自动返回<a href="/">走四方网首页</a>!</span></li>
            <li> 您可以：稍候重试或联系<a href="<?php echo tep_href_link('contact_us.php','','SSL');?>">走四方网客服</a></li>
       </ul>
    </div>
        <?php
		echo db_to_html(ob_get_clean()); 
        
		// 原来的404代码 里面有特价行程 目前不需要 start {
			/*
		<div class="top404">
            <h1><?php echo db_to_html('对不起！您请求的页面暂时无法打开');?></h1>
            <p><?php echo db_to_html('您可以'); echo db_to_html('：稍后重试 或');?> <a href="/<?php echo tep_db_output('contact_us.php');?>"><?php echo db_to_html("联系走四方网客服"); ?></a></p>
            <p><?php echo db_to_html('页面将在'); ?><b id="time">10</b><?php echo db_to_html('秒后自动跳转到'); ?><a href="/"><?php echo db_to_html('走四方网首页'); ?></a></p>
        </div>
    
    
        <div class="title titleSmall"><b></b><span></span><h3><?php echo db_to_html('特价行程'); ?></h3></div>
        
        <ul class="specialRoute">
        <?php
          $result = tep_db_query($sql);
          while ($row = tep_db_fetch_array($result)) {
        ?>
            <li class="first">
                <div class="pic"><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $row['products_id']);?> title="<?php echo db_to_html(tep_db_output($row['products_name']));?> style="cursor:pointer" "><img src="/images/<?php echo db_to_html(tep_db_output($row['products_image']));?>" alt="<?php echo db_to_html(tep_db_output($row['products_name']));?>" title="<?php echo db_to_html(tep_db_output($row['products_name']));?>"/></a></div>
                <div class="name">
                    <h2><a href="<?php echo tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $row['products_id']);?>"><?php echo db_to_html(tep_db_output($row['products_name']));?></a></h2>
                    <h3><?php echo db_to_html(tep_db_output('**06-10年度走四方最畅销推荐行程**'));?></h3>
                </div>
                <div class="price"><?php echo '$'.tep_db_output(Substr($row['products_price'],0,strripos($row['products_price'],'.')));?>
                <b><?php echo '$'.tep_db_output(Substr($row['specials_new_products_price'],0,strripos($row['specials_new_products_price'],'.')));?></b><?php echo db_to_html('省').'$'.tep_db_output(Substr($row['cz'],0,strripos($row['cz'],'.'))).'.00';?></div>
            </li>
            <?php }?>
        </ul>
        */
		// 注释结束 end } ?>
    </div>



