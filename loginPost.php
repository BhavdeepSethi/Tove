<?php
session_start();
include_once("includes/functions.inc.php"); 

$referer  = $_SERVER['HTTP_REFERER'];
$HASH_SALT = "columbiaPrinceton";

if(!endsWith($referer, "Tove/login.php")){
	header('HTTP/1.0 401 Unauthorized');
	exit();	
}

$user = $_POST["user"];
$pwd = $_POST["pwd"];
#echo $user.":".$pwd;

$type = $_POST["type"];

if($type == "register"){
	$id = registerUser($user, $pwd);	
	if($id < 0){
		echo "$id";
	}else{
		$_SESSION["toveUserId"] = $id;
		$_SESSION["toveUser"] = $user;
		$_SESSION["toveHash"] = sha1($user.$HASH_SALT);
		$_SESSION["toveInit"] = 0;		
		echo "0";
	}
}

if($type == "login"){
	$response = checkLogin($user, $pwd);
	if($response < 0){
		echo "-1";
	}else{
		//var_dump($response);
		$_SESSION["toveUserId"] = $response["id"];
		$_SESSION["toveUser"] = $user;
		$_SESSION["toveHash"] = sha1($user.$HASH_SALT);
		$_SESSION["toveInit"] = $response["status"];		
		echo "0";
	}

}




?>