<?php
//把下面的字符串转成繁体和简体两个版本，并在同一页面输出来。

$string = '当从您降落于纽约机场的时候,我们热情的导游将在行李认领处(Baggage Claim)等候您的到来(我们提供的机场免费接应服务的有效时段是: 9：00AM-10：00PM)。之后，会把您带往下榻的酒店，您可以自行安排剩余的时间。(希望可以在当地时间下午3：00pm以后到达，办理酒店登记手续。
提供JFK、LGA和EWR 机场的接机服务。
为了保证您得到最好的服务,EWR机场是您最佳的选择。提早抵达的游客可享受纽约城市夜游服务。座位有限，请有意向参加的客人最好于下午3:30之前到达。（每人额外收费25美元）。
如果您提前到达机场，请在以下地点与导游汇合：
JFK - Terminal # 7 "Hudson News" newsstand
EWR - Terminal C Continental Airlines
LGA - Terminal B United Airlines

注意：由于免费机场接应服务数量大，人次多，所以地接社可能会将前后到达时间相近的贵宾一并接应，您可能会有10-15分钟的等候时间，敬请谅解。

我们每个预订单只提供行程第一天指定时间内在纽约机场的一次免费接机服务(第一天免费机场接应服务的有效时段是: 9:00am--10:00pm)，行程结束后统一安排送机(在指定时间外，如需接送机服务，请联系我们取得报价)。
如果当天航班临时晚点， 请立即致电告知我们，我们会尽量安排航班改变之后的机场接应，但如遇当天机场接应数量太大，我们则保留请您自行入住酒店的权利。


如果您的航班不在纽约三大机场(JFK、LGA和EWR)降落，或者您提前到达纽约不需要接机，参团当天您可以选择在中国城集合等待导游(预订此团时，请务必留言注明该要求)：
5:00pm 中国城 (Chinatown), 48 Bowery, New York, NY 10013';




echo "<p>简体中文版：".$string."</p>";
echo "<p><font color=red>繁体中文版：".iconv('big5','GBK',$string)."</font></p>";


?>