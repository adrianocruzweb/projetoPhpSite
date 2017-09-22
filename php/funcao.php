<?php

function insereNoticia($obj,$dbh){

	$sqlInsereNoticia = "insert into noticia (
		titulo,
		texto,
		subtitulo,
		img,
		dt_ativacao,
		ct_noticia
	) values (
		:titulo,
		:texto,
		:subtitulo,
		:img,
		:dt_ativacao,
		:ct_noticia
	)";

	$stmt = $dbh->prepare($sqlInsereNoticia);

	$stmt->bindParam(':titulo',			$obj->titulo);
	$stmt->bindParam(':texto',			$obj->texto);
	$stmt->bindParam(':subtitulo',		$obj->subtitulo);
	$stmt->bindParam(':img',			$obj->nomeImgUP);
	$stmt->bindParam(':dt_ativacao',	$obj->dtAtivacao);
	$stmt->bindParam(':img',			$obj->nomeImgUP);
	$stmt->bindParam(':ct_noticia',		$obj->ctNoticia);

	if($stmt->execute()){
		return $dbh->lastInsertId();
	}else{
		return false;
	}
}

function updateNoticia($obj,$dbh){

	$sqlInsereNoticia = "update noticia set 
		titulo=:titulo,
		texto=:texto,
		subtitulo=:subtitulo,
		img=:img,
		dt_ativacao=:dt_ativacao,
		ct_noticia=:ct_noticia
		where 
		id_noticia=:id_noticia";

	$stmt = $dbh->prepare($sqlInsereNoticia);

	$stmt->bindParam(':titulo',			$obj->titulo);
	$stmt->bindParam(':texto',			$obj->texto);
	$stmt->bindParam(':subtitulo',		$obj->subtitulo);
	$stmt->bindParam(':img',			$obj->nomeImgUP);
	$stmt->bindParam(':dt_ativacao',	$obj->dtAtivacao);
	$stmt->bindParam(':img',			$obj->nomeImgUP);
	$stmt->bindParam(':id_noticia',		$obj->idNoticia);
	$stmt->bindParam(':ct_noticia',		$obj->ctNoticia);

	if($stmt->execute()){
		return true;
	}else{
		return false;
	}
}

function insereCTListagem($obj,$idNoticia,$dbh){

	$contaSucesso = 0;
	$qtdSucesso = count($obj->ctListagem);	
	
	foreach ($obj->ctListagem as $value) {
		$sqlInsereCTList = "insert into noticia_ct_list (
			id_noticia,
			id_ct_list
		) values (
			:id_noticia,
			:id_ct_list
		)";

		$stmt = $dbh->prepare($sqlInsereCTList);

		$stmt->bindParam(':id_noticia',		$idNoticia);
		$stmt->bindParam(':id_ct_list',		$value->categoria);

		if($stmt->execute()){
			$contaSucesso++;
		}
	}

	if($qtdSucesso != $contaSucesso){
		$dbh->rollBack();
		return false;
	}else{
		return true;
	}
}

function updateCTListagem($obj,$dbh){

	$contaSucesso = 0;
	$qtdSucesso = count($obj->ctListagem);	
	
	foreach ($obj->ctListagem as $value) {
		$sqlUpdateCTList = "update noticia_ct_list set
			id_noticia=:id_noticia,
			id_ct_list=:id_ct_list
			where
			id_noticia=:id_noticia and id_ct_list=:id_ct_list";

		$stmt = $dbh->prepare($sqlUpdateCTList);

		$stmt->bindParam(':id_noticia',	$obj->idNoticia);
		$stmt->bindParam(':id_ct_list',	$value->categoria);

		if($stmt->execute()){
			$contaSucesso++;
		}
	}

	if($qtdSucesso != $contaSucesso){
		$dbh->rollBack();
		return false;
	}else{
		return true;
	}
}

