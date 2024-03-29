<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'item-type' => 'required|alpha_space'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors()[0]);
}

$Gump->filter_rules([
	'item-type' => 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

$Item = new Item([
    'DB' => $DB
]);

$item_added = $Item->add([
    'suggested_by_user' => $User->id,
    'suggested_by_seller' => $User->GrowerOperation->id,
    'item_type'         => $item_type,
    'comments'          => $comments,
], 'item_suggestions');

if (!$item_added) quit('Could not record suggestion');

echo json_encode($json);

?>  
