<?php include("inc-main.php"); ?>
<?php
// *** Logout the current user.
$logoutGoTo = "http://charity-browser.org";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['MM_UsernameAdMIN'] = NULL;
$_SESSION['MM_UserGroupADmin'] = NULL;
unset($_SESSION['MM_UsernameAdMIN']);
unset($_SESSION['MM_UserGroupADmin']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>
