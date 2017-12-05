crudSis.controller('vejaCtrl', function($location,$scope,$http,$uibModal,$log,$document,$routeParams){
	var param = $routeParams.param;
	$scope.noticia = null;

	$scope.getNoticia = function(){

		if(!param){
			return false;
		}

		var dados = {
			'funcao':'getNoticiaLink',
			'link':param
		};

		var res = $http({
			method	: 'POST',
			url 	: 'php/noticia.php',
			data 	: dados,
			headers : {"Content-Type": "application/json; charset=utf-8"}
		});

		res.then(function(data, status, headers, config) {
			if(data.data){
				$scope.noticia = data.data;
			}else{
				alert('erro ao capturar busca inicial');
			}
		});

		res.catch(function(data, status) {
			console.error('Erro Get Noticia', response.status, response.data);
		});
	}

	$scope.getNoticia();
});