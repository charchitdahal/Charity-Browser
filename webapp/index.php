<?php require_once('Connections/saha.php'); ?>
<?php require_once('inc-main.php'); ?>
<?php 
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <script async="async" src="https://www.google.com/adsense/search/ads.js"></script>

<!-- other head elements from your page -->

<script type="text/javascript" charset="utf-8">
(function(g,o){g[o]=g[o]||function(){(g[o]['q']=g[o]['q']||[]).push(
  arguments)},g[o]['t']=1*new Date})(window,'_googCsa');
</script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta charset="utf-8">
      <title>Charity Browser Homepage</title>
      <link rel="shortcut icon" href="icon.png" type="image/png" />
      <link href="css/bootstrap.min.css" rel="stylesheet">
      <link href="fonts/font-awesome/css/font-awesome.css" rel="stylesheet">
      <link href="css/animate.css" rel="stylesheet">
      <link href="css/style.css" rel="stylesheet">
<style type="text/css">
/* centered columns styles */
.row-centered {
    text-align:center;
}
.col-centered {
    display:inline-block;
    float:none;
    /* reset the text-align */
    text-align:left;
    /* inline-block space fix */
    margin-right:-4px;
}
.col-fixed {
    /* custom width */
    width:320px;
}
.col-min {
    /* custom min width */
    min-width:320px;
}
.col-max {
    /* custom max width */
    max-width:320px;
}

/* visual styles */
body {
    padding-bottom:40px;
}
h1 {
    margin:40px 0px 20px 0px;
	color:#95c500;
    font-size:28px;
    line-height:34px;
    text-align:center;
}

.item {
    width:100%;
    height:100%;
	border:1px solid #cecece;
    padding:28px;
	background:#ededed;
	background:-webkit-gradient(linear, left top, left bottom,color-stop(0%, #f4f4f4), color-stop(100%, #ededed));
	background:-moz-linear-gradient(top, #f4f4f4 0%, #ededed 100%);
	background:-ms-linear-gradient(top, #f4f4f4 0%, #ededed 100%);
	border-radius: 15px;
}

/* content styles */
.item {
	display:table;
}
.content {
	display:table-cell;
	vertical-align:middle;
	text-align:center;
}

/* centering styles for jsbin */
html,
body {
    width:100%;
    height:100%;
}
html {
    display:table;
}
body {
    display:table-cell;
}
</style>      
</head>
<body>
<div id="wrapper">
<div class="animated fadeIn">
               <div class="row bg-primary">
                  <div class="col-lg-12" style="padding-top:0px;">
                 
                  <a href="user/" target="_blank" class="btn btn-sm btn-link pull-right"><i class="fa fa-user" aria-hidden="true"></i> User</a>
                    
          
                    
                  <a href="charity-browser.apk" class="btn btn-sm btn-link pull-right"><i class="fa fa-download" aria-hidden="true"></i>
 Download App</a>
                  </div>
               </div>
                  

<h1><img src="logo.png" width="128" height="128" alt="EWS"><br>
  Charity Browser</h1>
  
<div class="container">
    <div class="row row-centered">
        <div class="col-xs-6 col-centered col-min">
        <div class="item">&nbsp;
        <div class="content">
        <div class="search-form">
        <form action="https://www.google.com/search" method="get">
        <div class="input-group">
                                    <input type="text" placeholder="Search Web" name="q" class="form-control input-lg" value="<?php echo stripslashes(@$cid); ?>" required>
                                     
                                   <div class="input-group-btn">
                                       <button class="btn btn-lg btn-primary" type="submit">
                                       <i class="fa fa-search"></i>
                                       </button>
                                    </div>
                                 </div>
      </form>
                           </div>
        </div>
        </div>
        </div>
    </div>
</div>

    <div class="row row-centered">
    <div class="col-xs-6 col-centered col-min circle-border">
<br>

<a href="charity-browser.apk" target="_blank" class="btn btn-primary btn-lg btn-block"><i class="fa fa-download" aria-hidden="true"></i>
</i> Download Charity Browser App</a>

<div id='afscontainer1'></div>

<script type="text/javascript" charset="utf-8">

  var pageOptions = {
    "pubId": "pub-9616389000213823", // Make sure this the correct client ID!
    "query": "hotels",
    "adPage": 1
  };

  var adblock1 = {
    "container": "afscontainer1",
    "width": "700",
    "number": 2
  };

  _googCsa('ads', pageOptions, adblock1);

</script>


<?php
/////////////////////////////////
//////Top Searched Terms/////////
?>
<?php 
mysqli_select_db($saha, $database_saha);$query_searchTerms = "SELECT * FROM log GROUP BY log.keyword ORDER BY SUM(log.count) DESC LIMIT 20";
$searchTerms = mysqli_query($saha, $query_searchTerms) or die(mysqli_error($saha));
$row_searchTerms = mysqli_fetch_assoc($searchTerms);
$totalRows_searchTerms = mysqli_num_rows($searchTerms);

?>
<?php if($totalRows_searchTerms>0){?>
<div class="panel panel-default" style="border:none; margin-top:50px;">
<fieldset>
<legend style="text-align:center;">Recent Donations</legend>
<div class="panel-body text-center">
<?php do{?>
<a href=""<?php echo $row_searchTerms['keyword']; ?>"" class="btn btn-white btn-cons" style="margin:2px;"><?php echo $row_searchTerms['keyword']; ?></a>
<?php } while ($row_searchTerms = mysqli_fetch_assoc($searchTerms)); ?>
</div>
</fieldset>
</div>
<?php }?>
<?php 
//////End Top Searched Terms/////
/////////////////////////////////
?>
    </div>
    </div>

</div>
</div>      
</body>
</html>