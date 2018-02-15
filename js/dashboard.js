App.Dashboard = function() {
    function listener() {
        $(document).on('shown.bs.modal', function (event) {
            $(this).find('.modal-header').append("<button type='button' class='bootbox-close-button close' data-dismiss='modal'>&times;</button>");
        });
    };

    return {
        listener: listener
    };
}();