<?php
include("../inc/inc.php");
$id = 4;
function getFieldsAssignedToTankMix($id)
{
    $sql = "select field from wet_application_2 where tank_mix_id = '$id'";
    $rs = dbcall($sql,"2015_crop");
    $i=0;
    while (!$rs->EOF)
    {
        $field =  $rs->fields["field"];
        $fields[$i] = $field;   
        $i++;
        $rs->MoveNext();
    }
    return $fields;
}
function getTotalAcresForTankMixID($id)
{
    $sql = "
    SELECT 
        SUM(ACRES) AS total_acres
        FROM 
            2015_crop.wet_application_2 
        INNER JOIN maranatha.master_field 
            ON `2015_crop`.wet_application_2.field = maranatha.master_field.field 
            WHERE 
               `tank_mix_id` = '$id'
              GROUP BY wet_application_2.TANK_MIX_ID
    ";
    $rs = dbcall($sql,"2015_crop");
    $total_acres =  $rs->fields["total_acres"];
    return $total_acres;
}
function getchemicalsFromTankMixId($id)
{
    $sql = "select chemical from tank_mix where tank_mix = '$id'";
    $rs = dbcall($sql,"2015_crop");
    return $rs;
}
function getTotalChemicalCost($chemical,$total_chemical_needed)
{
    $sql = "select cost from master_chemical where chemical = '$chemical'";
    $rs = dbcall($sql,"maranatha");
    $cost =  $rs->fields["cost"];
    $total_cost = $cost*$total_chemical_needed;
    return $total_cost;
}
//get the chemicals in the tank mix

$total_acres = getTotalAcresForTankMixID($id);

$chemical_data = "var data_chemical = [";
$field_chemical_data = "var data_field = [";

$rs = getchemicalsFromTankMixId($id);
$fields = getFieldsAssignedToTankMix($id);
while (!$rs->EOF)
{
    $chemical        =  $rs->fields["chemical"];
    $chemical_needed = number_format(getChemicalNeededAcres($total_acres, $chemical),2,".",",");
    $total_cost      = number_format(getTotalChemicalCost($chemical,$chemical_needed),2,".",",");
    $chemical_data.="
        {
            chemical        :'".$chemical."',
            chemical_needed :'".$chemical_needed."',
            total_cost      :'".$total_cost."'
        },";
    foreach ($fields as $key => $value) 
    {
        $chemical_needed = round(getChemicalNeeded($value,$chemical));
        $cost             = number_format(getTotalChemicalCost($chemical,$chemical_needed),2,".",",");
        $field_chemical_data.="
        {
            chemical        :'".$chemical."',
            chemical_needed :'".$chemical_needed."',
            cost            :'".$cost."',
            field           :'".$value."'
        },";
    }
    $rs->MoveNext();
}
$field_chemical_data = rtrim($field_chemical_data,",");
$chemical_data = rtrim($chemical_data,",");
$chemical_data.="]"; 
$field_chemical_data.="]"; 

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
    
    
<table>
<tr>
    <td width = '1000'><div id='chemical_grid'></div></td>
    <td width = '1000'><div id='field_chemical'></div></td>
</tr>
<div>
</div>
</table>
<script>
$chemical_data
$field_chemical_data
$(document).ready(function() {
function selectEvent()
{
    var grid = $('#chemical_grid').data('kendoGrid');
    var grid_field = $('#field_chemical').data('kendoGrid');
    var rows  = grid.select();
    var data = grid.dataItem(rows).chemical;

    grid_field.dataSource.filter({ field: 'chemical', operator: 'eq', value: data })
}

$('#chemical_grid').kendoGrid({
    dataSource: {
        data: data_chemical,
        aggregate:[{ field:'total_cost', aggregate:'sum' }]
    },
    selectable: 'multiple',
    filterable: true,
    sortable: true,
    change: selectEvent,
    columns: [
        {
            field: 'chemical',
            title: 'Chemical'
    },{
            field: 'chemical_needed',
            title: 'Chemical Needed'
    },{
            field : 'total_cost',
            title : 'Total Cost',
            footerTemplate:'$#= sum # '
    }]
});
$('#field_chemical').kendoGrid({
    dataSource: {
        data: data_field,
        aggregate:[
        { field:'chemical_needed', aggregate:'sum' },
        { field:'cost', aggregate:'sum' }
    ]
    },
    scrollable: false,
    navigatable: true,
    filterable: true,
    sortable: true,
    columns: [
        {
            field: 'field',
            title: 'Field'
    },{
            field    : 'chemical_needed',
            title    : 'Chemical Needed',
            type     :'number',
            footerTemplate:'Total Gallons: #= sum # '
    },{
            field    : 'cost',
            title    : 'Chemical Cost',
            type     :'number',
            footerTemplate:'Total Cost: #= sum # '
    },{
            field: 'chemical',
            title: 'Chemical'
    }]
});

});
            </script>
        </div>


    
    
</body>
</html>

";
print $z;