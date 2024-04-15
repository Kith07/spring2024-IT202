<?php
function fetch_touristPlace($nationalLocID)
{
    require_once(__DIR__ . "/load_api_keys.php");
    require_once(__DIR__ . "/api_helper.php");
    $data = ["llocation_id" => '45963', "language" => 'en_US', "currency" => 'USD', "datatype" => "json"];
    $endpoint = "https://tourist-attraction.p.rapidapi.com/search";
    $isRapidAPI = true;
    $rapidAPIHost = "tourist-attraction.p.rapidapi.com";
    $output = post($endpoint, "TOURIST_API_KEY", $data, $isRapidAPI, $rapidAPIHost);

    if (se($output, "status", 400, false) == 200 && isset($output["response"])) {
        $output = json_decode($output["response"], true);
    } else {
        $output = [];
    }
    $output = $output["results"]["data"];
    $selectedPlace = null;
    foreach ($output as $place) {
        if ($place['location_id'] == $nationalLocID) {
            $selectedPlace = $place;
            break;
        }
    }
    return $selectedPlace;
}
?>