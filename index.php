<?php
include "lib.php";
$cn = mysqli_connect("127.0.0.1", "root", "mayur1092", "media_library");
if (isset($_GET['logout']))
{
	mysqli_query($cn,"DELETE FROM active_users WHERE username='".$_SESSION['username']."' ");
 	unset($_SESSION['username']);
}
	
private_zone();
$c_user = $_SESSION['username'];
if(session_status() == 1)
{
	mysqli_query($cn,"DELETE FROM active_users WHERE username='".$_SESSION['username']."' ");
}

$active = mysqli_query($cn,"SELECT * from active_users");
$active_users = mysqli_num_rows($active);
?>
<!DOCTYPE html>
<html>
<head>
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
    <h3 class="navbar-text">Mayur's Media Library (Development Build)</h3>
    <div class="collapse navbar-collapse">	
        <div class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="glyphicon glyphicon-user"></span>
              <?=$_SESSION['username']?>
              <b class="caret"></b></a>
              <ul class="dropdown-menu">
		<li><a href="reset_password.php?user=<?=$c_user?>">Change Password</a></li>
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
		<li><a href="index.php?download=ext">Stream Mode</a></li>
                <li><a href="index.php?download=m3u">Download Mode</a></li>
                
              </ul>
            </li>
        </div>
	<div class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              Categories
              <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
		<li><a href="#">Science Fiction</a></li>
		<li><a href="#">Comedy</a></li>
		<li><a href="#">Horror</a></li>	
              </ul>
            </li>
        </div>
	<div class="nav navbar-nav navbar-right">
	   <li><a href="tv_series.php">TV Series</a></li>
	</div>
	<div class="nav navbar-nav navbar-right">
	   <li><a href="changelog.php" target=_blank>Changelog</a></li>
	</div>
	<div class="nav navbar-nav navbar-right">
	
	   <li><a href="#">Current Users Watching : <?= $active_users?></a></li>
	</div>
        <form class="navbar-form navbar-right">
        </form>
    </div>
</nav>
    <div class="page-header">
        <h1>Movies</h1>
    </div>
<?php
empty_temp_files($temp_dir);
$files = glob("$root_dir/*");
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
        echo "<a href='download.php?file=$file&extension=$ext'>";
    //else echo "<a href='player.php?file_to_play=$file target=_blank'>";
    else echo "<a href='stream.php?file=$file&exte=$ext' target=_blank'>";
  
    //echo "var/www/html/images/$film[name].jpg";
    echo "<img src='$film[image]' alt='$film[name]'>";
    $size = abs(filesize($file)/1000000000);
    $round_size = round($size,2);
    echo "<div class='caption'>$film[name] ($round_size GB)</div>";
    echo "</div></div></a>";
    $i++;
    if ($i % 4 == 0) echo "</div>";
}

?>
</div>
</body>
</html>
