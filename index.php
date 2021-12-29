<?php
require("./vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable('./');
        $dotenv->load();

        $host = $_ENV['DB_HOST'];
        $user=$_ENV['DB_USER'];
        $password=$_ENV['DB_PASSWORD'];
        $dbname=$_ENV['DB_NAME'];
        $port=$_ENV['DB_PORT'];
 
echo "Hello there heroku, samuel Here!";
?>