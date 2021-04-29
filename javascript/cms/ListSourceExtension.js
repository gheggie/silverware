(function($){
    
    $.entwine('ss', function($){
        
        $('form select.sorting-toggle').entwine({
            
            onmatch: function() {
                
                this.showFields();
                
                this._super();
                
            },
            
            onchange: function() {
                
                this.hideFields();
                this.showFields();
                
                this._super();
                
            },
            
            hideFields: function() {
                
                $('form .field.sorting').slideUp();
                
            },
            
            showFields: function() {
                
                if ($(this).val() == 'Custom') {
                    
                    $('form .field.sorting input').show();
                    $('form .field.sorting').slideDown();
                    
                }
                
            }
            
        });
        
    });
    
}(jQuery));