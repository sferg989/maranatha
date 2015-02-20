<?php
include("../inc/inc.php");
function getTotalChemical($chemical)
{
    $sql ="
        select
            SUM(".$chemical."_needed) as total
        FROM
          wet_application_pending GROUP BY $chemical
    ";
    $rs = dbcall($sql,"2015_crop");
    $total =  $rs->fields["total"];
    return $total;
}
function getTotalChemicalCost($chemical,$total_chemical_needed)
{
    $sql = "select cost from master_chemical where chemical = '$chemical'";
    $rs = dbcall($sql,"maranatha");
    $cost =  $rs->fields["cost"];
    $total_cost = $cost*$total_chemical_needed;
    return $total_cost;
}
$sql = "
    SELECT 
        field_id,
        chemical_1,
        chemical_1_needed,
        chemical_2,
        chemical_2_needed,
        chemical_3,
        chemical_3_needed,
        chemical_4,
        chemical_4_needed,
        chemical_5,
        chemical_5_needed
    FROM
      2015_crop.wet_application_pending 
";
$rs = dbcall($sql,"maranatha");
$data = "var data = [";
while (!$rs->EOF)
{
    $field_id          =  $rs->fields["field_id"];
    $chemical_1        =  $rs->fields["chemical_1"];
    $chemical_1_needed =  $rs->fields["chemical_1_needed"];
    $chemical_2        =  $rs->fields["chemical_2"];
    $chemical_2_needed =  $rs->fields["chemical_2_needed"];
    $chemical_3        =  $rs->fields["chemical_3"];
    $chemical_3_needed =  $rs->fields["chemical_3_needed"];
    $chemical_4        =  $rs->fields["chemical_4"];
    $chemical_4_needed =  $rs->fields["chemical_4_needed"];
    $chemical_5        =  $rs->fields["chemical_5"];
	$chemical_5_needed =  $rs->fields["chemical_5_needed"];

	$data.="
            {field:'".$field_id."', 
                chem_1        :'".$chemical_1."',
                chem_1_needed :'".$chemical_1_needed."',
                chem_2        :'".$chemical_2."',
                chem_2_needed :'".$chemical_2_needed."',
                chem_3        :'".$chemical_3."',
                chem_3_needed :'".$chemical_3_needed."',
                chem_4        :'".$chemical_4."',
                chem_4_needed :'".$chemical_4_needed."',
                chem_5        :'".$chemical_5."',
                chem_5_needed :'".$chemical_5_needed."'
            },
    ";
		   
	$rs->MoveNext();
 }
 //total gallons line
$chemical_1_total = getTotalChemical("chemical_1");
$chemical_2_total = getTotalChemical("chemical_2");
$chemical_3_total = getTotalChemical("chemical_3");
$chemical_4_total = getTotalChemical("chemical_4");
$chemical_5_total = getTotalChemical("chemical_5");

 //total dollars line
$chemical_1_total_dollars = round(getTotalChemicalCost($chemical_1,$chemical_1_total),2);
$chemical_2_total_dollars = round(getTotalChemicalCost($chemical_2,$chemical_2_total),2);
$chemical_3_total_dollars = round(getTotalChemicalCost($chemical_3,$chemical_3_total),2);
$chemical_4_total_dollars = round(getTotalChemicalCost($chemical_4,$chemical_4_total),2);
$chemical_5_total_dollars = round(getTotalChemicalCost($chemical_5,$chemical_5_total),2);

$data.= "
    {field:'TOTAL Gallons', 
    chem_1        :'".$chemical_1."',
    chem_1_needed :'".$chemical_1_total."',
    chem_2        :'".$chemical_2."',
    chem_2_needed :'".$chemical_2_total."',
    chem_3        :'".$chemical_3."',
    chem_3_needed :'".$chemical_3_total."',
    chem_4        :'".$chemical_4."',
    chem_4_needed :'".$chemical_4_total."',
    chem_5        :'".$chemical_5."',
    chem_5_needed :'".$chemical_5_total."'
},{field:'TOTAL Dollars', 
    chem_1        :'".$chemical_1."',
    chem_1_needed :'$".$chemical_1_total_dollars."',
    chem_2        :'".$chemical_2."',
    chem_2_needed :'$".$chemical_2_total_dollars."',
    chem_3        :'".$chemical_3."',
    chem_3_needed :'$".$chemical_3_total_dollars."',
    chem_4        :'".$chemical_4."',
    chem_4_needed :'$".$chemical_4_total_dollars."',
    chem_5        :'".$chemical_5."',
    chem_5_needed :'$".$chemical_5_total_dollars."'
}]
";

$z = "
<!DOCTYPE html>
<html>
<head>
    <title>Selection</title>
    <meta charset='utf-8'>
    <link href='../examples/content/shared/styles/examples-offline.css' rel='stylesheet'>
    <link href='../inc/kendo/styles/kendo.common.min.css' rel='stylesheet'>
    <link href='../inc/kendo/styles/kendo.rtl.min.css' rel='stylesheet'>
    <link href='../inc/kendo/styles/kendo.default.min.css' rel='stylesheet'>
    <link href='../inc/kendo/styles/kendo.dataviz.min.css' rel='stylesheet'>
    <link href='../inc/kendo/styles/kendo.dataviz.default.min.css' rel='stylesheet'>
    <script src='../inc/kendo/js/jquery.min.js'></script>
    <script src='../inc/kendo/js/angular.min.js'></script>
    <script src='../inc/kendo/js/kendo.all.min.js'></script>
    <script src='../inc/kendo/examples/content/shared/js/console.js'></script>
    <script>
        
    </script>
    
    
</head>
<body>
    
        <a class='offline-button' href='../index.php'>Back</a>
    
        <div id='example'>

            <div class='demo-section k-header'>
                <div id='rowSelection'></div>
             	
            </div>
			
<script>
$data
$(document).ready(function () {
    $('#rowSelection').kendoGrid({
        dataSource: {
            data: data
        },
        selectable: 'multiple',
        scrollable: true,
        navigatable: true,
        columns: [
            {
                field: 'field',
                title: 'Field',
                width: 300
            },{
                field: 'chem_1',
                title: 'Chemical 1',
                width: 300
            },{
                field: 'chem_1_needed',
                title: 'gallons',
                width: 100
            },{
                field: 'chem_2',
                title: 'Chemical 2',
                width: 300
            },{
                field: 'chem_2_needed',
                title: 'gallons',
                width: 100
            },{
                field: 'chem_3',
                title: 'Chemical 3',
                width: 300
            },{
                field: 'chem_3_needed',
                title: 'gallons',
                width: 100
            },{
                field: 'chem_4',
                title: 'Chemical 4',
                width: 300
            },{
                field: 'chem_4_needed',
                title: 'gallons',
                width: 100
            },{
                field: 'chem_5',
                title: 'Chemical 5',
                width: 300
            },{
                field: 'chem_5_needed',
                title: 'gallons',
                width: 100
            }
        ]
    });
    

});
            </script>
        </div>


    
    
</body>
</html>

";
print $z;