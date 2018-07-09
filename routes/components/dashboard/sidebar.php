<div class="sidebar d-none d-md-block">
    <nav class="navbar navbar-light">
        <ul class="nav flex-column">
            <?php $buying   = isset($User->BuyerAccount) ?>
            <?php $selling  = isset($User->GrowerOperation) ?>

            <?php if ($buying || $selling): ?>

                <?php if ($buying) {
                    $account_name = $User->BuyerAccount->name;
                    $account_type = $User->BuyerAccount->type;
                } else if ($selling) {
                    $account_name = $User->GrowerOperation->name;
                    $account_type = $User->GrowerOperation->type;
                } ?>

                <?php $multiple = (count($User->BuyerAccounts) > 1 || count($User->Operations) > 1) ?>

                <li class="nav-item account-link dropdown">
                    <a href="" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-<?= ($account_type == 'individual') ? 'user-circle-o' : 'address-card-o' ?>"></i>
                        <?= truncate($account_name, 20) ?>
                    </a>

                    <div class="dropdown-menu">

                        <?php if ($buying): ?>

                            <?php foreach ($User->BuyerAccounts as $BuyerAccount): ?>
                            
                                <?php if ($User->BuyerAccount->id == $BuyerAccount->id) continue; ?>

                                <a href="<?= PUBLIC_ROOT ?>dashboard/buying/orders/overview" class="dropdown-item switch-buyer-account" data-buyer-account-id="<?= $BuyerAccount->id ?>">
                                    <i class="fa fa-<?= ($BuyerAccount->type == 'individual') ? 'user-circle-o' : 'address-card-o' ?>"></i>
                                    <?= $BuyerAccount->name ?>
                                </a>
                            
                            <?php endforeach; ?>
                            
                            <a href="<?= PUBLIC_ROOT ?>dashboard/buying/new-account" class="dropdown-item">
                                <i class="fa fa-external-link"></i>
                                Create another account
                            </a>

                        <?php elseif ($selling): ?>

                            <?php foreach ($User->Operations as $Operation): ?>

                                <?php if ($User->GrowerOperation->id == $Operation->id) continue ?>
                                
                                <a href="<?= PUBLIC_ROOT ?>dashboard/selling" class="dropdown-item switch-operation" data-grower-operation-id="<?= $Operation->id ?>">
                                    <i class="fa fa-<?= ($Operation->type == 'individual') ? 'user-circle-o' : 'address-card-o' ?>"></i>
                                    <?= $Operation->name ?>
                                </a>
                            
                            <?php endforeach; ?>

                            <a href="<?= PUBLIC_ROOT ?>dashboard/selling/new-account" class="dropdown-item">
                                <i class="fa fa-external-link"></i>
                                Create another account
                            </a>

                        <?php endif; ?>

                    </div>
                </li>

            <?php else: ?>

                <li class="nav-item account-link">
                    <span class="nav-link">
                        <i class="fa fa-user-circle-o"></i>
                        <?= $User->name ?>
                    </span>
                </li>

            <?php endif; ?>

            <?php $sidebar = [
                'buying' => [
                    'messages',
                    'orders' => [
                        'overview'
                    ],
                    'wish-list' => [
                        'items'
                    ],
                    'wholesale' => [
                        'sellers'
                    ],
                    'settings' => [
                        'profile',
                        'billing',
                        // 'team',
                        // 'create-new'
                    ]
                    //'saved-items',
                ],
                'selling' => [
                    'messages',
                    'orders' => [
                        'new',
                        'pending',
                        'under-review',
                        'completed',
                        'failed'
                    ],
                    'items' => [
                        'retail',
                        'wholesale',
                        'add-new'
                    ],
                    'exchange-options' => [
                        'delivery',
                        'meetups'
                    ],
                    'wholesale' => [
                        'buyers'
                    ],
                    'settings' => [
                        'profile',
                        'payout',
                        'team',
                        // 'create-new'
                    ]
                ],
                'account' => [
                    'settings' => [
                        'personal',
                        // 'notifications',
                        // 'language',
                        // 'social',
                        // 'security'
                    ],
                    // 'edit' => 'edit-profile', // link alias format
                ]
            ]; ?>

            <?php if ($Routing->section == 'selling'): ?>
                <li class="nav-item">
                    <a 
                        href="<?= PUBLIC_ROOT . "{$Routing->template}/{$Routing->section}"; ?>"
                        class="nav-link <?php if (empty($Routing->subsection)) echo 'active'; ?>">
                        <?= 'Dashboard' ?>
                    </a>
                </li>
            <?php endif; ?>

            <?php foreach ($sidebar[$Routing->section] as $k => $v) { ?>
                <li class="nav-item">
                    <?php if (!empty($k) && gettype($k) == 'string' && gettype($v) == 'array') { ?>
                        <a 
                            href="" 
                            id="sidebar-<?= $k ?>"
                            class="nav-link parent <?php if ($Routing->subsection == $k) echo 'active'; ?>" 
                            data-toggle="collapse" 
                            data-target="#navbarToggle-<?= $k ;?>" 
                            aria-controls="navbarToggle-<?= $k ;?>" 
                            aria-expanded="<?= ($Routing->subsection == $k) ? 'true' : 'false'; ?>" 
                            aria-label="Toggle navigation"
                        >
                            <?= ucfirst(str_replace('-', ' ', $k)); ?>
                        </a>

                        <div class="collapse <?php if ($Routing->subsection == $k) echo 'show'; ?>" id="navbarToggle-<?= $k;?>">
                            <ul class="nav flex-column">
                                <?php
                                
                                foreach($v as $alias_key => $alias) {
                                    if (!empty($alias)) {
                                    
                                    ?>

                                    <li class="nav-item">
                                        <a 
                                            href="<?= PUBLIC_ROOT . $Routing->template . '/'. $Routing->section . '/' . $k . '/' . ((gettype($alias_key) == 'string') ? $alias_key : $alias); ?>"
                                            class="nav-link child 
                                            
                                            <?php if ($Routing->page == $alias) {
                                                echo 'active';
                                            } ?>
                                            
                                        ">

                                            <?php

                                            echo ucfirst(str_replace('-', ' ', $alias));

                                            if ($Routing->section == 'selling' && $Routing->page != $alias && isset($User->GrowerOperation)) {
                                                // insert notification bubble for new orders
                                                if ($alias == 'new' && $User->GrowerOperation->new_orders) {
                                                    echo '<i class="fa fa-circle info jackInTheBox animated"></i>';
                                                }

                                                // insert notification bubble for pending orders
                                                if ($alias == 'pending'  && $User->GrowerOperation->pending_orders) {
                                                    echo '<i class="fa fa-circle warning jackInTheBox animated"></i>';
                                                }
                                            }

                                            ?>
                                        </a>

                                    </li>
                                    
                                    <?php
                                
                                    }
                                }
                                
                                ?>
                            </ul>
                        </div>
                    <?php } else if (!empty($k) && gettype($k) == 'string' && gettype($v) == 'string') { ?>
                        <a 
                            href="<?= PUBLIC_ROOT . $Routing->template . '/' . $Routing->section . '/' . $k; ?>"
                            class="nav-link <?php if ($Routing->subsection == $k) echo 'active'; ?>">
                            <?= ucfirst(str_replace('-', ' ', $v)); ?>
                        </a>
                    <?php } else if (!empty($v)) { ?>
                        <a 
                            href="<?= PUBLIC_ROOT . $Routing->template . '/' . $Routing->section . '/' . $v; ?>"
                            class="nav-link <?php if ($Routing->subsection == $v) echo 'active'; ?>">
                            
                            <?php

                            // insert bubbles for unread messages
                            if ($v == 'messages') {
                                $Message = new Message([
                                    'DB' => $DB
                                ]);

                                if ($Routing->section == 'buying') {
                                    $field      = 'buyer_account_id';
                                    $id         = $User->BuyerAccount->id;
                                    $sent_by    = 'seller';
                                } else if ($Routing->section == 'selling') {
                                    $field      = 'grower_operation_id';
                                    $id         = $User->GrowerOperation->id;
                                    $sent_by    = 'buyer';
                                }

                                $unread = $Message->retrieve([
                                    'where' => [
                                        $field => $id,
                                        'sent_by' => $sent_by,
                                        'read_on' => null
                                    ],
                                    'limit' => 1
                                ]);

                                if (!empty($unread)) {
                                    echo '<i class="fa fa-circle info jackInTheBox animated"></i>';
                                }
                            }

                            ?>

                            <?= ucfirst(str_replace('-', ' ', $v)); ?>
                
                        </a>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
    </nav>
</div>