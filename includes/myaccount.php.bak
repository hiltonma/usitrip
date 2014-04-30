<?php 
/***************************************************************************/
/*
 * Shri consultancy services
 * =======================================
 *
 * Description		: this page is for diaplaying images that is rated by users
 * 
 * Website			: http://www.shriconsultancy.com
 * Company			: Shri Consultancy
 * Email			: admin@shriconsultancy.com
 * Authour email	: bhavik.koradiya@shriconsultancy.com
*/
/*******************************************************************************/

include  "header1.php";



/*********************** display combo ******************/
	$select_image_combo = "";
	$select_video_combo = "";
	if($_POST['image_video_display']=="image" || $_COOKIE['image_video_display']=="image")
	{
		$select_image_combo = "selected";
	}
	elseif($_POST['image_video_display']=="video" || $_COOKIE['image_video_display']=="video")
	{
		$select_video_combo = "selected";
	}
	/*********************** display combo ******************/

?>
<table width="100%" cellpadding="2" cellspacing="2" border="0">
<tr>
	<td colspan="4" align="right">
	<form action="myaccount.php" method="post">
	<select name="image_video_display" onChange="this.form.submit()">
		<option value="">--Please Select--</option>
		<option value="image" <?php echo $select_image_combo; ?>/>Images Rating</option>
		<option value="video" <?php echo $select_video_combo; ?>/>Movies Rating</option>
	</select>
	</form>
	</td>
</tr>
	<?php
	$membername = mysql_query("select * from user where  userid = ".$bbuserid."");
	$membername_result = mysql_fetch_array($membername);
		
		
		
	$valalpha = $_REQUEST['valalpha'];
	if(!$_REQUEST['valalpha'])
	$valalpha="all";
	$alphaList="<a href='myaccount.php?valalpha=all'>All</a>&nbsp;|&nbsp;";
	for($i=65;$i<91;$i++)
	{
		if($i == 78)
		$alphaList.= "<br>";
		$alphaList.="<a href='myaccount.php?valalpha=".chr($i)."'>".chr($i)."</a>&nbsp;|&nbsp;";
	}
		
	if($_COOKIE['image_video_display']=="image")
	{
		$query="SELECT agr.imageid,agr.rating,agi.filename,agi.userid FROM adv_gallery_rate as agr, adv_gallery_images as agi where agr.userid = ".$bbuserid." and agr.imageid = agi.imageid ";
		if($valalpha && $valalpha!="all")
		$query.=" AND agi.filename like '".strtolower($valalpha)."%'";
		$query.=" ORDER by agi.imageid";
	}
	elseif($_COOKIE['image_video_display']=="video")
	{
		$query="SELECT agr.videoid,agr.rating,agi.filename,agi.userid FROM adv_gallery_rate_video as agr, adv_gallery_video as agi where agr.userid = ".$bbuserid." and agr.videoid = agi.videoid ";
		if($valalpha && $valalpha!="all")
		$query.=" AND agi.filename like '".strtolower($valalpha)."%'";
		$query.=" ORDER by agi.videoid";
	}
	
		$result= mysql_query($query); 
		$totalRecFound = mysql_num_rows($result); 
		$totalRecords=$totalRecFound;
		
		$noofpages = 10;
		
		$totalPages=ceil($totalRecords/$noofpages);
		$showingpage="Page :";
		
		if(!$_GET['pageno'])
		{
			$pageno=1;
			$initlimit=0;
		}
		else
		{
			$pageno = $_GET['pageno'];
			$initlimit=($pageno*$noofpages)-$noofpages;		
		}
		
		if($pageno < 6 )
			{
				$startpage = 1;
				if($pageno + 5  > $totalPages )
					{
						$endpage = $totalPages;
					}	
					else
					{
						$endpage = 10 ;
					}
			}	
		else
			{
				$startpage = $pageno - 5 ;
				if($pageno + 5  > $totalPages )
					{
						$endpage = $totalPages;
					}	
					else
					{
						$endpage = $pageno + 5 ;
					}
			}
			
		
		for($i=$startpage;$i<=$endpage;$i++)
		{			
			if($i==$pageno && $i==$totalPages)
			{
				$showingpage.=" [$i] ";
			}
			else if($i==$pageno)
				$showingpage.=" [$i] | ";
			else
				$showingpage.="<A Bold href='myaccount.php?valalpha=$valalpha&pageno=$i'> $i</a> | ";// change link name and give u'r page link
		}
		
		
		if($totalPages>1)
		{			
			if($pageno=="1")
			{
				$page=$pageno + 1;
				$next="<A Bold href='myaccount.php?valalpha=$valalpha&pageno=$page'>Next</A>";
				$prev="";		
			}
			else if($pageno==$totalPages)
			{
				$page=$pageno - 1;
				$next="";
				$prev="<A Bold href='myaccount.php?valalpha=$valalpha&pageno=$page'>Previous</A>";			
			}
			else
			{
				$page1=$pageno + 1;
				$page2=$pageno - 1;
				$next="<A Bold href='myaccount.php?valalpha=$valalpha&pageno=$page1'>Next</A>";
				$prev="<A Bold href='myaccount.php?valalpha=$valalpha&pageno=$page2'>Previous</A>";		
			}
		}else
		{
			$next="";
			$prev="";		
		}	
		
		$query.=" LIMIT $initlimit,$noofpages";			
		$result= mysql_query($query); 
