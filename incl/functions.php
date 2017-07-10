<?php

function layer($language, $files) {
    foreach ($files as $file) {
        $file .= '.' . $language;
        
        if (file_exists(SERVER_ROOT . $file)) {
            $file_path = PUBLIC_ROOT . $file;
            
            switch ($language) {
                case 'css':
                    echo '<link rel="stylesheet" href="' . $file_path . '"/>';
                    break;
                case 'js':
                    echo '<script src="' . $file_path . '"></script>';
                    break;
            }
        }
    }
}

function img($path, $ext, $class = "") {
    echo '<img src="media/' . $path . '.' . $ext . '"' . (!empty($class) ? 'class="' . $class . '"' : '') .'/>';
}

function quit($message) {
    $json['error'] = $message;
    $json['success'] = false;
    return exit(json_encode($json));
}

function console_log($data) {
    echo "<script>console.log('PHP log: " . (is_array($data) ? json_encode($data) : $data) . "');</script>";
}

?>