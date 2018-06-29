App.Form = function() {
    function listener() {
        /*
        * Focus input group
        */
        $('div.input-group.w-addon').on('focus', 'input, select, textarea', function() {
            $(this).parent().addClass('focused');
        }).on('blur', 'input, select, textarea', function() {
            $(this).parent().removeClass('focused');
        });

        /*
        * Parsley
        */
        $('form').parsley({
            errorClass: 'has-danger',
            successClass: 'has-success',
            classHandler: function(ParsleyField) {
                return ParsleyField.$element.closest('.form-group');
            },
            errorsContainer: function(ParsleyField) {
                return ParsleyField.$element.closest('.form-group');
            },
            errorsWrapper: '<div class="form-control-feedback"></div>',
            errorTemplate: '<span></span>',
            requiredMessage: 'Please fill out this field.'
        });

        window.Parsley.on('field:error', function() {
            this.$element.removeClass('success').addClass('danger');
        });
        
        window.Parsley.on('field:success', function() {
            this.$element.removeClass('danger')/* .addClass('success') */;
        });

        // prevent autofocus of invalid field(s)
        window.Parsley.on('form:validated', function() {
            this.$element.find('input, select').blur()
        });

        $('form :input').change(function() {
            $('form').data('changed', true);
        });
        
        $('form').on('submit', function() {
            $('form').data('changed', false);
        });

        $('body.dashboard a:not([data-toggle="modal"]):not([data-toggle="collapse"]):not([data-toggle="dropdown"]):not(.action)').on('click', function(e) {
            var href = $(this).attr('href');

            if ($('form').data('changed') || $('.form-group').hasClass('has-danger')) {
                e.preventDefault();

                bootbox.confirm({
                    closeButton: false,
                    message: 'You have unsaved changes. Are you sure you want to leave this page?',
                    buttons: {
                        confirm: {
                            label: 'Leave anyway',
                            className: 'btn-danger'
                        },
                        cancel: {
                            label: 'Stay here',
                            className: 'btn-muted'
                        }
                    },
                    callback: function(result) {
                        if (result === true) {
                            window.location.replace(href);
                        }
                    }
                });
            }
        });


        /*
        * File input
        */
        $('input[type="file"][data-toggle="custom-file"]').on('change', function(ev) {
            const $input = $(this);
            const target = $input.data('target');
            const $target = $(target);

            if (!$target.length)
                return console.error('Invalid target for custom file', $input);

            if (!$target.attr('data-content'))
                return console.error('Invalid `data-content` for custom file target', $input);

            // set original content so we can revert if user deselects file
            if (!$target.attr('data-original-content'))
                $target.attr('data-original-content', $target.attr('data-content'));

            const input = $input.get(0);

            let name = $.isPlainObject(input)
                && $.isPlainObject(input.files)
                && $.isPlainObject(input.files[0])
                && $.isString(input.files[0].name) ? input.files[0].name : $input.val();

            if (name === null || name === '') {
                name = $target.attr('data-original-content');
            } else {
                name = name.split('\\').pop();
            }

            $target.attr('data-content', name);
        });

        // toggle .active on suggested photos
        $('.suggested-photo').on('click', function() {
            $('.suggested-photo.active').not($(this)).removeClass('active');
            $(this).toggleClass('active');
        });


        // autosize textarea
        autosize($('.input-group > textarea'));
    }

	return {
        listener: listener
    };
}();