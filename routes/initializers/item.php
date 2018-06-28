<?php

$settings = [
    'title' => 'Item | Food From Friends'
];

if (isset($Routing->item_type)) {
    $SellerAccount = new GrowerOperation([
        'DB' => $DB,
        'slug' => $Routing->seller
    ], [
        'team'      => true,
        'exchange'  => true
    ]);

    if (isset($SellerAccount)) {
        $is_owner = isset($User) && isset($SellerAccount->TeamMembers[$User->id]);
        
        if ($Routing->item_account_type == 'wholesale') {
            // check if wholesale relationship exists between User:BuyerAccount and Seller
            if (isset($User->BuyerAccount)) {
                $wholesale_relationship = $User->BuyerAccount->retrieve([
                    'where' => [
                        'buyer_account_id' => $User->BuyerAccount->id,
                        'seller_id' => $SellerAccount->id,
                        'status'    => 2
                    ],
                    'table' => 'wholesale_relationships'
                ]);
            } else {
                $wholesale_relationship = false;
            }
        } else {
            $wholesale_relationship = false;
        }

        // Find seller's item that matches this subcategory
        $items = $SellerAccount->retrieve([
            'where' => [
                'grower_operation_id' => $SellerAccount->id,
                (($Routing->item_type == 'subcategory') ? 'item_subcategory_id' : 'item_variety_id') => $Routing->item_id,
                'is_wholesale' => $wholesale_relationship ? 1 : 0,
            ],
            'order' => 'quantity desc',
            'table' => 'items',
        ]);


        // Hash items
        $hashed_items = [];

        foreach($items as $raw_item) {
            $ThisItem = new Item([
                'DB' => $DB,
                'id' => $raw_item['id']
            ]);

            $in_cart = isset($User, $User->BuyerAccount->ActiveOrder, $User->BuyerAccount->ActiveOrder->Growers[$SellerAccount->id], $User->BuyerAccount->ActiveOrder->Growers[$SellerAccount->id]->Items[$ThisItem->id]);

            $hashed_items[$raw_item['id']] = [
                'price'     => _amount($ThisItem->price),
                'name'      => $ThisItem->title,
                'quantity'  => $ThisItem->quantity,
                'description' => $ThisItem->description,
                'rating'    => stars($ThisItem->rating),
                'filename'  => $ThisItem->Image->filename, 
                'ext'       => $ThisItem->Image->ext,
                'in_cart'   => $in_cart
            ];
        }


        // Initialize Item
        if (isset($_GET['package'])) {
            $Item = new Item([
                'DB' => $DB,
                'id' => \Num::clean_int($_GET['package'])
            ]);
        } else {
            $Item = new Item([
                'DB' => $DB,
                'id' => $items[0]['id']
            ]);
        }
        
        if (isset($Item->id) && !isset($Item->archived_on)) {
            $in_cart = isset($User, $User->BuyerAccount->ActiveOrder, $User->BuyerAccount->ActiveOrder->Growers[$SellerAccount->id], $User->BuyerAccount->ActiveOrder->Growers[$SellerAccount->id]->Items[$Item->id]);

            if ($in_cart) {
                $OrderItem = $User->BuyerAccount->ActiveOrder->Growers[$SellerAccount->id]->Items[$Item->id];
            }

            $exchange_options_available = [];
    
            if ($SellerAccount->Delivery && $SellerAccount->Delivery->is_offered)   $exchange_options_available []= 'delivery';
            if ($SellerAccount->Pickup && $SellerAccount->Pickup->is_offered)       $exchange_options_available []= 'pickup';
            if ($SellerAccount->Meetup && $SellerAccount->Meetup->is_offered)       $exchange_options_available []= 'meetup';
    
            $active_ex_op = (isset($User, $User->BuyerAccount->ActiveOrder->Growers[$SellerAccount->id]->Exchange)) ? $User->BuyerAccount->ActiveOrder->Growers[$SellerAccount->id]->Exchange->type : null;
    
            if (isset($User, $User->BuyerAccount, $User->BuyerAccount->Address, $User->BuyerAccount->Address->latitude, $User->BuyerAccount->Address->longitude)) {
                $geocode    = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins={$User->BuyerAccount->Address->latitude},{$User->BuyerAccount->Address->longitude}&destinations={$SellerAccount->latitude},{$SellerAccount->longitude}&key=" . GOOGLE_MAPS_KEY);
                $output     = json_decode($geocode);
                $distance   = explode(' ', $output->rows[0]->elements[0]->distance->text);
                $distance_miles = round((($distance[1] == 'ft') ? $distance[0] / 5280 : $distance[0]), 4);
            }
    
            $grower_stars   = ($SellerAccount->average_rating == 0) ? 'New' : stars($SellerAccount->average_rating);
            $item_stars     = ($Item->average_rating == 0) ? 'New' : stars($Item->average_rating);
    
            $ratings = $Item->retrieve([
                'where' => [
                    'item_id' => $Item->id
                ],
                'table' => 'item_ratings',
                'recent' => true
            ]);
    
            $settings['title'] = "{$Item->title} from {$SellerAccount->name} | Food From Friends";
        }
    }
}


?>