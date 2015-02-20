<?php
include("adodb5/adodb.inc.php");
extract($_REQUEST);
function dbCall($sql, $schema="test")
{
	$db = NewADOConnection('mysql');
	$db->Connect("localhost", "steve", "all4him", $schema);
	$rs = $db->Execute($sql);
	if ($rs === false) die("failed");  
	return $rs;
}
function getApplicationRate($chemical)
{   
    $sql = "
    select   
        default_application_rate
    from 
        master_chemical
    where
        chemical = '$chemical'
    ";
    $rs = dbcall($sql, "maranatha");
    $default_application_rate =  $rs->fields["default_application_rate"];
    return $default_application_rate;
}
function getfieldAcres($field)
{
    $sql = "select acres from master_field where field = '$field'";
    $rs = dbcall($sql, "maranatha");
    $acres =  $rs->fields["acres"];
    return $acres;
}
function getApplicationRateUOM($chemical)
{   
    $sql = "
    select   
        default_uom
    from 
        master_chemical
    where
        chemical = '$chemical'
    ";
    $rs = dbcall($sql, "maranatha");
    $default_uom              =  $rs->fields["default_uom"];
    return $default_uom;
}
function convertToGallons($uom, $raw_chemical_needed)
{
    if($uom=="fl oz")
    {
        return $raw_chemical_needed/128;
    }
    if($uom=="gallon")
    {
        return $raw_chemical_needed;
    }
    if($uom=="oz")
    {
        return $raw_chemical_needed/16;
    }
    if($uom=="pint")
    {
        return $raw_chemical_needed/8;
    }
    if($uom=="quart")
    {
        return $raw_chemical_needed/4;
    }
}
function getChemicalNeeded($field,$chemical)
{
    $rate                = round(getApplicationRate($chemical),2);
    $uom                 = getApplicationRateUOM($chemical);
    $acres               = getfieldAcres($field);
    $raw_chemical_needed = $acres * $rate;
    
    $chemical_needed = convertToGallons($uom, $raw_chemical_needed);
    return $chemical_needed;
}
function getChemicalNeededAcres($acres,$chemical)
{
    $rate                = round(getApplicationRate($chemical),2);
    $uom                 = getApplicationRateUOM($chemical);
    $raw_chemical_needed = $acres * $rate;    
    $chemical_needed = convertToGallons($uom, $raw_chemical_needed);
    return $chemical_needed;
}
 ?>