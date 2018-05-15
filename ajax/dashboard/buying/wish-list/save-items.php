<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$WishList = new WishList([
    'DB' => $DB,
]);

foreach($_POST['wishlist']['new'] as $wish) {
    try {
        $WishList->add([
            'buyer_account_id'      => $User->BuyerAccount->id,
            'item_category_id'      => $wish['item_category_id'],
            'item_subcategory_id'   => $wish['item_subcategory_id']
        ]);
    } catch(\Exception $e) {
        quit($e->getMessage());
    }
}

foreach($_POST['wishlist']['remove'] as $wish) {
    try {
        $WishList->delete('id', $wish['id']);
    } catch(\Exception $e) {
        quit($e->getMessage());
    }
}

echo json_encode($json);