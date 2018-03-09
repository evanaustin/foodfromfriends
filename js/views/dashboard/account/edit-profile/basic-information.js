// Initialize imaging
App.Image.init();


// Upload image
$('div.image-box').on('click', function(e) {
	// only one image so key is always 0
	var key = 0;

	if (App.Image.uploadDisabled[0] === false) {
		App.Image.selectFile($(this));
	}
});

$('div.image-box input[type=file]').on('click', function(e) {
	e.stopImmediatePropagation();
});

$('div.image-box input[type=file]').on('change', function(e) {
	var success = App.Image.onceSelected($(this), e);
});


// Remove profile image
$('a.remove-image').on('click', function(e) {
	e.preventDefault();
	
	App.Util.animation($(this), 'bounce');

	bootbox.confirm({
        closeButton: false,
		message: '<div class="text-center">Please confirm you want to remove the current image</div>',
		buttons: {
			confirm: {
				label: 'Confirm',
				className: 'btn-warning'
			},
			cancel: {
				label: 'Cancel',
				className: 'btn-muted'
			}
		},
		callback: function(result) {
			if (result === true) {
				if ($('div.image-box').hasClass('existing-image')) {
					App.Util.loading();
					App.Image.destroy('dashboard/account/edit-profile/remove-image');
				} else {
					App.Image.discard();
				}
			}
		}
	});
});

$('#edit-basic-information').on('submit', function(e) {
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
		
		data = formdata;
	} else {
		data = $form.serialize();
	}
	
	if ($form.parsley().isValid()) {
		App.Util.loading();
	    
		App.Ajax.postFiles('dashboard/account/edit-profile/save-basic-information', data, 
			function(response) {
                App.Util.finishedLoading();
                App.Util.msg('Your information has been updated! <a href="' + PUBLIC_ROOT + 'user/' + user_slug + '">Go view your profile <i class="fa fa-angle-right"></i></a>', 'success');
			},
			function(response) {
                App.Util.finishedLoading();
				App.Util.msg(response.error, 'danger');
			}
		);
	}
});	