<?php
include("../inc/inc.php");
//$crop, and all $chemicals are coming in from URL.
function getLastID()
{
	$sql= "select max(tank_mix) as id from tank_mix";
	$rs = dbcall($sql,"2015_crop");
	$id =  $rs->fields["id"];
	if($id==null)
	{
		$id = 1;
	}
	return $id+1;
}
function createTankMix($id,$chemical,$tank_mix_name)
{
	$sql = "
		insert into tank_mix
		(tank_mix,chemical, name)
		values($id,'$chemical', '$tank_mix_name')
	";
	//die($sql);
	$junk = dbcall($sql, "2015_crop");
}
function getTankMixID($name)
{
	$sql = "select tank_mix from tank_mix where name = '$name'";
	$rs = dbcall($sql,"2015_crop");
	$tank_mix =  $rs->fields["tank_mix"];
	return $tank_mix;
}
if($tank_mix=="yes")
{
	$id = getLastID();

	if($chem_1 !='')
	{
		createTankMix($id,$chem_1,$tank_mix_name);

	}
	if($chem_2 !='')
	{
		createTankMix($id,$chem_2,$tank_mix_name);
	}
	if($chem_3 !='')
	{
		createTankMix($id,$chem_3,$tank_mix_name);
	}
	if($chem_4 !='')
	{
		createTankMix($id,$chem_4,$tank_mix_name);
	}	
	if($chem_5 !='')
	{
		createTankMix($id,$chem_5,$tank_mix_name);
	}
}
if($bulk=="no")
{
	$id = getTankMixID($tank_mix_name);
	
	if($field!='')
	{
		$sql = "
		INSERT INTO 2015_crop.wet_application_2 
		(field, tank_mix_id) 
		VALUES
		('$field', $id)";
		$junk = dbcall($sql, "2015_crop");
	}

}
