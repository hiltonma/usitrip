<?php
/*
 * Created on 06/11/2006
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

list($start_m, $start_s) = explode(' ', microtime());
$start = $start_m + $start_s;

send_google_req('http://'.$_SERVER['HTTP_HOST'].'/googlecheckout/responsehandler.php');
list($end_m, $end_s) = explode(' ', microtime());
$end = $end_m + $end_s;

echo "\n\nTime to response: ". ($end-$start) ." segs"; 
 
 function send_google_req($url) {
    // Get the curl session object
    $session = curl_init($url);
    $merid = '212585145030587';
    $merkey = 'RP37Va7ASnNPGKdBx1z5cg';

    $header_string_1 = "Authorization: Basic ".base64_encode($merid.':'.$merkey);
    $header_string_2 = "Content-Type: application/xml";
    $header_string_3 = "Accept: application/xml";
    
    
    // here put the xml u want to emulate! u can take the ones from catalog/googlecheckout/response_message.log
    $postargs = '<?xml version="1.0" encoding="UTF-8"?>
<new-order-notification xmlns="http://checkout.google.com/schema/2" serial-number="85f54628-538a-44fc-8605-ae62364f6c71">
  <timestamp>2006-03-17T12:20:46.137Z</timestamp>
  <google-order-number>841171949013218</google-order-number>
  <shopping-cart>
    <cart-expiration>
      <good-until-date>2006-12-31T23:59:59</good-until-date>
    </cart-expiration>
    <items>
      <item>
        <quantity>1</quantity>
        <unit-price currency="USD">35.00</unit-price>
        <item-name>Dry Food Pack AA1453</item-name>
        <item-description>A pack of highly nutritious dried food for emergency - store in your garage for up to one year!!</item-description>
        <tax-table-selector>food</tax-table-selector>
      </item>
      <item>
        <quantity>1</quantity>
        <unit-price currency="USD">178.00</unit-price>
        <item-name>MegaSound 2GB MP3 Player</item-name>
        <item-description>Portable MP3 player - stores 500 songs, easy-to-use interface, color display</item-description>
        <merchant-private-item-data>
          <my-data>
            <weight>1.5</weight>
            <color>white</color>
            <item-note>Popular item: Check inventory and order more if needed</item-note>
          </my-data>
        </merchant-private-item-data>
      </item>
    </items>
  </shopping-cart>
  <buyer-shipping-address>
    <email>knbw@gmail.com</email>
    <company-name />
    <contact-name>Knikki Beckwell</contact-name>
    <phone />
    <fax />
    <address1>1250 Shoreline Blvd</address1>
    <address2 />
    <country-code>US</country-code>
    <city>Mountain View</city>
    <region>CA</region>
    <postal-code>94043</postal-code>
  </buyer-shipping-address>
  <buyer-billing-address>
    <email>knbw@gmail.com</email>
    <company-name />
    <contact-name>Knikki Beckwell</contact-name>
    <phone />
    <fax />
    <address1>1250 Shoreline Blvd</address1>
    <address2 />
    <country-code>US</country-code>
    <city>Mountain View</city>
    <region>CA</region>
    <postal-code>94043</postal-code>
  </buyer-billing-address>
  <buyer-marketing-preferences>
    <email-allowed>false</email-allowed>
  </buyer-marketing-preferences>
  <order-adjustment>
    <merchant-calculation-successful>true</merchant-calculation-successful>
    <merchant-codes>
      <coupon-adjustment>
        <applied-amount currency="USD">5.00</applied-amount>
        <code>FirstVisitCoupon</code>
        <calculated-amount currency="USD">5.00</calculated-amount>
        <message>Congratulations! You saved $5.00 on your first visit!</message>
      </coupon-adjustment>
      <gift-certificate-adjustment>
        <applied-amount currency="USD">10.00</applied-amount>
        <code>GiftCert012345</code>
        <calculated-amount currency="USD">10.00</calculated-amount>
        <message>You used your Gift Certificate!</message>
      </gift-certificate-adjustment>
    </merchant-codes>
    <shipping>
      <merchant-calculated-shipping-adjustment>
        <shipping-name>SuperShip</shipping-name>
        <shipping-cost currency="USD">13.00</shipping-cost>
      </merchant-calculated-shipping-adjustment>
    </shipping>
    <total-tax currency="USD">15.06</total-tax>
    <adjustment-total currency="USD">13.06</adjustment-total>
  </order-adjustment>
  <order-total currency="USD">226.06</order-total>
  <fulfillment-order-state>NEW</fulfillment-order-state>
  <financial-order-state>CHARGEABLE</financial-order-state>
  <buyer-id>419797746651146</buyer-id>
</new-order-notification>';

    //fwrite($message_log, sprintf("\r\n%s %s %s\n",$header_string_1, $header_string_2, $header_string_3));
    // Set the POST options.
    curl_setopt($session, CURLOPT_POST, true);
    curl_setopt($session, CURLOPT_HTTPHEADER, array($header_string_1, $header_string_2, $header_string_3));
    curl_setopt($session, CURLOPT_POSTFIELDS, $postargs);
    curl_setopt($session, CURLOPT_HEADER, true);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    // set to valir ssl.crt certification
    //curl_setopt($session, CURLOPT_CAINFO, $DOCUMENT_ROOT."/ssl/ssl.crt");

    // Do the POST and then close the session
   echo  $response = curl_exec($session);
exit;
    if (curl_errno($session)) {
        echo (curl_error($session));
    } else {
        curl_close($session);
    }

    //fwrite($message_log, sprintf("\r\n%s\n",$response));
    
    // Get HTTP Status code from the response
    $status_code = array();
    
    echo "<xmp>";
    print_r($response);
    preg_match('/\d\d\d/', $response, $status_code);
    
    //fwrite($message_log, sprintf("\r\n%s\n",$status_code[0]));
    
    
    // Check for errors
    switch( $status_code[0] ) {
      case 200:
      // Success
        break;
      case 503:
        echo ('Error 503: Service unavailable. An internal problem prevented us from returning data to you.');
          break;
      case 403:
        echo ('Error 403: Forbidden. You do not have permission to access this resource, or are over your rate limit.');
        break;
      case 400:
        echo ('Error 400: Bad request. The parameters passed to the service did not match as expected. The exact error is returned in the XML response.');
        break;
      default:
        echo ('Error :' . $status_code[0]);
    }
  }
 
?>


<?php
/*
 * Created on 02/01/2007
 * @author: Ropu
 * 
 * Script to emulate a google checkout request to merchant response handler
 * Expected answer: A valid XML in response to your posted xml request.
 *                  No PHP errors warnings or any other string. 
 * 
 * CURL must be installed
 * 
 * README:
 * Configure the parameters.
 * Place this script in your website.
 * Point your browser to this script.
 * Analize the response.
 

// Responsehandler.php URL
$url = 'http://www.usitrip.com/googlecheckout/responsehandler.php';
// Your Merchant ID
$merid = '212585145030587';
// Your Merchant Key
$merkey = 'RP37Va7ASnNPGKdBx1z5cg';
// Here put the xml u want to emulate! You can take the ones from googlecheckout/response_message.log
$postargs = '<xml replace!>';


// No need to touch anything below here.
list($start_m, $start_s) = explode(' ', microtime());
$start = $start_m + $start_s;
send_google_req($url, $merid, $merkey, $postargs);
list($end_m, $end_s) = explode(' ', microtime());
$end = $end_m + $end_s;

echo "\n\nTime to response: ". ($end-$start) ." segs"; 
echo "\n\nNote: This script MUST response in less than 3 sec. so GC srv doesn't timeout.'";
function send_google_req($url, $merid, $merkey, $postargs) {
  // Get the curl session object
  $session = curl_init($url);
  $headers = array();
  $headers[] = "Authorization: Basic ".base64_encode($merid.':'.$merkey);
  $headers[] = "Content-Type: application/xml";
  $headers[] = "Accept: application/xml";


  // Set the POST options.
  curl_setopt($session, CURLOPT_POST, true);
  curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($session, CURLOPT_POSTFIELDS, $postargs);
  curl_setopt($session, CURLOPT_HEADER, true);
  curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
  // Set to valir ssl.crt certification
//curl_setopt($session, CURLOPT_CAINFO, "C:\\Program Files\\xampp\\apache\\conf\\ssl.crt\\ca-bundle.crt");

  // Do the POST and then close the session
  $response = curl_exec($session);
	if (curl_errno($session)) {
		echo (curl_error($session));
	} else {
	    curl_close($session);
	}

  // Get HTTP Status code from the response
  $status_code = array();
  
  echo "<xmp>";
  print_r($response);
}
*/
?>