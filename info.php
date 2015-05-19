<?php
include_once("includes/head.inc.php"); 
include_once("includes/functions.inc.php");
$imdb = new Imdb();
$BYPASS = true;

$movieId = $_GET["mId"];
if(isset($_GET["type"])){
  $type = $_GET["type"];
}else{
  $type = "self";
}
$typeName = $_GET["typeName"];
trackActivity($_SESSION['toveUserId'], $movieId, $type, $typeName, $SOURCE, $CURRENT);

$movieArray = $imdb->getMovieInfo($movieId);
$metaArray = $imdb->getMovieMetaInfo($_SESSION['toveUserId'], $movieId);

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?= $template->videoTitle($movieArray['title'],$movieArray['year']); ?></title>
<?php $template->headerFiles(false, $DATA_POST, $SEARCH); ?>
</head>
<body class="info">

<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-left:auto;margin-right:auto;">
  <tr>
    <td style="color:white;"><div style="padding:20px 0px 10px 0px;"><div class="webtitle"><?= $settings_data['title'] ?></div></div></td>
  </tr> 
</table>

<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" class="center">
  <tr>
    <td class="main_tab_table"><a href="<?= $INDEX ?>" class="tab_link"><div class="main_tab_selected"><?= $lang_template_library; ?></div></a>

  <div class="main_tab" style="float:right;">
    <span class="uptab_link_deselected" style="cursor:default;">
    Welcome, <?= $_SESSION["toveUserName"]?>! &nbsp;
    <a href="<?= $LOGOUT ?>">Log Out</a>
    </span>
  </div>
  <div class="search_div">
    <div class="ui-widget">
      <label for="search">Search: </label>
      <input id="search" />
    </div>
    
  </td></tr>
</table>


<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" style="margin-left:auto;margin-right:auto;">
  <tr>
    <td class="dropmenu_container"><a class="tab_link"><div  style="float:left;padding:8px 50px 8px 50px;">
    <a href="<?= $INIT ?>" class="tab_link">Browse</a>
</div></a><a href="<?= $ACTIVITY ?>" class="tab_link"><div style="float:left;padding:8px 20px 8px 20px;">View Your Activity</div></a></td>
  </tr>
</table>

<table width="90%" border="2" align="center" cellpadding="0" cellspacing="0" bgcolor="#E1E1E1" style="margin-left:auto;margin-right:auto;background-color:#E1E1E1;padding-bottom:20px;">

  <tr>
    <td bgcolor="#FCFCFC">
    <div style="padding:40px;float:left;">
      <img src="<?=$movieArray['poster']?>" width="130" height="200" />
      
    </div>

    <div style="padding:20px;float:left;?>;">
    <h2><?= $movieArray['title']; ?></h2>
    <div style="float:left;"><?= $movieArray['year']; ?></div>
    <div style="float:left;padding-left:40px;">
      <?php if ($movieArray['contentRating'] != '' && $movieArray['contentRating'] != 'False'){
        echo formatRating($movieArray['contentRating']); 
       } else {
        ?>Unrated<?php 
      } ?></div>
      <div style="float:left;padding-left:40px;"><?= $movieArray['runtime']; ?> minutes</div><br /><br />
      <div style="width:500px;overflow:wrap;"> 
	<?= $movieArray['plot']; ?><br /><br />
  </div>

    <table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="66" align="left" valign="top"><strong><?= $lang_info_cast; ?>:</strong></td>
    <td width="464"><?php $cast_count = count($movieArray['cast']); $i = 0; foreach ($movieArray['cast'] as $cast) { $i++; echo ucwords($cast); if ($i >= $cast_count) { continue; } else { echo ', '; } } ?> <a href="http://www.imdb.com/title/<?= $movieArray['mId'] ?>/fullcredits#cast" onClick="return popitup('http://www.imdb.com/title/<?= $movieArray['mId'] ?>/fullcredits#cast')" style="text-decoration:none;">... more</a></td>
  </tr>
  <tr>
    <td align="left" valign="top"><strong><?= $lang_info_director; ?>:</strong></td>
    <td><?php $cast_count = count($movieArray['directors']); $i = 0; foreach ($movieArray['directors'] as $cast) { $i++; echo ucwords($cast); if ($i >= $cast_count) { continue; } else { echo ', '; } } ?></td>
  </tr>
  <tr>
    <td align="left" valign="top"><strong><?= $lang_info_writer; ?>:</strong></td>
    <td><?php $cast_count = count($movieArray['writers']); $i = 0; foreach ($movieArray['writers'] as $cast) { $i++; echo ucwords($cast); if ($i >= $cast_count) { continue; } else { echo ', '; } } ?></td>
  </tr>
  <tr>
    <td align="left" valign="top"><strong><?= $lang_info_genre; ?>:</strong></td>
    <td><?php $cast_count = count($movieArray['genres']); $i = 0; foreach ($movieArray['genres'] as $cast) { $i++; echo ucwords($cast); if ($i >= $cast_count) { continue; } else { echo ', '; } } ?></td>

  </tr>
