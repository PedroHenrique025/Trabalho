<?php 
    // Consulta Livros
    $url_books = 'http://localhost/api/listbooks';
    $response_books = file_get_contents($url_books);

    if ($response_books === false) {
        die('Erro ao acessar a API de livros');
    }

    $data_books = json_decode($response_books);

    if ($data_books === null) {
        die('Erro ao decodificar JSON de livros');
    }

    // Consulta categorias
    $url_categories = 'http://localhost/api/listcategory';
    $response_categories = file_get_contents($url_categories);
    
    if ($response_categories === false) {
        die('Erro ao acessar a API de categorias');
    }
    
    $data_categories = json_decode($response_categories);
    
    if ($data_categories === null) {
        die('Erro ao decodificar JSON de categorias');
    }
    
    $categories_map = [];
    foreach ($data_categories->data as $category) {
        $categories_map[$category->id] = $category->title;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <header class="p-3 text-bg-dark">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center">
                <a href="/" class="d-flex col-12 align-items-center justify-content-center mb-2 mb-lg-0 text-white text-decoration-none">
                    <img src="assets/img/logo.png" alt="logo">
                </a>
            </div>
        </div>
    </header>

    <!-- Listagem Livros -->
    <main class="p-5">
        <div class="d-flex justify-content-between align-items-center"> 
            <h1>Livros</h1>
            <div class="col-5 d-flex justify-content-between align-items-center">
                <div class="input-group p-3">
                    <select class="form-select" id="inputFilterCategories" aria-label="Example select with button addon">
                        <option value="default" selected>Selecionar...</option>
                        <?php foreach ($data_categories->data as $category): ?>
                            <option value="<?php echo $category->id; ?>"><?php echo $category->title; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-outline-secondary" id="fetchCategory" type="button">Filtrar</button>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEditBookModal" data-bs-type="create">Adicionar</button>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Cod.</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody class="table-group-divider" id="booksTableBody">
                <?php foreach ($data_books->data as $book): ?>
                    <tr>
                        <th scope="row"><?php echo $book->cod; ?></th>
                        <td><?php echo $book->title ?></td>
                        <td><?php echo isset($categories_map[$book->id_category]) ? $categories_map[$book->id_category] : 'Categoria não encontrada'; ?></td>
                        <td>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#createEditBookModal" data-bs-type="edit" data-bs-name="<?php echo $book->title ?>" data-bs-id="<?php echo $book->id ?>">Editar</button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteBookModal" data-bs-name="<?php echo $book->title ?>" data-bs-id="<?php echo $book->id ?>">Apagar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
          </table>
    </main>

    <!-- Listagem Categorias -->
    <main class="p-5">
        <div class="d-flex justify-content-between align-items-center"> 
            <h1>Categorias</h1>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEditCategoryModal" data-bs-type="create">Adicionar</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Cod.</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php foreach ($data_categories->data as $category): ?>
                    <tr>
                        <th scope="row"><?php echo $category->id; ?></th>
                        <td><?php echo $category->title ?></td>
                        <td>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#createEditCategoryModal" data-bs-type="edit" data-bs-name="<?php echo $category->title ?>" data-bs-id="<?php echo $category->id ?>">Editar</button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal" data-bs-name="<?php echo $category->title ?>" data-bs-id="<?php echo $category->id ?>">Apagar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <img src="/front/assets/img/alert.png" class="rounded me-2" alt="...">
                    <strong class="me-auto">Erro</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Não é possível deletar o registro porque ele está sendo utilizado em outra tabela.
                </div>
            </div>
        </div>
    </main>
    
    <!-- Modais Livros -->
    <div class="modal fade" id="createEditBookModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                    <div class="mb-3">
                        <label class="col-form-label">Codigo:</label>
                        <input type="text" class="form-control" id="cod">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Título:</label>
                        <input type="text" class="form-control" id="title">
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Sinopse:</label>
                        <textarea class="form-control" id="synopsis"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label">Categoria:</label>
                        <select class="form-select" id="selectCategory">
                            <option selected>Selecione</option>
                            <?php foreach ($data_categories->data as $category): ?>
                                <option value="<?php echo $category->id; ?>"><?php echo $category->title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="saveButton">Salvar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteBookModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Confirmação</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>Deseja realmente apagar o livro<b></b></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn btn-danger" id="confirmDelete">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modais Categoria -->
    <div class="modal fade" id="createEditCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                    <div class="mb-3">
                        <label class="col-form-label">Título:</label>
                        <input type="text" class="form-control" id="title">
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="saveButton">Salvar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Confirmação</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>Deseja realmente apagar a categoria<b></b></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn btn-danger" id="confirmDelete">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>