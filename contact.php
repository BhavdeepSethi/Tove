<?php
include_once("includes/head.inc.php"); 
include_once("includes/functions.inc.php");
$imdb = new Imdb();
$BYPASS = true;

$movieId = $_GET["mId"];
$movieArray = $imdb->getUserWatchList($_SESSION['toveUserId']);
$movieArrayRating = $imdb->getUserRatingList($_SESSION['toveUserId']);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>WatchList</title>
<?php $template->headerFiles(false); ?>
<script src="js/formValidator.js"></script>
</head>
<body class="info">

<? 
$classArray = array('contact' => "selected_tab" );
$template->addHeaderMenu($settings_data['title'], $lang_template_library, $classArray); 
?>

<?
$showForm = true;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = '';
    $myemail = 'toveAdmin@googlegroups.com';
    if(empty($_POST['name'])  || 
       empty($_POST['email']) || 
       empty($_POST['message']))
    {
        $errors .= "\nAll fields are required. ";
    }
     
    $name = $_POST['name']; 
    $email_address = $_POST['email']; 
    $message = $_POST['message']; 
     
    if (!preg_match(
    "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", 
    $email_address))
    {
        $errors .= "\n Invalid email address. ";
    }

    if( empty($errors)){
        $to = $myemail; 
        $email_subject = "Contact form submission: $name";
        $email_body = "You have received a new message. ".
        "Here are the details:\n Name: $name \n ".    
        "User Id: ".$_SESSION["toveUserId"]." \n ".               
        "Email: ".$_SESSION["toveUserEmail"]." \n Message: \n $message";     
        $headers = "From: $email_address\n";     
        mail($to,$email_subject,$email_body,$headers);
        $showForm = false;

    }else{
      ?>
      <div class="subtitle" style="text-align:center;color:#AA0000;">
    <?    echo "$errors \n Please try again. \n" ; ?>
      </div>
    <?
    }
}
if($showForm){
?>

<form method="post" name="contactUs" action="contact.php">
<table class ="contact" width="50%" border="0" align="center" cellpadding="10" cellspacing="10" bgcolor="#E1E1E1" >

  <tr>
    <td align="center">         
        Your Name:
    </td>
    <td>
        <input type="text" name="name" size ="50">
    </td>
  </tr>
  <tr>
    <td align="center">         
        Email Address:
    </td>
    <td>         
        <input type="text" name="email" size ="50">
    </td>
  </tr>
  <tr>
    <td align="center">         
        Message:
    </td>  
    <td>
        <textarea name="message" rows="7" cols="50"></textarea>
    </td>
  </tr>
  <tr>
    <td>&nbsp;
    </td>

    <td>
        <input type="submit" value="Submit">
    </td>
  </tr>
</table>
 </form>    
 <? } else { ?>   
<div class="subtitle" style="text-align:center;">
  Thank you for contacting us! We will get back to you as soon as possible!
</div>

 <? } ?>
<div style="float:left;width:830px;border:thin #CCC solid;background-color:white;border-bottom:white;background-color:#CCCCCC;">

<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-left:auto;margin-right:auto;">
  <tr>
    <td>
<?php $template->footer(); ?>
</td>
</tr>
</table>
<? if($showForm){ ?>
<script language="JavaScript" type="text/javascript" >
   
  var formValidator  = new Validator("contactUs");
  formValidator.addValidation("name","req","Please enter your Name.");
  formValidator.addValidation("name","alpha","Alphabetic characters only.");
    
  formValidator.addValidation("email","req", "Please enter your Email address.");
  formValidator.addValidation("email","email");
  
  formValidator.addValidation("message","req", "Please enter a message.");
</script>
<? } ?>
</body>
</html>