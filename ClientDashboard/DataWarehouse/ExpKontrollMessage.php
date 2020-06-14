
    <html xmlns="http://www.w3.org/1999/xhtml"><head><title>
            Softexpres - CelExpres
        </title><link href="css/bootstrap.css" rel="stylesheet" type="text/css"><meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            body {
                padding-top: 60px;
            }
            @media (max-width: 980px) {
                body {
                    padding-top: 30px;
                }
            }
        </style>
    </head>
    <body background="assets/back_img.jpg">
    <form name="form1" method="post" id="form1">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4 col-md-4"></div>
                <div class="col-sm-4 col-md-4">
                    <?php
                    session_start();
                    unset($_SESSION['buID']);
                    unset($_SESSION['businessCode']);
                    unset($_SESSION['uStatus']);
                    unset($_SESSION['buCode']);
                    unset($_SESSION['buDesc']);
                    unset($_SESSION['perdoruesi']);
                    unset($_SESSION['fjalekalimi']);
                    unset($_SESSION['CID']);
                    unset($_SESSION['date1']);
                    unset($_SESSION['date2']);
                    session_destroy();
                    echo '<span id="Label3" class="control-label" style="color:#a50020;font-size:Large;font-weight:bold;"><span aria-hidden="true" class="glyphicon glyphicon glyphicon-warning-sign"></span> Licensa e pajisjes aktuale ka skaduar ! </span><br><br>';
                    ?>
                    <br><br>
                </div>
            </div>
        </div>
    </form>
    </body>
    </html>


