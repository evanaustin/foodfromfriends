<logo>
    <?= _img('../media/bighousefarm/logo','png',[
        'server' => 'local'
    ]) ?>
</logo>

<main>
    <div class="main">
        <div id="hero">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <h1 class="text-center">
                            Chickens so happy<br>
                            you can <span>taste</span> it
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="sidekicks">
            <div class="sidekick">
                <div class="container">
                    <div class="row">
                        <div class="col-md-8 col">
                            <div class="match">
                                <p>We are a 5th generation farm that earns trust through transparent farming by producing nutrient-rich, pasture raised, fed organic feed poultry.</p>
                                <p>Our mission is to earn and maintain the trust of our community through transparent farming, which nurtures the soil and grasses to produce nutrient rich food for our family, friends and patrons.</p>
                            </div>
                        </div>

                        <div class="col-md-4 col">

                            <?= _img('../media/bighousefarm/log-grain','png',[
                                'server'    => 'local',
                                'class'     => 'img-fluid match'
                            ]) ?>

                        </div>
                    </div>
                </div>
            </div>

            <div class="sidekick">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col">

                            <?= _img('../media/bighousefarm/chicken','png',[
                                'server'    => 'local',
                                'class'     => 'img-fluid match'
                            ]) ?>

                        </div>

                        <div class="col-md-8 col">
                            <div class="match">
                                <p>Our chickens are never medicated, never fed a GMO grain, and never mistreated. They live on grass with plenty of room to move around and forage for bugs in open air chicken tractors that are moved daily to fresh ground.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>