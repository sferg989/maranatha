<?php
include("inc/inc.php");

$sql = "
SELECT
    `master_field`.`field`
    , `master_crop_2013`.`crop` AS `crop_2013`
    , `master_crop_2014`.`crop` AS `crop_2014`
    , `master_crop_2015`.`crop` AS `crop_2015`
    , `master_field`.`acres`
FROM
    `maranatha`.`master_crop_2013`
    INNER JOIN `maranatha`.`master_field` 
        ON (`master_crop_2013`.`id` = `master_field`.`id`)
    INNER JOIN `maranatha`.`master_crop_2014` 
        ON (`master_crop_2014`.`id` = `master_field`.`id`)
    INNER JOIN `maranatha`.`master_crop_2015` 
        ON (`master_field`.`id` = `master_crop_2015`.`id`)
";

$z = "
<html>
	<body>
		<table>
			<th>
				<tr>
					<td>Field</td>
					<td>acres</td>
					<td>2013</td>
					<td>2014</td>
					<td>2015</td>
				</tr>
			</th>
	";
$rs = dbcall($sql,"maranatha");
while (!$rs->EOF)
{
	$field =  $rs->fields["field"];
	$acres =  round($rs->fields["acres"]);
	$crop_2013 =  $rs->fields["crop_2013"];
	$crop_2014 =  $rs->fields["crop_2014"];
	$crop_2015 =  $rs->fields["crop_2015"];
	$z.="
		<tr>
			<td>$field</td>
			<td>$acres</td>
			<td>$crop_2013</td>
			<td>$crop_2014</td>
			<td>$crop_2015</td>
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
 