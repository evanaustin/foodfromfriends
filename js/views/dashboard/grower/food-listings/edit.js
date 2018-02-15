// Initialize imaging
App.Image.init();


// Upload image
$('div.image-box').on('click', function(e) {
    // only one image so key is always 0
    var key = 0;

    if (App.Image.uploadDisabled[key] === false) {
        App.Image.selectFile($(this));
    }
});

$('div.image-box input[type=file]').on('click', function(e) {
    e.stopImmediatePropagation();
});

$('div.image-box input[type=file]').on('change', function(e) {
    var success = App.Image.onceSelected($(this), e);
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


// Edit listing
$('#edit-listing').on('submit', function(e) {
    e.preventDefault();

    App.Util.hideMsg();

    $form = $(this);
    
    if (window.FormData) {
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
        App.Util.loading('.save');

        App.Ajax.postFiles('dashboard/grower/food-listings/edit', data, 
            function(response) {
                App.Util.finishedLoading('.save');
                toastr.success('Your listing has been updated!');
            },
            function(response) {
                App.Util.msg(response.error, 'danger');
                App.Util.finishedLoading('.save');
            }
        );
    }
});


// Remove listing image
$('a.remove-image').on('click', function(e) {
    e.preventDefault();

    var id = $(this).data('listing-id');

    bootbox.confirm({
        closeButton: false,
        message: 'You want to remove the current image?',
        buttons: {
            confirm: {
                label: 'Oh yeah',
                className: 'btn-warning'
            },
            cancel: {
                label: 'Nope',
                className: 'btn-muted'
            }
        },
        callback: function(result) {
            var data = {
                listing_id : $('a.remove-image').data('listing-id')
            };

            if (result === true) {
                if ($('div.image-box').hasClass('existing-image')) {
                    App.Util.loading('.save');
                    App.Image.destroy('dashboard/grower/food-listings/remove-image', data);
                } else {
                    App.Image.discard(id);
                }
            }
        }
    });
});


// Remove listing
$('a.remove-listing').on('click', function(e) {
    e.preventDefault();

    var data = {
        'listing_id': $('input[name="id"]').val()
    };

    bootbox.confirm({
        closeButton: false,
        message: 'You sure you want to remove this listing?',
        buttons: {
            confirm: {
                label: 'Oh yeah',
                className: 'btn-warning'
            },
            cancel: {
                label: 'Nope',
                className: 'btn-muted'
            }
        },
        callback: function(result) {
            if (result === true) {
                App.Util.loading('.remove');

                App.Ajax.post('dashboard/grower/food-listings/remove-listing', data, 
                    function(response) {
                        App.Util.finishedLoading('.remove');
                        toastr.success('Your listing has been removed');
                        $('main').fadeOut(1000);

                        setTimeout(function() {
                            window.location = PUBLIC_ROOT + 'dashboard/grower/food-listings/overview';
                        }, 1500);
                    },
                    function(response) {
                        App.Util.msg(response.error, 'danger');
                        App.Util.finishedLoading('.remove');
                    }
                );
            }
        }
    });
});