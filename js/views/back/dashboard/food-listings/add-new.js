    image = { 
        uploadDisabled: [false, false, false, false],

        source: [],

        crop: [],

        files: [],

        frame: {
            w: $('img.file').width(),
            h: Math.floor($('img.file').width() * 1080 / 1240)
            // h: Math.floor($('img.file').width() * 824 / 1240)
        },

        // Hide image upload inputs in incompatible browsers
        /* init: function() {
            if (!window.File || !window.FileReader || !window.FileList || !window.Blob || !window.FormData) {
                $('#images-box').hide();
            }
        }, */

        selectFile: function(el) {
            el.find('input[type=file]').trigger('click');
        },

        onceSelected: function(el, e) {
            var file = e.target.files[0];
            var self = this;

            // App.Util.hideMsg();

            if (file.type != 'image/png' && file.type != 'image/jpeg') {
                App.Util.msg('Please select a JPG or PNG image', 'danger');
                return;
            }

            if (file.size > 3 * 1024 * 1024) {
                App.Util.msg('Please select a file less than 3MB in size', 'danger');
                return;
            }

            var $img = $('div.image-box').find('img.file');

            console.log($img);

            // Show the loading image
            // It's 1px too tall, so we fix the height here
            // TODO: get a gif editing program and edit the gif
            /* var h = $img.height();
            $img.attr({
                src: '/images/loading-box.gif',
                height: h
            }); */

            // Load the file into the cropping window
            var reader = new FileReader();
            reader.onload = (function (f) {
                return function(e) {
                    var img = new Image;
                    img.src = e.target.result;

                    img.onload = function() {
                        var $img = $('div.image-box').find('img.file');

                        // Show preview
                        $img.removeAttr('height').attr('src', img.src);

                        // Launch crop script
                        $img.cropbox({
                            width: self.frame.w,
                            height: self.frame.h,
                            maxZoom: 10.0,
                            zoom: 20
                        }).on('cropbox', function(e, data) {
                            self.crop[0] = {
                                x: Math.abs(data.cropX),
                                y: Math.abs(data.cropY),
                                w: Math.abs(data.cropW),
                                h: Math.abs(data.cropH)
                            };
                        });

                        // Add delete button to controls menu
                        // $img.next('div.cropControls').prepend('<button class="removeImg" data-key="' + key + '"><i class="fa fa-trash-o"></i></button>');

                        self.source = {
                            w: img.width,
                            h: img.height
                        };

                        // Disable click-to-upload
                        self.uploadDisabled[0] = true;

                        self.files[0] = file;
                    }
                };
            })(file);
            reader.readAsDataURL(file);
        },

        getCropData: function() {
            var data = [];
            var self = this;

            $('div.image-box').find('img.file').each(function() {
                if (self.uploadDisabled[0] === false) {
                    return true;    // continue
                }

                data.push({
                    source: self.source[0],
                    crop: self.crop[0]
                });
            });

            return {
                images: data,
                frame: self.frame
            };
        }
    }

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

$('div.image-box').on('click', function(e) {
    if (image.uploadDisabled[0] === false) {
        image.selectFile($(this));
    }
});

$('div.image-box input[type=file]').on('click', function(e) {
    e.stopImmediatePropagation();
});

$('div.image-box input[type=file]').on('change', function(e) {
    var success = image.onceSelected($(this), e);
    
    if (!success) {

    }
});

$('#add-listing').on('submit', function(e) {
	e.preventDefault();
    
    $form = $(this);

    if (window.FormData){
        data = new FormData($form[0]);
    } else {
        data = $form.serialize();
    }

    if ($form.parsley().isValid()) {
        App.Ajax.postFiles('dashboard/food-listings/add-new', data, 
            function(response) {
                toastr.success('Your listing has been created!');
                
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