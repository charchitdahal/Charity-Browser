<?php require_once('inc-main.php'); ?>
<?php 
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  // $theValue = function_exists("mysqli_real_escape_string") ? mysqli_real_escape_string($saha,$theValue) : mysqli_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

//Crawling
/* 
require("inc.crawl.php");
CrawlURL($database_saha,$saha);  
 */
//Search
$lim=10;

if(@$_GET['stt']==""){
	$stt=0;
}else{
	$stt=@$_GET['stt'];
}

if((@$_GET['q'])!=""){

$cid=mysqli_real_escape_string($saha,$_GET['q']);
$stt=mysqli_real_escape_string($saha,$stt);

mysqli_select_db($saha, $database_saha);$query_imagesFound = "SELECT * FROM crawl_images WHERE crawl_images.deleted='N' AND  (crawl_images.image_url LIKE '%$cid%' OR crawl_images.current_url LIKE '%$cid%'  OR crawl_images.keywords LIKE '%$cid%') LIMIT 5";
$imagesFound = mysqli_query($saha, $query_imagesFound) or die(mysqli_error($saha));
$row_imagesFound = mysqli_fetch_assoc($imagesFound);
$totalRows_imagesFound = mysqli_num_rows($imagesFound);

mysqli_select_db($saha, $database_saha);$query_imagesFoundTotal = "SELECT * FROM crawl_images WHERE crawl_images.deleted='N' AND  (crawl_images.image_url LIKE '%$cid%' OR crawl_images.current_url LIKE '%$cid%'  OR crawl_images.keywords LIKE '%$cid%')";
$imagesFoundTotal = mysqli_query($saha, $query_imagesFoundTotal) or die(mysqli_error($saha));
$row_imagesFoundTotal = mysqli_fetch_assoc($imagesFoundTotal);
$totalRows_imagesFoundTotal = mysqli_num_rows($imagesFoundTotal);

mysqli_select_db($saha, $database_saha);$query_total = "SELECT * FROM crawl WHERE crawl.deleted='N' AND (crawl.title LIKE '%$cid%' OR crawl.description LIKE '%$cid%'  OR crawl.current_url LIKE '%$cid%'  OR crawl.keywords LIKE '%$cid%'  OR crawl.content LIKE '%$cid%')";
$total = mysqli_query($saha, $query_total) or die(mysqli_error($saha));
$row_total = mysqli_fetch_assoc($total);
$totalRows_total = mysqli_num_rows($total);

//Writing Log

if(@$_GET['stt']==""){

$keyword=strtolower($cid);

mysqli_select_db($saha, $database_saha);$query_selectedCrawl1 = "SELECT * FROM log WHERE log.keyword='$keyword'";
$selectedCrawl1 = mysqli_query($saha, $query_selectedCrawl1) or die(mysqli_error($saha));
$row_selectedCrawl1 = mysqli_fetch_assoc($selectedCrawl1);
$totalRows_selectedCrawl1 = mysqli_num_rows($selectedCrawl1);

if($totalRows_total>0){	
	    $insertSQL = sprintf("INSERT INTO log (count, ip, results, date, keyword, time) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($totalRows_selectedCrawl1+1, "int"),
                       GetSQLValueString($_SERVER['REMOTE_ADDR'], "text"),
                       GetSQLValueString($totalRows_total, "int"),
                       GetSQLValueString(date("Y-m-d"), "date"),
                       GetSQLValueString(strtolower($cid), "text"),
                       GetSQLValueString(date("H:i:s"), "date"));

  mysqli_select_db($saha, $database_saha);$Result1 = mysqli_query($saha, $insertSQL) or die(mysqli_error($saha));
}
}
//End Writing Log


mysqli_select_db($saha, $database_saha);$query_searchResults = "SELECT * FROM crawl WHERE crawl.deleted='N' AND (crawl.title LIKE '%$cid%' OR crawl.description LIKE '%$cid%'  OR crawl.current_url LIKE '%$cid%'  OR crawl.keywords LIKE '%$cid%'  OR crawl.content LIKE '%$cid%') LIMIT $stt,$lim";
$searchResults = mysqli_query($saha, $query_searchResults) or die(mysqli_error($saha));
$row_searchResults = mysqli_fetch_assoc($searchResults);
$totalRows_searchResults = mysqli_num_rows($searchResults);



}
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);

function searchedSample($database_saha, $saha,$searcTerm,$queryID){

mb_http_input("utf-8");
mb_http_output("utf-8");

$searcTerm=mysqli_real_escape_string($saha,$searcTerm);
$queryID=mysqli_real_escape_string($saha,$queryID);

mysqli_select_db($saha, $database_saha);$query_searchResultsSample = "SELECT * FROM crawl WHERE crawl.id='$queryID' AND crawl.deleted='N' AND (crawl.title LIKE '%$searcTerm%' OR crawl.description LIKE '%$searcTerm%'  OR crawl.current_url LIKE '%$searcTerm%'  OR crawl.keywords LIKE '%$searcTerm%'  OR crawl.content LIKE '%$searcTerm%')";
$searchResultsSample = mysqli_query($saha, $query_searchResultsSample) or die(mysqli_error($saha));
$row_searchResultsSample = mysqli_fetch_assoc($searchResultsSample);
$totalRows_searchResultsSample = mysqli_num_rows($searchResultsSample);

$description=NULL;
$descriptionArray=explode(" ",$row_searchResultsSample['description']);
$fileCount=0;
if(is_array($descriptionArray)){
	foreach ($descriptionArray as $key) { 
	$fileCount++;
		if($fileCount<32){
			
			$LowerStr=strtoupper($key);
			$LowersearcTerm=strtoupper($searcTerm);
			if(strpos($LowerStr,$LowersearcTerm)!== false){
				$keyWard="<strong style=\" text-decoration:underline;\">$key</strong>";
			}else{
				$keyWard=$key;
			}
		$description.=$keyWard." ";
		}
	}
}

$content=NULL;
$contentArray=explode(" ",$row_searchResultsSample['content']);
$fileCount=0;
if(is_array($contentArray)){
	foreach ($contentArray as $key) { 
	$fileCount++;
		if($fileCount<32){
			
			$LowerStr=strtoupper($key);
			$LowersearcTerm=strtoupper($searcTerm);
			if(strpos($LowerStr,$LowersearcTerm)!== false){
				$keyWard="<strong style=\" text-decoration:underline;\">$key</strong>";
			}else{
				$keyWard=$key;
			}
		$content.=$keyWard." ";
		}
	}
}

$keywords=NULL;
$keywordsArray=explode(" ",$row_searchResultsSample['keywords']);
$fileCount=0;
if(is_array($keywordsArray)){
	foreach ($keywordsArray as $key) { 
	$fileCount++;
		if($fileCount<32){
			$LowerStr=strtoupper($key);
			$LowersearcTerm=strtoupper($searcTerm);
			if(strpos($LowerStr,$LowersearcTerm)!== false){
				$keyWard="<strong style=\" text-decoration:underline;\">$key</strong>";
			}else{
				$keyWard=$key;
			}
		$keywords.=$keyWard." ";
		}
	}
}
return $description."..".$content."..".$keywords; //."..".substr($row_searchResultsSample['content'],0,100)."..".substr($row_searchResultsSample['keywords'],0,100);
}
?>