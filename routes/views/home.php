<main>
    <div class="main container-fluid">
        <div id="hero">
            <div class="row">
                <div class="d-none col-lg-3 d-lg-flex flexcenter no-padding">
                    <?php
                    
                    img('homepage/veggies', 'jpg', [
                        'server'    => 'local',
                        'class'     => 'img-fluid veggies',
                        'title'     => 'Veggies | Food From Friends'
                    ]);
                    
                    ?>
                </div>

                <div class="col-8 col-lg-6 d-flex flexjustifycenter flexcolumn lg-no-padding">
                    <h1>
                        the new way<br class="d-none d-sm-inline">
                        to buy & sell<br>
                        <strong>local food</strong>
                    </h1>

                    <div class="row d-md-flex">
                        <div class="col-12 col-lg-6 col-xl-4">
                            <a href="<?php echo PUBLIC_ROOT . 'map'; ?>" class="btn btn-primary btn-block btn-lg">See who's selling</a>
                        </div>

                        <div class="col-12 col-lg-6 col-xl-3">
                            <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/settings/edit-profile'; ?>" class="btn btn-dark btn-block btn-lg start-selling">Start selling</a>
                        </div>
                    </div>
                </div>

                <div class="col-4 col-lg-3 d-lg-flex flexcenter flexend no-padding text-right">
                    <?php
                    
                    img('homepage/colorbowl-phone', 'jpg', [
                        'server'    => 'local',
                        'class'     => 'img-fluid colorbowl d-inline d-md-none',
                        'title'     => 'Color bowl | Food From Friends'
                    ]);

                    img('homepage/colorbowl', 'png', [
                        'server'    => 'local',
                        'class'     => 'img-fluid colorbowl d-none d-md-inline d-xl-none',
                        'title'     => 'Color bowl | Food From Friends'
                    ]);
                    
                    img('homepage/colorbowl', 'png', [
                        'server'    => 'local',
                        'class'     => 'img-fluid colorbowl d-none d-xl-inline',
                        'title'     => 'Color bowl | Food From Friends'
                    ]);
                    
                    ?>
                </div>
            </div>
        </div>

        <div id="sidekick">
            <div id="thought" class="row">
                <div class="col-12 col-sm-3 col-md-2 col-lg-1 no-padding">
                    <?php
                    
                    img('homepage/chard', 'jpg', [
                        'server'    => 'local',
                        'class'     => 'chard',
                        'title'     => 'Chard | Food From Friends'
                    ]);
                    
                    ?>
                </div>

                <div class="col-12 col-sm-9 col-md-6 col-lg-7 offset-lg-0 lg-no-padding">
                    <div id="thought-for-food">
                        <h1>
                            Thought<br>
                            <strong>for food</strong>
                        </h1>
                        
                        <p>
                            Who grows your tomatoes? How were they grown? When were they picked?
                        </p>

                        <p>
                            If you can answer these questions, you're the exception. Buying food these days is too often a mysterious, detached experience. It hasn't always been this way.
                        </p>
                    </div>
                </div>

                <div class="col-md-4 d-none d-md-flex flexend no-padding">
                    <?php
                    
                    img('homepage/tomatoes', 'jpg', [
                        'server'    => 'local',
                        'class'     => 'img-fluid tomatoes',
                        'title'     => 'Tomatoes | Food From Friends'
                    ]);
                    
                    ?>
                </div>
            </div>

            <div id="model" class="row">
                <div class="col-12 col-sm-6 col-lg-3 col-xl-4 no-padding">
                    <?php
                    
                    img('homepage/square-plate-phone', 'jpg', [
                        'server'    => 'local',
                        'class'     => 'img-fluid plate d-inline d-sm-none',
                        'title'     => 'Meal | Food From Friends'
                    ]);
                    
                    img('homepage/square-plate', 'jpg', [
                        'server'    => 'local',
                        'class'     => 'img-fluid plate d-none d-sm-inline',
                        'title'     => 'Meal | Food From Friends'
                    ]);
                    
                    ?>
                </div>
    
                <div class="col-12 col-sm-6 col-lg-5 col-xl-4">
                    <div id="model-for-the-future">
                        <div class="row">
                            <div class="col-12 col-lg-10 offset-lg-2 col-xl-12 offset-xl-0 lg-no-padding">
                                <h1>
                                    A historic model<br>
                                    <strong><span class="d-none d-sm-inline">for</span> the future</strong>
                                </h1>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-lg-10 offset-lg-2 col-xl-12 offset-xl-0">
                                <p>
                                    We believe the best solution to the problems in our food system is <strong>neighbor-to-neighbor</strong> commerce.
                                </p>
                                
                                <p>
                                    It's a practice nearly as old as humanity itself, but it needs new life &mdash; fresh perspective, energy, and technology. That's where we come in.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="platform" class="row">
                <div class="col-12 d-inline d-sm-none col-lg-4 d-lg-inline">
                    <?php
                    
                    img('homepage/smoothie', 'jpg', [
                        'server'    => 'local',
                        'class'     => 'smoothie',
                        'title'     => 'Smoothie | Food From Friends'
                    ]);
                    
                    ?>
                </div>

                <div class="col-12 col-sm-7 col-md-8 col-lg-7 col-lg-6">
                    <div id="platform-for-everyone">
                        <h1>
                            A platform<br>
                            <strong>for everyone</strong>
                        </h1>
    
                        <p>
                            Food From Friends exists to make the local food system seamless. We're making it easy and convenient for anyone to exchange local food.
                        </p>
                        
                        <p>
                            We enable <strong>micro</strong> to <strong>medium-scale farmers</strong> and <strong>home gardeners</strong> with new market exposure and a platform to tell their story. 
                            We empower <strong>consumers</strong>, <strong>chefs</strong>, and <strong>wholesale buyers</strong> with choice and access they've never had before. 
                        </p>

                        <p>
                            We're here to connect you. To food, and to friends.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-sm-5 col-md-4 d-lg-none d-flex flexend no-padding">
                    <?php
                    
                    img('homepage/chard-bunch', 'jpg', [
                        'server'    => 'local',
                        'class'     => 'img-fluid chard-bunch',
                        'title'     => 'Chard bunch | Food From Friends'
                    ]);
                    
                    ?>
                </div>
            </div>
                    
            <div id="action" class="row">
                <div class="d-none col-md-6 d-md-inline d-lg-none">
                    <?php
                    
                    img('homepage/smoothie', 'jpg', [
                        'server'    => 'local',
                        'class'     => 'img-fluid smoothie',
                        'title'     => 'Smoothie | Food From Friends'
                    ]);
                    
                    ?>
                </div>

                <div class="col-12 col-md-6 col-lg-6 col-xl-5">
                    <div id="practice">
                        <h2>
                            Try out a thoughtful, new<br>
                            food experience
                        </h2>

                        <div class="row d-sm-flex flexjustifycenter">
                            <div class="col-12 col-sm-6 col-md-12 col-lg-5">
                                <a href="<?php echo PUBLIC_ROOT . 'map'; ?>" class="btn btn-primary btn-block btn-lg">Buy local</a>
                            </div>

                            <div class="col-12 col-sm-6 col-md-12 col-lg-5">
                                <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/settings/edit-profile'; ?>" class="btn btn-dark btn-block btn-lg start-selling">Sell local</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-none col-lg-6 d-lg-flex col-xl-7 flexend no-padding text-right">
                    <?php
                    
                    img('homepage/loaf-phone', 'jpg', [
                        'server'    => 'local',
                        'class'     => 'img-fluid loaf d-inline d-sm-none',
                        'title'     => 'Bread | Food From Friends'
                    ]);
                    
                    img('homepage/loaf', 'jpg', [
                        'server'    => 'local',
                        'class'     => 'img-fluid loaf d-none d-sm-inline',
                        'title'     => 'Bread | Food From Friends'
                    ]);
                    
                    ?>
                </div>
            </div>
        </div>
    </div>
</main>

<nav id="footer" class="navbar">
    <ul class="navbar-nav ml-auto mr-auto">
        <li class="nav-item">
            © Food From Friends, Inc.
        </li>
    </ul>
</nav>