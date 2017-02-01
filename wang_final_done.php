<?php

// Amy Wang
// CS IV
// 11/16/15
// Web development final project: StudySpace 
// MySQL code enacted on server when AJAX call is sent out

session_start(); 

$id = (int) $_GET["id"];
$isChecked = $_GET["ischecked"];

require("wang_final_config.php");
try
{
	$db = new PDO("mysql:dbname=" . $GLOBALS["database"] . 
	";host=" . $GLOBALS["hostname"] . ";port=" . $GLOBALS["port"], $GLOBALS["username"], $GLOBALS["password"]);	
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	echo intval($isChecked);
	echo intval(!$isChecked);
	$query = "UPDATE `awang_group_reminders` SET `done`=" . intval($isChecked) . " WHERE id = :id;"; 
	echo $query;
	$statement = $db->prepare($query);
	$statement->bindValue("id", $id, PDO::PARAM_INT); //have to use this, or "id" will be passed in as a string
	$statement->execute(); 
}
catch (PDOException $ex)
{
	echo ("Sorry, a database error occurred.");
	echo ("Error details: " . $ex->getMessage());
}

?>