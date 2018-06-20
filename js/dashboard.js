App.Dashboard = function() {
    function listener() {
        $(document).on('shown.bs.modal', '.bootbox.modal', function() {
            $(this).find('.modal-header').append("<button type='button' class='bootbox-close-button close' data-dismiss='modal'>&times;</button>");
        });

        // Enable keyboard shortcuts
        $(document).keyup(function(e) {
            if (!($('input, textarea, select').is(':focus'))) {
                switch(e.keyCode) {
                    case 69: // e
                        $('#sidebar-exchange-options').trigger('click');
                        break;
                    case 73: // i
                        $('#sidebar-items').trigger('click');
                        break;
                    case 79: // o
                        $('#sidebar-orders').trigger('click');
                }
            }
        });
    };

    return {
        listener: listener
    };
}();