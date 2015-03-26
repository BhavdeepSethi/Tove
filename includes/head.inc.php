<?php

$HASH_SALT = "columbiaPrinceton";
$PWD_SALT = "princetonColumbia";
$isLogged = false;
$isInit = 0;
$LOGIN = "/~bhavdeepsethi/Tove/login.php";
$INIT = "/~bhavdeepsethi/Tove/init.php";

if(isset($_SESSION['toveHash'])){
	$user = $_SESSION['toveUser'];
	if(isset($_SESSION['toveUser']) && $_SESSION['toveHash'] == sha1($user.$HASH_SALT)){
		$isLogged = true;
	} 

	if(isset($_SESSION['toveInit'])){
		$isInit = $_SESSION['toveInit'];
	}
}

if(!$isLogged){
	header('Location: '.$LOGIN);
	die();
}
#$isInit = true;
if($isInit == 0){
	header('Location: '.$INIT);
	die();
}





?>