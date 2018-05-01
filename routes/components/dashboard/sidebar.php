<div class="sidebar d-none d-md-block">
    <nav class="navbar navbar-light">
        <ul class="nav flex-column">
            <?php 
            
            $sidebar = [
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
                        'overview',
                        'add-new'
                    ],
                    'exchange-options' => [
                        'delivery',
                        'pickup',
                        'meetup'
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
            ];

            ?>

            <?php if ($Routing->template == 'dashboard' && $Routing->section == 'selling'): ?>
                <li class="nav-item">
                    <a 
                        href="<?= PUBLIC_ROOT . $Routing->template . '/' . $Routing->section; ?>"
                        class="nav-link <?php if ($Routing->template == 'dashboard' && empty($Routing->subsection)) echo 'active'; ?>">
                        <?= 'Dashboard' ?>
                    </a>
                </li>
            <?php endif; ?>

            <?php foreach ($sidebar[$Routing->section] as $k => $v) { ?>
                <li class="nav-item">
                    <?php if (!empty($k) && gettype($k) == 'string' && gettype($v) == 'array') { ?>
                        <a 
                            href="" 
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
                                                            'buyer_account_id'  => $User->BuyerAccount->id,
                                                            'sent_by'           => 'grower',
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
            <?php } ?>
        </ul>
    </nav>
</div>