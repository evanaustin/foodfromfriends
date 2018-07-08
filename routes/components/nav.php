<div id="nav" off-canvas="slidebar-left left push">
    <ul class="navbar-nav ml-auto">

        <?php if ($LOGGED_IN): ?>

            <?php if ($Routing->template == 'front'): ?>

                <li class="nav-item">
                    <a class="nav-link cart-toggle" data-toggle="collapse">
                        Basket
                    </a>
                </li>

                <hr>
            
                <li class="nav-item">
                    <a class="nav-link" href="<?= PUBLIC_ROOT; ?>">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= PUBLIC_ROOT . 'map'; ?>">
                        Map
                    </a>
                </li>

                <hr>

                <?php if (isset($User->BuyerAccount)): ?>

                    <li class="nav-item">
                        <a href="<?= PUBLIC_ROOT ?>dashboard/buying/orders/overview" class="nav-link">
                            Buying
                        </a>
                    </li>

                <?php endif; ?>

                <?php if (isset($User->GrowerOperation)): ?>

                    <li class="nav-item">
                        <a href="<?= PUBLIC_ROOT ?>dashboard/selling" class="nav-link">
                            Selling
                        </a>
                    </li>

                <?php endif; ?>

                <hr>

                <li class="nav-item">
                    <a class="nav-link" href="<?= PUBLIC_ROOT . 'dashboard/account/settings/personal'; ?>">
                        Account
                    </a>
                </li>

                <li class="nav-item">
                    <a id="log-out" class="nav-link" href="#">Log out</a>
                </li>

            <?php endif; ?>

            <?php if ($Routing->template == 'dashboard'): ?>

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
                        <a href=""
                            class="nav-link parent" 
                            data-toggle="collapse" 
                            data-target="#navToggle-account" 
                            aria-controls="navToggle-<?= $k ;?>" 
                            aria-expanded="<?= ($Routing->subsection == $k) ? 'true' : 'false'; ?>" 
                            aria-label="Toggle navigation"
                        >
                            <i class="fa fa-<?= ($account_type == 'individual') ? 'user-circle-o' : 'address-card-o' ?>"></i>
                            <?= truncate($account_name, 20) ?>
                        </a>

                        <div class="collapse" id="navToggle-account">
                            <ul class="nav flex-column">
                                
                                <?php if ($buying): ?>

                                    <?php foreach ($User->BuyerAccounts as $BuyerAccount): ?>
                                    
                                        <?php if ($User->BuyerAccount->id == $BuyerAccount->id) continue; ?>
                                        
                                        <li class="nav-item">
                                            <a href="<?= PUBLIC_ROOT ?>dashboard/buying/orders/overview" class="switch-buyer-account" data-buyer-account-id="<?= $BuyerAccount->id ?>">
                                                <i class="fa fa-<?= ($BuyerAccount->type == 'individual') ? 'user-circle-o' : 'address-card-o' ?> d-none d-sm-inline"></i><?= $BuyerAccount->name ?>
                                            </a>
                                        </li>
                                    
                                    <?php endforeach; ?>
                                    
                                    <li class="nav-item">
                                        <a href="<?= PUBLIC_ROOT ?>dashboard/buying/new-account">
                                            <i class="fa fa-external-link d-none d-sm-inline"></i>Create another account
                                        </a>
                                    </li>

                                <?php elseif ($selling): ?>

                                    <?php foreach ($User->Operations as $Operation): ?>

                                        <?php if ($User->GrowerOperation->id == $Operation->id) continue ?>
                                        
                                        <li class="nav-item">
                                            <a href="<?= PUBLIC_ROOT ?>dashboard/selling" class="switch-operation" data-grower-operation-id="<?= $Operation->id ?>">
                                                <i class="fa fa-<?= ($Operation->type == 'individual') ? 'user-circle-o' : 'address-card-o' ?> d-none d-sm-inline"></i><?= $Operation->name ?>
                                            </a>
                                        </li>
                                    
                                    <?php endforeach; ?>

                                    <li class="nav-item">
                                        <a href="<?= PUBLIC_ROOT ?>dashboard/selling/new-account">
                                            <i class="fa fa-external-link d-none d-sm-inline"></i>Create another account
                                        </a>
                                    </li>

                                <?php endif; ?>

                            </ul>
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

                <hr>

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

                <?php if ($Routing->section == 'selling') { ?>
                    <li class="nav-item">
                        <a 
                            href="<?= PUBLIC_ROOT . "{$Routing->template}/$Routing->section}" ?>"
                            class="nav-link <?php if (!isset($Routing->page)) echo 'active'; ?>">
                            <?= 'Dashboard'; ?>
                        </a>
                    </li>
                <?php } ?>

                <?php foreach ($sidebar[$Routing->section] as $k => $v): ?>

                    <li class="nav-item">

                        <?php if (!empty($k) && gettype($k) == 'string' && gettype($v) == 'array'): ?>
                           
                            <a 
                                href="" 
                                class="nav-link parent <?php if ($Routing->subsection == $k) echo 'active'; ?>" 
                                data-toggle="collapse" 
                                data-target="#navToggle-<?= $k ;?>" 
                                aria-controls="navToggle-<?= $k ;?>" 
                                aria-expanded="<?= ($Routing->subsection == $k) ? 'true' : 'false'; ?>" 
                                aria-label="Toggle navigation"
                            >
                                <?= ucfirst(str_replace('-', ' ', $k)); ?>
                            </a>

                            <div class="collapse <?php if ($Routing->subsection == $k) echo 'show'; ?>" id="navToggle-<?= $k;?>">
                                <ul class="nav flex-column">
                                    
                                    <?php foreach($v as $alias_key => $alias): ?>

                                        <?php if (!empty($alias)): ?>

                                        <li class="nav-item">
                                            <a 
                                                href="<?= PUBLIC_ROOT . "{$Routing->template}/{$Routing->section}/{$k}/" . ((gettype($alias_key) == 'string') ? $alias_key : $alias); ?>"
                                                class="nav-link child 
                                                
                                                <?php if ($Routing->page == $alias) {
                                                    echo 'active';
                                                } ?>

                                            ">

                                                <?php

                                                echo ucfirst(str_replace('-', ' ', $alias));

                                                // insert bubbles for new orders
                                                if ($alias == 'new' && $Routing->section == 'selling' && $Routing->page != $alias && isset($User) && $User->GrowerOperation->new_orders) echo '<i class="fa fa-circle info jackInTheBox animated"></i>';
                                                if ($alias == 'pending' && $Routing->section == 'selling' && $Routing->page != $alias && isset($User) && $User->GrowerOperation->pending_orders) echo '<i class="fa fa-circle warning jackInTheBox animated"></i>';
                                                
                                                // insert bubbles for unread messages
                                                if ($Routing->section == 'messages' && $Routing->subsection == 'inbox') {
                                                    $Message = new Message([
                                                        'DB' => $DB
                                                    ]);

                                                    if ($alias == 'buying' && !$active) {
                                                        $unread = $Message->retrieve([
                                                            'where' => [
                                                                'buyer_account_id'  => $User->BuyerAccount->id,
                                                                'sent_by'           => 'seller',
                                                                'read_on'           => null
                                                            ],
                                                            'limit' => 1
                                                        ]);

                                                        if (!empty($unread)) {
                                                            echo '<i class="fa fa-circle info jackInTheBox animated"></i>';
                                                        }
                                                    } else if (!$active) {
                                                        if ($alias != 'selling') {
                                                            $seller_id = str_replace('selling?grower=', '', $alias_key);
                                                        } else if ($User->GrowerOperation->type == 'individual') {
                                                            $seller_id = $User->GrowerOperation->id;
                                                        }
                                                        
                                                        if (isset($seller_id)) {
                                                            $unread = $Message->retrieve([
                                                                'where' => [
                                                                    'grower_operation_id' => $seller_id,
                                                                    'sent_by' => 'buyer',
                                                                    'read_on' => null
                                                                ],
                                                                'limit' => 1
                                                            ]);

                                                            if (!empty($unread)) {
                                                                echo '<i class="fa fa-circle info jackInTheBox animated"></i>';
                                                            }
                                                        }
                                                    }
                                                }

                                                ?>
                                            </a>

                                        </li>
                                        
                                        <?php endif; ?>

                                    <?php endforeach; ?>
                                    
                                </ul>
                            </div>

                        <?php elseif (!empty($k) && gettype($k) == 'string' && gettype($v) == 'string'): ?>

                            <a 
                                href="<?= PUBLIC_ROOT . "{$Routing->template}/{$Routing->section}/{$k}"; ?>"
                                class="nav-link <?php if ($Routing->subsection == $k) echo 'active'; ?>">
                                <?= ucfirst(str_replace('-', ' ', $v)); ?>
                            </a>

                        <?php elseif (!empty($v)): ?>

                            <a 
                                href="<?= PUBLIC_ROOT . "{$Routing->template}/{$Routing->section}/{$v}" ?>"
                                class="nav-link <?php if ($Routing->subsection == $v) echo 'active'; ?>">
                                <?= ucfirst(str_replace('-', ' ', $v)); ?>
                            </a>

                        <?php endif; ?>

                    </li>

                <?php endforeach; ?>

                <hr>

                <li class="nav-item">
                    <a class="nav-link" href="<?= PUBLIC_ROOT; ?>">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= PUBLIC_ROOT ?>map">
                        Map
                    </a>
                </li>

            <?php endif; ?>

        <?php else: ?>

            <li class="nav-item">
                <a class="nav-link" href="<?= PUBLIC_ROOT; ?>">
                    Home
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="<?= PUBLIC_ROOT ?>map">
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

        <?php endif; ?>

    </ul>
</div>