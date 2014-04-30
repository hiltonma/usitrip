<?php ob_start();?>
<?php echo tep_get_design_body_header(db_to_html('我们的优势')); ?>




<?php echo tep_get_design_body_footer();?>

<?php echo db_to_html(ob_get_clean());?>