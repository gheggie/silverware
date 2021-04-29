<?php

/**
 * An extension of the base rule class for a heading rule.
 */
class HeadingRule extends BaseRule
{
    private static $singular_name = "Heading Rule";
    private static $plural_name   = "Heading Rules";
    
    private static $selector = "div.content";
    
    private static $db = array(
        'AllTags' => 'Boolean',
        'ApplyToTags' => 'Varchar(255)'
    );
    
    private static $defaults = array(
        'AllTags' => 1
    );
    
    private static $heading_tags = array(
        'h1' => 'H1',
        'h2' => 'H2',
        'h3' => 'H3',
        'h4' => 'H4',
        'h5' => 'H5',
        'h6' => 'H6'
    );
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Create Field Objects:
        
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                FieldGroup::create(
                    _t('HeadingRule.APPLYTOTAGS', 'Apply to tags'),
                    array(
                        CheckboxField::create(
                            'AllTags',
                            _t('HeadingRule.ALLTAGS', 'All tags')
                        )
                    )
                ),
                DisplayLogicWrapper::create(
                    CheckboxSetField::create(
                        'ApplyToTags',
                        _t('HeadingRule.SELECTTAGS', 'Select tags'),
                        $this->config()->heading_tags
                    )
                )->displayIf('AllTags')->isNotChecked()->end()
            ),
            'Disabled'
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Defines the apply to tags property of the receiver.
     *
     * @param string $tags
     * @return HeadingRule
     */
    public function setApplyToTags($tags)
    {
        if ($tags) {
            
            if (Controller::curr()->getRequest()->postVar('AllTags')) {  // this is kinda hacky :\
                $this->setField('ApplyToTags', null);
            } else {
                $this->setField('AllTags', 0);
                $this->setField('ApplyToTags', $tags);
            }
        }
        
        return $this;
    }
    
    /**
     * Answers the CSS selector for the receiver.
     *
     * @return array
     */
    public function getSelector()
    {
        $selector = array();
        
        if ($this->AllTags) {
            $apply_to = array_keys($this->config()->heading_tags);
        } else {
            $apply_to = explode(',', $this->ApplyToTags);
        }
        
        if (!empty($apply_to)) {
            
            foreach ($apply_to as $tag) {
                $selector[] = $this->config()->selector . ' ' . $tag;
            }
            
        }
        
        return $selector;
    }
    
    /**
     * Answers a description for the receiver.
     *
     * @return string
     */
    public function getDescription()
    {
        if ($this->AllTags) {
            $description = _t('HeadingRule.ALLTAGS', 'All tags');
        } else {
            $description = str_replace(',', ', ', strtoupper($this->ApplyToTags));
        }
        
        return $description;
    }
    
    /**
     * Answers true if the rule is enabled.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        if (!$this->AllTags && !$this->ApplyToTags) {
            return false;
        }
        
        return parent::isEnabled();
    }
}
