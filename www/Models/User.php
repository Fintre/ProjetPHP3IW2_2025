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
}