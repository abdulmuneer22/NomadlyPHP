<?php
$Data = file_get_contents('Videos.json');
$Data = json_decode($Data);
$DestinationName = "chiang-mai-thailand";
$limit = sizeof($Data);

$videolink = "https://youtu.be/FS8MxjrEZL8";


 for($i=0 ; $i < $limit ; $i++){
    
     
    if($Data[$i]->CityName == $DestinationName){
            $videolink = $Data[$i]->VideoLink;
            $cityname = $Data[$i]->CityName;
        }
}

echo $videolink . "\n" . $cityname;

?>