?>

  
	
	<tr>
	<td colspan="4">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
               <tr> 
                <td align="center" colspan=8 class="alltext"><?php echo $showingpage; ?><br></td>
              </tr>            
             <tr> 
                <td align="center" colspan=10 class="alltext">
                <?php echo $alphaList;?>
                </td>
             </tr>                                        
              <tr> 
               <td class="alltext"><?php echo $prev; ?>&nbsp;<br></td>               
               <td >&nbsp;<br></td>
               <td align=right class="alltext"><?php echo $next; ?><br></td>
            </tr>                                              
	</table>
	</td>
	</tr>
	
	 <tr>
	<td colspan="3" class="alltext"><b><?php echo $membername_result['username'];?></b></td>
	<td width="39%"><b></b></td>
	</tr>
	
	<tr><td colspan="4">&nbsp;</td></tr>
	
	 
<?php
if($_COOKIE['image_video_display']=="image")
{
	?>
	<tr>
	<td width="21%" class="alltext"><b>Thumbnails</b></td>
	<td width="21%" class="alltext"><b>Rating by <?php echo $membername_result['username'];?></b></td>
	<td width="19%" class="alltext"><b>Total Votes</b></td>
	<td width="39%" class="alltext"><b>Average</b></td>
	</tr>
	<tr><td colspan="4">&nbsp;</td></tr>
	<?php
	while($select_images_result = mysql_fetch_array($result))
	{
	if($select_images_result['filename']!='')
	 {
	 	$memberimages = mysql_query("select count(*) as totalvote, avg(rating) as rate from adv_gallery_rate where imageid = ".$select_images_result['imageid']."");
		$memberimages_result = mysql_fetch_array($memberimages);
		
	 ?>
	<tr>
	<td valign="top"><a href="<?php echo ROOT_PATH.'display/'.$select_images_result['imageid'];?>" target="_blank">
	<!--<a href="<?php //echo ROOT_PATH;?>viewimages.php?imageid=<?php //echo $select_images_result['imageid'];?>" target="_blank">-->
				<?php 
				/* $filenameforsize_small = PATH .'files/'. implode('/', preg_split('//', $select_images_result['userid'], -1, 1)).'/'.$select_images_result['filename'];
				
				$imagewidthhight_small = getimagesize($filenameforsize_small);
				$imagewidth_small = $imagewidthhight_small[0];
				$imageheight_small = $imagewidthhight_small[1];						
				 
				if($imagewidth_small>50)
				{ */
				?>
				<img src="<?php echo IMAGES_PATH;?>thumbnailimages.php?imageid=<?php echo $select_images_result['imageid']; ?>&w=60" border="1">
				<?php 							
				/* }
				else
				{ */
				?>						 
				<!--<img border="1" src="<?php //echo ROOT_DISPLAY_MAIN_PATH . implode('/', preg_split('//', $preimageurl['userid'], -1, 1)).'/'.$preimageurl['filename']; ?>  "  alt="<?php //echo $preimageurl['filename'] ; ?>" width="50">-->
				<?php 
				//}
				?>
			</a>	</td>
	<td class="alltext"  valign="top" align="center"><?php echo $select_images_result['rating'];?></td>
	<td class="alltext"  valign="top" align="center"><?php echo $memberimages_result['totalvote'];?></td>
	<td class="alltext"  valign="top"><?php //echo $memberimages_result['rate'];?>
			<?php 
			$round_rate1 = explode(".",$memberimages_result['rate']);
			$round_rate = $round_rate1[0];
			$finalrate = 0;
			if($round_rate>$memberimages_result['rate'])
			{
				$finalrate = 1;
			}
			
			for($f=$finalrate;$f<round($memberimages_result['rate']);$f++)
			{
				echo '<img src="'.ROOT_PATH.'full.gif" alt="Rating">';
			}
			 
			$empty = 9-$round_rate; 
			if(($empty+$f)!=10)
			{
				echo '<img src="'.ROOT_PATH.'half.gif" alt="Rating">';
			}
			for($e=$finalrate;$e<$empty;$e++)
			{
				echo '<img src="'.ROOT_PATH.'empty.gif" alt="Rating">';			
			}
			?>
			
	</td>
	</tr>
	<?php 
	}
	}

}
elseif($_COOKIE['image_video_display']=="video")
{
	?>
	<tr>
	<td width="21%" class="alltext"><b>Movies Name</b></td>
	<td width="21%" class="alltext"><b>Rating by <?php echo $membername_result['username'];?></b></td>
	<td width="19%" class="alltext"><b>Total Votes</b></td>
	<td width="39%" class="alltext"><b>Average</b></td>
	</tr>
	
	<tr><td colspan="4">&nbsp;</td></tr>
<?php
	while($select_images_result = mysql_fetch_array($result))
	{
	if($select_images_result['filename']!='')
	 {
	 	$memberimages = mysql_query("select count(*) as totalvote, avg(rating) as rate from adv_gallery_rate_video where videoid = ".$select_images_result['videoid']."");
		$memberimages_result = mysql_fetch_array($memberimages);
		
	 ?>
	<tr>
	<td valign="top">
		<OBJECT id='mediaPlayer1' width="100" height="90"
			classid='CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95'
			codebase='http://activex.microsoft.com/activex/controls/ mplayer/en/nsmp2inf.cab#Version=5,1,52,701'
			standby='Loading Microsoft Windows Media Player components' type='application/x-oleobject'>
			<param name='fileName' value="http://movies.hardornot.com/movies.php?videoid=<?php echo $select_images_result['videoid']; ?>">
			<param name='animationatStart' value='1'>
			<param name='transparentatStart' value='1'>
			<param name='autoStart' value='1'>
			<param name='ShowControls' value='0'>
			<param name='ShowDisplay' value='0'>
			<param name='ShowStatusBar' value='0'>
			<param name='loop' value='0'>
			<EMBED type='application/x-mplayer2'
			pluginspage='http://microsoft.com/windows/mediaplayer/ en/download/'
			id='mediaPlayer1' name='mediaPlayer1' displaysize='4' autosize='0'
			bgcolor='darkblue' showcontrols='0' showtracker='0'
			showdisplay='0' showstatusbar='0' videoborder3d='0' width="100" height="90"
			src="http://movies.hardornot.com/movies.php?videoid=<?php echo $select_images_result['videoid']; ?>" autostart='1' designtimesp='5311' loop='0'>
			</EMBED>
		</OBJECT>
	<br><a href="<?php echo ROOT_PATH;?>viewvideo.php?videoid=<?php echo $select_images_result['videoid'];?>" target="_blank"><?php echo $select_images_result['filename'];?>
		</a></td>
	<td class="alltext"  valign="top" align="center"><?php echo $select_images_result['rating'];?></td>
	<td class="alltext"  valign="top" align="center"><?php echo $memberimages_result['totalvote'];?></td>
	<td class="alltext"  valign="top"><?php //echo $memberimages_result['rate'];?>
			<?php 
			$round_rate1 = explode(".",$memberimages_result['rate']);
			$round_rate = $round_rate1[0];
			$finalrate = 0;
			if($round_rate>$memberimages_result['rate'])
			{
				$finalrate = 1;
			}
			
			for($f=$finalrate;$f<round($memberimages_result['rate']);$f++)
			{
				echo '<img src="'.ROOT_PATH.'full.gif" alt="Rating">';
			}
			 
			$empty = 9-$round_rate; 
			if(($empty+$f)!=10)
			{
				echo '<img src="'.ROOT_PATH.'half.gif" alt="Rating">';
			}
			for($e=$finalrate;$e<$empty;$e++)
			{
				echo '<img src="'.ROOT_PATH.'empty.gif" alt="Rating">';			
			}
			?>
			
			
			<!--<img src="<?php //echo ROOT_PATH;?>empty.gif" alt="Rating">
			<img src="<?php //echo ROOT_PATH;?>half.gif" alt="Rating">-->
		
	</td>
	</tr>
	<?php 
	}
	}
}
	?>
</table>
<?php include "footer1.php";?>
