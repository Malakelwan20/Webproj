<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "webdb";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB connection failed"]));
}
?>