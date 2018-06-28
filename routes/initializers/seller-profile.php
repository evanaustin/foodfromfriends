<?php

$settings = [
    'title' => 'Seller profile | Food From Friends'
];

if (isset($Routing->seller)) {

    // initialize Seller
    $Seller = new GrowerOperation([
        'DB'    => $DB,
        'slug'  => $Routing->seller
    ], [
        'exchange'  => true,
        'team'      => true
    ]);
    
    if (isset($Seller)) {

        // check if User is owner of Seller
        $is_owner = isset($User) && ((isset($Seller->Owner) && $Seller->Owner->id == $User->id) || isset($Seller->TeamMembers[$User->id]));
    

        // check if Seller is active
        if ($Seller->is_active || $is_owner) {
            // configure date joined on
            $joined_on = new DateTime($Seller->created_on, new DateTimeZone('UTC'));
            $joined_on->setTimezone(new DateTimeZone('America/New_York'));
        

            // calculate delivery distance
            if (isset($User, $User->BuyerAccount, $User->BuyerAccount->Address, $User->BuyerAccount->Address->latitude, $User->BuyerAccount->Address->longitude)) {
                $geocode    = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins={$User->BuyerAccount->Address->latitude},{$User->BuyerAccount->Address->longitude}&destinations={$Seller->latitude},{$Seller->longitude}&key=" . GOOGLE_MAPS_KEY);
                $output     = json_decode($geocode);
                $distance   = explode(' ', $output->rows[0]->elements[0]->distance->text);
                $distance_miles = round((($distance[1] == 'ft') ? $distance[0] / 5280 : $distance[0]), 4);
            }


            // check if wholesale relationship exists between User:BuyerAccount and Seller
            if (isset($User->BuyerAccount)) {
                $wholesale_relationship = $User->BuyerAccount->retrieve([
                    'where' => [
                        'buyer_account_id' => $User->BuyerAccount->id,
                        'seller_id' => $Seller->id,
                        'status'    => 2
                    ],
                    'table' => 'wholesale_relationships'
                ]);
            } else {
                $wholesale_relationship = false;
            }
            
            $Item = new Item([
                'DB' => $DB
            ]);
        

            // retrieve Seller rating
            $grower_stars = stars($Seller->average_rating);
            

            // retrieve & hash categories, subcategories, and varieites
            $raw_categories = $Item->retrieve([
                'table' => 'item_categories'
            ]);
            
            $hashed_categories = [];
            
            foreach($raw_categories as $raw_category) {
                if (!isset($hashed_categories[$raw_category['id']])) {
                    $hashed_categories[$raw_category['id']] = $raw_category['title'];
                }
            }
            
            $raw_subcategories = $Item->retrieve([
                'table' => 'item_subcategories'
            ]);
            
            $hashed_subcategories = [];
            
            foreach($raw_subcategories as $raw_subcategory) {
                if (!isset($hashed_subcategories[$raw_subcategory['id']])) {
                    $hashed_subcategories[$raw_subcategory['id']] = $raw_subcategory['title'];
                }
            }
            
            $raw_varieties = $Item->retrieve([
                'table' => 'item_varieties'
            ]);
            
            $hashed_varieties = [];
            
            foreach($raw_varieties as $raw_variety) {
                if (!isset($hashed_varieties[$raw_variety['id']])) {
                    $hashed_varieties[$raw_variety['id']] = $raw_variety['title'];
                }
            }


            // retrieve & hash items
            if ($wholesale_relationship && (!isset($_GET['retail']) || $_GET['retail'] != 'true')) {
                $raw_items = $Item->get_items($Seller->id, [
                    'is_wholesale' => 1
                ]);
                
                $wholesale_active = true;
            } else {
                $raw_items = $Item->get_items($Seller->id, [
                    'is_wholesale' => 0
                ]);

                $wholesale_active = false;
            }

            $categorized_items  = [];
            $hashed_items       = [];

            foreach($raw_items as $raw_item) {
                if (!isset($categorized_items[$raw_item['item_category_id']])) {
                    $categorized_items[$raw_item['item_category_id']] = [];
                }
                
                if (!isset($categorized_items[$raw_item['item_category_id']][$raw_item['item_subcategory_id']])) {
                    $categorized_items[$raw_item['item_category_id']][$raw_item['item_subcategory_id']] = [];
                }
                
                if (!isset($categorized_items[$raw_item['item_category_id']][$raw_item['item_subcategory_id']][$raw_item['item_variety_id']])) {
                    $categorized_items[$raw_item['item_category_id']][$raw_item['item_subcategory_id']][$raw_item['item_variety_id']] = [];
                }

                $ThisItem = new Item([
                    'DB' => $DB,
                    'id' => $raw_item['id']
                ]);

                $categorized_items[$raw_item['item_category_id']][$raw_item['item_subcategory_id']][$raw_item['item_variety_id']][$raw_item['id']] = $ThisItem;

                $in_cart = isset($User, $User->BuyerAccount->ActiveOrder, $User->BuyerAccount->ActiveOrder->Growers[$Seller->id], $User->BuyerAccount->ActiveOrder->Growers[$Seller->id]->Items[$ThisItem->id]);

                if ($in_cart) {
                    $OrderItem = $User->BuyerAccount->ActiveOrder->Growers[$Seller->id]->Items[$ThisItem->id];
                }

                $hashed_items[$raw_item['id']] = [
                    'price'     => _amount($ThisItem->price),
                    'name'      => $ThisItem->title,
                    'quantity'  => $ThisItem->quantity,
                    'rating'    => stars(!empty($ThisItem->rating) ? $ThisItem->rating : 0),
                    'link'      => $ThisItem->link, 
                    'filename'  => $ThisItem->Image->filename, 
                    'ext'       => $ThisItem->Image->ext,
                    'in_cart'   => $in_cart,
                    'cart_qty'  => (isset($OrderItem) ? $OrderItem->quantity : 0)
                ];
            }

            $package_types = $Item->retrieve([
                'table' => 'item_package_types'
            ]);

            $metrics = $Item->retrieve([
                'table' => 'item_metrics'
            ]);


            // initialize OrderGrower if it exists
            if (isset($User, $User->BuyerAccount, $User->BuyerAccount->ActiveOrder, $User->BuyerAccount->ActiveOrder->Growers[$Seller->id])) {
                $OrderGrower = $User->BuyerAccount->ActiveOrder->Growers[$Seller->id];
            }
        

            // retrieve reviews
            $ratings = $Seller->retrieve([
                'where' => [
                    'grower_operation_id' => $Seller->id
                ],
                'table' => 'grower_operation_ratings',
                'recent' => true
            ]);
        

            // set page title
            $settings['title'] = "{$Seller->name} | Food From Friends";
        }
    }
}

?>