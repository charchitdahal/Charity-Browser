<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
// example of how to use basic selector to retrieve HTML contents
include('simple_html_dom.php');
include("inc.pd2text.php");
$CrawlingCompleted=NULL;
$PDFFound=NULL;

//////////////////////////////
//Image Crawling Functions////
function ImageCrawl($database_saha,$saha,$url,$ImageHeight,$ImageWidth,$BatchCount,$count,$actual_url,$MaxLinksPerSite){
$html = file_get_html($url);
	   
$urlBase=parse_url($url,PHP_URL_HOST);
//$urlBasePath=parse_url($url,PHP_URL_PATH);
//$urlBasePath=unparse_url(parse_url($url));
$parts = explode('/',$url);
$urlBasePath = "";
for ($i = 0; $i < count($parts) - 1; $i++) {
 $urlBasePath .= $parts[$i] . "/";
}

$iURLCount=0;
foreach($html->find('img') as $e) {
$iURLCount++;
if($iURLCount<($MaxLinksPerSite+1)){

    $metaType= $e->src;
	$SRC= $urlBasePath.$metaType;
	//echo $SRC."<br>";
    $metaType= $e->alt;
	$ALT= $metaType;

$SRCparts = explode('/',$SRC);
$SRCparts=$SRCparts[count($SRCparts) - 1];
$SRCparts = explode(' ',$SRC);
$SRCparts=implode(" ",$SRCparts);
$SRCparts = explode('_',$SRC);
$SRCparts=implode(" ",$SRCparts);
$SRCparts = explode('-',$SRC);
$SRCparts=implode(" ",$SRCparts);
$ALT= $ALT." ".$SRCparts;

	
$file = $SRC;
ini_set('user_agent', 'Mozilla/40.0');
$file_headers = @get_headers($file);
$pos200 = strpos($file_headers[0], "200");
$pos301 = strpos($file_headers[0], "301");
$pos302 = strpos($file_headers[0], "302");

if($pos200>0 || $pos301>0 || $pos302>0) {
    $exists = false;
}else {
    $exists = true;
}
list($width, $height) = @getimagesize($SRC);

if($exists=="true"){	
	
mysqli_select_db($saha, $database_saha);$query_selectedCrawlImage = "SELECT * FROM crawl_images WHERE crawl_images.image_url= '".addslashes($SRC)."'";
$selectedCrawlImage = mysqli_query($saha, $query_selectedCrawlImage) or die(mysqli_error($saha));
$row_selectedCrawlImage = mysqli_fetch_assoc($selectedCrawlImage);
$totalRows_selectedCrawlImage = mysqli_num_rows($selectedCrawlImage);

if($totalRows_selectedCrawlImage==0 && $ALT!="" && $SRC!="" && $width>=$ImageWidth && $height>=$ImageHeight){

$count++;
if($count==$BatchCount){
$CrownlingPending="true";	
$_SESSION['CrownlingPending']="true";
}
	
	    $insertSQL = sprintf("INSERT INTO crawl_images (`keywords`, `image_url`, `current_url`, `actual_url`, `base_url`) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString(addslashes($ALT), "text"),
                       GetSQLValueString(addslashes($SRC), "text"),
                       GetSQLValueString(addslashes($url), "text"),
                       GetSQLValueString(addslashes($actual_url), "text"),
                       GetSQLValueString(addslashes($urlBase), "text"));

  mysqli_select_db($saha, $database_saha);$Result1 = mysqli_query($saha, $insertSQL) or die(mysqli_error($saha));
	
//echo $SRC." ".$ALT." ".$count;
}
}
}

}
}

