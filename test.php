<?php
$time = microtime(true);
$loop = 100000;
$forData = array();
for($i=0, $n = $loop; $i<$n; $i++){
	$forData[] = $i;
}
$time1 = microtime(true);
echo 'For Time:'.($time1-$time).'<br>';

$time2 = microtime(true);
$whileData = array();
$i = 0;
while (true){
	$whileData[] = $i;
	$i++;
	if($i>$loop){
		break;
	}
}
$time3 = microtime(true);
echo 'While Time:'.($time3-$time2).'<br>';

$time4 = microtime(true);
$datas = array();
foreach($whileData as $val){
	$datas[] = $val;
	time_nanosleep(0,1);	
}
$time5 = microtime(true);
echo 'Foreach Time:'.($time5-$time4);
exit;
/* 
final class listFlights extends Flights{
	public function __construct(){
		parent::__construct();
	}
}

$B = new Guests();
echo '<pre>';
//print_r($B->getList());

print_r($B->getPageList());
echo '</pre>';
echo '<br>done.';
exit; */
?>