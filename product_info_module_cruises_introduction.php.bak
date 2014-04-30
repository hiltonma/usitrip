<?php
//邮轮团的邮轮介绍
if($isCruises!=true){
	die();
}

//$cruisesData;
//print_vars($cruisesData);
//exit;
//邮轮介绍
$attributeSql = tep_db_query('SELECT * FROM `cruises_content_attribute_value` ccav, `cruises_content_attribute` cca WHERE cca.attribute_id=ccav.attribute_id AND ccav.cruises_id='.(int)$cruisesData['cruises_id'].' Group BY ccav.attribute_id  ORDER BY `sort_id` ASC ');
$c_attribute = false;
while($attributeRows = tep_db_fetch_array($attributeSql)){
	$c_attribute[] = $attributeRows;
}

//取得图片信息
$cruisesImages = getCruisesImages((int)$cruisesData['cruises_id'], 'cruises',(int)$cruisesData['cruises_id']);

//取得所有甲板图片和客舱图片
$deckImages = false;
$cabinsImages = false;
foreach((array)$cruisesData['cabins'] as $key => $val){
	foreach((array)$val['images'] as $im => $img){
		$cabinsImages[] = $img;
	}
	foreach((array)$val['decks'] as $k=>$v){
		foreach((array)$val['decks'][$k]['images'] as $_i => $_v){
			$deckImages[] = $val['decks'][$k]['images'][$_i];
		}
	}
}

ob_start();

if(is_array($cruisesImages)){
	$cCount = sizeof($cruisesImages);
?>
	<div class="scfj">
      <div class="thumb_bg">
		  <div class="imgtxtbox">
			<span class="imgtxt-numC"></span>
			<div class="imgtxtC"></div>
		  </div>
		  <div class="undis">
				<?php foreach($cruisesImages as $n => $val){?>
				<div bigsrc="<?= $cruisesImages[$n]['images_url_thumb']?>">
				<h3><?= $cruisesImages[$n]['images_title']?></h3> 
				<p><?= nl2br(tep_db_output($cruisesImages[$n]['images_content']));?></p>
				<p class="ckdt"><a target="_blank" href="<?= $cruisesImages[$n]['images_url']?>">查看大图</a></p>
				</div>			
              <?php }?>
		  </div>
		</div>
	  <div class="scfj_show">
	    <div class="img_show"><img src="<?= $cruisesImages[0]['images_url_thumb']?>" /><span class="imgloading"></span></div>
		<div class="arrow_box">
		  <a href="#" class="previous" title="上一张">上一张</a>
		  <a href="#" class="next" title="下一张">下一张</a>
		</div>
		<div class="cruisesBjTl"></div>
		<div class="cruisesBjTr"></div>
		<div class="cruisesBjBr"></div>
		<div class="cruisesBjBl"></div>
		</div>
      </div>

<?php
}
//邮轮介绍
if(is_array($c_attribute)){
?>
  <div class="cruisesCon">
	  <table cellspacing="0" cellpadding="0" border="0">
		  <tbody>
		  <?php foreach($c_attribute as $attribute){?>
		  <tr><td class="cruisesTabt"><?= $attribute['attribute_name']?>：</td><td class="cruisesTabc"><?= nl2br(tep_db_output($attribute['value_content']));?></td></tr>
		  <?php }?>
	  </tbody></table>
  </div>
<?php }?>
<?php
if(is_array($deckImages)){
?> 	
	  <div class="cruisesConBt"><a name="anchorDeck"></a><h3><?= $cruisesData['cruises_name']?>甲板详情</h3></div>
	  <div class="scfj">
      <div class="thumb_bg">
		  <div class="imgtxtbox">
			<span class="imgtxt-numC"></span>
			<div class="imgtxtC"></div>
		  </div>
		  <div class="undis">
				<?php foreach($deckImages as $n => $val){?>
				<div bigsrc="<?= $deckImages[$n]['images_url_thumb']?>">
				<h3><?= $deckImages[$n]['images_title']?></h3> 
				<p><?= nl2br(tep_db_output($deckImages[$n]['images_content']));?></p>
				<p class="ckdt"><a target="_blank" href="<?= $deckImages[$n]['images_url']?>">查看大图</a></p>
				</div>			
              <?php }?>
		  </div>
		</div>
	  <div class="scfj_show">
	    <div class="img_show"><img src="<?= $deckImages[0]['images_url_thumb']?>" /><span class="imgloading"></span></div>
		<div class="arrow_box">
		  <a href="#" class="previous" title="上一张">上一张</a>
		  <a href="#" class="next" title="下一张">下一张</a>
		</div>
		<div class="cruisesBjTl"></div>
		<div class="cruisesBjTr"></div>
		<div class="cruisesBjBr"></div>
		<div class="cruisesBjBl"></div>
		</div>
      </div>
<?php }?>  
  
  
<?php
if(is_array($cabinsImages)){
?> 	
	  <div class="cruisesConBt"><a name="anchorCabin"></a><h3><?= $cruisesData['cruises_name']?>客舱详情</h3></div>
	  <div class="scfj">
      <div class="thumb_bg">
		  <div class="imgtxtbox">
			<span class="imgtxt-numC"></span>
			<div class="imgtxtC"></div>
		  </div>
		  <div class="undis">
				<?php foreach($cabinsImages as $n => $val){?>
				<div bigsrc="<?= $cabinsImages[$n]['images_url_thumb']?>">
				<h3><?= $cabinsImages[$n]['images_title']?></h3> 
				<p><?= nl2br(tep_db_output($cabinsImages[$n]['images_content']));?></p>
				<p class="ckdt"><a target="_blank" href="<?= $cabinsImages[$n]['images_url']?>">查看大图</a></p>
				</div>			
              <?php }?>
		  </div>
		</div>
	  <div class="scfj_show">
	    <div class="img_show"><img src="<?= $cabinsImages[0]['images_url_thumb']?>" /><span class="imgloading"></span></div>
		<div class="arrow_box">
		  <a href="#" class="previous" title="上一张">上一张</a>
		  <a href="#" class="next" title="下一张">下一张</a>
		</div>
		<div class="cruisesBjTl"></div>
		<div class="cruisesBjTr"></div>
		<div class="cruisesBjBr"></div>
		<div class="cruisesBjBl"></div>
		</div>
      </div>
<?php }?>

