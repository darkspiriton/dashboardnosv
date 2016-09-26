angular.module('App')
    .service('apiclient',["petition","$q",function(petition,$q){

        this.index = function () {            
            var defered = $q.defer();
            var promise = defered.promise;

            petition.get('api/auxclient')
            .then(function(data){
                defered.resolve(data);
            },function(error){
                defered.reject(error);
            });
            return promise;
        };
        

        this.saveClient = function (status,client) {
            var defered = $q.defer();
            var promise = defered.promise;

            if(status == 1){
                client.status_id = status;
                petition.post("api/auxclient",client)
                .then(function(data){
                    defered.resolve(data);
                },function(error){
                    defered.reject(error);
                });
                
            }else if(status ==2){
                client.status_id = status;
                petition.post("api/auxclient/store/clientI",client)
                .then(function(data){
                    defered.resolve(data);
                },function(error){
                    defered.reject(error);
                });                
            }
            return promise;
        };

        this.updateClient = function (){
            var defered = $q.defer();
            var promise = defered.promise;


        };

        this.download = function(){
            var defered = $q.defer();
            var promise = defered.promise;

            petition.post('api/auxclient/filter/get/client/download',null,{responseType: 'arraybuffer'})
                .then(function(data){
                    defered.resolve(data);
                },function(error){
                    defered.reject(error);
                });
            return promise;
        };

    }]);

    
