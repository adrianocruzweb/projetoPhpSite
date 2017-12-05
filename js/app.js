var crudSis = angular.module('crudSis', [
	'ngRoute',
	'ngResource',
	'ui.bootstrap',
	'slickCarousel',
	'ngSanitize'
]);


crudSis.config(function($routeProvider, $locationProvider){
   $locationProvider.hashPrefix('');
   $routeProvider

   .when('/', {
      templateUrl : 'templates/noticia.html',
      controller  : 'noticiaCtrl',
   })

   .when('/veja/:param', {
      templateUrl : 'templates/veja.html',
      controller  : 'vejaCtrl',
   })

   .when('/veicular', {
      templateUrl : 'templates/institucional.html',
      controller  : 'veicularCtrl',
   })

   .when('/adesivagem', {
      templateUrl : 'templates/adesivagem.html',
      controller  : 'adesivagemCtrl',
   })

   .when('/promocao', {
      templateUrl : 'templates/promocao.html',
      controller  : 'promocaoCtrl',
   })

   .when('/atvon', {
      templateUrl : 'templates/atvon.html',
      controller  : 'adesivagemCtrl',
   })

   .when('/itinerarios', {
      templateUrl : 'templates/itinerarios.html',
      controller  : 'adesivagemCtrl',
   })

   .when('/anunciantes', {
      templateUrl : 'templates/anunciantes.html',
      controller  : 'adesivagemCtrl',
   })

   .when('/diferenciais', {
      templateUrl : 'templates/diferenciais.html',
      controller  : 'adesivagemCtrl',
   })

   // caso não seja nenhum desses, redirecione para a rota '/'
   .otherwise ({ redirectTo: '/' });

   //$locationProvider.html5Mode(true);
});


var crudSisAdm = angular.module('crudSisAdm', [
	'ngRoute',
	'ngResource',
	'ui.bootstrap',
	'textAngular',
	'ngFileUpload',
   'ui.bootstrap.datetimepicker'
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

