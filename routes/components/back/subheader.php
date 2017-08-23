<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <main class="col-sm-9 col-md-10">
            <div class="subheader">
                <ul class="nav nav-fill">
                    <?php foreach ([
                        'dashboard' => 'food-listings/overview',
                        // 'stats' => '',
                        'profile' => '',
                        // 'account' => '',
                    ] as $section => $subsection) { ?>
                        <li class="nav-item">
                            <a 
                                href="<?php echo PUBLIC_ROOT . $section . '/' . $subsection; ?>" 
                                class="nav-link <?php if ($Routing->section == $section) echo 'active'; ?>"
                            >
                                <?php echo ucfirst($section) ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <!-- cont main -->
    <!-- cont div.row -->
<!-- cont div.container-fluid -->