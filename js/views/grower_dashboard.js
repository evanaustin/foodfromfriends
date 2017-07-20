$('#add-listing').on('submit', function(e) {
	e.preventDefault();
     console.log($(this).serialize());
    App.Ajax.post('grower_dashboard', $(this).serialize(), 
		function(response) {

			// success message
			$('#add-listing p.listing-message').html('<i class="fa fa-check"></i> Awesome!');
		},
		function(response) {
			// error message
			$('#locavore-signup p.listing-message').html(response.error);
		}
	 );	
});	

$("#add_new_subcategory").on('click',function(e){
    e.preventDefault();        
    $("#new_subcategory").toggle();
            
});

$("#food_category").change(function(){
            
    if($(this).val() == 1){
        $("#veg").show();
    } else{
        $("#veg").hide();
    }
    if($(this).val() == 2){
        $("#fruit").show();
    } else{
        $("#fruit").hide();
    }
    if($(this).val() == 3){
        $("#egg").show();
    } else{
        $("#egg").hide();
    }
    if($(this).val() == 4){
        $("#dairy").show();
    } else{
        $("#dairy").hide();
    }
    if($(this).val() == 5){
        $("#meat").show();
    } else{
        $("#meat").hide();
    }
    if($(this).val() == 6){
        $("#sea").show();
    } else{
        $("#sea").hide();
    }
    if($(this).val() == 7){
        $("#bev").show();
    } else{
        $("#bev").hide();
    }
    if($(this).val() == 8){
        $("#herb").show();
    } else{
        $("#herb").hide();
    }
});
            
$("#fruit").hide();
$("#egg").hide();
$("#dairy").hide();
$("#meat").hide();
$("#sea").hide();
$("#bev").hide();
$("#herb").hide();
$("#new_subcategory").hide();


                
            

