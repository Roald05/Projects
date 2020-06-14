<?php
include 'dbconn/dbconn.php';
include 'include/SessionStart.php';
include 'include/SessionValidation.php';
include 'include/LicenseChangeKontroll.php';
include 'Logout.php';
?>
<html xmlns="http://www.w3.org/1999/xhtml"><head><title>
        Softexpres - CelExpres
    </title><link href="css/bootstrap.css" rel="stylesheet" type="text/css"><meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="js/jquery.min.js"></script>
    <style>
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
<form name="form13" method="post" id="form13">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-4 col-md-4"></div>
            <div class="col-sm-4 col-md-4">
                <button ID="logoutBtn" name="logoutBtn" type="submit" Class="btn btn-danger btn-block btn-lg"><span aria-hidden="true" class="glyphicon glyphicon-log-out"></span> DALJE </button>

                <?php
                if(isset($_SESSION['date1']) && isset($_SESSION['date2'])){
                    $firstDate = date('Y-m-d\TH:i',strtotime($_SESSION['date1']));
                    $secondDate =date('Y-m-d\TH:i',strtotime($_SESSION['date2']));
                }else{
                    $firstDate = date('Y-m-d\T06:00');
                    $secondDate =date('Y-m-d\T06:00',strtotime('+1 day'));
                }

                if(isset($_POST['afishoBtn'])){
                    if(isset($_POST['firstDate']) && isset($_POST['secondDate'])){
                        $firstDate=date('Y-m-d H:i:s',strtotime($_POST['firstDate']));
                        $secondDate=date('Y-m-d H:i:s',strtotime($_POST['secondDate']));
                    }
                }

                ?>
                <br><br>
                <div class="text-center">
                    <Label ID="Label4" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;">NGA :</Label>

                    <input name="firstDate" type="datetime-local" id="firstDate" class="form-control" placeholder="VALIDATION DATE..." style="font-size:Large;" value="<?php echo''.date('Y-m-d\TH:i',strtotime($firstDate)).'' ?>">
                    <br/>
                    <Label ID="Label4" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;">DERI NE :</Label>

                    <input name="secondDate" type="datetime-local" id="secondDate" class="form-control" placeholder="VALIDATION DATE..." style="font-size:Large;" value="<?php echo''.date('Y-m-d\TH:i',strtotime($secondDate)).'' ?>">
                </div>
                <br/>
                <br/>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm">
                            <button ID="afishoBtn" name="afishoBtn" type="submit" Class="btn btn-success btn-block btn-lg"><span aria-hidden="true" class="glyphicon glyphicon-calendar"></span> AFISHO </button>
                        </div>
                    </div>
                </div>
                <br/>
                <br/>
                <div style="background-image: url(assets/div_img2.jpg)" class="padding">
                    <Label ID="Label4" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;">Xhirot totale te biznesit:</Label>
                    <table id="filifalet" class="table table-hover table-condensed boldtable">
                        <?php
                        if(isset($_SESSION['uStatus'])){
                            if($_SESSION['uStatus']=='A'||$_SESSION['uStatus']=='S'){
                                $TotaliFilialet=mysqli_prepare($conn,"SELECT d.BUSINESSID AS BUSINESSID,CAST(SUM(F.VLEFTATVSH) AS DECIMAL(18,0)) AS XHIRO_TOTALE
                                          FROM fatured F
                                          INNER JOIN seksion s ON F.SEKSIONID=s.SEKSIONID
                                          INNER JOIN (SELECT b.CODE AS BUSINESSID,dv.KUNDERID AS KUNDERID ,dv.STATUS AS STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE dv.BUSINESSID=? LIMIT 1) d ON d.BUSINESSID=F.BUSINESSID
                                          INNER JOIN cids c ON F.CID=c.CID
                                          INNER JOIN businesses b ON F.BUSINESSID=b.CODE
                                          WHERE F.INSERT_DATE BETWEEN ? AND ? AND b.STATUS=1 AND c.STATUS=1  AND d.STATUS=1 AND F.STATUS=1 AND F.IS_HYRJE_DALJE=0 GROUP BY d.BUSINESSID ORDER BY d.BUSINESSID ASC");
                                //echo''.$secondDate.' : '.$firsDate.'';
                                mysqli_stmt_bind_param($TotaliFilialet,'sss',$_SESSION['buCode'],date('Y-m-d H:i:s',strtotime($firstDate)),date('Y-m-d H:i:s',strtotime($secondDate)));

                            }elseif($_SESSION['uStatus']=='B'){
                                $TotaliFilialet=mysqli_prepare($conn,"SELECT d.BUSINESSID AS BUSINESSID,CAST(SUM(F.VLEFTATVSH) AS DECIMAL(18,0)) AS XHIRO_TOTALE
                                          FROM fatured F
                                          INNER JOIN seksion s ON F.SEKSIONID=s.SEKSIONID
                                          INNER JOIN (SELECT b.CODE AS BUSINESSID,dv.KUNDERID AS KUNDERID ,dv.STATUS AS STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID) d ON d.BUSINESSID=F.BUSINESSID
                                          INNER JOIN cids c ON F.CID=c.CID
                                          INNER JOIN businesses b ON F.BUSINESSID=b.CODE
                                          WHERE F.INSERT_DATE BETWEEN ? AND ? AND b.STATUS=1 AND c.STATUS=1 AND d.KUNDERID=? AND d.STATUS=1 AND F.STATUS=1 AND F.IS_HYRJE_DALJE=0 GROUP BY d.BUSINESSID ORDER BY d.BUSINESSID ASC");
                                //echo''.$secondDate.' : '.$firsDate.'';
                                mysqli_stmt_bind_param($TotaliFilialet,'sss',date('Y-m-d H:i:s',strtotime($firstDate)),date('Y-m-d H:i:s',strtotime($secondDate)),$_COOKIE['KUNDERID']);
                            }
                        }
                        if(mysqli_stmt_execute($TotaliFilialet)){
                            $rs1=mysqli_stmt_get_result($TotaliFilialet);
                            while($result1=mysqli_fetch_assoc($rs1)){
                                echo "
                                    <tr>
                                    <td>".$result1["BUSINESSID"]."</td>
                                    <td >".number_format($result1["XHIRO_TOTALE"],0)." LEK</td>
                                    <td >100%</td>
                                    </tr>
                                     ";
                                $XhiroTotale=$result1["XHIRO_TOTALE"];
                            }
                        }
                        ?>
                    </table>
                </div>
                </br>
                </br>
                <div style="background-image: url(assets/div_img2.jpg)" class="padding">
                    <Label ID="Label4" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;">Xhirot perkatese te filialeve:</Label>
                    <table id="filifalet" class="table table-hover table-condensed boldtable">

                        <?php
                        if(isset($_SESSION['uStatus'])){
                            if($_SESSION['uStatus']=='A'||$_SESSION['uStatus']=='S'){
                                $filialet=mysqli_prepare($conn,"SELECT DISTINCT c.CID,CAST(SUM(F.VLEFTATVSH) AS DECIMAL(18,0)) AS XHIRO_TOTALE
                                          FROM fatured F
                                          INNER JOIN seksion s ON F.SEKSIONID=s.SEKSIONID
                                          INNER JOIN (SELECT b.CODE AS BUSINESSID,dv.BUSINESSID AS CODE,dv.KUNDERID AS KUNDERID,dv.STATUS AS STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE dv.BUSINESSID=? LIMIT 1) d ON d.BUSINESSID=F.BUSINESSID
                                          INNER JOIN cids c ON F.CID=c.CID
                                          INNER JOIN businesses b ON F.BUSINESSID=b.CODE
                                          WHERE F.INSERT_DATE BETWEEN ? AND ? AND b.STATUS=1 AND c.STATUS=1 AND d.STATUS=1 AND F.STATUS=1 AND F.IS_HYRJE_DALJE=0
                                          GROUP BY F.CID ORDER BY F.CID ASC");
                                //echo''.$secondDate.' : '.$firsDate.'';
                                mysqli_stmt_bind_param($filialet,'sss',$_SESSION['buCode'],date('Y-m-d H:i:s',strtotime($firstDate)),date('Y-m-d H:i:s',strtotime($secondDate)));
                            }elseif($_SESSION['uStatus']=='B'){
                                $filialet=mysqli_prepare($conn,"SELECT DISTINCT c.CID,CAST(SUM(F.VLEFTATVSH) AS DECIMAL(18,0)) AS XHIRO_TOTALE
                                          FROM fatured F
                                          INNER JOIN seksion s ON F.SEKSIONID=s.SEKSIONID
                                          INNER JOIN (SELECT b.CODE AS BUSINESSID,dv.KUNDERID AS KUNDERID,dv.STATUS AS STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID) d ON d.BUSINESSID=F.BUSINESSID
                                          INNER JOIN businesses b ON F.BUSINESSID=b.CODE
                                          INNER JOIN cids c ON F.CID=c.CID
                                          WHERE F.INSERT_DATE BETWEEN ? AND ? AND d.KUNDERID=? AND b.STATUS=1 AND c.STATUS=1 AND  d.STATUS=1 AND F.STATUS=1 AND F.IS_HYRJE_DALJE=0
                                          GROUP BY F.CID ORDER BY F.CID ASC");
                                //echo''.$secondDate.' : '.$firsDate.'';
                                mysqli_stmt_bind_param($filialet,'sss',date('Y-m-d H:i:s',strtotime($firstDate)),date('Y-m-d H:i:s',strtotime($secondDate)),$_COOKIE['KUNDERID']);
                            }
                        }
                        if(mysqli_stmt_execute($filialet)){
                            $rs=mysqli_stmt_get_result($filialet);
                            while($result=mysqli_fetch_assoc($rs)){
                                echo "
                                    <tr>
                                    <td>".$result["CID"]."</td>
                                    <td >".number_format($result["XHIRO_TOTALE"],0)." LEK</td>
                                    <td >".round(($result["XHIRO_TOTALE"]/$XhiroTotale) * 100)."%</td>
                                    <td ><a href='Permbledhje.php?CID=".$result["CID"]."&date1=".$firstDate."&date2=".$secondDate."'><span aria-hidden='true' class='glyphicon glyphicon-eye-open'></span></a></td>
                                    </tr>
                                     ";
                            }
                        }
                        ?>
                    </table>
                </div>
                </br>
                </br>
            </div>
        </div>
    </div>
</form>
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