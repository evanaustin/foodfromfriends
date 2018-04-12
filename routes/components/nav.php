<div id="nav" off-canvas="slidebar-left left push">
    <ul class="navbar-nav ml-auto">
        <?php if ($LOGGED_IN) {

            if ($Routing->template == 'front') { ?>

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
                    <a class="nav-link" href="<?= PUBLIC_ROOT; ?>">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= PUBLIC_ROOT . 'map'; ?>">
                        Map
                    </a>
                </li>

                <li class="nav-item">
                    <a 
                        class="nav-link" 
                        href="<?= PUBLIC_ROOT . ((isset($User->GrowerOperation)) ? 'dashboard/grower' : 'dashboard/account/edit-profile/basic-information'); ?>"
                    >
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= PUBLIC_ROOT . 'dashboard/messages/inbox/buying'; ?>">
                        Messages
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="<?= PUBLIC_ROOT . 'dashboard/account/buying/orders'; ?>">
                        Order history
                    </a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="<?= PUBLIC_ROOT . "user/{$User->slug}"; ?>">
                        View profile
                    </a>
                </li>

                <?php
                
                if (isset($User->GrowerOperation) && $User->GrowerOperation->is_active) {
                    echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"" . PUBLIC_ROOT . $User->GrowerOperation->link . "\">Seller profile</a></li>";
                    echo "<li class=\"nav-item\"><a class=\"nav-link\" href=\"" . PUBLIC_ROOT . "dashboard/grower/items/overview\">Your items</a></li>";
                }
                
                ?>

                <li class="nav-item">
                    <a class="nav-link" href="<?= PUBLIC_ROOT . 'dashboard/account/edit-profile/basic-information'; ?>">
                        Edit profile
                    </a>
                </li>

                <li class="nav-item">
                    <a id="log-out" class="nav-link" href="#">Log out</a>
                </li>

            <?php } if ($Routing->template == 'dashboard') { ?>
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

                <?php

                $sidebar = [
                    'grower' => [
                        'orders' => [
                            'new',
                            'pending',
                            'under-review',
                            'completed',
                            'failed'
                        ],
                        'items' => [
                            'overview',
                            'add-new'
                        ],
                        'exchange-options' => [
                            'delivery',
                            'pickup',
                            'meetup'
                        ],
                        'settings' => [
                            'edit-profile',
                            'payout-settings',
                            'team-members',
                            // 'create-new'
                        ]
                    ],
                    'messages' => [
                        'inbox' => [
                            'buying'
                        ]
                    ],
                    'account' => [
                        'edit-profile' => [
                            'basic-information',
                            'billing-info',
                            'delivery-address'
                        ],
                        'buying' => [
                            'orders',
                            'wish-list',
                        ],
                        /* 'account-settings' => [
                            'notifications',
                            'payout',
                            'payment'
                        ] */
                        // 'edit' => 'edit-profile', // link alias format
                    ]
                ];

                foreach($User->Operations as $Op) {
                    if ($Op->type != 'individual') {
                        $sidebar['messages']['inbox']['selling?grower=' . $Op->id] = $Op->name;
                    } else {
                        array_splice($sidebar['messages']['inbox'], 1, 0, 'selling');
                    }
                }

                ?>

                <?php if ($Routing->section == 'grower') { ?>
                    <li class="nav-item">
                        <a 
                            href="<?= PUBLIC_ROOT . $Routing->template . '/' . $Routing->section; ?>"
                            class="nav-link <?php if (!isset($Routing->page)) echo 'active'; ?>">
                            <?= 'Dashboard'; ?>
                        </a>
                    </li>
                <?php } ?>

                <?php foreach ($sidebar[$Routing->section] as $k => $v) { ?>
                    <li class="nav-item">
                        <?php if (!empty($k) && gettype($k) == 'string' && gettype($v) == 'array') { ?>
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
                                    <?php
                                    
                                    foreach($v as $alias_key => $alias) {
                                        if (!empty($alias)) {
                                        
                                        ?>

                                        <li class="nav-item">
                                            <a 
                                                href="<?= PUBLIC_ROOT . $Routing->template . '/'. $Routing->section . '/' . $k . '/' . ((gettype($alias_key) == 'string') ? $alias_key : $alias); ?>"
                                                class="nav-link child 
                                                
                                                <?php 

                                                $active = false;

                                                if ($Routing->page == $alias && !($Routing->page == 'selling' && isset($_GET['grower']))) {
                                                    $active = true;
                                                } else if ($Routing->subsection == 'inbox' && $Routing->page == 'selling' && isset($_GET['grower'])) {
                                                    if ($User->Operations[\Num::clean_int($_GET['grower'])]->name == $alias) {
                                                        $active = true;
                                                    }
                                                }
                                                
                                                if ($active) echo 'active';
                                                
                                                ?>
                                            ">

                                                <?php

                                                echo ucfirst(str_replace('-', ' ', $alias));

                                                // insert bubbles for new orders
                                                if ($alias == 'new' && $Routing->section == 'grower' && $Routing->page != $alias && isset($User) && $User->GrowerOperation->new_orders) echo '<i class="fa fa-circle info jackInTheBox animated"></i>';
                                                if ($alias == 'pending' && $Routing->section == 'grower' && $Routing->page != $alias && isset($User) && $User->GrowerOperation->pending_orders) echo '<i class="fa fa-circle warning jackInTheBox animated"></i>';
                                                
                                                // insert bubbles for unread messages
                                                if ($Routing->section == 'messages' && $Routing->subsection == 'inbox') {
                                                    $Message = new Message([
                                                        'DB' => $DB
                                                    ]);

                                                    if ($alias == 'buying' && !$active) {
                                                        $unread = $Message->retrieve([
                                                            'where' => [
                                                                'user_id' => $User->id,
                                                                'sent_by' => 'grower',
                                                                'read_on' => null
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
                                                                    'sent_by' => 'user',
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
                                <?= ucfirst(str_replace('-', ' ', $v)); ?>
                            </a>
                        <?php } ?>
                    </li>
                <?php }

            }

        } else { ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= PUBLIC_ROOT . 'map'; ?>">
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