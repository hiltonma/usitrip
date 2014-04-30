<?$url = substr($PHP_SELF,-17,-4);?>
<BR><BR><table border="0" align='center' width="100%" cellspacing="1" cellpadding="0">


			<TR>
				<TD class='tableHeading'>&nbsp;</TD>
			</TR>
			<TR>
				<TD class='tableHeading'>usitrip's All Tours</TD>
			</TR>
			<TR>
				<TD class='tableHeading'><hr size='1'></TD>
			</TR>
</table>

	<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
 <td class='main'><?require DIR_FS_CLASSES . 'category_tree.php'; $osC_CategoryTree = new osC_CategoryTree; echo $osC_CategoryTree->buildTree($url);?></td>
  </tr>
   </table>

