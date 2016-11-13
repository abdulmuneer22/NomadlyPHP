<?php
header('Content-type: application/json');
require_once 'vendor/autoload.php';
#Get Data From Nomadlist

$OriginCity = $_GET['OriginCity'];
$Budget = $_GET['Budget'];
$Climate = $_GET['Climate'];
$Internet = $_GET['Internet'];
$Safe = $_GET['Safe'];
$Nightlife = $_GET['Nightlife'];
$Region = $_GET['Region'];
$data_decoded = null;
//Have All the parameters , now call nomdalist
$curl = curl_init();

function findCityNamesFromSkyScanner($citycode){
header('Content-type: application/json');
$headers = array('Accept' => 'application/json');
$url = "http://partners.api.skyscanner.net/apiservices/autosuggest/v1.0/UK/GBP/en?query=".$citycode."&apikey=prtl6749387986743898559646983194";
//echo $url;
$response = Unirest\Request::get($url);
//$data = json_decode($response);
return($response->raw_body);
}



function getThePrice($url,$key){
  $pollurl = $url.$key;
  print_r($pollurl);
  
  $response = Unirest\Request::get($pollurl);
  
  echo json_encode($response->body->Itineraries[0]->PricingOptions[0]->Price);
  
  //$Price = $response->body->Itineraries[0]->PricingOptions[0]->Price;
  //return $Price
  
  
  
  //$response = json_decode($response);
  //print_r($response->code);
  if($response->code = 200){
    //print_r($response->code);
    //print_r($response);
    echo json_encode($response->body->Itineraries[0]->PricingOptions[0]->Price);
  }else{
    print_r($response->code);
  }
  
  
  
}

function getPricefromSkyScanner($origin,$destination){
  $OriginCity = $origin;
  $DestinationCity = $destination;
  //echo $OriginCity.$DestinationCity;
  
  $getPriceSkyEndPoint = "http://partners.api.skyscanner.net/apiservices/pricing/v1.0";
  $headers = array('Accept' => 'application/json','Content-Type'=>'application/x-www-form-urlencoded');
  $body = Unirest\Request\Body::form($data);
  $data = array(
    'apiKey' => 'prtl6749387986743898559646983194',
    'country' => 'US',
    'currency' => 'USD',
    'locale' => 'en-US',
    'originplace' => $OriginCity,
    'destinationplace' => $DestinationCity,
    'outbounddate' => '2016-11-15',
    'adults' => '1'
    );
    
    $body = Unirest\Request\Body::form($data);
    $response = Unirest\Request::post($getPriceSkyEndPoint, $headers, $body);
    
    //print_r($response->headers["Location"]);
    $headerskyscanner = $response->headers["Location"];
    //echo($headerskyscanner);
    
    // Now Get the price
    getThePrice($headerskyscanner,"?apiKey=prtl6749387986743898559646983194");
}

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://nomadlist.com/api/v2/filter/city?c=5&f1_target=temperatureC&f1_type=lt&f1_max=20&f2_target=internet_speed&f2_type=gt&f2_min=15&f3_target=safety_level&f3_type=gt&f3_min=3&f4_target=air_quality&f4_type=lt&f4_max=50&f5_target=region&f5_type=em&f5_value=Europe&s=nomad_score&o=desc",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "postman-token: 0156a06f-4628-42e9-50a0-f6647a3ebbc1"
  ),
));


$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  $data_decoded = json_decode($response);
  
  
}

$nomadlistCityNames = array();
$sky_code = array();
//stored all skycode here ^^

for($i=0;$i<10;$i++){
    //echo $data_decoded->slugs[$i]."------";
   array_push($nomadlistCityNames,$data_decoded->slugs[$i]);
    $res = findCityNamesFromSkyScanner($data_decoded->slugs[$i]);
    $res =  json_decode($res);
    $skyplaceid = $res->Places[0]->PlaceId;
    $skycountryid = $res->Places[0]->CountryId;
    //echo json_encode($res);
    $push_array = array("PlaceID"=>$skyplaceid,"CountryId"=>$skycountryid);
    $city_code = array("CityCode" => $skyplaceid,"CountryCode" => $skycountryid);
    //$country_code = array("CountryCode" => $skycountryid);
    array_push($sky_code,$city_code);
    getPricefromSkyScanner("LHR-Sky",$skyplaceid);
    
}




//getPricefromSkyScanner("Bangalore","London");
//echo json_encode($sky_code)




echo json_encode($finalResult);

?>
