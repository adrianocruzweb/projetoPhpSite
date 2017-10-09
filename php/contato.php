<?php
	//Post do requisição http do angular
	include('funcao.php');
	include('seLiga.php');

	//Dados POST
	$dadosPost 	= file_get_contents("php://input");
	$resposta 	= json_decode($dadosPost);

	if(isset($resposta->funcao)){

		if($resposta->funcao == 'enviaContato'){
			
			echo json_encode(enviaContato($resposta, $dbh));		
		}		
	}	

	die();
?>