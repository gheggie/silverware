$(function(){
    
    // Initialise Tabs:
    
    $('#$WrapperID').responsiveTabs({
        active: $FirstActiveTabIndex,
        disabled: $InactiveTabs,
        rotate: $RotateType,
        setHash: $ReferenceTabInURL,
        animation: $AnimationMethod,
        animationQueue: true,
        duration: $AnimationDuration,
        collapsible: $CollapsibleType,
        startCollapsed: $StartCollapsedType,
        scrollToAccordion: $ScrollToAccordion
    });
    
});