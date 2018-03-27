<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'suggested-by-user'     => 'required|integer',
	'suggested-by-seller'  => 'required|integer',
	'item-type'             => 'required|alpha_space'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors()[0]);
}

$Gump->filter_rules([
	'sugggested-by-user'    => 'trim|whole_number',
	'sugggested-by-seller'  => 'trim|whole_number',
	'item-type'             => 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

$FoodListing = new FoodListing([
    'DB' => $DB
]);

$listing_added = $FoodListing->add([
    'suggested_by_user'     => $suggested_by_user,
    'suggested_by_seller'   => $suggested_by_seller,
    'item_type'             => $item_type,
    'comments'              => $comments,
], 'item_suggestions');

if (!$listing_added) quit('Could not record suggestion');

echo json_encode($json);

?>  
