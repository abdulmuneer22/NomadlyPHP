<?php
require_once 'unirest-php/src/Unirest.php';
$headers = array('Accept' => 'application/json');
$query = array('foo' => 'hello', 'bar' => 'world');

$response = Unirest\Request::post('http://mockbin.com/request', $headers, $query);

$response->code;        // HTTP Status code
$response->headers;     // Headers
$response->body;        // Parsed body
$response->raw_body;    // Unparsed body


print_r($response);
?>