App.Front.UserProfile = function() {
    function listener() {
        if ($('#map').length) {
            Mapbox.setCenter([lng, lat]);
        }

        $('.offer-item').on('click', function(e) {
            // Trigger sign up first
            if (typeof user === 'undefined' || user.id === null) {
                e.preventDefault();

                var $sign_up_modal = $('#sign-up-modal');
                var $sign_up_form = $sign_up_modal.find('#sign-up');
    
                $sign_up_modal.modal();
    
                App.Util.msg('Hey! Sign up first &mdash; then you can offer this item!', 'info', $sign_up_form);
    
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
