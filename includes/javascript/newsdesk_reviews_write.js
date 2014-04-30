<script language="javascript"><!--
function checkForm() {
var error = 0;
var error_message = "<?php echo JS_ERROR; ?>";
var review = document.newsdesk_reviews_write.review.value;

if (review.length < <?php echo REVIEW_TEXT_MIN_LENGTH; ?>) {
	error_message = error_message + "<?php echo JS_REVIEW_TEXT; ?>";
	error = 1;
}

if ((document.newsdesk_reviews_write.rating[0].checked) || (document.newsdesk_reviews_write.rating[1].checked) || (document.newsdesk_reviews_write.rating[2].checked) || (document.newsdesk_reviews_write.rating[3].checked) || (document.newsdesk_reviews_write.rating[4].checked)) {
} else {
	error_message = error_message + "<?php echo JS_REVIEW_RATING; ?>";
	error = 1;
}

if (error == 1) {
	alert(error_message);
	return false;
} else {
	return true;
}
}
//--></script>
