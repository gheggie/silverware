$(function(){
    
    // Handle Sticky Scroll:
    
    $(window).scroll(function() {
        if ($(this).scrollTop() > $StickyScrollDistance) {
            $('$CSSID').addClass('sticky');
        } else {
            $('$CSSID').removeClass('sticky');
        }
    });
    
});