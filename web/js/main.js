require(['jquery'], function($) {
    'use strict';

    return function() {

        var $form = $('form[name="battle_form"]');

        // Send the form via ajax to the postBattleAction() route
        function _postBattle() {
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
                    console.log(data);
                }
            });
        }

        // Listen to the submission on the form
        $(document).on('submit', $form, function(e) {
            e.preventDefault();
            _postBattle();
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
});