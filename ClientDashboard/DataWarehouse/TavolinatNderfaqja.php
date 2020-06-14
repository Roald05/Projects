<html xmlns="http://www.w3.org/1999/xhtml" >
<head id="Head1">
    <title>Tavolinat - CelExpres</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <script src="js/jquery.min.js"></script>

    <style type="text/css">
        body {
            padding-top: 60px;
        }
        @media (max-width: 980px) {
            body {
                padding-top: 30px;
            }
        }
        span.glyphicon-eye-open {
            font-size: 1.3em;
        }
        .boldtable, .boldtable TD {
            font-size:13pt;
        }
        div.padding {
            padding-top: 15px;
            padding-right: 10px;
            padding-bottom: 15px;
            padding-left: 10px;
        }
        div.margin {
            padding-top: 15px;
            padding-right: 10px;
            padding-bottom: 5px;
            padding-left: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body background="assets/back_img.jpg">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-4 col-md-4"></div>
            <div class="col-sm-4 col-md-4">
               <?php include 'Tavolinat.php'; ?>
            </div>
            </div>
            <div class="col-sm-4 col-md-4"></div>
        </div>
    </div>
</body>
</html>
<script>
    $(document).on('keyup keypress', function(e){
        var keyCode = e.keyCode || e.which;
        if(keyCode === 13){
            e.preventDefault();
            return false;
        }
    });
</script>
