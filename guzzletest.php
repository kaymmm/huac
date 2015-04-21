<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client();

$response = $client->get('https://www.documentcloud.org/api/search.json?q=group:dhpraxis&data=true&annotation=true&per_page=100');
$json = $response->json();
var_dump($json);
foreach ($json['documents'] as $id => $doc) {
    foreach ($doc['data'] as $data_key => $value) {
        if ($value == "Brecht, Bertolt") {
            echo "doc: " . $doc['title'];// . "" $data_key;
        }
        // var_dump($value);
    }
    // var_dump($doc['data']);
}

// var_dump($json['documents'][0]['data']);
?>