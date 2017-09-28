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

		// animate discard button departure
		App.Util.animation($('a.remove-image'), 'bounceOut', 'out');
	},
	
	destroy: function() {
		var self = this;
		
		App.Ajax.post('dashboard/account/edit-profile/remove-image', null,
			function(response) {
				self.discard();
				toastr.success('Your image has been deleted');
				App.Util.finishedLoading();
			},
			function(response) {
				App.Util.msg(response.error, 'danger');
				App.Util.finishedLoading();
			}
		); 
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


// Remove profile image
$('a.remove-image').on('click', function(e) {
	e.preventDefault();
	
	App.Util.animation($(this), 'bounce');

	bootbox.confirm({
		message: 'You want to remove the current image?',
		buttons: {
			confirm: {
				label: 'Oh yeah',
				className: 'btn-primary'
			},
			cancel: {
				label: 'Nope',
				className: 'btn-secondary'
			}
		},
		callback: function(result) {
			if (result === true) {
				if ($('div.image-box').hasClass('existing-image')) {
					App.Util.loading();
					image.destroy();
				} else {
					image.discard();
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
	    
		App.Ajax.postFiles('dashboard/account/edit-profile/save-basic-information', data, 
			function(response) {
				toastr.success('Your basic information has been updated!');
				App.Util.finishedLoading();
			},
			function(response) {
				App.Util.msg(response.error, 'danger');
				App.Util.finishedLoading();
			}
		);
	}
});	