<?php
	//Post do requisição http do angular
	include('funcao.php');
	include('seLiga.php');

	//Dados POST
	$dadosPost = file_get_contents("php://input");
	$resposta = json_decode($dadosPost);


	if(isset($resposta->funcao)){

		switch ($resposta->funcao) {
			case 'desloga':
				unset($resposta->funcao);
				session_start();
				$sessaoDestruida = session_destroy();
				if($sessaoDestruida = true){
					echo json_encode(1);
				}else{
					echo json_encode(2);
				}
				break;
			case 'verificaLogin':
				unset($resposta->funcao);
				session_start();			
				if(!empty($_SESSION['usuario']))
					echo json_encode(1);
				else
					echo json_encode(2);
				break;
			case 'logar':
				unset($resposta->funcao);
				$usuario = $resposta->usuario;
				$senha = $resposta->senha;
				$stmt = $dbh->prepare("select senha from login where usuario = :usuario");
				$stmt->bindParam(':usuario',$usuario);
				$stmt->execute();
				$dados = $stmt->fetch(PDO::FETCH_OBJ);
				
				if($senha == $dados->senha){
					session_start();
					$_SESSION['usuario'] = $usuario;
					echo json_encode(1);
				}else{
					echo json_encode(2);
				}
				break;
		}
	}	
	die();
?>