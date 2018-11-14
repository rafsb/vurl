<?php
require "vurl.php";
$args = json_decode(file_get_contents("php://input"))->url;
echo $args ? '{"url" : "' . $args . '", "status" : "' . ( rafsb\vurl::validate($args) ? "pass" : "fail" ) . '"}' : '{ "pass" : "fail", "error" : "no arguments supplied" }';