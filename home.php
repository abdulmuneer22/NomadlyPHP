<?php
require_once 'Unirest-php/src/Unirest.php';





function DateToVisit(){
    
    switch($_GET["Month"]){
    
    case 'NOV -2016':
        $DateToVisit = "2016-11-27";
        return $DateToVisit;
        
        
    case 'DEC -2016':
        $DateToVisit = "2016-12-27";
        return $DateToVisit;
        
    case 'JAN -2017':
        $DateToVisit = "2017-01-27";
        return $DateToVisit;
        
    case 'FEB -2017':
        $DateToVisit = "2017-02-27";
        return $DateToVisit;
    
    case 'MAR -2017':
        $DateToVisit = "2017-03-27";
        return $DateToVisit;
        
    case 'APR -2017':
        $DateToVisit = "2017-04-27";
        return $DateToVisit;
        
    case 'MAY -2017':
        $DateToVisit = "2017-05-27";
        return $DateToVisit;
    
    
    case 'MAY -2017':
        $DateToVisit = "2017-05-27";
        return $DateToVisit;
        
    case 'JUN -2017':
        $DateToVisit = "2017-06-27";
        return $DateToVisit;
        
    case 'JUL -2017':
        $DateToVisit = "2017-07-27";
        return $DateToVisit;
    
    default :
        $DateToVisit = "2016-12-27";
        return $DateToVisit;
}
}

function GetSKYCODEOrigin(){
    $headers = array('Accept' => 'application/json');
    $cityname = $_GET["OriginCity"];
    $url = "http://partners.api.skyscanner.net/apiservices/autosuggest/v1.0/UK/GBP/en?query=".$cityname."&apikey=prtl6749387986743898559646983194";
    $response = Unirest\Request::get($url);
    
    $CityCode = $response->body->Places[0]->PlaceId;
    
    return $CityCode;
}

function getUrlParams(){
    //required params
    //1 - Origin City
    //2 - adventure => [Outdoors,Snow,Beach,Nightlife,Fun,DigitalNomadling]
    //3 - Preferance = [Safe,Friendly,Outside Schengen, Good English , Female Friendly,Peaceful ]
    //4 - Budget [500,1000,2000]
    // time to visit => [Jan,Feb...etc]
    // Optinal Calculate Price and show cheapest month
    //http://localhost/server/home_nofilter.php?OriginCity=Bangalore&Climate=Snow&Preferances=Friendly
    
    
    //TO_DO
    // Check For Skip Option
    $url = null;
    $filtercount = 0;
    $baseurl = "https://nomadlist.com/api/v2/filter/city?c=";
    $tail = "&s=nomad_score&o=desc";
    
    $Climate = $_GET["Climate"];
    $filtercount = 0;
    
    switch($Climate){
        
        case '🏕 Outdoors':
        $filtercount = $filtercount + 1;
        $filterOne = "&f".$filtercount."_target=tags&f".$filtercount."_type=pm&f".$filtercount."_value=outdoors";
        break;
        
        case '❄ Snow':
        $filtercount = $filtercount + 1;        
        $filterOne = "&f".$filtercount."_target=tags&f".$filtercount."_type=pm&f".$filtercount."_value=snow";
        break;

        case '🏄 Beach':
        $filtercount = $filtercount + 1;        
        $filterOne = "&f".$filtercount."_target=tags&f".$filtercount."_type=pm&f".$filtercount."_value=beach";
        break;

        case '😎 Nightlife':
        $filtercount = $filtercount + 1;        
        $filterOne = "&f".$filtercount."_target=nightlife&f".$filtercount."_type=gt&f".$filtercount."_min=3";
        break;

        case '🎉 Fun':
        $filtercount = $filtercount + 1;
        $filterOne = "&f".$filtercount."_target=leisure_quality&f".$filtercount."_type=gt&f".$filtercount."_min=3";
        
        break;

        case '💻 Digital Nomading':
        
        $filtercount = $filtercount + 1;
        $filterOne = "&f".$filtercount."_target=internet_speed&f".$filtercount."_type=gt&f".$filtercount."_min=15";
        break;
        
        default :
        $filterOne = "";
        
        
    }
    
    $Preferances = $_GET['Preferances'];
    
    switch($Preferances){
        case '👮 Safe':
        $filtercount = $filtercount + 1;        
        $filterTwo = "&f".$filtercount."_target=safety_level&f".$filtercount."_type=gt&f".$filtercount."_min=3";
        break;

        case '😁 Friendly':
        $filtercount = $filtercount + 1;        
        $filterTwo = "&f".$filtercount."_target=friendliness_to_foreigners&f".$filtercount."_type=gt&f".$filtercount."_min=3";
        break;

        case '🌏 Outside Schengen':
        $filtercount = $filtercount + 1;        
        $filterTwo = "&f".$filtercount."_target=schengen&f".$filtercount."_type=em&f".$filtercount."_value=no";
        break;

        case '📢 Good English':
        $filtercount = $filtercount + 1;        
        $filterTwo = "&f".$filtercount."_target=english_speaking&f".$filtercount."_type=gt&f".$filtercount."_min=3";
        break;

        case '💃 Female Friendly':
        $filtercount = $filtercount + 1;        
        $filterTwo = "&f".$filtercount."_target=female_friendly&f".$filtercount."_type=gt&f".$filtercount."_min=3";
        break;

        case '🌏 Outside Schengen':
        $filtercount = $filtercount + 1;        
        $filterTwo = "&f".$filtercount."_target=schengen&f".$filtercount."_type=em&f".$filtercount."_value=no";
        break;

        case '☮ Peaceful':
        $filtercount = $filtercount + 1;        
        $filterTwo = "&f".$filtercount."_target=fragile_states_index&f".$filtercount."_type=lt&f".$filtercount."_max=60";
        break;

        default:
        $filterTwo = "";  
    }
    
    

    
    
    $url = $baseurl.$filtercount.$filterOne.$filterTwo.$tail;
    
    return $url;

}

