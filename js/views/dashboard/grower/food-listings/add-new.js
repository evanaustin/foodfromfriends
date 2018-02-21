// Initialize imaging
App.Image.init();


// Upload image
$('div.image-box').on('click', function(e) {
    // only one image so key is always 0
    var key = 0;
    
    if (App.Image.uploadDisabled[0] === false) {
        App.Image.selectFile($(this));
    }
});

$('div.image-box input[type=file]').on('click', function(e) {
    e.stopImmediatePropagation();
});

$('div.image-box input[type=file]').on('change', function(e) {
    var success = App.Image.onceSelected($(this), e);
});


// Discard uploaded image
$('a.remove-image').on('click', function(e) {
    e.preventDefault();
    App.Image.discard();
});


// Re-populate subcategory select menu
$('#item-categories').on('change', function() {
    $('#item-subcategories').prop('disabled', false).empty().focus().append('<option selected disabled>Select subcategory</option>');

    item_subcategories.forEach(function(sub) {
         if ($(this).val() == sub.food_category_id) {
            $('#item-subcategories').append($('<option>', {
                value: sub.id, 
                text: sub.title.charAt(0).toUpperCase() + sub.title.slice(1)
            }));
        } 
    }, this);
});


// Re-populate varieties select menu
$('#item-subcategories').on('change', function() {
    $('#item-varieties').empty().append('<option selected disabled>Select variety</option>');

    var varieties = false;

    item_varieties.forEach(function(vari) {
        if ($(this).val() == vari.food_subcategory_id) {
            varieties = true;

            $('#item-varieties').append($('<option>', {
                value:  vari.id, 
                text:   vari.title.charAt(0).toUpperCase() + vari.title.slice(1)
            }));
        } 
    }, this);

    if (varieties == true) {
        // are varieties
        $('#item-varieties').prop('disabled', false).focus().removeClass('hidden');
    } else {
        // are varieties
        $('#item-varieties').prop('disabled', true).empty().append('<option selected disabled>(no varieties)</option>').addClass('hidden');
    }
});


// Display other input field
$('#food-subcategories').on('change', function() {
    if ($(this).val() == 0) {
        if ($('#other-option').is(':not(:visible)')) {
            App.Util.animation($('#other-option'), 'flipInX', 'in');
            $('#other-subcategory').focus();
        }
    } else {
        if ($('#other-option').is(':visible')) {
            App.Util.animation($('#other-option'), 'fadeOut', 'out');
        }
    }
});


// Check if other already exists as a subcategory
$('#other-subcategory').on('keyup change', function() {
    var other = $(this);

    $('#food-subcategories option').each(function(id, el) {
        if (el.text.charAt(0).toUpperCase() + el.text.slice(1) == other.val().charAt(0).toUpperCase() + other.val().slice(1)) {
            el.setAttribute('selected', true);
            other.val('');
            App.Util.animation($('#other-option'), 'fadeOut', 'out');
            toastr.info(el.text + ' already exists as an option');

            return false;
        } else {
            el.removeAttribute('selected');
        }
    });
});


// Update availability as quantity is changed
$('#quantity').on('keyup change', function() {
    if ($(this).val() == 0) {
        $('#available').prop('checked', false);
        $('#unavailable').prop('checked', true);
    } else {
        $('#available').prop('checked', true);
        $('#unavailable').prop('checked', false);
    } 
});


// Add listing
$('#add-listing').on('submit', function(e) {
    e.preventDefault();
    App.Util.hideMsg();

    $form = $(this);

    if (window.FormData){
        formdata = new FormData($form[0]);
        
        if (App.Image.files.length > 0) {
            $.each(App.Image.files, function(k, v) {
                formdata.append('img' + k, v);
            });
    
            formdata.append('images', JSON.stringify(App.Image.getCropData()));
        }

        data = formdata;
    } else {
        data = $form.serialize();
    }

    if ($form.parsley().isValid()) {
        App.Util.loading();
        
        App.Ajax.postFiles('dashboard/grower/food-listings/add-new', data, 
            function(response) {
                App.Util.msg('Your listing has been created! Click <strong><a href="' + PUBLIC_ROOT + 'dashboard/grower/food-listings/edit?id=' + response.id + '">here</a></strong> to edit it, or add another new listing below.', 'success');
                App.Util.animation($('button[type="submit"]'), 'bounce');
                App.Util.finishedLoading();

                // clear form
                $form.each(function() {
                    this.reset();
                    $('div.form-group').removeClass('has-success');
                    $('input, select').removeClass('form-control-success');
                });

                // reset subcategories
                $('#food-subcategories').prop('disabled', true).empty().focus().append('<option selected disabled>Select a food subcategory</option>');

                // hide other option
                $('#other-option').fadeOut();

                // clear image
                App.Image.discard();
            },
            function(response) {
                App.Util.msg(response.error, 'danger');
                App.Util.finishedLoading();
            }
        );
    }
});	