<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class="container">
                <h3> Grower Dashboard</h3><br>
                <!--<form id="add-listing" class= "food_listing_form" action="upload.php" method="post" enctype="multipart/form-data">-->
                <form id="add-listing" class= "food_listing_form">
                    <h6>What do you have?</h6>
                    <?php echo "<select id='food_category' name= 'food_category'>";
                        foreach ($categories as $category){           

                            echo "<option value=$category[id]>$category[title]</option>"; 
                        }
                        echo"</select>";
                    ?> <br><br>

                    <?php echo "<select id='veg' class='food_subcategory' name= 'food_subcategory_id'>";
                        foreach ($subcategories_veg as $subcategory_veg){           

                            echo "<option value=$subcategory_veg[id]>$subcategory_veg[title]</option>"; 
                        }
                        echo"</select>";
                    ?> 
                    <?php echo "<select id='fruit' class='food_subcategory' name= 'food_subcategory_id'>";
                        foreach ($subcategories_fruit as $subcategory_fruit){           

                            echo "<option value=$subcategory_fruit[id]>$subcategory_fruit[title]</option>"; 
                        }
                        echo"</select>";
                    ?> 
                    <?php echo "<select id='egg' class='food_subcategory' name= 'food_subcategory_id'>";
                        foreach ($subcategories_egg as $subcategory_egg){           

                            echo "<option value=$subcategory_egg[id]>$subcategory_egg[title]</option>"; 
                        }
                        echo"</select>";
                    ?>    
                    <?php echo "<select id='dairy' class='food_subcategory' name= 'food_subcategory_id'>";
                        foreach ($subcategories_dairy as $subcategory_dairy){           

                            echo "<option value=$subcategory_dairy[id]>$subcategory_dairy[title]</option>"; 
                        }
                        echo"</select>";
                    ?> 
                    <?php echo "<select id='meat' class='food_subcategory' name= 'food_subcategory_id'>";
                        foreach ($subcategories_meat as $subcategory_meat){           

                            echo "<option value=$subcategory_meat[id]>$subcategory_meat[title]</option>"; 
                        }
                        echo"</select>";
                    ?> 
                    <?php echo "<select id='sea' class='food_subcategory' name= 'food_subcategory_id'>";
                        foreach ($subcategories_sea as $subcategory_sea){           

                            echo "<option value=$subcategory_sea[id]>$subcategory_sea[title]</option>"; 
                        }
                        echo"</select>";
                    ?> 
                    <?php echo "<select id='bev' class='food_subcategory' name= 'food_subcategory_id'>";
                        foreach ($subcategories_bev as $subcategory_bev){           

                            echo "<option value=$subcategory_bev[id]>$subcategory_bev[title]</option>"; 
                        }
                        echo"</select>";
                    ?> 
                    <?php echo "<select id='herb' class='food_subcategory' name= 'food_subcategory_id'>";
                        foreach ($subcategories_herb as $subcategory_herb){           

                            echo "<option value=$subcategory_herb[id]>$subcategory_herb[title]</option>"; 
                        }
                        echo"</select>";
                    ?> 
                    <br><br>
                    <!--Add New Subcategory-->
                    <a id="add_new_subcategory" href="#">Can't find the food you are trying to list?</a><br> 
                    <input id='new_subcategory' type='text' name='new_subcategory' placeholder='Add your food.'/><br>
                    <!--Upload File-->
                    <h6> Select image to upload:</h6> <br>
                    <input type="file" name="food_listing_image" id="fileToUpload"><br><br>
                    <!--Set Price-->
                    <h6>How much are you selling them for?</h6>
                    $ <input type="number" name="price" min="0.01" step="0.01" max="100" value="1.50" /><br><br><br>
                    <!--Add Description-->
                    <textarea type="text" class="form-control" name="description" placeholder =" Add a description">Add a description of your home grown food!</textarea><br>
                    <!--Set Stock-->
                    <h6>How many do you have?</h6>
                    <input type="number" name="stock" min="1" step="1" max="10000" value="1" /> <br><br>
                    <!--Is Avaible?-->
                    <h6>Is this listing avaible for neighbors to request?</h6>
                    <input id="available" type="radio" name="is_available" value="0" checked /> Oh yea! <br>
                    <input id="unavailable" type="radio" name="is_available" value="1" /> Nope.<br>
                    <!--Submit-->
                    <button type="submit" class="btn form-control">
                        <i class="fa fa-shopping-basket"></i> 
                        Submit
                    </button>
                    <p class="listing-message"></p>
                </form>  
            </div> <!-- end main -->
        </div> <!-- end div.row -->
    </div> <!-- end div.container-fluid -->