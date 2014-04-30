<?php
//for hot-tours || panorama-tours.php
if(basename($PHP_SELF) == 'hot-tours.php' || basename($PHP_SELF) == 'panorama-tours.php' || basename($PHP_SELF) == 'usa-tours-info.php'){
?>
	<div class="guanggao2">
		<?php echo db_to_html('什么是达人？如何成为达人？达人的好处')?>
		<a href="<?php echo tep_href_link("create_account.php","", "SSL")?>"><img src="image/jifen.gif" /></a>
	</div>

<?php }?>