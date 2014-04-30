jQuery(document).ready(function(e) {
	var gbpx = 0;
	var x = jQuery('div.news').offset();
	jQuery('#sina_btn').css('left',jQuery('div.news').width()+x.left+'px');
	jQuery('#bg').pan({fps: 30, speed: 2, dir: 'left', depth: 10});
});
window.onresize = function(){
	var x = jQuery('div.content').offset();
	jQuery('#sina_btn').css('left',jQuery('div.news').width()+x.left+'px');
}
try {
  document.execCommand("BackgroundImageCache", false, true);
} catch(e) {}

