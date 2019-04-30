<?php /*       <link href="../css/bootstrap.min.css" rel="stylesheet">
      <link href="../fonts/font-awesome/css/font-awesome.css" rel="stylesheet">
 */?>
<?php 
function curPageName() {
 return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}
?>

<li <?php if(curPageName()=="user-data.php" || curPageName()=="admin-data-update.php"){?>class="active"<?php }?>><a  href="user-data.php"><i class="glyphicon glyphicon-text-size"></i> Data</a></li>

<li <?php if(curPageName()=="user-donate.php" || curPageName()=="user-donate.php"){?>class="active"<?php }?>><a  href="user-donate.php"><i class="glyphicon glyphicon-picture"></i> Donate</a></li>

<li <?php if(curPageName()=="admin-log.php"){?>class="active"<?php }?>><a href="admin-log.php"><i class="glyphicon glyphicon-list"></i>Log</a></li>







<li class="<?php if(curPageName()=="admin-logout.php"){?>active<?php }?> pull-right"><a  href="admin-logout.php"><i class="glyphicon glyphicon-user"></i>Logout</a></li>

<li class="<?php if(curPageName()=="user-pass.php"){?>active<?php }?> pull-right"><a  href="user-pass.php"><i class="glyphicon glyphicon-adjust"></i>Settings</a></li>

