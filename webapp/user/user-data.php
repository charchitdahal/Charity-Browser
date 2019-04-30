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

mysqli_select_db($saha, $database_saha);$query_selected = "SELECT * FROM settings";
$selected = mysqli_query($saha, $query_selected) or die(mysqli_error($saha));
$row_selected = mysqli_fetch_assoc($selected);
$totalRows_selected = mysqli_num_rows($selected);

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

$lim=10;

if(@$_GET['stt']==""){
	$stt=0;
}else{
	$stt=@$_GET['stt'];
}

if((@$_GET['q'])!=""){

$cid=mysqli_real_escape_string($saha,$_GET['q']);
$stt=mysqli_real_escape_string($saha,$stt);


mysqli_select_db($saha, $database_saha);$query_total = "SELECT * FROM crawl WHERE (crawl.title LIKE '%$cid%' OR crawl.description LIKE '%$cid%'  OR crawl.current_url LIKE '%$cid%'  OR crawl.keywords LIKE '%$cid%'  OR crawl.content LIKE '%$cid%' OR crawl.actual_url LIKE '%$cid%')";
$total = mysqli_query($saha, $query_total) or die(mysqli_error($saha));
$row_total = mysqli_fetch_assoc($total);
$totalRows_total = mysqli_num_rows($total);

mysqli_select_db($saha, $database_saha);$query_selected = "SELECT * FROM crawl WHERE (crawl.title LIKE '%$cid%' OR crawl.description LIKE '%$cid%'  OR crawl.current_url LIKE '%$cid%'  OR crawl.keywords LIKE '%$cid%'  OR crawl.content LIKE '%$cid%' OR crawl.actual_url LIKE '%$cid%') LIMIT $stt,$lim";
$selected = mysqli_query($saha, $query_selected) or die(mysqli_error($saha));
$row_selected = mysqli_fetch_assoc($selected);
$totalRows_selected = mysqli_num_rows($selected);

}else{
	
mysqli_select_db($saha, $database_saha);$query_total = "SELECT * FROM crawl";
$total = mysqli_query($saha, $query_total) or die(mysqli_error($saha));
$row_total = mysqli_fetch_assoc($total);
$totalRows_total = mysqli_num_rows($total);


mysqli_select_db($saha, $database_saha);$query_selected = "SELECT * FROM crawl ORDER BY crawl.id DESC LIMIT $stt,$lim";
$selected = mysqli_query($saha, $query_selected) or die(mysqli_error($saha));
$row_selected = mysqli_fetch_assoc($selected);
$totalRows_selected = mysqli_num_rows($selected);

}

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
<body class="gray-bg" >



                        
                        <div class="loginColumns animated fadeInDown" style="max-width:1000px; padding-top:0px;">
         <div class="row">
            <div>
<h1><img src="logo.png" width="128" height="128" alt="EWS"> 
  Charity Browser - Admin Panel</h1>


               
               
            <div class="clients-list">
                              <ul class="nav nav-tabs tab-border-top-danger">
                              <?php include("admin-navi.php"); ?>   
                                 
                              </ul>
                              <div class="tab-content">
                                 <div id="tab-1" class="tab-pane active">
                                    <div class="full-height-scroll">
                                       <div class="table-responsive">
<table class="table table-striped table-hover table-bordered">
                                          <thead>
                                          <tr>
                                            <td colspan="3"><form action="" method="get">
                                 <div class="input-group">
                                    <input type="text" placeholder="Search Data" name="q" class="form-control input-lg" value="<?php echo stripslashes(@$cid); ?>">
                                    <div class="input-group-btn">
                                       <button class="btn btn-lg btn-primary" type="submit">
                                       Search
                                       </button>
                                    </div>
                                 </div>
                             </form></td>
                                            <td class="client-status">
<?php if((@$_GET['q'])!=""){?>                                            
<a href="admin-data.php" class="btn btn-warning btn-lg">Reset</a>
<?php }?>
</td>
                                            <td class="client-status" colspan="4"><a href="user-donate.php" class="btn btn-success btn-lg">Donate</a></td>
                                          </tr>
                                          <tr>
                                            <td>Month</td>
                                                  <td>Ad Name</td>
                                                  <td>Total Visits</td>
                                                  <td class="client-status">Money Earned</td>
                                                  <td class="client-status">&nbsp;</td>
                                                  <td class="client-status">&nbsp;</td>
                                                  <td class="client-status"></td>
                                                </tr>
                                          </thead>
                                             <tbody>
<?php if($totalRows_selected>0){?>                                             
<?php do { ?>
<?php 
$CURL=$row_selected['actual_url'];
//CrawlURLList($database_saha,$saha,$CURL);

mysqli_select_db($saha, $database_saha);$query_selectedCrawl = "SELECT * FROM crawl_images WHERE crawl_images.actual_url= '$CURL'";
$selectedCrawl = mysqli_query($saha, $query_selectedCrawl) or die(mysqli_error($saha));
$row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl);
$totalRows_selectedCrawl = mysqli_num_rows($selectedCrawl);

?>                 
                                             
                                                
                                                <tr>
                                                  <td><strong><?php echo stripslashes($row_selected['title']); ?></strong><br>
<?php echo $row_selected['ContentType']; ?>

</td>
                                                   <td><strong>Base URL : <?php echo $row_selected['base_url']; ?></strong><br>
URL : <a href="<?php echo $row_selected['current_url']; ?>" target="_blank" class="client-link" data-toggle="tab"><?php echo $row_selected['current_url']; ?></a>
                                                   
                                                   </td>
                                                   <td><?php echo $row_selected['visits']; ?>&nbsp;</td>
                                                   <td class="client-status"><?php echo $totalRows_selectedCrawl; ?></td>
                                                   <td class="client-status"><?php if($row_selected['pdf']=="Y"){ ?><a href="<?php echo $row_selected['current_url']; ?>" target="_blank" class="client-link" data-toggle="tab"> <i class="fa fa-file-pdf-o"></i> Download PDF</a><?php }?></td>
                                                  
                                                   <td class="client-status"> 
<?php if($row_selected['deleted']=="Y"){?>


<?php }?></td>
                                                </tr>
<?php } while ($row_selected = mysqli_fetch_assoc($selected)); ?>
<?php } ?>                   
                                                <tr>
                                                  <td colspan="7"><?php if(@$totalRows_total>@$lim){?>
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
<?php }?> </td>
                                                </tr>
                                                
                                             </tbody>
                                          </table>
                                          <br style="clear:both;" >                                         
                                      </div>
                                    </div>
                                 </div>
                                 
                              </div>
                           </div>   
           </div>

        </div>
 
<?php /*  
         <hr/>
         <div class="row" style="clear:both;">
            <div class="col-md-6">
               <small><a href="https://codecanyon.net/user/nelliwinne" target="_blank">Nelliwinne</a> &copy;</small>
            </div>
            <div class="col-md-6 text-right">
               <small><?php echo date("Y"); ?></small>
            </div>
         </div>
         
 */?>         
   </div>
</body>
</html>
