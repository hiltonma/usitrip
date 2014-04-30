<?php
$geographyData[]=array('cid'=>'25','name'=>db_to_html('美 东'));
$geographyData[]=array('cid'=>'24','name'=>db_to_html('美 西'));
$geographyData[]=array('cid'=>'33','name'=>db_to_html('夏威夷'));
$geographyData[]=array('cid'=>'196','name'=>db_to_html('特色美国游'));
$geographyData[]=array('cid'=>'54','name'=>db_to_html('加拿大'));
$geographyData[]=array('cid'=>'208','name'=>db_to_html('拉丁美洲'));
$geographyData[]=array('cid'=>'157','name'=>db_to_html('欧 洲'));
$geographyData[]=array('cid'=>'193','name'=>db_to_html('日 本'));
?>
<div class="title titleBig">
        <b></b><span></span>
        <h3><?php echo db_to_html('筛选搜索结果')?></h3>
      </div>
      <dl class="place placeBot">
        <dt><?php echo db_to_html('按地理位置查看：')?></dt>
<?php
foreach($geographyData as $geography){
	$class = $cid==$geography['cid']?' class="selected"':'';
?>
        <dd><a href="<?php echo makesearchUrl('cid',$geography['cid']);?>"<?php echo $class?>><?php echo $geography['name']?></a></dd>
<?}?>
</dl>