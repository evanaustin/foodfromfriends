<nav id="header" class="navbar navbar-expand-md navbar-light">
    <a class="navbar-brand" href="<?php echo PUBLIC_ROOT; ?>">
        <?php svg('logos/thin'); ?>
    </a>

    <button id="mobile-nav" class="btn btn-primary d-md-none">
        <i class="fa fa-bars"></i>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">

            <?php if (!$LOGGED_IN) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo PUBLIC_ROOT; ?>">
                        <span>
                            Map
                        </span>
                        
                        <!-- <i class="fa fa-map"></i> -->
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#sign-up-modal">
                        <span>
                            Sign up
                        </span>
                        
                        <!-- <i class="fa fa-rocket"></i> -->
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#log-in-modal">
                        <span>
                            Log in
                        </span>
                        
                        <!-- <i class="fa fa-id-badge"></i> -->
                    </a>
                </li>
            <?php } else { ?>
                <!-- TABLETS + DESKTOPS -->
                <li class="nav-item d-none d-md-block">
                    <a 
                        class="nav-link <?php if ($Routing->section == 'map') { echo 'active'; } ?>" 
                        href="<?php echo PUBLIC_ROOT; ?>"
                        data-toggle="tooltip" data-placement="bottom" title="Map"
                    >
                        <i class="fa fa-map"></i>
                    </a>
                </li>

                <li class="nav-item d-none d-md-block">
                    <a 
                        class="nav-link <?php if ($Routing->template == 'dashboard') { echo 'active'; } ?>" 
                        href="<?php echo PUBLIC_ROOT . ((isset($User->GrowerOperation)) ? 'dashboard/grower' : 'dashboard/account/edit-profile/basic-information'); ?>"
                        data-toggle="tooltip" data-placement="bottom" title="Dashboard"
                    >
                        <i class="fa fa-dashboard"></i>
                    </a>
                </li>

                <?php
                
                if ($Routing->template == 'front' || $Routing->template == 'map') {
                    
                    ?>

                    <li class="nav-item d-none d-md-block">
                        <a 
                            class="nav-link cart-toggle"
                            data-toggle="tooltip" data-placement="bottom" title="Basket"
                        >
                            <i class="fa fa-shopping-basket"></i>
                        </a>
                    </li>

                    <?php

                }

                ?>

                <li class="nav-item profile dropdown d-none d-md-block">
                    <div 
                        class="nav-link dropdown-toggle" 
                        style="background-image: url('<?php echo (!empty($User->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $User->filename . '.' . $User->ext : PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg'); ?>');" 
                        data-toggle="dropdown" 
                        aria-haspopup="true" 
                        aria-expanded="false"
                    ></div>
                
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="<?php echo PUBLIC_ROOT . 'dashboard/messages/inbox/buying'; ?>">
                            Messages

                            <?php

                            $Message = new Message([
                                'DB' => $DB
                            ]);

                            $unread = $Message->unread_aggregate($User);

                            if ($unread) echo '<i class="fa fa-circle info jackInTheBox animated"></i>';

                            ?>
                        </a>

                        <a class="dropdown-item" href="<?php echo PUBLIC_ROOT . 'dashboard/account/buying/orders'; ?>">
                            Order history
                        </a>

                        <a class="dropdown-item" href="<?php echo PUBLIC_ROOT . "user/{$User->slug}"; ?>">
                            User profile
                        </a>

                        <?php

                        if (isset($User->GrowerOperation)) {
                            echo "<a class=\"dropdown-item\" href=\"" . PUBLIC_ROOT . $User->GrowerOperation->link . "\">Seller profile</a>";
                            echo "<a class=\"dropdown-item\" href=\"" . PUBLIC_ROOT . "dashboard/grower/items/overview\">Your items</a>";
                        }

                        ?>

                        <a class="dropdown-item" href="<?php echo PUBLIC_ROOT . 'dashboard/account/edit-profile/basic-information'; ?>">
                            Edit profile
                        </a>
                        
                        <a id="log-out" class="dropdown-item" href="#">Log out</a>
                    </div>
                </li>
                <!-- END TABLETS + DESKTOPS -->
            <?php } ?>
        </ul>
    </div>
</nav>