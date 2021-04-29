<?php

/**
 * An extension of the base component class for a copyright component.
 */
class CopyrightComponent extends BaseComponent
{
    private static $singular_name = "Copyright Component";
    private static $plural_name   = "Copyright Components";
    
    private static $description = "A component to show a copyright message";
    
    private static $icon = "silverware/images/icons/components/CopyrightComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $db = array(
        'YearStart' => 'Varchar(8)',
        'YearEnd' => 'Varchar(8)',
        'EntityName' => 'Varchar(255)',
        'CopyrightNoun' => 'Varchar(128)',
        'CopyrightText' => 'Varchar(255)',
        'EntityURL' => 'Varchar(2048)',
        'CopyrightURL' => 'Varchar(2048)',
        'EntityLinkDisabled' => 'Boolean',
        'CopyrightLinkDisabled' => 'Boolean',
        'OpenLinksInNewTab' => 'Boolean'
    );
    
    private static $has_one = array(
        'EntityPage' => 'SiteTree',
        'CopyrightPage' => 'SiteTree'
    );
    
    private static $defaults = array(
        'HideTitle' => 1,
        'EntityLinkDisabled' => 0,
        'CopyrightLinkDisabled' => 0,
        'StyleAlignmentWide' => 'Left',
        'StyleAlignmentNarrow' => 'Center',
        'OpenLinksInNewTab' => 0
    );
    
    private static $extensions = array(
        'StyleAlignmentExtension'
    );
    
    private static $required_themed_css = array(
        'copyright-component'
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
        
        // Create Main Fields:
        
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                TextField::create(
                    'EntityName',
                    _t('CopyrightComponent.ENTITYNAME', 'Entity name')
                ),
                FieldGroup::create(
                    _t('CopyrightComponent.YEARS', 'Years'),
                    array(
                        TextField::create(
                            'YearStart',
                            ''
                        )->setAttribute('placeholder', _t('CopyrightComponent.START', 'Start')),
                        LiteralField::create(
                            'YearTo',
                            '<i class="fa fa-minus to"></i>'
                        ),
                        TextField::create(
                            'YearEnd',
                            ''
                        )->setAttribute('placeholder', _t('CopyrightComponent.END', 'End'))
                    )
                ),
                TreeDropdownField::create(
                    'EntityPageID',
                    _t('CopyrightComponent.ENTITYPAGE', 'Entity Page'),
                    'SiteTree'
                ),
                TextField::create(
                    'EntityURL',
                    _t('CopyrightComponent.ENTITYURL', 'Entity URL')
                ),
                TreeDropdownField::create(
                    'CopyrightPageID',
                    _t('CopyrightComponent.COPYRIGHTPAGE', 'Copyright Page'),
                    'SiteTree'
                ),
                TextField::create(
                    'CopyrightURL',
                    _t('CopyrightComponent.COPYRIGHTURL', 'Copyright URL')
                ),
                CheckboxField::create(
                    'OpenLinksInNewTab',
                    _t('CopyrightComponent.OPENLINKSINNEWTAB', 'Open links in new tab')
                )
            )
        );
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'CopyrightComponentOptions',
                $this->i18n_singular_name(),
                array(
                    TextField::create(
                        'CopyrightNoun',
                        _t('CopyrightComponent.COPYRIGHTNOUN', 'Copyright noun')
                    ),
                    TextField::create(
                        'CopyrightText',
                        _t('CopyrightComponent.COPYRIGHTTEXT', 'Copyright text')
                    ),
                    CheckboxField::create(
                        'EntityLinkDisabled',
                        _t('CopyrightComponent.ENTITYLINKDISABLED', 'Entity link disabled')
                    ),
                    CheckboxField::create(
                        'CopyrightLinkDisabled',
                        _t('CopyrightComponent.COPYRIGHTLINKDISABLED', 'Copyright link disabled')
                    )
                )
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers a validator for the CMS interface.
     *
     * @return RequiredFields
     */
    public function getCMSValidator()
    {
        return RequiredFields::create(
            array(
                'EntityName',
                'CopyrightNoun',
                'CopyrightText'
            )
        );
    }
    
    /**
     * Populates the default values for the attributes of the receiver.
     */
    public function populateDefaults()
    {
        // Populate Defaults (from parent):
        
        parent::populateDefaults();
        
        // Populate Defaults:
        
        $this->CopyrightNoun = _t(
            'CopyrightComponent.DEFAULTCOPYRIGHTNOUN',
            'Copyright'
        );
        
        $this->CopyrightText = _t(
            'CopyrightComponent.DEFAULTCOPYRIGHTTEXT',
            '{copyright} &copy; {year} {entity}. All Rights Reserved.'
        );
    }
    
    /**
     * Answers a string of class names for the wrapper element.
     *
     * @return string
     */
    public function getWrapperClass()
    {
        return implode(' ', $this->getWrapperClassNames());
    }
    
    /**
     * Answers an array of class names for the wrapper element.
     *
     * @return array
     */
    public function getWrapperClassNames()
    {
        $classes = array('copyright');
        
        return $classes;
    }
    
    /**
     * Answers the appropriate link for the entity page.
     *
     * @return string
     */
    public function getEntityLink()
    {
        if ($this->EntityURL) {
            return $this->dbObject('EntityURL')->URL();
        }
        
        if ($this->EntityPageID) {
            return $this->EntityPage()->Link();
        }
    }
    
    /**
     * Answers the appropriate link for the copyright page.
     *
     * @return string
     */
    public function getCopyrightLink()
    {
        if ($this->CopyrightURL) {
            return $this->dbObject('CopyrightURL')->URL();
        }
        
        if ($this->CopyrightPageID) {
            return $this->CopyrightPage()->Link();
        }
    }
    
    /**
     * Answers true if the receiver has an entity link.
     *
     * @return boolean
     */
    public function HasEntityLink()
    {
        return (boolean) $this->getEntityLink();
    }
    
    /**
     * Answers true if the receiver has a copyright link.
     *
     * @return boolean
     */
    public function HasCopyrightLink()
    {
        return (boolean) $this->getCopyrightLink();
    }
    
    /**
     * Answers the copyright text for the template.
     *
     * @return string
     */
    public function Copyright()
    {
        // Create Tokens Array:
        
        $tokens = array();
        
        // Define Link Target:
        
        $target = $this->OpenLinksInNewTab ? " target=\"_blank\"" : "";
        
        // Define Copyright Token:
        
        $tokens['copyright'] = $this->CopyrightNoun;
        
        if ($this->HasCopyrightLink() && !$this->CopyrightLinkDisabled) {
            
            $tokens['copyright'] = sprintf(
                '<a href="%s" rel="nofollow"%s>%s</a>',
                $this->CopyrightLink,
                $target,
                $this->CopyrightNoun
            );
            
        }
        
        // Define Entity Token:
        
        $tokens['entity'] = $this->EntityName;
        
        if ($this->HasEntityLink() && !$this->EntityLinkDisabled) {
            
            $tokens['entity'] = sprintf(
                '<a href="%s" rel="nofollow"%s>%s</a>',
                $this->EntityLink,
                $target,
                $this->EntityName
            );
            
        }
        
        // Define Year Token:
        
        $tokens['year'] = date('Y');
        
        if ($this->YearStart && $this->YearEnd) {
            
            $tokens['year'] = $this->YearStart . "-" . $this->YearEnd;
            
        } elseif ($this->YearStart && !$this->YearEnd) {
            
            $tokens['year'] = $this->YearStart . "-" . date('Y');
            
        } elseif (!$this->YearStart && $this->YearEnd) {
            
            $tokens['year'] = $this->YearEnd;
            
        }
        
        // Replace Tokens in Copyright Text:
        
        $text = $this->CopyrightText;
        
        foreach ($tokens as $name => $value) {
            $text = str_replace("{{$name}}", $value, $text);
        }
        
        // Answer Copyright Text:
        
        return $text;
    }
}

/**
 * An extension of the base component controller class for a copyright component.
 */
class CopyrightComponent_Controller extends BaseComponent_Controller
{
    /**
     * Defines the URLs handled by this controller.
     */
    private static $url_handlers = array(
        
    );
    
    /**
     * Defines the allowed actions for this controller.
     */
    private static $allowed_actions = array(
        
    );
    
    /**
     * Performs initialisation before any action is called on the receiver.
     */
    public function init()
    {
        // Initialise Parent:
        
        parent::init();
    }
}
