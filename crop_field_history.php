<?php
include("inc/inc.php");



$z = "
<html>
	<body>
		<table>
			<th>
				<tr>
					<td>2013 Crop</td>
					<td>acres</td>
					
				</tr>
			</th>
";

$sql = "
SELECT
     `master_crop_2013`.`crop` AS crop
    , SUM(`master_field`.`acres`) AS acres
FROM
    `maranatha`.`master_crop_2013`
    INNER JOIN `maranatha`.`master_field` 
        ON (`master_crop_2013`.`id` = `master_field`.`id`)
        WHERE FIELD IS NOT NULL
     GROUP BY crop
";
$rs = dbcall($sql,"maranatha");
while (!$rs->EOF)
{
	$crop =  $rs->fields["crop"];
	$acres =  round($rs->fields["acres"]);
	if ($crop=="") $crop ="acres that were not farmed"; 
	
	$z.="
		<tr>
			<td>$crop</td>
			<td>$acres</td>
		</tr>
	";
		   
	$rs->MoveNext();
 }
 //same thing for 2014
$z.= "
<html>
	<body>
		<table>
			<th>
				<tr>
					<td>2014 Crop</td>
					<td>acres</td>
					
				</tr>
			</th>
";

$sql = "
SELECT
     `master_crop_2014`.`crop` AS crop
    , SUM(`master_field`.`acres`) AS acres
FROM
    `maranatha`.`master_crop_2014`
    INNER JOIN `maranatha`.`master_field` 
        ON (`master_crop_2014`.`id` = `master_field`.`id`)
        WHERE FIELD IS NOT NULL
     GROUP BY crop
";
$rs = dbcall($sql,"maranatha");
while (!$rs->EOF)
{
	$crop =  $rs->fields["crop"];
	$acres =  round($rs->fields["acres"]);
	if ($crop=="") $crop ="acres that were not farmed"; 
	
	$z.="
		<tr>
			<td>$crop</td>
			<td>$acres</td>
		</tr>
	";
		   
	$rs->MoveNext();
 }
 //same thing for 2015
$z.= "
<html>
	<body>
		<table>
			<th>
				<tr>
					<td>2015 Crop</td>
					<td>acres</td>
					
				</tr>
			</th>
";
$sql = "
SELECT
     `master_crop_2015`.`crop` AS crop
    , SUM(`master_field`.`acres`) AS acres
FROM
    `maranatha`.`master_crop_2015`
    INNER JOIN `maranatha`.`master_field` 
        ON (`master_crop_2015`.`id` = `master_field`.`id`)
        WHERE FIELD IS NOT NULL
     GROUP BY crop
";
$rs = dbcall($sql,"maranatha");
while (!$rs->EOF)
{
	$crop =  $rs->fields["crop"];
	$acres =  round($rs->fields["acres"]);
	if ($crop=="") $crop ="acres that were not farmed"; 
	
	$z.="
		<tr>
			<td>$crop</td>
			<td>$acres</td>
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
 