<?php

/**
 * An extension of the base component class for a list component.
 */
class ListComponent extends BaseComponent
{
    private static $singular_name = "List Component";
    private static $plural_name   = "List Components";
    
    private static $description = "Shows a list of items";
    
    private static $icon = "silverware/images/icons/components/ListComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $default_image_width = 200;
    
    private static $db = array(
        'LinkImages' => 'Boolean',
        'LinkTitles' => 'Boolean',
        'DateFormat' => 'Varchar(128)',
        'ButtonLabel' => 'Varchar(128)',
        'ShowMeta' => "Enum('None, First, All', 'All')",
        'ShowImage' => "Enum('None, First, All', 'All')",
        'ShowSummary' => "Enum('None, First, All', 'All')",
        'ShowContent' => "Enum('None, First, All', 'None')",
        'HeadingTag' => "Enum('h1, h2, h3, h4, h5, h6', 'h4')",
        'ImageLinksTo' => "Enum('File, Item', 'Item')",
        'TitleLinksTo' => "Enum('File, Item', 'Item')",
        'ImageAlignment' => "Enum('None, Left, Right, Stagger', 'None')",
        'ImageWidth' => 'Varchar(16)',
        'ImageHeight' => 'Varchar(16)',
        'ImageResize' => 'Varchar(32)',
        'ShowDateIcon' => 'Boolean',
        'ShowButtons' => 'Boolean',
        'ShowTitles' => 'Boolean'
    );
    
    private static $defaults = array(
        'LinkImages' => 1,
        'LinkTitles' => 1,
        'HeadingTag' => 'h4',
        'DateFormat' => 'j F Y',
        'ShowMeta' => 'All',
        'ShowImage' => 'All',
        'ShowSummary' => 'All',
        'ShowContent' => 'None',
        'ShowDateIcon' => 1,
        'ShowButtons' => 1,
        'ShowTitles' => 1,
        'ImageLinksTo' => 'Item',
        'TitleLinksTo' => 'Item',
        'ImageAlignment' => 'None'
    );
    
    private static $extensions = array(
        'ListSourceExtension'
    );
    
