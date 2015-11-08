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
        "main": "js/main"
    },
    shim: {
        "bootstrap": {
            deps: ["jquery"]
        },
        "main": {
            deps: ["jquery"]
        }
    },
    waitSeconds: 10
});