function getBudget(){
    $Budget = $_GET["Budget"];
    
    switch($Budget){
        case '💵':
            $Budget = 200;
            break;
        case '💰💰':
            $Budget = 500;
            break;
        
        case '💸💸💸':
            $Budget = 1000;
            break;
        
        default:
            $Budget = 1000;
    }
    
    return $Budget;
    
    
}

function getNomadCities($Url){
    $myfile = fopen("./Logs/NomadCities.txt", "w");
    fwrite($myfile,"Started The Function getNomadCities");
    
    $headers = array('Accept' => 'application/json');
    $response = Unirest\Request::post($Url, $headers);
    // return first 15 results
    $nomadlist = array ();
    for ($i=0;$i < 15 ;$i++){
    fwrite($myfile,$response->body->slugs[$i]."\n");
    array_push($nomadlist,$response->body->slugs[$i]);
    }

    
    fclose($myfile);
    return $nomadlist;
}

function getSKYCODE($NomadlistCities){
    $headers = array('Accept' => 'application/json');
    $skycode = array();
    $myfile = fopen("./Logs/SkyCodes.txt", "w");
    fwrite($myfile,"Started The Function getSKYCODE\n");
    
    $arraySize =  sizeof($NomadlistCities);
    for($i = 0 ; $i < $arraySize ;$i++){
        
        // Get City Code
        $url = "http://partners.api.skyscanner.net/apiservices/autosuggest/v1.0/UK/GBP/en?query=".$NomadlistCities[$i]."&apikey=prtl6749387986743898559646983194";
        $response = Unirest\Request::get($url);
        
        //fwrite($myfile,"Inside Loop !!\n");
        $PlaceId = $response->body->Places[0]->PlaceId;
        $CountryId = $response->body->Places[0]->CountryId;
        
        
        if($PlaceId != null && $CountryId !=null){
        fwrite($myfile,"\nPlace-Name => ".$PlaceId."\t"."Country=>".$CountryId);    
        
        $Code = array(
            "nomadimageurl" => "https://nomadlist.com/assets/img/cities/".$NomadlistCities[$i]."-500px.jpg",
            "CityName" => $response->body->Places[0]->PlaceName,
            "CityCode" => $response->body->Places[0]->PlaceId,
            "CountryCode" => $response->body->Places[0]->CountryId,
            "DestinationNomadCode"=>$NomadlistCities[$i]
        );
        
            
        array_push($skycode,$Code);
        }
        
        
        
         
    }
    
    fclose($myfile);
    return $skycode;
}

