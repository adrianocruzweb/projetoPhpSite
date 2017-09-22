<?php
	//Post do requisição http do angular
	include('funcao.php');
	include('seLiga.php');

	//Dados POST
	$dadosPost = file_get_contents("php://input");
	$resposta = json_decode($dadosPost);

	if(isset($resposta->funcao)){

		if($resposta->funcao == 'insereNoticia'){

			if($resposta->idNoticia){
				$sqlAtualizaNoticia = "update noticia set titulo=:titulo,texto=:texto,destaque=:destaque,img=:img where id_noticia=:id";

				$stmt = $dbh->prepare($sqlAtualizaNoticia);

				$stmt->bindParam(':titulo',		$resposta->titulo);
				$stmt->bindParam(':texto',		$resposta->texto);
				$stmt->bindParam(':destaque',	$resposta->destaque);
				$stmt->bindParam(':img',		$resposta->nomeImgUP);
				$stmt->bindParam(':id',			$resposta->idNoticia);

				if($stmt->execute()){
					echo json_encode(array('resposta'=>true));
				}else{
					echo json_encode(array('resposta'=>false));
				}	
			}else{
				$sqlInsereNoticia = "insert into noticia (titulo,texto,destaque,img) values (:titulo,:texto,:destaque,:img)";

				$stmt = $dbh->prepare($sqlInsereNoticia);

				$stmt->bindParam(':titulo',		$resposta->titulo);
				$stmt->bindParam(':texto',		$resposta->texto);
				$stmt->bindParam(':destaque',	$resposta->destaque);
				$stmt->bindParam(':img',		$resposta->nomeImgUP);

				if($stmt->execute()){
					echo json_encode(array('resposta'=>true,'id_noticia'=>$dbh->lastInsertId()));
				}else{
					echo json_encode(array('resposta'=>false));
				}	
			}

					
		}

		if($resposta->funcao == 'getNoticia'){

			$sqlGetNoticia = "select * from noticia";

			$stmt = $dbh->prepare($sqlGetNoticia);

			if($stmt->execute()){
				echo json_encode(array('resposta'=>true,'dados'=>$stmt->fetchAll(PDO::FETCH_OBJ)));
			}else{
				echo json_encode(array('resposta'=>false));
			}			
		}

		if($resposta->funcao == 'excluirNoticia'){

			$sqlDelNoticia = "delete from noticia where id_noticia = :id";

			$stmt = $dbh->prepare($sqlDelNoticia);

			$stmt->bindParam(':id',$resposta->id_noticia);

			if($stmt->execute()){
				echo json_encode(array('resposta'=>true));
			}else{
				echo json_encode(array('resposta'=>false));
			}			
		}		
	}	

	die();
?>