<main>
    <div class="subheader">
        <ul class="nav nav-fill">
            
            <?php if ($Routing->section == 'buying' && count($User->BuyerAccounts) > 1): ?>

                <li class="nav-item dropdown">
                    <a 
                        href="<?= PUBLIC_ROOT . $Routing->template . '/buying/orders/overview'; ?>"
                        class="nav-link dropdown-toggle <?php if ($Routing->section == 'buying') echo 'active'; ?>" 
                        data-toggle="dropdown"
                    >
                        <?= $User->BuyerAccount->name ?>
                    </a>

                    <div class="dropdown-menu">

                        <?php foreach ($User->BuyerAccounts as $BuyerAccount) {
                            echo '<a ' . (($User->BuyerAccount->id == $BuyerAccount->id) ? 'href="' . PUBLIC_ROOT . 'dashboard/buying/orders/overview"' : '') . ' class="dropdown-item ' . (($User->BuyerAccount->id == $BuyerAccount->id) ? 'active' : 'switch-buyer-account') . '" data-buyer-account-id="' . $BuyerAccount->id .'">' . $BuyerAccount->name . '</a>';
                        } ?>

                    </div>
                </li>

            <?php else: ?>

                <li class="nav-item">
                    <a 
                        href="<?= PUBLIC_ROOT . $Routing->template . '/buying/orders/overview'; ?>"
                        class="nav-link <?php if ($Routing->section == 'buying') echo 'active'; ?>"
                    >
                        <?= ($Routing->section == 'buying' && isset($User->BuyerAccount)) ? $User->BuyerAccount->name : 'Buying'; ?>
                    </a>
                </li>

            <?php endif; ?>

            <?php if ($Routing->section == 'selling' && count($User->Operations) > 1): ?>

                <li class="nav-item dropdown">
                    <a 
                        href="<?= PUBLIC_ROOT . $Routing->template . '/selling'; ?>"
                        class="nav-link dropdown-toggle <?php if ($Routing->section == 'selling') echo 'active'; ?>" 
                        data-toggle="dropdown"
                    >
                        <?= ($User->GrowerOperation->type == 'individual' ? 'Selling' : $User->GrowerOperation->name); ?>
                    </a>

                    <div class="dropdown-menu">
                        <?php

                        foreach ($User->Operations as $Operation) {
                            echo '<a ' . (($User->GrowerOperation->id == $Operation->id) ? 'href="' . PUBLIC_ROOT . 'dashboard/selling"' : '') . ' class="dropdown-item ' . (($User->GrowerOperation->id == $Operation->id) ? 'active' : 'switch-operation') . '" data-grower-operation-id="' . $Operation->id .'">' . (!empty($Operation->name) ? $Operation->name : 'Individual') . '</a>';
                        }

                        ?>
                    </div>
                </li>
                
            <?php else: ?>

                <li class="nav-item">
                    <a 
                        href="<?= PUBLIC_ROOT . $Routing->template . '/selling'; ?>"
                        class="nav-link <?php if ($Routing->section == 'selling') echo 'active'; ?>"
                    >
                        <?= ($Routing->section == 'selling' && isset($User->GrowerOperation) && $User->GrowerOperation->type != 'individual') ? $User->GrowerOperation->name : 'Selling'; ?>
                    </a>
                </li>

            <?php endif; ?>
            
            <?php foreach ([
                // 'messages'  => 'inbox/buying',
                'account'   => 'settings/personal'
            ] as $section => $subsection): ?>

                <li class="nav-item">
                    <a 
                        href="<?= PUBLIC_ROOT . $Routing->template . '/' . $section . (!empty($subsection) ? '/' . $subsection : ''); ?>" 
                        class="nav-link <?php if ($Routing->section == $section) echo 'active'; ?>"
                    >

                        <?php if ($section == 'messages' && $Routing->section != 'messages'): ?>

                            <?php $Message = new Message([
                                'DB' => $DB
                            ]); ?>

                            <?php $unread = $Message->unread_aggregate($User); ?>

                            <?php if ($unread) echo '<i class="fa fa-circle info jackInTheBox animated" data-toggle="tooltip" data-placement="left" data-title="You have unread messages"></i>'; ?>
                        
                        <?php endif; ?>

                        <?= ucfirst($section); ?>
                    </a>
                </li>

            <?php endforeach; ?>

        </ul>
    </div>