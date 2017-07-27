<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <main class="col-sm-9 col-md-10">
            <div class="subheader">
                <ul class="nav nav-fill">
                    <?php foreach ([
                        'dashboard',
                        'stats',
                        'profile',
                        'account',
                    ] as $title) { ?>
                        <li class="nav-item">
                            <a 
                                href="<?php echo PUBLIC_ROOT . $Routing->template . '/' . $title; ?>" 
                                class="nav-link <?php if ($Routing->section == $title) echo 'active'; ?>"
                            >
                                <?php echo ucfirst($title) ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <!-- cont main -->
    <!-- cont div.row -->
<!-- cont div.container-fluid -->