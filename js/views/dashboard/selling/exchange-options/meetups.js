$('#start-time').timepicker({
    template: 'dropdown',
    defaultTime: false,
    icons: {
        up: 'fa fa-chevron-up',
        down: 'fa fa-chevron-down'
    }
});

$('#end-time').timepicker({
    template: 'dropdown',
    defaultTime: false,
    icons: {
        up: 'fa fa-chevron-up',
        down: 'fa fa-chevron-down'
    }
});

$('#start-time').timepicker().on('changeTime.timepicker', function(e) {
    console.log('The time is ' + e.time.value);
});

$('#add-meetup').on('submit', function(e) {
	e.preventDefault();
    App.Util.hideMsg();
    
    $form = $(this);
    data = $form.serialize();

    if ($form.parsley().isValid()) {
        App.Ajax.post('dashboard/selling/exchange-options/add-meetup', data, 
            function(response) {
                App.Util.msg('Your new meetup was added', 'success');
                App.Util.animation($('button[type="submit"]'), 'bounce');
                App.Util.finishedLoading();

                $form.find('input').val('');

                $('#meetups').removeClass('hidden');
                $('#meetups').find('tbody:last-child').append('<tr><td>' + response.meetup.title + '</td><td>' + response.meetup.address + '</td><td>' + response.meetup.time + '</td></tr>')
            },
            function(response) {
                App.Util.msg(response.error, 'danger');
                App.Util.finishedLoading();
            }
        );
    }
});