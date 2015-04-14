<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client();

$response = $client->get('https://www.documentcloud.org/api/search.json?q=group:dhpraxis&data=true&annotation=true&per_page=100');
$json = $response->json();

foreach ($json['documents'] as $id => $doc) {
    var_dump($doc['data']);
}

var_dump($json['documents'][0]['data']);
?>