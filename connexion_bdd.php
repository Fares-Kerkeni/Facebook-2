<?php
    $engine = "mysql";
    $host = "localhost";
    $port = 8889;
    $dbname = "social_network";  
    $username = "root";
    $password = "root";
    $pdo = new PDO("$engine:host=$host:$port;dbname=$dbname", $username, $password, [
    ]);
    // if($pdo){
    // echo"good";
    // }
    // else{
    // echo"pas good";
    // }
