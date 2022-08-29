<?php
include_once "libs/php-jwt/src/BeforeValidException.php";
include_once "libs/php-jwt/src/ExpiredException.php";
include_once "libs/php-jwt/src/SignatureInvalidException.php";
include_once "libs/php-jwt/src/JWT.php";
use \Firebase\JWT\JWT;

class GetEmployee
{
    private $jwt;
    private $key = "your_secret_key";

    public function __construct($jwt)
    {
        $this->jwt = $jwt;
    }

    public function getEmployee()
    {
        $jwtToken = isset($this->jwt) ? $this->jwt : "";
        if ($jwtToken) {
            try {
                $decoded = JWT::decode($this->jwt, $this->key, array("HS256")); // $jwtToken
                http_response_code(200);
                echo json_encode(array(
                    "message" => "Доступ разрешен.",
                    "data" => $decoded->data
                ));
            } catch (Exception $e) {
                http_response_code(401);

                echo json_encode(array(
                    "result" => "no_auth",
                    "description" => "Не правильная связка Логин/Пароль"
                ));
            }
        } else {

            http_response_code(401);

            echo json_encode(array(
                "result" => "no_auth",
                "description" => "Не правильная связка Логин/Пароль"));
        }
    }
}