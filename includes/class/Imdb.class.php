<?PHP
//This is heavily modified. Just leaving this here to remember where it came from.
//I want to move this to my server so I don't have to release new versions for every IMDb change.
/////////////////////////////////////////////////////////////////////////////////////////////////////////
// Ultimate PHP IMDb Scraper for the new IMDb Template.
// Author: Abhinay Rathore
// Website: http://www.AbhinayRathore.com
// Blog: http://web3o.blogspot.com
// Code Extended From: http://code.google.com/p/tylerhall/source/browse/trunk/media-info/class.media.php
/////////////////////////////////////////////////////////////////////////////////////////////////////////
class Imdb
{
	private $db;
	private $s3Url = "https://s3.amazonaws.com/reco-proj/images/";
	function __construct() {
		$test = "local";
		//mysqli_connect($_SERVER['RDS_HOSTNAME'], $_SERVER['RDS_USERNAME'], $_SERVER['RDS_PASSWORD'], $_SERVER['RDS_DB_NAME'], $_SERVER['RDS_PORT']);		
		if(in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ))){			
			$this->db = new mysqli("127.0.0.1", "root", "", "recoProj");				
		}else{
			$this->db = new mysqli($_SERVER['RDS_HOSTNAME'], $_SERVER['RDS_USERNAME'], $_SERVER['RDS_PASSWORD'], "recoProj");
		}
		
		if($this->db->connect_errno > 0){
			die('Unable to connect to database [' . $db->connect_error . ']');
		}

	}

	

	function listAllMovies($userId){
		$filterList = array();		
		$userSQL = '"'.$this->db->real_escape_string($userId).'"';
		$ratedQuery = "select distinct mId from userRating where userId=$userSQL";		
		if($result = $this->db->query($ratedQuery)){			
			while($row = $result->fetch_assoc()){
				array_push($filterList, $row["mId"]);
			}
		}
		$watchListQuery = "select distinct mId from userWatchList where userId=$userSQL";		
		if($result = $this->db->query($watchListQuery)){			
			while($row = $result->fetch_assoc()){
				array_push($filterList, $row["mId"]);
			}
		}
		


		$movieOuterList = array();
		$movieQuery = "select m.*,mg.genre from movie m, movieGenre mg where m.mId=mg.mId group by m.mId order by mg.genre, m.year DESC, m.createdTime DESC";		
		
		$prevGenre = null;
		$moviesList = array();
		if(!$result = $this->db->query($movieQuery)){			
			die('There was an error running the query [' . $db->error . ']');
		}
		while($row = $result->fetch_assoc()){
			if(in_array($row["mId"], $filterList)){
				continue;
			}
			$movie = array();			

			$movie["mId"] = $row["mId"];
			$mId = $movie["mId"];
			$movie["title"] = $row["title"];				
			$movie["year"] = $row["year"];
			$movie["tagline"] = $row["tagline"];
			$movie["plot"] = $row["plot"];			
			$movie["runtime"] = $row["runtime"];
			$movie["contentRating"] = $row["contentRating"];
			$movie["rating"] = 30;			
			$movie["genre"] = $row["genre"];

			if (isset($row['image']) && $row['image'] != ""){
				$movie["poster"] = $this->s3Url.$mId.".jpg";    			
  			}else{
    			$movie["poster"] = "images/unavailable.jpg";
  			}

  			if($prevGenre!= null && $movie["genre"] != $prevGenre){
  				$movieOuterList[$prevGenre] = $moviesList;
				$moviesList = array();
			}

			array_push($moviesList, $movie);
			$prevGenre = $movie["genre"];
		}
		return $movieOuterList;

	}


	function listMovies($genre, $page){
		$moviesList = array();		

		if(!empty($genre) && $genre!=""){
			$movieQuery = "select m.* from movie m, movieGenre mg where m.mId=mg.mId and mg.genre='".$genre."' order by m.year DESC, m.createdTime DESC";
		}else{
			$movieQuery = "select * from movie order by  year DESC, createdTime DESC";	
		}
		
		if(!$result = $this->db->query($movieQuery)){			
			die('There was an error running the query [' . $db->error . ']');
		}
		while($row = $result->fetch_assoc()){
			$movie = array();
			$movie["mId"] = $row["mId"];
			$mId = $movie["mId"];
			$movie["title"] = $row["title"];				
			$movie["year"] = $row["year"];
			$movie["tagline"] = $row["tagline"];
			$movie["plot"] = $row["plot"];
			
			$movie["runtime"] = $row["runtime"];
			$movie["contentRating"] = $row["contentRating"];
			$movie["rating"] = 30;



			if (isset($row['image']) && $row['image'] != ""){
				$movie["poster"] = $this->s3Url.$mId.".jpg";    			
  			}else{
    			$movie["poster"] = "images/unavailable.jpg";
  			}	

			//Cast Info
			$addInfo = $this->getMovieCast($mId);
			$movie["cast"] = $addInfo["cast"];
			$movie["writers"] = $addInfo["writers"];
			$movie["directors"] = $addInfo["directors"];
			//Genres
			$movie["genres"] =  $this->getMovieGenre($mId);
			array_push($moviesList, $movie);
		}
		return $moviesList;

	}

	function listMoviesFromName($search){

	}

	function listMoviesFromGenre($genre){

	}

	function getMovieGenre($mId){		
		//Fetching Genres
		$genreQuery = "select * from movieGenre where mId='".$mId."'";
		if(!$result = $this->db->query($genreQuery)){
			die('There was an error running the query [' . $db->error . ']');
		}
		$genres = array();
		while($row = $result->fetch_assoc()){
			array_push($genres, $row["genre"]);						
		}		
		return $genres;
	}

	function getMovieCast($mId){
		$movie = array();
		//Fetching Cast Info
		$castQuery = "select * from movieCast where mId='".$mId."'";
		if(!$result = $this->db->query($castQuery)){
			die('There was an error running the query [' . $db->error . ']');
		}
		$cast = array();
		$writers = array();
		$directors = array();
		while($row = $result->fetch_assoc()){
		
			if($row["castType"] == "director"){
				array_push($directors, $row["castName"]);
			}else if($row["castType"] == "writer"){
				array_push($writers, $row["castName"]);
			}else{
				array_push($cast, $row["castName"]);
			}	
						
		}
		$movie["cast"] = $cast;
		$movie["directors"] = $directors;
		$movie["writers"] = $writers;
	

		return $movie;		
	}

	
	function getMovieInfo($mId){
		$movie = array();
		$movieQuery = "select * from movie where mId='".$mId."'";
			//var_dump($sqlQuery);
		if(!$result = $this->db->query($movieQuery)){
			die('There was an error running the query [' . $db->error . ']');
		}
		while($row = $result->fetch_assoc()){
			$movie["mId"] = $row["mId"];
			$movie["title"] = $row["title"];				
			$movie["year"] = $row["year"];
			$movie["tagline"] = $row["tagline"];
			$movie["plot"] = $row["plot"];			
			$movie["runtime"] = $row["runtime"];
			$movie["contentRating"] = $row["contentRating"];
			$movie["rating"] = 30;


			if (isset($row['image']) && $row['image'] != ""){
				$movie["poster"] = $this->s3Url.$mId.".jpg";    			
  			}else{
    			$movie["poster"] = "images/unavailable.jpg";
  			}	
		}
		
		$addInfo = $this->getMovieCast($mId);
		$movie["cast"] = $addInfo["cast"];
		$movie["writers"] = $addInfo["writers"];
		$movie["directors"] = $addInfo["directors"];
		$movie["genres"] =  $this->getMovieGenre($mId);
		
		
		return $movie;			
	}

	function getMovieInfoOld($title, $info)
	{ //1=some information, 2=everything but images, 3=all data/images
		$title = str_ireplace('the ', '', $title);
		$url  = "http://www.google.com/search?hl=en&q=imdb+" . urlencode($title) . "&btnI=I%27m+Feeling+Lucky";
		$html = $this->geturl($url);
		if (stripos($html, "302 Moved") !== false)
			$html = $this->geturl($this->match('/HREF="(.*?)"/ms', $html, 1));
		$arr = array();
		return $this->scrapeMovieInfo($html, $info);
	}

	// Scan movie meta data from IMDb page
	function scrapeMovieInfo($html, $info)
	{
		$arr = array();
		$arr['title_id'] = $this->match('/id="(tt[0-9]+)\|imdb/ms', $html, 1);
		$arr['title'] = trim($this->match('/<title>(.*?) \(.*?<\/title>/ms', $html, 1));
			$arr['year'] = trim($this->match('/<title>.*?\(.*?([0-9][0-9][0-9][0-9]).*?\).*?<\/title>/ms', $html, 1));
			$arr['rating'] = trim($this->match('/<div class="titlePageSprite star-box-giga-star">(.*?)<\/div>/ms', $html, 1));
			$arr['creators'] = strip_tags($this->match('/Creators:(.*?)<\/div>/ms', $html, 1));
			if ($arr['creators'] == ''){
				$arr['creators'] = strip_tags($this->match('/Creator:(.*?)<\/div>/ms', $html, 1));
			}
			$arr['trailer'] = $this->match('/data-video="(.*?)"/ms', $html, 1);
			$arr['movie_rating'] = $this->match('/class="us_(.*?) titlePageSprite absmiddle"/ms', $html, 1);
			$arr['poster'] = $this->match('/<link rel=\'image_src\' href="(.*?)"/ms', $html, 1);
			$arr['genres'] = array();
			foreach ($this->match_all('/<a.*?>(.*?)<\/a>/ms', $this->match('/Genre.?:(.*?)(<\/div>|See more)/ms', $html, 1), 1) as $m)
				array_push($arr['genres'], trim($m));

			if ($info >= 2) {
				$arr['directors'] = array();
				foreach ($this->match_all('/<a.*?>(.*?)<\/a>/ms', $this->match('/Director.?:(.*?)(<\/div>|and )/ms', $html, 1), 1) as $m)
					array_push($arr['directors'], $m);

				$arr['writers'] = array();
				foreach ($this->match_all('/<a.*?>(.*?)<\/a>/ms', $this->match('/Writer.?:(.*?)(<\/div>|and )/ms', $html, 1), 1) as $m)
					array_push($arr['writers'], $m);

				$arr['cast'] = array();
				foreach ($this->match_all('/<h4 class="inline">Stars:<\/h4>(.*?)<span class="ghost">/ms', $html, 1) as $m)
					array_push($arr['cast'], trim(strip_tags($m)));

				$arr['release_date'] = $this->match('/Release Date:<\/h4>.*?([0-9][0-9]? (January|February|March|April|May|June|July|August|September|October|November|December) (19|20)[0-9][0-9]).*?(\(|<span)/ms', $html, 1);
					if ($arr['title_id'] != "") $arr['release_dates'] = $this->getReleaseDates($arr['title_id']);
					$arr['plot'] = trim(strip_tags($this->match('/<p itemprop="description">(.*?)<\/p>/ms', $html, 1)));
					$arr['runtime'] = trim($this->match('/Runtime:<\/h4>.*?([0-9]+) min.*?<\/div>/ms', $html, 1));
					$arr['top_250'] = trim($this->match('/Top 250 #([0-9]+)</ms', $html, 1));
					$arr['oscars'] = trim($this->match('/Won (.*?)<span/ms', $html, 1));
					$arr['oscars2'] = trim($this->match('/Nominated (.*?)<span/ms', $html, 1));
					$arr['storyline'] = trim(strip_tags($this->match('/Storyline<\/h2>(.*?)(<em|<\/p>|<span)/ms', $html, 1)));
					$arr['tagline'] = trim(strip_tags($this->match('/Tagline.?:<\/h4>(.*?)(<span|<\/div)/ms', $html, 1)));
				}
				if ($info == 3)
					if($arr['title_id'] != "") $arr['media_images'] = $this->getMediaImages($arr['title_id']);

				return $arr;
			}

			function getReviews($titleId){
				$url  = "http://www.imdb.com/title/" . $titleId . "/reviews";
				$html = $this->geturl($url);
				$reviews = array();
				foreach($this->match_all('/<b>(.*?)<div class="yn"/ms', $html, 1) as $r)
				{
					if (strstr($r, '***')){
						continue;
					}
					if (strstr($r, 'Reviews &#x26; Ratings')){
						continue;
					}
					if (strstr($r, '[1]')){
						continue;
					}
					$reviews[] = strip_tags($r, '<img><small><br><b><p>');
				}
				return $reviews;
			}

	// Scan all release dates
			function getReleaseDates($titleId){
				$url  = "http://www.imdb.com/title/" . $titleId . "/releaseinfo";
				$html = $this->geturl($url);
				$releaseDates = array();
				foreach($this->match_all('/<tr>(.*?)<\/tr>/ms', $this->match('/Date<\/th><\/tr>(.*?)<\/table>/ms', $html, 1), 1) as $r)
				{
					$country = trim(strip_tags($this->match('/<td><b>(.*?)<\/b><\/td>/ms', $r, 1)));
					$date = trim(strip_tags($this->match('/<td align="right">(.*?)<\/td>/ms', $r, 1)));
					array_push($releaseDates, $country . " = " . $date);
				}
				return $releaseDates;
			}

	// Collect all media img
			function getMediaImages($titleId){
				$url  = "http://www.imdb.com/title/" . $titleId . "/mediaindex";
				$html = $this->geturl($url);
				$media = array();
				$media = array_merge($media, $this->scanMediaImages($html));
				foreach($this->match_all('/<a href="\?page=(.*?)">/ms', $html, 1) as $p)
				{
					$html = $this->geturl($url . "?page=" . $p);
					$media = array_merge($media, $this->scanMediaImages($html));
				}
				return $media;
			}

	// Scan all media img
			function scanMediaImages($html){
				$pics = array();
				foreach($this->match_all('/src="(.*?)"/ms', $this->match('/<div class="media_index_thumb_list" id="media_index_thumbnail_grid">(.*?)<\/div>/ms', $html, 1), 1) as $i)
				{
			//transform thumb to full size image
					$startTagPos = strrpos($i, "V1");
					$endTagPos = strrpos($i, "jpg");
					$tagLength = $endTagPos - $startTagPos + 1;
					$i = substr_replace($i, "V1_SY500_CR27,0,0,0_.j", $startTagPos, $tagLength);
			//
					array_push($pics, $i);
				}
				return $pics;
			}

			function createPhotos($array, $max=0) {
				global $movieArray;
		$printArray = ''; //for WAMP
		
		if (empty($array))
			return;

		$i = 0;
		foreach ($array as $pics){
			if ($i >= $max)
				continue;

			$cleanID = cleanWAMP($movieArray['title_id']);

			if ($cleanID != "" && file_exists('pics/'.$cleanID."_$i.jpg") == false) 
				file_put_contents('pics/'.$cleanID."_$i.jpg", file_get_contents($pics)); 
			
			$printArray .= '<div style="float:left;padding:5px;"><a href="pics/'.$cleanID."_$i.jpg".'" data-lightbox="pics"><img width="100" height="100" src="pics/'.$cleanID."_$i.jpg".'" border="0"></a></div>';
			$i++;
		}
		return $printArray;
	}

	// ************************[ Extra Functions ]******************************
	function geturl($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		$html = curl_exec($ch);
		curl_close($ch);
		return $html;
	}

	function match_all($regex, $str, $i = 0)
	{
		if(preg_match_all($regex, $str, $matches) === false)
			return false;
		else
			return $matches[$i];
	}

	function match($regex, $str, $i = 0)
	{
		if(preg_match($regex, $str, $match) == 1)
			return $match[$i];
		else
			return false;
	}
}