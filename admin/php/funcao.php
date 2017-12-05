<?php

function linhasBusca($dbh,$obj){
	$sql = 'SELECT count(a.idaluno) num	FROM aluno as a WHERE a.codigoaluno is not null ';

	if(isset($obj->nomeAluno) && !empty($obj->nomeAluno)){
		$sqlNomeAluno 	= 'and a.nomealuno like :nome ';
		$sql 			= $sql.$sqlNomeAluno;	
	}
	if(isset($obj->colegio) && !empty($obj->colegio)){
		$sqlColegio = 'and a.colegio_idcolegio = :colegio ';
		$sql 		= $sql.$sqlColegio; 
	}
	if(isset($obj->periodo) && !empty($obj->periodo)){
		$sqlPeriodo = 'and a.periodo_idperiodo = :periodo ';
		$sql 		= $sql.$sqlPeriodo;
	}
	if(isset($obj->serie) && !empty($obj->serie)){
		$sqlSerie  	= 'and a.serie_idserie = :serie ';
		$sql 		= $sql.$sqlSerie;
	}
	if(isset($obj->linha) && !empty($obj->linha)){
		$sqlLinha 	= 'and a.linha_idlinha = :linha ';
		$sql 		= $sql.$sqlLinha;
	}

	$stmt 			= $dbh->prepare($sql);

	if(isset($obj->nomeAluno) && !empty($obj->nomeAluno)){
		$nomeDoAluno 	= '%'.$obj->nomeAluno.'%';
		$stmt->bindParam(':nome',$nomeDoAluno);
	}

	if(isset($obj->colegio) && !empty($obj->colegio)){
		$stmt->bindParam(':colegio',$obj->colegio,PDO::PARAM_INT);
	}

	if(isset($obj->periodo) && !empty($obj->periodo)){
		$stmt->bindParam(':periodo',$obj->periodo);
	}

	if(isset($obj->serie) && !empty($obj->serie)){
		$stmt->bindParam(':serie',$obj->serie);
	}

	if(isset($obj->linha) && !empty($obj->linha)){
		$stmt->bindParam(':linha',$obj->linha);
	}

	$stmt->execute();
	$dados = $stmt->fetchAll(PDO::FETCH_OBJ);
	return $dados[0]->num;
}

/*
	Função de busca de Alunos
*/
function buscaAlunosPrincipalAntigo($dbh,$obj){

	$paginou = false;

	$sql = 'SELECT 
			a.idaluno as id,
			a.codigoaluno as codigo,
			a.nomealuno as nome
		FROM
			aluno as a
		WHERE
			a.codigoaluno is not null ';

	if(isset($obj->nomeAluno) && !empty($obj->nomeAluno)){
		$sqlNomeAluno 	= 'and a.nomealuno like :nome ';
		$sql 			= $sql.$sqlNomeAluno;	
	}
	if(isset($obj->colegio) && !empty($obj->colegio)){
		$sqlColegio = 'and a.colegio_idcolegio = :colegio ';
		$sql 		= $sql.$sqlColegio; 
	}
	if(isset($obj->periodo) && !empty($obj->periodo)){
		$sqlPeriodo = 'and a.periodo_idperiodo = :periodo ';
		$sql 		= $sql.$sqlPeriodo;
	}
	if(isset($obj->serie) && !empty($obj->serie)){
		$sqlSerie  	= 'and a.serie_idserie = :serie ';
		$sql 		= $sql.$sqlSerie;
	}
	if(isset($obj->linha) && !empty($obj->linha)){
		$sqlLinha 	= 'and a.linha_idlinha = :linha ';
		$sql 		= $sql.$sqlLinha;
	}	
	
	$num = linhasBusca($dbh,$obj);

	if($num > 19){
		$obj->paginacao;
		$obj->linhaPagina;
		$sqlLimit 		= 'LIMIT 20 OFFSET :limit';
		$sql 			= $sql.$sqlLimit;
		$paginou 		= true;
	}

	$stmt 			= $dbh->prepare($sql);
	
	if(isset($obj->nomeAluno) && !empty($obj->nomeAluno)){
		$nomeDoAluno 	= '%'.$obj->nomeAluno.'%';
		$stmt->bindParam(':nome',$nomeDoAluno);
	}

	if(isset($obj->colegio) && !empty($obj->colegio)){
		$stmt->bindParam(':colegio',$obj->colegio,PDO::PARAM_INT);
	}

	if(isset($obj->periodo) && !empty($obj->periodo)){
		$stmt->bindParam(':periodo',$obj->periodo);
	}

	if(isset($obj->serie) && !empty($obj->serie)){
		$stmt->bindParam(':serie',$obj->serie);
	}

	if(isset($obj->linha) && !empty($obj->linha)){
		$stmt->bindParam(':linha',$obj->linha);
	}

	//Recurso para Paginação
	if($paginou == true){	
		if(isset($obj->paginacao) && $obj->paginacao == true){
			if(isset($obj->linhaPagina) && !empty($obj->linhaPagina)){
				$stmt->bindParam(':limit',$obj->linhaPagina,PDO::PARAM_INT);
			}
		}else{
			$vinte = 20;
			$stmt->bindParam(':limit',$vinte,PDO::PARAM_INT);
		}
	}	

	$stmt->execute();
	$dados = $stmt->fetchAll(PDO::FETCH_OBJ);
	
	//Variaveis usadas na etapa seguinte
	$dataInicial	= dataFormatada($obj->dtInicial);
	$dataFinal		= dataFormatada($obj->dtFinal, true);
	$arrayDes		= array();
	$aluno 			= new stdClass();

	foreach ($dados as $chave => $valor) {
		$aluno->id  	= $valor->id;
		$aluno->nome 	= $valor->nome;
		$aluno->codigo  = (string)((int)$valor->codigo);
		$aluno->datainicial = $dataInicial;
		$aluno->dataFinal = $dataFinal;

		$resultW 		= buscaWebService((string)((int)$valor->codigo),$dataInicial,$dataFinal);
		
		if(is_array($resultW)){
			$sizeArray = count($resultW);
			$aluno->arrayRast = $resultW[(int)$sizeArray-1];
			if(!empty($aluno->arrayRast->DataPosicao))
			$aluno->arrayRast->DataPosicao = formataDataWS($aluno->arrayRast->DataPosicao);
		}else{
			$aluno->arrayRast = $resultW;
			if(!empty($aluno->arrayRast->DataPosicao))
			$aluno->arrayRast->DataPosicao = formataDataWS($aluno->arrayRast->DataPosicao);
		}

		$arrayDes[] = $aluno;
		$aluno = new stdClass();
	}

	return $arrayDes;
}

