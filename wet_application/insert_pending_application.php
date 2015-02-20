<?php
include("../inc/inc.php");
//$crop, and all $chemicals are coming in from URL.

if($bulk=="yes")
{
	//get all the field id's so we can insert them, one at a time.
	$sql = "
	SELECT
    	master_field.field
	FROM
	    maranatha.master_field
	    INNER JOIN maranatha.master_crop_2015 
	        ON (master_field.id = master_crop_2015.id)
        where crop = '$crop'
    ";
	$rs = dbcall($sql,"maranatha");
	while (!$rs->EOF)
	{
		$field =  $rs->fields["field"];
		$chem_1_needed = getChemicalNeeded($field,$chem_1);
	    $chem_2_needed = getChemicalNeeded($field,$chem_2);
	    $chem_3_needed = getChemicalNeeded($field,$chem_3);
	    $chem_4_needed = getChemicalNeeded($field,$chem_4);
	    $chem_5_needed = getChemicalNeeded($field,$chem_5);
		//insert all the pending applications
		$sql = "
			insert into wet_application_pending  
			( field_id , 
			 method , 
			 chemical_1 ,
			 chemical_1_needed, 
			 chemical_2 ,
			 chemical_2_needed, 
			 chemical_3 ,
			 chemical_3_needed, 
			 chemical_4 ,
			 chemical_4_needed, 
			 chemical_5,
			 chemical_5_needed
			)
			values
			('$field', 
			'4940', 
			'$chem_1',
			'$chem_1_needed', 
			'$chem_2',
			'$chem_2_needed', 
			'$chem_3',
			'$chem_3_needed', 
			'$chem_4',
			'$chem_4_needed', 
			'$chem_5',
			'$chem_5_needed'
			)
		";
		$junk = dbcall($sql, "2015_crop");
		$rs->MoveNext();
 	}
}
if($bulk=="no")
{
	//$field comes in on the URL/
	$chem_1_needed = getChemicalNeeded($field,$chem_1);
    $chem_2_needed = getChemicalNeeded($field,$chem_2);
    $chem_3_needed = getChemicalNeeded($field,$chem_3);
    $chem_4_needed = getChemicalNeeded($field,$chem_4);
    $chem_5_needed = getChemicalNeeded($field,$chem_5);
	//insert all the pending applications
	$sql = "
		insert into wet_application_pending  
		( field_id , 
		 method , 
		 chemical_1 ,
		 chemical_1_needed, 
		 chemical_2 ,
		 chemical_2_needed, 
		 chemical_3 ,
		 chemical_3_needed, 
		 chemical_4 ,
		 chemical_4_needed, 
		 chemical_5,
		 chemical_5_needed
		)
		values
		('$field', 
		'4940', 
		'$chem_1',
		'$chem_1_needed', 
		'$chem_2',
		'$chem_2_needed', 
		'$chem_3',
		'$chem_3_needed', 
		'$chem_4',
		'$chem_4_needed', 
		'$chem_5',
		'$chem_5_needed'
		)
	";
	$junk = dbcall($sql, "2015_crop");
	$rs->MoveNext();
}

die("made it");
