var crudSis = angular.module('crudSis', [
	'ngRoute',
	'ngResource',
	'ui.bootstrap',
	'slickCarousel',
	'ngSanitize'
]);

crudSis.config(function($routeProvider, $locationProvider){
   //$locationProvider.html5Mode(true);
   console.log('ok');
   $routeProvider

   .when('/', {
      templateUrl : "templates/noticia.html",
      controller  : "noticiaCtrl",
   })

   .when('/veicular', {
      templateUrl : "templates/institucional.html",
      controller  : "veicularCtrl",
   })

   .when('/adesivagem', {
      templateUrl : "templates/adesivagem.html",
      controller  : "adesivagemCtrl",
   })

   .when('/promocao', {
      templateUrl : "templates/promocao.html",
      controller  : "promocaoCtrl",
   })
   // caso não seja nenhum desses, redirecione para a rota '/'
   .otherwise ({ redirectTo: '/' });
});


var crudSisAdm = angular.module('crudSisAdm', [
	'ngRoute',
	'ngResource',
	'ui.bootstrap',
	'textAngular',
	'ngFileUpload'
]);

var crudSisPromo = angular.module('crudSisPromo', [
	'ngRoute',
	'ngResource',
	'ui.bootstrap',
	'ngMaterial',
	'jkAngularCarousel',
	'ngSanitize'
]);

var crudSisIndexLogin = angular.module('crudSisIndexLogin', []);

