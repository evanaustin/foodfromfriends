image = { 
    uploadDisabled: [false, false, false, false],

    source: [],

    crop: [],

    files: [],

     frame: {
        w: Math.floor($('div.image-box').outerWidth()),
        h: Math.floor($('div.image-box').outerHeight())
    }, 

    init: function() {
        $('div.image-box').height(Math.floor($('div.image-box').width() * 540 / 630));
    },

    selectFile: function(el) {
        el.find('input[type=file]').trigger('click');
    },

    onceSelected: function(el, e) {
        // reset frame height
        this.frame.h = Math.floor($('div.image-box').outerHeight());
        
        // clear slide-over image class
        $('div.image-box').removeClass('slide-over').unbind('mouseover').unbind('mouseout');

        // only one image so key is always 0
        var key = 0;

        var file = e.target.files[key];
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

        // Load the file into the cropping window
        var reader = new FileReader();
        reader.onload = (function (f) {
            return function(e) {
                var img = new Image;
                img.src = e.target.result;

                img.onload = function() {
                    var $img = $('div.image-box').find('img.file');

                    // Show & animate preview
                    $img.removeAttr('height').attr('src', img.src);
                    App.Util.animation($('div.image-box img.file'), 'pulse');

                    // Launch crop script
                    $img.cropbox({
                        width: self.frame.w,
                        height: self.frame.h,
                        maxZoom: 10.0,
                        zoom: 20
                    }).on('cropbox', function(e, data) {
                        self.crop[key] = {
                            x: Math.abs(data.cropX),
                            y: Math.abs(data.cropY),
                            w: Math.abs(data.cropW),
                            h: Math.abs(data.cropH)
                        };
                    });

                    // Show delete button
                    App.Util.animation($('a.remove-image'), 'bounceIn', 'in');

                    self.source[key] = {
                        w: img.width,
                        h: img.height
                    };

                    // Disable click-to-upload
                    self.uploadDisabled[key] = true;

                    self.files[key] = file;
                }
            };
        })(file);

        reader.readAsDataURL(file);
    },

    getCropData: function() {
        var data = [];
        var self = this;

        // only one image so key is always 0
        var key = 0;

        $('div.image-box').find('img.file').each(function() {
            if (self.uploadDisabled[key] === false) {
                return true;    // continue
            }

            data.push({
                source: self.source[key],
                crop: self.crop[key],
                key: key
            });
        });

        return {
            images: data,
            frame: self.frame
        };
    },

    discard: function() {
        // only one image so key is always 0
        var key = 0;

        this.uploadDisabled[key] = false;
        this.source.splice(key, 1);
        this.crop.splice(key, 1);
        this.files.splice(key, 1);

        var $img = $('div.image-box').find('img.file');

        if (typeof $img.data('cropbox') != 'undefined') {
            $img.data('cropbox').remove();
        }

        // reset
        $img.attr('src', PUBLIC_ROOT + '/media/placeholders/default-thumbnail.jpg');
        $('input[type="file"]').val('');

        // hide discard icon tooltip
        $('a.remove-image').tooltip('hide');

        // animate discard departure
        App.Util.animation($('a.remove-image'), 'bounceOut', 'out');
    }
}


// Initialize imaging
image.init();


// Upload image
$('div.image-box').on('click', function(e) {
    // only one image so key is always 0
    var key = 0;
    
    if (image.uploadDisabled[0] === false) {
        image.selectFile($(this));
    }
});

$('div.image-box input[type=file]').on('click', function(e) {
    e.stopImmediatePropagation();
});

$('div.image-box input[type=file]').on('change', function(e) {
    var success = image.onceSelected($(this), e);
});


// Discard uploaded image
$('a.remove-image').on('click', function(e) {
    e.preventDefault();
    image.discard();
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

    $('#food-subcategories').append('<option value="0">Other</option>');
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
        
        if (image.files.length > 0) {
            $.each(image.files, function(k, v) {
                formdata.append('img' + k, v);
            });
    
            formdata.append('images', JSON.stringify(image.getCropData()));
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
                image.discard();
            },
            function(response) {
                App.Util.msg(response.error, 'danger');
                App.Util.finishedLoading();
            }
        );
    }
});	