function buscaAlunosPrincipal($dbh,$obj){
	$paginou = false;

	$sql = 'SELECT 
			a.idaluno as id,
			a.codigoaluno as codigo,
			a.nomealuno as nome
		FROM
			aluno as a
		WHERE
			a.codigoaluno is not null ';

	if(isset($obj->nomeAluno) && !empty($obj->nomeAluno)){
		$sqlNomeAluno 	= 'and a.nomealuno like :nome ';
		$sql 			= $sql.$sqlNomeAluno;	
	}
	if(isset($obj->colegio) && !empty($obj->colegio)){
		$sqlColegio = 'and a.colegio_idcolegio = :colegio ';
		$sql 		= $sql.$sqlColegio; 
	}
	if(isset($obj->periodo) && !empty($obj->periodo)){
		$sqlPeriodo = 'and a.periodo_idperiodo = :periodo ';
		$sql 		= $sql.$sqlPeriodo;
	}
	if(isset($obj->serie) && !empty($obj->serie)){
		$sqlSerie  	= 'and a.serie_idserie = :serie ';
		$sql 		= $sql.$sqlSerie;
	}
	if(isset($obj->linha) && !empty($obj->linha)){
		$sqlLinha 	= 'and a.linha_idlinha = :linha ';
		$sql 		= $sql.$sqlLinha;
	}	
	
	$num = linhasBusca($dbh,$obj);

	if($num > 19){
		$obj->paginacao;
		$obj->linhaPagina;
		$sqlLimit 		= 'LIMIT 20 OFFSET :limit';
		$sql 			= $sql.'ORDER BY nome ASC '.$sqlLimit;
	}else{
		$sql 			= $sql.'ORDER BY nome ASC ';
	}	

	$stmt 			= $dbh->prepare($sql);
	
	if(isset($obj->nomeAluno) && !empty($obj->nomeAluno)){
		$nomeDoAluno 	= '%'.$obj->nomeAluno.'%';
		$stmt->bindParam(':nome',$nomeDoAluno);
	}

	if(isset($obj->colegio) && !empty($obj->colegio)){
		$obj->colegio = (int)$obj->colegio;
		$stmt->bindParam(':colegio',$obj->colegio,PDO::PARAM_INT);
	}

	if(isset($obj->periodo) && !empty($obj->periodo)){
		$stmt->bindParam(':periodo',$obj->periodo);
	}

	if(isset($obj->serie) && !empty($obj->serie)){
		$stmt->bindParam(':serie',$obj->serie);
	}

	if(isset($obj->linha) && !empty($obj->linha)){
		$stmt->bindParam(':linha',$obj->linha);
	}

	//Recurso para Paginação
	if($num > 19){
		if(isset($obj->paginacao) && $obj->paginacao == true){
			if(isset($obj->linhaPagina) && !empty($obj->linhaPagina)){
				$stmt->bindParam(':limit',$obj->linhaPagina,PDO::PARAM_INT);
			}
		}else{
			$vinte = 20;
			$stmt->bindParam(':limit',$vinte,PDO::PARAM_INT);
		}
	}

	$stmt->execute();
	$dados = $stmt->fetchAll(PDO::FETCH_OBJ);
	
	//Variaveis usadas na etapa seguinte
	if(!explode("T",$obj->dtInicial)){
		$dataInicial	= dataFormatada($obj->dtInicial);
		$dataFinal		= dataFormatada($obj->dtFinal, true);
	}else{
		$dataInicial	= $obj->dtInicial;
		$dataFinal		= $obj->dtFinal;
	}
	$arrayDes		= array();
	$aluno 			= new stdClass();

	foreach ($dados as $chave => $valor) {
		$aluno->id  		= $valor->id;
		$aluno->nome 		= $valor->nome;
		$aluno->codigo  	= $valor->codigo;
		$aluno->datainicial = $dataInicial;
		$aluno->dataFinal 	= $dataFinal;

		if(explode("T",$dataFinal)){
			$di		= dataFormatada($dataInicial);
			$df		= dataFormatada($dataFinal, true);
		}else{
			$di		= $dataInicial;
			$df		= $dataFinal;
		}
		
		$res = getRastreio($dbh,$valor->codigo,$di->format('Y-m-d H:i:s'),$df->format('Y-m-d H:i:s'));

		if(is_array($res)){
			$sizeArray = count($res);
			if($sizeArray > 0){
				$aluno->arrayRast = $res[(int)$sizeArray-1];
				if(!empty($aluno->arrayRast->data_pos))
				$aluno->arrayRast->data_pos = formataDataWS($aluno->arrayRast->data_pos);				
			}			
		}else{
			$aluno->arrayRast = $res;
			if(!empty($aluno->arrayRast->DataPosicao))
			$aluno->arrayRast->DataPosicao = formataDataWS($aluno->arrayRast->DataPosicao);
		}

		$arrayDes[] = $aluno;
		$aluno = new stdClass();
	}

	return $arrayDes;
}

