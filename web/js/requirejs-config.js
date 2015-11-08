requirejs.config({
    baseUrl: "/",
    paths: {
        "jquery": [
            "//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min",
            "vendor/jquery/dist/jquery.min"
        ],
        "bootstrap": [
            "//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min",
            "vendor/bootstrap/dist/js/bootstrap.min"
        ],
        "angular": [
            "//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.min",
            "vendor/angularjs/angular.min"
        ],
        "main": "js/main"
    },
    shim: {
        "bootstrap": {
            deps: ["jquery"]
        },
        "angular": {
            exports: "angular"
        },
        "main": {
            deps: ["angular", "jquery"]
        }
    },
    waitSeconds: 10
});

require(['main'], function() {
    angular.bootstrap(document, ['app']);
});