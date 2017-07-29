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

    $('#food-subcategories').append('<option value="0">Other</option>');
});

// Display other input field
$('#food-subcategories').on('change', function() {
    if ($(this).val() == 0) {
        $('#other-option').show(function() {
            $('#other-subcategory').focus();
        });
    } else {
        $('#other-option').hide();
    }
});

$('#other-subcategory').on('keyup change', function() {
    var other = $(this);

    $('#food-subcategories option').each(function(id, el) {
        if (el.text.charAt(0).toUpperCase() + el.text.slice(1) == other.val().charAt(0).toUpperCase() + other.val().slice(1)) {
            el.setAttribute('selected', true);
            other.val('');
            $('#other-option').hide();
            toastr.info(el.text + ' already exists as an option');

            return false;
        } else {
            el.removeAttribute('selected');
        }
    });
});

$('#quantity').on('keyup change', function() {
    if ($(this).val() == 0) {
        $('#available').prop('checked', false);
        $('#unavailable').prop('checked', true);
    } else {
        $('#available').prop('checked', true);
        $('#unavailable').prop('checked', false);
    } 
});

$('#add-listing').on('submit', function(e) {
	e.preventDefault();
    
    $form = $(this);

    if ($(this).parsley().isValid()) {
        App.Ajax.post('dashboard/food-listings/add-new', $form.serialize(), 
            function(response) {
                toastr.success('Your listed has been created!');
                
                // clear form
                $form.each(function() {
                    this.reset();
                    $('div.form-group').removeClass('has-success');
                    $('input, select').removeClass('form-control-success');
                });
            },
            function(response) {
                $form.siblings('div.alert').addClass('alert-danger').html('<i class="fa fa-exclamation-triangle"></i> ' + response.error).show();
            }
        );
    }
});	