</table>
</div>

<!--<div style="clear:both;">&nbsp;</div>  -->
<div style="float:left;font-size:16px;padding:50px 20px 20px 20px;"><b><?= $lang_info_details; ?></b><hr />
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="126" align="left" valign="top"><strong>IMDb Rating:</strong></td>
    <td width="109"><?php if ($movieArray['imdbRating'] != '') { echo $movieArray['imdbRating']; } else { echo 'N/A'; } ?></td>
  </tr>
  <tr>
    <td align="left" valign="top"><strong>IMDb Votes:</strong></td>
    <td><?php if ($movieArray['imdbVotes'] != '') { echo $movieArray['imdbVotes']; } else { echo 'N/A'; } ?></td>    
  </tr>
  <tr>
    <td align="left" valign="top"><strong>Tagline:</strong></td>
    <td><?php if($movieArray['tagline']!=''){ echo $movieArray['tagline']; } else { echo 'N/A'; } ?></td>
  </tr>
  <tr>
    <td align="left" valign="top"><strong>Overall Rating:</strong></td>
    <td valign="top">
      <div class="ratingBox" style="margin-top:0px;">
              <div class="basicFixedRating" data-average="<?= $metaArray['overallRating']?>" data-id="<?= $movieArray['mId'] ?>"></div>
      </div>
    </td>
  </tr>
  <tr>
    <td align="left" valign="top"><strong>Your Rating:</strong></td>
    <td>
    <div class="ratingBox" style="margin-top:0px;">
          <div class="basicRating" data-average="<?= $metaArray['rating']?>" data-id="<?= $movieArray['mId'] ?>"></div>
    </div>
    </td>
  </tr>
  <tr>
    <td align="left" valign="top"><strong>WatchList:</strong></td>
    <td>
    <div style="min-height:26px;">
              <? 
              //var_dump($metaArray);
              if($metaArray['watchList']){ 
                  $watchStyle = "none";
                  $watchStyleRemove = "block";
              ?>

              <span class="" style="display: <?= $watchStyle ?>;"> Removed from WatchList!</span>
              <button class="watchListRemove" data-id="<?= $movieArray["mId"] ?>" style="margin-top:0px; display: <?= $watchStyleRemove ?>;">&#8212; WatchList </button>
                  
                
              <?

              }else{
                  $watchStyle = "block";
                  $watchStyleRemove = "none";
                  ?>

                <button class="watchList" data-id="<?= $movieArray["mId"] ?>" style="margin-top:0px; display: <?= $watchStyle ?>;">&#43; WatchList </button>
                <span class="" style="display: <?= $watchStyleRemove ?>;"> Added to WatchList!</span>
                  
              <?
                }            
              ?>
                          

                
            </div>                      
    </td>
  </tr>
  
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</div>

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