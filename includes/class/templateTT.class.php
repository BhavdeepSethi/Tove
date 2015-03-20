<?php
class template {
	public function headerFiles ($mobile) {
		if ($mobile){
			?><link rel="stylesheet" href="css/mobile.css" type="text/css" />
			<meta name="viewport" content="width=300" />
			<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' /><?php
		} else {
			?><link rel="stylesheet" href="css/style.css" type="text/css" /><?php
		} ?>
		<link rel="stylesheet" href="css/style.css" type="text/css" />
		<link rel="stylesheet" href="css/jquery.fancybox.css" type="text/css" />
		<link rel="stylesheet" href="css/lightbox.css" type="text/css" />
		<link rel="stylesheet" type="text/css" href="css/jRating.jquery.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/slick.css" />		
		<link rel="stylesheet" type="text/css" href="css/slick-theme.css"/>	
		<script src="js/jquery-1.10.2.min.js"></script>
		<script src="js/jquery-ui-1.8.18.custom.min.js"></script>		

		<script src="js/jquery.fancybox.js"></script>
		<script src="js/lightbox-2.6.min.js"></script>
		<script src="js/common.js"></script>
		<script src="js/chrome.js"></script>
		<script type="text/javascript" src="js/jRating.jquery.js"></script>
		<script type="text/javascript" src="js/slick.js"></script>
		<script type="text/javascript">
				$(document).ready(function(){
						$(".basicRating").jRating({
							step:true,
        					onClick : function(element, rate) {
         						alert(rate);
        					}
      					});

      					$(".basicFixedRating").jRating({
							step:true,
							isDisabled: true        					
      					});
      				

				});
		</script>


		<meta name="robots" content="NOINDEX, NOFOLLOW">
		<?php
	}
	
	public function videoTitle($title,$year='') {
		if ($year != '')
			return $title.' ('.$year.')';
		else
			return $title;
	}	    

	public function printVideos($movieList, $fp, $page=1,$genre="",$search=""){
		global $settings_data,$lang_search_noneFound,$lang_search_category_noneFound,$lang_search_noneFound,$lang_folder_empty;
		//$files = sortFileList();
		//$files = array();		
		$files = $movieList;		

		if (empty($files)) {					
			if (isset($_GET['search']))
				$errorMSG = $lang_search_noneFound;
			else if (isset($_GET['g']))
				$errorMSG = $lang_search_category_noneFound;
			else
				$errorMSG = $lang_folder_empty;
			echo '<div class="novideosfound">'.$errorMSG.'</div>';

		}
		
		$nextpage = $page * $settings_data['page_display'];
		$previous = $nextpage - $settings_data['page_display'];
		
		$i=0;		
		foreach ($files as $entry) {
			$i++;			
			if ($fp == false) {
				if (($i > $nextpage) || ($i <= $previous))
					continue;
			} else {
				if ($i > $settings_data['fp_display'])
					break;
			}			
			?>
			<div style="float:left;padding:35px 0px 0px 0px;width:130px;height:200px; margin-left:30px;">
			<div class="one">
				<a href="info.php?mId=<?= $entry['mId'] ?>" class="hovercover">
					<div class="two"><?= $entry["title"]; ?>
					<div style="width: 100%; color:black; margin-top:3px;">Your Rating: </div>
						<div class="classification" style="margin-top:5px;">
						<div class="cover"></div>
						
						<!--<div class="progress" style="width: <?= $entry['rating'] ?>%;"></div>-->
						
						<div class="fp_yearRateTime"><?= $entry["year"]; ?> <span class="fp_rated"><?= formatRating($entry["contentRating"]); ?></span><?= $entry["runtime"]; ?>mins </div>
						<div class="ratingBox">
							<div style="color: Black; margin-top:5px; margin-right:10px; float:left" >Your Rating: </div><div class="basicRating" data-average="0" data-id="<?= $i; ?>"></div>
						</div>
						
						<div class="ratingBox">
							<div style="color: Black; margin-top:5px; margin-right:10px; float:left" >Overall Rating: </div><div class="basicFixedRating" data-average="2" data-id="<?= $i; ?>"></div>
						</div>

						<div style="height:26px;">
							<button class="watchList" >&#43; Add to WatchList </button>
						</div>

						

						
						<div class="fp_plot"><?= $entry["plot"]; ?></div>
					</div></div><img src="<?= $entry['poster'] ?>" border="0" width="130" height="200">
				</a>
				</div>
				</div>

				<?php			
			}
			if ($fp == false){
				$prev = $page - 1;
				$prev_link = '<div class="nav"><a href="./?p='.$prev; 
				if ($genre != "")
					$prev_link .= '&g='.$genre; 
				if ($search != "")
					$prev_link .= '&search='.$search; 
				$prev_link .= '">< back</a></div>';

				$next = $page + 1;
				$next_link = '<div class="nav"><a href="./?p='.$next; 
				if ($genre != "")
					$next_link .= '&g='.$genre; 
				if ($search != "")
					$next_link .= '&search='.$search; 
				$next_link .= '">next ></a></div>';

				if ($page == 1)
					$prev_link = strip_tags($prev_link);
				if ($i <= $nextpage)
					$next_link = strip_tags($next_link);
				echo '<div class="navcontainer"><div class="navprev">'.$prev_link.'</div>
				<div class="navnext">'.$next_link.'</div></div>';
			}


		}

