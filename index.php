<?php include_once("includes/functions.inc.php"); 
$imdb = new Imdb();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?= $settings_data['title']; ?></title>
<?php $template->headerFiles(false); ?>
</head>
<body>
<div id="dropmenu1" class="dropmenudiv">
<?php $template->printGenres(); ?>
</div>

<table width="1200" border="0" align="center" cellpadding="0" cellspacing="0" class="center">
  <tr>
    <td style="color:white;"><div style="padding:5px 0px 10px 0px;"><div class="webtitle"><?= $settings_data['title']; ?></div></div></td>
  </tr> 
</table>

<table width="1200" border="0" align="center" cellpadding="0" cellspacing="0" class="center">
  <tr>
    <td class="main_tab_table"><a href="./" class="tab_link"><div class="main_tab_selected"><?= $lang_template_library; ?></div></a><a href="admin.php" class="uptab_link_deselected"><div class="main_tab"><?= $lang_template_admin; ?></div></a>
	<div class="search_div">
	
	</div></td></tr>
</table>

<table width="1200" border="0" align="center" cellpadding="0" cellspacing="0" class="center">
  <tr>
    <td class="dropmenu_container"><a class="tab_link"><div style="float:left;padding:8px 50px 8px 50px;" id="chromemenu">
    <a style="cursor:pointer;" rel="dropmenu1" class="tab_link"><?= $lang_template_genres; ?></a>
</div></a><a href="./" class="tab_link"><div style="float:left;padding:8px 20px 8px 20px;"><?= $lang_template_newVids; ?></div></a></td>
  </tr>
</table>

<table width="1200" border="0" align="center" cellpadding="0" cellspacing="0" class="center" style="background-color:#E1E1E1;padding-bottom:20px;">
  <tr>
    <td bgcolor="#FCFCFC" style="padding-bottom:20px;">
    <div class ="subtitle" style="margin:25px 0px 0px 25px;"> Newest Releases: </div>
    <div class ="slider slider1" style="width:1100px; margin-left:15px; margin-right:15px; padding-left:30px; padding-right:30px;">

    <?php 
      $movieList = $imdb->listMovies($_GET["g"], $_GET["p"]);
      $template->indexVideos($movieList); 

      ?>
    </div>
</td>
  </tr>
  <tr>
    <td bgcolor="#FCFCFC" style="padding-bottom:20px;">
    <div class ="subtitle" style="margin:25px 0px 0px 25px;"> Recommended For You: </div>
    <div class ="slider slider1" style="width:1100px; margin-left:15px; margin-right:15px; padding-left:30px; padding-right:30px;">

    <?php 
      $movieList = $imdb->listMovies($_GET["g"], $_GET["p"]);
      $template->indexVideos($movieList); 

      ?>
    </div>
</td>
  </tr>
  <tr>
    <td bgcolor="#FCFCFC" style="padding-bottom:20px;">
    <div class ="subtitle" style="margin:25px 0px 0px 25px;"> Top Picks: </div>
    <div class ="slider slider1" style="width:1100px; margin-left:15px; margin-right:15px; padding-left:30px; padding-right:30px;">

    <?php 
      $movieList = $imdb->listMovies($_GET["g"], $_GET["p"]);
      $template->indexVideos($movieList); 

      ?>
    </div>
</td>
  </tr>

</table>

<table width="1200" border="0" align="center" cellpadding="0" cellspacing="0" class="center">
  <tr>
    <td>
<?php $template->footer(true); ?>
</td>
</tr>
</table>
<script type="text/javascript">
    $(document).ready(function(){
      $('.slider1').slick({
        infinite: false,
        slidesToShow: 6,
        slidesToScroll: 3                
      });
    });
  </script>
  <script type="text/javascript">
    $(".watchList").click(function() {
        var wlproduct = $('#product').text();
        var wlquantity = $('#quantity').val();
        //clicked = $(this);
        //$(this).prop("disabled", true);
        $(this).hide();

        //console.log(clicked);
        alert('Added to wishlist!');
        return false;
        /*
        $.ajax({
          type : "POST",
            url : "{your path}/wl-script.php",
            data : { wlproduct : wlproduct, wlquantity : wlquantity },
            success : function(data) { 
              $('div#result').text('You added '+wlquantity+' '+wlproduct+'\'s to your wishlist.');
              $('#quantity').val("");
            }
        });
        */ 
    });
  </script>

</body>
</html>