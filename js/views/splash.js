 

 
 $('#locavore').on('submit', function(e){
	e.preventDefault();
	console.log('submit');
    console.log($(this).serialize())
    App.Ajax.post('splash/locavore_signup', $(this).serialize(), 
		function(response){ 
			$('#locavore_message').text('Thanks for your interest!');
		},
		function(response){ 
			$('#locavore_message').text(response.error);
		}
	 );	
  });	


 $('#grower').on('submit', function(e){
	e.preventDefault();
	console.log('submit');
	App.Ajax.post('splash/grower_signup', $(this).serialize(), 	
		function(response){ 
			$('#grower_message').text('Thanks for your interest!');
		},
		function(response){ 
			$('#grower_message').text(response.error);
		}
	);	
  });	

