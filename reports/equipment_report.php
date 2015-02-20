<?php
include("../inc/inc.php");

$sql = "
    SELECT 
      equipment,
      company,
      equipment_type,
      implement,
      dealer,
      price,
      delivery_date,
      used,
      warranty,
      insurance,
      serial_no 
    FROM
      maranatha.master_equipment 
";
$rs = dbcall($sql,"maranatha");
$data = "var data = [";
while (!$rs->EOF)
{
    $equipment      =  $rs->fields["equipment"];
    $company        =  $rs->fields["company"];
    $equipment_type =  $rs->fields["equipment_type"];
    $dealer         =  $rs->fields["dealer"];
    $price          =  $rs->fields["price"];
    $used           =  $rs->fields["used"];
    $warranty       =  $rs->fields["warranty"];
    $insurance      =  $rs->fields["insurance"];
    $serial_no      =  $rs->fields["serial_no"];

	$data.="
            {
                equipment      :'".$equipment."', 
                company        :'".$company."',
                equipment_type :'".$equipment_type."',
                dealer         :'".$dealer."',
                price          :'".$price."',
                used           :'".$used."',
                warranty       :'".$warranty."',
                insurance      :'".$insurance."',
                serial_no      :'".$serial_no."'
            },
    ";
		   
	$rs->MoveNext();
 }
$data = rtrim($data,",");
$data.= "]";

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
    
            <script src='../inc/kendo/examples/content/shared/js/orders.js'></script>

        <div id='example'>

            <div class='demo-section k-header'>
                <h4>Grid with multiple row selection enabled</h4>
                <div id='rowSelection'></div>
             	
            </div>
			
<script>
$data
$(document).ready(function () {
    $('#rowSelection').kendoGrid({
        dataSource: {
            data: data
        },
        sortable: {
            mode: 'multiple'
        },
        selectable: 'multiple',
        scrollable: true,
        navigatable: true,
        columns: [
            {
                field: 'equipment',
                title: 'Equipment'
                
            },{
                field: 'company',
                title: 'Company'
                
            },{
                field: 'equipment_type',
                title: 'Type'
                
            },{
                field: 'dealer',
                title: 'Dealer'
                
            },{
                field: 'price',
                title: 'Price'
            },{
                field: 'used',
                title: 'Used'
                
            },{
                field: 'warranty',
                title: 'Warranty',
            
            },{
                field  : 'insurance',
                title  : 'insurance'
                
            },{
                field: 'serial_no',
                title: 'serial no'
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