crudSis.controller('noticiaCtrl', function($scope,$http,Upload){

	$scope.listaNoticia 		= [];
	$scope.idNoticia 			= null;

	$scope.getNoticia = function(){

		alert('aqui');
    	var dados = {
    		'funcao':'getNoticia'
    	};

    	var res = $http({
			method	: 'POST',
			url 	: 'php/noticia.php',
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
							'img': "nimg/"+value.img,
							'dt_publicacao': value.dt_publicacao
						}
					);
				});	
				console.log($scope.listaNoticia);		
			}else{
				alert('erro ao capturar busca inicial');
			}
		});

		res.catch(function(data, status) {
			console.error('Erro Get Noticia', response.status, response.data);
		});
    }
});