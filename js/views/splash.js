// Register locavore signup form
$('#locavore-signup').on('submit', function(e) {
	e.preventDefault();

    App.Ajax.post('splash/locavore_signup', $(this).serialize(), 
		function(response) {
			// disable submit
			$('#locavore-signup button[type="submit"]').attr('disabled', 'disabled');

			// success message
			$('#locavore-signup p.signup-message').html('<i class="fa fa-check"></i> Awesome! We\'ll be in touch.');
		},
		function(response) {
			// error message
			$('#locavore-signup p.signup-message').html(response.error);
		}
	 );	
});	

// Register grower signup form
 $('#grower-signup').on('submit', function(e) {
	e.preventDefault();

	App.Ajax.post('splash/grower/_signup', $(this).serialize(), 	
		function(response) {
			// disable submit
			$('#grower-signup button[type="submit"]').attr('disabled', 'disabled');

			// success message
			$('#grower-signup p.signup-message').html('<i class="fa fa-check"></i> Awesome! We\'ll be in touch.');
		},
		function(response) {
			// error message
			$('#grower-signup p.signup-message').html(response.error);
		}
	);	
});	

