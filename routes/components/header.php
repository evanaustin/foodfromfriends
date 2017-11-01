<nav id="header" class="navbar navbar-toggleable-md navbar-light">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
  
    <a class="navbar-brand" href="<?php echo ((isset($User->GrowerOperation) && $User->GrowerOperation->permission == 2) ? PUBLIC_ROOT . 'dashboard/grower' : PUBLIC_ROOT . 'map'); ?>">
        <div class="hidden-md-down">
            <?php svg('logos/thin'); ?>
        </div>

        <div class="hidden-lg-up">
            <?php svg('logos/full_simplified'); ?>
        </div>
    </a>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">

            <?php if (!$LOGGED_IN) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#sign-up-modal">
                        <span>
                            Sign up
                        </span>
                        
                        <i class="fa fa-rocket"></i>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#log-in-modal">
                        <span>
                            Log in
                        </span>
                        
                        <i class="fa fa-id-badge"></i>
                    </a>
                </li>
            <?php } else { ?>
                <li class="nav-item">
                    <a 
                        class="nav-link <?php if ($Routing->section == 'map') { echo 'active'; } ?>" 
                        href="<?php echo PUBLIC_ROOT . 'map?city=harrisonburg'; ?>"
                        data-toggle="tooltip" data-placement="bottom" title="Map"
                    >
                        <i class="fa fa-map"></i>
                    </a>
                </li>

                <li class="nav-item">
                    <a 
                        class="nav-link <?php if ($Routing->template == 'dashboard') { echo 'active'; } ?>" 
                        href="<?php echo PUBLIC_ROOT . ((isset($User->GrowerOperation) && $User->GrowerOperation->permission == 2) ? 'dashboard/grower' : 'dashboard/account/edit-profile/basic-information'); ?>"
                        data-toggle="tooltip" data-placement="bottom" title="Dashboard"
                    >
                        <i class="fa fa-dashboard"></i>
                    </a>
                </li>

                <?php
                
                if ($Routing->template == 'front' || $Routing->template == 'map') {
                    
                    ?>

                    <li class="nav-item">
                        <a 
                            id="cart-toggle" 
                            class="nav-link"
                            data-toggle="tooltip" data-placement="bottom" title="Basket"
                        >
                            <i class="fa fa-shopping-basket"></i>
                        </a>
                    </li>

                    <?php

                }

                ?>

                <div class="hidden-lg-up">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo PUBLIC_ROOT . 'dashboard/account/edit-profile/basic-information'; ?>">
                            Edit profile
                        </a>
                    </li>

                    <li class="nav-item">
                        <a id="log-out" class="nav-link" href="#">Log out</a>
                    </li>
                </div>

                <div class="hidden-md-down">
                    <li class="nav-item profile dropdown">
                        <div 
                            class="nav-link dropdown-toggle" 
                            style="background-image: url('<?php echo (!empty($User->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $User->filename . '.' . $User->ext . '?' . time() : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg'); ?>');" 
                            data-toggle="dropdown" 
                            aria-haspopup="true" 
                            aria-expanded="false"
                        ></div>
                    
                        <div class="dropdown-menu dropdown-menu-right animated bounceIn">
                            <a class="dropdown-item" href="<?php echo PUBLIC_ROOT . 'dashboard/account/edit-profile/basic-information'; ?>">
                                Edit profile
                            </a>
                            
                            <a id="log-out" class="dropdown-item" href="#">Log out</a>
                        </div>
                    </li>
                </div>
            <?php } ?>
        </ul>
    </div>
</nav>