<?php
class Routes{
	public function exec(){
		$router = new router();

		$route = isset($_GET['route'])?'/'.$_GET['route']:'/';

		$router->addRoute('/', array(new booksController(), 'index'));

		// POST http://localhost/addbook
		$router->addRoute('/addbook', array(new booksController(), 'addbook'));

		// GET http://localhost/listbooks
		$router->addRoute('/listbooks', array(new booksController(), 'listbooks'));
		
		// GET http://localhost/getbookid?id={cod}
		$router->addRoute('/getbookid', array(new booksController(), 'getbookid'));

		// GET http://localhost/getbooktitle?title={title}
		$router->addRoute('/getbooktitle', array(new booksController(), 'getbooktitle'));

		// GET /books/{category}
		$router->addRoute('/getbookcategory', array(new booksController(), 'getbookcategory'));

		// PUT http://localhost/updatebook
		$router->addRoute('/updatebook', array(new booksController(), 'updatebook'));

		// DELETE http://localhost/deletebook
		$router->addRoute('/deletebook', array(new booksController(), 'deletebook'));

		// Categoria

		// GET http://localhost/listcategory
		$router->addRoute('/listcategory', array(new categoryController(), 'listcategory'));

		// GET http://localhost/getbookid?id={cod}
		$router->addRoute('/getcategoryid', array(new categoryController(), 'getcategoryid'));
		
		// POST http://localhost/insertcategory
		$router->addRoute('/insertcategory', array(new categoryController(), 'insertcategory'));

		// PUT http://localhost/updatecategory
		$router->addRoute('/updatecategory', array(new categoryController(), 'updatecategory'));

		// DELETE http://localhost/deletecategory
		$router->addRoute('/deletecategory', array(new categoryController(), 'deletecategory'));

		$router->handleRequest($route);
	}

}
