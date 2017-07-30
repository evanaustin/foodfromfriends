<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="container">
                <h4 class="title">Your food listings</h4>

                <hr>

                <div class="row">
                <?php foreach($listings as $listing) { ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img class="card-img-top" src="" alt="Card image cap">

                            <div class="card-block">
                                <h4 class="card-title"><?php echo ucfirst((empty($listing['other_subcategory']) ? ($listing['subcategory_title']) : $listing['other_subcategory'])); ?></h4>
                                <p class="card-text"><?php echo $listing['description']; ?></p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
        <!-- cont main -->
    <!-- cont div.row -->
<!-- cont div.container-fluid -->