App.Ajax = function() {

    // Print AJAX error causes to the console
    // ------------------------------------------------------------------------
    function handleAjaxErrors(a, b) {
        0 === a.status ?        console.log('Not connected.  Verify Network.') 
        : 404 == a.status ?     console.log('Requested page not found.  [404]') 
        : 500 == a.status ?     console.log('Internal Server Error  [500].') 
        : 'parsererror' === b ? console.log('Requested JSON parse failed.') 
        : 'timeout' === b ?     console.log('Time out error.') 
        : 'abort' === b ?       console.log('Ajax request aborted.') 
        :                       console.log('Uncaught Error.  ' + a.responseText);
    };

    // Send AJAX request
    // ------------------------------------------------------------------------
    function ajaxRequest(req_method, data_type, endpoint, data, success_callback, errors_callback, uses_formdata) {
        uses_formdata = uses_formdata || false;

        $.ajax({
            type: req_method,
            headers: {
                'x-csrf-token': $('meta[name="csrf-token"]').attr('content')
            },
            url: PUBLIC_ROOT + 'ajax/' + endpoint + '.php',
            cache: false,
            data: data,
            dataType: data_type,
            contentType: (uses_formdata === true 
                ? false 
                : 'application/x-www-form-urlencoded; charset=UTF-8'
            ),
            processData: (uses_formdata === true ? false : true),
            error: function(xhr, exception) { 
                handleAjaxErrors(xhr, exception); 
            },
            success: function(data, text_status, xhr) {
                if (data_type == 'html') {
                    if (success_callback && typeof(success_callback) === 'function') {
                        success_callback(data, text_status, xhr);
                    } 
                } else if (data_type == 'json') {
                    console.log(data);
                    if (data.error == null) {
                        if (success_callback && typeof(success_callback) === 'function') {
                            success_callback(data, text_status, xhr);
                        } 
                    } else {
                        if (errors_callback && typeof(errors_callback) === 'function') {
                            errors_callback(data, text_status, xhr);
                        } 
                    }
                }
            }
        }); 
    }

    // 4 public methods for sending an AJAX request
    // ------------------------------------------------------------------------
    function post(endpoint, data, success_callback, errors_callback) {
        ajaxRequest('POST', 'json', endpoint, data, success_callback, errors_callback);
    }

    function get(endpoint, data, success_callback, errors_callback) {
        ajaxRequest('GET', 'json', endpoint, data, success_callback, errors_callback);
    }

    function postFiles(endpoint, data, success_callback, errors_callback) {
        ajaxRequest('POST', 'json', endpoint, data, success_callback, errors_callback, true);
    }

    function getHTML(endpoint, data, callback) {
        ajaxRequest('GET', 'html', endpoint, data, callback);
    }

    // forms.register(options) is a shorthand way of invoking the post() and
    // postFiles() methods above.  Call it from any listener() function. 
    // 
    // Options:
    //     form (selector) (required)
    //     button (selector) (optional; defaults to button[type=submit])
    //     endpoint (required)
    //     data (object or callback that returns an object) (optional)
    //     template_data (can be defined in a wrapper for this method -- see js/_admin2/admin.js)
    //     success (callback after successful post) (optional)
    //     failure (callback after unsuccessful post) (optional)
    //     messages: (object or callback) {success, failure, parsley_failure, toastr_success, toastr_failure, alert_selector} (all optional)
    //     loading (selector) (optional)
    //     debug (bool) (optional -- prints useful data to console)
    //     formdata (bool) (default = false; set to true if sending images or files with FormData)
    //     parsley (bool) (default = true; whether to use parsley validation on form)
    // ------------------------------------------------------------------------
    /*var forms = {
        register: function(options) {
            var $f = $(options.form);
            var self = this;

            // If options.debug isn't defined, default to printing messages in
            // Dev & Stage and hiding them in Prod.
            // ----------------------------------------------------------------
            if (typeof options.debug == 'undefined') {
                options.debug = (ENV != 'prod');
            }

            // Listen for form submit
            // ----------------------------------------------------------------
            $f.off('submit.Parsley').on('submit', function(event) {
                event.preventDefault();

                options.debug && console.group(options.form + ' submission');

                // Run the messages callback if it isn't an object to start with
                // ------------------------------------------------------------
                if (!!(options.messages && options.messages.constructor && options.messages.call && options.messages.apply)) {
                    options.debug && console.log('Messages is a callback');
                    messages = options.messages();

                    if (messages === false) {
                        options.debug && console.log('Aborted in message-evaluating stage');
                        options.debug && console.groupEnd();
                        BWC.Util.finishedLoading(options.loading);
                        $(b).prop('disabled', false);
                        return false;
                    }
                } else {
                    messages = options.messages || {};
                }

                // Make sure it passes JS validation
                // ------------------------------------------------------------
                var p = options.parsley || true;
                if (p === true && $f.parsley().validate() !== true) {
                    e = messages.parsley_failure || 'Please correct the errors in the form before proceeding.';
                    toastr.error(e);
                    options.debug && console.groupEnd();
                    return false;
                } else if (p !== true) {
                    options.debug && console.log('Parsley validation disabled');
                }

                var display = options.loading_display || 'block';
                BWC.Util.loading(options.loading, display);

                // Disable the submit button
                // ------------------------------------------------------------
                var b = options.button || options.form + ' *[type=submit]';
                $(b).prop('disabled', true);

                // Get data to send to the server.  
                // ------------------------------------------------------------
                // If options.data is a callback, evaluate it
                if (!!(options.data && options.data.constructor && options.data.call && options.data.apply)) {
                    options.debug && console.log('Data is a callback');

                    // If it returns false, abort
                    if (options.data(event) !== false) {
                        var d = options.data(event);
                    } else {
                        options.debug && console.log('Aborted in data-gathering stage');
                        options.debug && console.groupEnd();
                        BWC.Util.finishedLoading(options.loading);
                        $(b).prop('disabled', false);
                        return false;
                    }

                } else {
                    options.debug && console.log('Data is not a callback');

                    // If an object was passed in, pass it along in our 
                    // AJAX request.
                    // If options.data is undefined, serialize the form inputs --
                    // every element in this form with a name attribute will be
                    // serialized.  See https://github.com/macek/jquery-serialize-object
                    var d = options.data || $f.serializeObject();
                }

                // Add template-specific data
                // Example: admin template includes data on which product 
                // the user is managing -- auctions, donation portals, etc
                // ------------------------------------------------------------
                var f = options.formdata || false;

                if (options.template_data) {
                    var td = options.template_data;
                    for (var key in td) {
                        if (td.hasOwnProperty(key)) {
                            if (f === false) {
                                d[key] = td[key];
                            } else {
                                d.append(key, td[key]);
                            }
                        }
                    }
                }

                options.debug && console.log('Sent: ', d);

                alert_selector = messages.alert_selector || '#alerts';

                // Send the data to the server
                // ------------------------------------------------------------
                if (f === false) {
                    BWC.Ajax.post(
                        options.endpoint, 
                        d, 
                        function(response) { self.ajaxSuccess(response, options, b, messages, d); }, 
                        function(response) { self.ajaxFailure(response, options, b, messages, d); }
                    );
                } else {
                    BWC.Ajax.postFiles(
                        options.endpoint, 
                        d, 
                        function(response) { self.ajaxSuccess(response, options, b, messages, d); }, 
                        function(response) { self.ajaxFailure(response, options, b, messages, d); }
                    );
                }
            });
        },

        ajaxSuccess: function(response, options, button, messages, data) {
            options.debug && console.log('Received: ', response);

            if (options.success && typeof(options.success) === "function") {
                options.success(response, data);
            } 

            alert_selector = (
                typeof response.alert_selector != 'undefined' 
                ? response.alert_selector
                : alert_selector
            );

            if (typeof response.msg != 'undefined' && response.msg != '') {
                BWC.Util.msg(response.msg, 'success', alert_selector);
            } else {
                if (typeof messages.success != 'undefined') {
                    BWC.Util.msg(messages.success, 'success', alert_selector);
                }
            }

            if (typeof response.toastr_msg != 'undefined' && response.toastr_msg != '') {
                toastr.success(response.toastr_msg);
            } else {
                if (typeof messages.toastr_success != 'undefined') {
                    toastr.success(messages.toastr_success);
                }
            }

            BWC.Util.finishedLoading(options.loading);
            $(button).prop('disabled', false);
            options.debug && console.groupEnd();

        },

        ajaxFailure: function(response, options, button, messages, data) {
            options.debug && console.log('Received: ', response);

            if (options.failure && typeof(options.failure) === "function") {
                options.failure(response, data);
            }

            alert_selector = (
                typeof response.alert_selector != 'undefined' 
                ? response.alert_selector
                : alert_selector
            );

            if (typeof response.msg != 'undefined' && response.msg != '') {
                BWC.Util.msg(response.msg, 'error', alert_selector);
            } else {
                if (typeof messages.failure != 'undefined') {
                    BWC.Util.msg(messages.failure, 'error', alert_selector);
                }
            }

            if (typeof response.toastr_msg != 'undefined' && response.toastr_msg != '') {
                toastr.error(response.toastr_msg);
            } else {
                if (typeof messages.toastr_failure != 'undefined') {
                    toastr.error(messages.toastr_failure);
                }
            }

            BWC.Util.finishedLoading(options.loading);
            $(button).prop('disabled', false);
            options.debug && console.groupEnd();
        },

        catchTransmissionErrors: function(eventInfo) {
            toastr.error('We were unable to process this request.');
            BWC.Util.finishedLoading();
            $('[type=submit]').prop('disabled', false);
            //console.log(eventInfo);
            //console.log(JSON.stringify(eventInfo));
            console.groupEnd();
        }
    }*/

    return {
        // forms: forms,
        post: post,
        get: get,
        postFiles: postFiles,
        getHTML: getHTML
    };
}();