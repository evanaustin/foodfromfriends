<nav id="header" class="navbar navbar-toggleable-md navbar-light bg-faded">
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
                    <a class="nav-link" href="#">Grow</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Shop</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">Messages</a>
                </li>

                <li class="nav-item dropdown">
                    <div class="nav-link dropdown-toggle profile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></div>
                
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">Edit profile</a>
                        <a class="dropdown-item" href="#">Account settings</a>
                        <a id="log-out" class="dropdown-item" href="#">Log out</a>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>