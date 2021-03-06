<?php
include "lib.php";
if (isset($_GET['logout'])) unset($_SESSION['username']);
private_zone();
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- JQuery -->
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<link rel="stylesheet" href="css/style.css" />
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" 
    href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<!-- Optional theme -->
<link rel="stylesheet" 
    href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
</head>
<body class="main-body">
<div class="container">
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <h3 class="navbar-text">Mayur's Media Library (Development)</h3>
    <div class="collapse navbar-collapse">	
        <div class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="glyphicon glyphicon-user"></span>
              <?=$_SESSION['username']?>
              <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="index.php?logout">Logout</a></li>
              </ul>
            </li>
        </div>
        <div class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
<?php 
if ($_SESSION['download'] == 'ext') echo "Stream Mode"; 
else echo "Download Mode";
?>
              <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="index.php?download=m3u">Download Mode</a></li>
                <li><a href="index.php?download=ext">Stream Mode</a></li>
              </ul>
            </li>
        </div>
	
	<div class="nav navbar-nav navbar-right">
	   <li><a href="index.php">Movies</a></li>
	</div>
        <form class="navbar-form navbar-right">
        </form>
    </div>
</nav>
    <div class="page-header">
        <h1>TV Series</h1>
    </div>
<?php
empty_temp_files($temp_dir);
$files = glob("/var/www/media/TV_series/*");
$i = 0;
foreach($files as $file) {
    if (isset(pathinfo($file)['extension']))
        $ext = pathinfo($file)['extension'];
    else $ext ='';
    $film = "";
    if (!is_dir($file) and in_array($ext, $video_extension))
        $film = create_m3u_from_file($file, $temp_dir);
    if (is_dir($file))
        $film = create_m3u_from_dir($file, $temp_dir);
    if ($film == '') continue;
    if ($i % 4 == 0) echo "<div class='row'>";
    echo "<div class='col-md-3 col-sm-3'><div class='thumbnail'>";
    if ($_SESSION['download'] == 'm3u')
        echo "<a href='seasons.php?path=$file'>";
    else echo "<a href='seasons.php?path=$file'>";
    $search = $film[name];
    $q = preg_replace("/\([^)]+\)/","",$search);
    echo "<img src='images/$film[name].jpg' alt='$film[name]' >";
    //echo "<img src='$film[image]' alt='$film[name]'>";
    echo "<div class='caption'>$film[name]</div>";
    echo "<div class='caption'><a href='https://www.google.com/search?q=$q' target=_blank>Details</a></div>";
    echo "</div></div></a>";
    $i++;
    if ($i % 4 == 0) echo "</div>";
}

?>
</div>
</body>
</html>
