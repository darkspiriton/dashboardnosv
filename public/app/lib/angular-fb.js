angular.module('angular-fb', [])
    .constant('FacebookConfig', {
        appId: '',
        xfbml: true,
        status: true,
        cookie: true,
        version: 'v2.6'
    })
    .constant('FacebookPrivilege', {
        permissions: [],
        extendPermissions: ''
    })
    .constant('FacebookUser', {})
    .provider('$fb', ['FacebookConfig', 'FacebookPrivilege', function (config,privilege) {
        Object.defineProperties(this, {
            appId: {
                get: function () {
                    return config.appId;
                },
                set: function (value) {
                    config.appId = value;
                }
            },
            xfbml: {
                get: function () {
                    return config.xfbml;
                },
                set: function (value) {
                    config.xfbml = value;
                }
            },
            status: {
                get: function () {
                    return config.status;
                },
                set: function (value) {
                    config.status = value;
                }
            },
            cookie: {
                get: function () {
                    return config.cookie;
                },
                set: function (value) {
                    config.cookie = value;
                }
            },
            extendPermissions: {
                get: function () {
                    return privilege.extendPermissions;
                },
                set: function (value) {
                    privilege.extendPermissions = value;
                }
            }
        });

        this.$get = [
            'facebookServices',
            'FacebookHelper',
            function (service, helper) {
                var $fb = {};

                $fb.me = function () {
                    return helper.run(service.me);
                };

                $fb.albums = function () {
                    return helper.run(service.albums);
                };

                $fb.createAlbum = function (name) {
                    return helper.run(service.createAlbum, arguments, []);
                };

                $fb.publish = function (album_id, data) {
                    return helper.run(service.publish, arguments, []);
                };

                $fb.collection = function (url, data) {
                    return helper.run(service.collection, arguments, []);
                };

                return $fb;
            }];
    }])
    .factory('facebookServices', ['$q', function ($q) {
        var service = {};

        service.me = function () {
            var defer = $q.defer();
            FB.api('me', function (response) {
                if (!response || response.error) {
                    defer.reject(response.error.message, response);
                } else {
                    defer.resolve(response);
                }
            });
            return defer.promise;
        };

        service.albums = function () {
            var defer = $q.defer();
            FB.api('me/albums', { fields: 'name'}, function (response) {
                if (!response || response.error) {
                    defer.reject(response.error.message, response);
                } else {
                    defer.resolve(response);
                }
            });
            return defer.promise;
        };

        service.createAlbum = function (args) {
            var defer = $q.defer();
            FB.api('me/albums','POST',args[0], function (response) {
                if (!response || response.error) {
                    defer.reject(response.error.message, response);
                } else {
                    defer.resolve(response);
                }
            });
            return defer.promise;
        };

        service.publish = function (args) {
            var defer = $q.defer();
            FB.api(args[0] + '/photos', 'POST', args[1], function (response) {
                if (!response || response.error) {
                    defer.reject(response.error.message, response);
                } else {
                    defer.resolve(response);
                }
            });
            return defer.promise;
        };

        service.collection = function (args) {
            var defer = $q.defer();
            FB.api(args[0], args[1], function (response) {
                if (!response || response.error) {
                    defer.reject(response.error.message, response);
                } else {
                    defer.resolve(response);
                }
            });
            return defer.promise;
        };

        return service;
    }])
    .factory('FacebookHelper',['$q','FacebookPrivilege', function ($q, privilege) {

        var helper = {};

        helper.run = function (callback, args, perms) {
            if(this.getLoginStatus() && this.hasPermissions(perms)){
                return callback(args);
            } else {
                var defer = $q.defer();
                this.login().then(function () {
                    defer.resolve(callback(args));
                }, function (error) {
                    defer.reject(error);
                });
                return defer.promise;
            }
        };

        helper.getLoginStatus = function (){
            var status;
            FB.getLoginStatus(function (response) {
                status = (response.status == 'connected');
            });
            return status;
        };

        helper.login = function () {
            var defer = $q.defer();
            FB.login(function (response) {
                if (response.status == 'connected') {
                    FB.api('/me/permissions', function (permissions) {
                        privilege.permissions = [];
                        for (i in permissions.data[0]) {
                            if (permissions.data[0][i] == 1) {
                                privilege.permissions.push(i);
                            }
                        }
                    });
                    defer.resolve(response.status);
                } else {
                    defer.reject('Usuario no se logueo', response);
                }
            }, {scope: privilege.extendPermissions});

            return defer.promise;
        };

        helper.hasPermissions = function (perm) {
            perm || (perm = []);
            for (var i = 0, l = perm.length; i < l; i++) {
                var c = 0;
                for (var y = 0, x = privilege.permissions.length; y < x; y++){
                    if (privilege.permissions[y] == perm[i]) {
                        c++; break;
                    }
                }
                if(c == 0) return false;
            }
            return true;
        };

        return helper;

    }])
    .run(['$window', 'FacebookConfig', function ($window, config) {
        $window.fbAsyncInit = function () {
            FB.init(config);
        };

        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/en_EN/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    }]);