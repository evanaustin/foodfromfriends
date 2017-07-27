$(document).ready(function() {
    // Loop through the App object and execute all listener() methods
    // belonging to PascalCase objects.
    // ------------------------------------------------------------------------

    function callListeners(obj, stack) {
        for (var property in obj) {
            if (!obj.hasOwnProperty(property)) {
                continue;
            }

            // If this is a child object, keep going until we find a listener
            if (typeof obj[property] == 'object'
                && property[0].toUpperCase() == property[0]) {

                callListeners(obj[property], stack + '.' + property);
            }

            // If this is a listener method, go ahead and run it
            else if (property == 'listener') {
                // Print method to the console
                var listener = 'App' + stack + '.listener()';
                console.log(listener);

                // Get the stack in array form.  If we were going to call
                // App.Auction.ItemGrid.listener(), children would be
                // ["Auction", "ItemGrid"]
                var children = stack.split('.');

                // Remove empty array elements
                children = $.grep(children, function(n) { return(n); });

                // Execute the listener
                switch(children.length) {
                    case 0:
                        App.listener();
                        break;
                    case 1:
                        App[children[0]].listener();
                        break;
                    case 2:
                        App[children[0]][children[1]].listener();
                        break;
                    case 3:
                        App[children[0]][children[1]][children[2]].listener();
                        break;
                    case 4:
                        App[children[0]][children[1]][children[2]][children[3]].listener();
                        break;
                    case 5:
                        App[children[0]][children[1]][children[2]][children[3]][children[4]].listener();
                        break;
                }
            }
        }
    }

    // config.debug && console.group('Listeners:');
    callListeners(App, '');
    // config.debug && console.groupEnd();
});
