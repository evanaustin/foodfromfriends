App.Dashboard.EditItemListing = function() {
    function listener() {
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


        // Re-populate subcategory select menu
        $('#item-categories').on('change', function() {
            $('#item-subcategories').prop('disabled', false).empty().focus().append('<option selected disabled>Select an item subcategory</option>');

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
            $('#item-varieties').prop('disabled', false).empty().focus().append('<option selected disabled>Select an item variety</option>');

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

                App.Ajax.postFiles('dashboard/grower/items/edit', data, 
                    function(response) {
                        App.Util.finishedLoading('.save');
                        App.Util.msg('Your item has been updated! Click <strong><a href="' + PUBLIC_ROOT + response.link + '">here</a></strong> to view it.', 'success');
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
                message: '<div class="text-center">Please confirm you want to remove the current image</div>',
                buttons: {
                    confirm: {
                        label: 'Confirm',
                        className: 'btn-warning'
                    },
                    cancel: {
                        label: 'Cancel',
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
                            App.Image.destroy('dashboard/grower/items/remove-image', data);
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
                message: '<div class="text-center">Please confirm you want to remove this listing</div>',
                buttons: {
                    confirm: {
                        label: 'Confirm',
                        className: 'btn-warning'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'btn-muted'
                    }
                },
                callback: function(result) {
                    if (result === true) {
                        App.Util.loading('.remove');

                        App.Ajax.post('dashboard/grower/items/remove-listing', data, 
                            function(response) {
                                App.Util.finishedLoading('.remove');
                                toastr.success('Your listing has been removed');
                                $('main').fadeOut(1000);

                                setTimeout(function() {
                                    window.location = PUBLIC_ROOT + 'dashboard/grower/items/overview';
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
    }

    return {
        listener: listener
    };
}();