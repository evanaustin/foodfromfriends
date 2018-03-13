App.Front.SellerProfile = function() {
    function listener() {
        if ($('#map').length) {
            Mapbox.setCenter([lng, lat]);
        }

        $('#message').on('click', function(e) {
            // trigger sign up before directing to messages
            if (user == 0) {
                e.preventDefault();
                
                var $sign_up_modal = $('#sign-up-modal');
                var $sign_up_form = $sign_up_modal.find('#sign-up');

                $sign_up_modal.modal();

                App.Util.msg('Hey! Sign up first &mdash; then you can send a message!', 'info', $sign_up_form);

                $sign_up_form
                    .find('input[name="redirect"]')
                    .val(false);

                return;
            }
        });
    };

    return {
        listener: listener
    };
}();
