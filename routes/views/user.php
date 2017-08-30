<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
</head>
  <pre>
  <?php 
  print_r($ProfileUser_info);?>
  </pre>
<div class="container">
    <div class="row">   
        <div class="col-lg-3">  
            <div class="row">   
                <div class="col-lg-12">    
                    <div class="profile-picture">
                        <img class="profile-picture" src="https://images.unsplash.com/photo-1496239943629-500dbbf945b1?dpr=1.25&amp;auto=format&amp;fit=crop&amp;w=1500&amp;h=1000&amp;q=80&amp;cs=tinysrgb&amp;crop=">
                    </div> 
                </div> 
            </div>
            <div class="map-display">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="profile-left-content">
                            <h6>
                                Find my food
                            </h6>
                            <iframe class="map"src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d81996.50392261393!2d-78.95624680054243!3d38.43683317585813!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89b492c33f077155%3A0x84e65b9dabd7b5f!2sHarrisonburg%2C+VA!5e0!3m2!1sen!2sus!4v1500919962877"  allowfullscreen></iframe>
                        </div>
                    </div>        
                </div>
            </div>    
            <div class="delivery">       
                <div class="row">   
                    <div class="col-lg-12">    
                        <div class="profile-left-content">
                            <h6>
                                My delivery offerings <!--Placeholder-->
                            </h6>
                            <!--<?php
                          //  if ($delivery_details['is_offered']==1){ }
                            ?>-->
                            <p> <?php echo (isset($delivery_details['fee']) ? $delivery_details['fee']  : '');  ?> </p> 
                            <p> <strong>Delivery radius: </strong> <?php echo (isset($delivery_details['distance']) ? $delivery_details['distance'] : '');  ?> miles </p>
                            <p> <strong>Delivery time:</strong> 24 hours</p>
                            <p> <strong>Request approval: </strong> Automatic</p>
                        </div>    
                    </div> 
                </div>
            </div>
        </div> <!--close col-lg-3 -->
       
        <div class="col-lg-9">      
            <div class="profile-name">
                <h2>
                    Food From <?php echo $User->first_name; ?>
                </h2>
            </div>
            <div class="profile-location">
                <h6>
                    <?php echo $User->city; ?>, <?php  echo $User->state;?> - Joined July 20 2017
                </h6>
            </div>     
            <div class="profile-review-count">
                <h6>
                    Reviews <?php echo"(". count($reviews).")";?>
                </h6>
            </div>
            <div class="profile-verified-info">
                <div class="row">
                    <div class="col-lg-2">
                        <p> Verified info:</p>
                    </div>
                    <div class="col-lg-2">
                        <p>  <i class="fa fa-home"></i> Address </p>
                    </div>
                    <div class="col-lg-2">
                        <p>  <i class="fa fa-envelope-o"></i> Email </p>
                    </div>
                    <div class="col-lg-2">
                            <p>  <i class="fa fa-volume-control-phone"></i> Phone </p>
                    </div>
                </div>
            </div>
            <div class="profile-bio">
                <p>
                    <?php echo  $User->bio; ?>
                </p>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="profile-foodlisting-title">
                        <h3>
                            Available Food Listings 
                        </h3>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="profile-foodlisting-title-count">
                        <h5>
                             <?php echo"(".count($available_foodlistings).")";?>
                        </h5>
                    </div>
                </div>
            </div>
            <div class='row'>
            <?php 
                $i = 1;
                foreach ($foodlistings as $foodlisting){ 
                    if($foodlisting[is_available]==1 && $i < 4 ){    
             ?>
            <div class='col-lg-4'>
                <div class='food-listing'>
                    <p>
                        <?php echo "$". $foodlisting[price] ." "."ea."; ?>
                    <p>
                    <div class='food-listing-banner'>
                        <h4>
                            <?php echo ucfirst($foodlisting[title]); ?>
                        <h4>
                    </div>
                </div>
            </div>
            <?php
             $i++;
             }elseif($foodlisting[is_available]==0){
             ?>
            <div class='col-lg-4'>
                    <div class='food-listing hide-food-listing'>
                        <p>
                            <?php echo "$". $foodlisting[price] ." "."ea."; ?>
                        <p>
                        <div class='food-listing-banner'>
                            <h4>
                                <?php echo ucfirst($foodlisting[title]); ?>
                            <h4>
                        </div>
                    </div>
                </div>      
            <?php 
            }};
            ?>
            </div> <!--close row from for each loop-->
            
            <p id="show-all-listings" class = "see-all"> See all </p>
            <p class = "show-less"> Show less </p>
      
            <div class="row">
                <div class="col-lg-2">
                    <div class="profile-review-title">
                        <h3>
                            Reviews
                        </h3>
                    </div>
                </div>

                <div class="col-lg-10">
                    <div class="profile-review-title-count">
                        <h5>
                            <?php echo"(". count($reviews).")";?>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="row">  
                <?php 
                $i = 1;
                foreach ($reviews as $review){ 
                ?>            
                <div class="col-lg-2">
                    <div class="profile-review">                    
                        <div class="reviewer-picture"> </div>
                        <p class="reviewer-name">
                            <?php echo $review[first_name];?> 
                        <p>
                    </div>
                </div> 

                <div class="col-lg-10">
                    <div class="profile-review">
                        <p>
                           <?php echo $review[content];?> 
                        </p> 
                        <p class="reviewer_info">  
                           <?php echo $review[city] . ", ". $review[state];?>  - July 20 2017 
                        </p>
                    </div>
                </div>    
                <?php
                $i++;
                }
                ?>
            </div>
        </div>  <!--close out col-lg-9  -->
    </div>  <!--close out row  -->
<div> <!--close out container -->
