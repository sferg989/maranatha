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
$gallons_purchased = $mysqli->real_escape_string(strip_tags($_POST['gallons_purchased']));
$cost = $mysqli->real_escape_string(strip_tags($_POST['cost']));
$tablename = $mysqli->real_escape_string(strip_tags($_POST['tablename']));

$return=false;
if ( $stmt = $mysqli->prepare("INSERT INTO ".$tablename."  (date, gallons_purchased,cost) VALUES (  ?, ?, ?)")) {

	$stmt->bind_param("sid", $date, $gallons_purchased, $cost);
    $return = $stmt->execute();
    $file = 'people.txt';
	$current = file_get_contents($file);
	// Append a new person to the file
	// Write the contents back to the file
	file_put_contents($file, $stmt);
	$stmt->close();
}             
$mysqli->close();        

echo $return ? "ok" : "error";

      

