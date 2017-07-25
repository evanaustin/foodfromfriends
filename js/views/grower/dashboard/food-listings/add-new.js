$('#add-listing').on('submit', function(e) {
	e.preventDefault();
     console.log($(this).serialize());
    App.Ajax.post('grower_dashboard', $(this).serialize(), 
		function(response) {
			$('#add-listing p.listing-message').html('<i class="fa fa-check"></i> Awesome!');
		},
		function(response) {
			$('#locavore-signup p.listing-message').html(response.error);
		}
	 );	
});	

// Re-populate subcategory select menu
$('#food-categories').on('change', function() {
    $('#food-subcategories').prop('disabled', false).empty().focus().append('<option selected disabled>Select a food subcategory</option>');

    food_subcategories.forEach(function(sub) {
         if ($(this).val() == sub.food_category_id) {
            $('#food-subcategories').append($('<option>', {
                value: sub.id, 
                text: sub.title.charAt(0).toUpperCase() + sub.title.slice(1)
            }));
        } 
    }, this);

    $('#food-subcategories').append('<option value="other">Other</option>');
});

// Display other input field
$('#food-subcategories').on('change', function() {
    if ($(this).val() == 'other') {
        $('#other-option').show(function() {
            $('#other-subcategory').focus();
        });
    } else {
        $('#other-option').hide();
    }
});