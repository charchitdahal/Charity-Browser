<?php 
$verif_box = $_GET["p1"];

if(md5($verif_box).'a4xn' == $_COOKIE['tntcon']){
	

?>
<input value="Sign in" name="Search" id="Search" class="btn btn-lg" type="submit">
<?php }else{?>
<input value="Sign in" name="Search" id="Search" class="btn btn-lg btn-danger" type="submit" disabled="disabled">
<p class="alert-danger">Wrong Verification Code</p>
<?php }?>