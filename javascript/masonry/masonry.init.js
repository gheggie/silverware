$(function(){
    
    // Initialise Masonry Plugin:
    
    var $grid = $('#$WrapperID').imagesLoaded(function(){
        
        $grid.masonry({
            columnWidth: '.masonry-grid-sizer',
            itemSelector: '.masonry-grid-item',
            percentPosition: true,
            gutter: $Gutter
        });
        
    });
    
});