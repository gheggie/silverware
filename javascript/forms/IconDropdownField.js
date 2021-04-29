(function($){
    
    $.entwine('ss', function($){
        
        var initChosen = function(el) {
            
            if (el.is(':visible')) {
                el.addClass('has-chzn').chosen({
                    allow_single_deselect: true,
                    disable_search: true
                });
            } else {
                setTimeout(function() {
                    el.show();
                    initChosen(el);
                }, 500);
            }
            
        };
        
        $('.cms select.icondropdown').entwine({
            
            onmatch: function() {
                
                // Remove Placeholder (if undefined):
                
                if (!this.data('placeholder')) {
                    this.data('placeholder', ' ');
                }
                
                // Clean Up Stale Classes:
                
                this.removeClass('has-chzn chzn-done');
                this.siblings('.chzn-container').remove();
                
                // Prepend Icon Tag to Options:
                
                $(this).children('option').each(function() {
                    if ($(this).val()) {
                        $(this).prepend('<i class="fa fa-fw ' + $(this).val() + '"></i> ');
                    }
                });
                
                // Initialise Chosen Dropdown:
                
                initChosen(this);
                
                // Handle Inheritance:
                
                this._super();
                
            },
            
            onunmatch: function() {
                
                // Handle Inheritance:
                
                this._super();
                
            }
            
        });
        
    });
    
}(jQuery));