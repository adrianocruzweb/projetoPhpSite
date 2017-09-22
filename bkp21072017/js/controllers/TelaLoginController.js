crudSisIndexLogin.controller('telaLogin', function($scope,$http){

	$scope.msgErro = " ";

	$scope.contaLogin = 5;

	$scope.verificaLogin = function(){

		$http({
			method  : 'POST',
			url     : '../php/json.php',
			data    : {'funcao':'verificaLogin'},
			headers : {"Content-Type": "application/json; charset=utf-8"}
		}).success(function(data, status, headers, config) {
			console.log(typeof data);
			if(data == 1){
				window.location.href='principal.html';
			}			
		}).error(function(data, status, headers, config) {
			$scope.status = status;
			alert('Status de ERRO: '+status);
		});

		return;
	}

	$scope.logar = function($event){
		
		$scope.contaLogin--;

		if($scope.contaLogin != 0){

			if(!$scope.usuario){
				$scope.msgErro = 'Informe o Usuario';
				return;
			}
			if(!$scope.senha){
				$scope.msgErro = 'Informe o Senha';
				return;
			}

			var dados = {
				'funcao':'logar',
				'usuario': $scope.usuario,
				'senha': $scope.senha
			};

			$http({
				method  : 'POST',
				url     : '../php/json.php',
				data    : dados,
				headers : {"Content-Type": "application/json; charset=utf-8"}
			}).success(function(data, status, headers, config) {
				console.log(data);
				if(data == 1){
					window.location.href = "principal.html";
				}else{
					$scope.msgErro = "Dados incorretos tente denovo";
				}
			}).error(function(data, status, headers, config) {
				$scope.status = status;
				alert('Status de ERRO: '+status);
			});

		}else{
			$scope.msgErro = "Foi esgotado o seu limite de tentativas";
		}

		$event.preventDefault();
		$event.stopPropagation();
	}

});