function getRastreio($dbh,$cod,$di,$df){//RASTREIO BUSCA
	$sql = 'SELECT * FROM rast WHERE cod = :cod and data_pos BETWEEN :dataInicial AND :dataFinal';

	$stmt = $dbh->prepare($sql);

	$stmt->bindParam(':cod', $cod);
	$stmt->bindParam(':dataInicial', $di);
	$stmt->bindParam(':dataFinal', $df);

	$stmt->execute();
	
	return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getAluno($dbh,$datai,$dataf,$idAluno = null,$linhaPagina = null){

	$sql = 'SELECT 
			a.idaluno as idaluno,
			a.codigoaluno as Codigo,
			a.nomealuno as Nome,
			a.foto as Foto,
			a.turma as Turma,
			a.pai as Pai,
			a.mae as Mae,
			a.endereco as Endereco,
			a.telefone as Telefone,
			a.nasc as DataNascimento,
			l.linha as Linha,
			n.nivel as Nivel,
			s.serie as Serie,
			p.periodo as Periodo,
			c.colegio as Colegio
		FROM
			aluno as a
			left join nivel as n on n.idnivel = a.nivel_idnivel
			left join serie as s on s.idserie = a.serie_idserie
			left join periodo as p on p.idperiodo = a.periodo_idperiodo
			left join colegio as c on c.idcolegio = a.colegio_idcolegio
			left join linha as l on l.idlinha = a.linha_idlinha';
	
	$limit = ' LIMIT 20 OFFSET ?';

	if(isset($idAluno)){
		$sqlComWhereIdAluno = ' where idaluno = ?';
		$sql = $sql.$sqlComWhereIdAluno.' ORDER BY Nome ASC';
	}else{	
		$sql = $sql.' ORDER BY Nome ASC'.$limit;	
	}

	$stmt = $dbh->prepare($sql);

	if(isset($idAluno)){
		$stmt->bindParam(1,$idAluno,PDO::PARAM_INT);
	}else{	
		if(isset($linhaPagina)){
			$stmt->bindParam(1,$linhaPagina,PDO::PARAM_INT);
		}else{
			$vinte = 20;
			$stmt->bindParam(1,$vinte,PDO::PARAM_INT);
		}
	}	

	if(isset($datai)){
		$dataInicial = dataFormatada($datai);
	}
	if(isset($dataf)){
		$dataFinal = dataFormatada($dataf,true);
	}

	$stmt->execute();
	$dados = $stmt->fetchAll(PDO::FETCH_OBJ);	
	
	$dadosResp 	= array();
	$rast 		= array();
	$conteudo 	= array();

	foreach ($dados as $dadosValor) {
		if(!empty($dadosValor->DataNascimento)){
			$strD = explode('-',$dadosValor->DataNascimento);
			$dadosValor->DataNascimento = $strD[2].'/'.$strD[1].'/'.$strD[0];
		}
		//mudar aqui
		if(is_string($dataFinal) && explode("T",$dataFinal)){
			$di		= dataFormatada($dataInicial);
			$df		= dataFormatada($dataFinal, true);
		}else{
			$di		= $dataInicial;
			$df		= $dataFinal;
		}

			
		$dadosValor->rast = getRastreio($dbh,$dadosValor->Codigo,$di->format('Y-m-d H:i:s'),$df->format('Y-m-d H:i:s'));

		/*if(isset($dataFinal) && isset($dataInicial)){
			$dadosValor->rast = buscaWebService((string)((int)$dadosValor->Codigo),$dataInicial,$dataFinal);
		}*/
		
		if(isset($dadosValor->rast)){
			foreach ($dadosValor->rast as $valor) {
				if(!empty($valor->DataPosicao)){
					$valor->DataPosicao = formataDataWS($valor->DataPosicao);
				}
				
			}
			$rast[] = $dadosValor->rast;
		}

		$dadosResp[] = $dadosValor;
	}

	$conteudo['dados'] = $dadosResp;
	$conteudo['rast'] = $rast;
	return $conteudo;
}

function dataFormatada($dt, $hora = null){
	$arrayData = explode('/',$dt);
	$horaString = null;
	$retorno = $arrayData[2].'-'.$arrayData[1].'-'.$arrayData[0];
	if($hora){
		$horaString = "23:59:15.889342";
		$retorno = $arrayData[2].'-'.$arrayData[1].'-'.$arrayData[0].' '.$horaString;
	}
	return new DateTime($retorno);
}

function listaDB($tabela,$idtabela,$limit,$dbh){
	$addLimit = "";
	if($limit){
		$addLimit = " LIMIT 20 ";
	}
	$stmt = $dbh->prepare("select ".$idtabela.",".$tabela." from ".$tabela.$addLimit);
	$stmt->execute();
	$dados = new stdClass();
	while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
		$dados->id[] = $linha->$idtabela;
		$dados->nome[] = $linha->$tabela;
	}
	return $dados;
}

function ordenaDadosDeInsercao($array,$dbh){
	//busca os dados do banco
	$listaColegio = listaDB('colegio','idcolegio',false,$dbh);
	$listaSerie = listaDB('serie','idserie',false,$dbh);
	$listaNivel = listaDB('nivel','idnivel',false,$dbh);
	$listaLinha = listaDB('linha','idlinha',false,$dbh); 
	
	//montando listas
	foreach ($array as $dados) {
		$listaColegio->nome[] = strtoupper(trim(utf8_encode($dados[3])));
		$listaSerie->nome[] = strtoupper(trim(utf8_encode($dados[4])));
		$listaNivel->nome[] = strtoupper(trim(utf8_encode($dados[6])));
		$listaLinha->nome[] = strtoupper(trim(utf8_encode($dados[7])));
	}
	
	//exlcuindo repetidos
	$listaColegio->nome = array_unique($listaColegio->nome);
	$listaSerie->nome = array_unique($listaSerie->nome);
	$listaNivel->nome = array_unique($listaNivel->nome);
	$listaLinha->nome = array_unique($listaLinha->nome);
	
	$idColegio = array();
	$idSerie = array();
	$idNivel = array();
	$idLinha = array();
	$colegio = array();
	$serie = array();
	$nivel = array();
	$linha = array();

	//devolvendo array
	$size = count($array);
	for ($i=0; $i < $size; $i++) { 
		if(empty($listaColegio->id[$i])){
			if(!empty($listaColegio->nome[$i])){
				$colegio[] = $listaColegio->nome[$i];
				$idColegio[] = insereFilho($listaColegio->nome[$i],"colegio","idcolegio","colegio",$dbh);
			}
		}else{
			if(!empty($listaColegio->nome[$i])){
				$colegio[] = $listaColegio->nome[$i];
				$idColegio[] = $listaColegio->id[$i];
			}
		}
	
		if(empty($listaSerie->id[$i])){
			if(!empty($listaSerie->nome[$i])){
				$serie[] = $listaSerie->nome[$i];
				$idSerie[] = insereFilho($listaSerie->nome[$i],"serie","idserie","serie",$dbh);
			}
		}else{
			if(!empty($listaSerie->nome[$i])){
				$serie[] = $listaSerie->nome[$i];
				$idSerie[] = $listaSerie->id[$i];
			}
		}
	
		if(empty($listaNivel->id[$i])){
			if(!empty($listaNivel->nome[$i])){
				$nivel[] = $listaNivel->nome[$i];
				$idNivel[] = insereFilho($listaNivel->nome[$i],"nivel","idnivel","nivel",$dbh);
			}
		}else{
			if(!empty($listaNivel->nome[$i])){
				$nivel[] = $listaNivel->nome[$i];
				$idNivel[] = $listaNivel->id[$i];
			}
		}
	
		if(empty($listaLinha->id[$i])){
			if(!empty($listaLinha->nome[$i])){
				$linha[] = $listaLinha->nome[$i];
				$idLinha[] = insereFilho($listaLinha->nome[$i],"linha","idlinha","linha",$dbh);
			}
		}else{
			if(!empty($listaLinha->nome[$i])){
				$linha[] = $listaLinha->nome[$i];
				$idLinha[] = $listaLinha->id[$i];
			}
		}


	}

	for ($i=0; $i < $size; $i++) {
		if(!empty($array[$i][3])){
			$chave = null;
			$elementoColegio = strtoupper(trim(utf8_encode($array[$i][3])));
			$chave = buscaElementoNoArray($elementoColegio, $colegio);
			if(!empty($chave)){
				$array[$i][3] = !empty($idColegio[$chave-1])?$idColegio[$chave-1]:null;
			}else{
				$array[$i][3] = $chave;
			}
		}
		if(!empty($array[$i][4])){
			$chave = null;
			$elementoSerie = strtoupper(trim(utf8_encode($array[$i][4])));
			$chave = buscaElementoNoArray($elementoSerie, $serie);
			if(!empty($chave)){
				$array[$i][4] = !empty($idSerie[$chave])?$idSerie[$chave]:null;
			}else{
				$array[$i][4] = $chave;
			}
		}
		if(!empty($array[$i][6])){
			$chave = null;
			$elementoNivel = strtoupper(trim(utf8_encode($array[$i][6])));
			$chave = buscaElementoNoArray($elementoNivel, $nivel);
			if(!empty($chave)){
				$array[$i][6] = !empty($idNivel[$chave])?$idNivel[$chave]:null;
			}else{
				$array[$i][6] = $chave;
			}
		}
		if(!empty($array[$i][7])){
			$chave = null;
			$elementoLinha = strtoupper(trim(utf8_encode($array[$i][7])));
			$chave = buscaElementoNoArray($elementoLinha, $linha);
			if(!empty($chave)){
				$array[$i][7] = !empty($idLinha[$chave-1])?$idLinha[$chave-1]:null;
			}else{
				$array[$i][7] = $chave;
			}
		}
	}
	return $array;
}

