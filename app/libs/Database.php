<?php

namespace App\Libs;

use PDO;

class Database {
    
    private $container;
    private $pdo;
    
    public function __construct($container) {
        $this->container = $container;
        $this->pdo = NULL;
    }
    
    public function connection() {
        try {
            $db = $this->container['settings']['db'];
            $this->pdo = new PDO('mysql:host=' . $db['host'] . ';port=' . $db['port'] . ';dbname=' . $db['dbname'] . ';charset=' . $db['charset'], $db['user'], $db['pass']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            //$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            return $this->pdo;
        }catch (\Exception $e) {
            throw new \Exception($e->getMessage(), (int)$e->getCode());
        }
        
    }
}