    private static $required_themed_css = array(
        'list-component'
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
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'ListComponentOptions',
                $this->i18n_singular_name(),
                array(
                    DropdownField::create(
                        'ShowImage',
                        _t('ListComponent.SHOWIMAGE', 'Show image'),
                        $this->dbObject('ShowImage')->enumValues()
                    ),
                    DropdownField::create(
                        'ShowMeta',
                        _t('ListComponent.SHOWMETADATA', 'Show metadata'),
                        $this->dbObject('ShowMeta')->enumValues()
                    ),
                    DropdownField::create(
                        'ShowSummary',
                        _t('ListComponent.SHOWSUMMARY', 'Show summary'),
                        $this->dbObject('ShowSummary')->enumValues()
                    ),
                    DropdownField::create(
                        'ShowContent',
                        _t('ListComponent.SHOWCONTENT', 'Show content'),
                        $this->dbObject('ShowContent')->enumValues()
                    ),
                    DropdownField::create(
                        'ImageLinksTo',
                        _t('ListComponent.IMAGELINKSTO', 'Image links to'),
                        $this->dbObject('ImageLinksTo')->enumValues()
                    ),
                    DropdownField::create(
                        'TitleLinksTo',
                        _t('ListComponent.TITLELINKSTO', 'Title links to'),
                        $this->dbObject('TitleLinksTo')->enumValues()
                    ),
                    DropdownField::create(
                        'ImageAlignment',
                        _t('ListComponent.IMAGEALIGNMENT', 'Image alignment'),
                        $this->dbObject('ImageAlignment')->enumValues()
                    ),
                    FieldGroup::create(
                        _t('ListComponent.IMAGEDIMENSIONS', 'Image dimensions'),
                        array(
                            TextField::create('ImageWidth', '')->setAttribute(
                                'placeholder',
                                _t('ListComponent.WIDTH', 'Width')
                            ),
                            LiteralField::create('ImageBy', '<i class="fa fa-times by"></i>'),
                            TextField::create('ImageHeight', '')->setAttribute(
                                'placeholder',
                                _t('ListComponent.HEIGHT', 'Height')
                            )
                        )
                    ),
                    DropdownField::create(
                        'ImageResize',
                        _t('ListComponent.IMAGERESIZE', 'Image resize'),
                        ImageTools::get_resize_methods()
                    )->setEmptyString(_t('ListComponent.NONE', 'None')),
                    DropdownField::create(
                        'HeadingTag',
                        _t('ListComponent.HEADINGTAG', 'Heading tag'),
                        $this->dbObject('HeadingTag')->enumValues()
                    ),
                    TextField::create(
                        'DateFormat',
                        _t('ListComponent.DATEFORMAT', 'Date format')
                    ),
                    TextField::create(
                        'ButtonLabel',
                        _t('ListComponent.BUTTONLABEL', 'Button label')
                    ),
                    CheckboxField::create(
                        'LinkTitles',
                        _t('ListComponent.LINKTITLES', 'Link titles')
                    ),
                    CheckboxField::create(
                        'LinkImages',
                        _t('ListComponent.LINKIMAGES', 'Link images')
                    ),
                    CheckboxField::create(
                        'ShowTitles',
                        _t('ListComponent.SHOWTITLES', 'Show titles')
                    ),
                    CheckboxField::create(
                        'ShowButtons',
                        _t('ListComponent.SHOWBUTTONS', 'Show buttons')
                    ),
                    CheckboxField::create(
                        'ShowDateIcon',
                        _t('ListComponent.SHOWDATEICON', 'Show date icon')
                    )
                )
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Populates the default values for the attributes of the receiver.
     */
    public function populateDefaults()
    {
        // Populate Defaults (from parent):
        
        parent::populateDefaults();
        
        // Populate Defaults:
        
        $this->ButtonLabel = _t('ListComponent.DEFAULTBUTTONLABEL', 'Read More');
    }
    
    /**
     * Event method called before the receiver is written to the database.
     */
    public function onBeforeWrite()
    {
        // Call Parent Event:
        
        parent::onBeforeWrite();
        
        // Fix Image Dimensions:
        
        $this->ImageWidth  = SilverWareTools::integer_or_null($this->ImageWidth);
        $this->ImageHeight = SilverWareTools::integer_or_null($this->ImageHeight);
    }
    
    /**
     * Answers a string of class names for the list wrapper.
     *
     * @return string
     */
    public function getWrapperClass()
    {
        return implode(' ', $this->getWrapperClassNames());
    }
    
    /**
     * Answers an array of class names for the list wrapper.
     *
     * @return array
     */
    public function getWrapperClassNames()
    {
        $classes = array('items');
        
        $classes[] = "meta-" . strtolower($this->ShowMeta);
        
        $classes[] = "image-" . strtolower($this->ShowImage);
        
        $classes[] = "summary-" . strtolower($this->ShowSummary);
        
        $classes[] = "content-" . strtolower($this->ShowContent);
        
        $classes[] = "image-align-" . strtolower($this->ImageAlignment);
        
        $classes[] = "titles-" . ($this->ShowTitles ? 'shown' : 'hidden');
        
        $classes[] = "buttons-" . ($this->ShowButtons ? 'shown' : 'hidden');
        
        return $classes;
    }
    
    /**
     * Answers the margin for list item images (if applicable).
     *
     * @return integer
     */
    public function getImageMargin()
    {
        if ($this->ImageAlignment != 'None') {
            
            if ($this->HasImageDimensions()) {
                return (integer) $this->ImageWidth;
            } else {
                return (integer) $this->config()->default_image_width;
            }
            
        }
        
        return 0;
    }
    
    /**
     * Answers a string of class names for the image links.
     *
     * @return string
     */
    public function getImageLinkClass()
    {
        return implode(' ', $this->getImageLinkClassNames());
    }
    
    /**
     * Answers an array of class names for the image links.
     *
     * @return array
     */
    public function getImageLinkClassNames()
    {
        $classes = array('image-link');
        
        if (!$this->ImageLinksTo || $this->ImageLinksTo == 'File') {
            $classes[] = "popup";
        }
        
        return $classes;
    }
    
    /**
     * Answers a string of class names for the title links.
     *
     * @return string
     */
    public function getTitleLinkClass()
    {
        return implode(' ', $this->getTitleLinkClassNames());
    }
    
    /**
     * Answers an array of class names for the title links.
     *
     * @return array
     */
    public function getTitleLinkClassNames()
    {
        $classes = array('title-link');
        
        if (!$this->TitleLinksTo || $this->TitleLinksTo == 'File') {
            $classes[] = "popup";
        }
        
        return $classes;
    }
    
    /**
     * Defines the properties of the receiver from the given data object.
     *
     * @param DataObject $object
     * @return ListComponent
     */
    public function setPropertiesFrom(DataObject $object)
    {
        // Define Style ID:
        
        $this->setStyleIDFrom($object);
        
        // Define Image Properties:
        
        $this->setImagePropertiesFrom($object);
        
        // Answer Receiver:
        
        return $this;
    }
    
    /**
     * Defines the image properties of the receiver from the given data object.
     *
     * @param DataObject $object
     * @return ListComponent
     */
    public function setImagePropertiesFrom(DataObject $object)
    {
        if ($object->hasExtension('ImageDefaultsExtension')) {
            
            $this->ImageWidth = $object->getDefaultThumbnailWidth();
            $this->ImageHeight = $object->getDefaultThumbnailHeight();
            $this->ImageResize = $object->getDefaultThumbnailResize();
            $this->ImageLinksTo = $object->getDefaultThumbnailLinksTo();
            $this->ImageAlignment = $object->getDefaultThumbnailAlignment();
            
        }
        
        return $this;
    }
    
    /**
     * Answers true if valid dimensions are defined for list item images.
     *
     * @return boolean
     */
    public function HasImageDimensions()
    {
        return ($this->ImageWidth > 0 && $this->ImageHeight > 0);
    }
    
    /**
     * Renders the list of items for the template.
     *
     * @return string
     */
    public function RenderListItems()
    {
        $output = array();
        
        if ($items = $this->getListItems()) {
            
            $data = array('Component' => $this);
            
            foreach ($items as $key => $item) {
                
                $data['IsFirst'] = ($key == 0);
                $data['IsLast']  = ($key == (count($items) - 1));
                
                $output[] = $item->RenderListItem($data);
                
            }
            
        }
        
        return implode("\n", $output);
    }
    
    /**
     * Answers true if metadata is to be shown in the template for the current list item.
     *
     * @param boolean $first
     * @return boolean
     */
    public function MetaShown($first = false)
    {
        return (($this->ShowMeta == 'First' && $first) || $this->ShowMeta == 'All');
    }
    
    /**
     * Answers true if the image is to be shown in the template for the current list item.
     *
     * @param boolean $first
     * @return boolean
     */
    public function ImageShown($first = false)
    {
        return (($this->ShowImage == 'First' && $first) || $this->ShowImage == 'All');
    }
    
    /**
     * Answers true if the summary is to be shown in the template for the current list item.
     *
     * @param boolean $first
     * @return boolean
     */
    public function SummaryShown($first = false)
    {
        if ($this->ContentShown($first)) {
            return false;
        }
        
        return (($this->ShowSummary == 'First' && $first) || $this->ShowSummary == 'All');
    }
    
    /**
     * Answers true if the content is to be shown in the template for the current list item.
     *
     * @param boolean $first
     * @return boolean
     */
    public function ContentShown($first = false)
    {
        return (($this->ShowContent == 'First' && $first) || $this->ShowContent == 'All');
    }
}

/**
 * An extension of the base component controller class for a list component.
 */
class ListComponent_Controller extends BaseComponent_Controller
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
