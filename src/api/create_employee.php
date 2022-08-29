<?php
include_once "config/database.php";
include_once "objects/employee.php";

class EmployeeCreate {
    private $database;
    private $db;
    private $user;
    public function __construct()
    {
        $this->database = new Database();
        $this->db = $this->database->getConnection();
        $this->user = new Employee($this->db);
    }

    public function creat($data) {
        $this->user->name = $data->name;
        $this->user->email = $data->email;
        $this->user->password = $data->password;

        if (
            !empty($this->user->name) &&
            !empty($this->user->email) &&
            !empty($this->user->password) &&
            $this->user->create()
        ) {

            http_response_code(200);


            // код ответа
            http_response_code(200);
            echo json_encode(array("message" => "Пользователь был создан."));

        }
        else {
            http_response_code(400);
            echo json_encode(array("message" => "Невозможно создать пользователя."));
        }
    }
}
?>