<?php
include 'dbconn/dbconn.php';
include 'include/SessionStart.php';
include 'include/SessionValidation.php';
include 'include/LicenseChangeKontroll.php';
include 'include/FilialeValidation.php';
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
<?php
if(isset($_GET['perdoruesi'])){

    if($_SESSION['uStatus']=='A'||$_SESSION['uStatus']=='S'){
        $perdoruesCheck=mysqli_prepare($conn, "SELECT USERNAMED FROM (SELECT DISTINCT ft.USERID AS USERNAMED,ft.BUSINESSID AS BUSINESSID FROM fatured ft) u
                                               WHERE u.BUSINESSID=? AND u.USERNAMED=?");
        mysqli_stmt_bind_param($perdoruesCheck,'ss',$BUSINESSID,$_GET['perdoruesi']);

    }elseif($_SESSION['uStatus']=='B'){
        $perdoruesCheck=mysqli_prepare($conn, "SELECT USERNAMED FROM (SELECT DISTINCT ft.USERID AS USERNAMED,ft.BUSINESSID AS BUSINESSID FROM fatured ft) u
                                      INNER JOIN (SELECT b.CODE AS BUSINESSID,dv.KUNDERID AS KUNDERID ,dv.STATUS AS STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE b.STATUS=1) d ON u.BUSINESSID=d.BUSINESSID
                                      INNER JOIN fatured f ON u.BUSINESSID=f.BUSINESSID where u.USERNAMED=? AND d.KUNDERID=? AND d.STATUS=1");
        mysqli_stmt_bind_param($perdoruesCheck,'ss',$_GET['perdoruesi'],$_COOKIE['KUNDERID']);

    }

        if(mysqli_stmt_execute($perdoruesCheck)){
            $rs=mysqli_stmt_get_result($perdoruesCheck);
            if (mysqli_num_rows($rs) > 0){
                $perdoruesi=$_GET["perdoruesi"];
            }else{
                $perdoruesi='SoftExpres';
                echo'<div class="container-fluid">';
                echo'<div class="row">';
                echo'<div class="col-sm-4 col-md-4"></div>';
                echo'<div class="col-sm-4 col-md-4">';
                echo'<span id="Label1" class="control-label" style="color:Red;font-size:Large;font-weight:bold;"><span aria-hidden="true" class="glyphicon glyphicon glyphicon-alert"></span> Ky perdorues nuk ekziston!</span><br><br>';
                echo'</div>';
                echo'</div>';
                echo'</div>';
            }

        }
}
?>


<html>
<head id="Head1">
    <title>T&euml Detajuara - CelExpres</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            font-size: 1.5em;
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
            padding-bottom: 15px;
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
                <a type="submit" ID="backBtn" Class="btn btn-danger btn-block btn-lg" name="backBtn" href="Permbledhje.php"><span aria-hidden="true" class="glyphicon glyphicon-arrow-left"></span> KTHEHU PAS </a>
                <br/><br/>
                <p class="text-center">
                    <span id="lblKam" class="control-label" style="font-size:22pt;font-weight:bold;text-decoration:underline;"><?php echo"$perdoruesi"; ?></span>
                </p>
                <br/>
                <!--KOKA E FATURES-->
                <div style="background-image: url(assets/div_img5.jpg)" class="padding">
                    <span id="Label4" class="control-label" style="font-size:15pt;font-weight:bold;"> Fatura e fundit: </span>
                    <div style="margin-left: auto; margin-right: auto; text-align: center;">
                        <?php
                        if($perdoruesi != 'SoftExpres'){
                            try{
                                if($_SESSION['uStatus']=='A'||$_SESSION['uStatus']=='S'){
                                    $sqlFaturaFun=mysqli_prepare($conn, "SELECT a.ARTIKULL AS emertim, fdr.SASIA AS sasia, fd.INSERT_DATE AS data,CAST(fd.VLEFTATVSH AS DECIMAL(18,0)) AS totali
                                     FROM fatured fd, faturedrel fdr, (SELECT DISTINCT ARTIKULL FROM faturedrel) a
                                     WHERE fd.FATUREDID = fdr.FATUREDID
                                     AND fdr.ARTIKULL = a.ARTIKULL
                                     AND fdr.CID = ?
                                     AND fdr.BUSINESSID=?
                                     AND fd.FATUREDID IN (
                                     SELECT MAX(F.FATUREDID)
                                     FROM fatured F
                                     WHERE F.BUSINESSID=?
                                     AND F.USERID = ?
                                     AND F.STATUS = 1
                                     AND F.IS_HYRJE_DALJE = 0
                                     AND F.CID=?
                                     AND F.INSERT_DATE BETWEEN ? AND ?
                                     )");
                                    mysqli_stmt_bind_param($sqlFaturaFun,'sssssss',$_SESSION['CID'],$BUSINESSID,$BUSINESSID,$perdoruesi,$_SESSION['CID'],date('Y-m-d H:i:s',strtotime($_SESSION['date1'])),date('Y-m-d H:i:s',strtotime($_SESSION['date2'])));

                                }elseif($_SESSION['uStatus']=='B'){
                                    $sqlFaturaFun=mysqli_prepare($conn, "SELECT a.ARTIKULL AS emertim, fdr.SASIA AS sasia, fd.INSERT_DATE AS data,CAST(fd.VLEFTATVSH AS DECIMAL(18,0)) AS totali
                                     FROM fatured fd, faturedrel fdr, (SELECT DISTINCT ARTIKULL FROM faturedrel) a
                                     WHERE fd.FATUREDID = fdr.FATUREDID
                                     AND fdr.ARTIKULL = a.ARTIKULL
                                     AND fdr.CID = ?
                                     AND fd.FATUREDID IN (
                                     SELECT MAX(F.FATUREDID)
                                     FROM fatured F
                                     INNER JOIN (SELECT b.CODE AS BUSINESSID,dv.KUNDERID AS KUNDERID ,dv.STATUS AS STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE b.STATUS=1) d
                                     ON F.BUSINESSID=d.BUSINESSID
                                     WHERE F.USERID = ?
                                     AND F.STATUS = 1
                                     AND F.IS_HYRJE_DALJE = 0
                                     AND d.KUNDERID=?
                                     AND F.CID=?
                                     AND d.STATUS=1
                                     AND F.INSERT_DATE BETWEEN ? AND ?
                                     )");
                                    mysqli_stmt_bind_param($sqlFaturaFun,'ssssss',$_SESSION['CID'],$perdoruesi,$_COOKIE['KUNDERID'],$_SESSION['CID'],date('Y-m-d H:i:s',strtotime($_SESSION['date1'])),date('Y-m-d H:i:s',strtotime($_SESSION['date2'])));
                                }
                                if(mysqli_stmt_execute($sqlFaturaFun)){
                                    $rs=mysqli_stmt_get_result($sqlFaturaFun);

                                    while($result=mysqli_fetch_assoc($rs)){
                                        $dateFatF=date("d M Y H:i:s",strtotime($result["data"]));
                                    }
                                }
                            }catch (Exception $e){
                                echo'<td>"DETAJET E FATURES NUK U NGARKUAN ME SUKSES"</td>';
                                echo'<td>'.$e.'</td>';
                            }
                        }
                        ?>
                        <span id="dtFat" class="control-label" style="font-size:17pt;font-weight:bold;"><?php if($perdoruesi != 'SoftExpres'){echo"$dateFatF";} ?></span>
                    </div>
                    <!--DETAJET E FATURES-->
                    <table id="tabFatura" class="table table-hover table-condensed boldtable" >
                        <?php
                        if($perdoruesi != 'SoftExpres'){
                            try{
                                if(mysqli_stmt_execute($sqlFaturaFun)){
                                    $rs=mysqli_stmt_get_result($sqlFaturaFun);

                                    while($result=mysqli_fetch_assoc($rs)){
                                        $emArtikull=$result["emertim"];
                                        $saArtikull=$result["sasia"];
                                        $totArtikull=$result["totali"];
                                        echo'<tr>';
                                        echo '<td>'.$emArtikull.'</td>';
                                        echo '<td>'.$saArtikull.'</td>';
                                        echo'<tr>';
                                    }
                                }
                                mysqli_stmt_close($sqlFaturaFun);
                            }catch (Exception $e){
                                echo'<td>"DETAJET E FATURES NUK U NGARKUAN ME SUKSES"</td>';
                                echo'<td>'.$e->getMessage().'</td>';
                            }
                        }
                        ?>
                        <tr class="warning">
                            <td>TOTALI</td>
                            <td><?php if($perdoruesi != 'SoftExpres'){echo''.number_format($totArtikull,0).'';} ?> LEK</td>
                        </tr>
                    </table>
                </div>
                <br/><br/><br/>
                <!--ARTIKUJT ME TE SHITUR SOT-->
                <div style="background-image: url(assets/div_img4.jpg)" class="padding">
                    <span id="Label8" class="control-label" style="font-size:15pt;font-weight:bold;">Artikujt e shitur gjate kesaj periudhe:</span>

                    <table id="tabArtSot" class="table table-hover table-condensed boldtable" >
                        <?php
                        if($perdoruesi != 'SoftExpres'){
                            try{
                                if($_SESSION['uStatus']=='A'||$_SESSION['uStatus']=='S'){
                                    $sqlArtikujSot=mysqli_prepare($conn, "SELECT DISTINCT fdr.ARTIKULL AS artikull, cast(fdr.CMIMISHITJES AS DECIMAL(18,0))AS cmimi, CAST(SUM(fdr.SASIA) AS DECIMAL(18,0)) AS sasia
                                    FROM fatured fd, faturedrel fdr
                                    WHERE fd.FATUREDID = fdr.FATUREDID
                                    AND fd.BUSINESSID=?
                                    AND fd.INSERT_DATE
                                    AND fdr.CID = ?
                                    AND fdr.BUSINESSID = ?
                                    BETWEEN ? AND ?
                                    AND fd.USERID =?
                                    AND fd.IS_HYRJE_DALJE=0
                                    AND fd.STATUS=1
                                    AND fd.CID=?
                                    GROUP BY fdr.ARTIKULL, fdr.CMIMISHITJES ORDER BY sasia DESC");

                                    mysqli_stmt_bind_param($sqlArtikujSot,'sssssss',$BUSINESSID,$_SESSION['CID'],$BUSINESSID,date('Y-m-d H:i:s',strtotime($_SESSION['date1'])),date('Y-m-d H:i:s',strtotime($_SESSION['date2'])),$perdoruesi,$_SESSION['CID']);

                                }elseif($_SESSION['uStatus']=='B'){
                                    $sqlArtikujSot=mysqli_prepare($conn, "SELECT DISTINCT fdr.ARTIKULL AS artikull, cast(fdr.CMIMISHITJES AS DECIMAL(18,0))AS cmimi, CAST(SUM(fdr.SASIA) AS DECIMAL(18,0)) AS sasia
                                    FROM fatured fd, faturedrel fdr,  (SELECT b.CODE AS BUSINESSID,dv.KUNDERID AS KUNDERID ,dv.STATUS AS STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE b.STATUS=1) d
                                    WHERE fd.FATUREDID = fdr.FATUREDID
                                    AND fd.BUSINESSID=d.BUSINESSID
                                    AND fd.INSERT_DATE
                                    AND fdr.CID = ?
                                    BETWEEN ? AND ?
                                    AND fd.USERID =?
                                    AND fd.IS_HYRJE_DALJE=0
                                    AND fd.STATUS=1
                                    AND d.KUNDERID=?
                                    AND fd.CID=?
                                    AND d.STATUS=1
                                    GROUP BY fdr.ARTIKULL, fdr.CMIMISHITJES ORDER BY sasia DESC");

                                    mysqli_stmt_bind_param($sqlArtikujSot,'ssssss',$_SESSION['CID'],date('Y-m-d H:i:s',strtotime($_SESSION['date1'])),date('Y-m-d H:i:s',strtotime($_SESSION['date2'])),$perdoruesi,$_COOKIE['KUNDERID'],$_SESSION['CID']);
                                }
                                if(mysqli_stmt_execute($sqlArtikujSot)){
                                    $rs=mysqli_stmt_get_result($sqlArtikujSot);
                                    while($result1=mysqli_fetch_assoc($rs)){
                                        $artikull=$result1["artikull"];
                                        $sasia=$result1["sasia"];
                                        $cmimiA=$result1["cmimi"];
                                        echo'<tr>';
                                        echo '<td>'.$artikull.'</td>';
                                        echo '<td>'.$sasia.'</td>';
                                        echo '<td>'.number_format($cmimiA,0).' LEK</td>';
                                        echo'<tr>';
                                    }
                                }
                                mysqli_stmt_close($sqlArtikujSot);
                            }catch (Exception $e){
                                echo'<td>"DETAJET E ARTUKJVE NUK U NGARKUAN ME SUKSES"</td>';
                                echo'<td>'.$e->getMessage().'</td>';
                            }
                        }
                        ?>
                        <tr class="success">
                            <td>TOTALI</td>
                            <?php
                            if($perdoruesi != 'SoftExpres'){
                                try{
                                    if($_SESSION['uStatus']=='A'||$_SESSION['uStatus']=='S'){
                                        $sqlTotaliArtikujSot=mysqli_prepare($conn, "SELECT IFNULL(CAST(SUM(T.cmimi*T.sasia) AS DECIMAL(18,0)),0) AS TotaliSot FROM (SELECT DISTINCT fdr.ARTIKULL AS artikull, cast(fdr.CMIMISHITJES AS DECIMAL(18,0))AS cmimi, CAST(SUM(fdr.SASIA) AS DECIMAL(18,0)) AS sasia
                                        FROM fatured fd, faturedrel fdr
                                        WHERE fd.FATUREDID = fdr.FATUREDID
                                        AND fd.BUSINESSID=?
                                        AND fd.INSERT_DATE
                                        BETWEEN ? AND ?
                                        AND fd.USERID =?
                                        AND fd.IS_HYRJE_DALJE=0
                                        AND fd.STATUS=1
                                        AND fd.CID=?
                                        AND fdr.CID=?
                                        AND fdr.BUSINESSID=?
                                        GROUP BY fdr.ARTIKULL, fdr.CMIMISHITJES ORDER BY sasia DESC) AS T");

                                        mysqli_stmt_bind_param($sqlTotaliArtikujSot,'sssssss',$BUSINESSID,date('Y-m-d H:i:s',strtotime($_SESSION['date1'])),date('Y-m-d H:i:s',strtotime($_SESSION['date2'])),$perdoruesi,$_SESSION['CID'],$_SESSION['CID'],$BUSINESSID);

                                    }elseif($_SESSION['uStatus']=='B'){
                                        $sqlTotaliArtikujSot=mysqli_prepare($conn, "SELECT IFNULL(CAST(SUM(T.cmimi*T.sasia) AS DECIMAL(18,0)),0) AS TotaliSot FROM (SELECT DISTINCT fdr.ARTIKULL AS artikull, cast(fdr.CMIMISHITJES AS DECIMAL(18,0))AS cmimi, CAST(SUM(fdr.SASIA) AS DECIMAL(18,0)) AS sasia
                                        FROM fatured fd, faturedrel fdr,  (SELECT b.CODE AS BUSINESSID,dv.KUNDERID AS KUNDERID ,dv.STATUS AS STATUS FROM businesses b INNER JOIN devices dv ON b.BUSINESSID=dv.BUSINESSID WHERE b.STATUS=1) d
                                        WHERE fd.FATUREDID = fdr.FATUREDID AND fd.BUSINESSID=d.BUSINESSID
                                        AND fd.INSERT_DATE
                                        BETWEEN ? AND ?
                                        AND fd.USERID =?
                                        AND fd.IS_HYRJE_DALJE=0
                                        AND fd.STATUS=1
                                        AND d.KUNDERID=?
                                        AND fd.CID=?
                                        AND fdr.CID=?
                                        AND d.STATUS=1
                                        GROUP BY fdr.ARTIKULL, fdr.CMIMISHITJES ORDER BY sasia DESC) AS T");

                                        mysqli_stmt_bind_param($sqlTotaliArtikujSot,'ssssss',date('Y-m-d H:i:s',strtotime($_SESSION['date1'])),date('Y-m-d H:i:s',strtotime($_SESSION['date2'])),$perdoruesi,$_COOKIE['KUNDERID'],$_SESSION['CID'],$_SESSION['CID']);

                                    }
                                    if(mysqli_stmt_execute($sqlTotaliArtikujSot)){
                                        $rs=mysqli_stmt_get_result($sqlTotaliArtikujSot);
                                        while($result2=mysqli_fetch_assoc($rs)){
                                            $totArtikullSot=number_format($result2["TotaliSot"],0) ;
                                        }
                                    }
                                    mysqli_stmt_close($sqlTotaliArtikujSot);
                                }catch (Exception $e){
                                    echo'<td>"DETAJET E ARTUKJVE NUK U NGARKUAN ME SUKSES"</td>';
                                    echo'<td>'.$e->getMessage().'</td>';
                                }
                            }
                            ?>
                            <td></td>
                            <td ><?php if($perdoruesi != 'SoftExpres'){echo"$totArtikullSot";} ?> LEK</td>
                        </tr>
                    </table>
                </div>
                <br/><br/><br/>
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