$('#add-listing').on('submit', function(e) {
	e.preventDefault();
    
    $form = $(this);
    
    App.Ajax.post('dashboard/food-listings/add-new', $form.serialize(), 
		function(response) {
            toastr.success('Your listed has been created!')
		},
		function(response) {
            $form.siblings('div.alert').addClass('alert-danger').html('<i class="fa fa-exclamation-triangle"></i> ' + response.error).show();
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

$('#quantity').on('change', function() {
    if ($(this).val() == 0) {
        $('#available').prop('checked', false);
        $('#unavailable').prop('checked', true);
    } else {
        $('#available').prop('checked', true);
        $('#unavailable').prop('checked', false);
    } 
});