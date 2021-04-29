<?php

/**
 * An extension of the extension class to add additional link types to the HTML editor field link form.
 */
class SilverWareLinkFormExtension extends Extension
{
    /**
     * @config
     * @var string
     */
    private static $link_type_value;
    
    /**
     * @config
     * @var string|array
     */
    private static $link_type_label;
    
    /**
     * @config
     * @var string|array
     */
    private static $link_field_label;
    
    /**
     * @config
     * @var string
     */
    private static $link_href;
    
    /**
     * @config
     * @var string
     */
    private static $link_regex;
    
    /**
     * @config
     * @var string
     */
    private static $link_shortcode;
    
    /**
     * Updates the given link form belonging to the extended object.
     *
     * @param Form $form
     */
    public function updateLinkForm(Form $form)
    {
        // Update Link Type Source:
        
        $this->addLinkTypeOption(
            $form,
            $this->getLinkTypeValue(),
            $this->getLinkTypeLabel()
        );
        
        // Update Link Form Fields:
        
        if ($field = $this->getLinkField($form)) {
            $this->addLinkField($form, $field);
        }
    }
    
    /**
     * Adds an option to the source array of the link type field from the given link form.
     *
     * @param Form $form
     * @param string $value
     * @param string $title
     */
    protected function addLinkTypeOption(Form $form, $value, $title)
    {
        // Obtain Link Type Optionset Field:
        
        if ($LinkType = $form->Fields()->dataFieldByName('LinkType')) {
            
            // Add Link Type Option to Source:
            
            $source = $LinkType->getSourceAsArray();
            
            $source[$value] = $title;
            
            $LinkType->setSource($source);
            
        }
    }
    
    /**
     * Adds the given link field to the provided link form.
     *
     * @param Form $form
     * @param FormField $field
     */
    protected function addLinkField(Form $form, FormField $field)
    {
        // Obtain Insert Link Wrapper:
        
        $linkWrapper = $form->Fields()->filterByCallback(function ($item) {
            return (strpos($item->extraClass(), 'ss-insert-link') !== false);
        })->first();
        
        // Insert Link Field:
        
        if ($linkWrapper) {
            $linkWrapper->insertBefore('Description', $field);
        }
    }
    
    /**
     * Answers the field for choosing the item to link to (defaults as dropdown field).
     *
     * @param Form $form
     * @return FormField
     */
    protected function getLinkField(Form $form)
    {
        // Create Field Object:
        
        $field = DropdownField::create(
            $this->getLinkTypeValue(),
            $this->getLinkFieldLabel(),
            $this->getLinkFieldSource()
        )->setForm($form)->addExtraClass('link-field')->setEmptyString($this->getLinkFieldEmptyString());
        
        // Define Field Object:
        
        $field->setAttribute('data-link-href',  $this->getLinkHref());
        $field->setAttribute('data-link-regex', $this->getLinkRegex());
        
        // Answer Field Object:
        
        return $field;
    }
    
    /**
     * Answers the href to use for the link (usually a shortcode).
     *
     * @return string
     */
    protected function getLinkHref()
    {
        $href = Config::inst()->get($this->class, 'link_href');
        
        if (!$href && $shortcode = $this->getLinkShortcode()) {
            return sprintf('[%s,id={value}]', $shortcode);
        }
        
        return $href;
    }
    
    /**
     * Answers the regex to use for matching the link.
     *
     * @return string
     */
    protected function getLinkRegex()
    {
        $regex = Config::inst()->get($this->class, 'link_regex');
        
        if (!$regex && $shortcode = $this->getLinkShortcode()) {
            return sprintf('^\[%s(?:\s*|%%20|,)?id=([0-9\-]+)\]?(#.*)?$', $shortcode);
        }
        
        return $regex;
    }
    
    /**
     * Answers the shortcode to use for the link.
     *
     * @return string
     */
    public function getLinkShortcode()
    {
        return Config::inst()->get($this->class, 'link_shortcode');
    }
    
    /**
     * Answers the label for the link type option.
     *
     * @return string
     */
    protected function getLinkTypeLabel()
    {
        if ($label = Config::inst()->get($this->class, 'link_type_label')) {
            
            if (is_array($label)) {
                return _t($label[0], $label[1]);
            }
            
            return $label;
            
        }
    }
    
    /**
     * Answers the value for the link type option.
     *
     * @return string
     */
    protected function getLinkTypeValue()
    {
        return Config::inst()->get($this->class, 'link_type_value');
    }
    
    /**
     * Answers the label for the link field.
     *
     * @return string
     */
    protected function getLinkFieldLabel()
    {
        if ($label = Config::inst()->get($this->class, 'link_field_label')) {
            
            if (is_array($label)) {
                return _t($label[0], $label[1]);
            }
            
            return $label;
            
        }
        
        return $this->getLinkTypeLabel();
    }
    
    /**
     * Answers the source for the default link field.
     *
     * @return array|ArrayAccess
     */
    protected function getLinkFieldSource()
    {
        return array();
    }
    
    /**
     * Answers the empty string for the default link field.
     *
     * @return string
     */
    protected function getLinkFieldEmptyString()
    {
        return _t('SilverWareLinkFormExtension.EMPTYSTRING', '(Choose)');
    }
}
