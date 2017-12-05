<?php
	//Post do requisição http do angular
	include('funcao.php');
	include('seLiga.php');

	//Dados POST
	$dadosPost 	= file_get_contents("php://input");
	$resposta 	= json_decode($dadosPost);

	if(isset($resposta->funcao)){

		if($resposta->funcao == 'insereNoticia'){

			if($resposta->idNoticia){

				if(updateNoticia($resposta,$dbh) == true){

					if(delCTList($resposta->idNoticia,$dbh)){

						if(insereCTListagem($resposta,$resposta->idNoticia,$dbh)){
							echo json_encode(array('resposta'=>true,'id_noticia'=>$resposta->idNoticia));
						}else{
							echo json_encode(array('resposta'=>false));
						}
					}
				}
			}else{

				$idNoticia = insereNoticia($resposta,$dbh);
				
				if(isset($idNoticia) && !empty($idNoticia)){
					if(insereCTListagem($resposta,$idNoticia,$dbh)){
						echo json_encode(array('resposta'=>true,'id_noticia'=>$idNoticia));
					}else{
						echo json_encode(array('resposta'=>false));
					}
				}
			}					
		}

		if($resposta->funcao == 'getNoticiaDestaque'){
			echo json_encode(getNoticiaDestaque($dbh));			
		}

		if($resposta->funcao == 'getNoticiaSub'){
			echo json_encode(getNoticiaSub($dbh));			
		}

		if($resposta->funcao == 'getNoticiaUm'){
			echo json_encode(getNoticiaUm($dbh));			
		}

		if($resposta->funcao == 'getNoticiaDois'){
			echo json_encode(getNoticiaDois($dbh));			
		}

		if($resposta->funcao == 'getNoticiaTres'){
			echo json_encode(getNoticiaTres($dbh));			
		}

		if($resposta->funcao == 'getNoticia'){
			echo json_encode(getNoticia($dbh));			
		}

		if($resposta->funcao == 'excluirNoticia'){
			echo json_encode(delNoticia($resposta, $dbh));			
		}

		if($resposta->funcao == 'getNoticiaPorId'){
			echo json_encode(getNoticiaPorId($resposta->id, $dbh));
		}

		if($resposta->funcao == 'getNoticiaLink'){
			echo json_encode(getNoticiaLink($resposta->link, $dbh));
		}		
	}	

	die();
?>