function getNoticia($dbh){

	$sqlGetNoticia = "select *,now() as agora from noticia order by dt_publicacao desc";

	$stmt = $dbh->prepare($sqlGetNoticia);

	if($stmt->execute()){
		$res 		= $stmt->fetchAll(PDO::FETCH_OBJ);
		$conta 		= count($res);
		$contaCT 	= 0;
		
		for ($i=0; $i < $conta; $i++) { 
			$res[$i]->ctListagem = getCTListagem($res[$i]->id_noticia,$dbh);

			if(isset($res[$i]->ctListagem)){
				$contaCT++;
			}
		}
		
		if($contaCT == $conta){
			return array('resposta'=>true,'dados'=>$res);
		}else{
			return array('resposta'=>false);
		}		
	}else{
		return array('resposta'=>false);
	}
}

function getNoticiaDestaque($dbh){

	$sqlGetNotDest = "SELECT
							n.id_noticia,
							n.titulo,
							n.subtitulo,
							n.texto,
							n.dt_publicacao,
							n.img,
							n.ct_noticia
						FROM
							noticia n
						JOIN
						 	noticia_ct_list ncl ON n.id_noticia = ncl.id_noticia
						WHERE
						  	ncl.id_ct_list = 1 AND n.dt_ativacao < NOW()
					  	ORDER BY
						  	n.id_noticia desc						
						LIMIT 3";
	
	$stmt = $dbh->prepare($sqlGetNotDest);

	if($stmt->execute()){
		$res 		= $stmt->fetchAll(PDO::FETCH_OBJ);	
		
		$resposta = monta($res);
		
		if($resposta){
			return $resposta;
		}else{
			return false;
		}		
	}else{
		return false;
	}
}

function getNoticiaSub($dbh){

	$sqlGetNotSub = "SELECT
							n.id_noticia,
							n.titulo,
							n.subtitulo,
							n.texto,
							n.dt_publicacao,
							n.img,
							n.ct_noticia
						FROM
							noticia n
						JOIN
						 	noticia_ct_list ncl ON n.id_noticia = ncl.id_noticia
						WHERE
						  	ncl.id_ct_list = 2 AND n.dt_ativacao < NOW()
						ORDER BY
						  	n.id_noticia desc
						LIMIT 1";

	
	$stmt = $dbh->prepare($sqlGetNotSub);

	if($stmt->execute()){
		$res 		= $stmt->fetchAll(PDO::FETCH_OBJ);	
		
		$resposta = monta($res);
		
		if($resposta){
			return $resposta;
		}else{
			return false;
		}		
	}else{
		return false;
	}
}

function getNoticiaUm($dbh){

	$sqlGetNotUm = "SELECT
							n.id_noticia,
							n.titulo,
							n.subtitulo,
							n.texto,
							n.dt_publicacao,
							n.img,
							n.ct_noticia
					FROM
						noticia n
					JOIN
					 	noticia_ct_list ncl ON n.id_noticia = ncl.id_noticia
					WHERE
					  	n.dt_ativacao < NOW()
					ORDER BY
					  	n.id_noticia desc";

	
	$stmt = $dbh->prepare($sqlGetNotUm);

	if($stmt->execute()){
		$res 		= $stmt->fetchAll(PDO::FETCH_OBJ);	
		
		$resposta = monta($res);
		
		if($resposta){
			return $resposta;
		}else{
			return false;
		}		
	}else{
		return false;
	}
}

function getNoticiaDois($dbh){

	$sqlGetNotDois = "SELECT
							n.id_noticia,
							n.titulo,
							n.subtitulo,
							n.texto,
							n.dt_publicacao,
							n.img,
							n.ct_noticia
						FROM
							noticia n
						JOIN
						 	noticia_ct_list ncl ON n.id_noticia = ncl.id_noticia
						WHERE
						  	ncl.id_ct_list = 4 AND n.dt_ativacao < NOW()
						ORDER BY
						  	n.id_noticia desc
						LIMIT 3";

	
	$stmt = $dbh->prepare($sqlGetNotDois);

	if($stmt->execute()){
		$res 		= $stmt->fetchAll(PDO::FETCH_OBJ);	
		
		$resposta = monta($res);
		
		if($resposta){
			return $resposta;
		}else{
			return false;
		}		
	}else{
		return false;
	}
}

