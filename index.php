<?php
    require("./vendor/autoload.php");

    try {
        // $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        //     $dotenv->load();

            $host = $_ENV['DB_HOST'];
            $user=$_ENV['DB_USER'];
            $password=$_ENV['DB_PASSWORD'];
            $dbname=$_ENV['DB_NAME'];
            $port=$_ENV['DB_PORT'];
        
                //Set DSN data source name
                $dsn = "pgsql:host=" . $host . ";port=" . $port .";dbname=" . $dbname . ";user=" . $user . ";password=" . $password . ";";

                //         //create a pdo instance
    

          echo $dsn;
    } catch (\Throwable $th) {
        echo $th;
    }
?>