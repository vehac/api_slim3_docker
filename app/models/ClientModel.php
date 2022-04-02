<?php

namespace App\Models;

use App\Libs\Database;
use App\Libs\MyResponse;

class ClientModel {
    
    private $db;
    private $table = 'client';
    private $response;
    
    public function __construct($container) {
        $database = new Database($container);
        $this->db = $database->connection();
        $this->response = new MyResponse();
    }
    
    public function getClients() {
        try {
            $stm = $this->db->prepare("SELECT * FROM $this->table");
            $stm->execute();
            $this->response->setResponse(false, "No existen clientes.");
            $this->response->code = 400;
            if($stm->rowCount() > 0) {
                $this->response->setResponse(true, "Listado realizado con éxito.");
                $this->response->code = 200;
                $this->response->result = $stm->fetchAll();
            }
            return $this->response;
        }catch(\Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            $this->response->code = 500;
            return $this->response;
        }
    }
    
    public function getClientById($id) {
        try {
            $stm = $this->db->prepare("SELECT * FROM $this->table WHERE id = ?");
            $stm->execute(array($id));
            $this->response->setResponse(false, "Cliente no existe.");
            $this->response->code = 400;
            if($stm->rowCount() > 0) {
                $this->response->setResponse(true, "Cliente retornado con éxito.");
                $this->response->code = 200;
                $this->response->result = $stm->fetch();
            }
            return $this->response;
        }catch(\Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            $this->response->code = 500;
            return $this->response;
        }  
    }
    
    public function saveClient($data) {
        try {
            
            if(isset($data['id']) && is_numeric($data['id'])) {
                $sql = "UPDATE $this->table SET name = ?, lastname = ?, gender = ?, description = ?, 
                    phone = ?, country = ?, address = ?, birth_date = ?, updated_at = ? WHERE id = ?";
                $stm = $this->db->prepare($sql);
                $stm->execute(array($data['name'], $data['lastname'], $data['gender'], 
                    $data['description'], $data['phone'], $data['country'], $data['address'], 
                    $data['birth_date'], date('Y-m-d H:i:s'), $data['id']));
                $msg = "Cliente actualizado con éxito.";
                $this->response->setResponse(false, "No se pudo actualizar cliente.");
                $this->response->code = 400;
            }else {
                $sql = "INSERT INTO $this->table (name, lastname, gender, description, phone, country, 
                    address, birth_date, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stm = $this->db->prepare($sql);
                $stm->execute(array($data['name'], $data['lastname'], $data['gender'], $data['description'], 
                    $data['phone'], $data['country'], $data['address'], $data['birth_date'], date('Y-m-d H:i:s')));
                $msg = "Cliente creado con éxito.";
                $this->response->setResponse(false, "No se pudo crear cliente.");
                $this->response->code = 400;
            }
            if($stm->rowCount() > 0) {
                $this->response->setResponse(true, $msg);
                $this->response->code = 200;
            }
            return $this->response;
        }catch (\Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            $this->response->code = 500;
            return $this->response;
        }
    }
    
    public function deleteClient($id) {
        try {
            $stm = $this->db->prepare("DELETE FROM $this->table WHERE id = ?");
            $stm->execute(array($id));
            $this->response->setResponse(false, "Cliente no existe.");
            $this->response->code = 400;
            if($stm->rowCount() > 0) {
                $this->response->setResponse(true, "Eliminación realizada con éxito.");
                $this->response->code = 200;
            }
            return $this->response;
        }catch (\Exception $e) {
            $this->response->setResponse(false, $e->getMessage());
            $this->response->code = 500;
            return $this->response;
        }
    }
}