////////////////////////////////
//Data Crawling Functions///////
function CrawlData($url,$BodyLengh){
// get DOM from URL or file
$metaTitle= NULL;
$metaDescription= NULL;
$metaKeywords= NULL;
$plainText= NULL;
$metaType= NULL;

$html = file_get_html($url);

// find all link
foreach($html->find('title') as $e){ 
$metaTitle= $e->plaintext;
$metaTitle=str_replace('\'','',$metaTitle);
$metaTitle=str_replace('\"','',$metaTitle);
	//echo $metaTitle."<br>";
}
foreach($html->find('body') as $e){ 
$plainText= $e->plaintext;
$plainText=strip_tags($plainText);
$plainText=preg_replace('/\s+/', ' ', $plainText);
$plainText=substr($plainText,0,$BodyLengh);
$plainText=str_replace('\'','',$plainText);
$plainText=str_replace('\"','',$plainText);
	//echo $plainText."<br>";
}

foreach($html->find('meta') as $e) {
    $metaType= $e->name;
	//echo $metaType.":<br>";
	if($metaType=="description"){
    $metaDescription= $e->content;
$metaDescription=str_replace('\'','',$metaDescription);
$metaDescription=str_replace('\"','',$metaDescription);
	//echo $metaDescription."<br>";
	}
}

foreach($html->find('meta') as $e) {
    $metaType= $e->name;
	//echo $metaType.":<br>";
	if($metaType=="keywords"){
    $metaKeywords= $e->content;
$metaKeywords=str_replace('\'','',$metaKeywords);
$metaKeywords=str_replace('\"','',$metaKeywords);
	//echo $metaKeywords."<br>";
	}
}
	
return array($metaTitle,$metaDescription,$metaKeywords,$plainText);
}




