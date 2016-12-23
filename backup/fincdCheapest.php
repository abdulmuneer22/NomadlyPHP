<?php
require_once 'Unirest-php/src/Unirest.php';

function getUrlParams(){
    //rquired params
    //1 - Origin City
    //2 - adventure => [Outdoors,Snow,Beach,Nightlife,Fun,DigitalNomadling]
    //3 - Preferance = [Safe,Friendly,Outside Schengen, Good English , Female Friendly,Peaceful ]
    //4 - Budget [500,1000,2000]
    // time to visit => [Jan,Feb...etc]
    // Optinal Calculate Price and show cheapest month
    //http://localhost/server/home_nofilter.php?OriginCity=Bangalore&Climate=Snow&Preferances=Friendly
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


function getNomadCities($Url){
    $myfile = fopen("NomadCities.txt", "a");
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
    $myfile = fopen("SkyCode.txt", "a");
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
            "CountryCode" => $response->body->Places[0]->CountryId
        );
        
            
        array_push($skycode,$Code);
        }
        
        
        
         
    }
    
    fclose($myfile);
    return $skycode;
}

function GetSKYCODEOrigin(){
    $headers = array('Accept' => 'application/json');
    $cityname = $_GET["OriginCity"];
    $url = "http://partners.api.skyscanner.net/apiservices/autosuggest/v1.0/UK/GBP/en?query=".$cityname."&apikey=prtl6749387986743898559646983194";
    $response = Unirest\Request::get($url);
    
    $OriginCitySkyCode = $response->body->Places[0]->PlaceId;
    
    return $OriginCitySkyCode;
}

function findCheapestMonth($OriginCitySkyCode,$DestinationSkyCodes){
    header('Content-type: application/json');
    
    //requires - Origin ,Destinations , returns cheapst month for that set of origin and destination 
    //print_r($OriginCitySkyCode."\n");
    
    
    $arraySize = sizeof($DestinationSkyCodes);
    if($arraySize < 10){
        $arraySize = sizeof($DestinationSkyCodes);
    }else{
        $arraySize = 10;
    }
    
    //print_r($arraySize);
    
    for ($i=0 ; $i< $arraySize ; $i++){
        $DestinatioCode = $DestinationSkyCodes[$i]["CityCode"];
        $cheapestmonth = "http://partners.api.skyscanner.net/apiservices/browsedates/v1.0/US/USD/en-US/".$OriginCitySkyCode."/".$DestinatioCode."/anytime/anytime?apiKey=prtl6749387986743898559646983194";
        $response = Unirest\Request::get($cheapestmonth); 
        $response = $response->body->Dates->OutboundDates;
        $responseSize = sizeof($response);
        //print_r($response);
        //print_r($responseSize);
        $cheapest = $response[0]->Price;
        
            for($i=0;$i<3;$i++){
                if($cheapest > $response[$i]->Price){
                        $cheapest = $response[$i]->Price;
                        $cheapest_array = $response[$i];
                        
                }
            
            
                
                
            }
            
            print($DestinationSkyCodes[$i]["CityCode"] ."=>".$cheapest."\n");
    }
}


//$OriginCitySkyCode = GetSKYCODEOrigin();
$OriginCitySkyCode = "LOND-Sky";
//$Url = getUrlParams();
$Url  = "https://nomadlist.com/api/v2/filter/city?c=2&f1_target=nightlife&f1_type=gt&f1_min=3&f2_target=schengen&f2_type=em&f2_value=no&s=nomad_score&o=desc";
$NomadlistCities = getNomadCities($Url);
$DestinationSkyCodes = getSKYCODE($NomadlistCities);

findCheapestMonth($OriginCitySkyCode,$DestinationSkyCodes);


?>
