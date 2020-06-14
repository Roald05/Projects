<?php
include "dbconn/conn.php";
ini_set('max_execution_time', -1);
ini_set('memory_limit', '512M');


/*$startDate=date('Y-m-d',strtotime('2019-09-23'));
$finishDate=date('Y-m-d',strtotime('2019-09-30'));
$eventLength=30;
$startTime=date("H:i",strtotime('08:00'));
$finishTime=date("H:i", strtotime('08:00')+$eventLength*60);

$studentId=1;
$schedulerId=1;
$freeTimeSlotsArr=[];
$MAX_FITNESS=$eventLength;

RunScheduler($conn,$freeTimeSlotsArr,$startDate,$finishDate,$eventLength,$startTime,$finishTime,$studentId,$schedulerId);*/


function returnGenes($conn,$freeTimeSlotsArr,$startDate,$finishDate,$eventLength,$startTime,$finishTime,$studentId,$supervisorId,$markerId,$schedulerId){

    if($startDate<=$finishDate){
            $gene=array();

            $sqlQ = "
        SELECT FinishTime FROM studentevent
        WHERE StudentId=".$studentId."
        AND (StartTime  BETWEEN '".$startTime."' AND '".$finishTime."'
        or FinishTime  BETWEEN '".$startTime."' AND '".$finishTime."')
        AND EventDate = '".$startDate."'
        AND SchedulerId = ".$schedulerId."
        UNION ALL
        SELECT FinishTime FROM supervisorevent
        WHERE StudentId=".$studentId."
        AND (StartTime  BETWEEN '".$startTime."' AND '".$finishTime."'
        or FinishTime  BETWEEN '".$startTime."' AND '".$finishTime."')
        AND EventDate = '".$startDate."'
        AND SchedulerId = ".$schedulerId."
        UNION ALL
        SELECT FinishTime FROM markerevent
        WHERE StudentId=".$studentId."
        AND (StartTime  BETWEEN '".$startTime."' AND '".$finishTime."'
        or FinishTime  BETWEEN '".$startTime."' AND '".$finishTime."')
        AND EventDate = '".$startDate."'
        AND SchedulerId = ".$schedulerId."
        GROUP BY EventDate
        ORDER BY FinishTime DESC LIMIT 1";

            $result = $conn->query($sqlQ);
            if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    //echo''.$row['FinishTime'].'';
                    $lastTime=$row['FinishTime'];
                    $finishTime=$lastTime;
                }

            }else{

                $gene[0]=$startDate;
                $gene[1]=$finishDate;
                $gene[2]=$eventLength;
                $gene[3]=$startTime;
                $gene[4]=$finishTime;
                $gene[5]=$studentId;
                $gene[6]=$supervisorId;
                $gene[7]=$markerId;
                $gene[8]=$schedulerId;

                $freeTimeSlotsArr[]=$gene;
            }

            if($startTime<date("H:i",strtotime('18:00'))){

                //$startDate=date('Y-m-d',strtotime($startDate.'+1 day'));
                $startTime=date("H:i",strtotime($finishTime)+1800);
                $finishTime=date("H:i", strtotime($startTime)+$eventLength*60);
                //$studentId=$studentId+1;
                return returnGenes($conn,$freeTimeSlotsArr,$startDate,$finishDate,$eventLength,$startTime,$finishTime,$studentId,$supervisorId,$markerId,$schedulerId);
            }else{
                $startDate=date('Y-m-d',strtotime($startDate.'+1 day'));
                $startTime=date("H:i",strtotime('08:00'));
                $finishTime=date("H:i", strtotime('08:00')+$eventLength*60);

                return returnGenes($conn,$freeTimeSlotsArr,$startDate,$finishDate,$eventLength,$startTime,$finishTime,$studentId,$supervisorId,$markerId,$schedulerId);
            }

    }else{

        //echo '<pre>'; print_r($freeTimeSlotsArr); echo '</pre>';
        return $freeTimeSlotsArr;
    }
}

function generateRandom($a,$b){
    $rand=0;
    $arr=array();
   while(count($arr)<=$b){
       $rand=mt_rand($a,$b);
       if(in_array($rand,$arr,true)){
           continue;
       }else{
           $arr[]=$rand;
       }
   }

    //echo '<pre>'; print_r($arr); echo '</pre>';
   return $arr;
}

function findLowestAnd2ndLowest($array){
    //$array = array('200', '15','69','122','50','201');
    $max_1 = $max_2 = PHP_INT_MAX;

    for ($i=0; $i<count($array); $i++) {
        if ($array[$i] <= $max_1) {
            $max_2 = $max_1;
            $max_1 = $array[$i];
        } else if ($array[$i] < $max_2 && $array[$i] != $max_1) {
            $max_2 = $array[$i];
        }
    }

    return array($max_1,$max_2);
}

