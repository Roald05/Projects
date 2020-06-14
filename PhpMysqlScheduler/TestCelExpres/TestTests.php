<?php
/*$timeArray=array(
                array('08:00','09:00','07:00','06:25'),
                array(   1,   2,   7,   3)
            );

$floatTimeArray=array();
for($i=0;$i<count($timeArray);$i++){
    $hourMinuteToken=explode(":",$timeArray[0][$i]);
    $floatTimeArray[]=(float)$hourMinuteToken[0].".".$hourMinuteToken[1];
}
if(count($floatTimeArray)==count($timeArray)){
    for($i=0;$i<count($timeArray);$i++){
        $timeArray[0][$i]=$floatTimeArray[$i];
    }
}
//array_multisort( array_row($timeArray, 0), SORT_ASC, $timeArray );
//sort($floatTimeArray);


$timeArray=flip_row_col_array($timeArray);
array_multisort( array_column($timeArray, 0), SORT_ASC, $timeArray );*/




/*$timeArray=array("2019-09-25 09:00","2019-09-23 10:00","2019-09-26 15:00");
$e="2019-09-23 08:00";
function findClosestDate($expectedDate,$dates)
{

    $differenceInMinutes = null;
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

    return array(
        "closest" => $dates[$returnIndex],
        "difference" => $differenceInMinutes
    ) ;
}
echo '<pre>'; print_r(findClosestDate($e,$timeArray)); echo '</pre>';


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

    echo '<pre>'; print_r($arr); echo '</pre>';
    //return $arr;
}

generateRandom(0,100)*/



?>
