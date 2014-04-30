<?php
$is_js_file = false;	/* 如果为false将以php的格式一行一行列到页面 */
if($base_php_self == "javascript.php"){
	$is_js_file = true;
}
if($is_js_file==false){
?>
<script type="text/javascript"><!--

<?php
}
?>

function emailCheck(emailStr) {

var emailPat=/^(.+)@(.+)$/

var specialChars="\\(\\)<>@,;:\\\\\\\"\\.\\[\\]"


var validChars="\[^\\s" + specialChars + "\]"


var quotedUser="(\"[^\"]*\")"


var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/


var atom=validChars + '+'


var word="(" + atom + "|" + quotedUser + ")"

var userPat=new RegExp("^" + word + "(\\." + word + ")*$")


var domainPat=new RegExp("^" + atom + "(\\." + atom +")*$")


var matchArray=emailStr.match(emailPat)
if (matchArray==null) {
 
	alert("Please enter valid email address (check @ and .'s)")
	return false
}

var user=matchArray[1]
var domain=matchArray[2]


if (user.match(userPat)==null) {
    
    alert("The username doesn't seem to be valid.")
    return false
}


var IPArray=domain.match(ipDomainPat)
if (IPArray!=null) {
    /*  this is an IP address */
	  for (var i=1;i<=4;i++) {
	    if (IPArray[i]>255) {
	        alert("Destination IP address is invalid!")
		return false
	    }
    }
    return true
}


var domainArray=domain.match(domainPat)
if (domainArray==null) {
	alert("The domain name doesn't seem to be valid.")
    return false
}


var atomPat=new RegExp(atom,"g")
var domArr=domain.match(atomPat)
var len=domArr.length
if (domArr[domArr.length-1].length<2 || 
    domArr[domArr.length-1].length>3) {
   alert("The address must end in a three-letter domain, or two letter country.")
   return false
}

if (len<2) {
   var errStr="This address is missing a hostname!"
   alert(errStr)
   return false
}

return true;
}



function favarite_stores(s_id)
{
options = "top=0,left=0,toolbar=0,status=0,menubar=0,scrollbars=1,locationbar=1,resizable=1,width=400,height=160,screenX=25,screenY=25,top=25,left=25'"; 
storeURL = "<?php echo HTTP_SERVER;?>/favorites_add.php?s_id=" + s_id
newwindow=window.open(storeURL,"storewindow", options);
}

function favarite_coupons(coupon_id)
{
options = "top=0,left=0,toolbar=0,status=0,menubar=0,scrollbars=1,locationbar=1,resizable=1,width=400,height=160,screenX=25,screenY=25,top=25,left=25'"; 
catURL = "<?php echo HTTP_SERVER;?>/favorites_add.php?coupon_id=" + coupon_id
newwindow=window.open(catURL,"catwindow", options); 
}


function tell_a_frd_coupons(coupon_id)
{
options = "top=0,left=0,toolbar=0,status=0,menubar=0,scrollbars=1,locationbar=1,resizable=1,width=550,height=550";  
strURL = "<?php echo HTTP_SERVER;?>/send_coupons_friend.php?coupon_id=" + coupon_id
newwindow=window.open(strURL,"tellwindow", options);
}


function feedback_coupons(coupon_id)
{
options = "top=0,left=0,toolbar=0,status=0,menubar=0,scrollbars=1,locationbar=1,resizable=1,width=500,height=450"; 
strURL = "<?php echo HTTP_SERVER;?>/coupons_feedback.php?coupon_id=" + coupon_id
newwindow=window.open(strURL,"feedwindow", options);
}

function popWindow(varURL) {
	options = "toolbar=0,status=0,menubar=0,scrollbars=0,resizable=1,width=350,height=270"; 
	newwindow=window.open(varURL,"befinitwindow", options);
}

<?php
if($is_js_file==false){
?>
//--></script>
<?php
}
?>