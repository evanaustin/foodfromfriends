<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'name'              => 'required|alpha_space',
    'type'              => 'required|integer',
    'address-line-1'    => 'required|alpha_numeric_space|max_len,35',
    'address-line-2'    => 'alpha_numeric_space|max_len,25',
    'city'              => 'required|alpha_space|max_len,35',
    'state'             => 'required|regex,/^[a-zA-Z]{2}$/',
    'zipcode'           => 'required|regex,/^[0-9]{5}$/'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
    'name'              => 'trim|sanitize_string',
	'type'              => 'trim|sanitize_numbers',
    'address-line-1'    => 'trim|sanitize_string',
	'address-line-2'    => 'trim|sanitize_string',
	'city'              => 'trim|sanitize_string',
	'state'             => 'trim|sanitize_string',
	'zipcode'           => 'trim|whole_number'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;


/*
* Create BuyerAccount
*/

try {
    $buyer_account_id = $User->BuyerAccount->create($User->id, [
        'type'              => $type,
        'name'              => $name,
        'bio'               => $bio,
        'address_line_1'    => $address_line_1,
        'address_line_2'    => $address_line_2,
        'city'              => $city,
        'state'             => $state,
        'zipcode'           => $zipcode
    ],[
        'is_default' => 0
    ]);
} catch (\Exception $e) {
    quit($e->getMessage());
}


/* 
 * Reinitialize User w/ Accounts
 */

$User = new User([
    'DB' => $DB,
    'id' => $USER['id'],
    'buyer_account' => true,
]);
    
$User->switch_buyer_account($buyer_account_id);


/*
 * Create BuyerAccount:Image
 */

$Image = new Image();

// validate image
if (isset($_POST['images'])) {
    // decode stringified JSON
    $_POST['images'] = html_entity_decode($_POST['images']);
    $images = json_decode($_POST['images'], true);

    // make sure the user didn't tamper with the data
    if (!ctype_digit((string)$images['frame']['w']) || !ctype_digit((string)$images['frame']['h'])) {
        quit('We were unable to crop your images');
    }

    // only one image so key is always 0
    $key = 0;

    // get image
    $image = $images['images'][$key];
    
    // validate image
    $valid = validate_image($image);

    if (!$valid) {
        quit($valid);
    }

    // compile file data
    $file = [
        'name' => $_FILES['img' . $image['key']]['name'],
        'type' => $_FILES['img' . $image['key']]['type'],
        'tmp'  => $_FILES['img' . $image['key']]['tmp_name'],
        'size' => $_FILES['img' . $image['key']]['size']
    ];
    
    // set random image key
    $rand = substr(md5(microtime()), rand(0,26), 5);

    // set filename
    $filename = "ba.{$rand}.{$User->BuyerAccount->id}";
    
    // determine file type
    $ext = (explode('/', $_FILES['profile-image']['type'])[1] == 'jpeg') ? 'jpg' : 'png';
    
    // set temporary storage paths
    $tmp1 = SERVER_ROOT . 'media/tmp/start/';
    $tmp2 = SERVER_ROOT . 'media/tmp/final/';
    
    // set temporary image names
    $tmp1_image = $tmp1 . $filename . '.' . $ext;
    $tmp2_image = $tmp2 . $filename . '.' . $ext;
    
    // temporarily store the file
    move_uploaded_file($file['tmp'], $tmp1_image);
    
    // convert to JPG if PNG
    if ($ext == 'png') {
        // Since this is the first time we open the file, ensure that it isn't
        // corrupted at this point with a temporary custom error handler.
        // Don't forget to call restore_error_handler() after checking.
        set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });
    
        try {
            $Image->load($tmp1_image);
            $jpg_filesize = $Image->convert_png_to_jpg($tmp1 . $filename . '.jpg');
        } catch(ErrorException $e) {
            // quit('We were unable to process your image');
            quit($e->getMessage());
        }
        
        restore_error_handler();
    
        // proceed with the smaller file
        if ($jpg_filesize < $file['size']) {
            // delete the PNG
            if (file_exists($tmp1_image)) {
                unlink($tmp1_image);
            }
    
            // set up the JPG as the image to proceed with
            $extension  = 'jpg';
            $tmp1_image = $tmp1 . $filename . '.' . $extension;
            $tmp2_image = $tmp2 . $filename . '.' . $extension;
        } else {
            // delete the JPG
            if (file_exists($tmp1 . $filename . '.jpg')) {
                unlink($tmp1 . $filename . '.jpg');
            }
        }
    }
    
    list($true_width, $true_height, $type, $attr) = getimagesize($tmp1_image);
    
    if ($image['crop']['x'] == 0 
    && $image['crop']['y'] == 0
    && $image['crop']['w'] == $true_width
    && $image['crop']['h'] == $true_height
    ) {
        copy($tmp1_image, $tmp2_image);
    } else {
        $Image->load($tmp1_image);
        $Image->crop($image['crop']['x'], $image['crop']['y'], $image['crop']['w'], $image['crop']['h']);
        $Image->save($tmp2_image);
    }
    
    $final = [
        'w' => 933,
        'h' => 800,
        'file' => $tmp2 . $filename . '.cropped.' . $ext
    ];
    
    if ($true_width == $final['w'] && $true_height == $final['h']) {
        copy($tmp2_image, $final['file']);
    } else {
        $Image->load($tmp2_image);
        $Image->resize($final['w'], $final['h'], 'exact');
        $Image->save($final['file']);
    }
    
    // add image record
    $image_added = $User->BuyerAccount->add([
        'path'      => 'buyer-account-images',
        'filename'  => $filename,
        'ext'       => $ext
    ], 'images');
    
    if (!$image_added) {
        quit('Could not add image');
    }

    $record_added = $User->BuyerAccount->add([
        'buyer_account_id'  => $User->BuyerAccount->id,
        'image_id'          => $image_added['last_insert_id'],
    ], 'buyer_account_images');

    if (!$record_added) {
        quit('Could not add image record');
    }
    
    $image_uploaded = $S3->save_object(ENV . "/buyer-account-images/{$filename}.{$ext}", fopen($final['file'], 'r'));
    
    if (!$image_uploaded) quit('Could not add new image');

    // unlink tmp imgs
    if (file_exists($tmp1_image)) {
        unlink($tmp1_image);
    }
    
    if (file_exists($tmp2_image)) {
        unlink($tmp2_image);
    }
    
    if (file_exists($tmp2 . $filename . '.cropped.' . $ext)) {
        unlink($tmp2 . $filename . '.cropped.' . $ext);
    }
}


/*
 * Return JSON
 */

$json['slug'] = $slug;

echo json_encode($json);

?>