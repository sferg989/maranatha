<?php
include("inc.php");

// ... more code
$z="";
$sql = "select field, crop from master_field";
$rs = dbcall($sql);
$num_columns = $rs->fields->count();
$arrColumns = array(); 

for ($i=0; $i < $num_columns; $i++) { 
    $arrColumns[] = $rs->fields($i); 
} 

$arrResult = array(); 

while (!$rs->EOF) { 
    $arrRow = array(); 

    for ($i=0; $i < $num_columns; $i++) { 
        $arrRow[ $arrColumns[$i]->name ] = $arrColumns[$i]->value; 
    } 
    $arrResult[] = $arrRow; 
    $rs->MoveNext(); 
} 



print_r( $arrResult );  
print $rs;
