<?php
include("../inc/inc.php");
//combo boxes
$sql = "SELECT chemical FROM master_chemical GROUP BY chemical ORDER BY chemical";
$rs = dbcall($sql,"maranatha");
$chem_list="";
while (!$rs->EOF)
{
    $chem =  $rs->fields["chemical"];

	$chem_list.="{text:'".$chem."', value:'".$chem."'},";
		   
	$rs->MoveNext();
 }
$chem_list = rtrim($chem_list,',');
//get rice fields for grid
$sql="
    SELECT 
        master_field.field as field
    FROM 
        master_crop_2015 
    INNER JOIN master_field 
        ON master_field.id = master_crop_2015.id 
        WHERE 
            master_crop_2015.crop = 'rice'
";
$rs = dbcall($sql,"maranatha");
$rice_data = "var data_rice = [";
while (!$rs->EOF)
{
    $field =  $rs->fields["field"];
    $rice_data.="{field:'".$field."'},";
           
    $rs->MoveNext();
 }
 $rice_data = rtrim($rice_data,",");
 $rice_data.="]"; 

 $soybeans_data = "var data_soybeans = [";
 $sql="
    SELECT 
        master_field.field as field
    FROM 
        master_crop_2015 
    INNER JOIN master_field 
        ON master_field.id = master_crop_2015.id 
        WHERE 
            master_crop_2015.crop = 'soybeans'
";
$rs = dbcall($sql,"maranatha");
$soybeans_data = "var data_soybeans = [";
while (!$rs->EOF)
{
    $field =  $rs->fields["field"];
    $soybeans_data.="{field:'".$field."'},";
           
    $rs->MoveNext();
 }
 $soybeans_data = rtrim($soybeans_data,",");
 $soybeans_data.="]"; 
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
            <div id='window'>
               <input id='chem_1' placeholder='Select Chemical...' />
                <input id='chem_2' placeholder='Select Chemical...' />
                <input id='chem_3' placeholder='Select Chemical...' />
                <input id='chem_4' placeholder='Select Chemical...' />
                <input id='chem_5' placeholder='Select Chemical...' />
                <input type='text' id='tank_mix_name' name='tank_mix_name' placeholder='tank_mix_name' />
                <div>
                    <button id='textButton'>Create Tank Mix</button>  
            </div>
                <table>
                <tr>
                    <td><div id='rice_field_grid'></div></td>
                    <td><div id='soybean_field_grid'></div></td>
                </tr>
                <div>
                <button id='apply'>Apply to Fields</button>  
                </div>
                </table>
            <span id='undo' style='display:none' class='k-button'>Click here to open the window.</span>
<script>
$rice_data
$soybeans_data
function success()
{
    alert('run');
    $('#chem_1').val('');
    $('#chem_2').val('');
    $('#chem_3').val('');
    $('#chem_4').val('');
    $('#chem_5').val('');
}
function submitChemicals()
{

    var chem_1   =  $('#chem_1').val();
    var chem_2   =  $('#chem_2').val();
    var chem_3   =  $('#chem_3').val();
    var chem_4   =  $('#chem_4').val();
    var chem_5   =  $('#chem_5').val();
    var tank_mix =  $('#tank_mix_name').val();
    $.ajax({
        type    : 'get',
        url     : 'insert_tank_mix.php',
        success : success,
        data :({
            bulk   : 'no',
            tank_mix : 'yes',
            tank_mix_name : tank_mix,
            chem_1 : chem_1,
            chem_2 : chem_2,
            chem_3 : chem_3,
            chem_4 : chem_4,
            chem_5 : chem_5
        })
    })
}
function insertChemicalsByField(field)
{
    var tank_mix =  $('#tank_mix_name').val(); 
    $.ajax({
    type    : 'get',
    url     : 'insert_tank_mix.php',
    success : success,
        data :({
            bulk   : 'no',
            field   : field,
            tank_mix_name : tank_mix
        })
    });
}
function applyToFields()
{
    var grid = $('#rice_field_grid').data('kendoGrid');
    var rows  = grid.select();
    var data = grid.dataItem(rows);
    rows.each(function(index, row) {
      var selectedItem = grid.dataItem(row);
          insertChemicalsByField(selectedItem.field);   
    });    
    var grid = $('#soybean_field_grid').data('kendoGrid');
    var rows  = grid.select();
    var data = grid.dataItem(rows);
    rows.each(function(index, row) {
      var selectedItem = grid.dataItem(row);
          insertChemicalsByField(selectedItem.field);   
    });
}
$(document).ready(function() {
$('#rice_field_grid').kendoGrid({
    dataSource: {
        data: data_rice,
        pageSize: 6
    },
    selectable: 'multiple',
    pageable: {
        buttonCount: 5
    },
    scrollable: false,
    navigatable: true,
    columns: [
        {
            field: 'field',
            title: 'Rice Fields'
    }]
});
$('#soybean_field_grid').kendoGrid({
    dataSource: {
        data: data_soybeans,
        pageSize: 6
    },
    selectable: 'multiple',
    pageable: {
        buttonCount: 5
    },
    scrollable: false,
    navigatable: true,
    columns: [
        {
            field: 'field',
            title: 'Soybean Fields'
    }]
});
    $('#chem_1').kendoComboBox({
        dataTextField: 'text',
        dataValueField: 'value',
        dataSource: [$chem_list]
    });                 
    $('#chem_2').kendoComboBox({
        dataTextField: 'text',
        dataValueField: 'value',
        dataSource: [$chem_list]
    });
    $('#chem_3').kendoComboBox({
        dataTextField: 'text',
        dataValueField: 'value',
        dataSource: [$chem_list]
    });
    $('#chem_4').kendoComboBox({
        dataTextField: 'text',
        dataValueField: 'value',
        dataSource: [$chem_list]
    });
    $('#chem_5').kendoComboBox({
        dataTextField: 'text',
        dataValueField: 'value',
        dataSource: [$chem_list]
    });             
    $('#textButton').kendoButton({
        click: submitChemicals
    });    
    $('#apply').kendoButton({
        click: applyToFields
    });
});
            </script>
        </div>


    
    
</body>
</html>

";
print $z;