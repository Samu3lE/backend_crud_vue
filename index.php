<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require("./vendor/autoload.php");

try{
        $dotenv = Dotenv\Dotenv::createImmutable('./');
        $dotenv->load();

        $host = $_ENV['DB_HOST'];
        $user=$_ENV['DB_USER'];
        $password=$_ENV['DB_PASSWORD'];
        $dbname=$_ENV['DB_NAME'];
        $port=$_ENV['DB_PORT'];

                //Set DSN data source name
        $dsn = "pgsql:host=" . $host . ";port=" . $port .";dbname=" . $dbname . ";user=" . $user . ";password=" . $password . ";";

        //         //create a pdo instance

        $pdo = new PDO($dsn, $user, $password);
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
}
   
$request = $_GET;

if(isset($request["search"])){
    $employees_sql= "SELECT * FROM employee WHERE id={$_GET['search']}";

  $stmt = $pdo->prepare($employees_sql);
  $stmt->execute();
  $rowCount = $stmt->rowCount();
  
    if($rowCount > 0){
        $employees = $stmt->fetch();
        echo json_encode($employees);
        exit();
    }else{
        echo json_encode(["success"=>0]);
    }
}else if(isset($request["delete"])){
    $employees_sql= "DELETE FROM employee WHERE id={$_GET['delete']}";
    $stmt = $pdo->prepare($employees_sql);
    $success=$stmt->execute();
    
    if($success){
        echo json_encode(["success"=>1]);
        exit();
    }else{
        echo json_encode(["success"=>0]);
    }
}else if(isset($request["create"])){
    $data = json_decode(file_get_contents("php://input"));
    $name = $data->name;
    $email= $data->email;
    if(($email!="")&&($name!="")){
        $employees_sql= "INSERT INTO employee (name,email) VALUES ('{$name}','{$email}') ";

        $stmt = $pdo->prepare($employees_sql);
        $success=$stmt->execute();
        
        if($success){
            echo json_encode(["success"=>1]);
            
        }else{
            echo json_encode(["success"=>0]);
        }

    }else{
        echo json_encode(["success"=>0]);
    }
    exit();
    
}else if(isset($request["update"])){
    $data = json_decode(file_get_contents("php://input"));
    $id=(isset($data->id))?$data->id:$_GET["update"];
    $name=$data->name;
    $email=$data->email;
    
    if(($email!="")&&($name!="")){
        $employees_sql= "UPDATE employee SET name='{$name}',email='{$email}' WHERE id='{$id}'";
        $stmt = $pdo->prepare($employees_sql);
        $success=$stmt->execute();
        
        if($success){
            echo json_encode(["success"=>1]);
            
        }else{
            echo json_encode(["success"=>0]);
        }
    }else{
        echo json_encode(["success"=>0]);
    }
    exit();
}else if(isset($request["list"])){
        
    $employees_sql= "SELECT * FROM employee";
    
    $stmt = $pdo->prepare($employees_sql);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    
      if($rowCount > 0){
          $employees = $stmt->fetch();
          echo json_encode($employees);
          exit();
      }else{
          echo json_encode(["success"=>0]);
      }

}else{
    http_response_code(404);

    echo json_encode([
        "success" => 0,
        "message" => 'no se encontro la ruta',
    ]);

    exit();
}

?>