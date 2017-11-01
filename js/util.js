App.Util = function() {

    // Switch to hi-res graphics on retina-capable devices
    function loadRetinaGraphics() {
        if($('img.replace-2x').css('font-size') == "1px") {                       
            var els = $('img.replace-2x').get();
            for(var i = 0; i < els.length; i++) {
                var src = els[i].src;
                var ext = src.split('.').pop();
                var fn  = src.split('.');
                fn.pop();
                fn  = fn.join('.');
                src = fn + '@2x.' + ext;
                els[i].src = src;
            }
        }
    };

    // Error message handling 
    // You have to create a container element, eg: <div id="alert"></div>
    // alert_type = 'error', 'success', 'info'
    
    function msg(message, alert_type, form) {
        var $alert_container = form.siblings('div.alerts') || $('div.alerts');

        hideMsg('all', $alert_container);

        message = htmlEntityDecode(message);
        console.log(htmlEntityDecode(message));

        $alert_container
            .append('<div class="alert alert-' +  alert_type + '"><a class="close" data-dismiss="alert">×</a><span>' + message + '</span></div>')
            .fadeIn();
    }

    // Leave msg_type blank to clear all alerts
    // Leave container blank to call all alerts of a given type in a single container only
    function hideMsg(msg_type, container) {
        var msg_type = msg_type || 'all';
        var $container = container || $('div.alerts');

        switch (msg_type) {
            case 'all':
                $container.fadeOut();
                break;
            case 'error':
                $container.hasClass('alert-danger').fadeOut();
                break;
            case 'success':
                $container.hasClass('alert-success').fadeOut();
                break;
            case 'info':
                $container.hasClass('alert-info').fadeOut();
                break;
        }
    }

    function fadeAndRemove(element) {
        $(element).fadeOut(100, function() {
            $(this).remove();
        });
    };

    function animation(element, animation, direction = undefined, remove = false, rmEl = undefined) {
        element.addClass('animated ' + animation);

        if (direction == 'in') {
            element.removeClass('hidden');
        }

        element.one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
            element.removeClass('animated ' + animation);

            if (direction == 'out') {
                element.addClass('hidden');
            }
            
            if (remove === true) {
                if (rmEl !== undefined) {
                    rmEl.remove();
                } else {
                    element.remove();
                }
            }
        });
    }

    function loading(element, display_type) {
        var spinner = element || '';

        if ($('i.loading-icon' + spinner).length > 0) {
            $('i.loading-icon' + spinner).css('visibility', 'visible').css('opacity', '1');
            $('button[type=submit]').prop('disabled', true);
        }
    }

    function finishedLoading(element) {
        var spinner = element || '';

        if ($('i.loading-icon' + spinner).length > 0) {
            $('i.loading-icon' + spinner).css('visibility', 'hidden').css('opacity', '0');
            $('button[type=submit]').prop('disabled', false);
        }
    }

    function slidebar(controller, action, target, e) {
        if (e != null) {
            e.stopPropagation();
        }

        switch(action) {
            case 'open':
                controller.open('slidebar-' + target);
                break;
            case 'close':
                controller.close('slidebar-' + target);
                break;
            case 'toggle':
                controller.toggle('slidebar-' + target);
        };

        /* $(controller.events).on('opened', function () {
            $('[canvas="container"]').addClass('close-any-slidebar');
        }).on('closed', function () {
            $('[canvas="container"]').removeClass('close-any-slidebar');
        }); */
    };

    // Replaces the some HTML entities with their Unicode values
    function htmlDecode(input) {
        var entities= {
            "&amp;": "\u0026",
            "&lt;": "\u003c",
            "&gt;": "\u003e",
            "&#039;": "\u0027",
            "&#39;": "\u0027",
            "&quot;": "\u0022"
        };           
        for (var prop in entities) {
            if (entities.hasOwnProperty(prop)) {
                input = input.replace(new RegExp(prop, "g"), entities[prop]);
            }
        }
        return input;
    };

    // Returns video ID and provider given a YouTube or Vimeo URL
    // EX: provider=youtube, id=PQLnmdOthmA; provider=vimeo, id=22080133
    function parseVideoURL(data) {

        if (data == '' || typeof data == 'undefined') {
            return {
                provider : '',
                id : ''
            };
        }

        data.match(/htt(p|ps):\/\/(player.|www.|m.)?(vimeo\.com|youtu(be\.com|\.be))\/(video\/|embed\/|watch\?v=|#\/watch\?v=)?([A-Za-z0-9._%-]*)(\&\S+)?/);
     
        var match = {
            provider: null,
            url: RegExp.$3,
            id: RegExp.$6
        }
     
        if(match.url == 'youtube.com' || match.url == 'youtu.be'){
            match.provider = 'youtube';   
            if (match.id.length < 11) {
                all_parameters = urlParams(data);
                match.id = all_parameters['v'];
            }
        } else if(match.url == 'vimeo.com'){
            match.provider = 'vimeo';
        }
     
        return {
            provider : match.provider,
            id : match.id
        };
    };

    // Returns all the GET variables as an array.  Useful for getting youtube's v= parameter
    function urlParams(url) {
        var query_parts = url.split(/[\?&]+/);
        var url_params = {};
        for (i=0; i<query_parts.length; i++) {
            var item = query_parts[i].split('=');
            url_params[item[0]] = item[1];
        } 
        return url_params;
    };

    function parseURL(url){
        parsed_url = {}

        if ( url == null || url.length == 0 )
            return parsed_url;

        protocol_i = url.indexOf('://');
        parsed_url.protocol = url.substr(0,protocol_i);

        remaining_url = url.substr(protocol_i + 3, url.length);
        domain_i = remaining_url.indexOf('/');
        domain_i = domain_i == -1 ? remaining_url.length - 1 : domain_i;
        parsed_url.domain = remaining_url.substr(0, domain_i);
        parsed_url.path = domain_i == -1 || domain_i + 1 == remaining_url.length ? null : remaining_url.substr(domain_i + 1, remaining_url.length);

        domain_parts = parsed_url.domain.split('.');
        switch ( domain_parts.length ){
            case 2:
              parsed_url.subdomain = null;
              parsed_url.host = domain_parts[0];
              parsed_url.tld = domain_parts[1];
              break;
            case 3:
              parsed_url.subdomain = domain_parts[0];
              parsed_url.host = domain_parts[1];
              parsed_url.tld = domain_parts[2];
              break;
            case 4:
              parsed_url.subdomain = domain_parts[0];
              parsed_url.host = domain_parts[1];
              parsed_url.tld = domain_parts[2] + '.' + domain_parts[3];
              break;
        }

        parsed_url.parent_domain = parsed_url.host + '.' + parsed_url.tld;

        return parsed_url;
    };

    // Performs rot13 operation on a string.
    // https://en.wikipedia.org/wiki/ROT13
    // Usage: 'string'.rot13();    or      rot13('string');
    String.prototype.rot13 = rot13 = function(s) {
        return (s ? s : this).split('').map(function(_) {
            if (!_.match(/[A-Za-z]/)) return _;
            var c = Math.floor(_.charCodeAt(0) / 97);
            var k = (_.toLowerCase().charCodeAt(0) - 83) % 26 || 26;
            return String.fromCharCode(k + ((c == 0) ? 64 : 96));
        }).join('');
    }

    // Returns a tricksy email address.
    // Must specify 'hide' and 'esrever' classes in CSS.
    // Usage: $('#container').html(mailto('xyz@example.com', 'Email us').rot13());
    function mailto(handle, domain, link_text) {
        var email_address = handle + '@' + domain;
        var email = email_address.split('@');
        if (typeof link_text == 'undefined' || link_text == '' || link_text == email_address)
            link_text = email[0]+'@<span class="hide">spammers!</span><span class="esrever">'+email[1].split("").reverse().join("")+'</span>';
        var l = '<a href="mailto:'+email[0]+'%40'+email[1]+'" rel="nofollow" target="_blank">'+link_text+'</a>';
        return l.rot13();
    }

    // UA detection
    // Usage example:
    //    Browser name:    BrowserDetect.browser
    //    Browser version: BrowserDetect.version
    //    OS name:         BrowserDetect.OS
    var BrowserDetect = {
        init: function () {
            this.browser = this.searchString(this.dataBrowser) || "An unknown browser";
            this.version = this.searchVersion(navigator.userAgent) || this.searchVersion(navigator.appVersion) || "an unknown version";
            this.OS = this.searchString(this.dataOS) || "an unknown OS"
        },
        searchString: function (b) {
            for (var a = 0; a < b.length; a++) {
                var c = b[a].string,
                    d = b[a].prop;
                this.versionSearchString = b[a].versionSearch || b[a].identity;
                if (c) {
                    if (-1 != c.indexOf(b[a].subString)) return b[a].identity
                } else if (d) return b[a].identity
            }
        },
        searchVersion: function (b) {
            var a = b.indexOf(this.versionSearchString);
            if (-1 != a) return parseFloat(b.substring(a + this.versionSearchString.length + 1))
        },
        dataBrowser: [
            {string:navigator.userAgent,subString:"Chrome",identity:"Chrome"},
            {string:navigator.userAgent,subString:"OmniWeb",versionSearch:"OmniWeb/",identity:"OmniWeb"},
            {string:navigator.vendor,subString:"Apple",identity:"Safari",versionSearch:"Version"},
            {prop:window.opera,identity:"Opera",versionSearch:"Version"},
            {string:navigator.vendor,subString:"iCab",identity:"iCab"},
            {string:navigator.vendor,subString:"KDE",identity:"Konqueror"},
            {string:navigator.userAgent,subString:"Firefox",identity:"Firefox"},
            {string:navigator.vendor,subString:"Camino",identity:"Camino"},
            {string:navigator.userAgent,subString:"Netscape",identity:"Netscape"},
            {string:navigator.userAgent,subString:"MSIE",identity:"Explorer",versionSearch:"MSIE"},
            {string:navigator.userAgent,subString:"Gecko",identity:"Mozilla",versionSearch:"rv"},
            {string:navigator.userAgent,subString:"Mozilla",identity:"Netscape",versionSearch:"Mozilla"}
        ],
        dataOS: [
            {string:navigator.platform,subString:"Win",identity:"Windows"},
            {string:navigator.platform,subString:"Mac",identity:"Mac"},
            {string:navigator.userAgent,subString:"iPhone",identity:"iPhone"},
            {string:navigator.userAgent,subString:"iPad",identity:"iPad"},
            {string:navigator.userAgent,subString:"iPod",identity:"iPod"},
            {string:navigator.userAgent,subString:"Android",identity:"Android"},
            {string:navigator.userAgent,subString:"Blackberry",identity:"Blackberry"},
            {string:navigator.userAgent,subString:"webOS",identity:"webOS"},
            {string:navigator.userAgent,subString:"Windows Phone",identity:"Windows Phone"},
            {string:navigator.platform,subString:"Linux",identity:"Linux"}
        ]
    };

    function getUA() {
        BrowserDetect.init();
        var ua = {
            'os': BrowserDetect.OS.toLocaleLowerCase(),
            'browser': BrowserDetect.browser.toLocaleLowerCase(),
            'version': BrowserDetect.version
        };
        return ua;
    }

    // Appends classes for the OS, browser, and browser version to the HTML tag
    function appendUserAgentClasses() {
        ua = getUA();
        $('html').addClass(ua.os + ' ' + ua.browser + ' v' + ua.version); 
        return ua.os + ' ' + ua.browser + ' v' + ua.version;
    }

    // Scroll to an anchor point on the screen
    function scrollToAnchor(anchor_name, offset) {
        if (typeof anchor_name == 'undefined') {
            var anchor = $('body');
        } else {
            var anchor = $("a[name='"+ anchor_name +"']");
            if (!anchor.length) {
                anchor = $('#' + anchor_name);
            }
        }
        var slider_offset = (offset && offset != '' ? parseInt(offset) : 0);
        $('html,body').animate({scrollTop: anchor.offset().top - $('header').height() + slider_offset}, 'slow');
    };

    // JS doesn't have multidimensional arrays, it has objects.
    // Use this function to return the length of an object.
    function objectLength(obj) {
        var size = 0, key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) size++;
        }
        return size;
    };

    // Works for ints, floats, pos, neg
    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    };

    // For converting org names, item titles, etc to slugs on the fly in admin panels
    function convertToSlug(source) {
        return source
            .toLowerCase()                // change everything to lowercase
            .replace(/^\s+|\s+$/g, "")    // trim leading and trailing spaces       
            .replace(/[_|\s]+/g, "-")     // change all spaces and underscores to a hyphen
            .replace(/[^a-z0-9-]+/g, "")  // remove all non-alphanumeric characters except the hyphen
            .replace(/[-]+/g, "-")        // replace multiple instances of the hyphen with a single instance
            .replace(/^-+|-+$/g, "")      // trim leading and trailing hyphens              
            ;
    }

    function scrollToTop() {
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    }

    function closePopovers() {
        $('html').on('click', function (e) {
            $('.popover-link').each(function () {
                //the 'is' for buttons that trigger popups
                //the 'has' for icons within a button that triggers a popup
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });
    }

    // Returns true if our CSS thinks we're in mobile/tablet/whatever mode
    // This applies to Bootstrap 2.3.2 and will be phased out in favor of
    // deviceSize() as we transition to Bootstrap 3.
    function isDeviceSize(device_type) {
        switch (device_type) {
            case 'phone':
                return (window.innerWidth <= 767);
                break;
            case 'tablet':
                return (window.innerWidth <= 979 && window.innerWidth > 767);
                break;
            case 'desktop':
                return (window.innerWidth > 979);
                break;
        }
    }

    function deviceSize(device_type) {
        switch (device_type) {
            case 'phone':
                return (window.innerWidth <= 767);
                break;
            case 'tablet':
                return (window.innerWidth <= 991 && window.innerWidth > 767);
                break;
            case 'sm-desktop':
                return (window.innerWidth > 991 && window.innerWidth <= 1199);
                break;
            case 'lg-desktop':
                return (window.innerWidth > 1199);
                break;
        }
    }

    function confirm(heading, question, cancelButtonTxt, okButtonTxt, callback, cancelCallback) {
        var confirmModal = 
          $('<div class="modal hide fade">' +    
              '<div class="modal-header">' +
                '<a class="close" data-dismiss="modal" >&times;</a>' +
                '<h3>' + heading +'</h3>' +
              '</div>' +

              '<div class="modal-body">' +
                '<p>' + question + '</p>' +
              '</div>' +

              '<div class="modal-footer">' +
                '<a href="#" id="cancelButton" class="btn no-btn" data-dismiss="modal">' + 
                  cancelButtonTxt + 
                '</a>' +
                '<a href="#" id="okButton" class="btn yes-btn">' + 
                  okButtonTxt + 
                '</a>' +
              '</div>' +
            '</div>');

        confirmModal.find('#okButton').click(function(event) {
            if(callback && typeof callback == 'function')
                callback();
            confirmModal.modal('hide');
        });

        confirmModal.find('#cancelButton').click(function(event) {
            if(cancelCallback && typeof cancelCallback == 'function')
                cancelCallback();
        });

        confirmModal.modal('show');     
    }

    function numberFormat(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    function countDown(seconds, output_container, end_message, reload_page) {
        var reload_page = reload_page || false;

        var timer = setInterval(function() {        
            runCountdown();
        }, 1000); 

        var runCountdown = function() {
            if (seconds > 0) {

                var cd = formatSeconds(seconds);

                // If between 10 min and 1 hr, do minutes countdown
                if (seconds >=600 && seconds <= 3601) {
                    // Only update on the minute
                    if (seconds % 60 == 0) {
                        $(output_container).html(cd.minutes + ' <span class="period period_m">m</span>');
                    }
                }

                // Do mm:ss countdown if there are less than 10 minutes left
                if (seconds < 600) 
                    $(output_container).html('<span>'+cd.minsec+'</span>');

                seconds--;
            } else if (seconds == 0) {
                $(output_container).html(end_message);
                clearInterval(timer);
                if (reload_page === true)
                    location.replace(window.location.href);
            } 
        }

        var formatSeconds = function(secs) {
            var pad = function(n) {
                return (n < 10 ? "0" + n : n);
            };
            
            var h = Math.floor(secs / 3600);
            var m = Math.floor((secs / 3600) % 1 * 60); 
            var s = Math.floor((secs / 60) % 1 * 60);
            
            return {
                'minutes': m,
                'minsec': m + ':' + pad(s)
            };
        };
    }

    function saveTabState() {
        // Enable link to tab
        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-tabs a[href=#'+url.split('#')[1]+']').tab('show');
            scrollToTop();
        } 

        // Change hash for page reload
        $('.nav-tabs a').on('click', function () {
            window.location.hash = $(this).attr('href');
        })
    }

    // Make sure header and D360 footer stay "fixed"
    // when the user focuses on a form element
    function fixFixedPositioning() {
        $(document)
            .on('blur', 'input, textarea, select', function(e) {
                setTimeout(function() {
                    $('body').removeClass('fixfixed');
                }, 50);
            })

            .on('focus', 'input, textarea, select', function() {
                $('body').addClass('fixfixed');
            });
    }

    // Trims whitespace from beginning and end of string
    function trim(str) {
        str = str.replace(/^\s+/, '');
        for (var i = str.length - 1; i >= 0; i--) {
            if (/\S/.test(str.charAt(i))) {
                str = str.substring(0, i + 1);
                break;
            }
        }
        return str;
    }

    function arraysEqual(a, b) {
        if (!a || !b)
            return false;

        if (a.length != b.length)
            return false;

        for (var i = 0, l = a.length; i < l; i++) {
            if (a[i] instanceof Array && b[i] instanceof Array) {
                if (!arraysEqual(a[i], b[i]))
                    return false;
            } else if (a[i] != b[i]) {
                return false;
            }
        }
        return true;
    }

    // Takes the content of an element and converts it to markdown syntax
    function extractMarkdown(element) {
        return toMarkdown(trim($(element).html()));
    }

    var slideOver = {
        show: function($article) {
            $article.find('div.slide-over').addClass('active');
            $article.find('div.background').addClass('active');
            $article.addClass('expanded');
        },

        hide: function($article) {
            $article.find('div.slide-over').removeClass('active');
            $article.find('div.background').removeClass('active');
            $article.removeClass('expanded');
        },

        toggle: function($article) {
            $so = $article.find('div.slide-over');
            $bg = $article.find('div.background');
            if ($so.hasClass('active')) {
                this.hide($article);
            } else {
                this.shut($article);
                this.show($article);                
            }
        },

        // close open taxons without any meaningful user input
        shut: function($article) {
            var that = this,
                exempted_item_id = (typeof $article != 'undefined' ? $article.data('item-id') : 0);
            $('article.expanded[data-item-id!=' + exempted_item_id + ']').each(function() {
                if (!$(this).hasClass('active'))
                    that.hide($(this));
            });
        },

        bindEvents: function(element) {
            var self = this;

            if (element == '' || typeof element == 'undefined')
                element = 'article';

            // Slide details down
            if (!$.browser.mobile) {
                    
                $(element).mouseenter(function() {
                    self.show($(this));
                });

                $(element).mouseleave(function() {
                    self.hide($(this));
                });

            } else {

                $(element).on('click', function(e) {
                    if ($(e.target).is('a, input'))
                        return;
                    self.toggle($(this));
                });

                // Close empty taxons when clicking outside of them
                $(element).on('click', function(e) {
                    if ($(e.target).is('a, button, textarea, input, article *'))
                        return;
                    self.shut($(this));
                });

            }
        }
    };

    function d(objectName) {
        return typeof objectName !== 'undefined';
    }

    // Get current date/time in ISO formate for tagging analytics events
    function getISOString(dateString) {
        if (dateString) {
            var d = new Date(dateString);
        } else {
            var d = new Date();
        }
        return d.toISOString();
    }

    // For some reason we have to reinitialize before Segment can attach
    // automated revenue and increment events to Mixpanel.
    function confirmAnalyticsIdentity() {
        var user = analytics.user();
        analytics.identify(user.id());
    }

    // Ensures fields with dollar signs remain numeric
    function formatDollarAmount($amount) {
        var val = $amount.val();

        // More than one decimal point?  Remove it
        // and all that follows
        var parts = val.split('.');
        if (parts.length > 2) {
            val = parts[0] + '.' + parts[1].charAt(0) + parts[1].charAt(1);
        }

        // Force no more than two digits behind decimal
        else if (parts.length == 2 && parts[1].length > 2) {
            val = parts[0] + '.' + parts[1].charAt(0) + parts[1].charAt(1);
        }

        // Force dollar sign at the beginning
        if (val.charAt(0) != '$') {
            val = '$' + val;
        }

        // Has illegal chars?  Remove them
        if (!isNumber(val.substring(1))) {
            val = '$' + val.replace(/[^0-9.]/g, '');
        }

        $amount.val(val);
    }

    function htmlEntityDecode(string) {
        return $('<textarea>').html(string).text();
    }

    // Address autocomplete
    if (typeof google != 'undefined') {
        var geo = {
            autocomplete: {},

            // Ex from bid processing page: 
            // input = 'geo-billing-address'
            // form = '#billing_form'
            // input_prefix = '#billing-'
            init: function(params) {
                var input = params.input || 'geo-address';
                var form = params.form || '#address';
                var input_prefix = params.input_prefix || '#';

                if (!$('#' + input).length) {
                    return false;
                }

                // Create the autocomplete object, restricting the search
                // to geographical location types.
                geo.autocomplete[input] = new google.maps.places.Autocomplete(
                    document.getElementById(input),
                    { 
                        types: ['geocode'] 
                    }
                );

                // When the user selects an address from the dropdown,
                // populate the address fields in the form.
                google.maps.event.addListener(geo.autocomplete[input], 'place_changed', function() {
                    var place = geo.autocomplete[input].getPlace();
                    var address = geo.readAddress(place.address_components);

                    $(form + ' div.full-address input, ' + form + ' div.full-address select').val('');

                    $.each(address, function(k, v) {
                        if (k != 'country' && k != 'state') {
                            $(input_prefix + k).val(v);
                        } else {
                            if (k == 'state' && typeof params.state_select != 'undefined' && params.state_select === false) {
                                $(input_prefix + k).val(v);
                            } else {
                                $(form + ' select[name=' + k + ']').val(v);
                            }
                        }

                        
                    });

                    $(form + ' div.short-address').hide();
                    $(form + ' div.full-address').fadeIn();
                });
            },

            readAddress: function(c) {
                var address = {
                    address1: '',
                    city: '',
                    state: '',
                    country: '',
                    postal_code: ''
                };

                // Address line 1
                for (var i = 0; i < c.length; i++) {
                    var address_type = c[i].types[0];
                    if (address_type == 'street_number') {
                        address.address1 += c[i]['long_name'] + ' ';
                    }
                }
                for (var i = 0; i < c.length; i++) {
                    var address_type = c[i].types[0];
                    if (address_type == 'route') {
                        address.address1 += c[i]['long_name'];
                    }
                }

                // City
                for (var i = 0; i < c.length; i++) {
                    var address_type = c[i].types[0];
                    if (address_type == 'locality') {
                        address.city += c[i]['long_name'];
                    }
                }
                if (address.city == '') {
                    for (var i = 0; i < c.length; i++) {
                        var address_type = c[i].types[0];
                        if (address_type == 'administrative_area_level_3') {
                            address.city += c[i]['long_name'];
                        }
                    }
                }

                // State
                for (var i = 0; i < c.length; i++) {
                    var address_type = c[i].types[0];
                    if (address_type == 'administrative_area_level_1') {
                        address.state += c[i]['long_name'];
                    }
                }

                // Country
                for (var i = 0; i < c.length; i++) {
                    var address_type = c[i].types[0];
                    if (address_type == 'country') {
                        address.country += c[i]['short_name'];
                    }
                }

                // Postal code
                for (var i = 0; i < c.length; i++) {
                    var address_type = c[i].types[0];
                    if (address_type == 'postal_code') {
                        address.postal_code += c[i]['long_name'];
                    }
                }
                if (address.postal_code == '') {
                    for (var i = 0; i < c.length; i++) {
                        var address_type = c[i].types[0];
                        if (address_type == 'postal_code_prefix') {
                            address.postal_code += c[i]['long_name'];
                        }
                    }
                }

                return address;
            },

            // Bias the autocomplete object to the user's geographical location,
            // as supplied by the browser's 'navigator.geolocation' object.
            // type can be "billing" or "shipping"
            locate: function(input) {
                var input = input || 'geo-address';

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var geolocation = new google.maps.LatLng(
                            position.coords.latitude, 
                            position.coords.longitude
                        );

                        var circle = new google.maps.Circle({
                            center: geolocation,
                            radius: position.coords.accuracy
                        });

                        geo.autocomplete[input].setBounds(circle.getBounds());
                    });
                }
            },

            showFullAddressForm: function($form) {
                $form.find('div.short-address').hide();
                $form.find('div.full-address').fadeIn();
            }
        };
    }

    function openMarkdownLinksInNewTab() {
        $('.markdown a').filter(function() {
            return this.hostname != window.location.hostname;
        }).attr('target', '_blank');
    }

    // Ensure user-supplied links begin with a protocol
    function forceProtocol(url) {
        if (url && url.substring(0, 7) != 'http://' && url.substring(0, 8) != 'https://') {
            return 'http://' + url;
        }

        return url;
    }

    // Like the PHP strip_tags function: removes HTML tags form a strign
    function stripTags(input, allowed) {
        // Make sure the allowed arg is a string containing only tags in 
        // lowercase (<a><b><c>)
        allowed = (((allowed || '') + '')
            .toLowerCase()
            .match(/<[a-z][a-z0-9]*>/g) || [])
            .join(''); 
      
        var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi;
        var commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
        
        return input.replace(commentsAndPhpTags, '')
            .replace(tags, function($0, $1) {
                return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
            });
    }

    function capitalize(string) {
        return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
    }

    // Public pointers to private
    // functions and properties
    return {
        loadRetinaGraphics: loadRetinaGraphics,
        msg: msg,
        hideMsg: hideMsg,
        fadeAndRemove: fadeAndRemove,
        animation: animation,
        loading: loading,
        finishedLoading: finishedLoading,
        slidebar: slidebar,
        htmlDecode: htmlDecode,
        parseVideoURL: parseVideoURL,
        urlParams: urlParams,
        parseURL: parseURL,
        rot13: rot13,
        mailto: mailto,
        browserDetect: BrowserDetect,
        userAgent: getUA,
        appendUserAgentClasses: appendUserAgentClasses,
        scrollToAnchor: scrollToAnchor,
        objectLength: objectLength,
        isNumber: isNumber,
        convertToSlug: convertToSlug,
        scrollToTop: scrollToTop,
        closePopovers: closePopovers,
        isDeviceSize: isDeviceSize,
        deviceSize: deviceSize,
        confirm: confirm,
        numberFormat: numberFormat,
        countDown: countDown,
        saveTabState: saveTabState,
        fixFixedPositioning: fixFixedPositioning,
        trim: trim,
        arraysEqual: arraysEqual,
        extractMarkdown: extractMarkdown,
        slideOver: slideOver,
        d: d,
        getISOString: getISOString,
        confirmAnalyticsIdentity: confirmAnalyticsIdentity,
        formatDollarAmount: formatDollarAmount,
        htmlEntityDecode: htmlEntityDecode,
        geo: geo,
        openMarkdownLinksInNewTab: openMarkdownLinksInNewTab,
        forceProtocol: forceProtocol,
        stripTags: stripTags,
        capitalize: capitalize
    };
    
}();

jQuery.fn.extend({
    ensureLoad: function(handler) {
        return this.each(function() {
            if(this.complete) {
                handler.call(this);
            } else {
                $(this).load(handler);
            }
        });
    }
});