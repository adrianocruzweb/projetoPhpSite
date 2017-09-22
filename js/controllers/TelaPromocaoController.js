crudSisPromo.controller('promoCtrl', function($scope,$http,$uibModal,$log,$document){

	

	$scope.cadastro = function (parentSelector) {

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
					return null;
				}
			}
		});
	}

	
});

crudSisPromo.controller('ModalInstanceCtrl', function ($scope,$uibModalInstance,lista) {
	$scope.lista = lista;

	$scope.ok = function () {
		$uibModalInstance.close();
	};

	$scope.cancel = function () {
		$uibModalInstance.dismiss('cancel');
	};
});