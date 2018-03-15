App.Front.Home = function() {
    function listener() {
        $('a.start-selling').on('click', function(e) {
            // Trigger sign up first
            if (typeof user === 'undefined') {
                e.preventDefault();

                var $sign_up_modal = $('#sign-up-modal');
                var $sign_up_form = $sign_up_modal.find('#sign-up');

                $sign_up_modal.modal();

                App.Util.msg('Hey! Sign up first &mdash; then we can go add an item to sell!', 'info', $sign_up_form);

                $sign_up_form
                    .find('input[name="redirect"]')
                    .val($(this).attr('href'));

                return;
            }
        });

    };

    return {
        listener: listener
    };
}();