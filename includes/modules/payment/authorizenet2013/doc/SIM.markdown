Server Integration Method
=========================

Basic Overview
基本概况
--------------

The Authorize.Net PHP SDK includes classes that can speed up implementing
a Server Integration Method solution.
该Authorize.Net的PHP SDK中包含的类，可以加快实施
服务器整合。

Hosted Order/Receipt Page
主办订单/收据页
-------------------------

The AuthorizeNetSIM_Form class aims to make it easier to setup the hidden
fields necessary for creating a SIM experience. While it is not necessary
to use the AuthorizeNetSIM_Form class to implement SIM, it may be handy for
reference.
该AuthorizeNetSIM_Form类的目的是使其更容易安装的隐蔽
需要用于创建一个SIM经验字段。虽然这不是必要
使用AuthorizeNetSIM_Form类来实现SIM卡，它可以很方便的
参考。


The code below will generate a buy now button that leads to a hosted order page:
下面的代码会生成一个立即购买按钮，导致托管的订购页面：



<form method="post" action="https://test.authorize.net/gateway/transact.dll">
<?php
$amount = "9.99";
$fp_sequence = "123";
$time = time();

$fingerprint = AuthorizeNetSIM_Form::getFingerprint($api_login_id, $transaction_key, $amount, $fp_sequence, $time);
$sim = new AuthorizeNetSIM_Form(
    array(
    'x_amount'        => $amount,
    'x_fp_sequence'   => $fp_sequence,
    'x_fp_hash'       => $fingerprint,
    'x_fp_timestamp'  => $time,
    'x_relay_response'=> "FALSE",
    'x_login'         => $api_login_id,
    )
);
echo $sim->getHiddenFieldString();?>
<input type="submit" value="Buy Now">
</form>

Fingerprint Generation
指纹生成
----------------------

To generate the fingerprint needed for a SIM transaction call the getFingerprint method:
要生成所需的SIM卡交易的指纹调用getFingerprint方法：

$fingerprint = AuthorizeNetSIM_Form::getFingerprint($api_login_id, $transaction_key, $amount, $fp_sequence, $fp_timestamp);


Relay Response
返回页的响应设置
--------------

The PHP SDK includes a AuthorizeNetSIM class for handling a relay response from
Authorize.Net.
PHP的SDK包括一个AuthorizeNetSIM类处理从Authorize.Net一个响应返回。

To receive a relay response from Authorize.Net you can either configure the
url in the Merchant Interface or specify the url when submitting a transaction
with SIM using the "x_relay_url" field.
从Authorize.Net接收继电器的响应您可以配置
URL中的商户接口或提交一个事务时指定的URL
与使用“x_relay_url”字段中的SIM卡。


When a transaction occurs, Authorize.Net will post the transaction details to
this url. You can then craete a page on your server at a url such as
http://yourdomain.com/response_handler.php and execute any logic you want
when a transaction occurs. The AuthorizeNetSIM class makes it easy to verify
the transaction came from Authorize.Net and parse the response:
当交易发生时，Authorize.Net将发布有关交易细节
这个网址。然后，您可以在一个URL，例如craete一个页面在服务器上
http://yourdomain.com/response_handler.php并执行任何你想要的逻辑
当交易发生。该AuthorizeNetSIM类可以很容易地验证
交易来自Authorize.Net，并解析响应：


$response = new AuthorizeNetSIM;
if ($response->isAuthorizeNet())
{
  if ($response->approved)
  {
    // Activate magazine subscription
    magazine_subscription_activate($response->cust_id);
  }
}