		public function printMobileVideos($fp, $name="",$cat=""){
			global $settings_data,$lang_search_searchingFor;
			$searchContent = "";
			if (isset($_POST['sname']))
				$searchContent = $_POST['sname'];
			
			if ($fp == false){
				if ($name != "")
					echo '<div class="mobilesearch"><h3>'.$lang_search_searchingFor.' "'.$searchContent.'"</h3></div>';
				else if ($cat != "" && $name != "")
					echo '<div class="pick">'.$lang_mobile_pickVideo.'</div>';
			}
			$files = sortFileList();

			$i=1;
			foreach ($files as $entry) {
				$videodata = explode("\n", file_get_contents($entry));
				$movie_rating = $videodata[5] * 10;
				if ($cat != "" && strstr($videodata[4], $cat) == false)
					continue;
				?>

				<div style="width:100%;height:170px;"><a href="m_info.php?name=<?php echo urlencode($videodata[1]); ?>&raw=<?php echo urlencode(cleanWAMP($videodata[6])); ?>" style="text-decoration:none;"><div style="float:left;"><img src="<?php echo $this->printPoster($videodata[3]); ?>" border="0" width="100" height="150"></div><div class="mobile_video_title"><?php echo $videodata[1]; ?><br>

				<div align="left"><div class="classification"><div class="cover"></div>
				<div class="progress" style="width:<?php echo $movie_rating; ?>%;"></div>
				<div class="mobile_rated">Rated: <?php echo formatRating($videodata[7]); ?></div>
				<div class="mobile_plot"><?php echo $videodata[8]; ?></div>
			</div></div></div></a></div>

			<?php
			if ($fp == true){
				if ($i == $settings_data['m_display'])
					break;
			}

			$i++;
		}
	}

	public function printGenres(){
		global $settings_data;
		$genres = arraySettings($settings_data["genres"]);

		foreach ($genres as $genre)
			echo '<a href="./?g='.strtolower($genre).'">'.$genre.'</a>';
	}

	public function printMGenres($category){
		global $settings_data;
		$genres = arraySettings($settings_data["genres"]);

		foreach ($genres as $genre) {
			if ($category == $genre)
				echo '<option value="'.$genre.'" selected>'.$genre.'</option>';
			else
				echo '<option value="'.$genre.'">'.$genre.'</option>';
		}
	}

	
	public function mobileSearch () {
		if (isset($_GET['search'])) {
			$searchContent = "";
			if (isset($_POST['sname']))
				$searchContent = $_POST['sname']; ?>
			<div align="center" style="padding-bottom:10px;">
				<form action="mobile.php?search" method="post">
					<input name="sname" type="text" style="width:190px;font-size:16px;" value="<?php echo $searchContent; ?>" />
					<input name="" type="submit" value="search" />
				</form></div>
				<?php
			} else if (isset($_GET['browse'])) { ?>
			<div align="center" style="margin-bottom:20px;"><form action="mobile.php?browse" method="post">
				<select name="category" style="font-size:16px;">
					<?php echo $this->printMGenres($_POST['category']); ?>
				</select>
				<input name="" type="submit" value="go" /></form></div>
				<?php
			}
		}

