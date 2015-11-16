define(function() {
    'use strict';

    var app = angular.module('app', []);
    app.config( function($interpolateProvider) {
        $interpolateProvider.startSymbol('<%').endSymbol('%>');
    });

    app.controller('MainController', function($scope, $http) {

        $scope.messages = [];
        $scope.victor = '';
        $scope.comp_img = '';
        $scope.user_img = '';
        $scope.user_victories = '';
        $scope.computer_victories = '';
        $scope.total_ties = '';

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
                // If registration or login success
                if (response.data['success']) {
                    _showMessages('success', response.data);
                    _hideRegistrationLoginForms();
                    $scope.showBattleZone();
                }

                // If it is the battle info
                if (response.data['battle-info']) {
                    var data = response.data['battle-info'][0];
                    _showLastBattleInfo(data);
                }
            }, function errorCallback(response) {
                _showMessages('error', response.data);
            });
        };

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

        function _showLastBattleInfo(data) {
            var messages = ['Bummer', 'Rats', 'Sorry', 'Unfortunately'];
            var message = messages[Math.floor(Math.random() * messages.length)];

            _setImages(data);

            if (data.victor == 0) {
                $scope.victor = 'You defeated the computer!';
            } else if (data.victor == 1) {
                $scope.victor = message + ', the computer won.';
            } else {
                $scope.victor = 'You tied with the computer.';
            }

            $('#battle_form').hide();
            $('.battle-info').fadeIn();

            setTimeout(function() {
                $('.battle-info .teaser').hide();
                $('.computer-weapon img').delay(1000).css('opacity', 1);
                $('.battle-info .victor').fadeIn();
                $scope.setScoreboard();
            }, 3000);
        }

        function _setImages(data) {
            // Set the computer weapon img
            if (data.computerWeapon == 0) {
                $scope.comp_img = 'rock';
            } else if (data.computerWeapon == 1) {
                $scope.comp_img = 'paper';
            } else if (data.computerWeapon == 2) {
                $scope.comp_img = 'scissors';
            } else if (data.computerWeapon == 3) {
                $scope.comp_img = 'spock';
            } else {
                $scope.comp_img = 'lizard';
            }

            // Set the user weapon img
            if (data.userWeapon == 0) {
                $scope.user_img = 'rock';
            } else if (data.userWeapon == 1) {
                $scope.user_img = 'paper';
            } else if (data.userWeapon == 2) {
                $scope.user_img = 'scissors';
            } else if (data.userWeapon == 3) {
                $scope.user_img = 'spock';
            } else {
                $scope.user_img = 'lizard';
            }
        }

        $scope.setScoreboard = function() {
            $http({
                method: 'GET',
                url: 'battles',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).then(function successCallback(response) {
                var data = response.data;
                $scope.user_victories = data['user_victories'];
                $scope.computer_victories = data['computer_victories'];
                $scope.total_ties = data['total_ties'];
            }, function errorCallback(response) {
                console.log(response);
            });
        };

        $scope.showBattleZone = function() {
            $('section.battle-zone').fadeIn();
            $('#battle_form').fadeIn();
            $('.battle-info').hide();
            $('.battle-info .victor').hide();
            $('.battle-info .teaser').show();
            $('.computer-weapon img').css('opacity', 0);
        };

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
            $('form button[type="submit"]').fadeIn().css('display', 'block');
        };

        // Set the scoreboard on load
        $scope.setScoreboard();
    });
});