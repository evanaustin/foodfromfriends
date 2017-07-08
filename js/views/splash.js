 

 
 $('#locavore').on('submit', function(e){
	e.preventDefault();
	console.log('submit');
    console.log($(this).serialize())
    App.Ajax.post('splash/locavore_signup', $(this).serialize(), function(response){
		 if (response.success == true){
			$('#locavore_message').text('Thanks');
		 }
	 });	
  });	


 $('#grower').on('submit', function(e){
	e.preventDefault();
	console.log('submit');
	App.Ajax.post('splash/grower_signup', $(this).serialize(), function(response){
		if (response.success == true){
		$('#grower_message').text('Thanks');
		 }
		
	
	});	
  });	

