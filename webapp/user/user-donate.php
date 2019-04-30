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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form")) {

$id= mysqli_real_escape_string($saha,$_POST['actual_url']);


mysqli_select_db($saha, $database_saha);$query_selectedCrawl = "SELECT * FROM settings WHERE settings.id='$id'";
$selectedCrawl = mysqli_query($saha, $query_selectedCrawl) or die(mysqli_error($saha));
$row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl);
$totalRows_selectedCrawl = mysqli_num_rows($selectedCrawl);
  
	    $insertSQL = sprintf("INSERT INTO crawl_images (image_url, current_url, actual_url, base_url, manual, keywords, block_update) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['current_url'], "text"),
                       GetSQLValueString($row_selectedCrawl['actual_url'], "text"),
                       GetSQLValueString($row_selectedCrawl['actual_url'], "text"),
                       GetSQLValueString($row_selectedCrawl['base_url'], "text"),
                       GetSQLValueString("Y", "text"),
                       GetSQLValueString($_POST['keywords'], "text"),
                       GetSQLValueString("Y", "text"),
                       GetSQLValueString($urlBase, "text"));

  mysqli_select_db($saha, $database_saha);$Result1 = mysqli_query($saha, $insertSQL) or die(mysqli_error($saha));
  



header("Location: admin-images.php");
exit;
}



mysqli_select_db($saha, $database_saha);$query_selectedCrawl = "SELECT * FROM settings";
$selectedCrawl = mysqli_query($saha, $query_selectedCrawl) or die(mysqli_error($saha));
$row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl);
$totalRows_selectedCrawl = mysqli_num_rows($selectedCrawl);

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
         <div class="row" style="height:900px;">
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
                                          <div>
                              <form name="form" action="<?php echo $editFormAction; ?>" method="POST">
                              <fieldset>
                              <legend><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" class="btn btn-primary">Go Back</a> Donation Page</legend>
                                 <div class="form-group">
                                   <label for="actual_url">Month</label>
                                   <select name="actual_url" id="actual_url" class="form-control input-lg">
                                     <?php
do {  
?>
                                     <option value="<?php echo $row_selectedCrawl['id']?>"><?php echo $row_selectedCrawl['actual_url']?></option>
                                     <?php
} while ($row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl));
  $rows = mysqli_num_rows($selectedCrawl);
  if($rows > 0) {
      mysqli_data_seek($selectedCrawl, 0);
	  $row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl);
  }
?>
                                   </select>
                                   <label style="clear:both;">Link to Charity </label>
                                 
                                   <input name="current_url" type="text" class="form-control input-lg" id="current_url" placeholder="http://"  value=""  />
                                   
                                   
                                     
                                     
                                   
<br>
                                 <label style="clear:both;">Notes</label>
                                 
                                   <textarea name="keywords" class="form-control input-lg" id="keywords" placeholder="Keywords"></textarea>
                                     
                                     
                                   
\
 <br>



                                    <label>
                                       <button class="btn btn-lg btn-primary" type="submit">
                                      Request Donation
                                      </button>
                                   </label>
                                      
                               </div>    
                                   
                                 </fieldset>
                                 <input type="hidden" name="MM_insert" value="form">
                                 <input type="hidden" name="MM_update" value="form">
                              </form>
                           </div>
                                       </div>
                                    </div>
                                 </div>
                                 
                              </div>
                           </div>   
           </div>
         </div>
         <hr/>
         <div class="row">
         
            <div class="col-md-6 text-right">
               <small><?php echo date("Y"); ?></small>
            </div>
         </div>
   </div>
</body>
</html>
