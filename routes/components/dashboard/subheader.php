<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <main class="col-sm-9 col-md-10">
            <div class="subheader">
                <ul class="nav nav-fill">
                    <?php foreach ([
                        'grower'    => '',
                        'account'   => 'edit-profile/basic-information'
                    ] as $section => $subsection) { ?>
                        <li class="nav-item">
                            <a 
                                href="<?php echo PUBLIC_ROOT . $Routing->template . '/' . $section . (!empty($subsection) ? '/' . $subsection : ''); ?>" 
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