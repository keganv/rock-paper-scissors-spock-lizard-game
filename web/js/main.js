define(function() {
    'use strict';

    var app = angular.module('app', []);
    app.config( function($interpolateProvider) {
        $interpolateProvider.startSymbol('<%').endSymbol('%>');
    });

    app.controller('MainController', function($scope, $http) {
        $scope.submitForm = function(e) {
            e.preventDefault();
            var $form = $(e.currentTarget);
            var fields = {};
            $.each($form.serializeArray(), function(index, field) {
                fields[field.name] = field.value;
            });

            $http({
                method: $form.attr('method'),
                url: $form.attr('action'),
                data: $.param(fields),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(response) {
                console.log(response);
                _showMessages('success', response.data);
            }, function errorCallback(response) {
                _showMessages('error', response.data);
            });

        };

        $scope.messages = [];

        function _showMessages(type, data) {
            var classes;

            // Remove previous messages
            $scope.messages = [];

            if (type === 'error') {
                classes = 'error alert alert-danger';
            } else if (type === 'success') {
                classes = 'success alert alert-success';
            }

            $.each(data, function(k, v) {
                if (v != '') {
                    $scope.messages.push({
                        'class': classes,
                        'text': v
                    });
                }
            });
        }

        $scope.closeAlert = function(e) {
            var $target = $(e.currentTarget);
            $target.parent('.alert').fadeOut(function() {
                $(this).remove();
            });
        };
    });

    // Show registrations form
    $(document).on('click', '.btn.register', function() {
        $('section.register-login').fadeOut(function() {
            $('section.register').fadeIn();
        });
    });

    /*
    return function() {

        function _submitForm($form) {
            var fields = {};
            $.each($form.serializeArray(), function(index, field) {
                fields[field.name] = field.value;
            });

            $.ajax({
                'type' : $form.attr('method'),
                'url' : $form.attr('action'),
                'data' : fields,
                'success' : function(data) {
                    _showMessages('success', data);
                },
                'error' : function(data) {
                    _showMessages('error', data.responseJSON);
                }
            });
        }

        // Listens for form submission
        $(document).on('submit', 'form', function(e) {
            e.preventDefault();
            _submitForm($(this));
        });

        // When a user selects a weapon
        $(document).on('click', '.weapons label', function() {
            $('.weapons img').removeClass('active');
            $(this).parent().find('img').addClass('active');
            $('form button[type="submit"]').fadeIn();
        });



        // When a user resets the games
        $(document).on('click', 'button.reset-games', function() {
            $.ajax({
                'type' : 'DELETE',
                'url' : '/battles',
                'data' : null,
                'success' : function(data) {
                    console.log(data);
                },
                'error' : function(data) {
                    console.log(data.statusText);
                }
            });
        });
    }();
    */
});