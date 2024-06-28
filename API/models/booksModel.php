<?php

class booksModel extends db{
    
    public function list(){
        $retorno = array();
    
        $sql = "SELECT * FROM books ORDER BY id";
        $sql = $this->db->query($sql);
    
        if($sql->rowCount() > 0){
            $retorno = $sql->fetchAll(\PDO::FETCH_ASSOC);
        }
    
        return $retorno;
    }

    public function getbyid($id){
        $retorno = array();

        $sql = "SELECT * FROM books WHERE id = :id";

        $sql = $this->db->prepare($sql);
        $sql->bindValue(':id', $id);
        $sql->execute();

        if($sql->rowCount() > 0){
            $retorno = $sql->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $retorno;
    }

    public function getbytitle($title){
        $retorno = array();

        $sql = "SELECT * FROM books WHERE title = :title";

        $sql = $this->db->prepare($sql);
        $sql->bindValue(':title', $title);
        $sql->execute();

        if($sql->rowCount() > 0){
            $retorno = $sql->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $retorno;
    }

    public function getbycategory($title){
        $retorno = array();

        $sql = "SELECT * FROM books WHERE id_category = :title";

        $sql = $this->db->prepare($sql);
        $sql->bindValue(':title', $title);
        $sql->execute();

        if($sql->rowCount() > 0){
            $retorno = $sql->fetchAll(\PDO::FETCH_ASSOC);
        }

        return $retorno;
    }

    public function insert($bookData) {
        if (!isset($bookData['cod'], $bookData['title'], $bookData['synopsis'], $bookData['id_category'])) {
            output_header(false,'O array de dados deve conter (cod, title, synopsis, id_category)');
            exit;
        }
    
        $cod = $bookData['cod'];
        $title = $bookData['title'];
        $synopsis = $bookData['synopsis'];
        $id_category = $bookData['id_category'];

        $sql = "INSERT INTO books (cod, title, synopsis, id_category) VALUES (:cod, :title, :synopsis, :id_category)";
    
        $sql = $this->db->prepare($sql);
    
        $sql->bindValue(':cod', $cod);
        $sql->bindValue(':title', $title);
        $sql->bindValue(':synopsis', $synopsis);
        $sql->bindValue(':id_category', $id_category);
    
        $sql->execute();

        if ($sql->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    public function update($bookData){
        if (!isset($bookData['id'], $bookData['cod'], $bookData['title'], $bookData['synopsis'], $bookData['id_category'])) {
            output_header(false, 'O array de dados deve conter (id, cod, title, synopsis, id_category)');
            exit;
        }
        
        $id = $bookData['id'];
        $cod = $bookData['cod'];
        $title = $bookData['title'];
        $synopsis = $bookData['synopsis'];
        $id_category = $bookData['id_category'];
        
        $sql = "UPDATE books SET cod = :cod, title = :title, synopsis = :synopsis, id_category = :id_category WHERE id = :id";
        
        $sql = $this->db->prepare($sql);
        
        $sql->bindValue(':id', $id);
        $sql->bindValue(':cod', $cod);
        $sql->bindValue(':title', $title);
        $sql->bindValue(':synopsis', $synopsis);
        $sql->bindValue(':id_category', $id_category);
        
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

        $sql = "DELETE FROM books WHERE id IN ($placeholders)";

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