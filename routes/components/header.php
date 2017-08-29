<nav id="header" class="navbar navbar-toggleable-md navbar-light">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
  
    <a class="navbar-brand" href="#">
        <?php svg('logos/thin'); ?>
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
                    <a class="nav-link" href="<?php echo PUBLIC_ROOT . 'dashboard/food-listings/overview'; ?>">Sell</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Shop</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Messages</a>
                </li>

                <li class="nav-item dropdown">
                    <div class="nav-link dropdown-toggle profile" style="background-image: url('<?php echo (!empty($User->filename) ? 'https://s3.amazonaws.com/foodfromfriends/user/profile-photos/' . $User->filename . '.' . $User->ext . '?' . time() : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg'); ?>');" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></div>
                
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="<?php echo PUBLIC_ROOT . 'profile/edit'; ?>">Edit profile</a>
                        <!-- <a class="dropdown-item" href="#">Account settings</a> -->
                        <a id="log-out" class="dropdown-item" href="#">Log out</a>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>