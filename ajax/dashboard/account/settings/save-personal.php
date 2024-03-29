<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

// sanitize_numbers won't actually strip the special characters from the phone number... so we have to do it manually
$_POST['phone'] = preg_replace('/[^0-9]/', '', str_replace(' ', '-', $_POST['phone']));

$Gump->validation_rules([
    'first-name'    => 'required|alpha',
	'last-name'     => 'required|alpha',
    'email'         => 'required|valid_email',
	'phone'         => 'required|numeric',
	'month'         => 'required|alpha',
	'day'           => 'required|integer',
	'year'          => 'required|integer',
    'gender'        => 'alpha'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'first-name'    => 'trim|sanitize_string',
    'last-name'     => 'trim|sanitize_string',
	'email'         => 'trim|sanitize_email',
	'phone'         => 'trim|sanitize_numbers',
	'month'         => 'trim|sanitize_string',
	'day'           => 'trim|whole_number',
	'year'          => 'trim|whole_number',
    'gender'        => 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

if ($User->email != $email && $User->exists('email', $email)) {
    quit('An existing account is already using this email');
}

$date   = DateTime::createFromFormat('d-F-Y H:i:s', "{$day}-{$month}-{$year} 12:00:00");
$dob    = $date->format('Y-m-d H:i:s');

$profile_updated = $User->update([
    'email'         => $email,
    'first_name'    => ucfirst($first_name),
    'last_name'     => ucfirst($last_name),
    'phone'         => $phone,
    'dob'           => $dob,
    'gender'        => (isset($gender)) ? $gender : '',
]);

if (!$profile_updated) {
    quit('We couldn\'t update your information');
}


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
    $filename = 'u.' . $User->id;
    
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
    
    if (!empty($User->filename)) {
        $record_edited = $User->update([
            'ext' => $ext
        ], 'id', $User->id, 'user_profile_images');

        if (!$record_edited) quit('Could not edit image record');
        
        $img_removed = $S3->delete_objects([
            ENV . '/profile-photos/' . $User->filename . $User->ext
        ], $file);
    } else {
        $record_added = $User->add([
            'user_id' => $User->id,
            'filename' => $filename,
            'ext' => $ext
        ], 'user_profile_images');

        if (!$record_added) {
            quit('Could not add image record');
        }
    }
    
    $img_added = $S3->save_object(ENV . '/profile-photos/' . $filename . '.' . $ext, fopen($final['file'], 'r'));
    
    if (!$img_added) quit('Could not add new image');

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

echo json_encode($json);

?>