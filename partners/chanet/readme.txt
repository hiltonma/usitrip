
爱德威CHANet成果网CPS客户接口说明

一、广告主提供给CHANet的活动页面链接：

http://qa.usitrip.com/?partners_id=[SID]&partners_source=chanet

参数说明：
partners_id	必选项	返回CHANet的渠道信息[SID]
partners_source	可选项	广告主可自行判断来源来自CHANet

二、广告主提供数据接口地址，例如：

http://qa.usitrip.com/partners_info.php?userName=chanet&pwd=12ae56fbaad00b02&startdate=2010-07-30&enddate=2010-09-30
http://qa.usitrip.com/partners_info.php?userName=chanet&pwd=12ae56fbaad00b02&type=html
http://qa.usitrip.com/partners_info.php?userName=chanet&pwd=12ae56fbaad00b02&orders_status=all

OR
http://qa.usitrip.com/partners_info.php?userName=chanet&pwd=12ae56fbaad00b02&startdate=2010-07-30&enddate=2010-09-30&type=html&orders_status=all

参数说明：
参数名称	参数说明	备注
userName	账号		chanet
pwd			密码		12ae56fbaad00b02
startdate	起始时间	2010-07-30
enddate		结束时间	2010-09-30
type 		类型		html
orders_status 订单状态 	all 如果没有此参数将列出已经完成的订单，即已出行的。

startdate和enddate是可选项


接口数据格式，例如：

数据格式：订单时间,SID,订单ID,产品分类,产品数量,销售价格

表头：订单时间|成果网唯一标识SID |订单号|产品分类|产品数量|销售价格
分隔符：字段与字段时间用 ”\t” 分隔，行与行之间用 ”\n” 分隔
时间：2008-07-16 22:07:05
唯一标识：数字

示例：
2008-07-16 22:07:05\t 1234567891 \t 02143331 \t goods1 \t 2 \t 1000.00
2008-07-16 22:07:05\t 1234567892 \t 02143332 \t goods1 \t 1 \t 1000.00
2008-07-16 22:07:05\t 1234567893 \t 02143333 \t goods1 \t 1 \t 1000.00

2010-09-10 15:56:56	1	17719	纽约;	1	357.70
2010-09-12 23:12:49	1	17720	洛杉矶;夏威夷旅游;温哥华;	3	1431.78

注意：上述网址qa.usitrip.com是测试网址，在正式运营时请用tw.usitrip.com





