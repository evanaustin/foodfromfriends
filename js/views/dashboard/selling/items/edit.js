App.Dashboard.EditItem = function() {
    function listener() {
        var img_added = false;

        // Initialize imaging
        App.Image.init();

        // Re-initialize imaging on window resize
        $(window).resize(function(e) {
            App.Image.init();
        
            if (img_added) {
                $('.image-box input[type=file]').trigger('change');
            }
        });

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
            App.Image.onceSelected($(this), e);
            img_added = true;
        });


        // Re-populate subcategory select menu
        $('#item-categories').on('change', function() {
            $('#item-subcategories').prop('disabled', false).empty().focus().append('<option selected disabled>Select item subcategory</option>');

            subcategories.forEach(function(sub) {
                if ($(this).val() == sub.item_category_id) {
                    $('#item-subcategories').append($('<option>', {
                        value: sub.id, 
                        text: sub.title.charAt(0).toUpperCase() + sub.title.slice(1)
                    }));
                } 
            }, this);
        });
        
        
        // Re-populate varieties select menu
        $('#item-subcategories').on('change', function() {
            $('#item-varieties').prop('disabled', false).empty().focus()
                .append('<option selected disabled>Select item variety</option>')
                .append('<option value="0">None</option>');

            var varieties_exist = false;

            varieties.forEach(function(vari) {
                if ($(this).val() == vari.item_subcategory_id) {
                    varieties_exist = true;

                    $('#item-varieties').append($('<option>', {
                        value:  vari.id, 
                        text:   vari.title.charAt(0).toUpperCase() + vari.title.slice(1)
                    }));
                } 
            }, this);

            if (varieties_exist == true) {
                $('#item-varieties').prop('disabled', false).focus().removeClass('hidden');
            } else {
                $('#item-varieties').prop('disabled', true).empty().append('<option selected disabled>(no varieties)</option>').addClass('hidden');
            }
        });


        // Edit item
        $('#edit-item').on('submit', function(e) {
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

                if ($('.suggested-photo').hasClass('active')) {
                    formdata.append('similar-photo', $('.suggested-photo.active').data('image-id'));
                }

                data = formdata;
            } else { 
                data = $form.serialize();
            }
            
            if ($form.parsley().isValid()) {
                App.Util.loading('.save');

                App.Ajax.postFiles('dashboard/selling/items/edit', data, 
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


        // Remove item image
        $('a.remove-image').on('click', function(e) {
            e.preventDefault();

            var id = $(this).data('item-id');

            bootbox.confirm({
                closeButton: false,
                message: '<div class="text-center">Please confirm you want to remove the current image</div>',
                buttons: {
                    confirm: {
                        label: 'Remove image',
                        className: 'btn-danger'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'btn-muted'
                    }
                },
                callback: function(result) {
                    var data = {
                        item_id : $('a.remove-image').data('item-id')
                    };

                    if (result === true) {
                        if ($('div.image-box').hasClass('existing-image')) {
                            App.Util.loading('.save');
                            App.Image.destroy('dashboard/selling/items/remove-image', data);
                        } else {
                            App.Image.discard(id);
                        }
                    }
                }
            });
        });


        // Remove item
        $('a.remove-item').on('click', function(e) {
            e.preventDefault();

            var data = {
                'item_id': $('input[name="id"]').val()
            };

            bootbox.confirm({
                closeButton: false,
                message: '<div class="text-center">Please confirm you want to remove this item</div>',
                buttons: {
                    confirm: {
                        label: 'Remove item',
                        className: 'btn-danger'
                    },
                    cancel: {
                        label: 'Cancel',
                        className: 'btn-muted'
                    }
                },
                callback: function(result) {
                    if (result === true) {
                        App.Util.loading('.remove');

                        App.Ajax.post('dashboard/selling/items/remove-item', data, 
                            function(response) {
                                App.Util.finishedLoading('.remove');
                                toastr.success('This item has been removed');
                                $('main').fadeOut(1000);

                                setTimeout(function() {
                                    window.location = PUBLIC_ROOT + 'dashboard/selling/items/overview';
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