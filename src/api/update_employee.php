<?php
use Firebase\JWT\JWT;
class UpdateEmployee
{
    private $key = "your_secret_key";
    private $database;
    private $db;
    private $employee;
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
        $this->database = new Database();
        $this->db = $this->database->getConnection();
        $this->employee = new Employee($this->db);
    }

    public function updateEmployee()
    {
        $jwtToken = isset($this->data->jwt) ? $this->data->jwt : "";
        if ($jwtToken) {
            $decoded = JWT::decode($this->data->jwt, $this->key, array("HS256"));
            $this->employee->name = $this->data->name;
            $this->employee->email = $this->data->email;
            $this->employee->password = $this->data->password;

            $this->employee->id = $decoded->data->id;
            $oldName = $decoded->data->name;
            $oldEmail = $decoded->data->email;

            if ($this->employee->update($oldName, $oldEmail)) {
                $key = "your_secret_key";
                $token = array(
                    "iss" => "localhost",
                    "aud" => "localhost",
                    "exp" => time() + 1000,
                    "data" => array(
                        "id" => $this->employee->id,
                        "name" => $this->employee->name,
                        "email" => $this->employee->email
                    )
                );
                $jwt = JWT::encode($token, $key);

                http_response_code(200);

                echo json_encode(
                    array(
                        "message" => "Пользователь был обновлён",
                        "jwt" => $jwt
                    )
                );

            } else {
                http_response_code(401);
                echo json_encode(array("message" => "Невозможно обновить пользователя."));
            }


        }
    }

}