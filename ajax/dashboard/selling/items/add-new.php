<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'subcategory'   => 'required|integer',
    'variety'       => 'integer',
    'name'          => 'regex,/^[a-zA-z\s:]+$/',
	'price'         => 'required|regex,/^[0-9]+.[0-9]{2}$/|min_numeric, 0|max_numeric, 1000000',
	'quantity'      => 'required|regex,/^[0-9]+$/|min_numeric, 0|max_numeric, 10000',
    'package-type'  => 'required|integer',
	'measurement'   => 'regex,/^([0-9]*[.x\s])*[0-9]+$/|max_len, 10',
	'metric'        => 'integer',
	'similar-photo' => 'integer'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors()[0]);
}

$Gump->filter_rules([
	'subcategory'   => 'trim|whole_number',
    'variety'       => 'trim|whole_number',
	'name'          => 'trim|sanitize_string',
	'price'         => 'trim|sanitize_floats',
	'quantity'      => 'trim|whole_number',
	'package-type'  => 'trim|whole_number',
	'measurement'   => 'trim|sanitize_string',
	'metric'        => 'trim|whole_number',
	'description'   => 'trim|sanitize_string',
	'similar-photo' => 'trim|whole_number'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

// check that if measurement is set then metric is too
if (!empty($measurement) && empty($metric)) {
    quit('Select a metric of measurement');
}

$Item = new Item([
    'DB' => $DB,
    'S3' => $S3
]);

// ! TODO: make sure category + subcategory + variety are valid

// check that category + subcategory + variety + package combination is unique
// ! NOTE: disabled for now
/* $item_exists = $Item->retrieve([
    'where' => [
        'grower_operation_id'   => $User->GrowerOperation->id,
        'item_subcategory_id'   => $subcategory,
        'item_variety_id'       => (isset($variety) ? $variety : 0),
        'package_type_id'       => $package_type
    ],
    'limit' => 1
]);

if (!empty($item_exists)) {
    quit('You already have an item like this');
} */

$item_added = $Item->add([
    'grower_operation_id'   => $User->GrowerOperation->id,
    'item_subcategory_id'   => $subcategory,
    'item_variety_id'       => (isset($variety) ? $variety : 0),
    'name'                  => (!empty($name) ? $name : NULL),
    'is_wholesale'          => (isset($is_wholesale) && $is_wholesale == 'on') ? 1 : 0,
    'price'                 => $price * 100,
    'quantity'              => $quantity,
    'package_type_id'       => $package_type,
    'measurement'           => (isset($measurement)) ? $measurement : 0,
    'metric_id'             => (isset($measurement, $metric)) ? $metric : 0,
    'description'           => $description
]);

if (!$item_added) quit('Could not add item');

$id = $item_added['last_insert_id'];

$Item = new Item([
    'DB' => $DB,
    'id' => $id
]);

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
    
    // set filename
    $rand = substr(md5(microtime()), rand(0,26), 5);
    $filename = "i.{$rand}.{$id}";

    // determine file type
    $ext = (explode('/', $file['type'])[1] == 'jpeg') ? 'jpg' : 'png';
    
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
            quit('We were unable to process your image');
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
    
    $img_added = $S3->save_object(ENV . "/item-images/{$filename}.{$ext}", fopen($final['file'], 'r'));
    
    $handle = curl_init('https://s3.amazonaws.com/foodfromfriends/' . ENV . "/item-images/i.{$filename}.{$ext}");
    curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

    if ($httpCode == 403 || $httpCode == 404) {
        quit('Item added, but image could not be uploaded');
        die();
    }

    $image = $Item->add([
        'path'      => 'item-images',
        'filename'  => $filename,
        'ext'       => $ext
    ], 'images');

    if (!$image) quit('Could not add new image');

    $Item->add([
        'item_id'   => $Item->id,
        'image_id'  => $image['last_insert_id'],
    ], 'item_images');

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

    $json['new_image'] = [
        'id'        => $image['last_insert_id'],
        'filename'  => $filename,
        'ext'       => $ext,
    ];
} else if (isset($similar_photo)) {
    $Item->add([
        'item_id'   => $Item->id,
        'image_id'  => $similar_photo
    ], 'item_images');
}

$json['link'] = "{$User->GrowerOperation->link}/{$Item->link}";

echo json_encode($json);

?>  
