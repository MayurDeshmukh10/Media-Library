<?php
$user = $_GET['user'];
$link = mysqli_connect("127.0.0.1", "root", "mayur1092", "media_library");
if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
$row = mysqli_fetch_array($result);
$p_password = $_POST['p_password'];
$n_password = $_POST['n_password'];
$hashed = md5($n_password);

$result = mysqli_query($link,"SELECT password FROM User WHERE username='".$user."' ");
$row = mysqli_fetch_array($result);
$dp_password = $row['password'];

if(md5($p_password) == $dp_password) {
	mysqli_query($link,"UPDATE User SET password='".$hashed."' WHERE username='".$user."' ");
	//header('location: login.php');
	header("Location: login.php"); 
}
else
{
	echo '<script>alert("Wrong Previous password")</script>'; 
}
	
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <br><br><br>
    <br><br><br><br><br><br><br><br><br>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1 class="panel-title">Change Password</h1>
                </div>
                <div class="panel-body">
                    <form action="#" method="POST">
                        <div class="form-group">
                        <input class="form-control" name='p_password' type='password'
                            placeholder="Previous Password">
                        </div>
                        <div class="form-group">
                        <input class="form-control" name='n_password' 
                            placeholder="New Password" type='password'>
                        </div>
                        <input type='submit' value="Change" 
                            class="btn btn-primary btn-block"/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
