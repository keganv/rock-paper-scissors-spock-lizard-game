require(['jquery'], function($) {
    'use strict';

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
                    console.log(data);
                },
                'error' : function(data) {
                    _showMessages('error', data.responseJSON);
                }
            });
        }

        function _showMessages(type, data) {
            if (type === 'error') {
                data = data.split('ERROR:');
                $.each(data, function(k, v) {
                    if (v != '') {
                        var message =
                            '<div class="error alert alert-danger">' +
                                '<p>' + v + '</p>' +
                                '<span class="close">&times;</span>' +
                            '</div>';
                        $('.messages').append(message);
                    }
                });
                $('.messages .error').fadeIn();
            }
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

        // Show registrations form
        $(document).on('click', '.btn.register', function() {
            $('section.register-login').fadeOut(function() {
                $('section.register').fadeIn();
            });
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
});