function getNoticiaTres($dbh){

	$sqlGetNotTres = "SELECT
							n.id_noticia,
							n.titulo,
							n.subtitulo,
							n.texto,
							n.dt_publicacao,
							n.img,
							n.ct_noticia
						FROM
							noticia n
						JOIN
						 	noticia_ct_list ncl ON n.id_noticia = ncl.id_noticia
						WHERE
						  	ncl.id_ct_list = 5 AND n.dt_ativacao < NOW()
						ORDER BY
						  	n.id_noticia desc
						LIMIT 3";

	$stmt = $dbh->prepare($sqlGetNotTres);

	if($stmt->execute()){
		$res 		= $stmt->fetchAll(PDO::FETCH_OBJ);	
		
		$resposta = monta($res);
		
		if($resposta){
			return $resposta;
		}else{
			return false;
		}		
	}else{
		return false;
	}
}

function monta($res){
	$lista = array();

	if(!isset($res) || empty($res)){
		return false;
	}

	foreach($res as $valor){
		array_push($lista,array(
			'id' => $valor->id_noticia,
			'titulo' => $valor->titulo,
			'subtitulo' =>$valor->subtitulo,
			'texto' =>$valor->texto,
			'dt_publicacao' =>date_format(date_create($valor->dt_publicacao),"d/m/Y H:i"),
			'img' => "nimg/".$valor->img,
			'ct_noticia' => getCtNoticia($valor->ct_noticia)
		));
	}

	if(!count($lista)){
		return false;
	}

	return $lista;
}

function getCtNoticia($ct){
	$cat = "";
	switch($ct) {
	    case "1":
	        $cat = "Mundo";
	        break;
	    case "2":
	        $cat = "Geral";
	        break;
	    case "3":
	        $cat = "Famosos";
	        break;
	    case "4":
	        $cat = "Esporte";
	        break;
    	case "5":
	        $cat = "Policial";
	        break;
        case "6":
	        $cat = "Política";
	        break;
        case "7":
	        $cat = "Saúde";
	        break;
        case "8":
	        $cat = "Tecnologia";
	        break;
        case "9":
	        $cat = "Informe Publicitário";
	        break;
	}

	return $cat;
}

function getNoticiaPorId($id,$dbh){
	$sqlGetNoticia = "select * from noticia where id_noticia = :id order by dt_publicacao desc";

	$stmt = $dbh->prepare($sqlGetNoticia);
	$stmt->bindParam(':id',$id);

	if($stmt->execute()){
		$res = $stmt->fetchAll(PDO::FETCH_OBJ);
		return array('resposta'=>true,'dados'=>$res);				
	}else{
		return array('resposta'=>false);
	}
}	

function getCTListagem($idNoticia,$dbh){
	$sqlGetCTList = "select id_ct_list as ct_list from noticia_ct_list where id_noticia = :id";

	$stmt=$dbh->prepare($sqlGetCTList);

	$stmt->bindParam(':id',$idNoticia);

	if($stmt->execute()){
		return $stmt->fetchAll(PDO::FETCH_OBJ);
	}else{
		return false;
	}
}

function delNoticia($obj, $dbh){

	if(!deletaFoto($obj->id_noticia,$dbh)){
		return false;
	}

	$sqlDelNoticia = "delete from noticia where id_noticia = :id";

	$stmt = $dbh->prepare($sqlDelNoticia);

	$stmt->bindParam(':id',$obj->id_noticia);

	if($stmt->execute()){
		if(delCTList($obj->id_noticia,$dbh)){
			return array('resposta'=>true);
		}else{
			$dbh->rollBack();
			return array('resposta'=>true);
		}	
	}else{
		return array('resposta'=>false);
	}
}

function delCTList($idNoticia, $dbh){

	$sqlDelCTList = "delete from noticia_ct_list where id_noticia = :id";

	$stmt = $dbh->prepare($sqlDelCTList);

	$stmt->bindParam(':id',$idNoticia);

	if($stmt->execute()){
		return true;
	}else{
		return false;
	}
}

function deletaFoto($id,$dbh){
	$sql = 'select img from noticia where id_noticia = :id';

	$stmt = $dbh->prepare($sql);

	$stmt->bindParam(':id',$id);

	if($stmt->execute()){
		$obj = $stmt->fetchAll(PDO::FETCH_OBJ);
		return unlink("../nimg/".$obj[0]->img);
	}
}