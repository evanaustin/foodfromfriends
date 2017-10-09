<?php

$Template = new Template($Routing);

if ($Routing->template == 'dashboard' && !$LOGGED_IN) {
    header('Location: ' . PUBLIC_ROOT);
    die();
}

foreach ([
    $Template->initializer,
    $Template->architecture
] as $path) {
    $file = SERVER_ROOT . $path . '.php';
    if (file_exists($file)) include $file;
}

?>

<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php if (isset($settings['title'])) echo $settings['title']; ?></title>
        <link rel="shortcut icon" href="<?php echo PUBLIC_ROOT; ?>media/logos/favicon-32.png" type="image/x-icon">
        <?php layer('css', [
            // some of these don't need to be loaded universally
            'css/thirdparty/bootstrap/bootstrap-reboot',
            'css/thirdparty/bootstrap/bootstrap-grid',
            'css/thirdparty/bootstrap/bootstrap',
            'css/thirdparty/bootstrap-form-helper/bootstrap-formhelpers',
            'css/thirdparty/animate/animate',
            'css/thirdparty/cropbox/cropbox',
            'css/thirdparty/fontawesome-4.7/font-awesome',
            'node_modules/tether/dist/css/tether.min',
            'node_modules/toastr/build/toastr',
            'node_modules/mapbox-gl/dist/mapbox-gl',
            (($Routing->template != 'splash' && $Routing->template != 'log-in' && $Routing->template != 'early-access-invitation' && $Routing->template != 'team-member-invitation') ? 'css/app' : ''),
            $Template->styles
        ]); ?>
    </head>

    <body class="<?php echo $Routing->template . ' ' . $Routing->fullpage; ?>">
        <?php
        
        foreach ($body as $part) {
            $file = SERVER_ROOT . $part . '.php';
            if (file_exists($file)) include $file;
        }
        
        layer('js', [
            'node_modules/jquery/dist/jquery',
            'node_modules/jquery.ui.widget/jquery.ui.widget',
            'node_modules/bootbox/bootbox.min',
            'node_modules/imagesloaded/imagesloaded.pkgd.min',
            'node_modules/tether/dist/js/tether.min',
            'node_modules/parsleyjs/dist/parsley.min',
            'node_modules/toastr/build/toastr.min',
            'node_modules/mapbox-gl/dist/mapbox-gl',
            'js/thirdparty/bootstrap/bootstrap.min',
            'js/thirdparty/bootstrap-form-helper/bootstrap-formhelpers.min',
            'js/thirdparty/cropbox/cropbox',
            'js/app',
            'js/ajax',
            'js/account',
            'js/bootstrap',
            'js/form',
            'js/util',
            'js/domready'
        ]);
            
        layer('js', $Template->scripts);
        
        ?>

        <script>var PUBLIC_ROOT = <?php echo json_encode(PUBLIC_ROOT); ?></script>
    </body>
</html>
