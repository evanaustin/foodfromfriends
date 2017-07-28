<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

foreach ($_POST as $k => $v) if (isset($v) && !empty($v) || $v == 0) ${str_replace('-', '_', $k)} = rtrim($v);

$FoodListing = new FoodListing([
    'DB' => $DB
]);

foreach ([
    'food_category',
    'food_subcategory',
    'price',
    'weight',
    'units',
    'quantity'
] as $required) {
    if (!isset(${$required})) quit('The ' . strtoupper(str_replace('_', ' ', $required)) . ' field is required');
}

// what if other value entered is an existing subcategory?

$added = $FoodListing->add([
    'user_id' => $User->id,
    'food_subcategory_id' => $food_subcategory,
    'other_subcategory' => (($food_subcategory == 0) ? $other_subcategory : '' ),
    'price' => $price,
    'weight' => $weight,
    'units' => $units,
    'quantity' => $quantity,
    'is_available' => $is_available,
    'description' => (isset($description) ? $description : ''),
    'image_path' => (isset($image_path) ? $image_path : '')
]);

 if (!$added) {
    quit('Could not add listing');
}
    
echo json_encode($json);

?>  
