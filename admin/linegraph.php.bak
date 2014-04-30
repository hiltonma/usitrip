<?php
require('includes/application_top.php');
$extension = tep_banner_image_extension();
if(isset($_GET['type']) && $_GET['type'] == 'order'){
?>
<img src="images/graphs/duration_graph_<?php echo $_GET['duration_key']; ?>.<?php echo $extension; ?>">
<?php }else if(isset($_GET['type']) && $_GET['type'] == 'revenue'){ ?>
<img src="images/graphs/duration_graph_revenue_<?php echo $_GET['duration_key']; ?>.<?php echo $extension; ?>">
<?php } ?>