<?php
class db{

	protected $db;

	public function __construct(){
		global $db;
		$this->db = $db;
	}
}