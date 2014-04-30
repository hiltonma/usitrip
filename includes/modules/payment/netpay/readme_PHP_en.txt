===============================================================
chinapay online payment interface demo (PHP) instructions
===============================================================

1)The interface needs two PHP expanded libraries' support ¡ª¡ªmcrypt and bcmath, therefore, please confirm that the two libraries are installed and enabled. 
  If you are virtual host user, generally, host provider will install the two libraries. You can use phpinfo() to check the PHP configuration information, if they are not installed, please contact your host business to resolve.

2)File description: netpaylclient.php provides library files for chinapay, which includes the signature and signature verified functions. Please develop it refering to the merchant manual.

3)After you applying for merId in CP, you will get two testing key files whose suffix called .key, among them, beginning with Mer is private key file, beginning with Pg is public key file.
  Put the two key files into your program directory(other position also do, but should make corresponding configuration according to actual position). 
  Open the configuration file, modify key files' path according to your situation.