<?php

ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);


$file_path = $_GET['file_to_play'];

?>
<html>
<head>
  <title>Player</title>
   <link
  href="https://unpkg.com/video.js@7/dist/video-js.min.css"
  rel="stylesheet"
/>

<!-- City -->
<link
  href="https://unpkg.com/@videojs/themes@1/dist/city/index.css"
  rel="stylesheet"
/>
  <link href="https://vjs.zencdn.net/7.7.5/video-js.css" rel="stylesheet" />
  <script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
  <script src="https://vjs.zencdn.net/7.7.5/video.js"></script>
</head>
<body bgcolor="#00000">
  

<video id="example_video_1" class="video-js vjs-default-city" width="640" height="264" src="http://192.168.1.103:80/stream.php?file=<?= $file_path?>" controls autoplay/>
</video>
   
</body>
</html>