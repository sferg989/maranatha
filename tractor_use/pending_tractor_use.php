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
    
        <a class='offline-button' href='../index.html'>Back</a>
    
            <script src='../inc/kendo/examples/content/shared/js/orders.js'></script>

        <div id='example'>

            <div class='demo-section k-header'>
            	<h4>T-shirt Fabric</h4>
                <input id='chem_1' placeholder='Select Chemical...' />
                <input id='chem_2' placeholder='Select Chemical...' />
                <input id='chem_3' placeholder='Select Chemical...' />
                <input id='chem_4' placeholder='Select Chemical...' />
                <input id='chem_5' placeholder='Select Chemical...' />
                <div>
					<input id='crop' placeholder='Select crop...' />
					<button id='textButton'>Button</button>  
            </div>
                <h4>Grid with multiple row selection enabled</h4>
                <table>
                <tr>
                    <td><div id='rice_field_grid'></div></td>
                    <td><div id='soybean_field_grid'></div></td>
                </tr>
                </table>
             	
            </div>
			
<script>
$rice_data
$soybeans_data
function submitChemicals()
{
    function success()
    {
        window.location.href = 'pending_application_report.php';
    }
    var grid = $('#rice_field_grid');
    var selected = grid.select();
    alert(selected);
    return true;
    var chem_1 =  $('#chem_1').val();
    var chem_2 =  $('#chem_2').val();
    var chem_3 =  $('#chem_3').val();
    var chem_4 =  $('#chem_4').val();
    var chem_5 =  $('#chem_5').val();
    var crop   =  $('#crop').val();
    $.ajax({
        type    : 'get',
        url     : 'insert_pending_application.php',
        success : success,
        data :({
            bulk   : 'yes',
            crop   : crop,
            chem_1 : chem_1,
            chem_2 : chem_2,
            chem_3 : chem_3,
            chem_4 : chem_4,
            chem_5 : chem_5
        })
    })
}
                $(document).ready(function () {
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
					$('#crop').kendoComboBox({
                        dataTextField: 'text',
                        dataValueField: 'value',
                        dataSource: [{
							text : 'rice', value : 'rice'
                        },{
                        	text : 'soybeans', value : 'soybeans'
                        },{
                        	text : 'wheat', value : 'wheat'
                        }]
                    });
					$('#textButton').kendoButton({
                        click: submitChemicals
                    });

                });
            </script>
        </div>


    
    
</body>
</html>

";
print $z;