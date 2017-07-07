 
 
 $('#locavore').on('submit', function(e){
	e.preventDefault();
	console.log('submit');
    console.log($(this).serialize())
    $.post('splash/locavore_signup', $(this).serialize(), function(response){
	 	console.log(response);
	// 	$('#message').text(response);
	
	 });	
  });	


 $('#grower').on('submit', function(e){
	e.preventDefault();
	console.log('submit');
	$.post('splash/grower_signup', $(this).serialize(), function(response){

		console.log(response);
		$('#message').text(response);
	
	});	
  });	

