<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

foreach ($_POST as $k => $v) if (isset($v) && !empty($v)) ${$k} = rtrim($v);


$Food_listing = new Food_listing([
    'DB' => $DB
]);

 $add_listing = $Food_listing->add('food_listing', [

    'food_subcategory_id'   =>      (isset($food_subcategory_id) ? $food_subcategory_id : ''),
    'price'                 =>      (isset($price) ? $price : ''),
    'description'           =>      (isset($description) ? $description : ''),
    'is_available'          =>      (isset($is_available) ?  $is_available : ''),
    'stock'                 =>      (isset($stock) ?  $stock : '' )

 ]);

 if (!$add_listing) {
        quit('<i class="fa fa-exclamation-triangle"></i> Could not add listing!');
    }
    
echo json_encode($json);

?>  
