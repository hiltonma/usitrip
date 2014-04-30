<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET ?>" />
<title><?php echo db_to_html('走四方旅游体验师大赛')?></title>
<meta content="<?php echo db_to_html('走四方|走四方网|走四方旅游|旅游走四方|美东|美西|夏威夷|特色美国游|加拿大|拉丁美洲|欧洲|日本|酒店预订|目的地指南|结伴同游') ?>" name="keywords"/>
<meta content="<?php echo db_to_html('走四方网为您提供美国旅游与加拿大旅游套餐，绝对超值，游遍全美国与加拿大。我们的顾客可以享受种类繁多，内容丰富，度身为您定制的旅游套餐，以及我们优质的服务。')?>" name="description"/>
<style type="text/css">
body,p{ margin:0; padding:0;}
img{border:0;}
body{ background: url(image/trip_player/daren_bg.png) no-repeat top center #b7e3f6; font-size:12px; font-family:"瀹嬩綋",Tahoma,Arial,Helvetica,sans-serif; color:#777;}
.darenAll{ width:950px; margin:0 auto;}
.darenTop1{ width:950px; height:67px;}
.darenTop2{ background:url(image/trip_player/style1_02.jpg) no-repeat top left; width:950px; height:72px; padding-top:160px;}
.darenTop2 p{ font-size:24px; color:#d8bf9e; font-weight:bold;  margin-left:340px; }
.darenTop2 p span{ width:72px; height:44px; display:block; text-align:center; float:left; background:url(image/trip_player/darends_bg.png) no-repeat top left; color:#FFFFFF; font-size:48px; line-height:46px; }
.darenTop2 p b{ display:block; float:left; line-height:48px;}
.darenTop2 p img{ display:block; float:left; line-height:44px;}
.darenTop3{ width:950px; height:354px;}
.darendsJs{ width:910px; text-align:right; padding-top:0px; font-size:16px; color:#000000; padding-right:40px;}


</style>
</head>
<script src="jquery-1.3.2/jquery-1.4.2.min.js"></script>
<script language="JavaScript">
function _fresh()
{
 var endtime=new Date("2011/10/12,12:00:00");
 var nowtime = new Date();
 var leftsecond=parseInt((endtime.getTime()-nowtime.getTime())/1000);
 __d=parseInt(leftsecond/3600/24);
 __h=parseInt((leftsecond/3600)%24);
 __m=parseInt((leftsecond/60)%60);
 __s=parseInt(leftsecond%60);
 jQuery("#days").html(__d);
 jQuery("#hours").html(__h);
 jQuery("#minutes").html(__m);
 jQuery("#seconds").html(__s);
 if(leftsecond<=0){
 	jQuery("#time").html("<?php echo db_to_html('活动结束') ?>");
 	clearInterval(sh);
 }
}
_fresh()
var sh;
sh=setInterval(_fresh,1000);

function addFavorites(sURL, sTitle){    
    if (document.all){    
        try{    
            window.external.addFavorite(sURL,sTitle);    
        }catch(e){    
            alert( "<?php echo db_to_html('加入收藏失败，请使用Ctrl+D进行添加！') ?>" );    
        }    
            
    }else if (window.sidebar){    
        window.sidebar.addPanel(sTitle, sURL, "");    
     }else{    
        alert( "<?php echo db_to_html('加入收藏失败，请使用Ctrl+D进行添加！') ?>" );    
    }    
} 
</script>
	
<body>
<div id="time"></div>
<div class="darenAll">
<div class="darenTop1"><img src="image/trip_player/style1_01.png" alt="<?php echo db_to_html('走四方网，走四方旅游体验师大赛')?>" title="<?php echo db_to_html('走四方网，走四方旅游体验师大赛')?>" width="950" height="67"/></div>
<div class="darenTop2" title="<?php echo db_to_html('走四方网，走四方旅游体验师大赛，Ipad2大奖等你拿，更有机会赢取$1000美国之旅！')?>">
  <p><span id='days'>00</span> <b><?php echo db_to_html('天')?></b> 
  <span class="darenShi" id="hours">00</span><img src="image/trip_player/darends_f.png" alt="时" /> 
  <span id="minutes">00</span><img src="image/trip_player/darends_f.png" alt="分" /> 
  <span id="seconds">00</span> </p>
</div>
<div class="darenTop3"><img src="image/trip_player/style1_03.jpg" alt="<?php echo db_to_html('走四方网，走四方旅游体验师大赛，Ipad2大奖等你拿，更有机会赢取$1000美国之旅！')?>" title="<?php echo db_to_html('走四方网，走四方旅游体验师大赛，Ipad2大奖等你拿，更有机会赢取$1000美国之旅！')?>" width="950" height="354" usemap="#Map" />
  <map name="Map" id="Map">
    <area shape="rect" coords="810,230,933,264" href="javascript:addFavorites(this.location.href, '<?php echo db_to_html('走四方旅游体验师大赛') ?>');" alt="<?php echo db_to_html('收藏')?>" title="<?php echo db_to_html('收藏')?>"  />
  </map>
  <div class="darendsJs"><p><?php echo db_to_html('活动开始：2011年10月12日') ?></p></div>
</div>
<!--<div class="darendsJs"><p>以上各项奖品以实物为准，最终解释权归走四方网所有</p></div>
</div>-->
<a class="bshareDiv" href="http://www.bshare.cn/share"><?php echo db_to_html('分享按钮')?></a>
<script language="javascript" type="text/javascript" src="http://static.bshare.cn/b/button.js#uuid=929efbd7-68d3-4cc2-b113-24c3cb6d957f&amp;style=3&amp;fs=4&amp;textcolor=#fff&amp;bgcolor=#F60&amp;text=<?php echo db_to_html('分享到...')?>"></script>
<script type="text/javascript">
jQuery(function(){
if(typeof(bShare)!='undefined'){
bShare.addEntry({
     title: "#<?php echo db_to_html('走四方旅游体验师')?>#",
     summary: "<?php echo db_to_html('大赛即将举行，@走四方网 微博即可参与，IPAD2大奖等你拿，更有机会免费畅游美国。')?>"
}) 
}
});
</script>
</body>
</html>
