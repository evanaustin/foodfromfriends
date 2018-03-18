<main>
    <div class="main">
        <div class="hero container-fluid">
            <div class="row">
                <div class="d-none col-md-3 d-md-flex flexcenter no-padding">
                    <?php img('homepage/veggies', 'png', 'local', 'img-fluid veggies'); ?>
                </div>

                <div class="col-8 col-md-6 d-flex flexjustifycenter flexcolumn no-padding">
                    <h1>
                        the new way<br>
                        to buy & sell<br>
                        <strong class="uppercase">local food</strong>
                    </h1>

                    <div class="row d-none d-md-flex">
                        <div class="col-md-6 col-xl-4">
                            <a href="<?php echo PUBLIC_ROOT . 'map'; ?>" class="btn btn-primary btn-block btn-lg">See what's for sale</a>
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/items/add-new'; ?>" class="btn btn-dark btn-block btn-lg start-selling">Start selling</a>
                        </div>
                    </div>
                </div>

                <div class="col-4 col-md-3 d-md-flex flexcenter flexend no-padding">
                    <?php img('homepage/colorbowl-half', 'png', 'local', 'img-fluid colorbowl'); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-3 d-block d-md-none no-padding">
                    <?php img('homepage/veggies', 'png', 'local', 'img-fluid veggies'); ?>
                </div>

                <div class="col-9 d-flex flexjustifycenter flexcolumn d-md-none">
                    <a href="<?php echo PUBLIC_ROOT . 'map'; ?>" class="btn btn-primary btn-block">See what's for sale</a>
                    <a href="<?php echo PUBLIC_ROOT . 'dashboard/grower/items/add-new'; ?>" class="btn btn-dark btn-block start-selling">Start selling</a>
                </div>
            </div>
        </div>

        <div class="sidekick container-fluid">
            <div class="row">
                <!-- <div class="col-12 col-md-2 no-padding">
                    <?php //img('homepage/chard', 'png', 'local', 'chard text-left'); ?>
                </div> -->

                <div class="col-12 col-md-7 no-padding shift-up">
                    <?php img('homepage/chard', 'png', 'local', 'chard text-left'); ?>

                    <div id="thought-for-food">
                        <h1>
                            Thought<br>
                            <strong class="uppercase">for food</strong>
                        </h1>
                        
                        <p>
                            Who grows your tomatoes? What kind of conditions were they grown in? How much time has passed since they were picked? What's happened to them since then?
                        </p>

                        <p>
                            Are we afraid to ask these questions because of the answers we may get? Or is there simply no one to ask? Buying food these days is a mysterious, detached experience.
                            <!-- The average American meal consists of food shipped in from <strong>1,500 miles</strong> away. By the time it eventually ends up on our plates it's lost not only flavor but also nutritional quality. -->
                        </p>

                        <p>
                            It shouldn't be.
                        </p>
                    </div>

                    <?php img('homepage/artichoke-plate', 'png', 'local', 'plate'); ?>
                </div>

                <div class="col-12 col-md-5">
                    <?php img('homepage/tomatoes', 'png', 'local', 'img-fluid tomatoes text-right'); ?>

                    <div id="the-big-problems">
                        <h1>
                            Tackling the<br>
                            <strong class="uppercase">big problems</strong>
                        </h1>
                        
                        <p>
                            Food is our fuel &mdash; the source of our energy as living beings. Yet the energy we spend in food transportation is <strong>10 times</strong> greater than the energy we receive in eating. The average American meal is sourced from <strong>1,500 miles</strong> away. By the time food ends up on our plates it's lost not only flavor but also nutritional quality.
                        </p>

                        <p>
                            This is a resource-intensive, unhealthy, non-transparent food system. It doesn't need to be this way.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="model-cake" class="sidekick container-fluid">
            <div class="row">
                <!-- <div class="col-12 col-md-1">
                </div> -->

                <div class="col-12 col-md-6">
                    <div id="model-for-the-future">
                        <h1>
                            A history-proven model<br>
                            <strong class="uppercase">for the future</strong>
                        </h1>

                        <p>
                            As recently as <strong>1945</strong>, nearly half of the fresh produce in America was grown in home and community gardens. Our production was so abundant that during World War II we shipped food overseas to Europe to aid our allies in their food shortages.
                        </p>

                        <p>
                            Furthermore, in about <strong>30 years</strong> food demand is expected to increase by up to <strong>98%</strong>. Some suggest clear-cutting even more land for greater commercial productivity. We believe the solution (and now the technology) is already here.
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-6 text-center">
                    <?php img('homepage/coffee-cake', 'png', 'local', 'img-fluid'); ?>
                </div>
            </div>
        </div>
    </div>
</main>