App.Dashboard.AddItem = function() {
    function listener() {
        var img_added = false;

        // Initialize imaging
        App.Image.init();

        $(window).resize(function(e) {
            App.Image.init();

            if (img_added) {
                $('.image-box input[type=file]').trigger('change');
            }
        });

        // Upload image
        $('.image-box').on('click', function(e) {
            // only one image so key is always 0
            var key = 0;
            
            if (App.Image.uploadDisabled[0] === false) {
                App.Image.selectFile($(this));
            }
        });

        $('.image-box input[type=file]').on('click', function(e) {
            e.stopImmediatePropagation();
        });

        $('.image-box input[type=file]').on('change', function(e) {
            App.Image.onceSelected($(this), e);
            img_added = true;
        });


        // Discard uploaded image
        $('a.remove-image').on('click', function(e) {
            e.preventDefault();
            App.Image.discard();
        });


        // Re-populate subcategory select menu
        $('#item-categories').on('change', function() {
            $('#item-subcategories').prop('disabled', false).empty().focus().append('<option selected disabled>Select subcategory</option>');

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
            $('#name').attr('placeholder', subcategories[$('#item-subcategories').val() - 1]['title']).css('textTransform', 'capitalize');;

            $('#item-varieties').empty()
                .append('<option selected disabled>Select variety</option>')
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

        $('#item-varieties').on('change', function() {
            if ($(this).val() > 0) {
                $('#name').attr('placeholder', varieties[$(this).val() - 1]['title'] + ' ' + subcategories[$('#item-subcategories').val() - 1]['title']).css('textTransform', 'capitalize');;
            } else {
                $('#name').attr('placeholder', subcategories[$('#item-subcategories').val() - 1]['title']).css('textTransform', 'capitalize');;
            }
        });


        // Add item
        $('#add-item').on('submit', function(e) {
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

                if ($('.suggested-photo').hasClass('active')) {
                    if ($('.suggested-photo.active').length > 1) {
                        console.log
                        App.Util.msg('Please select only one photo', 'danger');
                        return;
                    }

                    formdata.append('similar-photo', $('.suggested-photo.active').data('image-id'));
                }

                data = formdata;
            } else {
                data = $form.serialize();
            }

            if ($form.parsley().isValid()) {
                App.Util.loading();
                
                App.Ajax.postFiles('dashboard/selling/items/add-new', data, 
                    function(response) {
                        App.Util.msg('Your item has been created! Click <a href="' + PUBLIC_ROOT + response.link + '">here</a> to view it now', 'success');

                        App.Util.animation($('button[type="submit"]'), 'bounce');
                        App.Util.finishedLoading();

                        if (response.new_image) {
                            $('#similar-items').find('.row').append(
                                '<div class="col-md-4">' +
                                    '<div class="image-box suggested-photo margin-top-2em" data-image-id="' + response.new_image.id + '" data-toggle="tooltip" data-title="Use this photo" data-placement="bottom">' +
                                        '<img src="https://s3.amazonaws.com/foodfromfriends/' + ENV + '/item-images/' + response.new_image.filename + '.' + response.new_image.ext + '" class="img-fluid rounded"/>' +
                                    '</div>' +
                                '</div>'
                            );
                        }

                        // clear form
                        /* $form.each(function() {
                            this.reset();
                            $('div.form-group').removeClass('has-success');
                            $('input, select').removeClass('success');
                        }); */

                        App.Image.discard();
                    },
                    function(response) {
                        App.Util.msg(response.error, 'danger');
                        App.Util.finishedLoading();
                    }
                );
            }
        });


        $('#suggest-item-modal').on('shown.bs.modal', function () {
            $('#suggest-item-form').siblings('.alerts').find('.alert').remove();
        });


        // Suggest new item type
        $('#suggest-item-form').on('submit', function(e) {
            e.preventDefault();
            App.Util.hideMsg();

            $form = $(this);

            var data = $form.serialize();

            if ($form.parsley().isValid()) {
                App.Util.loading('.suggest-item-submit');
                
                App.Ajax.post('dashboard/selling/items/suggest-item', data, 
                    function(response) {
                        $('input[name="type"]').val('');
                        $('textarea[name="comments"]').val('');

                        App.Util.msg('Thanks for the suggestion! We\'ll take a look and let you know if we decide to add it', 'success', $('#suggest-item-form'));
                        App.Util.finishedLoading('.suggest-item-submit');
                    },
                    function(response) {
                        App.Util.msg(response.error, 'danger', $('#suggest-item-form'));
                        App.Util.finishedLoading('.suggest-item-submit');
                    }
                );
            }
        });
    }

    return {
        listener: listener
    };
}();