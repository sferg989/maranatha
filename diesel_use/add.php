<?php
 
/*
 * 
 * http://editablegrid.net
 *
 * Copyright (c) 2011 Webismymind SPRL
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://editablegrid.net/license
 */
      
require_once('config.php');         

// Database connection                                   
$mysqli = mysqli_init();
$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
$mysqli->real_connect($config['db_host'],$config['db_user'],$config['db_password'],$config['db_name']); 

// Get all parameter provided by the javascript
$date = $mysqli->real_escape_string(strip_tags($_POST['date']));
$gallons_used = $mysqli->real_escape_string(strip_tags($_POST['gallons_used']));
$person = $mysqli->real_escape_string(strip_tags($_POST['person']));
$equipment = $mysqli->real_escape_string(strip_tags($_POST['equipment']));
$equipment_hours = $mysqli->real_escape_string(strip_tags($_POST['equipment_hours']));
$tablename = $mysqli->real_escape_string(strip_tags($_POST['tablename']));

$return=false;
if ( $stmt = $mysqli->prepare("INSERT INTO ".$tablename."  (date, gallons_used, person, equipment, equipment_hours) VALUES (  ?, ?,  ?,  ?, ?)")) {

	$stmt->bind_param("sdssd", $date, $gallons_used, $person, $equipment,  $equipment_hours);
    $return = $stmt->execute();

	$stmt->close();
}             
$mysqli->close();        

echo $return ? "ok" : "error";

      

