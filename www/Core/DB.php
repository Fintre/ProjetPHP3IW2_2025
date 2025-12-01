<?php

namespace App\Core;

class DB{
    
    private ?object $pdo = null;

    public function __construct(){
        try{
            $this->pdo = new \PDO("pgsql:host=db;port=5432;dbname=devdb","devuser", "devpass");
        }catch(Exception $e){
            die("Erreur ".$e->getMessage());
        }
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