<?php
	function output_header($status = true, $retorno = null, $dados  = array(), $versao = 'v1'){
		header("Content-Type: application/json;charset=utf-8");
		echo json_encode( array('status' => $status, 'return' => $retorno, 'data'  => $dados, 'version' => $versao) );
		exit;
	}
?>