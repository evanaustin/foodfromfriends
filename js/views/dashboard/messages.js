App.Dashboard.Message = function() {
    function listener() {
        $('#send-message textarea').keypress(function (e) {
            if (e.which == 13 && !e.shiftKey) {        
                $(this).closest('form').submit();
                e.preventDefault();
                return false;
            }
        });

        $('#new-message form').on('submit', function(ev) {
            ev.preventDefault();
            
            $form = $(this);
            var data = $form.serializeArray();
            
            var formdata = {};
            $.each(data, function() {
                formdata[this.name] = this.value;
            });

            App.Ajax.post('dashboard/messages/send-message', data, 
                function(response) {
                    $('#messages').append('<div class="row">' +
                        '<div class="col-md-9 offset-md-3">' +
                            '<fable class="margin-btm-50em">' +
                                '<cell class="flexend">' +
                                    '<div class="bubble inline-block align-left muted animated bounceIn">' +
                                        formdata.message +
                                    '</div>' +
                                '</cell>' +
                        
                                '<cell class="justify-center flexcenter flexgrow-0 margin-left-1em d-none d-md-block">' +
                                    '<div class="user-photo no-margin" style="background-image: url(\'https://s3.amazonaws.com/foodfromfriends/' + ENV + ((formdata['sent-by'] == 'grower') ? '/grower-operation-images/' : '/profile-photos/') + formdata.filename + '.' + formdata.fileext + '\');"></div>' +
                                '</cell>' +
                            '</fable>' +
                        '</div>' +
                    '</div>');

                    $form[0].reset();

                    App.Util.scrollToID('new-message');
                },
                function(response) {
                    toastr.error(response.error);
                }
            );
        });
    }

    return {
        listener: listener
    };
}();