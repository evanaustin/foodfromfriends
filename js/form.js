App.Form = function() {
    function listener() {
        /*
        * Parsley
        */
        $('form[data-parsley-validate]').parsley({
            errorClass: 'has-danger',
            successClass: 'has-success',
            classHandler: function(ParsleyField) {
                return ParsleyField.$element.parents('.form-group');
            },
            errorsContainer: function(ParsleyField) {
                return ParsleyField.$element.parents('.form-group');
            },
            errorsWrapper: '<div class="form-control-feedback"></div>',
            errorTemplate: '<span></span>'
        });

        window.Parsley.on('field:error', function() {
            this.$element.addClass('form-control-danger');
        });

        window.Parsley.on('field:success', function() {
            this.$element.addClass('form-control-success');
        });

        /*
        * File input
        */
        $('input[type="file"][data-toggle="custom-file"]').on('change', function (ev) {
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
    }

	return {
        listener: listener
    };
}();