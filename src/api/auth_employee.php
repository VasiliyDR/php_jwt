<?php
include_once "config/database.php";
include_once "objects/employee.php";
include_once "libs/php-jwt/src/ExpiredException.php";
include_once "libs/php-jwt/src/BeforeValidException.php";
include_once "libs/php-jwt/src/SignatureInvalidException.php";
include_once "libs/php-jwt/src/JWT.php";
use Firebase\JWT\JWT;

class AuthEmployee {
    private $database;
    private $db;
    private $employee;
    public function __construct()
    {
        $this->database = new Database();
        $this->db = $this->database->getConnection();
        $this->employee = new Employee($this->db);
    }

    public function auth($data) {
        $this->employee->email = $data->email;
        $email_exists = $this->employee->emailExists();
        if ( $email_exists && password_verify($data->password, $this->employee->password) ) {
            $key = "your_secret_key";
            $token = array(
                "iss" => "localhost",
                "aud" => "localhost",
                "exp" => time()+10000,
                "data" => array(
                    "id" => $this->employee->id,
                    "name" => $this->employee->name,
                    "email" => $this->employee->email
                )
            );

            http_response_code(200);

            $jwt = JWT::encode($token, $key, "HS256");

            echo json_encode(
                array(
                    "result" => "Successful",
                    "jwt" => $jwt
                )
            );
        }

        else {
            http_response_code(401);
            echo json_encode(array("result" => "no_auth", "description" => "Не правильная связка Логин/Пароль"));
        }
    }
}