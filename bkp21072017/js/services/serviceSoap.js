crudSisIndex.service('Soap', ['$q', '$http',
    function($q, $http) {
        this.execute = function(metodo, params) {
            var envelope = '';
            var deferred = $q.defer();
            if (params) {
                envelope =  '<ns1:'+metodo+'>'+
                                '<arg0>'+ JSON.stringify(params) +'</arg0>'+
                            '</ns1:'+metodo+'>';
            } else {
                envelope = '<ns1:'+metodo+'></ns1:'+metodo+'>';
            }
            $http({
                'url'   :   'http://localhost/php/servidor.php?wsdl',
                'method':   'POST', 
                'data'  :   '<?xml version="1.0" encoding="UTF-8"?>'+
                                '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns1="http://connect.webservice.business.com.br/">'+
                                '<SOAP-ENV:Body>'+ envelope + '</SOAP-ENV:Body>'+
                            '</SOAP-ENV:Envelope>'
            })
            .then(function(response) {
                var result = response.data.substring(response.data.indexOf("<return>") + 8, response.data.indexOf("</return>"));
                deferred.resolve(JSON.parse(result));
            }, function(response) {
                deferred.reject(response);
            }).catch(function(fallback) {
                console.log(fallback);
            });
            return deferred;
        };
    }
]);