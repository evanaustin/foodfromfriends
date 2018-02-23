<div id="nav" off-canvas="slidebar-left left push">
    <ul class="navbar-nav ml-auto">
        <?php if ($LOGGED_IN) { ?>
            <li class="nav-item">
                <a 
                    class="nav-link cart-toggle"
                    data-toggle="collapse"
                    data-target="#navbarSupportedContent"
                >
                    Basket
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="<?php echo PUBLIC_ROOT; ?>">
                    Map
                </a>
            </li>

            <li class="nav-item">
                <a 
                    class="nav-link <?php if ($Routing->template == 'dashboard') { echo 'active'; } ?>" 
                    href="<?php echo PUBLIC_ROOT . ((isset($User->GrowerOperation)) ? 'dashboard/grower' : 'dashboard/account/edit-profile/basic-information'); ?>"
                >
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo PUBLIC_ROOT . 'dashboard/messages/inbox/buying'; ?>">
                    Messages
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo PUBLIC_ROOT . 'dashboard/account/orders-placed/overview'; ?>">
                    Your purchases
                </a>
            </li>

            <?php if (isset($User->GrowerOperation)) {
                echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"" . PUBLIC_ROOT . "dashboard/grower/food-listings/overview\">Your listings</a></li>";
                echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"" . PUBLIC_ROOT . $User->GrowerOperation->link . "\">View profile</a></li>";
            } ?>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo PUBLIC_ROOT . 'dashboard/account/edit-profile/basic-information'; ?>">
                    Edit profile
                </a>
            </li>

            <li class="nav-item">
                <a id="log-out" class="nav-link" href="#">Log out</a>
            </li>
        <?php } else { ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo PUBLIC_ROOT; ?>">
                    Map
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#sign-up-modal">
                    <span>
                        Sign up
                    </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#log-in-modal">
                    <span>
                        Log in
                    </span>
                </a>
            </li>
        <?php } ?>
    </ul>
</div>