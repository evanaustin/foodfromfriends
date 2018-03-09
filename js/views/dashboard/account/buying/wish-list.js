App.Dashboard.WishList = function() {
    function listener() {
        var wishlist = {
            'remove': [],
            'new': []
        };
        
        $('.subcategory .btn').on('click', function() {
            if (!$(this).hasClass('active')) {
                $(this).addClass('active');

                // Add to wishlist
                wishlist.new.push({
                    'item_category_id': $(this).parents('.category').data('cat'),
                    'item_subcategory_id': $(this).data('sub')
                });
            } else {
                $(this).removeClass('active');

                if ($(this).data('id') != null) {
                    // Remove from wishlist
                    wishlist.remove.push({
                        'id': $(this).data('id')
                    });
                } else {
                    // Remove from new
                    var index = wishlist.new.indexOf(wishlist.new.find(wish => wish.sub === $(this).data('sub')));
                    if (index > -1) wishlist.new.splice(index, 1);
                }
            }
        });

        $('#wish-list').on('submit', function(e) {
            e.preventDefault();
            
            App.Ajax.post('dashboard/account/buying/save-wishes', {wishlist:wishlist},
                function(response) {
                    App.Util.msg('Your wishes were saved! <a href="' + PUBLIC_ROOT + 'user/' + user_slug + '">Go view your profile  <i class="fa fa-angle-right"></i></a>', 'success');
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