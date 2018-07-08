<?php

if ($Routing->template == 'dashboard') {
    if (!$LOGGED_IN) {
        header('Location:' . PUBLIC_ROOT);
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
        <meta name="google-site-verification" content="UmsixiFqd2YXf2qI8LFn_5Q4R-iDnuwQiPwdlDxTCvI"/>
        
        <?php if (isset($settings['meta-description'])): ?>
        
            <meta name="description" content="<?= $settings['meta-description'] ?>"/>
        
        <?php endif; ?>
        
        <title><?= (!empty($settings['title']) ? $settings['title'] : 'Food From Friends'); ?></title>
        <link rel="shortcut icon" href="<?= PUBLIC_ROOT; ?>media/logos/favicon-32.png" type="image/x-icon">

        <?php if (ENV == 'prod'): ?>

            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=UA-114682144-1"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', 'UA-114682144-1');
            </script>

        <?php endif; ?>

        <?php layer('css', [
            'css/thirdparty/bootstrap/bootstrap-reboot',
            'css/thirdparty/bootstrap/bootstrap-grid',
            'css/thirdparty/bootstrap/bootstrap',
            'css/thirdparty/bootstrap-form-helper/bootstrap-formhelpers',
            'css/thirdparty/animate/animate',
            'css/thirdparty/fontawesome-4.7/font-awesome',
            'css/thirdparty/slidebars/slidebars',
            'node_modules/tether/dist/css/tether.min',
            'node_modules/toastr/build/toastr',
            'node_modules/bootstrap-timepicker/css/bootstrap-timepicker.min',
            'js/thirdparty/jquery.ui.sortable/jquery.ui.sortable.min',
            (($Routing->template == 'dashboard') ? 'css/thirdparty/cropbox/cropbox' : ''),
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

    <body class="<?= "{$Routing->template} {$Routing->path} {$Routing->fullpage}" ?>">
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

        $extensions = [
            'nav'   => 'routes/components/nav',
            'modal' => 'routes/modals/' . $Routing->path
        ];

        if ($Routing->template == 'front') {
            $extensions['cart']         = 'routes/components/front/cart';

            if (!$LOGGED_IN) {
                $extensions['sign-up']              = 'routes/modals/sign-up';
                $extensions['log-in']               = 'routes/modals/log-in';
                $extensions['reset-password-link']  = 'routes/modals/reset-password-link';
            } else {
                $extensions['checkout'] = 'routes/modals/checkout';
            }
        }
        
        foreach ($extensions as $extension) {
            $file = SERVER_ROOT . $extension . '.php';
            if (file_exists($file)) include $file;
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
            'node_modules/moment/min/moment.min',
            'node_modules/bootstrap-timepicker/js/bootstrap-timepicker.min',
            'node_modules/popper.js/dist/umd/popper.min',
            'js/thirdparty/jquery.ui.sortable/jquery.ui.sortable.min',
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

        $useragent = $_SERVER['HTTP_USER_AGENT'];

        // Mobile-only JS
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent,0,4))) {
            layer('js', [
                'js/thirdparty/hammer/hammer.min'
            ]);
        }
            
        layer('js', $Template->scripts);
        
        ?>

        <script>
            var ENV = <?= json_encode(ENV); ?>;
            var PUBLIC_ROOT = <?= json_encode(PUBLIC_ROOT); ?>
            
            <?php if ($LOGGED_IN): ?>
                
                var user = { 
                    'id'    : <?= json_encode($User->id); ?>,
                    'slug'  : <?= json_encode($User->slug); ?>
                }
                
            <?php endif; ?>

        </script>
    </body>
</html>