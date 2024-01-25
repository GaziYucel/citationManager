<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

exit;

echo "<textarea style='width: 100%; height: 100%;' >";

require_once('/var/www/html/ojs340/lib/pkp/lib/vendor/autoload.php');
require_once(__DIR__ . '/vendor/autoload.php');

use GuzzleHttp\Client;


$client = new Client(
    [
        'headers' => [
            'User-Agent' => 'ojs2/OptimetaCitationsPlugin',
            'Accept' => 'application/json'],
        'verify' => false,
        'cookies' => true
    ]
);

$url = 'https://www.wikidata.org/w/api.php?prop=&list=search&srsearch=10.7717%2Fpeerj.1990&srlimit=2&action=query&format=json&formatversion=2';
//$url = 'https://pub.orcid.org/v2.1/0000-0002-4411-9674';

$response = $client->get($url);

var_dump($response->getStatusCode());
echo "\n**************************************************\n\n";

var_dump(json_decode($response->getBody(), true));
echo "\n**************************************************\n\n";

var_dump($response);
echo "\n**************************************************\n\n";





echo "</textarea>";
