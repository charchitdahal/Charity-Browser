<?php require_once('../Connections/saha.php'); ?>
<?php require_once('../inc-main.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_UsernameAdMIN  set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_UsernameAdMIN'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_UsernameAdMIN'], $_SESSION['MM_UserGroupADmin'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}


?>
<?php
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



$lim=9;

if(@$_GET['stt']==""){
	$stt=0;
}else{
	$stt=@$_GET['stt'];
}

mysqli_select_db($saha, $database_saha);$query_total = "SELECT * FROM settings ORDER BY settings.id DESC";
$total = mysqli_query($saha, $query_total) or die(mysqli_error($saha));
$row_total = mysqli_fetch_assoc($total);
$totalRows_total = mysqli_num_rows($total);

mysqli_select_db($saha, $database_saha);$query_selected = "SELECT * FROM settings ORDER BY settings.id DESC LIMIT $stt,$lim";
$selected = mysqli_query($saha, $query_selected) or die(mysqli_error($saha));
$row_selected = mysqli_fetch_assoc($selected);
$totalRows_selected = mysqli_num_rows($selected);

?>
<!DOCTYPE html>
<html>
<head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Charity Browser</title>
 <link rel="shortcut icon" href="../icon.png" type="image/png" />
     <link href="../css/bootstrap.min.css" rel="stylesheet">
      <link href="../fonts/font-awesome/css/font-awesome.css" rel="stylesheet">
      <link href="../css/animate.css" rel="stylesheet">
      <link href="../css/style.css" rel="stylesheet">
   </head>
<body class="gray-bg">

                        
                        <div class="loginColumns animated fadeInDown" style="max-width:1000px; padding-top:0px;">
         <div class="row">
            <div>
<h1><img src="logo.png" width="128" height="128" alt="EWS"> 
  Charity Browser - User Panel</h1>


               
               
            <div class="clients-list">
                              <ul class="nav nav-tabs tab-border-top-danger">
                             
                              <?php include("admin-navi.php"); ?>   
                                 
                              </ul>
                              <div class="tab-content">
                                 <div id="tab-1" class="tab-pane active">
                                    <div class="full-height-scroll">
                                       <div class="table-responsive">
                                          <table class="table table-striped table-hover">
                                          <thead>
                                          <tr>
                                                  <td><a href="admin-new.php" class="btn btn-primary full-width"><i class="glyphicon glyphicon-upload"></i> Add New Ad(s) </a></td>
                                                  <td>Base URL</td>
                                                  <td>Total Visits</td>
                                                  <td class="client-status">HTTP Status</td>
                                                  <td class="client-status">Indexed  Links</td>
                                                  <td class="client-status">Indexed Images</td>
                                                  <td class="client-status">&nbsp;</td>
                                                </tr>
                                          </thead>
                                             <tbody>
                 <?php do { ?>
<?php 
$CURL=$row_selected['actual_url'];

ini_set('user_agent', 'Mozilla/40.0 (Charity Browser)');
$file_headers = @get_headers($row_selected['actual_url']);

$pos200 = strpos($file_headers[0], "200");
$pos302 = strpos($file_headers[0], "302");
$pos301 = strpos($file_headers[0], "301");


mysqli_select_db($saha, $database_saha);$query_selectedCrawl = "SELECT * FROM crawl WHERE crawl.actual_url= '$CURL'";
$selectedCrawl = mysqli_query($saha, $query_selectedCrawl) or die(mysqli_error($saha));
$row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl);
$totalRows_selectedCrawl = mysqli_num_rows($selectedCrawl);

mysqli_select_db($saha, $database_saha);$query_selectedCrawlImgs = "SELECT * FROM crawl_images WHERE crawl_images.actual_url= '$CURL'";
$selectedCrawlImgs = mysqli_query($saha, $query_selectedCrawlImgs) or die(mysqli_error($saha));
$row_selectedCrawlImgs = mysqli_fetch_assoc($selectedCrawlImgs);
$totalRows_selectedCrawlImgs = mysqli_num_rows($selectedCrawlImgs);

?>                 
                                             
                                                
                                                <tr >
                                                   <td ><a href="<?php echo $row_selected['actual_url']; ?>" target="_blank" class="client-link" data-toggle="tab"><?php echo substr($row_selected['actual_url'],0,20);if(strlen($row_selected['actual_url'])>20){echo "...".substr($row_selected['actual_url'],-10);} ?></a>

<?php if($row_selected['demo']=="Y"){ ?><br>
 <span class="badge badge-warning">Demo Site / URL</span>
<?php }else{?>  
<br>
 <span class="badge badge-inverse"> on <?php echo date("Y-m-d H:i:s",$row_selected['time']); ?> by <?php echo $row_selected['user']; ?> </span>
<?php }?>     
                                               
                                                   </td>
                                                   <td>
                                                   
                                                    <?php echo $row_selected['base_url']; ?>
<?php if($row_selected['spider_mode']=="Y"){ ?><br>
 <span class="text-warning"><strong>Spider Mode</strong> : <a href="admin-spidermode.php?id=<?php echo $row_selected['id']; ?>&spider_mode=N"><strong><i class="fa fa-edit"></i> On</strong></a></span>
<?php }else{?>  
<br>
 <span class="text-inverse"><strong>Spider Mode</strong> : <a href="admin-spidermode.php?id=<?php echo $row_selected['id']; ?>&spider_mode=Y"><strong><i class="fa fa-edit"></i> Off</strong></a></span>
<?php }?>

</td>
                                                   <td><?php echo $row_selected['visits']; ?>&nbsp;</td>
                                                   <td class="client-status" style="color:#<?php if($pos200<1  && $pos302<1  && $pos301<1) {?>F00<?php }?>;">
<?php 
echo $file_headers[0];
?> 
<br>
<span class="badge badge-inverse">Added on : <?php echo date("Y-m-d H:i:s",$row_selected['time']); ?></span>
                                                  
                                                   </td>
                                                   <td class="client-status"><a href="admin-data.php?q=<?php echo ($row_selected['actual_url']); ?>" class="btn btn-success <?php if($totalRows_selectedCrawl==0){?>disabled<?php }?>">View <?php echo $totalRows_selectedCrawl; ?> Links</a></td>
                                                   <td class="client-status"><a href="admin-images.php?q=<?php echo ($row_selected['actual_url']); ?>" class="btn btn-info <?php if($totalRows_selectedCrawlImgs==0){?>disabled<?php }?>">View <?php echo $totalRows_selectedCrawlImgs; ?> Images</a></td>
                                                   <td class="client-status">
                                                   <?php if($row_selected['demo']=="N"){ ?>
                                                   <a href="admin-delete.php?id=<?php echo $row_selected['id']; ?>" class="btn btn-danger">Delete</a>
<?php }else{?>
<a href="#" class="btn btn-danger disabled">Delete</a> 
                                                
                                                   
                                                   <?php }?>
                                                   </td>
                                                </tr>
                   <?php } while ($row_selected = mysqli_fetch_assoc($selected)); ?>
                                                <tr >
                                                  <td colspan="7" ><?php if(@$totalRows_total>@$lim){?>
                           <div class="text-center">
                              <div class="btn-group">
<?php 
$aa=0;
$totap= (($totalRows_total-($totalRows_total%$lim))/$lim);
		if(($totalRows_total%$lim)>0){
			$totap=$totap+1;
		}
		
$crrpage=(($stt/$lim)+1);
$lastpage=floor($totalRows_total/$lim)+1;
if($totap>=18){
	$allowedmin=$crrpage-9;
	if($allowedmin<1){
		$allowedmin=1;
	}
	$allowedmax=$crrpage+9;
	if($allowedmax<18){
	if($totalRows_total<18){	
	$allowedmax=$totalRows_total;
	}else{
	$allowedmax=18;	
	}
	}
	
}else{
	$allowedmin=1;
	$allowedmax=$totap;
}

if($allowedmax<100){
	$roudnoff=2;
}else if($allowedmax<1000){
	$roudnoff=3;
}else if($allowedmax<10000){
	$roudnoff=4;
}else if($allowedmax<100000){
	$roudnoff=5;
}else if($allowedmax<1000000){
	$roudnoff=6;
}

?>         
       
<a href="<?php if((($aa-1)*$lim)==$stt){?>javascript:void();<?php }else{?>http://<?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>?q=<?php echo (@$_GET['q']); ?>&stt=0<?php }?>">
                                 <button class="btn btn-white pull-left" type="button"><i class="fa fa-chevron-left"></i></button>
</a>                                 

  <?php 


	$aa=$allowedmin-1;
	for($s=$allowedmin;$s<=$allowedmax;$s++){
        $aa=$aa+1;

if($lastpage>=$aa){
		
	 ?><a href="<?php if((($aa-1)*$lim)==$stt){?>javascript:void();<?php }else{?>http://<?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>?q=<?php echo (@$_GET['q']); ?>&stt=<?php echo ($aa-1)*$lim; ?><?php }?>">
<?php }?>

                                 <button class="btn btn-white <?php  if((($aa-1)*$lim)==$stt){  ?>active<?php }?>"><?php echo  sprintf("%0".$roudnoff."s", $aa);  ?></button>
                                 </a>
<?php }?>
  
  <?php if($totap>0){?>        

<a href="<?php if((($aa-1)*$lim)==$stt){?>javascript:void();<?php }else{?>http://<?php echo $_SERVER['HTTP_HOST']; ?><?php echo $_SERVER['PHP_SELF']; ?>?q=<?php echo (@$_GET['q']); ?>&stt=<?php echo (floor(($totalRows_total/$lim)*$lim)-$lim); ?><?php }?>">
                               <button class="btn btn-white" type="button"><i class="fa fa-chevron-right"></i> </button>
                               </a>
<?php }?>
                              </div>
                           </div>
<?php }?></td>
                                                </tr>
                                                
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                                 
                              </div>
                           </div>   
           </div>
         </div>
         <hr/>
         <div class="row">
            <div class="col-md-6">
            <small><a href="https://codecanyon.net/user/nelliwinne" target="_blank">Nelliwinne</a> &copy;</small>
            </div>
            <div class="col-md-6 text-right">
               <small><?php echo date("Y"); ?></small>
            </div>
         </div>
   </div>
</body>
</html>
<?php 
mysqli_select_db($saha, $database_saha);$query_selectedCrawl = "SELECT * FROM crawl WHERE crawl.deleted='N'  AND crawl.block_update='N'";
$selectedCrawl = mysqli_query($saha, $query_selectedCrawl) or die(mysqli_error($saha));
$row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl);
$totalRows_selectedCrawl = mysqli_num_rows($selectedCrawl);

do{
if(strlen($row_selectedCrawl['content'])<10 && strlen($row_selectedCrawl['description'])<10){
/* 
  $deleteSQL = sprintf("DELETE FROM crawl WHERE id=%s",
                       GetSQLValueString($row_selectedCrawl['id'], "int"));

  mysqli_select_db($saha, $database_saha);$Result1 = mysqli_query($saha, $deleteSQL) or die(mysqli_error($saha));
	
 */	//echo "<strong style=\"color:#F00; padding-left:400px;\">Deleted</strong>";
}
 } while ($row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl)); 
?>
<?php
mysqli_free_result($selected);
?>
