crudSisAdm.controller('telaPrincipal', function($scope,$http,Upload){

	$scope.isCollapsedNews 		= false;
	$scope.isCollapsedPromocao 	= true;
	$scope.imagemNoticia		= '';
	$scope.msgImg 				= 'Status da Imagem';
	$scope.listaNoticia 		= [];
	$scope.idNoticia 			= null;
	$scope.ctListagem 			= [];
	$scope.ctNoticia 			= null;
	$scope.categoriaNoticia 	= [];

	

	$scope.limpa = function(){
		$scope.imagemNoticia		= '';
		$scope.msgImg 				= 'Status da Imagem';
		$scope.listaNoticia 		= [];
		$scope.idNoticia 			= null;
		$scope.ctListagem 			= [];
		$scope.ctNoticia 			= null;
		$scope.categoriaNoticia 	= [];
		$scope.listaCategoriasNoticia = [
			{ct:'1',nome:'Mundo',ck:''},
			{ct:'2',nome:'Geral',ck:''},
			{ct:'3',nome:'Famosos',ck:''},
			{ct:'4',nome:'Esporte',ck:''},
			{ct:'5',nome:'Policial',ck:''},
			{ct:'6',nome:'Política',ck:''},
			{ct:'7',nome:'Saúde',ck:''},
			{ct:'8',nome:'Tecnologia',ck:''},
			{ct:'9',nome:'Informe Publicitário',ck:''}
		];
		$scope.categoriaListagem = [
			{ct:'1',nome:'Destaque',ck:''},
			{ct:'2',nome:'Sub-capa',ck:''},
			{ct:'3',nome:'Central 01',ck:''},
			{ct:'4',nome:'Central 02',ck:''},
			{ct:'5',nome:'Central 03',ck:''}
		];
		$scope.titulo = '';
		$scope.subtitulo = '';
		$scope.texto = '';
		$scope.categoriaNoticia = new Array($scope.listaCategoriasNoticia.length);
		$scope.categoriaList = new Array($scope.categoriaListagem.length);
	}

	$scope.dtAtivacao = new Date();

	$scope.listaCategoriasNoticia = [
		{ct:'1',nome:'Mundo',ck:''},
		{ct:'2',nome:'Geral',ck:''},
		{ct:'3',nome:'Famosos',ck:''},
		{ct:'4',nome:'Esporte',ck:''},
		{ct:'5',nome:'Policial',ck:''},
		{ct:'6',nome:'Política',ck:''},
		{ct:'7',nome:'Saúde',ck:''},
		{ct:'8',nome:'Tecnologia',ck:''},
		{ct:'9',nome:'Informe Publicitário',ck:''}
	];

	$scope.categoriaListagem = [
		{ct:'1',nome:'Destaque',ck:''},
		{ct:'2',nome:'Sub-capa',ck:''},
		{ct:'3',nome:'Central 01',ck:''},
		{ct:'4',nome:'Central 02',ck:''},
		{ct:'5',nome:'Central 03',ck:''}
	];

	$scope.linkNoticia = {
		status:"sem link",
		link:""
	};

	$scope.categoriaNoticia = new Array($scope.listaCategoriasNoticia.length);
	$scope.categoriaList = new Array($scope.categoriaListagem.length);

	$scope.getCKList = function(){
		$scope.ctListagem = [];		
		angular.forEach($scope.categoriaListagem, function(value,key){
			if($scope.categoriaList[key]){
				$scope.ctListagem.push(
					{'categoria': value.ct}
				);
			}else if(value.ck == true){
				$scope.ctListagem.push(
					{'categoria': value.ct}
				);
			}
		});
	}

	$scope.verificaLogin = function(){

		var res = $http({
			method  : 'POST',
			url     : '../php/json.php',
			data    : {'funcao':'verificaLogin'},
			headers : {"Content-Type": "application/json; charset=utf-8"}
		});

		res.then(function(data, status, headers, config) {
			if(data.data != 1){
				window.location.href='index.html';
			}
		});

		res.catch(function(data, status) {
			$scope.status = status;
			alert('Status de ERRO: '+status);
		});

		return;
	}

	$scope.desloga = function(){

		if(!confirm("Tem certeza que quer sair?")){
			return false;
		}

		var res = $http({
			method  : 'POST',
			url     : '../php/json.php',
			data    : {'funcao':'desloga'},
			headers : {"Content-Type": "application/json; charset=utf-8"}
		});

		res.then(function(data, status, headers, config) {
			if(data.data == 1){
				window.location.href='index.html';
			}
		});

		res.catch(function(data, status) {
			$scope.status = status;
			alert('Status de ERRO: '+status);
		});

		return;
	};

	$scope.upload = function (file) {

		if(!file){
			alert('insira uma imagem');
			return false;
		}

		Upload.upload({
			url: '../php/file.php',
			data: {file: file, 'funcao': 'uploadAdminImgNoticia'}
		}).then(function (resp) {
			if(resp.data){
				$scope.imagemNoticia = resp.data;
				$scope.msgImg = 'Imagem Carregada';
			}
		}, function (resp) {
			console.log('Error status: ' + resp.status);
		}, function (evt) {
			var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
			console.log('progress: ' + progressPercentage + '% ' + evt.config.data.file.name);
		});
	};

	$scope.getCK = function(){
		angular.forEach($scope.listaCategoriasNoticia, function(value,key){
			if($scope.categoriaNoticia[key]){
				$scope.ctNoticia = value.ct;
			}
		});
	}

	$scope.salvaNoticia = function(){
		$scope.getCK();
		$scope.getCKList();

		if(!$scope.imagemNoticia){
			alert('insira uma imagem');
			return false;
		}

		if(!$scope.ctListagem.length){
			alert('Por favor informe novamente a Categoria de Listagem da Noticia');
			return false;
		}

		if(!$scope.ctNoticia){
			alert('Por favor informe novamente a Categoria de Noticia');
			return false;
		}

		var dados = {
			'nomeImgUP' :$scope.imagemNoticia,
			'idNoticia'	:$scope.idNoticia,
			'titulo' 	:$scope.titulo,
			'funcao' 	:'insereNoticia',
			'texto' 	:$scope.texto,
			'ctNoticia' :$scope.ctNoticia,
			'ctListagem':$scope.ctListagem,
			'dtAtivacao':$scope.dtAtivacao,
			'subtitulo'	:$scope.subtitulo
		};

		var res = $http({
			method  : 'POST',
			url     : '../php/noticia.php',
			data    : dados,
			headers : {"Content-Type": "application/json; charset=utf-8"}
		});

		res.then(function(data, status, headers, config) {
			if(data.data.resposta){
				$scope.msgImg = 'Imagem Salva';
				$scope.getNoticia(data.id_noticia);
				$scope.limpa();
			}
		});

		res.catch(function(data, status) {
			$scope.status = status;
			alert('Status de ERRO: '+status);
		});
	}

	$scope.getNoticia = function(id){

		var dados = {
			'funcao':'getNoticia',
			'id_noticia':id
		};

		var res = $http({
			method	: 'POST',
			url 	: '../php/noticia.php',
			data 	: dados,
			headers : {"Content-Type": "application/json; charset=utf-8"}
		})

		res.then(function(data, status, headers, config) {
			$scope.listaNoticia = [];
			if(data.data.resposta){
				angular.forEach(data.data.dados, function(value,key){
					if(value.link_noticia){
						$scope.linkNoticia = {
							status:"Clique aqui",
							link:"http://www.tvonmidia.com.br/#/veja/"+value.link_noticia
						};
					}else{
						$scope.linkNoticia = {
							status:"sem link",
							link:"#"
						};
					}

					$scope.listaNoticia.push(
						{
							'id': value.id_noticia,
							'texto': value.texto,
							'titulo': value.titulo,
							'subtitulo': value.subtitulo,
							'img': value.img,
							'dt_publicacao':value.dt_publicacao,
							'dtAtivacao':value.dt_ativacao,
							'ct_noticia':value.ct_noticia,
							'ct_listagem':value.ctListagem,
							'link_noticia': $scope.linkNoticia
						}
					);
				});
			}else{
				alert('erro ao capturar busca inicial no');
			}
		});

		res.catch(function(data, status) {
			console.error('Erro Get Noticia', response.status, response.data);
		});
	}

	$scope.editarNoticia = function(id){
		angular.forEach($scope.listaNoticia,function(value,key){
			if(value.id == id){
				if(value.img){
					$scope.imagemNoticia = value.img;
					$scope.msgImg = 'Imagem Carregada';
				}
				$scope.texto 		= value.texto;
				$scope.titulo 		= value.titulo;
				$scope.idNoticia 	= id;
				$scope.subtitulo 	= value.subtitulo;

				$scope.dtAtivacao = '';
				console.log(new Date(value.dtAtivacao));
				$scope.dtAtivacao 	= new Date(value.dtAtivacao);
				
				if(value.ct_listagem){
					$scope.setCTListagem(value.ct_listagem);
				}
				if(value.ct_noticia){
					$scope.setCTNoticia(value.ct_noticia);
				}
			}
		});
	}

	$scope.setCTNoticia = function($ct){
		angular.forEach($scope.listaCategoriasNoticia, function(valor,chave){
			if(valor.ct==$ct){
				valor.ck = true;
			}
		});
	}

	$scope.setCTListagem = function($ct){
		angular.forEach($ct, function(v,c){
			angular.forEach($scope.categoriaListagem, function(valor,chave){
				if(valor.ct==v.id_ct_list){
					valor.ck = true;
				}
			});
		});
	}

	$scope.excluirNoticia = function(id){
		var dados = {
			'funcao':'excluirNoticia',
			'id_noticia':id
		};

		var res = $http({
			method	: 'POST',
			url 	: '../php/noticia.php',
			data 	: dados,
			headers : {"Content-Type": "application/json; charset=utf-8"}
		})

		res.then(function(data, status, headers, config) {
			if(data.data.resposta){
				$scope.limpa();
				$scope.getNoticia(id);
			}
		});

		res.catch(function(data, status) {
			console.error('Erro Get Noticia', response.status, response.data);
		});
	}

});