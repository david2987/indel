$(document).ready(function () {
    $('#carouselExampleCaptions').carousel({
  interval: 500
})
    $(".bannerProductosNoSeleccionado").hover(function () {
        $(this).css("background-color", "#FE1E1E");  
        $(this).css("color", "#fff");        
            
        }, function () {
            $(this).css("background-color", "#D9D9D9");  
            $(this).css("color", "#6C6A6A");  
        }
    );
});
