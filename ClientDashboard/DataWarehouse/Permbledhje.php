<?php
include 'dbconn/dbconn.php';
include 'include/SessionStart.php';
include 'include/LicenseChangeKontroll.php';
include 'include/SessionValidation.php';

if(isset($_GET['CID'])&& isset($_GET['date1'])&& isset($_GET['date2'])){
    $_SESSION['CID']=$_GET['CID'];
    $_SESSION['date1']=$_GET['date1'];
    $_SESSION['date2']=$_GET['date2'];
}else{
    include 'include/FilialeValidation.php';
}

if(isset($_POST['filialeBtn'])){
    header('Location: Filialet.php');
}
?>
<?php
$BUSINESSID="";
if($_SESSION['uStatus']=='A'||$_SESSION['uStatus']=='S'){
    $cidCheck=mysqli_prepare($conn, "SELECT b.CODE AS CODE FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE b.BUSINESSID=? AND b.STATUS=1 AND dv.STATUS=1 LIMIT 1");
    mysqli_stmt_bind_param($cidCheck,'s',$_SESSION['buCode']);

    if(mysqli_stmt_execute($cidCheck)){
        $rs=mysqli_stmt_get_result($cidCheck);
        while($result=mysqli_fetch_assoc($rs)){
            $BUSINESSID=$result["CODE"];
        }
    }
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" >
<head runat="server">
    <title>T&euml P&eumlrmbledhura - CelExpres</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
    <meta name="viewport" charset="UTF-8" content="width=device-width, initial-scale=1">
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
<form id="form1" method="post">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-4 col-md-4"></div>
            <div class="col-sm-4 col-md-4">
                <button ID="filialeBtn" type="submit" name="filialeBtn" class="btn btn-primary btn-block btn-lg"><span aria-hidden="true" class="glyphicon glyphicon-arrow-left"></span> FILIALET </button>
                <?php

                $firstDate = date('Y-m-d\TH:i',strtotime($_SESSION['date1']));
                $secondDate =date('Y-m-d\TH:i',strtotime($_SESSION['date2']));

                if(isset($_POST['afishoBtn'])){
                    if(isset($_POST['firstDate']) && isset($_POST['secondDate'])){
                        $firstDate=date('Y-m-d H:i:s',strtotime($_POST['firstDate']));
                        $secondDate=date('Y-m-d H:i:s',strtotime($_POST['secondDate']));

                        if(isset($_SESSION['date1'])&&isset($_SESSION['date2'])){
                            $_SESSION['date1']=$firstDate;
                            $_SESSION['date2']=$secondDate;
                        }

                    }else{

                        $firstDate = date('Y-m-d\T06:00');
                        $secondDate =date('Y-m-d\T06:00',strtotime('+1 day'));
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
                <!--XHIRO DITORE-->
                <div style="background-image: url(assets/div_img1.jpg)" class="padding">
                    <Label ID="Label2" runat="server" style="font-size:15pt;font-weight:bold;" class="control-label" Font-Bold="True">Xhiro ditore:</Label>
                    <div style="margin-left: auto; margin-right: auto; text-align: center;">
                        <?php
                        $sqlXhiroDitore="";
                        if($_SESSION['uStatus']=='A'||$_SESSION['uStatus']=='S'){
                            $sqlXhiroDitore=mysqli_prepare($conn, "SELECT IFNULL(CAST(SUM(VLEFTATVSH) AS DECIMAL(18,2)),0.00) AS XHIRO_DITORE
                                         FROM fatured fd INNER JOIN seksion s ON fd.SEKSIONID=s.SEKSIONID
                                         WHERE fd.BUSINESSID=? AND fd.INSERT_DATE BETWEEN ? AND ? AND fd.CID=? AND fd.STATUS=1 AND fd.IS_HYRJE_DALJE=0 AND s.CID=? AND s.BUSINESSID=?");

                            mysqli_stmt_bind_param($sqlXhiroDitore,'ssssss',$BUSINESSID,date('Y-m-d H:i:s',strtotime($firstDate)),date('Y-m-d H:i:s',strtotime($secondDate)),$_SESSION['CID'],$_SESSION['CID'],$BUSINESSID);

                        }elseif($_SESSION['uStatus']=='B'){
                            $sqlXhiroDitore=mysqli_prepare($conn, "SELECT IFNULL(CAST(SUM(VLEFTATVSH) AS DECIMAL(18,2)),0.00) AS XHIRO_DITORE
                                         FROM fatured fd INNER JOIN seksion s ON fd.SEKSIONID=s.SEKSIONID
                                         INNER JOIN (SELECT b.CODE AS BUSINESSID,dv.KUNDERID AS KUNDERID ,dv.STATUS AS STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE b.STATUS=1 and dv.STATUS=1) d ON d.BUSINESSID=fd.BUSINESSID
                                         WHERE fd.INSERT_DATE BETWEEN ? AND ? AND d.KUNDERID=? AND fd.CID=? AND fd.STATUS=1 AND fd.IS_HYRJE_DALJE=0 AND d.STATUS=1 AND s.CID=?");

                            mysqli_stmt_bind_param($sqlXhiroDitore,'sssss',date('Y-m-d H:i:s',strtotime($firstDate)),date('Y-m-d H:i:s',strtotime($secondDate)),$_COOKIE['KUNDERID'],$_SESSION['CID'],$_SESSION['CID']);

                        }

                        if(mysqli_stmt_execute($sqlXhiroDitore)){
                            $rs=mysqli_stmt_get_result($sqlXhiroDitore);
                            while($result=mysqli_fetch_assoc($rs)){
                                $xhiroT=$result["XHIRO_DITORE"];
                            }
                        }
                        ?>
                        <Label ID="lblXhiroDitore" runat="server" class="control-label" style="font-size:17pt;font-weight:bold;"><?php echo ''.number_format($xhiroT,0).''; ?>  LEK</Label>
                    </div>
                </div>
                <br/><br/><br/>
                <!--XHIRO DITORE E DETAJUAR-->
                <div style="background-image: url(assets/div_img2.jpg)" class="padding">
                    <Label ID="Label4" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;">Xhiro ditore e detajuar:</Label>
                    <table id="tabKam" class="table table-hover table-condensed boldtable">
                        <?php
                        if($_SESSION['uStatus']=='A'||$_SESSION['uStatus']=='S'){
                            $sqlXhiroDitoreP=mysqli_prepare($conn, "SELECT U.USERID, CAST(SUM(F.VLEFTATVSH) AS DECIMAL(18,0)) AS XHIRO_TOTALE
                        FROM (SELECT DISTINCT ft.USERID FROM fatured ft) U INNER JOIN fatured F ON U.USERID=F.USERID
                        INNER JOIN seksion s ON F.SEKSIONID=s.SEKSIONID
                        WHERE F.BUSINESSID=? AND F.INSERT_DATE BETWEEN ? AND ? AND F.CID=? AND F.STATUS=1 AND F.IS_HYRJE_DALJE=0 AND s.CID=? AND s.BUSINESSID=? GROUP BY U.USERID ORDER BY XHIRO_TOTALE ASC");
                            mysqli_stmt_bind_param($sqlXhiroDitoreP,'ssssss',$BUSINESSID,date('Y-m-d H:i:s',strtotime($firstDate)),date('Y-m-d H:i:s',strtotime($secondDate)),$_SESSION['CID'],$_SESSION['CID'],$BUSINESSID);

                        }elseif($_SESSION['uStatus']=='B'){
                            $sqlXhiroDitoreP=mysqli_prepare($conn, "SELECT U.USERID, CAST(SUM(F.VLEFTATVSH) AS DECIMAL(18,0)) AS XHIRO_TOTALE
                        FROM (SELECT DISTINCT ft.USERID FROM fatured ft) U INNER JOIN fatured F ON U.USERID=F.USERID
                        INNER JOIN seksion s ON F.SEKSIONID=s.SEKSIONID
                        INNER JOIN (SELECT b.CODE AS BUSINESSID,dv.KUNDERID AS KUNDERID ,dv.STATUS AS STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE b.STATUS=1 and dv.STATUS=1) d ON d.BUSINESSID=F.BUSINESSID
                        WHERE F.INSERT_DATE BETWEEN ? AND ? AND d.KUNDERID=? AND F.CID=? AND F.STATUS=1 AND F.IS_HYRJE_DALJE=0 AND d.STATUS=1 GROUP BY U.USERID AND s.CID=? ORDER BY XHIRO_TOTALE ASC");
                            mysqli_stmt_bind_param($sqlXhiroDitoreP,'sssss',date('Y-m-d H:i:s',strtotime($firstDate)),date('Y-m-d H:i:s',strtotime($secondDate)),$_COOKIE['KUNDERID'],$_SESSION['CID'],$_SESSION['CID']);

                        }
                        if(mysqli_stmt_execute($sqlXhiroDitoreP)){
                            $rs=mysqli_stmt_get_result($sqlXhiroDitoreP);
                            while($result=mysqli_fetch_assoc($rs)){
                                echo "
                                    <tr>
                                    <td>".$result["USERID"]."</td>
                                    <td >".number_format($result["XHIRO_TOTALE"],0)." LEK</td>
                                    <td ><a href='Detajuara.php?perdoruesi=".$result["USERID"]."'><span aria-hidden='true' class='glyphicon glyphicon-eye-open'></span></a></td>
                                    </tr>
                                     ";
                            }
                        }
                        ?>
                    </table>
                </div>
                <br/><br/><br/>
                <!--TE DHENAT E TAVOLINAVE TE HAPURA DHE TE MBYLLURA-->
                <div class="padding center-block">
                    <Label ID="Label1" runat="server" class="control-label" style="font-size:15pt;font-weight:bold;">T&euml dh&eumlnat e tavolinave:</Label>
                    <hr style="color: Black" />
                    <?php
                    if($_SESSION['uStatus']=='A'||$_SESSION['uStatus']=='S'){
                        $tavH=mysqli_prepare($conn, "SELECT COUNT(DISTINCT s.SEKSIONID) as nrtav,IFNULL(CAST(SUM(fd.VLEFTATVSH) AS DECIMAL(18,2)),0.00) AS Totali
                       FROM seksion s LEFT JOIN fatured fd ON s.SEKSIONID = fd.SEKSIONID
                       WHERE fd.BUSINESSID=? AND fd.INSERT_DATE BETWEEN ? AND ? AND fd.CID=? AND fd.STATUS=1 AND fd.IS_HYRJE_DALJE=0 AND s.STATUS='HAP' AND s.CID=? AND s.BUSINESSID=?");

                        mysqli_stmt_bind_param($tavH,'ssssss',$BUSINESSID,date('Y-m-d H:i:s',strtotime($firstDate)),date('Y-m-d H:i:s',strtotime($secondDate)),$_SESSION['CID'],$_SESSION['CID'],$BUSINESSID);

                    }elseif($_SESSION['uStatus']=='B'){
                        $tavH=mysqli_prepare($conn, "SELECT COUNT(DISTINCT s.SEKSIONID) as nrtav,IFNULL(CAST(SUM(fd.VLEFTATVSH) AS DECIMAL(18,2)),0.00) AS Totali FROM seksion s
                       LEFT JOIN fatured fd ON s.SEKSIONID = fd.SEKSIONID
                       INNER JOIN (SELECT b.CODE AS BUSINESSID,dv.KUNDERID AS KUNDERID ,dv.STATUS AS STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE b.STATUS=1) d ON d.BUSINESSID=fd.BUSINESSID
                       WHERE fd.INSERT_DATE BETWEEN ? AND ? AND d.KUNDERID=? AND fd.CID=? AND fd.STATUS=1 AND fd.IS_HYRJE_DALJE=0 AND s.STATUS='HAP' AND d.STATUS=1 AND s.CID=?");

                        mysqli_stmt_bind_param($tavH,'sssss',date('Y-m-d H:i:s',strtotime($firstDate)),date('Y-m-d H:i:s',strtotime($secondDate)),$_COOKIE['KUNDERID'],$_SESSION['CID'],$_SESSION['CID']);
                    }
                    if(mysqli_stmt_execute($tavH)){
                        $rs=mysqli_stmt_get_result($tavH);
                        while($result=mysqli_fetch_assoc($rs)){
                            $nrTavH=$result["nrtav"];
                            $totali=$result["Totali"];
                            echo '<a id="tavhapurinfo" class="btn btn-success btn-lg btn-block" href="TavolinatNderfaqja.php?statTav=HAP">Tavolina te Hapura : <br>' .number_format($nrTavH,0).' tavolina | '.number_format($totali,2).' LEK</a>';
                        }
                    }
                    ?>
                    <?php
                    if($_SESSION['uStatus']=='A'||$_SESSION['uStatus']=='S'){
                        $tavM=mysqli_prepare($conn, "SELECT COUNT(DISTINCT s.SEKSIONID) as nrtav,SUM(fd.VLEFTATVSH) AS Totali
                       FROM seksion s
                       INNER JOIN fatured fd ON s.SEKSIONID = fd.SEKSIONID
                       INNER JOIN (SELECT b.CODE AS BUSINESSID,dv.KUNDERID AS KUNDERID,dv.STATUS AS STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE b.STATUS=1 and dv.STATUS=1 and dv.BUSINESSID=? LIMIT 1) d ON d.BUSINESSID=fd.BUSINESSID
                       WHERE fd.INSERT_DATE BETWEEN ? AND ? AND fd.CID=? AND fd.STATUS=1 AND fd.IS_HYRJE_DALJE=0 AND s.STATUS='MBY' AND s.CID=? AND s.BUSINESSID=?");

                        mysqli_stmt_bind_param($tavM,'ssssss',$_SESSION['buCode'],date('Y-m-d H:i:s',strtotime($firstDate)),date('Y-m-d H:i:s',strtotime($secondDate)),$_SESSION['CID'],$_SESSION['CID'],$BUSINESSID);

                    }elseif($_SESSION['uStatus']=='B'){
                        $tavM=mysqli_prepare($conn, "SELECT COUNT(DISTINCT s.SEKSIONID) as nrtav,IFNULL(CAST(SUM(fd.VLEFTATVSH) AS DECIMAL(18,2)),0.00) AS Totali
                       FROM seksion s
                       INNER JOIN fatured fd ON s.SEKSIONID = fd.SEKSIONID
                       INNER JOIN (SELECT b.CODE AS BUSINESSID,dv.KUNDERID AS KUNDERID ,dv.STATUS AS STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE b.STATUS=1) d ON d.BUSINESSID=fd.BUSINESSID
                       WHERE fd.INSERT_DATE BETWEEN ? AND ? AND d.KUNDERID=? AND fd.CID=? AND fd.STATUS=1 AND fd.IS_HYRJE_DALJE=0 AND s.STATUS='MBY' AND d.STATUS=1 AND s.CID=?");

                        mysqli_stmt_bind_param($tavM,'sssss',date('Y-m-d H:i:s',strtotime($firstDate)),date('Y-m-d H:i:s',strtotime($secondDate)),$_COOKIE['KUNDERID'],$_SESSION['CID'],$_SESSION['CID']);

                    }
                    if(mysqli_stmt_execute($tavM)){
                        $rs=mysqli_stmt_get_result($tavM);
                        while($result=mysqli_fetch_assoc($rs)){
                            $nrTavM=$result["nrtav"];
                            $totali1=$result["Totali"];
                            echo '<a id="tavmbyllurinfo" class="btn btn-danger btn-lg btn-block" href="TavolinatNderfaqja.php?statTav=MBY">Tavolina te Mbyllura : <br>' .number_format($nrTavM,0).' tavolina | '.number_format($totali1,2).' LEK</a>';
                        }
                    }
                    ?>
                    <hr style="color: Black" />
                </div>
                <br/><br/><br/>
                <!--XHIRO MESATARE E 10 DITEVE-->
                <div style="background-image: url(assets/div_img3.jpg)" class="padding">
                    <Label ID="Label6" runat="server" class='control-label' style="font-size:15pt;font-weight:bold;">Xhiro mesatare p&eumlr 10 dit&euml:</Label>
                    <div style="margin-left: auto; margin-right: auto; text-align: center;">
                        <?php
                        $a=date('Y-m-d H:i:s',strtotime($secondDate.'-10 day'));
                        $b=date('Y-m-d H:i:s',strtotime($secondDate));
                        if($_SESSION['uStatus']=='A'||$_SESSION['uStatus']=='S'){
                            $mes10=mysqli_prepare($conn, "SELECT SUM(fd.VLEFTATVSH)/10 AS Totali FROM fatured fd
                       INNER JOIN (SELECT b.CODE AS BUSINESSID,dv.KUNDERID AS KUNDERID,dv.STATUS AS STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE b.STATUS=1 and dv.STATUS=1 and dv.BUSINESSID=? LIMIT 1) d ON d.BUSINESSID=fd.BUSINESSID
                       WHERE fd.INSERT_DATE BETWEEN ? AND ? AND fd.CID=? AND fd.STATUS=1 AND fd.IS_HYRJE_DALJE=0 AND d.STATUS=1");

                            mysqli_stmt_bind_param($mes10,'ssss',$_SESSION['buCode'],date('Y-m-d H:i:s',strtotime('-10 day')),date('Y-m-d H:i:s',strtotime($secondDate)),$_SESSION['CID']);

                        }elseif($_SESSION['uStatus']=='B'){
                            $mes10=mysqli_prepare($conn, "SELECT SUM(fd.VLEFTATVSH)/10 AS Totali FROM fatured fd
                       INNER JOIN (SELECT b.CODE AS BUSINESSID,dv.KUNDERID AS KUNDERID ,dv.STATUS AS STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE b.STATUS=1) d ON d.BUSINESSID=fd.BUSINESSID
                       WHERE fd.INSERT_DATE BETWEEN ? AND ? AND d.KUNDERID=? AND fd.CID=? AND fd.STATUS=1 AND fd.IS_HYRJE_DALJE=0 AND d.STATUS=1");

                            mysqli_stmt_bind_param($mes10,'ssss',date('Y-m-d H:i:s',strtotime('-10 day')),date('Y-m-d H:i:s',strtotime($secondDate)),$_COOKIE['KUNDERID'],$_SESSION['CID']);

                        }

                        if(mysqli_stmt_execute($mes10)){
                            $rs=mysqli_stmt_get_result($mes10);
                            while($result=mysqli_fetch_assoc($rs)){
                                $totali=$result["Totali"];
                                echo'<Label ID="lblXhiroMesatare" runat="server" class="control-label" style="font-size:17pt;font-weight:bold;">'.number_format($totali,0).' LEK</Label>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <br/><br/><br/>
                <!--ARTIKUJT ME TE SHITUR NE 10 DITE-->
                <div style="background-image: url(assets/div_img4.jpg)" class="margin">
                    <Label ID="Label8" runat="server"  class="control-label" style="font-size:15pt;font-weight:bold;">10 artikujt m&euml t&euml shitur p&eumlr 10 dit&euml (Nqs Ka):</Label>
                    <table id="tabArt" runat="server" class="table table-hover table-condensed boldtable">
                        <?php
                        if($_SESSION['uStatus']=='A'||$_SESSION['uStatus']=='S'){
                            $artikullM=mysqli_prepare($conn, "SELECT DISTINCT fd.ARTIKULL AS artikulli,SUM(fd.SASIA) AS Totali,SUM(fd.SASIA*fd.CMIMSHITJESTVSH) AS VLEFTA
                             FROM faturedrel fd
                             JOIN fatured f ON fd.FATUREDID=f.FATUREDID
                             JOIN (SELECT b.CODE AS BUSINESSID,dv.STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE b.STATUS=1 AND dv.STATUS=1 AND dv.BUSINESSID=? LIMIT 1) d ON f.BUSINESSID=d.BUSINESSID
                             WHERE f.INSERT_DATE BETWEEN ? AND ?
                             AND f.IS_HYRJE_DALJE = 0
                             AND f.STATUS = 1
                             AND f.CID=?
                             AND fd.CID=?
                             AND fd.BUSINESSID=?
                             GROUP BY fd.ARTIKULL
                             UNION ALL
                             SELECT 'TOTAL' artikulli,SUM(fd.SASIA),SUM(fd.SASIA*fd.CMIMSHITJESTVSH)
                             FROM faturedrel fd
                             JOIN fatured f ON fd.FATUREDID=f.FATUREDID
                             JOIN (SELECT b.CODE AS BUSINESSID,dv.STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE b.STATUS=1 AND dv.STATUS=1 AND dv.BUSINESSID=? LIMIT 1) d
                             ON f.BUSINESSID=d.BUSINESSID
                             WHERE f.INSERT_DATE BETWEEN ? AND ?
                             AND f.IS_HYRJE_DALJE = 0
                             AND f.STATUS = 1
                             AND f.CID=?
                             AND fd.CID=?
                             AND fd.BUSINESSID=?
                             ORDER BY Totali desc LIMIT 11");

                            mysqli_stmt_bind_param($artikullM,'ssssssssssss',$_SESSION['buCode'],date('Y-m-d H:i:s',strtotime('-10 day')),date('Y-m-d H:i:s'),$_SESSION['CID'],$_SESSION['CID'],$BUSINESSID,$_SESSION['buCode'],date('Y-m-d H:i:s',strtotime($secondDate.'-10 day')),date('Y-m-d H:i:s',strtotime($secondDate)),$_SESSION['CID'],$_SESSION['CID'],$BUSINESSID);

                        }elseif($_SESSION['uStatus']=='B'){
                            $artikullM=mysqli_prepare($conn, " SELECT DISTINCT fd.ARTIKULL AS artikulli,SUM(fd.SASIA) AS Totali,SUM(fd.SASIA*fd.CMIMSHITJESTVSH) AS VLEFTA
                             FROM faturedrel fd
                             JOIN fatured f ON fd.FATUREDID=f.FATUREDID
                             JOIN (SELECT b.CODE AS BUSINESSID,dv.KUNDERID AS KUNDERID ,dv.STATUS AS STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE b.STATUS=1) d ON f.BUSINESSID=d.BUSINESSID
                             WHERE f.INSERT_DATE BETWEEN ? AND ?
                             AND f.IS_HYRJE_DALJE = 0
                             AND f.STATUS = 1
                             AND d.KUNDERID=?
                             AND f.CID=?
                             AND fd.CID=?
                             GROUP BY fd.ARTIKULL
                             UNION ALL
                             SELECT 'TOTAL' artikulli,SUM(fd.SASIA),SUM(fd.SASIA*fd.CMIMSHITJESTVSH)
                             FROM faturedrel fd
                             JOIN fatured f ON fd.FATUREDID=f.FATUREDID
                             JOIN (SELECT b.CODE AS BUSINESSID,dv.KUNDERID AS KUNDERID ,dv.STATUS AS STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE b.STATUS=1) d
                             ON f.BUSINESSID=d.BUSINESSID
                             WHERE f.INSERT_DATE BETWEEN ? AND ?
                             AND f.IS_HYRJE_DALJE = 0
                             AND f.STATUS = 1
                             AND d.KUNDERID=?
                             AND f.CID=?
                             AND fd.CID=?
                             ORDER BY Totali desc LIMIT 11");

                            mysqli_stmt_bind_param($artikullM,'ssssssssss',date('Y-m-d H:i:s',strtotime($secondDate .'- 10 day')),date('Y-m-d H:i:s',strtotime($secondDate)),$_COOKIE['KUNDERID'],$_SESSION['CID'],$_SESSION['CID'],date('Y-m-d H:i:s',strtotime($secondDate.'-10 day')),date('Y-m-d H:i:s',strtotime($secondDate)),$_COOKIE['KUNDERID'],$_SESSION['CID'],$_SESSION['CID']);
                        }

                        if(mysqli_stmt_execute($artikullM)){
                            $rs=mysqli_stmt_get_result($artikullM);
                            while($result=mysqli_fetch_assoc($rs)){
                                $artikulliMeIShitur=$result["artikulli"];
                                $artikulliMeIShiturCmimi=$result["VLEFTA"];
                                echo'<tr>';
                                echo '<td>'.$artikulliMeIShitur.'</td>';
                                echo '<td>'.$result["Totali"].'</td>';
                                echo '<td>'.number_format($artikulliMeIShiturCmimi,0).' LEK</td>';
                                echo'<tr>';
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
            <div class="col-sm-4 col-md-4"></div>
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

