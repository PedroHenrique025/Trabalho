<?php
require_once 'util/token.php';

class categoryController{
    private $dados;

	public function __construct(){
		global $config;
		$this->dados = array();
	}

    public function listcategory(){
        $categoryModel = new categoryModel();
        $retorno = $categoryModel->list();

        if(count($retorno) == 0){
            output_header(false, "Nenhum produto encontrado.");
        }

        output_header(true, 'Busca concluída', $retorno);
    }

    public function getcategoryid(){
        $categoryModel = new categoryModel();

        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $_GET['id'];
        }
        else{
            output_header(false, 'Parâmetro de busca inválido.', array('ID não enviada.'));
        }
        
        $retorno = $categoryModel->getbyid($id);

        if(!count($retorno)){
            output_header(false, 'ID não encontrada', array('Consulte o método lista'));
        }

        output_header(true, "Busca concluída", $retorno);
    }

    public function insertcategory() {
        
        $categoryModel = new categoryModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

            if (!isValidToken($token)) {
                http_response_code(401);
                output_header(false, 'Token invalido');
                exit;
            }

            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if ($data) {
                header('Content-Type: application/json');
                $categoryModel->insert($data);
                output_header(true, 'Dados JSON recebidos com sucesso', $data);
            } else {
                http_response_code(400);
                output_header(false, 'Formato JSON inválido', array('Consulte a documentação da API'));
            }
        } else {
            http_response_code(405);
            output_header(false, 'Método não permitido para esta rota', array('Consulte a documentação da API'));
        }
    }

    public function updatecategory() {
        $categoryModel = new categoryModel();

        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            $token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

            if (!isValidToken($token)) {
                http_response_code(401);
                output_header(false, 'Token invalido');
                exit;
            }

            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if ($data) {
                header('Content-Type: application/json');
                $categoryModel->update($data);
                output_header(true, 'Dados JSON recebidos com sucesso', $data);
            } else {
                http_response_code(400);
                output_header(false, 'Formato JSON inválido', array('Consulte a documentação da API'));
            }
        } else {
            http_response_code(405);
            output_header(false, 'Método não permitido para esta rota', array('Consulte a documentação da API'));
        }
    }

    public function deletecategory(){
        
        $categoryModel = new categoryModel();

        
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

            if (!isValidToken($token)) {
                http_response_code(401);
                echo json_encode(['error' => 'Token invalido']);
                exit;
            }

            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if ($data) {
                try {
                    header('Content-Type: application/json');
                    $categoryModel->delete($data);
                    output_header(true, 'Dados apagados com sucesso', $data);
                } catch (PDOException $e) {
                    if ($e->getCode() == '23000') {
                        echo "Erro: Não é possível deletar o registro porque ele está sendo utilizado em outra tabela.";
                    } else {
                        echo "Erro ao deletar o registro: " . $e->getMessage();
                    }
                }
            } else {
                http_response_code(400);
                output_header(false, 'Formato JSON inválido', array('Consulte a documentação da API'));
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Método não permitido para esta rota']);
        }
    }
}