(function($){
    
    $.entwine('ss', function($){
        
        // Define Tree Node Functions:
        
        $('.cms-tree ul li.jstree-leaf').entwine({
            
            updateBadge: function(num, text) {
                
                var span = this.find('span.status-number-badge');
                
                if (span.length > 0) {
                    
                    var selector = '#' + this.attr('id') + ' a .jstree-pageicon::before';
                    
                    $('head').append(
                        '<style type="text/css">' + selector + ' { content: "' + (num > 0 ? num : '') + '"; }</style>'
                    );
                    
                    if (text !== undefined) {
                        
                        if (num > 0) {
                            
                            var label = ss.i18n.inject(text, {'num': num});
                            span.attr('title', label).html(label);
                            
                        } else {
                            
                            this.removeClass('status-number-badge');
                            span.remove();
                            
                        }
                        
                    }
                    
                }
                
            }
            
        });
        
        // Update from Tree Node Span:
        
        $('span.status-number-badge-val').entwine({
            
            onmatch: function() {
                
                this._super();
                
                var num = parseInt(this.attr('title'));
                
                if (!this.attr('data-updated')) {
                    this.closest('li').updateBadge(num);
                    this.attr('data-updated', true);
                }
                
            }
            
        });
        
        // Update from CMS Field:
        
        $('input.update-number-badge').entwine({
            
            onmatch: function() {
                
                this._super();
                
                var self = this;
                
                // Obtain Tree ID and Count:
                
                var id  = self.data('tree-id');
                var num = parseInt(self.val());
                
                // Update Tree Node:
                
                $(id).entwine({
                    
                    onmatch: function() {
                        
                        this._super();
                        
                        this.updateBadge(num, self.data('text'));
                        
                    }
                    
                });
                
            }
            
        });
        
        // Improve Display Logic Checkbox Field Appearance:
        
        $('.ss-toggle .ui-accordion-content > .field.checkbox.display-logic-master:visible:last').entwine({
            
            onmatch: function() {
                this.addClass('last-visible-field');
            }
            
        });
        
    });
    
}(jQuery));