<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <nav class="navbar navbar-toggleable-sm navbar-light">
                <button class="navbar-toggler navbar-toggler-left animated bounceIn" type="button" data-toggle="collapse" data-target="#sidebar-collapse" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-clone"></i>
                </button>
            
                <div class="collapse navbar-collapse" id="sidebar-collapse">
                    <ul class="nav flex-column">
                        <?php 
                        
                        $sidebar = [
                            'dashboard' => [
                                'food-listings' => [
                                    'overview',
                                    'add-new'
                                ],
                                'exchange-settings' => [
                                    'delivery',
                                    'pickup',
                                    'meetup'
                                ]
                            ],
                            'account' => [
                                'edit-profile' => [
                                    'basic-information',
                                    'location',
                                ],
                                /* 'account-settings' => [
                                    'notifications',
                                    'payout',
                                    'payment'
                                ] */
                                // 'edit' => 'edit-profile', // link alias format
                            ]
                        ];

                        foreach($sidebar[$Routing->section] as $k => $v) { ?>
                            <li class="nav-item">
                                <?php if (!empty($k) && gettype($k) == 'string' && gettype($v) == 'array') { ?>
                                    <a 
                                        href="" 
                                        class="nav-link parent <?php if ($Routing->subsection == $k) echo 'active'; ?>" 
                                        data-toggle="collapse" 
                                        data-target="#navbarToggle-<?php echo $k ;?>" 
                                        aria-controls="navbarToggle-<?php echo $k ;?>" 
                                        aria-expanded="<?php echo ($Routing->subsection == $k) ? 'true' : 'false'; ?>" 
                                        aria-label="Toggle navigation"
                                    >
                                        <?php echo ucfirst(str_replace('-', ' ', $k)); ?>
                                    </a>

                                    <div class="collapse <?php if ($Routing->subsection == $k) echo 'show'; ?>" id="navbarToggle-<?php echo $k;?>">
                                        <ul class="nav flex-column">
                                            <?php foreach($v as $l) { ?>
                                                <li class="nav-item">
                                                    <a 
                                                        href="<?php echo PUBLIC_ROOT . $Routing->section . '/' . $k . '/' . $l; ?>"
                                                        class="nav-link child <?php if ($Routing->page == $l) echo 'active'; ?>">
                                                        <?php echo ucfirst(str_replace('-', ' ', $l)); ?>
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                <?php } else if (!empty($k) && gettype($k) == 'string' && gettype($v) == 'string') { ?>
                                    <a 
                                        href="<?php echo PUBLIC_ROOT . $Routing->section . '/' . $k; ?>"
                                        class="nav-link <?php if ($Routing->subsection == $k) echo 'active'; ?>">
                                        <?php echo ucfirst(str_replace('-', ' ', $v)); ?>
                                    </a>
                                <?php } else { ?>
                                    <a 
                                        href="<?php echo PUBLIC_ROOT . $Routing->section . '/' . $v; ?>"
                                        class="nav-link <?php if ($Routing->subsection == $v) echo 'active'; ?>">
                                        <?php echo ucfirst(str_replace('-', ' ', $v)); ?>
                                    </a>
                                <?php } ?>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </nav>
        </div>
    <!-- cont div.row -->
<!-- cont div.container-fluid -->