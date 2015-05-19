<?php
include_once("includes/head.inc.php"); 
include_once("includes/functions.inc.php");
$imdb = new Imdb();
$result = $imdb->getRecoForComparison($_SESSION['toveUserId']);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>WatchList</title>
<?php $template->headerFiles(false); ?>
</head>
<body class="info">

<? 
$classArray = array('reco' => "selected_tab" );
$template->addHeaderMenu($settings_data['title'], $lang_template_library, $classArray); 


?>

<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#E1E1E1" style="margin-left:auto;margin-right:auto;background-color:#E1E1E1;padding-bottom:20px;">

  <tr>
    <td bgcolor="#FCFCFC"> 
      <div style="padding:30px;margin-left:40px;float:left;left:30%;?>;">              
        <div class ="subtitle" style="margin:25px 0px 0px 10px;"> Movies in your Watch List! </div>     
        <table width="100%" border="0" cellspacing="0" cellpadding="5">    
          <? foreach ($movieArray as $movie) { ?>
            <tr>
              <td width="150px;"> 
                <div style="padding:5px;float:top;">
                  <a href="<?=$INFO ?>?type=activity&typeName=watchlist&mId=<?= $movie['mId'] ?>">
                    <img src="<?=$movie['poster']?>" width="130" height="200" />
                    <?= $movie['title'] ?>
                  </a>
                </div>
              </td>    
              <td width="200px"> <button class="watchListRemove" data-id="<?= $movie["mId"] ?>" style="margin-top:0px;">&#8212; Remove from WatchList </button>
              </td>
            </tr>
        <? } ?>
    
        </table>
      </div>
      <div style="padding:30px;margin-right:40px;float:right;right:20%?>;">              
        <div class ="subtitle" style="margin:25px 0px 0px 10px;"> Movies you have rated! </div>        
         <table width="100%" border="0" cellspacing="0" cellpadding="5">    
          <? foreach ($movieArrayRating as $movie) { ?>
            <tr>
              <td width="150px;"> 
                <div style="padding:5px;float:top;">
                  <a href="<?=$INFO ?>?type=activity&typeName=rating&mId=<?= $movie['mId'] ?>">
                    <img src="<?=$movie['poster']?>" width="130" height="200" />
                    <?= $movie['title'] ?>
                  </a>
                </div>
              </td>    
              <td width="200px"> 

              <div class="ratingBox" style="margin-top:0px;">
                  <div class="basicRating" data-average="<?= $movie['rating']?>" data-id="<?= $movie['mId'] ?>"></div>
              </div>

              </td>
            </tr>
        <? } ?>
    
        </table>
    </div>
  </td>
</tr>
</table>

<div style="float:left;width:830px;border:thin #CCC solid;background-color:white;border-bottom:white;background-color:#CCCCCC;">

<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-left:auto;margin-right:auto;">
  <tr>
    <td>
<?php $template->footer(); ?>
</td>
</tr>
</table>

</body>
</html>