function buscaElementoNoArray($elemento,$array){
	$i = 0;
	$chave = 0;
	if(in_array($elemento, $array)){			
		foreach ($array as $valor) {
			$i++;
			if($valor == $elemento){
				$chave = $i;
			}
		}
	}
	return $chave;
}

function insereFilho($dadoInsercao,$tabela,$idTabela,$campo,$con){
	$stmt = $con->prepare("insert into ".$tabela." (".$campo.") values (?)");
	$stmt->bindParam(1, $dadoInsercao);
	$stmt->execute();	
	return $con->lastInsertId();
}

function formataDataWS($dataCompleta){
	$dataCompleta = explode(" ",$dataCompleta);
	$data = $dataCompleta[0];
	$data = explode('-',$data);
	return $data[2].'/'.$data[1].'/'.$data[0].' - '.$dataCompleta[1]; 
}

function verificaAlunoInsericoPeloCodigo($codigo,$dbh){
	$stmt = $dbh->prepare("select * from aluno where codigoaluno = ?");
	$stmt->bindParam(1,$codigo);
	$stmt->execute();
	if($stmt->rowCount() > 0){
		return false;
	}
	return true;		
}

function buscaWebService($codigoAcesso, $dataInicio, $dataFinal){

	$dataIni				= $dataInicio->format('c');
	$dataFim				= $dataFinal->format('c');
	$client 				= new SoapClient('http://ws.globalbus.com.br/ServiceBilhetagem.asmx?WSDL'); 
	$functionAutenticar 	= 'Autenticar';
	$function 				= 'RetornaPassagemCartao';

	$argumentsAutenticar	= array(
		array(
			'HSCode'=>"QrQ4!6z@RT"
		)
	);

	$arguments	= array(
		array(
			'CodigoAcesso' 	=>$codigoAcesso,
			'DataInicio' 	=>$dataIni,
			'DataFim' 		=>$dataFim
		)
	);

	$options 	= array(
		'location' => 'http://ws.globalbus.com.br/ServiceBilhetagem.asmx'
	);

	$resultAutenticar 	= $client->__soapCall($functionAutenticar, $argumentsAutenticar, $options);

	$result 			= $client->__soapCall($function, $arguments, $options);

	if(!empty($results->RetornaPassagemCartaoResult->Dado->WSPassagemCartao))
		return $result->RetornaPassagemCartaoResult->Dado->WSPassagemCartao;
	else
		return null;
}

function retornaPeriodo($periodo){
	$idPeriodo = null;
	switch(strtolower($periodo)){
		case "matutino":
			$idPeriodo = 1;
		break;
		case "vespertino":
			$idPeriodo = 2;
		break;
		case "noturno":
			$idPeriodo = 3;
		break;
		case "integral":
			$idPeriodo = 4;
		break;
		default:
			$idPeriodo = null;
		break;
	}
	return $idPeriodo;
}

