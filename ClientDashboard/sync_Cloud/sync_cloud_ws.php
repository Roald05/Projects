<?php
include "dbconn/dbconn.php";
$answer="";
if
( $_SERVER["REQUEST_METHOD"] == "POST" )
{

    $postText = file_get_contents('php://input');
    //echo " Connected successfully ";

}
else
{
    exit(" Serveri nuk eshte lidhur ");
}
//$clean_xml = str_ireplace(['soap-env:', 'SOAP:'], '', $postText);
//$clean_xml = str_replace("\n", "", $clean_xml);
//$clean_xml = str_replace("\r", "", $clean_xml);
//$clean_xml = preg_replace("/\r. |\n/", "", $clean_xml);
try{
    $xml_data=simplexml_load_string($postText);
}catch (Exception $e){
    $answer=$answer." Failed loading XML: ".$e->getMessage();
}
if ($xml_data === false)
{
    $answer=$answer." Failed loading XML: ";
    echo ".$answer.";
    foreach(libxml_get_errors() as $error)
    {
        echo "<br>", $error->message;
    }
    insertLog($conn,"F",0,"",$answer,$postText);
}
else {
    mysqli_autocommit($conn, false);
    $flag = true;
    $fatureArr=array();

    //$escapedString = $conn->real_escape_string($xml_data);
    if (isset($xml_data->Seksione)) {

        foreach($xml_data->Seksione->children() as $Seksion) {

            $sync_cloud_key = (string)$Seksion->SYNC_CLOUD_KEY;
            $SeksionId = (string)$Seksion->SeksionId;
            $TavolinaId = (string)$Seksion->TavolinaId;
            $TavolinaEmertim = (string)$Seksion->TavolinaEmertim;
            $terminalid = (string)$Seksion->terminalid;
            $UserId = (string)$Seksion->UserId;
            $hapja = date ("Y-m-d H:i:s", strtotime($Seksion->hapja));
            $mbyllja = date ("Y-m-d H:i:s", strtotime($Seksion->mbyllja));
            $status = (string)$Seksion->status;
            $BUSINESSID=(string)$Seksion->BUSINESSID;
            $CID=(string)$Seksion->CID;

            $query = mysqli_query($conn, "SELECT * FROM seksion WHERE SEKSIONID='".$SeksionId."' AND BUSINESSID='".$BUSINESSID."' AND CID='".$CID."'");
            $rekordIPerseritur=mysqli_num_rows($query);

            if($rekordIPerseritur<1) {

                $sql = "INSERT INTO seksion (`SEKSIONID`, `TERMINALID`, `TAVOLINA`, `TAVOLINA_EMERTIM`, `HAPJA`,`MBYLLJA`,
                                        `STATUS`, `USERID`,`BUSINESSID`,`CID`)
                                        VALUES (?,?,?,?,?,?,?,?,?,?)";

                $sqlQuery = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($sqlQuery, 'ssssssssss', $SeksionId, $terminalid, $TavolinaId, $TavolinaEmertim, $hapja, $mbyllja, $status, $UserId, $BUSINESSID, $CID);

                if (mysqli_stmt_execute($sqlQuery)) {
                    $answer=$answer." "." Rekordi ne Seksion u krijua me sukses \n";
                } else {
                    $flag = false;
                    $answer=$answer." "."Error Seksion (insert): " . $SeksionId . "\n" . $conn->error;
                }
                mysqli_stmt_close($sqlQuery);
            }
            else
            {
                //update seksion
                $sql = "UPDATE seksion SET USERID=?,STATUS=?,TERMINALID=?,HAPJA=?,MBYLLJA=? WHERE SEKSIONID=? AND BUSINESSID=? AND CID=?";

                $sqlUpdateSeksion = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($sqlUpdateSeksion, 'ssssssss', $UserId, $status, $terminalid, $hapja, $mbyllja, $SeksionId,$BUSINESSID,$CID);

                if (mysqli_stmt_execute($sqlUpdateSeksion)) {
                    $answer=$answer." "." Rekordi ne Seksion u modifikua me sukses \n";
                } else {
                    $flag = false;
                    $answer=$answer." "."Error Seksion (update): " . $SeksionId . "\n" . $conn->error;
                }
                mysqli_stmt_close($sqlUpdateSeksion);
            }
        }
    }


    if (isset($xml_data->FatureD)) {

        $sync_cloud_key = (string)$xml_data->FatureD->SYNC_CLOUD_KEY;
        $FatureDId = (string)$xml_data->FatureD->FatureDId;
        $terminalid = (string)$xml_data->FatureD->terminalid;
        $kod_fature = (string)$xml_data->FatureD->kod_fature;
        $date_fat = date ("Y-m-d", strtotime($xml_data->FatureD->date_fat));
        $vlefta = (string)$xml_data->FatureD->vlefta;
        $vleftaTvsh = (string)$xml_data->FatureD->vleftaTvsh;
        $tvsh = (string)$xml_data->FatureD->tvsh;
        $skonto = (string)$xml_data->FatureD->skonto;
        $Klienti = (string)$xml_data->FatureD->Klienti;
        $nr_serial = (string)$xml_data->FatureD->nr_serial;
        $Magazina = (string)$xml_data->FatureD->Magazina;
        $monedha = (string)$xml_data->FatureD->monedha;
        $tip_fature = (string)$xml_data->FatureD->tip_fature;
        $is_hyrje_dalje = (string)$xml_data->FatureD->is_hyrje_dalje;
        $insert_date = date ("Y-m-d H:i:s", strtotime($xml_data->FatureD->insert_date));
        $data_regj =  date ("Y-m-d H:i:s", strtotime($xml_data->FatureD->data_regj));
        $SeksionId_FatureD = (string)$xml_data->FatureD->SeksionId;
        $userid = (string)$xml_data->FatureD->userid;
        $Status = strtolower((string)$xml_data->FatureD->Status) === 'true'? true: false; ;
        $BUSINESSID=(string)$xml_data->FatureD->BUSINESSID;
        $CID=(string)$xml_data->FatureD->CID;

        //roald 13.04.2020
        $deleteFdrelQuery=mysqli_prepare( $conn,"delete from faturedrel where FATUREDID=(select FATUREDID from fatured WHERE KOD_FATURE=? and BUSINESSID=? and CID=?) and BUSINESSID=? and CID=?");
        mysqli_stmt_bind_param($deleteFdrelQuery,'sssss', $kod_fature,$BUSINESSID,$CID,$BUSINESSID,$CID);

        if(mysqli_stmt_execute($deleteFdrelQuery)){
            $answer=$answer." "." Rekordi ne FatureDRel u fshi me sukses \n";
        }else {
            $flag = false;
            $answer=$answer." "." Error FatureDRel (delete): " . $FatureDId . "\n" . $conn->error;
        }

        $deleteFdQuery=mysqli_prepare( $conn,"delete from fatured where KOD_FATURE=? and BUSINESSID=? and CID=?");
        mysqli_stmt_bind_param($deleteFdQuery,'sss', $kod_fature,$BUSINESSID,$CID);

        if(mysqli_stmt_execute($deleteFdQuery)){
            $answer=$answer." "." Rekordi ne FatureD u fshi me sukses \n";
        }else {
            $flag = false;
            $answer=$answer." "." Error FatureDRel (delete): " . $FatureDId . "\n" . $conn->error;
        }


        $sql1 = "Insert into fatured (`FATUREDID`, `TERMINALID`, `KOD_FATURE`,`DATE_FAT`,`VLEFTA`, `VLEFTATVSH`,
                                     `TVSH`, `SKONTO`,`KLIENTI`,`NR_SERIAL`, `MAGAZINA`, `MONEDHA`, `TIP_FATURE`,`IS_HYRJE_DALJE`,`INSERT_DATE`,
                                     `DATA_REGJ`, `SEKSIONID`, `USERID`, `STATUS`, `BUSINESSID`, `CID`)
                                      values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $sqlQuery=mysqli_prepare( $conn,$sql1 );

        mysqli_stmt_bind_param($sqlQuery,'sssssssssssssssssssss', $FatureDId, $terminalid, $kod_fature, $date_fat, $vlefta,
            $vleftaTvsh, $tvsh, $skonto, $Klienti, $nr_serial, $Magazina, $monedha, $tip_fature, $is_hyrje_dalje, $insert_date,
            $data_regj, $SeksionId_FatureD, $userid, $Status, $BUSINESSID, $CID);


        if (mysqli_stmt_execute($sqlQuery)){
            $answer=$answer." "." Rekordi ne FatureD u krijua me sukses \n";
        }else {
            $flag = false;
            $answer=$answer." "." Error FatureD (insert): " . $FatureDId . "\n" . $conn->error;
        }

        mysqli_stmt_close($sqlQuery);

    }else{
        $answer=$answer." "." Failed loading FatureD: ";
    }



    if (isset($xml_data->FatureD->FatureDRels)) {

        foreach($xml_data->FatureD->FatureDRels->children() as $FatureDRel) {

            $FatureDRelId = (string)$FatureDRel->FatureDRelId;
            $terminalid_Drel = (string)$FatureDRel->terminalid;
            $FatureDId_Drel = (string)$FatureDRel->FatureDId;
            $Sasia = (string)$FatureDRel->Sasia;
            $Skonto_Drel = (string)$FatureDRel->Skonto;
            $cmimi =(string)$FatureDRel->cmimi;
            $cmimiShitjes = (string)$FatureDRel->cmimiShitjes;
            $cmimShitjesPaTvsh = (string)$FatureDRel->cmimShitjesPaTvsh;
            $cmimShitjestvsh = (string)$FatureDRel->cmimShitjestvsh;
            $tvsh_Drel =(string)$FatureDRel->tvsh;
            $Artikull = (string)$FatureDRel->Artikull;

            $sql2 = "Insert into faturedrel (`FATUREDRELID`, `TERMINALID`, `FATUREDID`,`SASIA`,`SKONTO`, `CMIMI`,
                                             `CMIMISHITJES`, `CMIMSHITJESPATVSH`,`CMIMSHITJESTVSH`,`TVSH`, `ARTIKULL`,`BUSINESSID`,`CID`)
                                         values (?,?,?,?,?,?,?,?,?,?,?,?,?)";

            $sqlQuery=mysqli_prepare( $conn,$sql2 );
            mysqli_stmt_bind_param($sqlQuery,'sssdsssssssss', $FatureDRelId, $terminalid_Drel, $FatureDId_Drel, $Sasia, $Skonto_Drel, $cmimi, $cmimiShitjes,
                $cmimShitjesPaTvsh, $cmimShitjestvsh, $tvsh_Drel, $Artikull,$BUSINESSID,$CID);

            if (!mysqli_stmt_execute($sqlQuery)){
                $flag = false;
                $answer=$answer." "." Error FatureDRel (insert): " . $FatureDRelId . "\n" . $conn->error;
            }
            mysqli_stmt_close($sqlQuery);

            if(!$flag){
                break;
            }
        }
        $answer=$answer.""."Rekordi ne FatureDRel u krijua me sukses\n";
    }else{
        $answer=$answer.""." Failed loading FatureDRels: \n";
    }

    if ($flag==true){
        if($sync_cloud_key != 'ROALD'){
            mysqli_rollback($conn);
            echo'2';

        }else{
          $answer="";
            echo '1';
            mysqli_commit($conn);
        }
    }else{

        mysqli_rollback($conn);
        $answer="".$answer."All queries were rolled back\n";

        if ($FatureDId == ""){
            $transactionid=$SeksionId;
            $type="S";
        }else{
            $transactionid=$FatureDId;
            $type="F";
        }
        echo '0'.''.$transactionid.'';
        mysqli_close($conn);

        insertLog($conn,$type,$transactionid,$CID,$answer,$postText);

    }
}

function insertLog($conn,$type,$transaction_id,$cid,$respond_msg,$request_xml){
    include "dbconn/dbconn.php";

    $errDate=date("Y-m-d H:i:s");


    $sqLog=mysqli_prepare($conn, "Insert into log(TYPE,TRANSACTION_ID,CID,STATUS,RESPOND_MSG,REQUEST_XML,REG_DATE) VALUES(?,?,?,'F',?,?,?)");

    mysqli_stmt_bind_param($sqLog,'ssssss',$type,$transaction_id,$cid,$respond_msg,$request_xml,$errDate);
    mysqli_stmt_execute($sqLog);
}
?>