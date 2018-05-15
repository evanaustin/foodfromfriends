App.Image = function () {
    var uploadDisabled = [false, false, false, false];

    var source = [];

    var crop = [];

    var files = [];

    var frame = {
        w: Math.floor($('div.image-container').outerWidth()),
        h: Math.floor($('div.image-container').outerHeight())
    };

    function init() {
        // $('div.image-container').height(Math.floor($('div.image-container').width() * 540 / 630));
        $('div.image-container').height(Math.floor($('div.image-container').width() * 800 / 933));
    }

    function selectFile(el) {
        el.find('input[type=file]').trigger('click');
    }

    function onceSelected(el, e) {
        // reset frame height
        this.frame.h = Math.floor($('div.image-container').outerHeight());
        
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

        if (file.size > 5 * 1024 * 1024) {
            App.Util.msg('Please select a file less than 5MB in size', 'danger');
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

                // show delete button
                App.Util.animation($('a.remove-image'), 'bounceIn', 'in');
                
                // hide help text
                App.Util.animation($('small#profilePhotoHelp'), 'zoomOut', 'out');

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
    }

    function getCropData() {
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
    }

    function discard() {
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

        // animate discard button departure
        App.Util.animation($('a.remove-image'), 'bounceOut', 'out');
    }
    
    function destroy(path, data = null) {
        var self = this;
        
        App.Ajax.post(path, data,
            function(response) {
                self.discard();
                App.Util.msg('Your image has been deleted', 'success');
                App.Util.animation($('a.remove-image'), 'bounce');
                App.Util.finishedLoading();
            },
            function(response) {
                App.Util.msg(response.error, 'danger');
                App.Util.finishedLoading();
            }
        ); 
    }

    return {
        uploadDisabled: uploadDisabled,
        source: source,
        crop: crop,
        files: files,
        frame: frame,
        init: init,
        selectFile: selectFile,
        onceSelected: onceSelected,
        getCropData: getCropData,
        discard: discard,
        destroy: destroy
    }
}();