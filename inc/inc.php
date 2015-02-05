<?php
include("adodb5/adodb.inc.php");
function dbCall($sql, $schema="test")
{
	$db = NewADOConnection('mysql');
	$db->Connect("localhost", "steve", "all4him", $schema);
	$rs = $db->Execute($sql);
	if ($rs === false) die("failed");  
	return $rs;
}
 ?>