crudSisIndexLogin.controller('telaPrincipal', function($scope,$http){

	$scope.verificaLogin = function(){

		$http({
			method  : 'POST',
			url     : 'php/json.php',
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
			url     : 'php/json.php',
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

});