function CrawlURLList($database_saha,$saha,$CURL,$count,$CrownlingPending,$BodyLengh,$BatchCount,$MaxLinksPerSite,$URLMode){
$url=$CURL;
$file = $CURL;
ini_set('user_agent', 'Mozilla/40.0');
$file_headers = @get_headers($file);
$pos200 = strpos($file_headers[0], "200");
$pos301 = strpos($file_headers[0], "301");
$pos302 = strpos($file_headers[0], "302");

if($pos200>0 || $pos301>0 || $pos302>0) {
    $exists = true;
}
else {
    $exists = false;
}

if( $exists==true){
$XMLPageSubmit="false";	
if(substr($url,-4)==".xml"){
$XMLPageSubmit="true";	
$urlBase=parse_url($url,PHP_URL_HOST);
$LinkList=NULL;

$urls = array();  

$DomDocument = new DOMDocument();
$DomDocument->preserveWhiteSpace = false;
$DomDocument->load($url);
$DomNodeList = $DomDocument->getElementsByTagName('loc');

foreach($DomNodeList as $url) {
    $urls[] = $url->nodeValue;
}



foreach ($urls as $entry) {
	$LinkList.=$entry.",";
}
$LinkList= substr($LinkList,0,-1);
$coluMs = explode(",",$LinkList);

}else{
	
$urlBase=parse_url($url,PHP_URL_HOST);
//$urlBasePath=parse_url($url,PHP_URL_PATH);
//$urlBasePath=unparse_url(parse_url($url));
$parts = explode('/',$url);
$urlBasePath = "";
for ($i = 0; $i < count($parts) - 1; $i++) {
 $urlBasePath .= $parts[$i] . "/";
}
//echo $urlBasePath;
$html = file_get_html($CURL);

$urlBase=parse_url($url,PHP_URL_HOST);
$LinkList=NULL;

foreach($html->find('a') as $e) {
    $metaType= $e->href;
	if($metaType!="#" && $metaType!="javascript:void(0);" && $metaType!="javascript:void();"){
	if((substr($metaType,0,8)==substr($urlBase,0,8))){ //http://
	$LinkList.= $metaType.",";
	}else if(substr($metaType,0,4)!="http" && substr($metaType,0,4)!="www" && substr($metaType,0,4)!=substr($urlBase,0,4)){
	$LinkList.=$urlBasePath.$metaType.",";
	}else if(substr($metaType,0,4)=="http"){
	$LinkList.=$metaType.",";
	}
	}
}

$LinkList= substr($LinkList,0,-1);
$coluMs = explode(",",$LinkList);
}

//$urlBase=parse_url($url,PHP_URL_HOST);
//echo count($coluMs);
$iURLCount=0;
foreach ($coluMs as $value) {
$iURLCount++;
$value1=addslashes($value);

//echo $value1;

mysqli_select_db($saha, $database_saha);$query_selectedCrawl = "SELECT * FROM crawl WHERE crawl.current_url= '$value1'";
$selectedCrawl = mysqli_query($saha, $query_selectedCrawl) or die(mysqli_error($saha));
$row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl);
$totalRows_selectedCrawl = mysqli_num_rows($selectedCrawl);

//echo $totalRows_selectedCrawl;
//echo $iURLCount." ".substr($value,-4)."<br>";

$urlBase2=parse_url($value1,PHP_URL_HOST);

if($iURLCount<($MaxLinksPerSite+1)){
if($totalRows_selectedCrawl==0 && $CURL!="" && @$count<$BatchCount && ($urlBase==$urlBase2 || $XMLPageSubmit=="true")){
	

if(substr($value,-4)!=".pdf"){ //HTML Pages
list($metaTitle,$metaDescription,$metaKeywords,$plainText) =CrawlData($value,$BodyLengh); 

if($metaTitle!=""){  
$count++;
if($count==$BatchCount){
$CrownlingPending="true";	
$_SESSION['CrownlingPending']="true";
}
/*
echo $plainText."<br>";
echo $metaDescription."<br>";
echo $metaKeywords."<br>";
echo $metaTitle."<br>";
echo $value."<br>";
echo $CURL."<br>";
echo $urlBase."<br>";
*/
$plainText=str_replace("\"","",$plainText);
$plainText=str_replace("'","",$plainText);

$metaDescription=str_replace("\"","",$metaDescription);
$metaDescription=str_replace("'","",$metaDescription);

$metaKeywords=str_replace("\"","",$metaKeywords);
$metaKeywords=str_replace("'","",$metaKeywords);

$metaTitle=str_replace("\"","",$metaTitle);
$metaTitle=str_replace("'","",$metaTitle);

$headers = get_headers($value, 1);
$ContentType = $headers["Content-Type"];

	    $insertSQL = sprintf("INSERT INTO crawl (ContentType, `content`, `description`, `keywords`, `title`, `current_url`, `actual_url`, `base_url`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($ContentType, "text"),
                       GetSQLValueString(addslashes($plainText), "text"),
                       GetSQLValueString(addslashes($metaDescription), "text"),
                       GetSQLValueString(addslashes($metaKeywords), "text"),
                       GetSQLValueString(addslashes($metaTitle), "text"),
                       GetSQLValueString(addslashes($value), "text"),
                       GetSQLValueString(addslashes($CURL), "text"),
                       GetSQLValueString(addslashes($urlBase), "text"));

  mysqli_select_db($saha, $database_saha);$Result1 = mysqli_query($saha, $insertSQL) or die(mysqli_error($saha));
}
}else{ //PDF Docuemnts

$count++;
$PDFFound++;

$headers = get_headers($value, 1);
$ContentType = $headers["Content-Type"];
$pdfText=pdf2text($value);

	    $insertSQL = sprintf("INSERT INTO crawl (pdf, ContentType, `content`, `description`, `keywords`, `title`, `current_url`, `actual_url`, `base_url`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString("Y", "text"),
                       GetSQLValueString($ContentType, "text"),
                       GetSQLValueString(substr($pdfText,0,$BodyLengh), "text"),
                       GetSQLValueString("PDF Document", "text"),
                       GetSQLValueString("PDF Document", "text"),
                       GetSQLValueString(substr($pdfText,0,80)."...", "text"),
                       GetSQLValueString(addslashes($value), "text"),
                       GetSQLValueString(addslashes($CURL), "text"),
                       GetSQLValueString(addslashes($urlBase), "text"));

  mysqli_select_db($saha, $database_saha);$Result1 = mysqli_query($saha, $insertSQL) or die(mysqli_error($saha));
	
  
}

}else if($urlBase!=$urlBase2 && $URLMode=="Y"){

$CURL= mysqli_real_escape_string($saha,$value);

$urlBase=parse_url($CURL,PHP_URL_HOST);
  
mysqli_select_db($saha, $database_saha);$query_selectedCrawl = "SELECT * FROM settings WHERE settings.actual_url= '$CURL'";
$selectedCrawl = mysqli_query($saha, $query_selectedCrawl) or die(mysqli_error($saha));
$row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl);
$totalRows_selectedCrawl = mysqli_num_rows($selectedCrawl);

if($totalRows_selectedCrawl==0 && $urlBase!=""  && $CURL!=""){
	    $insertSQL = sprintf("INSERT INTO settings (spidered_url, user, time, spider_mode, actual_url, base_url) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString("Yes", "text"),
                       GetSQLValueString($_SESSION['MM_UsernameAdMIN'], "text"),
                       GetSQLValueString(time(), "int"),
                       GetSQLValueString("N", "text"),
                       GetSQLValueString($CURL, "text"),
                       GetSQLValueString($urlBase, "text"));

  mysqli_select_db($saha, $database_saha);$Result1 = mysqli_query($saha, $insertSQL) or die(mysqli_error($saha));
}

}
}


}
}
}

?>