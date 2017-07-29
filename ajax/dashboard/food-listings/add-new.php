<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'food-category'     => 'required|integer',
	'food-subcategory'  => 'required|integer',
    'other-subcategory' => 'alpha',
	'price'             => 'required|regex,/^[0-9]+.[0-9]{2}$/|min_numeric, 0|max_numeric, 1000000',
	'weight'            => 'required|regex,/^[0-9]+$/|min_numeric, 1|max_numeric, 10000',
	'units'             => 'required|alpha',
	'quantity'          => 'required|regex,/^[0-9]+$/|min_numeric, 0|max_numeric, 10000',
	'is-available'      => 'required|boolean',
	// 'listing-image'     => 'alpha_dash'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors()[0]);
}

$Gump->filter_rules([
	'food-category'     => 'trim|whole_number',
	'food-subcategory'  => 'trim|whole_number',
    'other-subcategory' => 'trim|sanitize_string',
	'price'             => 'trim|sanitize_floats',
	'weight'            => 'trim|whole_number',
	'units'             => 'trim|sanitize_string',
	'stock'             => 'trim|whole_number',
	'description'       => 'trim|sanitize_string',
	// 'listing-image'     => 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

$FoodListing = new FoodListing([
    'DB' => $DB
]);

if ($other_subcategory) {
    $food_category_title = $FoodListing->other_exists($other_subcategory);

    if ($food_category_title) {
        quit(ucfirst($other_subcategory) . ' already exists as a ' . ucfirst($food_category_title));
    }
}

// what if other value entered is an existing subcategory?

$added = $FoodListing->add([
    'user_id' => $User->id,
    'food_subcategory_id' => $food_subcategory,
    'other_subcategory' => strtolower($other_subcategory),
    'price' => $price * 100,
    'weight' => $weight,
    'units' => $units,
    'quantity' => $quantity,
    'is_available' => $is_available,
    'description' => $description,
    // 'image_path' => $listing_image
]);

 if (!$added) {
    quit('Could not add listing');
}
    
echo json_encode($json);

?>  