function generateChromosome($genes,$sIds){
    //creation g chromosome...fills the chromosome with random genes
    $chrm=array();

    $randIndexes=generateRandom(0,min($sIds)-1);
    //echo '<pre>'; print_r($genes); echo '</pre>';

    for($i=0;$i<count($genes);$i++){
        $chrm[$i]=$genes[$i][$randIndexes[$i]];
    }

    /*$isValid=true;

   for($i=0;$i<count($sIds);$i++){
       if(!array_search($sIds[$i],$chrm[$i][5])){
           $isValid=false;
       }
   }

   if($isValid){
       return $chrm;
   }else{
       return generateChromosome($genes,$sIds);
   }*/

    return $chrm;
}

function generatePopulation($size,$genes,$nrOfGenes){
    //creation of population...fills the population with individuals(chromosomes) whith random genes
    for($i=0;$i<$size;$i++){
        $population[$i]=generateChromosome($genes,$nrOfGenes);

    }
    return $population;
}

function getFitestIndividuals($population){


    for($i=0;$i<count($population);$i++){//loop through the individuals of the population
        $timeArray=array();
        $individualFitnesses = array();

        for($j=0;$j<count($population[$i]);$j++){
            $eventDate=$population[$i][$j][0];//loop through the genes of the indivual
            $startTime=$population[$i][$j][3];//store the startime of a slot(gene) in a variable

            $timeArray[]=$eventDate." ".$startTime;

        }
        $closestDate=array();
        for($m=0;$m<count($timeArray)-1;$m++){
            $timeArr=$timeArray;
            // echo '<pre>'; print_r($timeArr); echo '</pre>';

            array_splice($timeArr, $m, 1);//time Array but without the element we are going to put on the function below
            $closestDate[]=findClosestDate($timeArray[$m],$timeArr);//get minutes beetwen two consecutive events

        }
        if(count($closestDate)!== 0 ){
            $individualFitnesses[]=array_sum($closestDate)/count($closestDate);
        }

    }
    //$individualFitnesses[]=70;
    $minIndividualFitnesses=array();
    if (!empty($individualFitnesses)){
        $minIndividualFitnesses=min($individualFitnesses);
    }

    $fitesstIndividuals=array(array_search($minIndividualFitnesses,$individualFitnesses),
        array_search(findLowestAnd2ndLowest($individualFitnesses)[1],$individualFitnesses));

    return array($fitesstIndividuals,$minIndividualFitnesses,findLowestAnd2ndLowest($individualFitnesses)[1]) ;
}

function crossover($fitesstIndividuals,$population){

    $firstPoint=mt_rand(1,count($population[$fitesstIndividuals[0]])-1);
    $secondPoint=mt_rand(1,count($population[$fitesstIndividuals[0]])-1);

    //childs creation
    $firstArrPartOne=array_slice($population[$fitesstIndividuals[0]],0,$firstPoint+1);
    $firstArrPartTwo=array_slice($population[$fitesstIndividuals[0]],$firstPoint+1);

    $secondArrPartOne=array_slice($population[$fitesstIndividuals[1]],0,$firstPoint+1);
    $secondArrPartTwo=array_slice($population[$fitesstIndividuals[1]],$firstPoint+1);

    $child1=array_merge($firstArrPartOne,$secondArrPartTwo);
    $child2=array_merge($firstArrPartTwo,$secondArrPartOne);
    $child3=array_merge($secondArrPartTwo,$firstArrPartOne);
    $child4=array_merge($secondArrPartOne,$firstArrPartTwo);

    return array($child1,$child2,$child3,$child4);

}

function mutation($population){
    //swap mutation
    foreach ($population as $individual){

        $randPos1=mt_rand(0,count($individual)-1);
        $randPos2=mt_rand(0,count($individual)-1);

        if($randPos1!==$randPos2){
            $temp = $individual[$randPos1];
            $individual[$randPos1] = $individual[$randPos2];
            $individual[$randPos2] = $temp;
        }else{
            $temp = $individual[0];
            $individual[0] = $individual[count($individual)-1];
            $individual[count($individual)-1] = $temp;
        }
    }

    return $population;
}

function findClosestDate($expectedDate,$dates)
{

    $differenceInMinutes = null;
    $actualDate=$expectedDate;
    $expectedDate = new DateTime($expectedDate);
    $expectedDateEpoch = $expectedDate->getTimestamp();
    $returnIndex = -1;

    for($i = 0; $i<count($dates); $i++)
    {
        $dateObject = new DateTime($dates[$i]);
        $dateEpoch = $dateObject->getTimestamp();
        $difference = abs($expectedDateEpoch-$dateEpoch);
        $difference = $difference/60;
        if($differenceInMinutes === null || $difference < $differenceInMinutes)
        {
            $differenceInMinutes = $difference;
            $returnIndex = $i;
        }
    }

    /*return array(
        "actualDate" => $actualDate,
        "closest" => $dates[$returnIndex],
        "difference" => $differenceInMinutes
    ) ;*/
    return $differenceInMinutes;
}
?>
