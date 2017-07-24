$("#show-all-listings").on('click',function(e){
    e.preventDefault();        
    $(".hide-food-listing").toggle();
    $(".show-less").show(); 
    $(".see-all").hide();       
});

$(".show-less").on('click',function(e){
    e.preventDefault();        
    $(".hide-food-listing").toggle();
    $(".show-less").hide(); 
    $(".see-all").show(); 
});

