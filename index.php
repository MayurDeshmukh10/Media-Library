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
    <h3 class="navbar-text">Mayur's Media Library (Beta Build)</h3>
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
		<li><a href="#must_watch">Must Watch</a></li>
                <li><a href="#romedy">Romedy</a></li>
		<li><a href="#action">Action</a></li>
		<li><a href="#science_fiction">Science Fiction</a></li>
		<li><a href="#thriller">Thriller</a></li>
		<li><a href="#horror">Horror</a></li>
  		<li><a href="#animation">Animation</a></li>	
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
        <h1 id="must_watch">Must Watch Movies</h1>
    </div>
<?php
empty_temp_files($temp_dir);
$files = glob("$root_dir/Must_Watch/*");
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
    echo "<img src='images/$film[name].jpg' alt='$film[name]' >";
    //echo "<img src='$film[image]' alt='$film[name]'>";
    $size = abs(filesize($file)/1000000000);
    $round_size = round($size,2);
    $search = $film[name];
    $q = preg_replace("/\([^)]+\)/","",$search);
    echo "<div class='caption'>$film[name] ($round_size GB)</div>";
    echo "<div class='caption'><a href='https://www.google.com/search?q=$q' target=_blank>Details</a></div>";
    echo "</div></div></a>";
    $i++;
    if ($i % 4 == 0) echo "</div>";
}
echo "</div>";
?>
<div class="page-header">
        <h1 id="romedy">Romedy</h1>
    </div>
<?php
empty_temp_files($temp_dir);
$files = glob("$root_dir/Romedy/*");
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
    echo "<img src='images/$film[name].jpg' alt='$film[name]' >";
    //echo "<img src='$film[image]' alt='$film[name]'>";
    $size = abs(filesize($file)/1000000000);
    $round_size = round($size,2);
    $search = $film[name];
    $q = preg_replace("/\([^)]+\)/","",$search);
    echo "<div class='caption'>$film[name] ($round_size GB)</div>";
    echo "<div class='caption'><a href='https://www.google.com/search?q=$q' target=_blank>Details</a></div>";
    echo "</div></div></a>";
    $i++;
    if ($i % 4 == 0) echo "</div>";
}
echo "</div>";
?>
<div class="page-header">
        <h1 id="action">Action</h1>
    </div>
<?php
empty_temp_files($temp_dir);
$files = glob("$root_dir/Action/*");
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
    echo "<img src='images/$film[name].jpg' alt='$film[name]' >";
    //echo "<img src='$film[image]' alt='$film[name]'>";
    $size = abs(filesize($file)/1000000000);
    $round_size = round($size,2);
    $search = $film[name];
    $q = preg_replace("/\([^)]+\)/","",$search);
    echo "<div class='caption'>$film[name] ($round_size GB)</div>";
    echo "<div class='caption'><a href='https://www.google.com/search?q=$q' target=_blank>Details</a></div>";
    echo "</div></div></a>";
    $i++;
    if ($i % 4 == 0) echo "</div>";
}
echo "</div>";
?>
<div class="page-header">
        <h1 id="science_fiction">Science Fiction</h1>
    </div>
<?php
empty_temp_files($temp_dir);
$files = glob("$root_dir/Science_Fiction/*");
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
    echo "<img src='images/$film[name].jpg' alt='$film[name]' >";
    //echo "<img src='$film[image]' alt='$film[name]'>";
    $size = abs(filesize($file)/1000000000);
    $round_size = round($size,2);
    $search = $film[name];
    $q = preg_replace("/\([^)]+\)/","",$search);
    echo "<div class='caption'>$film[name] ($round_size GB)</div>";
    echo "<div class='caption'><a href='https://www.google.com/search?q=$q' target=_blank>Details</a></div>";
    echo "</div></div></a>";
    $i++;
    if ($i % 4 == 0) echo "</div>";
}
echo "</div>";
?>
<div class="page-header">
        <h1 id="thriller">Thriller</h1>
    </div>
<?php
empty_temp_files($temp_dir);
$files = glob("$root_dir/Thriller/*");
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
    echo "<img src='images/$film[name].jpg' alt='$film[name]' >";
    //echo "<img src='$film[image]' alt='$film[name]'>";
    $size = abs(filesize($file)/1000000000);
    $round_size = round($size,2);
    $search = $film[name];
    $q = preg_replace("/\([^)]+\)/","",$search);
    echo "<div class='caption'>$film[name] ($round_size GB)</div>";
    echo "<div class='caption'><a href='https://www.google.com/search?q=$q' target=_blank>Details</a></div>";
    echo "</div></div></a>";
    $i++;
    if ($i % 4 == 0) echo "</div>";
}
echo "</div>";
?>
<div class="page-header">
        <h1 id="horror">Horror</h1>
    </div>
<?php
empty_temp_files($temp_dir);
$files = glob("$root_dir/Horror/*");
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
    echo "<img src='images/$film[name].jpg' alt='$film[name]' >";
    //echo "<img src='$film[image]' alt='$film[name]'>";
    $size = abs(filesize($file)/1000000000);
    $round_size = round($size,2);
    $search = $film[name];
    $q = preg_replace("/\([^)]+\)/","",$search);
    echo "<div class='caption'>$film[name] ($round_size GB)</div>";
    echo "<div class='caption'><a href='https://www.google.com/search?q=$q' target=_blank>Details</a></div>";
    echo "</div></div></a>";
    $i++;
    if ($i % 4 == 0) echo "</div>";
}
echo "</div>";
?>
<div class="page-header">
        <h1 id="animation">Animation</h1>
    </div>
<?php
empty_temp_files($temp_dir);
$files = glob("$root_dir/Animation/*");
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
    echo "<img src='images/$film[name].jpg' alt='$film[name]' >";
    //echo "<img src='$film[image]' alt='$film[name]'>";
    $size = abs(filesize($file)/1000000000);
    $round_size = round($size,2);
    $search = $film[name];
    $q = preg_replace("/\([^)]+\)/","",$search);
    echo "<div class='caption'>$film[name] ($round_size GB)</div>";
    echo "<div class='caption'><a href='https://www.google.com/search?q=$q' target=_blank>Details</a></div>";
    echo "</div></div></a>";
    $i++;
    if ($i % 4 == 0) echo "</div>";
}
echo "</div>";
?>

</div>
</body>
</html>
