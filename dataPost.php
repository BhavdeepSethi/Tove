<?php
session_start();
include_once("includes/functions.inc.php"); 


if(!isset($_SESSION["toveUserId"])){
	echo "-1";
	return;
}
$user = $_SESSION["toveUserId"];
$mId = $_POST["mId"];
$type = $_POST["type"];

$count = 0;
if($type == "rating"){
	$rating = $_POST["rating"];
	$response = storeUserRating($user, $mId, $rating);
	if($_SESSION["toveInit"]==0){
		$count = fetchUGCCount($user);	
	}
	$out = array('response' =>  $response, 'count'=>$count);
	echo json_encode($out);
}
elseif($type == "watchList"){
	$response = storeUserWatchList($user, $mId);
	if($_SESSION["toveInit"]==0){
		$count = fetchUGCCount($user);	
	}
	$out = array('response' =>  $response, 'count'=>$count);
	echo json_encode($out);
}



?>