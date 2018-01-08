<main>
    <div class="subheader">
        <ul class="nav nav-fill">
            <?php

            if ($Routing->section == 'grower' && count($User->Operations) > 1) {

                ?>

                <li class="nav-item dropdown">
                    <a 
                        href="<?php echo PUBLIC_ROOT . $Routing->template . '/grower'; ?>"
                        class="nav-link dropdown-toggle <?php if ($Routing->section == 'grower') echo 'active'; ?>" 
                        data-toggle="dropdown"
                    >
                        <?php echo ($User->GrowerOperation->type == 'none' ? 'Grower' : $User->GrowerOperation->name); ?>
                    </a>

                    <div class="dropdown-menu">
                        <?php

                        foreach ($User->Operations as $Operation) {
                            echo '<a ' . (($User->GrowerOperation->id == $Operation->id) ? 'href="' . PUBLIC_ROOT . 'dashboard/grower"' : '') . ' class="dropdown-item ' . (($User->GrowerOperation->id == $Operation->id) ? 'active' : 'switch-operation') . '" data-grower-operation-id="' . $Operation->id .'">' . (!empty($Operation->name) ? $Operation->name : 'Individual') . '</a>';
                        }

                        ?>
                    </div>
                </li>
                
                <?php

            } else {

                ?>

                <li class="nav-item">
                    <a 
                        href="<?php echo PUBLIC_ROOT . $Routing->template . '/grower'; ?>"
                        class="nav-link <?php if ($Routing->section == 'grower') echo 'active'; ?>"
                    >
                        <?php echo ($Routing->section == 'grower' && isset($User->GrowerOperation) && $User->GrowerOperation->type != 'none') ? $User->GrowerOperation->name : 'Grower'; ?>
                    </a>
                </li>

                <?php

            }

            foreach ([
                // 'grower'    => 'food-listings/overview',
                'messages'  => 'inbox/buying',
                'account'   => 'edit-profile/basic-information'
            ] as $section => $subsection) { ?>
                <li class="nav-item">
                    <a 
                        href="<?php echo PUBLIC_ROOT . $Routing->template . '/' . $section . (!empty($subsection) ? '/' . $subsection : ''); ?>" 
                        class="nav-link <?php if ($Routing->section == $section) echo 'active'; ?>"
                    >
                        <?php
                        
                        // insert bubble for unread message
                        if ($section == 'messages' && $Routing->section != 'messages') {
                            $Message = new Message([
                                'DB' => $DB
                            ]);

                            $unread = $Message->unread_aggregate($User);

                            if ($unread) echo '<i class="fa fa-circle info jackInTheBox animated" data-toggle="tooltip" data-placement="left" data-title="You have unread messages"></i>';
                        }

                        echo ucfirst($section);

                        ?>

                        
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>