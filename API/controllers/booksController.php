<?php
require_once 'util/token.php';

class booksController{
    private $dados;

	public function __construct(){
		global $config;
		$this->dados = array();
	}

    public function index(){
        output_header(false, "Método não permitido");
    }

    public function listbooks(){
        $booksModel = new booksModel();
        $retorno = $booksModel->list();

        if(count($retorno) == 0){
            output_header(false, "Nenhum produto encontrado.");
        }

        output_header(true, 'Busca concluída', $retorno);
    }

    public function getbookid(){
        $booksModel = new booksModel();

        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $_GET['id'];
        }
        else{
            output_header(false, 'Parâmetro de busca inválido.', array('ID não enviada.'));
        }
        
        $retorno = $booksModel->getbyid($id);

        if(!count($retorno)){
            output_header(false, 'ID não encontrada', array('Consulte o método lista'));
        }

        output_header(true, "Busca concluída", $retorno);
    }

    public function getbooktitle(){
        $booksModel = new booksModel();

        if(isset($_GET['title']) && !empty($_GET['title'])){
            $title = $_GET['title'];
        }
        else{
            output_header(false, 'Parâmetro de busca inválido.', array('Título não enviada.'));
        }
        
        $retorno = $booksModel->getbytitle($title);

        if(!count($retorno)){
            output_header(false, 'Título não encontrada');
        }

        output_header(true, "Busca concluída", $retorno);
    }

    public function getbookcategory(){
        $booksModel = new booksModel();

        if(isset($_GET['title']) && !empty($_GET['title'])){
            $title = $_GET['title'];
        }
        else{
            output_header(false, 'Parâmetro de busca inválido.', array('ID da categoria não enviado.'));
        }
        
        $retorno = $booksModel->getbycategory($title);

        if(!count($retorno)){
            output_header(false, 'Categoria não encontrada');
        }

        output_header(true, "Busca concluída", $retorno);
    }

    public function addbook() {
        $booksModel = new booksModel();

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
                $booksModel->insert($data);
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

    public function updatebook() {
        $booksModel = new booksModel();

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
                $booksModel->update($data);
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

    /**
     * Deleta um ou varios item na DB.
     * Responde à requisição POST em 'http://localhost/api/delete'.
     * Requer um token de autorização no cabeçalho 'Authorization'.
     * Os dados devem ser fornecidos em formato JSON no corpo da requisição.
    */
    public function deletebook(){
        
        $booksModel = new booksModel();

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
                header('Content-Type: application/json');
                $booksModel->delete($data);
                output_header(true, 'Dados apagados com sucesso', $data);
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