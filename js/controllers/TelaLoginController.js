crudSisIndexLogin.controller('telaLogin', function($scope,$http){

	$scope.msgErro = " ";

	$scope.contaLogin = 5;

	$scope.verificaLogin = function(){

		var res = $http({
			method  : 'POST',
			url     : '../php/json.php',
			data    : {'funcao':'verificaLogin'},
			headers : {"Content-Type": "application/json; charset=utf-8"}
		})

		res.then(function(data, status, headers, config) {
			if(data.data == 1){
				window.location.href='principal.html';
			}			
		})

		res.catch(function(data, status) {
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

			var res = $http({
				method  : 'POST',
				url     : '../php/json.php',
				data    : dados,
				headers : {"Content-Type": "application/json; charset=utf-8"}
			})

			res.then(function(data, status, headers, config) {
				if(data.data == 1){
					window.location.href = "principal.html";
				}else{
					$scope.msgErro = "Dados incorretos tente denovo";
				}
			})

			res.catch(function(data, status) {
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