function buscaGenerica($tabela, $dbh, $limit){
	$textoLimit = '';
	if($limit == true){
		$textoLimit = ' LIMIT 20';
	}
	$stmt = $dbh->prepare("select * from ".$tabela.$textoLimit);
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function limpaString($string){
	$limpar 		= array('{','}');
	$string 		= str_replace($limpar,'',$string);
	$string 		= str_replace('"','',$string);
	$arrayString 	= explode(',',$string);

	$arrayAll 		= array();

	foreach ($arrayString as $aString) {
		$aString 	= explode(':',$aString);

		$arrayAll[] = $aString;
	}

	return $arrayAll;
}

function consultaRelatorio($dbConnect, $array){

	$sql 	= 	'SELECT 
					a.codigoaluno 	as codigo,
					a.nomealuno 	as nome,
					a.foto 			as foto,
					a.nasc 			as nasc,
					l.linha 		as linha,
					n.nivel 		as nivel,
					s.serie 		as serie,
					p.periodo 		as periodo,
					c.colegio 		as colegio
				FROM
					aluno 				as a
					left join nivel 	as n on n.idnivel 	= a.nivel_idnivel
					left join serie 	as s on s.idserie 	= a.serie_idserie
					left join periodo 	as p on p.idperiodo = a.periodo_idperiodo
					left join colegio 	as c on c.idcolegio = a.colegio_idcolegio
					left join linha 	as l on l.idlinha 	= a.linha_idlinha';

	$stringSQL 		= '';
	$dataInicial 	= '';
	$dataFinal 		= '';

	foreach ($array as $valor) {	

		if(!empty($valor[1])){
			switch ($valor[0]) {
				case 'nomeAluno':
					$stringSQL = montaSQL($stringSQL).'a.nomealuno like :nome ';
					break;
				case 'serie':
					$stringSQL = montaSQL($stringSQL).'a.serie_idserie = :serie ';
					break;
				case 'linha':
					$stringSQL = montaSQL($stringSQL).'a.linha_idlinha = :linha ';
					break;
				case 'periodo':
					$stringSQL = montaSQL($stringSQL).'a.periodo_idperiodo = :periodo ';
					break;										
				case 'colegio':
					$stringSQL = montaSQL($stringSQL).'a.colegio_idcolegio = :colegio ';
					break;
				case 'dtInicial':
					$dataInicial = $valor[1];
					break;
				case 'dtFinal':
					$dataFinal = $valor[1];
					break;
			}			
		}
	}

	$sql 	= $sql.$stringSQL;

	$sql = $sql.' ORDER BY nome ASC ';

	$stmt 	= $dbConnect->prepare($sql);

	foreach ($array as $valor) {	
		if(!empty($valor[1])){
			switch ($valor[0]) {
				case 'nomeAluno':
					$nome = '%'.$valor[1].'%';
					$stmt->bindParam(':nome',$nome);
					break;
				case 'serie':
					$stmt->bindParam(':serie',$valor[1]);
					break;
				case 'linha':
					$stmt->bindParam(':linha',$valor[1]);
					break;
				case 'periodo':
					$stmt->bindParam(':periodo',$valor[1]);
					break;										
				case 'colegio':
					$stmt->bindParam(':colegio',$valor[1]);
					break;
			}
		}		
	}

	$stmt->execute();
	
	$dados = $stmt->fetchAll(PDO::FETCH_OBJ);

	$dadosResp	= array();
	$rast 		= array();
	$conteudo	= array();

	if(isset($dataInicial)){
		$dataInicial = dataFormatada($dataInicial);
	}
	if(isset($dataFinal)){
		$dataFinal = dataFormatada($dataFinal);
	}

	$contador = 0;
	
	foreach ($dados as $d) {

		$contador++;

		if(!empty($d->nasc)){
			$strD = explode('-',$d->nasc);
			$d->nasc = $strD[2].'/'.$strD[1].'/'.$strD[0];
		}

		if(isset($dataFinal) && isset($dataInicial)){
			try {
			    $d->rast = getRastreio($dbConnect,$d->codigo,$dataInicial->format('Y-m-d H:i:s'),$dataFinal->format('Y-m-d H:i:s'));
			} catch (Exception $e) {
			    echo 'Estamos enfrentamos problemas na busca de dados no webService de Rastreamento',  $e->getMessage(), "\n";
			}
		}
		
		if(isset($d->rast)){
			if(is_object($d->rast) || is_array($d->rast)){
				foreach ($d->rast as $valor) {
					if(!empty($valor->DataPosicao)){
						$valor->DataPosicao = formataDataWS($valor->DataPosicao);
					}
				}
			}
		}

		$dadosResp[] = $d;
	}

	$conteudo['dados'] = $dadosResp;
	
	return $conteudo;
}


function montaSQL($string){
	if(empty($string)){
		$string = ' WHERE ';
		return $string;
	}

	if(!empty($string)){
		$string = $string.'AND ';
		return $string;	
	}

	return '';
}

function retornaCorrigido($chave){
	switch ($chave) {
		case 'colegio':
			$chave = "Colégio";
			break;
		case 'periodo':
			$chave = "Período";
			break;
		case 'nivel':
			$chave = "Nível";
			break;
		case 'codigo':
			$chave = "Código";
			break;
		case 'nome':
			$chave = "Nome";
			break;
		case 'turma':
			$chave = "Turma";
			break;
		case 'foto':
			$chave = "Foto";
			break;
		case 'pai':
			$chave = "Pai";
			break;
		case 'mae':
			$chave = "Mãe";
			break;
		case 'endereco':
			$chave = "Endereço";
			break;
		case 'telefone':
			$chave = "Telefone";
			break;
		case 'nasc':
			$chave = "Data Nascimento";
			break;
		case 'linha':
			$chave = "Linha";
			break;
		case 'serie':
			$chave = "Série";
			break;			
	}
	return $chave;
}


function enviaEmail($email,$nome,$texto){
	$para 		= 'ademilson_vilalba@hotmail.com';

	$assunto 	= 'Sistema SIMAP contato '.$nome;

	$topo = "From: " . strip_tags($email) . "\r\n";
	$topo .= "MIME-Version: 1.0\r\n";
	$topo .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

	$msg = '<html><body>';
	$msg .= '<h1>'.$texto.'</h1>';
	$msg .= '</body></html>';

	$contato = mail($para, $assunto, $msg, $topo);

	if(!empty($contato)){
		return true;
	}
	return false;
}

function insereAluno($dbh,$res){

	if(verificaRepitido($dbh,$res) > 0){
		return array(
			'msg'=>"Esse aluno já esta na base de dados",
			'cdg'=>'1313'
		);
	}

	$campo 	= "";
	$valor 	= "";

	if(!empty($res->codigoaluno)){
		$campo 	= "codigoaluno,";
		$valor 	= ":codigo,";
	}
	if(!empty($res->nomealuno)){
		$campo 	= $campo . "nomealuno,";
		$valor 	= $valor . ":nome,";
	}
	if(!empty($res->foto)){
		$foto = explode(".",$res->foto);
		$res->foto = $foto[0];
		$campo 	= $campo . "foto,";
		$valor 	= $valor . ":foto,";
	}
	if(!empty($res->cidade)){
		$campo 	= $campo . "cidade_idcidade,";
		$valor 	= $valor . ":cidade,";
	}
	if(!empty($res->turma)){
		$campo 	= $campo . "turma,";
		$valor 	= $valor . ":turma,";
	}
	if(!empty($res->pai)){
		$campo 	= $campo . "pai,";
		$valor 	= $valor . ":pai,";
	}
	if(!empty($res->mae)){
		$campo 	= $campo . "mae,";
		$valor 	= $valor . ":mae,";
	}
	if(!empty($res->endereco)){
		$campo 	= $campo . "endereco,";
		$valor 	= $valor . ":endereco,";
	}
	if(!empty($res->telefone)){
		$campo 	= $campo . "telefone,";
		$valor 	= $valor . ":telefone,";
	}
	if(!empty($res->nasc)){
		$campo 	= $campo . "nasc,";
		$valor 	= $valor . ":nasc,";
	}
	if(!empty($res->nivel)){
		$campo 	= $campo . "nivel_idnivel,";
		$valor 	= $valor . ":nivel,";
	}
	if(!empty($res->periodo)){
		$campo 	= $campo . "periodo_idperiodo,";
		$valor 	= $valor . ":periodo,";
	}
	if(!empty($res->colegio)){
		$campo 	= $campo . "colegio_idcolegio,";
		$valor 	= $valor . ":colegio,";
	}
	if(!empty($res->linha)){
		$campo 	= $campo . "linha_idlinha,";
		$valor 	= $valor . ":linha,";
	}
	if(!empty($res->serie)){
		$campo 	= $campo . "serie_idserie,";
		$valor 	= $valor . ":serie,";
	}

	$campo 	= rtrim($campo, ",");
	$valor 	= rtrim($valor, ",");

	$sqlInsert = "Insert into aluno (".$campo.") values (".$valor.")";


	$stmt	= $dbh->prepare($sqlInsert);
	
	//BINDS
	(!empty($res->codigoaluno))?$stmt->bindParam(':codigo', $res->codigoaluno):null;
	(!empty($res->nomealuno))?$stmt->bindParam(':nome', $res->nomealuno):null;
	(!empty($res->foto))?$stmt->bindParam(':foto', $res->foto):null;
	(!empty($res->cidade))?$stmt->bindParam(':cidade', $res->cidade):null;
	(!empty($res->turma))?$stmt->bindParam(':turma', $res->turma):null;
	(!empty($res->pai))?$stmt->bindParam(':pai', $res->pai):null;	
	(!empty($res->mae))?$stmt->bindParam(':mae', $res->mae):null;
	(!empty($res->endereco))?$stmt->bindParam(':endereco', $res->endereco):null;
	(!empty($res->telefone))?$stmt->bindParam(':telefone', $res->telefone):null;	
	(!empty($res->nasc))?$stmt->bindParam(':nasc', $res->nasc):null;	
	(!empty($res->nivel))?$stmt->bindParam(':nivel', $res->nivel):null;
	(!empty($res->periodo))?$stmt->bindParam(':periodo', $res->periodo):null;
	(!empty($res->colegio))?$stmt->bindParam(':colegio', $res->colegio):null;
	(!empty($res->linha))?$stmt->bindParam(':linha', $res->linha):null;
	(!empty($res->serie))?$stmt->bindParam(':serie', $res->serie):null;

	$stmt->execute();	
	return $dbh->lastInsertId();
}

function copiaImagem($file){
	$imagem 			= $file;
	$caminhoAntigo  	= $imagem['file']['tmp_name'];
	$caminhoNovo 		= '../fotos/'.$imagem['file']['name'];
	$nome 				= $imagem['file']['name'];

	$copiado = move_uploaded_file($caminhoAntigo,$caminhoNovo);

	if($copiado){
		return array(
			'msg'=>'Sucesso',
			'nome'=>$nome
		);
	}else{
		return array(
			'msg'=>"Erro ao Copiar Imagem Para a Pasta",
			'cdg'=>'1212'
		);
	}
}

function getAlunoInd($con, $res){
	$where = "";

	(!empty($res->codigoaluno) && isset($res->codigoaluno))?$where .= " a.codigoaluno = :codigo and ":null;
	(!empty($res->nomealuno) && isset($res->nomealuno))?$where .= " a.nomealuno like :nome and ":null;
	(!empty($res->foto) && isset($res->foto))?$where .= " a.foto = :foto and ":null;
	(!empty($res->cidade) && isset($res->cidade))?$where .= " a.cidade_idcidade = :cidade and ":null;
	(!empty($res->turma) && isset($res->turma))?$where .= " a.turma = :turma and ":null;
	(!empty($res->pai) && isset($res->pai))?$where .= " a.pai = :pai and ":null;
	(!empty($res->mae) && isset($res->mae))?$where .= " a.mae = :mae and ":null;
	(!empty($res->endereco) && isset($res->endereco))?$where .= " a.endereco = :endereco and ":null;
	(!empty($res->telefone) && isset($res->telefone))?$where .= " a.telefone = :telefone and ":null;
	(!empty($res->nasc) && isset($res->nasc))?$where .= " a.nasc = :nasc and ":null;
	(!empty($res->nivel) && isset($res->nivel))?$where .= " a.nivel_idnivel = :nivel and ":null;
	(!empty($res->periodo) && isset($res->periodo))?$where .= " a.periodo_idperiodo = :periodo and ":null;
	(!empty($res->colegio) && isset($res->colegio))?$where .= " a.colegio_idcolegio = :colegio and ":null;
	(!empty($res->linha) && isset($res->linha))?$where .= " a.linha_idlinha = :linha and ":null;
	(!empty($res->serie) && isset($res->serie))?$where .= " a.serie_idserie = :serie ":null;

	$where 	= rtrim($where);
	$where 	= rtrim($where, "and");
	$where 	= rtrim($where);
	
	$sql = "Select
				a.idaluno,
				a.nomealuno,
				c.colegio
			from
				aluno a
				left join colegio c on a.colegio_idcolegio = c.idcolegio
			where ".$where;
	$stmt	= $con->prepare($sql);

	//BINDS
	(!empty($res->codigoaluno) && isset($res->codigoaluno))?$stmt->bindParam(':codigo', $res->codigoaluno):null;
	if(!empty($res->nomealuno) && isset($res->nomealuno)){
		$nomeLike = $res->nomealuno."%";
		$stmt->bindParam(':nome', $nomeLike);
	}
	(!empty($res->foto) && isset($res->foto))?$stmt->bindParam(':foto', $res->foto):null;
	(!empty($res->cidade) && isset($res->cidade))?$stmt->bindParam(':cidade', $res->cidade):null;
	(!empty($res->turma) && isset($res->turma))?$stmt->bindParam(':turma', $res->turma):null;
	(!empty($res->pai) && isset($res->pai))?$stmt->bindParam(':pai', $res->pai):null;	
	(!empty($res->mae) && isset($res->mae))?$stmt->bindParam(':mae', $res->mae):null;
	(!empty($res->endereco) && isset($res->endereco))?$stmt->bindParam(':endereco', $res->endereco):null;
	(!empty($res->telefone) && isset($res->telefone))?$stmt->bindParam(':telefone', $res->telefone):null;	
	(!empty($res->nasc) && isset($res->nasc))?$stmt->bindParam(':nasc', $res->nasc):null;	
	(!empty($res->nivel) && isset($res->nivel))?$stmt->bindParam(':nivel', $res->nivel):null;
	(!empty($res->periodo) && isset($res->periodo))?$stmt->bindParam(':periodo', $res->periodo):null;
	(!empty($res->colegio) && isset($res->colegio))?$stmt->bindParam(':colegio', $res->colegio):null;
	(!empty($res->linha) && isset($res->linha))?$stmt->bindParam(':linha', $res->linha):null;
	(!empty($res->serie) && isset($res->serie))?$stmt->bindParam(':serie', $res->serie):null;

	$stmt->execute();
	
	$dados = $stmt->fetchAll(PDO::FETCH_OBJ);

	if(!empty($dados) && isset($dados)){
		return array(
			'msg'=>"Sucesso",
			'dados'=>$dados
		);
	}else{
		return array(
			'msg'=>"Erro buscar aluno",
			'cdg'=>'1414'
		);
	}
}

function verificaRepitido($dbh,$res){	

	$where = "";
	(!empty($res->codigoaluno))?$where .= " codigoaluno = :codigo and ":null;
	(!empty($res->nomealuno))?$where .= " nomealuno = :nome and ":null;
	(!empty($res->foto))?$where .= " foto = :foto and ":null;
	(!empty($res->cidade))?$where .= " cidade_idcidade = :cidade and ":null;
	(!empty($res->turma))?$where .= " turma = :turma and ":null;
	(!empty($res->pai))?$where .= " pai = :pai and ":null;
	(!empty($res->mae))?$where .= " mae = :mae and ":null;
	(!empty($res->endereco))?$where .= " endereco = :endereco and ":null;
	(!empty($res->telefone))?$where .= " telefone = :telefone and ":null;
	(!empty($res->nasc))?$where .= " nasc = :nasc and ":null;
	(!empty($res->nivel))?$where .= " nivel_idnivel = :nivel and ":null;
	(!empty($res->periodo))?$where .= " periodo_idperiodo = :periodo and ":null;
	(!empty($res->colegio))?$where .= " colegio_idcolegio = :colegio and ":null;
	(!empty($res->linha))?$where .= " linha_idlinha = :linha and ":null;
	(!empty($res->serie))?$where .= " serie_idserie = :serie ":null;

	$where 	= rtrim($where);
	$where 	= rtrim($where, "and");
	$where 	= rtrim($where);
	
	$sql = 'SELECT count(idaluno) num FROM aluno WHERE '.$where;

	$stmt = $dbh->prepare($sql);

	//BINDS
	(!empty($res->codigoaluno))?$stmt->bindParam(':codigo', $res->codigoaluno):null;
	(!empty($res->nomealuno))?$stmt->bindParam(':nome', $res->nomealuno):null;
	(!empty($res->foto))?$stmt->bindParam(':foto', $res->foto):null;
	(!empty($res->cidade))?$stmt->bindParam(':cidade', $res->cidade):null;
	(!empty($res->turma))?$stmt->bindParam(':turma', $res->turma):null;
	(!empty($res->pai))?$stmt->bindParam(':pai', $res->pai):null;	
	(!empty($res->mae))?$stmt->bindParam(':mae', $reenderecos->mae):null;
	(!empty($res->endereco))?$stmt->bindParam(':', $res->endereco):null;
	(!empty($res->telefone))?$stmt->bindParam(':telefone', $res->telefone):null;	
	(!empty($res->nasc))?$stmt->bindParam(':nasc', $res->nasc):null;	
	(!empty($res->nivel))?$stmt->bindParam(':nivel', $res->nivel):null;
	(!empty($res->periodo))?$stmt->bindParam(':periodo', $res->periodo):null;
	(!empty($res->colegio))?$stmt->bindParam(':colegio', $res->colegio):null;
	(!empty($res->linha))?$stmt->bindParam(':linha', $res->linha):null;
	(!empty($res->serie))?$stmt->bindParam(':serie', $res->serie):null;

	$stmt->execute();
	$dados = $stmt->fetchAll(PDO::FETCH_OBJ);
	return $dados[0]->num;
}

				
function getAlunoSemData($dbh,$res){

	$idAluno 		= null;
	$linhaPagina 	= null;

	if(isset($res->idaluno)){
		$idAluno = $res->idaluno;
	}

	if(isset($res->linhaPagina)){
		$linhaPagina = $res->linhaPagina;
	}

	$sql = 'SELECT 
			a.idaluno as idaluno,
			a.codigoaluno as Codigo,
			a.nomealuno as Nome,
			a.foto as Foto,
			a.turma as Turma,
			a.pai as Pai,
			a.mae as Mae,
			a.endereco as Endereco,
			a.telefone as Telefone,
			a.nasc as DataNascimento,
			l.linha as Linha,
			n.nivel as Nivel,
			s.serie as Serie,
			p.periodo as Periodo,
			c.colegio as Colegio
		FROM
			aluno as a
			left join nivel as n on n.idnivel = a.nivel_idnivel
			left join serie as s on s.idserie = a.serie_idserie
			left join periodo as p on p.idperiodo = a.periodo_idperiodo
			left join colegio as c on c.idcolegio = a.colegio_idcolegio
			left join linha as l on l.idlinha = a.linha_idlinha';
	
	$limit = ' LIMIT 20 OFFSET ?';

	if(isset($idAluno)){
		$sqlComWhereIdAluno = ' where idaluno = ?';
		$sql = $sql.$sqlComWhereIdAluno.' ORDER BY nome ASC ';
	}else{	
		$sql = $sql.' ORDER BY nome ASC '.$limit;	
	}

	$stmt = $dbh->prepare($sql);

	if(isset($idAluno)){
		$stmt->bindParam(1,$idAluno,PDO::PARAM_INT);
	}else{	
		if(isset($linhaPagina)){
			$stmt->bindParam(1,$linhaPagina,PDO::PARAM_INT);
		}else{
			$vinte = 20;
			$stmt->bindParam(1,$vinte,PDO::PARAM_INT);
		}
	}	

	$stmt->execute();
	$dados = $stmt->fetchAll(PDO::FETCH_OBJ);	
	
	$dadosResp = array();
	$conteudo = array();

	foreach ($dados as $dadosValor) {
		if(!empty($dadosValor->DataNascimento)){
			$strD = explode('-',$dadosValor->DataNascimento);
			$dadosValor->DataNascimento = $strD[2].'/'.$strD[1].'/'.$strD[0];
		}

		$dadosResp[] = $dadosValor;
	}

	$conteudo['dados'] = $dadosResp;
	
	return array('dados'=>$conteudo['dados'], 'linha'=>isset($linhaPagina)?$linhaPagina:null);							
}

function deleteAluno($dbh,$res){
	if(!empty($res->id)){
		$id = $res->id;
		$stmt = $dbh->prepare("delete from aluno where idaluno = :id");
		$stmt->bindValue(':id', $id);
		$stmt->execute();
		return array('msg'=>"Sucesso");		
	}
	return array('msg'=>"Erro ao deletar Aluno");
}

function editaAluno($dbh,$res){
	$idAluno 	= $res->id;
	$res 		= $res->aluno;


	$set = "";

	(!empty($res->codigoaluno))?$set .= " codigoaluno = :codigo , ":null;
	(!empty($res->nomealuno))?$set .= " nomealuno = :nome , ":null;
	(!empty($res->foto))?$set .= " foto = :foto , ":null;
	(!empty($res->cidade))?$set .= " cidade_idcidade = :cidade , ":null;
	(!empty($res->turma))?$set .= " turma = :turma , ":null;
	(!empty($res->pai))?$set .= " pai = :pai , ":null;
	(!empty($res->mae))?$set .= " mae = :mae , ":null;
	(!empty($res->endereco))?$set .= " endereco = :endereco , ":null;
	(!empty($res->telefone))?$set .= " telefone = :telefone , ":null;
	(!empty($res->nasc))?$set .= " nasc = :nasc , ":null;
	(!empty($res->nivel))?$set .= " nivel_idnivel = :nivel , ":null;
	(!empty($res->periodo))?$set .= " periodo_idperiodo = :periodo , ":null;
	(!empty($res->colegio))?$set .= " colegio_idcolegio = :colegio , ":null;
	(!empty($res->linha))?$set .= " linha_idlinha = :linha , ":null;
	(!empty($res->serie))?$set .= " serie_idserie = :serie ":null;

	$set 	= rtrim($set);
	$set 	= rtrim($set, ",");
	$set 	= rtrim($set);
	
	$sql = 'UPDATE aluno set'.$set.' WHERE idaluno = '.$idAluno;

	$stmt = $dbh->prepare($sql);

	//BINDS
	(!empty($res->codigoaluno))?$stmt->bindParam(':codigo', $res->codigoaluno):null;
	(!empty($res->nomealuno))?$stmt->bindParam(':nome', $res->nomealuno):null;
	(!empty($res->foto))?$stmt->bindParam(':foto', $res->foto):null;
	(!empty($res->cidade))?$stmt->bindParam(':cidade', $res->cidade):null;
	(!empty($res->turma))?$stmt->bindParam(':turma', $res->turma):null;
	(!empty($res->pai))?$stmt->bindParam(':pai', $res->pai):null;	
	(!empty($res->mae))?$stmt->bindParam(':mae', $reenderecos->mae):null;
	(!empty($res->endereco))?$stmt->bindParam(':', $res->endereco):null;
	(!empty($res->telefone))?$stmt->bindParam(':telefone', $res->telefone):null;	
	(!empty($res->nasc))?$stmt->bindParam(':nasc', $res->nasc):null;	
	(!empty($res->nivel))?$stmt->bindParam(':nivel', $res->nivel):null;
	(!empty($res->periodo))?$stmt->bindParam(':periodo', $res->periodo):null;
	(!empty($res->colegio))?$stmt->bindParam(':colegio', $res->colegio):null;
	(!empty($res->linha))?$stmt->bindParam(':linha', $res->linha):null;
	(!empty($res->serie))?$stmt->bindParam(':serie', $res->serie):null;

	$ok = $stmt->execute();
	
	return $ok;
}

function getWebServiceNovoModo($cod,$di,$df){
	$client 			= new SoapClient('http://ws.globalbus.com.br/ServiceBilhetagem.asmx?WSDL');
	$resultAutenticar 	= $client->__soapCall(
		'Autenticar',
		array(array('HSCode'=>"QrQ4!6z@RT")),
		array('location' => 'http://ws.globalbus.com.br/ServiceBilhetagem.asmx')
	);

	$result = $client->__soapCall(
		'RetornaPassagemCartao',
		array(
			array(
				'CodigoAcesso' 	=>(string)$cod,
				'DataInicio' 	=>$di->format('c'),
				'DataFim' 		=>$df->format('c')
			)
		),
		array('location' => 'http://ws.globalbus.com.br/ServiceBilhetagem.asmx')
	);

	if(empty($result->RetornaPassagemCartaoResult->Dado->WSPassagemCartao)){
		return false;
	}

	return $result->RetornaPassagemCartaoResult->Dado->WSPassagemCartao;
}

function populaTabelaRastreio($dbh, $di){
	$di = new DateTime($di.' 00:00:00.000000');
	
	$df = getDataBanco($dbh);
	$df = new DateTime($df->dt);

	$codAluno = getCodAluno($dbh);

	foreach ($codAluno as $val) {
		if(is_numeric($val->cod)){
			$res = getWebServiceNovoModo($val->cod,$di,$df);
			if(!empty($res)){
				foreach ($res as $r) {
					$dadosGetRast = getRast($dbh,$val->cod,$r->DataPosicao);
					if(empty($dadosGetRast)){
						insereRastreio($dbh,$val->cod,$r);
					}
				}
			}
		}
	}
}

function getCodAluno($dbh){
	$sql = 'SELECT codigoaluno cod FROM aluno';

	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getDataBanco($dbh){
	$sql = 'select now() as dt';

	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getRast($dbh,$cod,$dataPos){
	$sql = 'SELECT * FROM rast WHERE cod = :cod and data_pos = :dataPos';

	$stmt = $dbh->prepare($sql);
	$stmt->bindParam(':cod', $cod);
	$stmt->bindParam(':dataPos', $dataPos);
	$stmt->execute();
	return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function insereRastreio($dbh,$cod,$rast){
		
	$sqlInsert 	= 'insert into rast 
					(cod,data_pos,desc_vei,longi,lat)
					values
					(:cod,:data,:desc,:longi,:lat)';

	$stmt = $dbh->prepare($sqlInsert);

	$stmt->bindParam(':cod', $cod);
	$stmt->bindParam(':data', $rast->DataPosicao);
	$stmt->bindParam(':desc', $rast->DescricaoVeiculo);
	$stmt->bindParam(':longi', $rast->Longitude);
	$stmt->bindParam(':lat', $rast->Latitude);

	$ok = $stmt->execute();
	
	return $ok;
}