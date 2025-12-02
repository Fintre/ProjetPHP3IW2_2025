<?php

namespace App\Models;

use App\Core\DB;

class Page extends DB{

    protected string $table;

    public function __construct(){
        parent::__construct();
        $this->setTable();
    }
    
    public function setTable(){
        $this->table = 'page';
    }

    public function insert(array $data): bool{
        $columns = array_keys($data);

        $placeholders = array_map(fn($col) => ":" . $col, $columns);

        $sql = "INSERT INTO {$this->table} (" 
                . implode(", ", $columns) .
               ") VALUES (" 
                . implode(", ", $placeholders) .
               ")";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);
    }
    

    public function findAll(): array{
        $sql = 'SELECT * FROM "' . $this->table . '" ORDER BY id DESC';

        $query = $this->pdo->prepare($sql);
        $query->execute();

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function update(int $id, array $data): bool{
        $setParts = [];
        foreach ($data as $key => $value) {
            $setParts[] = $key . " = :" . $key;
        }

        $sql = "UPDATE {$this->table} SET " . implode(", ", $setParts) . " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;

        return $stmt->execute($data);
    }

    public function delete(int $id): bool{
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(["id" => $id]);
    }

    public function getOneBy(array $data){

        $field = array_key_first($data); 
        $value = $data[$field];          
        $sql = 'SELECT * FROM "' . $this->table . '" WHERE "' . $field . '" = :value LIMIT 1';
        $query = $this->pdo->prepare($sql);
        $query->execute(['value' => $value]);
        
        return $query->fetch(\PDO::FETCH_ASSOC);
    }


}