<?php
!defined('_MODE_KEY') && exit('Access error!');

$tid = intval($_GET['tid']);
$expWhere = !$isExpertsSelf ? " and b.is_draft='0'":'';
	

if($tid){
	$sql = "SELECT b.aid,b.tid, b.uid, a.name, b.title, b.hits,b.`time` ,b.is_pic,b.is_draft
		FROM experts_writings b,experts_writings_type a
		where b.uid =  '{$uid}' and b.tid = a.tid and b.tid='{$tid}'{$expWhere}
		ORDER BY b.time DESC";
	$countsql = "SELECT count(b.aid) as rows FROM experts_writings b,experts_writings_type a
		where b.uid =  '{$uid}' and b.tid = a.tid and b.tid='{$tid}'{$expWhere}";
}else{
	$sql = "SELECT b.aid,b.tid, b.uid, a.name, b.title, b.hits,b.`time` ,b.is_pic,b.is_draft
		FROM experts_writings_type a
		INNER JOIN experts_writings b ON a.tid = b.tid
		INNER JOIN (
		SELECT tid, MAX(  `time` ) AS mtime
		FROM experts_writings
		WHERE uid =  '{$uid}'
		GROUP BY tid
		)c ON a.tid = c.tid
		WHERE b.uid =  '{$uid}' and a.uid=b.uid{$expWhere}
		ORDER BY mtime DESC ,  `time` DESC , a.tid";
	$countsql = "SELECT count(b.aid) as rows FROM experts_writings_type a
		INNER JOIN experts_writings b ON a.tid = b.tid
		INNER JOIN (
		SELECT tid, MAX(  `time` ) AS mtime
		FROM experts_writings
		WHERE uid =  '{$uid}'
		GROUP BY tid
		)c ON a.tid = c.tid
		WHERE b.uid =  '{$uid}' and a.uid=b.uid{$expWhere}";
}
$query = tep_db_query($countsql);
$query = tep_db_fetch_array($query);
$total=(int)$query["rows"];
$db_perpage = 15;
$pages=makePager($total,'page',$db_perpage,false);
$limit=" LIMIT ".($page-1)*$db_perpage.",$db_perpage;";

$query = tep_db_query($sql.$limit);
$expertsWritings = array();
$thisTypeName = '';
while ($rt = tep_db_fetch_array($query)){
	$rt['time'] = date('m/d/Y',strtotime($rt['time']));
	if($tid && !$thisTypeName){
		$thisTypeName = $rt['name'];	
	}
	$expertsWritings[]=$rt;
}
if($tid && !$thisTypeName){
	$thisTypeName = tep_db_get_one("SELECT name FROM experts_writings_type WHERE uid =  '{$uid}' and tid='{$tid}'");	
	$thisTypeName = $thisTypeName['name'];
}
if($tid && $thisTypeName!=''){
	$crumb['Url'] = tep_href_link($baseUrl,"uid={$uid}&mod=writings&tid={$tid}");

	$crumb['Title'] = $thisTypeName;
	$crumbData[] = $crumb;	
}
?>