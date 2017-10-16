crudSis.controller('noticiaCtrl', function($location,$scope,$http,$uibModal,$log,$document){


	$scope.activetab = $location.path();

	$scope.paraLoop = true;

	$scope.listaNoticia 		= [];
	$scope.listaNoticiaDestaque	= [];
	$scope.listaNoticiaSub		= [];
	$scope.listaNoticiaUm		= [];
	$scope.listaNoticiaDois		= [];
	$scope.listaNoticiaTres		= [];
	$scope.idNoticia 			= null;
	$scope.conteudoNoticia		= null;

	$scope.slickConfig = {
	    enabled: true,
	    autoplay: false,
	    draggable: false,
	    //infinite: false,
	    autoplaySpeed: 7000,
	    method: {},
	    event: {
	        beforeChange: function (event, slick, currentSlide, nextSlide) {
	        },
	        afterChange: function (event, slick, currentSlide, nextSlide) {
	        }
	    }
	};	

 	$scope.list = [];

	$scope.submit = function() {
		if ($scope.msg) {
			dadosFormContato = {
				'funcao':'enviaContato',
				'nome':$scope.nome,
				'email':$scope.email,
				'telefone':$scope.telefone,
				'mensagem':$scope.msg
			};

			var res = $http({
				method	: 'POST',
				url 	: 'php/contato.php',
				data 	: dadosFormContato,
				headers : {"Content-Type": "application/json; charset=utf-8"}
			});

			res.then(function(data, status, headers, config) {
				console.log(data);
				if(data.data.resposta){
					
				
				}else{
					console.log("Erro ao enviar contato");
				}
			});

			res.catch(function(data, status) {
				console.error('Erro Envio Contato', response.status, response.data);
			});			
		}
	};

	/*$scope.getNoticia = function(){

		var dados = {
			'funcao':'getNoticia'
		};

		var res = $http({
			method	: 'POST',
			url 	: 'php/noticia.php',
			data 	: dados,
			headers : {"Content-Type": "application/json; charset=utf-8"}
		});

		res.then(function(data, status, headers, config) {
			$scope.listaNoticia = [];
			if(data.data.resposta){
				angular.forEach(data.data.dados, function(value,key){
					$scope.listaNoticia.push(
						{
							'id': value.id_noticia,
							'subtitulo':value.subtitulo,
							'texto':value.texto,
							'titulo': $scope.resolveTitulo(value.titulo),
							'img': "nimg/"+value.img,
							'ct_noticia':$scope.ct(value.ct_noticia),
							'ct_listagem':value.ctListagem,
							'dt_ativacao':value.dt_ativacao,
							'dt_publicacao':$scope.limpaData(value.dt_publicacao),
							'agora':value.agora
						}
					);
				});
				$scope.organizaLista();
			}else{
				alert('erro ao capturar busca inicial');
			}
		});

		res.catch(function(data, status) {
			console.error('Erro Get Noticia', response.status, response.data);
		});
	}*/

	$scope.getNoticiaDestaque = function(){

		var dados = {
			'funcao':'getNoticiaDestaque'
		};

		var res = $http({
			method	: 'POST',
			url 	: 'php/noticia.php',
			data 	: dados,
			headers : {"Content-Type": "application/json; charset=utf-8"}
		});

		res.then(function(data, status, headers, config) {
			$scope.listaNoticiaDestaque = [];
			if(data.data){
				
				angular.forEach(data.data, function(value,key){
					$scope.listaNoticiaDestaque.push(value);
				});

				/*$scope.listaNoticiaDestaque = $scope.listaNoticia[0];
				$scope.listaNoticiaSub 		= $scope.listaNoticia[1];
				$scope.listaNoticiaUm 		= $scope.listaNoticia[2];
				$scope.listaNoticiaDois 	= $scope.listaNoticia[3];
				$scope.listaNoticiaTres 	= $scope.listaNoticia[4];*/
			}else{
				alert('erro ao capturar busca inicial');
			}
		});

		res.catch(function(data, status) {
			console.error('Erro Get Noticia', response.status, response.data);
		});
	}

	$scope.getNoticiaSub = function(){

		var dados = {
			'funcao':'getNoticiaSub'
		};

		var res = $http({
			method	: 'POST',
			url 	: 'php/noticia.php',
			data 	: dados,
			headers : {"Content-Type": "application/json; charset=utf-8"}
		});

		res.then(function(data, status, headers, config) {
			$scope.listaNoticiaSub = [];
			if(data.data){
				angular.forEach(data.data, function(value,key){
					$scope.listaNoticiaSub.push(value);
				});
			}else{
				alert('erro ao capturar busca inicial');
			}
		});

		res.catch(function(data, status) {
			console.error('Erro Get Noticia', response.status, response.data);
		});
	}

	$scope.getNoticiaUm = function(){

		var dados = {
			'funcao':'getNoticiaUm'
		};

		var res = $http({
			method	: 'POST',
			url 	: 'php/noticia.php',
			data 	: dados,
			headers : {"Content-Type": "application/json; charset=utf-8"}
		});

		res.then(function(data, status, headers, config) {
			$scope.listaNoticia = [];
			if(data.data){
				angular.forEach(data.data, function(value,key){
					$scope.listaNoticia.push(value);
				});
			}else{
				alert('erro ao capturar busca inicial');
			}
		});

		res.catch(function(data, status) {
			console.error('Erro Get Noticia', response.status, response.data);
		});
	}

	$scope.getNoticiaDois = function(){

		var dados = {
			'funcao':'getNoticiaDois'
		};

		var res = $http({
			method	: 'POST',
			url 	: 'php/noticia.php',
			data 	: dados,
			headers : {"Content-Type": "application/json; charset=utf-8"}
		});

		res.then(function(data, status, headers, config) {
			$scope.listaNoticiaDois = [];
			if(data.data){
				angular.forEach(data.data, function(value,key){
					$scope.listaNoticiaDois.push(value);
				});
			}else{
				alert('erro ao capturar busca inicial');
			}
		});

		res.catch(function(data, status) {
			console.error('Erro Get Noticia', response.status, response.data);
		});
	}
	

	$scope.getNoticiaTres = function(){

		var dados = {
			'funcao':'getNoticiaTres'
		};

		var res = $http({
			method	: 'POST',
			url 	: 'php/noticia.php',
			data 	: dados,
			headers : {"Content-Type": "application/json; charset=utf-8"}
		});

		res.then(function(data, status, headers, config) {
			$scope.listaNoticiaTres = [];
			if(data.data){
				angular.forEach(data.data, function(value,key){
					$scope.listaNoticiaTres.push(value);
				});
			}else{
				alert('erro ao capturar busca inicial');
			}
		});

		res.catch(function(data, status) {
			console.error('Erro Get Noticia', response.status, response.data);
		});
	}
	/*$scope.ct = function(ct){
		var cat = "";
		switch(ct) {
		    case "1":
		        cat = "Mundo";
		        break;
		    case "2":
		        cat = "Geral";
		        break;
		    case "3":
		        cat = "Famosos";
		        break;
		    case "4":
		        cat = "Esporte";
		        break;
        	case "5":
		        cat = "Policial";
		        break;
	        case "6":
		        cat = "Política";
		        break;
	        case "7":
		        cat = "Saúde";
		        break;
	        case "8":
		        cat = "Tecnologia";
		        break;
	        case "9":
		        cat = "Informe Publicitário";
		        break;
		}

		return cat;
	}*/

	/*$scope.organizaLista = function(){

		if($scope.listaNoticia.length<1){
			return;
		}
		angular.forEach($scope.listaNoticia, function(value,key){
			var bits 	= value.dt_ativacao.split(/\D/);
			var date 	= new Date(bits[0], --bits[1], bits[2], bits[3], bits[4]);
			var bAgora 	= value.agora.split(/\D/);
			var dateAgora 	= new Date(bAgora[0], --bAgora[1], bAgora[2], bAgora[3], bAgora[4]);
			var dtAtiv 	= parseInt(date.getTime());
			var agora 	= parseInt(dateAgora.getTime());
			if(agora >= dtAtiv){
				$scope.getCtListagem(value.ct_listagem,value);
			} 
		});
	}*/

	/*$scope.orgList = function(lista){

		if(lista.length<1){
			alert('aqui');
			return;
		}

		var list = [];

		angular.forEach(lista, function(value,key){
			list.push(
				{
					'id': value.id_noticia,
					'subtitulo':value.subtitulo,
					'texto':value.texto,
					'titulo': $scope.resolveTitulo(value.titulo),
					'img': "nimg/"+value.img,
					'ct_noticia':$scope.ct(value.ct_noticia),
					'ct_listagem':value.ctListagem,
					'dt_ativacao':value.dt_ativacao,
					'dt_publicacao':$scope.limpaData(value.dt_publicacao)
				}
			);
		});


		return list;
	}*/

	/*$scope.getCtListagem = function(ctList,obj){
		if(ctList.length<1){
			return false;
		}

		angular.forEach(ctList, function(value,key){
			if(value.ct_list == 1){
				$scope.listaNoticiaDestaque.push($scope.montaJson(obj));
			}else if(value.ct_list == 2){
				if($scope.listaNoticiaSub.length == 0){
					$scope.listaNoticiaSub = [$scope.montaJson(obj)];
				}
			}else if(value.ct_list == 3){
				$scope.listaNoticiaUm.push($scope.montaJson(obj));
			}else if(value.ct_list == 4){
				$scope.listaNoticiaDois.push($scope.montaJson(obj));
			}else if(value.ct_list == 5){
				$scope.listaNoticiaTres.push($scope.montaJson(obj));
			}else{
				return false;
			}
		});
	}*/
	
	$scope.montaJson = function(obj){
		if(!obj){
			return false;
		}

		var objJson = {
			'id': obj.id,
			'subtitulo':obj.subtitulo,
			'texto':obj.texto,
			'titulo': obj.titulo,
			'img': obj.img,
			'ct_noticia':obj.ct_noticia,
			'dt_publicacao':(obj.dt_publicacao)
		};

		return objJson;
	}

	$scope.limpaData = function(dt){
		var bits 		= dt.split(/\D/);
		var date 		= new Date(bits[0], --bits[1], bits[2], bits[3], bits[4]);
		var day 		= date.getDate();
	    var month 		= date.getMonth();
	    var year 		= date.getFullYear();
	    monthNames 	= ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];

		var dataComp = ('('+ ("0" + day).slice(-2) + '/' + ("0" + (month + 1)).slice(-2) + '/' + year +')');
		return dataComp;
	}

	/*$scope.resolveTitulo = function(text){
		if(text.split(" ").length <= 1){
			return text;
		}

		var nText = "";
		var cutText = text.split(" ");
		var tamanho = 0;
		var cont = 0;

		do{
			if(cutText[cont]){
				tamanho = tamanho+(cutText[cont].length);
				nText = nText + ' ' + cutText[cont];
			}			
			cont++;
			if(cont == 150){
				break;
			}
		}while(tamanho <= 80);

		return nText;
	}*/

	$scope.open = function (id,tp,parentSelector) {
		if(!id){
			return false;
		}

		var parentElem = parentSelector ?
			angular.element($document[0].querySelector('.modal-demo' + parentSelector)) : undefined;
		var modalInstance = $uibModal.open({
			animation: $scope.animationsEnabled,
			ariaLabelledBy: 'modal-title',
			ariaDescribedBy: 'modal-body',
			templateUrl: 'myModalContent.html',
			controller: 'ModalInstanceCtrl',
			size: 'lg',
			resolve: {
				lista: function () {
					var lista = $scope.getPorIdNoticia(id,tp);
					return lista;
				}
			}
		});
	}

	$scope.getPorIdNoticia = function(id,tipoListagem){
		var indice = null;

		$scope.verificaListaCorreta(tipoListagem);

		if(tipoListagem == 1){
			if($scope.listaNoticiaDestaque.length > 1){
				angular.forEach($scope.listaNoticiaDestaque, function(value,key){
					if(value.id == id){
						indice = key;
					}
				});
				return $scope.listaNoticiaDestaque[indice];
			}else{
				return $scope.listaNoticiaDestaque[0];
			}			
		} else if(tipoListagem == 2){
			if($scope.listaNoticiaSub.length > 1){
				angular.forEach($scope.listaNoticiaSub, function(value,key){
					if(value.id == id){
						indice = key;
					}
				});
				return $scope.listaNoticiaSub[indice];
			}else{
				return $scope.listaNoticiaSub[0];
			}
		} else if(tipoListagem == 3){
			if($scope.listaNoticia.length > 1){				
				angular.forEach($scope.listaNoticia, function(value,key){
					if(value.id == id){
						indice = key;
					}
				});

				return $scope.listaNoticia[indice];
			}else{
				return $scope.listaNoticia[0];
			}
		} else if(tipoListagem == 4){
			if($scope.listaNoticiaDois.length > 1){
				angular.forEach($scope.listaNoticiaDois, function(value,key){
					if(value.id == id){
						indice = key;
					}
				});
				return $scope.listaNoticiaDois[indice];
			}else{
				return $scope.listaNoticiaDois[0];
			}
		} else if(tipoListagem == 5){
			if($scope.listaNoticiaTres.length > 1){
				angular.forEach($scope.listaNoticiaTres, function(value,key){
					if(value.id == id){
						indice = key;
					}
				});
				return $scope.listaNoticiaTres[indice];
			}else{
				return $scope.listaNoticiaTres[0];
			}
		} else {
			return false;
		}
	}

	$scope.verificaListaCorreta = function(tipoListagem){
		if(tipoListagem == 1){
			if($scope.listaNoticiaDestaque.length == 0){
				return false;
			}
		} else if(tipoListagem == 2){
			if($scope.listaNoticiaSub.length == 0){
				return false;
			}
		} else if(tipoListagem == 3){
			if($scope.listaNoticiaUm.length == 0){
				return false;
			}
		} else if(tipoListagem == 4){
			if($scope.listaNoticiaDois.length == 0){
				return false;
			}
		} else if(tipoListagem == 5){
			if($scope.listaNoticiaTres.length == 0){
				return false;
			}
		} 

		return true;
	}
});

crudSis.controller('ModalInstanceCtrl', function ($scope,$uibModalInstance,lista) {
	$scope.lista = lista;

	$scope.ok = function () {
		$uibModalInstance.close();
	};

	$scope.cancel = function () {
		$uibModalInstance.dismiss('cancel');
	};
});