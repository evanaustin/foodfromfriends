App.Front.BigHouseFarm = function() {
    function listener() {
        $('#interest-signup').on('submit', function(e) {
            e.preventDefault();

            App.Ajax.post('interest/sign-up', $(this).serialize(), 
                function(response) {
                    $('#interest-signup button[type="submit"]').attr('disabled', 'disabled').text('Thanks!');
                    $('.post-signup').show();
                },
                function(response) {
                    toastr.error(response.error);
                }
            );	
        });	
    }

	return {
        listener: listener
    };
}();