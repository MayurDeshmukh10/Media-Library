<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$filepath = $_GET['file'];
$extension = $_GET['extension'];
$filename = basename($filepath);

$download_name = $filename.'.'.$extension;

header("Content-Description: File Transfer");
header('Content-disposition: attachment; filename="'.$download_name.'"');
header("Content-type: video/mp4");
header("Expires: 0");
header('Pragma: public');
header('Content-Length:' . filesize($filepath));
readfile($filepath);

?>