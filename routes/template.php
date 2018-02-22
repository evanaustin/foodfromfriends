<?php

use \Firebase\JWT\JWT;

if ($Routing->template == 'dashboard') {
    if (isset($_GET['token'])) {
        $JWT = JWT::decode($_GET['token'], JWT_KEY, array('HS256'));

        if (isset($JWT->user_id) && (!isset($JWT->time) || (time() - $JWT->iss_on <= $JWT->time))) {
            if ($LOGGED_IN) {
                $User->soft_log_out();
            }
            
            $User = new User([
                'DB' => $DB,
                'id' => $JWT->user_id
            ]);

            $User->log_in($JWT->user_id);

            $LOGGED_IN = true;

            if (isset($JWT->grower_operation_id)) {
                $User->switch_operation($JWT->grower_operation_id);
            }
        }
    }

    if (!$LOGGED_IN) {
        header('Location: ' . PUBLIC_ROOT);
        die();
    } else if (!empty($User->GrowerOperation)) {
        $User->GrowerOperation->determine_outstanding_orders();
    }
}

$Template = new Template($Routing, $LOGGED_IN);

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
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="google-site-verification" content="UmsixiFqd2YXf2qI8LFn_5Q4R-iDnuwQiPwdlDxTCvI" />
        <title><?php if (isset($settings['title'])) echo $settings['title']; ?></title>
        <link rel="shortcut icon" href="<?php echo PUBLIC_ROOT; ?>media/logos/favicon-32.png" type="image/x-icon">

        <?php if (ENV == 'prod') { ?>
            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=UA-114682144-1"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', 'UA-114682144-1');
            </script>
        <?php } ?>

        <?php layer('css', [
            'css/thirdparty/bootstrap/bootstrap-reboot',
            'css/thirdparty/bootstrap/bootstrap-grid',
            'css/thirdparty/bootstrap/bootstrap',
            'css/thirdparty/bootstrap-form-helper/bootstrap-formhelpers',
            'css/thirdparty/animate/animate',
            'css/thirdparty/fontawesome-4.7/font-awesome',
            'node_modules/tether/dist/css/tether.min',
            'node_modules/toastr/build/toastr',
            (($Routing->template == 'dashboard') ? 'css/thirdparty/cropbox/cropbox' : ''),
            (($Routing->template == 'front') ? 'css/thirdparty/slidebars/slidebars' : ''),
            (($Routing->template == 'front') ? 'node_modules/mapbox-gl/dist/mapbox-gl' : ''),
            (!in_array($Routing->template, $Routing->unique) || $Routing->template == 'map' ? 'css/app' : ''),
            $Template->styles
        ]); ?>
         <!--[if lt IE 9]> 
            <script> document.createElement("fable"); </script>
            <script> document.createElement("cell"); </script>
            <script> document.createElement("ledger"); </script>
        <![endif]-->
    </head>

    <body class="<?php echo $Routing->template . ' ' . $Routing->path . ' ' . $Routing->fullpage; ?>">
        <?php
        
        if ($Routing->template == 'front' || $Routing->template == 'dashboard') {
            include SERVER_ROOT . 'routes/components/header.php';
            
            // begin canvas
            echo '<div canvas="container">';
        }
        
        foreach ($body as $part) {
            $file = SERVER_ROOT . $part . '.php';
            if (file_exists($file)) include $file;
        }

        if ($Routing->template == 'front' || $Routing->template == 'dashboard') {
            // end canvas
            echo '</div>';
        }

        if ($Routing->template == 'front') {
            $extensions = [
                'cart'  => 'routes/components/front/cart',
                'modal' =>'routes/modals/' . $Routing->path
            ];

            if (!$LOGGED_IN) {
                $extensions['sign-up']  = 'routes/modals/sign-up';
                $extensions['log-in']   = 'routes/modals/log-in';
            } else {
                $extensions['checkout'] = 'routes/modals/checkout';
            }

            foreach ($extensions as $extension) {
                $file = SERVER_ROOT . $extension . '.php';
                if (file_exists($file)) include $file;
            }
        }
        
        ?>

        <script src="https://js.stripe.com/v3/"></script>

        <?php

        layer('js', [
            'node_modules/jquery/dist/jquery',
            'node_modules/jquery.ui.widget/jquery.ui.widget',
            'node_modules/bootbox/bootbox.min',
            'node_modules/imagesloaded/imagesloaded.pkgd.min',  // not universal
            'node_modules/tether/dist/js/tether.min',
            'node_modules/parsleyjs/dist/parsley.min',
            'node_modules/toastr/build/toastr.min',
            'node_modules/mapbox-gl/dist/mapbox-gl',            // not universal
            'node_modules/autosize/dist/autosize.min',          // not universal
            'node_modules/jstimezonedetect/dist/jstz.min',
            'node_modules/popper.js/dist/umd/popper.min',
            'js/thirdparty/bootstrap/bootstrap.min',
            'js/thirdparty/bootstrap-form-helper/bootstrap-formhelpers.min',
            'js/thirdparty/cropbox/cropbox',                    // not universal
            'js/thirdparty/slidebars/dist/slidebars.min',
            'js/app',
            'js/ajax',
            'js/account',
            'js/bootstrap',
            'js/form',
            'js/util',
            'js/image',
            'js/domready'
        ]);
            
        layer('js', $Template->scripts);
        
        ?>

        <script>
            var ENV         = <?php echo json_encode(ENV); ?>;
            var PUBLIC_ROOT = <?php echo json_encode(PUBLIC_ROOT); ?>;
        </script>
    </body>
</html>