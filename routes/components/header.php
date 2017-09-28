<nav id="header" class="navbar navbar-toggleable-md navbar-light">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
  
    <a class="navbar-brand" href="<?php echo PUBLIC_ROOT; ?>">
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
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#sign-up-modal">Sign up</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#log-in-modal">Log in</a>
                </li>
            <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo PUBLIC_ROOT . 'map?city=harrisonburg'; ?>">Map</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo PUBLIC_ROOT . 'grower/food-listings/overview'; ?>">Your listings</a>
                </li>

                <!-- <li class="nav-item">
                    <a class="nav-link" href="#">Messages</a>
                </li> -->

                <div class="hidden-lg-up">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo PUBLIC_ROOT . 'account/edit-profile/basic-information'; ?>">Account settings</a>
                    </li>

                    <li class="nav-item">
                        <a id="log-out" class="nav-link" href="#">Log out</a>
                    </li>
                </div>

                <div class="hidden-md-down">
                    <li class="nav-item dropdown">
                        <div class="nav-link dropdown-toggle profile" style="background-image: url('<?php echo (!empty($User->filename) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $User->filename . '.' . $User->ext . '?' . time() : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg'); ?>');" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></div>
                    
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="<?php echo PUBLIC_ROOT . 'account/edit-profile/basic-information'; ?>">Edit profile</a>
                            <!-- <a class="dropdown-item" href="#">Account settings</a> -->
                            <a id="log-out" class="dropdown-item" href="#">Log out</a>
                        </div>
                    </li>
                </div>
            <?php } ?>
        </ul>
    </div>
</nav>