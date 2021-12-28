<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$server = "localhost";
$user="root";
$password="";
$db_name="empleados";

$db_conection = new mysqli($server,$user,$password,$db_name);


$request = $_GET;

if(isset($request["search"])){
    $employees_sql= mysqli_query($db_conection,"SELECT * FROM employee WHERE id={$_GET['search']}");
    
    if(mysqli_num_rows($employees_sql) > 0){
        $employees = mysqli_fetch_all($employees_sql,MYSQLI_ASSOC);
        echo json_encode($employees);
        exit();
    }else{
        echo json_encode(["success"=>0]);
    }
}else if(isset($request["delete"])){
    $employees_sql= mysqli_query($db_conection,"DELETE FROM employee WHERE id={$_GET['delete']}");
    if($employees_sql){
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
        $employees_sql= mysqli_query($db_conection,"INSERT INTO employee (name,email) VALUES ('{$name}','{$email}') ");
        echo json_encode(["success"=>1]);
    }
    exit();
}else if(isset($request["update"])){
    $data = json_decode(file_get_contents("php://input"));
    $id=(isset($data->id))?$data->id:$_GET["update"];
    $name=$data->name;
    $email=$data->email;
    
    if(($email!="")&&($name!="")){
        $employees_sql= mysqli_query($db_conection,"UPDATE employee SET name='{$name}',email='{$email}' WHERE id='{$id}'");
        echo json_encode(["success"=>1]);
    }else{
        echo json_encode(["success"=>0]);
    }
    exit();
}else if(isset($request["list"])){
        
    $employees_sql= mysqli_query($db_conection,"SELECT * FROM employee");
    
    if(mysqli_num_rows($employees_sql) > 0){
        $employees = mysqli_fetch_all($employees_sql,MYSQLI_ASSOC);
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