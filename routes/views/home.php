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
    </div>
</main>