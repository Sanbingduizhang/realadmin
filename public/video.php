<?php
 $vidoeUrl = $_REQUEST["videourl"];
 ?>
<html>
    <body>
    <video id="video_content" style="width:100%; height:553px; max-height:553px" controls="controls" autoplay="autoplay">                                   <source src=<?php echo $vidoeUrl; ?> type="video/mp4" valuetype="ref">         
    </video>
    </body>
</html>
