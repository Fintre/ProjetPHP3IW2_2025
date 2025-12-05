<?php

namespace App\Models;

use App\Core\DB;

class User{

    protected string $table;


    public function __construct(){
        $this->setTable();
    }

    private function getPdo(): \PDO {
        return DB::getInstance()->getPdo();
    }
    
    public function setTable(){
        $this->table = 'user';
    }

    public function insert(array $data): bool
    {
        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ':' . $col, $columns);

        $sql = 'INSERT INTO "' . $this->table . '" (' 
                . implode(', ', $columns) .
               ') VALUES (' 
                . implode(', ', $placeholders) .
               ')';

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool
    {
        $setParts = [];
        foreach ($data as $key => $value) {
            $setParts[] = $key . ' = :' . $key;
        }

        $sql = 'UPDATE "' . $this->table . '" SET ' . implode(', ', $setParts) . ' WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;

        return $stmt->execute($data);
    }

    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM "' . $this->table . '" WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }


    public function updatePasswordEmail(string $email, string $password): bool{
        $sql = 'UPDATE "user" SET password = :password WHERE email = :email';
        $query = $this->getPdo()->prepare($sql);

        return $query->execute([
            ":password" => password_hash($password, PASSWORD_DEFAULT),
            ":email" => $email
        ]);
    }
    
        public function getOneBy(array $data) {
        $field = array_key_first($data); 
        $value = $data[$field];          
        $sql = 'SELECT * FROM "' . $this->table . '" WHERE "' . $field . '" = :value LIMIT 1';
        $query = $this->getPdo()->prepare($sql);
        $query->execute(['value' => $value]);
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    

}