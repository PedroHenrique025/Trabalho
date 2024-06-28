<?php

class categoryModel extends db{
    
    public function list(){
        $retorno = array();
    
        $sql = "SELECT * FROM category ORDER BY id";
        $sql = $this->db->query($sql);
    
        if($sql->rowCount() > 0){
            $retorno = $sql->fetchAll(\PDO::FETCH_ASSOC);
        }
    
        return $retorno;
    }

    public function getbyid($id){
        $retorno = array();

        $sql = "SELECT * FROM category WHERE id = :id";

        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $retorno = $sql->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $retorno;
    }
    
    public function insert($categoryData) {
        if (!isset($categoryData['title'])) {
            output_header(false,'O array de dados deve conter (id, title)');
            exit;
        }
    
        $title = $categoryData['title'];

        $sql = "INSERT INTO category (title) VALUES (:title)";
    
        $sql = $this->db->prepare($sql);
    
        $sql->bindValue(':title', $title);
    
        $sql->execute();

        if ($sql->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function update($categoryData){
        if (!isset($categoryData['id'], $categoryData['title'])) {
            output_header(false,'O array de dados deve conter (id, title)');
            exit;
        }
        
        $id = $categoryData['id'];
        $title = $categoryData['title'];
        
        $sql = "UPDATE category SET title = :title WHERE id = :id";
        
        $sql = $this->db->prepare($sql);
        
        $sql->bindValue(':id', $id);
        $sql->bindValue(':title', $title);
        
        $sql->execute();
        
        if ($sql->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function delete($data){

        if (!isset($data) || empty($data)) {
            output_header(false, 'O array de IDs não deve estar vazio');
            exit;
        }

        $ids = $data;

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "DELETE FROM category WHERE id IN ($placeholders)";

        $sql = $this->db->prepare($sql);

        foreach ($ids as $index => $id) {
            $sql->bindValue($index + 1, $id);
        }
    
        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }
}