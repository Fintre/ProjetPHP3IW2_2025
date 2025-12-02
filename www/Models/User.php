<?php

namespace App\Models;

use App\Core\DB;

class User extends DB{

    protected string $table;

    public function __construct(){
        parent::__construct();
        $this->setTable();
    }
    
    public function setTable(){
        $this->table = 'user';
    }

    public function updatePasswordEmail(string $email, string $password): bool{
        $sql = 'UPDATE "user" SET password = :password WHERE email = :email';
        $query = $this->pdo->prepare($sql);

        return $query->execute([
            ":password" => password_hash($password, PASSWORD_DEFAULT),
            ":email" => $email
        ]);
    }

}