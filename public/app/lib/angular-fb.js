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

                $fb.createAlbum = function (name) {
                    return helper.run(service.createAlbum, arguments, []);
                };

                $fb.albums = function () {
                    return helper.run(service.albums);
                };

                $fb.publish = function (album_id, data) {
                    return helper.run(service.publish, arguments, []);
                };

                $fb.me = function () {
                    return helper.run(service.me);
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
                    defer.reject(response.error);
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
                    defer.reject(response.error);
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
                    defer.reject(response.error);
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
                    defer.reject(response.error);
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
            // console.log(callback);
            console.log("Inicio");
            console.log("-------------");
            console.log(this.getLoginStatus(), this.hasPermissions(perms));
            console.log("-------------");
            if(this.getLoginStatus() && this.hasPermissions(perms)){
                console.log("User logid and permissions");
                return callback(args);
            } else {
                return this.login();
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
            console.log("Login inicio");
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
                    console.log("login success");
                } else {
                    defer.reject(response.status);
                    console.log("user denied login");
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
            js.src = "//connect.facebook.net/es_ES/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    }]);