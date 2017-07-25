<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="container">
                <h4 class="title">Add a new food listing</h4>
                <hr>

                <div class="alert"></div>

                <form id="add-listing" class="food_listing_form" data-parsley-validate>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="food-categories">What kind of food do you have?</label>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <select id="food-categories" name="food-category" class="custom-select form-control" data-parsley-trigger required>
                                    <option selected disabled>Select a food category</option>

                                    <?php foreach($food_categories as $food_category) { ?>
                                        <option value="<?php echo $food_category['id'] ?>"><?php echo ucfirst($food_category['title']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <select id="food-subcategories" name="food-subcategory" class="custom-select form-control" data-parsley-trigger disabled required>
                                    <option selected disabled>Select a food subcategory</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="other-option" class="form-group">
                        <label for="other-subcategory">Can't find the food you're trying to list?</label>
                        <input id="other-subcategory" type="text" name="other" class="form-control" placeholder="Add your food type">
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="price">Listing price</label>
                                <div class="input-group">
                                    <div class="input-group-addon">$</div>
                                    <input id="price" type="number" name="price" class="form-control" placeholder="Enter a price for your food">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="form-group">
                                <label>Upload a listing image</label>
                                
                                <div id="listing-image">
                                    <label class="custom-file">
                                        <input type="file" name="listing-image" class="custom-file-input form-control">
                                        <span class="custom-file-control"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input id="quantity" type="number" name="quantity" class="form-control" placeholder="Enter how many you have in stock">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Availability</label>
                                
                                <div class="radio-box">
                                    <label class="custom-control custom-radio">
                                        <input id="radio1" name="radio" type="radio" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Available</span>
                                    </label>

                                    <label class="custom-control custom-radio">
                                        <input id="radio2" name="radio" type="radio" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Unavailable</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Listing description</label>
                        <textarea type="text" name="description" class="form-control" rows="4" placeholder="Write a description of your homegrown food"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        Create listing
                    </button>
                </form>
            </div> <!-- end main -->
        </div> <!-- end div.row -->
    </div> <!-- end div.container-fluid -->

    <script>
        var food_subcategories = <?php echo json_encode($food_subcategories); ?>;
    </script>