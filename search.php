<?php
session_start();
include_once("includes/head.inc.php"); 
include_once("includes/functions.inc.php"); 


if(!isset($_SESSION["toveUserId"])){
	echo "-1";
	return;
}

$user = $_SESSION["toveUserId"];
$term = $_GET["term"];

$result = searchTerm($term, $INFO);

	
echo json_encode($result);



?>