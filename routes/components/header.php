<nav id="header" class="navbar navbar-expand-md navbar-light">
    <a class="navbar-brand" href="<?= PUBLIC_ROOT; ?>" title="Food From Friends">
        <?php svg('logos/thin'); ?>
    </a>

    <button id="mobile-nav" class="btn btn-primary d-md-none">
        <i class="fa fa-bars"></i>
    </button>

    <div id="navbarSupportedContent" class="collapse navbar-collapse">
        <ul class="navbar-nav ml-auto">

            <?php if (!$LOGGED_IN): ?>

                <li class="nav-item">
                    <a class="nav-link" href="<?= PUBLIC_ROOT . 'map'; ?>">
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

            <?php else: ?>

                <!-- BEGIN TABLETS + DESKTOPS -->

                <li class="nav-item d-none d-md-block">
                    <a href="<?= PUBLIC_ROOT ?>map" class="nav-link <?php if ($Routing->section == 'map') echo 'active' ?>" data-toggle="tooltip" data-placement="bottom" title="Map">
                        <i class="fa fa-map"></i>
                    </a>
                </li>

                <li class="nav-item d-none d-md-block">
                    <a href="<?= PUBLIC_ROOT ?>dashboard/buying/orders/overview" class="nav-link <?php if ($Routing->template == 'dashboard') echo 'active' ?>" data-toggle="tooltip" data-placement="bottom" title="Dashboard">
                        <i class="fa fa-dashboard"></i>
                    </a>
                </li>

                <?php if ($Routing->template == 'front' || $Routing->template == 'map'): ?>

                    <li class="nav-item d-none d-md-block">
                        <a class="nav-link cart-toggle" data-toggle="tooltip" data-placement="bottom" title="Basket">
                            <i class="fa fa-shopping-basket"></i>
                        </a>
                    </li>

                <?php endif; ?>

                <li class="nav-item profile dropdown d-none d-md-block">

                    <?php if (!empty($User->BuyerAccount->Image->filename)) {
                        $path = 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/buyer-account-images/{$User->BuyerAccount->Image->filename}.{$User->BuyerAccount->Image->ext}";
                    } else if (!empty($User->GrowerOperation->filename)) {
                        $path = 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/grower-operation-images/{$User->GrowerOperation->filename}.{$User->GrowerOperation->ext}";
                    } else {
                        $path = PUBLIC_ROOT . 'media/placeholders/user-thumbnail.jpg';
                    } ?>

                    <div class="nav-link dropdown-toggle" style="background-image: url('<?= $path ?>');" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></div>
                
                    <div class="dropdown-menu dropdown-menu-right">
                        
                        <?php if (isset($User->BuyerAccount)): ?>

                            <a class="dropdown-item" href="<?= PUBLIC_ROOT ?>dashboard/buying/settings/profile">
                                Buying profile
                            </a>
                            
                        <?php endif; ?>

                        <?php if (isset($User->GrowerOperation)): ?>

                            <a class="dropdown-item" href="<?= PUBLIC_ROOT ?>dashboard/selling/settings/profile">
                                Selling profile
                            </a>
                            
                        <?php endif; ?>

                        <a class="dropdown-item" href="<?= PUBLIC_ROOT ?>dashboard/account/settings/personal">
                            Account
                        </a>
                        
                        <a id="log-out" class="dropdown-item" href="#">Log out</a>
                    </div>
                </li>

                <!-- END TABLETS + DESKTOPS -->

            <?php endif; ?>
        </ul>
    </div>
</nav>