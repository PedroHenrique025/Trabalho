$(document).ready(function() {

    var categoriesMap = {};

    function loadCategories() {
        return $.ajax({
            url: 'http://localhost/api/listcategory',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    response.data.forEach(function(category) {
                        categoriesMap[category.id] = category.title;
                    });
                } else {
                    console.error('Erro ao carregar as categorias:', response.return);
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    function populateTable(books) {
        var tbody = $('#booksTableBody');
        tbody.empty();

        if (books.length === 0) {
            var row = '<tr>' +
                '<td colspan="4" class="text-center">Nenhum livro encontrado para a categoria selecionada</td>' +
                '</tr>';
            tbody.append(row);
        }

        books.forEach(function(book) {
            var category = categoriesMap[book.id_category];
            var row = '<tr>' +
                '<th scope="row">' + book.cod + '</th>' +
                '<td>' + book.title + '</td>' +
                '<td>' + category + '</td>' +
                '<td>' +
                    '<button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#createEditBookModal" data-bs-type="edit" data-bs-name="' + book.title + '" data-bs-id="' + book.id + '">Editar</button>' +
                    '<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteBookModal" data-bs-name="' + book.title + '" data-bs-id="' + book.id + '">Apagar</button>' +
                '</td>' +
            '</tr>';
            tbody.append(row);
        });
    }

    loadCategories();

    $('#fetchCategory').click(function() {
        var selectedOption = $('#inputFilterCategories').find('option:selected').val();
        var apiUrl;

        if (selectedOption === 'default') {
            apiUrl = 'http://localhost/api/listbooks';
        } else {
            var selectedCategory = $('#inputFilterCategories').find('option:selected').val();
            apiUrl = 'http://localhost/api/getbookcategory?title=' + selectedCategory;
        }

        $.ajax({
            url: apiUrl,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                populateTable(response.data);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    $('#deleteBookModal').on('show.bs.modal', function(event) {
        console.log("deleteBookModal");
        var button = $(event.relatedTarget);
        var recipient = button.data('bs-name');
        var id = button.data('bs-id');
        var modalBody = $(this).find('.modal-body b');
        modalBody.text(` ${recipient}`);
        var ajaxData = {
            id: id,
        };

        $('#deleteBookModal #confirmDelete').on('click', function() {
            $.ajax({
                type: 'POST',
                url: '/front/functions/bookDeleteAjax.php',
                data: ajaxData,
                dataType: 'text',
                success: function(response) {
                    console.log(response);
                    location.reload();
                },
                error: function(error) {
                    console.error(error);
                }
            });
        });
    });

    $('#createEditBookModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var type = button.data('bs-type');

        var modal = $(this);
        var modalTitle = modal.find('.modal-title');
        var id = button.data('bs-id');

        switch (type) {
            case "create":
                console.log("createModal");
                modalTitle.text('Adicionar Livro');
                $('#createEditBookModal form')[0].reset();

                $('#createEditBookModal #saveButton').on('click', function() {
                    var ajaxData = {
                        cod: $('#cod').val(),
                        title: $('#title').val(),
                        synopsis: $('#synopsis').val(),
                        selectCategory: $('#selectCategory').val(),
                    };

                    console.log(ajaxData);

                    $.ajax({
                        type: 'POST',
                        url: '/front/functions/bookInsertAjax.php',
                        data: ajaxData,
                        dataType: 'text',
                        success: function(response) {

                            console.log(response);

                            $('#createEditBookModal').modal('hide');
                            location.reload();
                        },
                        error: function(error) {
                            console.error(error);
                        }
                    });
                });
            break;
            
            case "edit":
                console.log("editModal");
                var title = button.data('bs-name');
                modalTitle.text('Editando ' + title);
                var ajaxData = {
                    id: id,
                };

                $.ajax({
                    type: 'POST',
                    url: '/front/functions/bookEditAjax.php',
                    data: ajaxData,
                    dataType: 'json',
                    success: function(response) {
                        $('#cod').val(response.data[0].cod);
                        $('#title').val(response.data[0].title);
                        $('#synopsis').val(response.data[0].synopsis);
                        $('#selectCategory').val(response.data[0].id_category);
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });

                $('#createEditBookModal #saveButton').on('click', function() {
                    var ajaxData = {
                        id: id,
                        cod: $('#cod').val(),
                        title: $('#title').val(),
                        synopsis: $('#synopsis').val(),
                        selectCategory: $('#selectCategory').val()
                    };

                    $.ajax({
                        type: 'POST',
                        url: '/front/functions/bookUpdateAjax.php',
                        data: ajaxData,
                        dataType: 'text',
                        success: function(response) {
                            console.log(response);

                            $('#createEditBookModal').modal('hide');
                            location.reload();
                        },
                        error: function(error) {
                            console.error(error);
                        }
                    });
                });
            break;
        }
    });

    $('#deleteCategoryModal').on('show.bs.modal', function(event) {
        console.log("deleteCategoryModal");
        var button = $(event.relatedTarget);
        var recipient = button.data('bs-name');
        var id = button.data('bs-id');
        var modalBody = $(this).find('.modal-body b');
        modalBody.text(` ${recipient}`);
        var ajaxData = {
            id: id,
        };

        $('#deleteCategoryModal #confirmDelete').on('click', function() {
            $.ajax({
                type: 'POST',
                url: '/front/functions/categoryDeleteAjax.php',
                data: ajaxData,
                dataType: 'text',
                success: function(response) {
                    console.log(response);

                    if (response == "Return: Erro: Não é possível deletar o registro porque ele está sendo utilizado em outra tabela."){
                        var toastElement = $('#liveToast');
                        var toast = new bootstrap.Toast(toastElement[0]);
                        toast.show();
                    }else{
                        location.reload();
                    }
                },
                error: function(error) {
                    console.error(error);
                }
            });
        });
    });

    $('#createEditCategoryModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var type = button.data('bs-type');

        var modal = $(this);
        var modalTitle = modal.find('.modal-title');
        var id = button.data('bs-id');

        switch (type) {
            case "create":
                console.log("createModal");
                modalTitle.text('Adicionar Categoria');
                $('#createEditCategoryModal form')[0].reset();

                $('#createEditCategoryModal #saveButton').on('click', function() {

                    console.log("save category");
                    var ajaxData = {
                        title: $('#createEditCategoryModal #title').val(),
                    };

                    console.log(ajaxData);

                    $.ajax({
                        type: 'POST',
                        url: '/front/functions/categoryInsertAjax.php',
                        data: ajaxData,
                        dataType: 'text',
                        success: function(response) {

                            console.log(response);

                            $('#createEditCategoryModal').modal('hide');
                            location.reload();
                        },
                        error: function(error) {
                            console.error(error);
                        }
                    });
                });
            break;

            case "edit":
                console.log("editModal");
                var title = button.data('bs-name');
                modalTitle.text('Editando ' + title);
                var ajaxData = {
                    id: id,
                };

                $.ajax({
                    type: 'POST',
                    url: '/front/functions/categoryEditAjax.php',
                    data: ajaxData,
                    dataType: 'json',
                    success: function(response) {
                        $('#createEditCategoryModal #title').val(response.data[0].title);
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });

                $('#createEditCategoryModal #saveButton').on('click', function() {
                    var ajaxData = {
                        id: id,
                        title: $('#createEditCategoryModal #title').val(),
                    };

                    $.ajax({
                        type: 'POST',
                        url: '/front/functions/categoryUpdateAjax.php',
                        data: ajaxData,
                        dataType: 'text',
                        success: function(response) {
                            console.log(response);

                            $('#createEditCategoryModal').modal('hide');
                            location.reload();
                        },
                        error: function(error) {
                            console.error(error);
                        }
                    });
                });
            break;
        }
    });
});