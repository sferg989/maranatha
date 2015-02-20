<?php
include("inc/inc.php");
$z = "
<html>
	<body>
		<table>
";
$sql = "
	select name, url from menu
";
$rs = dbcall($sql, "maranatha");
while (!$rs->EOF)
{
	$url =  $rs->fields["url"];
	$name =  $rs->fields["name"];
	$z.="
		<tr>
			<td><a href=\"$url\">$name</a></td>
		</tr>
	";
		   
	$rs->MoveNext();
 }
$z.="
		</table>
	</body>
</html>
";
print $z; 