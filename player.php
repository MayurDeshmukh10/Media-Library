<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);


$file_path = $_GET['file_to_play'];

echo '
<html>
<head>
  <title>Player</title>
  <link href="https://vjs.zencdn.net/7.7.5/video-js.css" rel="stylesheet" />
  <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
</head>
<body bgcolor="#00000">
  <video
    id="my-video"
    class="video-js"
    controls
    preload="auto"
    width="640"
    height="264"
    data-setup="{}"
  >
  <source src="$file_path" type="video/mp4" />

 </video>
   <script src="https://vjs.zencdn.net/7.7.5/video.js"></script>
</body>
</html>';

?>