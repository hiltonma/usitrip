// JavaScript Document
var Mar = document.getElementById("banner");
var how_many_ads = 3
var now = new Date() 
var sec = now.getSeconds() 
var ad = sec % how_many_ads; 
ad +=1; 
 
if (ad==1){ 
url="http://www.sohu.com"; 
alt="ad1";  
banner="image/banner_1.jpg"; 
width="663"; 
height="134";
} 

if (ad==2) {   
url="http://www.sohu.com"; 
alt="ad2";  
banner="image/banner_2.jpg";  
width="663"; 
height="134"; 
} 
  
if (ad==3) { 
url="http://www.sohu.com"; 
alt="ad3"; 
banner="image/banner_3.jpg"; 
width="663"; 
height="134";
}

document.write('<center>'); 
document.write('<a href=\"' + url + '\" target=\"_blank\">'); 
document.write('<img src=\"' + banner + '\" ');
document.write(' width=' + width + ' height =' + height + ' '); 
document.write('alt=\"' + alt + '\" border=0 /></a><br>'); 
document.write('</center>'); 