<script type="text/javascript">
jQuery(function(){
	jQuery('.scfj').each(function(){
		var me = jQuery(this);
		var a = me.find('div[bigsrc]');
		var len = a.length;
		var bigprev = me.find('.arrow_box .previous');
		var bignext = me.find('.arrow_box .next');
		var loading = me.find('.imgloading');
		if(len > 0){
			var imgbox = me.find('.img_show');
			var bigpic = me.find('.img_show img');		
			var cur = 0;
			var ing = false;
			
			var showLoading = function(){loading.show();};
			var hideLoading = function(){loading.hide();};
			
			function setBigPic(n){
				if(ing){return false;}
				showLoading();
				ing = true;
							
				var newImg = jQuery('<img />');				
				imgbox.append(newImg);					
				var appendedImg = imgbox.find('img:last');					
				appendedImg.load(function(){
					imgbox.find('img:first').remove();
					hideLoading();	
					ing = false;
				});					
				appendedImg.attr('src',a.eq(n).attr('bigsrc'));
				me.find('.imgtxt-numC').html('（<strong>'+(n+1)+'</strong>/'+len+'张）');
				me.find('.imgtxtC').html(a.eq(n).html());
			}
			
			bignext.click(function(){			
				cur++;
				if(cur == len){
					cur--;
					return false;
				}
				setBigPic(cur);			
				return false;
			});
			bigprev.click(function(){			
				cur--;
				if(cur < 0){
					cur++;
					return false;
				}
				setBigPic(cur);			
				return false;
			});
			setBigPic(0);
		}
	});
});
</script>

<?php
echo db_to_html(ob_get_clean());
?>