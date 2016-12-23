<?php
require_once 'Unirest-php/src/Unirest.php';
header('Content-type: application/json');

// Get cheapest
$cheapestmonth = "http://partners.api.skyscanner.net/apiservices/browsedates/v1.0/US/USD/en-US/LON/BUD-sky/anytime/anytime?apiKey=prtl6749387986743898559646983194";
$response = Unirest\Request::get($cheapestmonth); 


$response = $response->body->Dates->OutboundDates;
$cheapest = $response[0]->Price;
$heighest = $response[0]->Price;
for($i=0;$i<10;$i++){
    if($cheapest > $response[$i]->Price){
        $cheapest = $response[$i]->Price;
        $cheapest_array = $response[$i];
    }
    if($heighest < $response[$i]->Price){
        $heighest = $response[$i]->Price;
    }
}
print_r("Cheapest->".$cheapest);
print_r($cheapest_array);
print_r("heighest->".$heighest);
//print_r($response);


?>