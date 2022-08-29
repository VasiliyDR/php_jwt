<?php
header("Access-Control-Allow-Origin: http://localhost:8000/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once"./api/config/database.php";
include_once"./api/objects/employee.php";
include_once "./api/create_employee.php";
include_once"./api/auth_employee.php";
include_once"./api/get_employee.php";
include_once"./api/update_employee.php";


if($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input"));
    if($data->act == "auth") {
        $authEmployee = new AuthEmployee();
        $authEmployee->auth($data);
    } else if($data->act == "get_employee") {
        $getEmployee = new GetEmployee($data->jwt);
        $getEmployee->getEmployee();

    } else if($data->act == "create") {
        $createEmployee = new EmployeeCreate();
        $createEmployee->creat($data);

    } else if($data->act == "update") {
        $updateEmployee = new UpdateEmployee($data);
        $updateEmployee->updateEmployee();
    }
}