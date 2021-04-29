$(function(){
    
    var offset_show = $OffsetShow;
    var offset_opacity = $OffsetOpacity;
    var scroll_top_duration = $ScrollDuration;
    
    $scroll_to_top = $('$CSSID > a');
    
    // Hide or Show Button:
    
    $(window).scroll(function(){
        
        if ($(this).scrollTop() > offset_show) {
            $scroll_to_top.addClass('is-visible');
        } else {
            $scroll_to_top.removeClass('is-visible fade-out');
        }
        
        if ($(this).scrollTop() > offset_opacity) {
            $scroll_to_top.addClass('fade-out');
        }
        
    });
    
    // Animate Scroll to Top:
    
    $scroll_to_top.on('click', function(event){
        event.preventDefault();
        $('body, html').animate({ scrollTop: 0 }, scroll_top_duration);
    });
    
});