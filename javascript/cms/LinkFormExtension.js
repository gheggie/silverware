(function($){
    
    $.entwine('ss', function($){
        
        // Match link form:
        
        $('form.htmleditorfield-linkform').entwine({
            
            // Handles the insertion of a link:
            
            getLinkAttributes: function() {
                
                // Determine Link Type:
                
                var linkType = this.find(':input[name=LinkType]:checked').val();
                
                // Obtain Link Field:
                
                var linkField = this.find(':input[name=' + linkType + ']');
                
                // Answer Link Attributes:
                
                if (linkField.hasClass('link-field')) {
                    return linkField.getLinkAttributes();
                }
                
                return this._super();
                
            },
            
            // Handles the selection of a contact link:
            
            getCurrentLink: function() {
                
                // Initialise:
                
                var el = this.getSelection();
                
                // Obtain Link Data Source:
                
                var linkDataSource = null;
                
                if (el.length) {
                    linkDataSource = el.is('a') ? el : el.parents('a:first');
                }
                
                // Modify Editor Selection:
                
                if (linkDataSource && linkDataSource.length) {
                    
                    this.modifySelection(function(ed) {
                        ed.selectNode(linkDataSource[0]);
                    });
                    
                }
                
                // Check Link Data Source for HREF:
                
                if (!linkDataSource.attr('href')) {
                    linkDataSource = null;
                }
                
                // Handle Link Data Source:
                
                if (linkDataSource) {
                    
                    // Clean HREF:
                
                    var href = this.getEditor().cleanLink(linkDataSource.attr('href'), linkDataSource);
                    
                    // Obtain Link Field:
                    
                    var linkField = this.getLinkField(href);
                    
                    if (linkField) {
                        return linkField.getCurrentLink(href, linkDataSource);
                    }
                    
                }
                
                return this._super();
                
            },
            
            // Answer a link field which matches the given HREF:
            
            getLinkField: function(href) {
                
                // Initialise:
                
                var linkField;
                
                // Locate Link Field:
                
                this.find(':input.link-field').each(function(){
                    
                    if ($(this).matches(href)) {
                        linkField = $(this);
                        return false;
                    }
                    
                });
                
                // Answer Link Field:
                
                return linkField;
                
            }
            
        });
        
        // Match link fields:
        
        $('form.htmleditorfield-linkform :input.link-field').entwine({
            
            // Answer form object:
            
            getForm: function() {
                return this.closest('form.htmleditorfield-linkform');
            },
            
            // Answer regular expression for matching link:
            
            getRegExp: function() {
                return new RegExp(this.data('link-regex'), 'i');
            },
            
            // Answer regex matches for given HREF:
            
            parseHref: function(href) {
                return href.match($(this).getRegExp());
            },
            
            // Answers true if the regex matches the given HREF:
            
            matches: function(href) {
                return $.isArray(this.parseHref(href));
            },
            
            // Answer link attributes (for inserting a link):
            
            getLinkAttributes: function() {
                
                // Initialise:
                
                var href, target = null;
                
                // Obtain Link Title:
                
                var title = this.getForm().find(':input[name=Description]').val();
                
                // Obtain Link Target:
                
                if (this.getForm().find(':input[name=TargetBlank]').is(':checked')) {
                    target = "_blank";
                }
                
                // Define Link HREF:
                
                href = this.data('link-href').replace('{value}', this.val());
                
                // Answer Link Details:
                
                return {
                    href: href,
                    target: target,
                    title: title
                };
                
            },
            
            // Answer current link for given HREF and link data source element (for editing selected link):
            
            getCurrentLink: function(href, linkDataSource) {
                
                // Define Variables:
                
                var target = linkDataSource.attr('target');
                var title  = linkDataSource.attr('title');
                
                if (this.matches(href)) {
                    
                    // Define Name:
                    
                    var name = this.attr('name');
                    
                    // Define Value:
                    
                    var value = this.parseHref(href)[1];
                    
                    // Update Field:
                    
                    this.val(value);
                    this.trigger('liszt:updated');
                    
                    // Define Link Details:
                    
                    var link = {
                        LinkType: name,
                        Description: title,
                        TargetBlank: target ? true : false
                    };
                    
                    // Define Link Value:
                    
                    link[name] = value;
                    
                    // Answer Link Details:
                    
                    return link;
                    
                }
                
            }
            
        });
        
    });
    
})(jQuery);