		public function episodeLists() {
			global $garbage,$settings_data;

			if (!file_exists($_GET['raw']))
			return; //error
		
		$files = sortSeasonsList($_GET['raw']);
		
		$episodes='';
		$episodeList = array();
		foreach ($files as $folder){
			$path = $_GET['raw'].'/'.$folder;
			if (is_dir($path) == true){
				if ($handle = opendir($path)) {
					$episodes.='<h3>'.$folder.'</h3>';
					//put episodes in array, filter garbage, sort array
					while (false !== ($entry = readdir($handle))) {
						if (in_array($entry, $garbage))
							continue;

						$episodeList[] = $entry;
					}
					$episodeFiles = sortEpisodesList($episodeList);
					foreach ($episodeFiles as $episodeFile){
						$episodes.= '<a href="play.php?video='.urlencode($_GET['raw']).'/'.urlencode($folder).'/'.urlencode($episodeFile).'" onClick="return playvideo(\'play.php?video='.urlencode($_GET['raw']).'/'.urlencode($folder).'/'.urlencode($episodeFile).'\')">'.clean_title($episodeFile).'</a><br>';
					}
					unset($episodeList);
				}
				closedir($handle);
			} else //outside files. Filter garbage, sort array, print after loop
			$extras[] = str_replace($_GET['raw'].'/','',$path);
		}
		
		if (!empty($extras)){
			$extraFiles = sortEpisodesList($extras);
			foreach ($extraFiles as $extraFile){
				$episodes.= '<p><a href="play.php?video='.urlencode($_GET['raw']).'/'.$extraFile.'" onClick="return playvideo(\'play.php?video='.urlencode($_GET['raw']).'/'.$extraFile.'\')">'.clean_title($extraFile).'</a></p>';
			}
		}
		return $episodes;
	}

	public function mobileEpisodeLists(){
		global $garbage;
		
		if (!file_exists($_GET['raw']))
			return; //error
		
		$files = sortSeasonsList($_GET['raw']);
		
		$episodes='';
		$episodeList = array();
		foreach ($files as $folder){
			$path = $_GET['raw'].'/'.$folder;
			if (is_dir($path) == true){
				if ($handle = opendir($path)) {
					$episodes.='<h3>'.$folder.'</h3>';
					//put episodes in array, filter garbage, sort array
					while (false !== ($entry = readdir($handle))) {
						if (in_array($entry, $garbage))
							continue;

						$episodeList[] = $entry;
					}
					$episodeFiles = sortEpisodesList($handle,$episodeList);
					foreach ($episodeFiles[0] as $episodeFile){
						$episodes.= '<a href="play.php?video='.urlencode($_GET['raw']).'/'.urlencode($folder).'/'.urlencode($episodeFile).'" onClick="return playvideo(\'play.php?video='.urlencode($_GET['raw']).'/'.urlencode($folder).'/'.urlencode($episodeFile).'\')">'.clean_title($episodeFile).'</a><br>';
					}
					unset($episodeList);
				}
				closedir($handle);
			} else //outside files. Filter garbage, sort array, print after loop
			$extras[] = str_replace($_GET['raw'].'/','',$path);
		}
		
		if (!empty($extras)){
			$extraFiles = sortEpisodesList($handle,$extras);
			foreach ($extraFiles[0] as $extraFile){
				$episodes.= '<p><a href="play.php?video='.urlencode($_GET['raw']).'/'.$extraFile.'" onClick="return playvideo(\'play.php?video='.urlencode($_GET['raw']).'/'.$extraFile.'\')">'.clean_title($extraFile).'</a></p>';
			}
		}
		return $episodes;
	}
	
	public function mobileVideos () {
		if (isset($_GET['browse']))
			$this->printMobileVideos(false, "", $_POST['category']);
		else if (isset($_GET['search']) && isset($_POST['sname']))
			$this->printMobileVideos(false, $_POST['sname']);
		else
			$this->printMobileVideos(true);
	}
	
	public function indexVideos ($movieList) {
		global $settings_data;		
		if (isset($_GET['search']))
			$this->printVideos($movieList, false, $_GET['p'], "", $_GET['search']);
		else if (isset($_GET['g']))
			$this->printVideos($movieList, false, $_GET['p'], $_GET['g']);
		else
			$this->printVideos($movieList, true);
	}
	
	public function footer($checkMobile = false) {
		global $lang_template_softwareBy,$lang_template_personalUse,$lang_mobile_mobile;
		if ($checkMobile == true) {
			if (checkMobile()==true) { ?>
			<p><a href="mobile.php?mobile" style="color:white;font-size:24px;"><?php echo $lang_mobile_mobile; ?></a></p>
			<?php } }		
		}
	}
	?>