function setPollingURL($skycode,$CityCode,$DateToVisit){
    //print_r($skycode[0][DestinationNomadCode]);
    header('Content-type: application/json');
    $PollingURLSkyEndPoint = "http://partners.api.skyscanner.net/apiservices/pricing/v1.0";
    $headers = array('Accept' => 'application/json');
    
    $myfile = fopen("./Logs/PollingURL.txt", "w");
    fwrite($myfile,"Started The Function setPollingURL\n");
    
    $PollingURLWithCityCodes = array();
    
    $limit = 0;
    
    if(sizeof($skycode) < 10){
        $limit = sizeof($skycode);
    }else{
        $limit = 10;
    }
    
    for($i=0 ; $i < $limit ; $i++){
    
    $data = array(
    'apiKey' => 'prtl6749387986743898559646983194',
    'country' => $skycode[$i]["CountryCode"],
    'currency' => 'USD',
    'locale' => 'en-US',
    'originplace' => $CityCode,
    'destinationplace' => $skycode[$i]["CityCode"],
    'outbounddate' => $DateToVisit,
    'adults' => '1'
    );
    
    $body = Unirest\Request\Body::form($data);
    
    $response = Unirest\Request::post($PollingURLSkyEndPoint, $headers, $body);    
    /*
    $string_data = serialize($response);
    fwrite($myfile,$string_data."\n");
    */
    
    fwrite($myfile,"\nDestinationName=>".$skycode[$i]["CityName"]);
    fwrite($myfile,"\nnomadimageurl=>".$skycode[$i]["nomadimageurl"]);
    fwrite($myfile,"\DestinationCityCode=>".$skycode[$i]["CityCode"]);
    fwrite($myfile,"\nDestinationCountryCode=>".$skycode[$i]["CountryCode"]);
    fwrite($myfile,"\nOriginCityCode=>".$CityCode);
    fwrite($myfile,"\nPollingURL=>".$response->headers["Location"]);
    fwrite($myfile,"\n*********************************************\n");
    
    $polling = array(
                "DestinationName" => $skycode[$i]["CityName"],
                "nomadimageurl" => $skycode[$i]["nomadimageurl"],
                "DestinationCityCode"=>$skycode[$i]["CityCode"],
                "DestinationCountryCode"=>$skycode[$i]["CountryCode"],
                "OriginCityCode" => $CityCode,
                "PollingURL"=>$response->headers["Location"],
                "DestinationNomadCode"=>$skycode[$i][DestinationNomadCode]
                
                );
    
    array_push($PollingURLWithCityCodes,$polling);
    



}

fclose($myfile);
return $PollingURLWithCityCodes;
}

function CalculatePrice($Budget,$PollingURLWithCityCodes){
    $apikey = "?apiKey=prtl6749387986743898559646983194";
    //print_r($PollingURLWithCityCodes);
    $j = 0;
    
    for($i=0; $i < sizeof($PollingURLWithCityCodes) ; $i++){
        if($PollingURLWithCityCodes[$i]["PollingURL"] != null){
        
        $imageurl = $PollingURLWithCityCodes[$i]["nomadimageurl"];
        $DestinationName = $PollingURLWithCityCodes[$i]["DestinationName"];
        $response = Unirest\Request::get($PollingURLWithCityCodes[$i]["PollingURL"].$apikey);
        //print_r($response->body->Itineraries[0]->PricingOptions[0]->Price);
        $price = ceil($response->body->Itineraries[0]->PricingOptions[0]->Price);
        $DeeplinkUrl = $response->body->Itineraries[0]->PricingOptions[0]->DeeplinkUrl;
        
        if($price != null && $price < $Budget && $DestinationName != null){
        $CalculatedPrice[$j] = array(
            'UrlToPOll'=>$PollingURLWithCityCodes[$i]["PollingURL"].$apikey,
            'imageurl'=>$imageurl,
            'DestinationName'=>$DestinationName,
            'Price'=>$price,
            'DeeplinkUrl'=>$DeeplinkUrl,
            'DestinationNomadCode'=>$PollingURLWithCityCodes[$i]["DestinationNomadCode"]
            
            );
        $j = $j + 1;
        }
        
        }
        
        
    }
    
    //print_r($CalculatedPrice);
    return $CalculatedPrice;
    
}

