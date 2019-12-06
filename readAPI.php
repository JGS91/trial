<?php

$apiKey = '3NLTTNlXsi6rBWl7nYGluOdkl2htFHug';

//CURL setup
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'http://trialapi.craig.mtcdevserver.com/api/properties?api_key=' . $apiKey);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

// Should probably be in a config file.
$servername = "localhost";
$username = "root";
$password = "root";

//Database connection.
try {
    $conn = new PDO("mysql:host=$servername;dbname=myDB", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    echo "Connected successfully \n\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

//Get count to see if any records exist within the database
$sql = "SELECT COUNT(*) AS 'Count' FROM properties";
$stmt = $conn->query($sql);
$result = $stmt->fetch();

//Helps avoid php warning
$response['next_page_url'] = ['nope'];

//If there are no records need to do a full database insert.
if ($result['Count'] == '0') {
    $sql = "INSERT INTO properties (uuid, 
                county, 
                country, 
                town, 
                description, 
                displayable_address, 
                image_url, 
                thumbnail_url, 
                latitude, 
                longitude, 
                bedrooms, 
                bathrooms,
                price, 
                property_type, 
                type, 
                created_at, 
                updated_at) 
                    VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";

    $stmt = $conn->prepare($sql);
    
    //Can keep going until the API tells there are no more pages.
    while ($response['next_page_url'] != null) {
        $response = json_decode(curl_exec($curl), true);

        foreach ($response["data"] as $property) {
            $stmt->execute([$property['uuid'], $property['county'], $property['country'], $property['town'], $property['description'], $property['address'], $property['image_full'], $property['image_thumbnail'], $property['latitude'], $property['longitude'], $property['num_bedrooms'], $property['num_bathrooms'], $property['price'], $property['property_type']['title'], $property['type']]);
        }

        curl_setopt($curl, CURLOPT_URL, $response['next_page_url']);
    }
} else {
    //If there are records this block is going to be checking whether the records need updating.

    $sql = "SELECT updated_at FROM properties WHERE uuid = ?";
    $stmt = $conn->prepare($sql);

    while ($response['next_page_url'] != null) {
        $response = json_decode(curl_exec($curl), true);

        //Check whether each record brought back has an updated timestamp before the api timestamp and if so update accordingly.
        foreach ($response["data"] as $property) {
            $stmt->execute([$property['uuid']]);
            $result = $stmt->fetch();
            if ($result['updated_at'] < $property['updated_at']) {
                echo "Property " . $property['uuid'] . " needs updating \n\n";
            } else {
                echo "Property " . $property['uuid'] . " does not need updating \n\n";
            }
        }

        curl_setopt($curl, CURLOPT_URL, $response['next_page_url']);
    }
}

// NOTES
// Would need to also add code to deal with properties added to the api which don't exist in the database. The first if only catches on a pure empty database.