<?php 
session_start();
session_unset();

unset($_SESSION['FBID']);
unset($_SESSION['EMAIL']);
unset($_SESSION['toveUserId']);
unset($_SESSION['toveUserName']);
unset($_SESSION['toveUserEmail']);
unset($_SESSION['toveInit']);  
unset($_SESSION['fb_token']);  
session_destroy(); 
header("Location: login.php?type=bypass"); 
?>
