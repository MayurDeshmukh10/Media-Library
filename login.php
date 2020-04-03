<?php
include "lib.php";
public_zone();
$link = mysqli_connect("127.0.0.1", "root", "mayur1092", "media_library");
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

$user = $_POST['username'];
$result = mysqli_query($link,"SELECT password FROM User WHERE username='".$user."' ");
if(! $result ) {
      die('Could not get data: ');
   }

$row = mysqli_fetch_array($result);
$pass = $row['password'];

	


if (isset($_POST['username'])) {
    if (md5($_POST['password']) == $pass) {
	    date_default_timezone_set("Asia/Calcutta");
	    $timestamp = date("Y:M:d:h:i:sa");
            $ipaddress = '';
    	    if (isset($_SERVER['HTTP_CLIENT_IP']))
               $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
            else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
               $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_X_FORWARDED']))
               $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
            else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
               $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
            else if(isset($_SERVER['HTTP_FORWARDED']))
               $ipaddress = $_SERVER['HTTP_FORWARDED'];
            else if(isset($_SERVER['REMOTE_ADDR']))
               $ipaddress = $_SERVER['REMOTE_ADDR'];
            else
               $ipaddress = 'UNKNOWN';
	    mysqli_query($link,"INSERT INTO login_log values('$user','$timestamp','$ipaddress')");
            mysqli_query($link,"INSERT INTO active_users values('$user')");
	    $_SESSION['username'] = $_POST['username'];
            header('location: index.php');
        }
    else $loginfailed='';
}
?>
<!DOCTYPE html>
<html>
<head>
<!-- JQuery -->
<script src=" http://code.jquery.com/jquery-1.6.4.min.js" 
    type="text/javascript"></script>
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
<body>

<div class="container">
    <div class="page-header">
        <h1>Mayur's Media Library <small>just a media library...</small></h1>
    </div>
    <br><br><br><br><br><br><br>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">Login</h2>
                </div>
                <div class="panel-body">
                    <form action="#" method="POST">
                        <div class="form-group">
                        <input class="form-control" name='username' type='text'
                            placeholder="Username">
                        </div>
                        <div class="form-group">
                        <input class="form-control" name='password' 
                            placeholder="Password" type='password'>
                        </div>
                        <input type='submit' value="Login" 
                            class="btn btn-primary btn-block"/>
                    </form>
                </div>
            </div>
<?php 
if (isset($loginfailed))  
echo "<div class='alert alert-danger'>Login failed</div>"; 
?>
        </div>
    </div>
</div>
</body>
</html>
