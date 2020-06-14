<?php

?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        #loading {
            background:url("ajax-loader.gif") no-repeat center;
            height: 100px; width: 100px;
            position: fixed; left: 50%; top: 50%; z-index: 1000;
            margin: -25px 0 0 -25px;
        }
    </style>
</head>
<body style="background-color: white">
<div id="loading"> </div>
</body>
</html>
<script src='jquery/jquery-3.2.1.min.js' type='text/javascript'>
    $(window).bind("load", function() {
        $('#loading').fadeOut(2000);
    });
</script>