function getVideoURL($DestinationName){
        $Data = file_get_contents('Videos.json');
        $Data = json_decode($Data);
        
        $limit = sizeof($Data);
        
        $videolink = "not found";
        
        
        for($i=0 ; $i < $limit ; $i++){
        
        
        if($Data[$i]->CityName == $DestinationName){
        $videolink = $Data[$i]->VideoLink;
        $videolink = $videolink."&feature=youtu.be&t=12";
        $cityname = $Data[$i]->CityName;
        }
        }
        
        return $videolink;
}
function formatOutput($CalculatedPrice){
    
    //print_r($CalculatedPrice);
    header('Content-type: application/json');
    
    $data = array("messages"=>array(
        array(
        "attachment"=>array(
         "type"=>"template",
         "payload"=>array(
             "template_type" => "generic" , 
             "elements"=> array(
                 
                 array(
                    "title" => "Could Not Fetch Data",
                    "image_url" => "https://nomadlist.com/assets/img/cities/las-palmas-gran-canaria-spain-500px.jpg",
                    "subtitle" => "Could Not Fetch Data",
                    "buttons" => array(
                        array(
                            "type" => "web_url",
                            "url" => "https://skyscanner.com",
                            "title" => "Could Not Fetch Data"
                        ),
                        array(
                            "type" => "web_url",
                            "url" => "https://skyscanner.com",
                            "title" =>"Could Not Fetch Data"
                            )
                        )
                    )
                 
                 )
                 
                 )
        )
    )
        
        
        )
    );
    
    
    
    
    
    for($i=0;$i < sizeof($CalculatedPrice) ; $i++){
        $DestinationNomadCode = $CalculatedPrice[$i]["DestinationNomadCode"];
        $videolink = getVideoURL($DestinationNomadCode);
        //print_r($CalculatedPrice[$i]["DestinationName"]);
        if($CalculatedPrice[$i]["DestinationName"] != null){
           
           $data["messages"][0]["attachment"]["payload"]["elements"][$i] = array(
                    "title" => $CalculatedPrice[$i]["DestinationName"],
                    "image_url" => $CalculatedPrice[$i]["imageurl"],
                    "subtitle" => "A Great Place To Visit",
                    "buttons" => array(
                        array(
                            "type" => "web_url",
                            "url" => $CalculatedPrice[$i]["DeeplinkUrl"],
                            "title" => $CalculatedPrice[$i]["Price"]
                        ),
                        array(
                            "type" => "web_url",
                            "url" => $videolink,
                            "title" =>"Explore"
                            )
                        )
                    ); 
        
        
            
        }
            
            
        
        
    }
    
    
    $myfile = fopen("./Logs/finalOutput.json", "w");
    //fwrite($myfile,"calculated");
    fwrite($myfile,json_encode($data));
    fclose($myfile);
    
    echo json_encode($data);
}



$DateToVisit = DateToVisit();
//$DateToVisit = "2016-12-27";

$CityCode = GetSKYCODEOrigin();
//$CityCode = "MIAA-sky";

$Budget = getBudget();
//$Budget = "1000";

$Url = getUrlParams();


$myfile = fopen("testfile.txt", "w");
fwrite($myfile,"URL For Nomadly=>".$Url."\n"."Date To Visit=>".$DateToVisit."\n"."Origin=>".$CityCode."\n");
fwrite($myfile,"Budget=>".$Budget."\n");
fclose($myfile);

$Url = "https://nomadlist.com/api/v2/filter/city?c=2&f1_target=internet_speed&f1_type=gt&f1_min=15&f2_target=schengen&f2_type=em&f2_value=no&s=nomad_score&o=desc";
$NomadlistCities = getNomadCities($Url);


$skycode = getSKYCODE($NomadlistCities);

$PollingURLWithCityCodes = setPollingURL($skycode,$CityCode,$DateToVisit);

// Calculate Price and Format Output pending
$CalculatedPrice = CalculatePrice($Budget,$PollingURLWithCityCodes);

//Format Output
formatOutput($CalculatedPrice);



?>
