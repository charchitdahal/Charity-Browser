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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form3")) {
  $updateSQL = sprintf("UPDATE crawl_settings SET max_links_per_site=%s, batch=%s, image_width=%s, image_height=%s, body_lengh=%s WHERE id=%s",
                       GetSQLValueString($_POST['max_links_per_site'], "int"),
                       GetSQLValueString($_POST['batch'], "int"),
                       GetSQLValueString($_POST['image_width'], "int"),
                       GetSQLValueString($_POST['image_height'], "int"),
                       GetSQLValueString($_POST['body_lengh'], "int"),
                       GetSQLValueString($_POST['id'], "int"));

  mysqli_select_db($saha, $database_saha);$Result1 = mysqli_query($saha, $updateSQL) or die(mysqli_error($saha));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {

  $updateSQL = sprintf("UPDATE admin SET pw=%s WHERE id=%s",
                       GetSQLValueString(md5($_POST["pws"]), "text"),
                       GetSQLValueString($_POST["id"], "int"));

  mysqli_select_db($saha, $database_saha);$Result1 = mysqli_query($saha, $updateSQL) or die(mysqli_error($saha));


}

mysqli_select_db($saha, $database_saha);$query_selectedCrawl = "SELECT * FROM admin";
$selectedCrawl = mysqli_query($saha, $query_selectedCrawl) or die(mysqli_error($saha));
$row_selectedCrawl = mysqli_fetch_assoc($selectedCrawl);
$totalRows_selectedCrawl = mysqli_num_rows($selectedCrawl);

mysqli_select_db($saha, $database_saha);$query_settings = "SELECT * FROM crawl_settings";
$settings = mysqli_query($saha, $query_settings) or die(mysqli_error($saha));
$row_settings = mysqli_fetch_assoc($settings);
$totalRows_settings = mysqli_num_rows($settings);

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
  Charity Browser - Admin Panel</h1>


               
               
            <div class="clients-list">
                              <ul class="nav nav-tabs tab-border-top-danger">
                              <?php include("admin-navi.php"); ?>   
                                 
                              </ul>
                              <div class="tab-content">
                                 <div id="tab-1" class="tab-pane active">
                                    <div class="full-height-scroll">
                                       <div class="table-responsive">
                                       <?php if($row_selectedCrawl['demo']=="Y"){ ?><br>
                                       <div>

 <span class="badge badge-warning">Settings are Disabled to  Update in this Demo Version</span><br>

                                       
                                       </div>
                                       <?php }?>
                                          <div class="col-lg-6">
                              <form name="form" action="<?php echo $editFormAction; ?>" method="POST">
                              <fieldset>
                              <legend>Update Administrator Password</legend>
                                 <div class="form-group">
                                    <input name="id" type="hidden" id="id" value="<?php echo $row_selectedCrawl['id']; ?>">
                                 <label style="clear:both;">Username
                                 
                                    <input name="q" type="text" disabled class="form-control input-lg" placeholder="http://www.myweb.com"  value="<?php echo $row_selectedCrawl['un']; ?>" />
                                    </label>
                                    <br>

                                 <label style="clear:both;">Password
                                 
                                     <input name="pws" type="password" class="form-control input-lg" id="pws" placeholder="Password"    value="" required <?php if($row_selectedCrawl['demo']=="Y"){ ?>readonly<?php }?> />
                                     
                                     
                                    </label>
<br>
<?php if($row_selectedCrawl['demo']=="Y"){ ?><br>


 <label>
                                       <button class="btn btn-lg btn-primary disabled" type="button">
                                       Update Password
                                       </button>
                                       </label>
 <?php }else{?>
                                    <label>
                                       <button class="btn btn-lg btn-primary" type="submit">
                                       Update Password
                                       </button>
                                       </label>
<?php }?>                                       
                               </div>    
                                   
                                 </fieldset>
                                 <input type="hidden" name="MM_insert" value="form">
                              </form>
                           </div>
                          
                           </div>   
           </div>
         </div>
         <hr/>
         
   </div>
</body>
</html>
<?php
mysqli_free_result($selectedCrawl);
?>
