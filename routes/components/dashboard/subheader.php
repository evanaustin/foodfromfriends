<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <main class="col-sm-9 col-md-10">
            <div class="subheader">
                <ul class="nav nav-fill">
                    <?php

                    if (count($User->Operations) > 1) {

                        ?>

                        <li class="nav-item dropdown">
                            <a 
                                href="<?php echo PUBLIC_ROOT . $Routing->template . '/grower/food-listings/overview'; ?>"
                                class="nav-link dropdown-toggle <?php if ($Routing->section == 'grower') echo 'active'; ?>" 
                                data-toggle="dropdown"
                            >
                                <?php echo ($User->GrowerOperation->type == 'none' ? 'Grower' : $User->GrowerOperation->name); ?>
                            </a>

                            <div class="dropdown-menu">
                                <?php

                                foreach ($User->Operations as $Operation) {
                                    echo '<a class="dropdown-item ' . (($User->GrowerOperation->id == $Operation->id) ? 'active' : 'switch-operation') . '" data-grower-operation-id="' . $Operation->id .'">' . (!empty($Operation->name) ? $Operation->name : 'Individual') . '</a>';
                                }

                                ?>
                            </div>
                        </li>
                        
                        <?php

                    } else {

                        ?>

                        <li class="nav-item">
                            <a 
                                href="<?php echo PUBLIC_ROOT . $Routing->template . '/grower/food-listings/overview'; ?>" 
                                class="nav-link <?php if ($Routing->section == 'grower') echo 'active'; ?>"
                            >
                                Grower
                            </a>
                        </li>

                        <?php

                    }

                    foreach ([
                        // 'grower'    => 'food-listings/overview',
                        'account'   => 'edit-profile/basic-information'
                    ] as $section => $subsection) { ?>
                        <li class="nav-item">
                            <a 
                                href="<?php echo PUBLIC_ROOT . $Routing->template . '/' . $section . '/' . $subsection; ?>" 
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