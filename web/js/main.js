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
                _showMessages('success', response.data);

                // If registration success
                if (response.data['success'].indexOf('you may now battle')) {
                    _hideRegistrationLoginForms();
                    _showBattleZone();
                }
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

        function _hideRegistrationLoginForms() {
            $('section.register-login').fadeOut(function(){
                $(this).remove();
            });
        }

        function _showBattleZone() {
            $('section.battle-zone').fadeIn();
        }

        $scope.closeAlert = function(e) {
            var $target = $(e.currentTarget);
            $target.parent('.alert').fadeOut(function() {
                $(this).remove();
            });
        };

        // When a user selects a weapon
        $scope.selectWeapon = function(e) {
            var $target = $(e.currentTarget);
            $('.weapons img').removeClass('active');
            $target.parent().find('img').addClass('active');
            $('form button[type="submit"]').fadeIn();
        };

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
    });
});