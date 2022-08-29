<?php

class Employee {
    private $conn;
    private $table_name = "employee";


    public $id;
    public $name;
    public $email;
    public $password;


    public function __construct($db) {
        $this->conn = $db;
    }

        public function update($oldName,$oldEmail) {


            $name_set= " name = :name";
            $email_set=" ,email = :email";
            $password_set=!empty($this->password) ? ", password = :password" : "";

            $query = "UPDATE " . $this->table_name . "
            SET
                {$name_set}
                {$email_set}
                {$password_set}
            WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            if(!empty($this->password)){
                $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
                $stmt->bindParam(":password", $password_hash);
            }
            if(!empty($this->name)){
                $stmt->bindParam(":name", $this->name);
            } else {
                $this->name = $oldName;
                $stmt->bindParam(":name", $this->name);
            }
            if(!empty($this->email)){
                $stmt->bindParam(":email", $this->email);
            } else {
                $this->email = $oldEmail;
                $stmt->bindParam(":email", $this->email);
            }

            $stmt->bindParam(":id", $this->id);

            if($stmt->execute()) {
                return true;
            }

            return false;
        }



    public function create() {


        $query = "INSERT INTO employee
                SET
                    name = :name,
                    email = :email,
                    password = :password";


        $stmt = $this->conn->prepare($query);

        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->password=htmlspecialchars(strip_tags($this->password));

        // привязываем значения
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);

        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(":password", $password_hash);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    function emailExists() {


        $query = "SELECT id, name, password
            FROM " . $this->table_name . "
            WHERE email = ?
            LIMIT 0,1";


        $stmt = $this->conn->prepare( $query );

        // инъекция
        $this->email=htmlspecialchars(strip_tags($this->email));

        // привязываем значение e-mail
        $stmt->bindParam(1, $this->email);


        $stmt->execute();


        $num = $stmt->rowCount();


        if($num>0) {


            $row = $stmt->fetch(PDO::FETCH_ASSOC);


            $this->id = $row["id"];
            $this->name = $row["name"];
            $this->password = $row["password"];


            return true;
        }


        return false;
    }
}