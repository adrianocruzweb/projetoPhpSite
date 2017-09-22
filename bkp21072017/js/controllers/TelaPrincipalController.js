crudSis.controller('telaPrincipal', function($scope,$http,Upload){

	$scope.isCollapsedNews 		= true;
	$scope.isCollapsedPromocao 	= true;
	$scope.imagemNoticia		= '';
	$scope.msgImg 				= 'Status da Imagem';
	$scope.listaNoticia 		= [];
	$scope.idNoticia 			= null;

	$scope.limpa = function(){
		$scope.imagemNoticia		= '';
		$scope.msgImg 				= 'Status da Imagem';
		$scope.listaNoticia 		= [];
		$scope.idNoticia 			= null;
	}

	$scope.verificaLogin = function(){

		$http({
			method  : 'POST',
			url     : '../php/json.php',
			data    : {'funcao':'verificaLogin'},
			headers : {"Content-Type": "application/json; charset=utf-8"}
		}).success(function(data, status, headers, config) {
			if(data != 1){
				window.location.href='index.html';
			}			
		}).error(function(data, status, headers, config) {
			$scope.status = status;
			alert('Status de ERRO: '+status);
		});	

		return;
	}

	$scope.desloga = function(){

		if(!confirm("Tem certeza que quer sair?")){
			return false;
		}

    	$http({
			method  : 'POST',
			url     : '../php/json.php',
			data    : {'funcao':'desloga'},
			headers : {"Content-Type": "application/json; charset=utf-8"}
		}).success(function(data, status, headers, config) {
			if(data == 1){
				window.location.href='index.html';
			}
		}).error(function(data, status, headers, config) {
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

    $scope.salvaNoticia = function(){
    	
    	if(!$scope.titulo){
    		alert('informe o titulo da notícia');
    		return false;
    	}

    	if(!$scope.texto){
    		alert('informe o texto da notícia');
    		return false;
    	}

    	if(!$scope.imagemNoticia){
    		alert('insira uma imagem');
    		return false;
    	}

    	var dados = {
    		'nomeImgUP' :$scope.imagemNoticia,
    		'idNoticia'	:$scope.idNoticia,
    		'destaque' 	:$scope.destaque,
    		'titulo' 	:$scope.titulo,
    		'funcao' 	:'insereNoticia',
    		'texto' 	:$scope.texto
    	};

    	$http({
			method  : 'POST',
			url     : '../php/noticia.php',
			data    : dados,
			headers : {"Content-Type": "application/json; charset=utf-8"}
		}).success(function(data, status, headers, config) {	
			if(data.resposta){
				$scope.msgImg = 'Imagem Salva';
				$scope.getNoticia(data.id_noticia);
			}
		}).error(function(data, status, headers, config) {
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
					$scope.listaNoticia.push(
						{
							'id': value.id_noticia,
							'texto': value.texto,
							'titulo': value.titulo,
							'destaque': value.destaque,
							'img': value.img,
							'dt_publicacao': value.dt_publicacao
						}
					);
				});			
			}else{
				alert('erro ao capturar busca inicial');
			}
		});

		res.catch(function(data, status) {
			console.error('Erro Get Noticia', response.status, response.data);
		});
    }

    $scope.editarNoticia = function(id){
    	angular.forEach($scope.listaNoticia,function(value,key){
    		if(value.id == id){
    			$scope.titulo = value.titulo;
    			$scope.texto = value.texto;
    			if(value.destaque){
    				$scope.destaque = true;
    			}
    			$scope.idNoticia = id;
    			$scope.file = null;
    		}
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